<?php
$q = ($_GET['q']);

$host = 'localhost';
$user = 'root';
$pass = 'raspberry';
$dbname = 'temp_hum';

// Create connection
$con = mysqli_connect($host, $user, $pass, $dbname);
// Check connection
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

$sql="SELECT * FROM TEMP";
$result = mysqli_query($con,$sql);

while( $row = mysqli_fetch_array($result)) {
    $temp_array[] = $row[$q]; // Inside while loop
}

echo json_encode($temp_array);

mysql_close($con);
?>