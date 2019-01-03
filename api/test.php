<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "MyStrom.php";
require "Hue.php";
$strom = new MyStrom("192.168.1.142");
//var_dump($strom->report());

//qlFbxjqbhpeJZC0JtisyD5n-TdHoqB40ZXfjB-JW
//On success: [{"success":{"username":"qlFbxjqbhpeJZC0JtisyD5n-TdHoqB40ZXfjB-JW"}}]

$hue = new Hue("192.168.1.132","qlFbxjqbhpeJZC0JtisyD5n-TdHoqB40ZXfjB-JW");
echo $hue->getAllLights();
$id = $hue->getLightIdByName("Nachttisch");

//echo $hue->getLight(3);

//echo $hue->setLightDim($id,$_GET["state"]);
//echo Hue::requestUsername("192.168.1.132");
?>
