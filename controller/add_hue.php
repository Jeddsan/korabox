<?php
require "../include/connection.php";
$ip = $_REQUEST["ip"];
$username = $_REQUEST["username"];
if($ip==""||$username==""){
  $json = array("status"=>false,"message"=>"not_filled_out");
}else{
  $sql=mysqli_query($con,"INSERT INTO philipshue (ip,username) VALUES ('$ip','$username')");
  if($sql){
    $json = array("status"=>true,"message"=>"success_inserted");
  }else{
    $json = array("status"=>false,"message"=>"error_inserting");
  }
}
echo json_encode($json);
?>
