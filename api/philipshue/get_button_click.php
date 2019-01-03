<?php
require "../Hue.php";
$ip = trim(htmlspecialchars($_REQUEST["ip"]));
echo Hue::requestUsername($ip);
?>
