import dhtreader
import threading
import new
import subprocess
import os
import tornado.ioloop
import tornado.web
import tornado.websocket
import MySQLdb
import time
from datetime import datetime
from pytz import timezone
import RPi.GPIO as GPIO ## Import GPIO library


def recedata():
	t,h = dhtreader.read(22,4)
	fmt = "%Y-%m-%d %H:%M"

	# Current time in UTC
	now_utc = datetime.now(timezone('UTC'))
	# Convert to US/Pacific time zone
	now_aus = now_utc.astimezone(timezone('Australia/Brisbane'))
	print now_aus.strftime(fmt)
	
	db = MySQLdb.connect("localhost","root","raspberry","temp_hum" )
	cursor = db.cursor()
	
	print("Connected to the DataBase")
	print("Temp: {0} *C, Hum: {1} %".format(t,h))
	
	sql = "INSERT INTO TEMP(TIME, \
         TEMP, HUM) \
         VALUES ('%s', '%f','%f' )" % \
         (now_aus.strftime(fmt), t, h)
	try:
	   # Execute the SQL command
	   cursor.execute(sql)
	   # Commit your changes in the database
	   db.commit()
	except:
	   # Rollback in case there is any error
	   db.rollback()
	   
	db.close()
	
	return (t,h)

def blink():
	for x in range(0, 5):
		GPIO.output(11,True) ## Turn on GPIO pin 7
		time.sleep(0.2)
		GPIO.output(11,False)
		time.sleep(0.2)
	
	
class WebSocketHandler(tornado.websocket.WebSocketHandler):
	
	def open(self):
		print "new client connected"
		GPIO.output(12,True)
		GPIO.output(13,False)
		
	def on_message(self, message):
		attemps = 0
		if message == "recedata":
			while attemps < 5:
				try:
					time.sleep(5)
					t,h = recedata()
					self.write_message("{0} *C,{1} %".format(t,h))
					attemps = 0
					blink()
					break;
				except TypeError:
					self.write_message("Error Connecting to the sensor, Try again?")
					attemps += 1
	
	def on_close(self):
		print "client Dissconnected"
		GPIO.output(12,False)
		GPIO.output(13,True)

application = tornado.web.Application([
	(r"/", WebSocketHandler),
])

if __name__== "__main__":
	dhtreader.init()
	application.listen(29876)
	GPIO.setmode(GPIO.BOARD) ## Use board pin numbering
	GPIO.setup(11, GPIO.OUT) 
	GPIO.setup(12, GPIO.OUT) 
	GPIO.setup(13, GPIO.OUT)
	GPIO.output(12,False)
	GPIO.output(13,True) 
	GPIO.output(12,False)
	print "Sensor Server has Started!"
	tornado.ioloop.IOLoop.instance().start()
