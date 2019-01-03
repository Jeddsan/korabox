<?php
require "../include/connection.php";
$id=mysqli_real_escape_string($con,trim($_GET["id"]));
if($id==""){
  header("Location: /pages/mystrom.php?n=error");
}else{
  $sql_delete=mysqli_query($con,"DELETE FROM mystrom WHERE id='$id'");
  if($sql_delete){
    header("Location: /pages/mystrom.php?n=success");
  }else{
    header("Location: /pages/mystrom.php?n=error");
  }
}
?>
