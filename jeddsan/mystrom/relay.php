<?php
if (!isset($_SERVER['PHP_AUTH_USER'])||!isset($_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="Korabox"');
    header('HTTP/1.0 401 Unauthorized');
    $json = array("status"=>false,"message"=>"unauthorized");
}else{
  require "../../include/connection.php";
  require "../../api/MyStrom.php";
  require "../Token.php";
  $token = new Token($_SERVER['PHP_AUTH_PW']."",$con);
  if($token->isValid()){
    /*
    MyStrom relay
    */
    $name = mysqli_real_escape_string($con,trim($_GET["name"]));
    if($name==""){
      $json = array("status"=>false,"message"=>"no_name_given");
    }else{
      $sql_mystrom=mysqli_query($con,"SELECT * FROM mystrom WHERE name LIKE '$name'");
      if($sql_mystrom){
        if(mysqli_num_rows($sql_mystrom)==1){
          while($row=mysqli_fetch_object($sql_mystrom)){
            $ip = $row->ip;
          }
          $switch = new MyStrom($ip);
          $json = array("status"=>true,"message"=>"data_output","relay"=>$switch->getRelay());
        }else{
          $json = array("status"=>false,"message"=>"switch_not_found");
        }
      }else{
        $json = array("status"=>false,"message"=>"internal_error");
      }
    }
    /*
    My Strom relay Ende
    */
  }else{
    $json = array("status"=>false,"message"=>"wrongtoken");
  }
}
echo json_encode($json);
?>
