<?php
require "../include/connection.php";
$sql_delete=mysqli_query($con,"DELETE FROM philipshue");
if($sql_delete){
  header("Location: /pages/philipshue.php?n=success");
}else{
  header("Location: /pages/philipshue.php?n=error");
}
?>
