<?php
require "../include/connection.php";
$token=mysqli_real_escape_string($con,$_GET["token"]);
$sql_insert=mysqli_query($con,"INSERT INTO token (token) VALUES ('$token')");
if($sql_insert){
  echo json_encode(array("status"=>true));
}else{
  echo json_encode(array("status"=>false));
}
?>
