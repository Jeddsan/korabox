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
    <title>Philips Hue - Korabox</title>
</head>

<body>

    <div id="wrapper">

        <?php
        require "../include/navigation.php";
         ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Philips Hue</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color:#222222;color:#fff;">
                            <i class="fa fa-hdd-o"></i> Hue-Bridge
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                          <?php
                          $hue=false;
                          $sql_get_hue_bridge = mysqli_query($con,"SELECT * FROM philipshue LIMIT 1");
                          if(mysqli_num_rows($sql_get_hue_bridge)==1){
                            $hue=true;
                            while ($row=mysqli_fetch_object($sql_get_hue_bridge)) {
                              $ip = $row->ip;
                              $username = $row->username;
                            }
                            ?>
                            <h3 style="color:green;margin:0px;">Verbunden</h3>
                            <p><b>IP-Adresse</b>: <?php echo $ip ?></p>
                            <a class="btn btn-danger pull-right" onclick="confirmDialog('/controller/delete_hue.php')">Verbindung l&ouml;schen</a>
                            <?php
                          }else{
                            ?>
                            <h3 style="color:red;margin:0px;">Nicht verbunden</h3>
                            <p>Keine Bridge verbunden. Du kannst dich unten verbinden.</p>
                            <?php
                          }
                          ?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="col-sm-6">

                </div>
                <!-- /.col-lg-4 -->
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    if(!$hue){
                      ?>
                      <div class="panel panel-default" id="add_hue">
                          <div class="panel-heading" style="background-color:#222222;color:#fff;">
                              <i class="fa fa-hdd-o"></i> Hue Bridge hinzuf&uuml;gen
                          </div>
                          <!-- /.panel-heading -->
                          <div class="panel-body">
                            <form onsubmit="return addButtonListener()" method="post">
                              <input type="text" name="ip" class="form-control" id="hue_ip" autocomplete="off" placeholder="IP"><br>
                              <input type="submit" value="verifizieren" class="btn btn-default" id="hue_add_button">
                              <div class="progress" id="hue_progress" style="display:none;">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="background-color:#222222;width:100%">
                                </div>
                              </div>
                            </form>
                          </div>
                          <!-- /.panel-body -->
                      </div>
                      <!-- /.panel -->
                      <?php
                    }else{
                      ?>
                      <div class="panel panel-default" id="add_hue">
                          <div class="panel-heading" style="background-color:#222222;color:#fff;">
                              <i class="fa fa-lightbulb-o"></i> Lampen
                          </div>
                          <!-- /.panel-heading -->
                          <div class="panel-body">
                            <ul class="list-group">
                              <?php
                              require "../api/Hue.php";
                              $hue = new Hue($ip,$username);
                              $lamps = json_decode($hue->getAllLights(),true);
                              $total = count($lamps);
                              for($i=0;$i<$total;$i++){
                                if(isset($lamps[$i])){
                                  ?>
                                  <li class="list-group-item" style="overflow:hidden;">
                                    <?php echo $lamps[$i]["name"]; ?>
                                    <div class="pull-right">
                                      <?php
                                      if($lamps[$i]["state"]["on"]){
                                        ?>
                                        <button onclick="HueLightOff(<?php echo $i; ?>)" id="<?php echo "hue$i"; ?>" class="btn btn-success">EIN</button>
                                        <?php
                                      }else{
                                        ?>
                                        <button onclick="HueLightOn(<?php echo $i; ?>)" id="<?php echo "hue$i"; ?>" class="btn btn-danger">AUS</button>
                                        <?php
                                      }
                                      ?>
                                    </div>
                                  </li>
                                  <?php
                                }else{
                                  $total++;
                                }
                              }
                              ?>
                            </ul>
                          </div>
                          <!-- /.panel-body -->
                      </div>
                      <!-- /.panel -->
                      <?php
                    }
                    ?>
                </div>
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
    function HueLightOn(id){
      $.post("/api/philipshue/on.php?id="+id, function( data ) {
        $("#hue"+id).removeClass("btn-danger");
        $("#hue"+id).addClass("btn-success");
        $("#hue"+id).attr('onclick','HueLightOff('+id+')');
        $("#hue"+id).html("EIN");
      });
    }

    function HueLightOff(id){
      $.post("/api/philipshue/off.php?id="+id, function( data ) {
        $("#hue"+id).removeClass("btn-success");
        $("#hue"+id).addClass("btn-danger");
        $("#hue"+id).attr('onclick','HueLightOn('+id+')');
        $("#hue"+id).html("AUS");
      });
    }

    //Setup
    var hue_button_listener,i=0;
    function addButtonListener(){
      hue_button_listener = setInterval(function(){ checkButtonClickOnHue() }, 1000);
      i=0;
      return false;
    }
    function checkButtonClickOnHue(){
      i++;
      var ip = document.getElementById("hue_ip");
      $("#hue_add_button").hide();
      $("#hue_progress").show();
      var ip_value = ip.value;
      $(ip).prop('disabled','true');
      $.post("/api/philipshue/get_button_click.php?ip="+ip_value, function( data ) {
        try{
          var json = JSON.parse(data);
          if(json[0].hasOwnProperty('error')){
            if(i==1){
              loadNotification('','Bitte dr&uuml;cke jetzt den Button auf deiner Hue-Bridge, um den Zugriff zu gew&auml;hren.');
              console.log("show");
            }else{
              console.log("Waiting...");
            }
          }else{
            console.log("Detected");
            $.ajax({
              url: "/controller/add_hue.php",
              type: 'POST',
              data: {ip: ip_value, username: json[0].success.username},
            }).done(function(data) {
              loadNotification('Verbunden!','Die Hue-Bridge ist jetzt mit der Korabox verbunden und zur Verwendung bereit.');
              $("#add_hue").hide();
              setTimeout(function(){location.href="philipshue.php"} , 3000);
            });
            clearInterval(hue_button_listener);
          }
        }catch(e){
          clearInterval(hue_button_listener);
          $(ip).removeAttr('disabled');
          $("#hue_add_button").show();
          $("#hue_progress").hide();
          loadNotification('Internal Error','Etwas ist schief gelaufen. Probiere es nochmals.','danger');
        }
      });
      return false;
    }
    </script>

</body>

</html>
