<?php
if (!isset($_SERVER['PHP_AUTH_USER'])||!isset($_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="Korabox"');
    header('HTTP/1.0 401 Unauthorized');
    $json = array("status"=>false,"message"=>"unauthorized");
}else{
  require "../../include/connection.php";
  require "../../api/Hue.php";
  require "../Token.php";
  $token = new Token($_SERVER['PHP_AUTH_PW']."",$con);
  if($token->isValid()){
    /*
    Philips Hue dim
    */
    $name = mysqli_real_escape_string($con,trim($_GET["name"]));
    $bri = intval(mysqli_real_escape_string($con,trim($_GET["bri"])));
    if($name==""){
      $json = array("status"=>false,"message"=>"no_name_given");
    }else{
      $sql_hue=mysqli_query($con,"SELECT * FROM philipshue");
      if($sql_hue){
        if(mysqli_num_rows($sql_hue)==1){
          while($row=mysqli_fetch_object($sql_hue)){
            $ip = $row->ip;
            $username = $row->username;
          }
          $hue = new Hue($ip,$username);
          if($hue->getLightIdByName($name)){
            $hue->setLightDim($hue->getLightIdByName($name),$bri);
            $json = array("status"=>true,"message"=>"lamp_dimmed");
          }else{
            $json = array("status"=>false,"message"=>"lamp_not_found");
          }
        }else{
          $json = array("status"=>false,"message"=>"not_configured");
        }
      }else{
        $json = array("status"=>false,"message"=>"internal_error");
      }
    }
    /*
    Philips Hue dim Ende
    */
  }else{
    $json = array("status"=>false,"message"=>"wrongtoken");
  }
}
echo json_encode($json);
?>
