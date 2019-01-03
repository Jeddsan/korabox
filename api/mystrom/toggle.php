<?php
require "../MyStrom.php";
$ip = $_GET["ip"];
$switch = new MyStrom($ip);
echo $switch->toggle();
?>
