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
    <title>MyStrom - Korabox</title>
</head>

<body>

    <div id="wrapper">

        <?php
        require "../include/navigation.php";
         ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">MyStrom</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color:#5BB401;color:#fff;">
                            <i class="fa fa-toggle-on"></i> Switches
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
                                    ?> <a onclick="confirmDialog('/controller/delete_mystrom.php?id=<?php echo $row->id; ?>')"><button class="btn btn-danger">Löschen</button></a>
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
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color:#5BB401;color:#fff;">
                            <i class="fa fa-toggle-on"></i> Neuen Switch hinzufügen
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                          <form action="/controller/add_mystrom.php" method="post">
                            <input type="text" name="name" class="form-control" placeholder="Name"><br>
                            <input type="text" name="ip" class="form-control" placeholder="IP"><br>
                            <input type="submit" value="speichern" class="btn btn-default">
                          </form>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color:#5BB401;color:#fff;">
                            <i class="fa fa-toggle-on"></i> Statistiken
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                          <div id="stat1"></div>
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
    Morris.Area({
      element: 'stat1',
      data: [
        <?php
        $sql_get_data=mysqli_query($con,"SELECT * FROM mystrom_data WHERE ip='192.168.1.140' AND date > '".(new DateTime("-24 hours"))->format('Y-m-d H:i:s')."'");
        while($row=mysqli_fetch_object($sql_get_data)){
          ?>
          { t: "<?php echo ($row->date); ?>", p: <?php echo $row->power; ?>},
          <?php
        }
        ?>
      ],
      xkey: 't',
      ykeys: 'p',
      labels: ['Watt']
    });

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
