<?php
require "../../include/connection.php";
require "../Hue.php";
$id = trim(htmlspecialchars($_REQUEST["id"]));
if($id==""){
  $json = array("status"=>false,"message"=>"not_filled_out");
}else{
  $sql_hue=mysqli_query($con,"SELECT * FROM philipshue");
  if($sql_hue){
    if(mysqli_num_rows($sql_hue)==1){
      while($row=mysqli_fetch_object($sql_hue)){
        $ip = $row->ip;
        $username = $row->username;
      }
      $hue = new Hue($ip,$username);
      $hue->setLightState($id,0);
      $json = array("status"=>true,"message"=>"turned_on");
    }else{
      $json = array("status"=>false,"message"=>"not_configured");
    }
  }else{
    $json = array("status"=>false,"message"=>"internal_error");
  }
}
echo json_encode($json);
?>
