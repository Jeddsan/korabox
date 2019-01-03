<?php
require "../include/connection.php";

$id = trim(htmlspecialchars($_REQUEST["id"]));

$sql_get_room_information = mysqli_query($con,"SELECT r.id, r.name AS room_name, f.name AS floor_name FROM rooms r, floors f WHERE f.id=r.floor AND r.id=$id ORDER BY floor_name ASC, room_name ASC");

while($row = mysqli_fetch_object($sql_get_room_information)){
  ?>
  <h3><?php echo $row->room_name; ?></h3>
  <p>Befindet sich im <b><?php echo $row->floor_name; ?></b></p>
  <?php
}
?>
