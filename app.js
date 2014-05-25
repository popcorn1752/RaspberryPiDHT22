window.onload=function(){

	
	var datagetBtn = document.getElementById('getdata');
	var dissconnectBtn = document.getElementById('dissconnect');
	var startBtn = document.getElementById('sstart');
	var stopBtn = document.getElementById('sstop');
	var runBtn = document.getElementById('rrun');
	var rec = false;

	var temp = document.getElementById('list-temp');
	var tempitem = document.getElementById('temp-item');

	var hum = document.getElementById('list-hum');
	var humitem = document.getElementById('hum-item');

	var conn = document.getElementById('connlabel');
 	var polllab = document.getElementById('poll');
	var tempdiv = document.getElementById('temp');
	var humdiv = document.getElementById('hum');

	var form = document.getElementById('login');
	var formip = document.getElementById('ip');	
	var formport = document.getElementById('port');	
	
	var connection_status = false;	
	var wait_cache = false;
	conn.innerHTML = "Disconnected!";
	var timerr = "";
	
		form.onsubmit = function(e) {
			e.preventDefault();
			wait_cache = true;
			waait();
			return false;
		};
		waait();

		function waait(){
			if(wait_cache === false){
				setTimeout(waait(), 50);
				return;
			}

		var ws = new WebSocket("ws://" + getIP() + ":" + getPort());	
	
		ws.onopen = function(){
			conn.innerHTML = "Connected to: " + getIP() + ":" + getPort();
			formip.value = "";
			formport.value = "";
			connection_status = true;
		};

		ws.onmessage = function(msg){
			var res = msg.data.split(',')
			tempitem.innerHTML += "Time: " + time_date() + res[0] + '<br>';
			humitem.innerHTML += "Time: " + time_date() + res[1] + '<br>';
			tempdiv.scrollTop = tempdiv.scrollHeight;
			humdiv.scrollTop = humdiv.scrollHeight;
			sqlget_temp();
		};

		datagetBtn.onclick = function(){
			if(connection_status == true){
				ws.send("recedata");
			}
			else{
			alert("You are not connected to server!");
			}
		};
		startBtn.onclick = function(){
			if(connection_status == true){
				rec = true;
				timerr = time_poll();
				poll();
			}
			else{
			alert("You are not connected to server!");
			}
		};
		stopBtn.onclick = function(){
			if(connection_status == true){
				rec = false;
				poll();
			}
			else{
			alert("You are not connected to server!");
			}
		};
		dissconnectBtn.onclick = function(){
			ws.close();
		};
//		runBtn.onclick = function(){
//			sqlget();
//		};
		ws.onclose = function(){
			conn.innerHTML = "Disconnected!";
			connection_status = false;
		};
		function poll(){
			if(rec == true){
				ws.send("recedata");
				polllab.innerHTML = " Polling sensor: True";
				
				//alert("done");
					setTimeout(function(){
					    poll();
					}, timerr);
			}
			else{
			polllab.innerHTML = " Polling sensor: False";	
			}
			
		};
};	
		function time_poll(){
			var one_dig_regex = /^([3-9]{1})$/ 
			var two_dig_regex = /^([0-9]{2})$/
			var timee = prompt("How many minutes would you like there to be between polls?","Must be a minimum of 3");
			if(timee.length == 1){
				if(one_dig_regex.test(timee)){
					timee *= 60000
					return timee;
				}
				else{
					alert("Please ensure you enter a minimum of 3 minutes");
					time_poll();
				}
			}
			else{
				if(two_dig_regex.test(timee)){
					timee *= 60000
					return timee;
				}
				else{
					alert("Please ensure you enter a minimum of 3 minutes");
					time_poll();
				}
			}	
				
		};
		function getIP(){
			ip = formip.value;
			
			return ip;
		};
		function getPort(){
			port = formport.value;
			
			return port;
		};
		function time_date(){
			var a_p = "";
			var d = new Date();
			
			var curr_hour = d.getHours();
			
			if (curr_hour < 12)
			   {
			   a_p = "AM";
			   }
			else
			   {
			   a_p = "PM";
			   }
			if (curr_hour == 0)
			   {
			   curr_hour = 12;
			   }
			if (curr_hour > 12)
			   {
			   curr_hour = curr_hour - 12;
			   }
			
			var curr_min = d.getMinutes();
			
			return(curr_hour + ":" + curr_min + " " + a_p + " - ");
		};
		
		
};
