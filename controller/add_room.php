<?php
require "../include/connection.php";
$name = $_REQUEST["name"];
$floor = $_REQUEST["floor"];
if($name==""||$floor==""){
  $json = array("status"=>false,"message"=>"not_filled_out");
}else{
  $sql=mysqli_query($con,"INSERT INTO rooms (name,floor) VALUES ('$name','$floor')");
  if($sql){
    $json = array("status"=>true,"message"=>"success_inserted");
  }else{
    $json = array("status"=>false,"message"=>"error_inserting");
  }
}
echo json_encode($json);
?>
