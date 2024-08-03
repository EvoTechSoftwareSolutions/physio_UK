function accept(id){
    event.preventDefault();
    // alert("Request " + id + " accepted");
    var f = new FormData();
    f.append("id", id);
    f.append("act","acceptAppt");

    var r = new XMLHttpRequest();
  
    r.onreadystatechange = function () {
      if (r.readyState == 4) {
        if (r.responseText == "success") {
          Swal.fire({
            title: "Accepted",
            text: "Appointment accepted successfully",
            icon: "success",
          });
        } else {
          Swal.fire({
            title: "Error",
            text: r.responseText,
            icon: "warning",
          });
        }
      }
    };
  
    r.open("POST", "./Backend/backend.php", true);
    r.send(f);
}

function decline(id){
  event.preventDefault();
  // alert("Request " + id + " accepted");
  var f = new FormData();
  f.append("id", id);
  f.append("act","declineAppt");

  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      if (r.responseText == "success") {
        Swal.fire({
          title: "Declined",
          text: "Appointment declined successfully",
          icon: "success",
        });
      } else {
        Swal.fire({
          title: "Error",
          text: r.responseText,
          icon: "error",
        });
      }
    }
  };

  r.open("POST", "./Backend/backend.php", true);
  r.send(f);
}