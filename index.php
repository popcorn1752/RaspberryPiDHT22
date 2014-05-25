<html>

<head>
<script src="app.js"></script>
<script src="Chart.js"></script>
<link rel="stylesheet" type="text/css"  href="style.css">
</head>

<body>
<div id="page-wrapper">


	<div id="top">
		 <p>Please Enter Ip and Port Number of Sensor Server and Press Connect</p>
			<form id="login" action="#" method="post" onsubmit="press()">
				<textarea id="ip" placeholder="Enter IP" required></textarea>
				<textarea id="port" placeholder="Enter port" required></textarea>
				<br>
				<button id="sub" type="submit">Connect</button>
				<br>
				<label id="connlabel"></label>
				<label id="poll"></label>
				

			
			</form>
	</div>

	<div id="left">
		<div id="temphum">
					<p id="templabel" class="lab">Tempature: </p>
				<div id="temp">
					<ul type ="none" id="list-temp">
						<li id= "temp-item"></li>
					</ul>
				</div>

					<p id="humlabel" class="lab">Humidity: </p>
				<div id="hum">
					<ul type="none" id="list-hum">
						<li id="hum-item"></li>
					</ul>
				</div>
	
	
		</div>
		<div id="button">
			<button class="but" id="sstart">Start</button>
			<button class="but" id="sstop">Stop</button>
			<button class="but" id="getdata">Getdata</button>
			<button class="but" id="dissconnect">Disconnect</button>
			<button class="but" id="rrun" onclick="sqlget_temp()">Draw Table</button>
		</div>
	</div>
	<p id="graphiclabel" class="lab">Graphics:  </p>
	<div id="graphic">
		<canvas id="graph">
			
		</canvas>
	</div>


</div>
</body>
<script>
function draww(temp, hum, time){
	
		var lineChartData = {
			labels : time,
			datasets : [
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					data : temp
				},
				{
					fillColor : "rgba(135,202,100,0.5)",
					strokeColor : "rgba(135,202,100,1)",
					pointColor : "rgba(135,202,100,1)",
					pointStrokeColor : "#fff",
					data : hum
				}
			]
		}
	var graph = document.getElementById('graph');
	var h = document.getElementById('graphic');
	graph.height = h.clientHeight - 20;
	graph.width = h.clientWidth;
	
	var myLine = new Chart(document.getElementById("graph").getContext("2d")).Line(lineChartData,{
				scaleOverride : true,
				scaleSteps : 60,
				scaleStepWidth : 0.9,
				scaleStartValue : 10,
				bezierCurve: false,
				scaleOverlay: true,
	});
	}
	
function sqlget_temp(){
		str = "TEMP";
		var tmyObj = [];
		if (window.XMLHttpRequest) {
		    xmlhttp=new XMLHttpRequest();
		   }
		  xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				tmyObj = JSON.parse(xmlhttp.response);
			//	alert("temp");
				sqlget_hum(tmyObj);
		    }
		  }
		  xmlhttp.open("GET","myscript.php?q="+str,true);
		  xmlhttp.send();
		  xmlhttp.close();
		};
function sqlget_hum(temp){
		//alert("hum");
		str = "HUM";
		var hmyObj = [];
		if (window.XMLHttpRequest) {
		    xmlhttp=new XMLHttpRequest();
		   }
		  xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				//alert(xmlhttp.response);
				hmyObj = JSON.parse(xmlhttp.response);
			//	alert("hum");
				sqlget_time(temp, hmyObj);
		    }
		  }
		  xmlhttp.open("GET","myscript.php?q="+str,true);
		  xmlhttp.send();
		};
function sqlget_time(temp, hum){
		//alert("hum");
		str = "TIME";
		var timemyObj = [];
		if (window.XMLHttpRequest) {
		    xmlhttp=new XMLHttpRequest();
		   }
		  xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				timemyObj = JSON.parse(xmlhttp.response);
			//	alert("time");
				draww(temp,hum,timemyObj);
		    }
		  }
		  xmlhttp.open("GET","myscript.php?q="+str,true);
		  xmlhttp.send();
		};
</script>
</html>

