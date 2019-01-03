<?php
require "../include/connection.php";
require "../api/MyStrom.php";
$all_mystrom_switches = mysqli_query($con,"SELECT * FROM mystrom");
while($row=mysqli_fetch_object($all_mystrom_switches)){
  $switch = new MyStrom($row->ip);
  $power = $switch->report()["power"];
  $sql_add = mysqli_query($con,"INSERT INTO mystrom_data (ip,power,date) VALUES ('$row->ip','$power','".(new DateTime("now"))->format('Y-m-d H:i:s')."')");
}
?>
