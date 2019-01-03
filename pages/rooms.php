<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require "../include/connection.php";
    require "../include/lib.php";
    require "../include/head.php";
    ?>
    <title>Dashboard - Korabox</title>
</head>

<body>

    <div id="wrapper">

        <?php
        require "../include/navigation.php";
         ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Raumverwaltung</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background-color:#E8EEF1;color:#000;">
                        <i class="fa fa-home"></i> R&auml;ume
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                      <ul class="list-group">
                        <?php
                        $sql_rooms=mysqli_query($con,"SELECT r.id, r.name AS room_name, f.name AS floor_name FROM rooms r, floors f WHERE f.id=r.floor ORDER BY floor_name ASC, room_name ASC");
                        $last_floor="";
                        while($row=mysqli_fetch_object($sql_rooms)){
                          if($row->floor_name!=$last_floor){
                            ?>
                            <li class="list-group-item" style="overflow:hidden;">
                              <div class="row">
                                <div class="col-sm-9">
                                  <b><?php echo $row->floor_name; ?></b>
                                </div>
                                <div class="col-sm-3" style="text-align:right;cursor:pointer;">
                                  <a onclick="addNewRoom()"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                              </div>
                            </li>
                            <?php
                          }
                          ?>
                            <li class="list-group-item" onclick="openDetailsRoom(<?php echo $row->id; ?>)" style="overflow:hidden;cursor:pointer;">
                              <?php echo $row->room_name; ?>
                            </li>
                          <?php
                          $last_floor=$row->floor_name;
                        }
                        ?>
                      </ul>
                    </div>
                    <!-- /.panel-body -->
                </div>
              </div>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Modal -->
    <div id="addNewRoom" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Neuer Raum hinzuf&uuml;gen</h4>
          </div>
          <div class="modal-body">
            <form method="post" onsubmit="return addNewRoomSave()">
              <label for="add_room_name">Name</label><br>
              <input type="text" id="add_room_name" name="add_room_name" class="form-control"><br>
              <label for="add_room_floor">Etage</label><br>
              <select name="add_room_floor" id="add_room_floor" class="form-control">
                <?php
                $sql_get_floors = mysqli_query($con,"SELECT * FROM floors");
                while($row=mysqli_fetch_object($sql_get_floors)){
                  ?><option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option><?php
                }
                ?>
              </select><br>
              <input type="submit" value="hinzuf&uuml;gen" class="btn btn-primary">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">abbrechen</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal -->
    <div id="roomDetails" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Raumdetails</h4>
          </div>
          <div class="modal-body" id="detailRoomBody">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">abbrechen</button>
          </div>
        </div>

      </div>
    </div>

    <?php
    require "../include/footer.php";
    ?>

</body>

</html>
