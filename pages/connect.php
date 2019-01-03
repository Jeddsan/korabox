<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require "../include/connection.php";
    require "../include/lib.php";
    require "../include/head.php";
    ?>
    <title>Verbinden zu Jeddsan</title>
</head>

<body>

    <div id="wrapper">

        <?php
        require "../include/navigation.php";
         ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Verbinden zu Jeddsan</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <?php
            $sql_get_logins=mysqli_query($con,"SELECT * FROM token");
              if(mysqli_num_rows($sql_get_logins)>=1){
                ?>
                <table class="table table-striped table-responsive">
                  <thead>
                    <th>
                      ID
                    </th>
                    <th>
                      Token
                    </th>
                  </thead>
                  <tbody>
                <?php
                while($row=mysqli_fetch_object($sql_get_logins)){
                  ?>
                  <tr>
                    <td><?php echo $row->id; ?></td>
                    <td><?php echo $row->token; ?></td>
                  </tr>
                  <?php
                }
                ?>
                  </tbody>
                </table>
                <?php
              }
            ?>
            <div style="width:300px;text-align:center;margin-left:auto;margin-right:auto;">
              <?php
              if(mysqli_num_rows($sql_get_logins)>=1){
                ?>
                <button class="btn btn-primary" id="connect" onclick="connectToJeddsan()">Neuen Account verbinden</button>
                <?php
              }else{
                ?>
                <button class="btn btn-primary" id="connect" onclick="connectToJeddsan()">Jetzt verbinden</button>
                <?php
              }
              ?>
              <br><br>
              <p id="pin_beschreibung" style="display:none;">Bitte gebe jetzt folgenden PIN unter <a href="https://www.jeddsan.ch/pin/" target="_blank">https://www.jeddsan.ch/pin/</a> an.</p>
              <h2 id="pin" style="display:none;"></h2><br>
              <button class="btn btn-success" id="pin_finish" onclick="get_login()" style="display:none;">Ich habe den PIN eingegeben</button>
            </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <?php
    require "../include/footer.php";
    ?>
    <script>
    var id=0,pin,secret;
    function connectToJeddsan(){
      $("#connect").prop("disabled",true);
      $.ajax({
        url: "https://www.jeddsan.ch/assistent/connect/",
        dataType: 'jsonp',
        type: 'POST',
        data: {ip: "<?php echo $_SERVER['SERVER_NAME'] ?>", port: "<?php echo $_SERVER['SERVER_PORT'] ?>"},
        jsonpCallback: 'json'
      }).done(function(data) {
        if(data.status){
          id=data.id;
          console.log(data.id);
          console.log("----");
          console.log(data.message);
          load_pin();
        }else{
          loadNotification('Info','Bitte rufe diese Seite über eine externe IP auf.');
        }
      });
    }
    function load_pin(){
      $.ajax({
        url: "https://www.jeddsan.ch/pin/api/get_pin.php",
        dataType: 'jsonp',
        type: 'GET',
        jsonpCallback:'json'
      }).done(function(data) {
        pin=data.pin;
        secret=data.secret;
        $("#pin").html(pin).show();
        $("#pin_beschreibung").show();
        $("#pin_finish").show();
      });
    }
    function get_login(){
      $.ajax({
        url: "https://www.jeddsan.ch/pin/api/get_login.php?r="+pin+"&m=j&s="+secret,
        dataType: 'jsonp',
        type: 'GET',
        jsonpCallback:'json'
      }).done(function(data) {
        console.log(data);
        if(data.token==null){
          loadNotification('Kein Token erhalten','KoraBox hat leider keinen Token erhalten.<br>Hast du den PIN auf der Seite eingegeben.','danger');
        }else{
          token=data.token;
          $.ajax({
            url: "../controller/add_connection.php?token="+token,
            dataType: 'json',
            type: 'GET',
          }).done(function(data) {
            if(data.status){
              $.ajax({
                url: "https://www.jeddsan.ch/assistent/connect/set_token.php?id="+id+"&token="+token,
                dataType: 'jsonp',
                type: 'GET',
                jsonpCallback:'json'
              }).done(function(data) {
                loadNotification('Verbindung erfolgreich','Du kannst nun diverse Dienste mit Kora von Jeddsan steuern.','success');
              });
            }else{
              loadNotification('Fehler','Du kannst nun diverse Dienste mit Kora von Jeddsan steuern.','danger');
            }
          });
        }
      });
    }
    </script>

</body>

</html>
