<?php
require "../include/connection.php";
require "../api/MyStrom.php";
$name=mysqli_real_escape_string($con,trim($_POST["name"]));
$ip=mysqli_real_escape_string($con,trim($_POST["ip"]));
if($name==""||$ip==""){
  header("Location: /pages/mystrom.php?n=error");
}else{
  $switch = new MyStrom($ip);
  $sql_check = mysqli_query($con,"SELECT * FROM mystrom WHERE ip='$ip'");
  if($switch->check()&&mysqli_num_rows($sql_check)==0){
    $sql_insert = mysqli_query($con,"INSERT INTO mystrom (name,ip) VALUES ('$name','$ip')");
    if($sql_insert){
      header("Location: /pages/mystrom.php?n=success");
    }else{
      header("Location: /pages/mystrom.php?n=error");
    }
  }else{
    header("Location: /pages/mystrom.php?n=error");
  }
}
?>
