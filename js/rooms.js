function addNewRoom() {
  $('#addNewRoom').modal('show');
}

function openDetailsRoom(id) {
  $('#roomDetails').modal('show');
  $.ajax({
    url: "../views/get_room_details.php",
    method: "POST",
    data: {
      id: id
    }
  }).done(function(data) {
    $("#detailRoomBody").html(data);
  }).fail(function(data) {
    loadNotification("Fehler",
      "Etwas ist schief gelaufen", "danger",
      "error");
  });
}

function addNewRoomSave() {
  var name = $("#add_room_name").val();
  var floor = $("#add_room_floor").val();
  $.ajax({
    url: "../controller/add_room.php",
    method: "POST",
    data: {
      name: name,
      floor: floor
    }
  }).done(function(data) {
    var json = JSON.parse(data);
    if (json.status) {
      loadNotification("Hinzugef&uuml;gt", "Der Raum wurde erfolgreich im " +
        floor + " hinzugef&uuml;gt", "success", "success");
      $("#add_room_name").val("");
      $("#add_room_floor").val("");
      $('#addNewRoom').modal('hide');
    } else {
      loadNotification("Fehler",
        "Der Raum konnte nicht hinzugef&uuml;gt werden!", "danger",
        "error");
      console.log(json);
    }
  }).fail(function(data) {
    loadNotification("Fehler",
      "Der Raum konnte nicht hinzugef&uuml;gt werden!", "danger",
      "error");
  });
  return false;
}
