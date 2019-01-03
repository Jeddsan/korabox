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
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel" style="background-color:#5BB401;color:#fff;">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-toggle-on fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php
                                    $sql_count_mystrom=mysqli_query($con,"SELECT COUNT(name) AS count FROM mystrom");
                                    while($row=mysqli_fetch_object($sql_count_mystrom)){
                                      echo $row->count;
                                    }
                                     ?></div>
                                    <div>MyStrom Switches</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color:#5BB401;color:#fff;">
                            <i class="fa fa-toggle-on"></i> MyStrom Switches
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                          <ul class="list-group">
                            <?php
                            $sql_mystrom=mysqli_query($con,"SELECT * FROM mystrom ORDER BY name ASC");
                            while($row=mysqli_fetch_object($sql_mystrom)){
                              ?>
                                <li class="list-group-item" style="overflow:hidden;"><?php echo $row->name; ?>
                                  <div class="pull-right">
                                    <?php
                                    $switch = new MyStrom($row->ip);
                                    if($switch->getRelay()){
                                      ?>
                                      <button onclick="MyStromToggle('<?php echo $row->ip; ?>')" id="<?php echo str_replace(".","",$row->ip); ?>" class="btn btn-success">EIN</button>
                                      <?php
                                    }else{
                                      ?>
                                      <button onclick="MyStromToggle('<?php echo $row->ip; ?>')" id="<?php echo str_replace(".","",$row->ip); ?>" class="btn btn-danger">AUS</button>
                                      <?php
                                    }
                                    ?>
                                  </div>
                                </li>
                              <?php
                            }
                            ?>
                          </ul>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php
    require "../include/footer.php";
    ?>
    <script>
    function MyStromToggle(ip){
      $.post("/api/mystrom/toggle.php?ip="+ip, function( data ) {
        ip = ip.replace(/\./g,'');
        if(data=="1"){
          $("#"+ip).removeClass("btn-danger");
          $("#"+ip).addClass("btn-success");
          $("#"+ip).html("EIN");
        }else{
          $("#"+ip).removeClass("btn-success");
          $("#"+ip).addClass("btn-danger");
          $("#"+ip).html("AUS");
        }
        console.log(data);
      });
    }
    </script>

</body>

</html>
