function bookAppt() {
  var date = document.getElementById("apptDate").value;
  var fname = document.getElementById("fname").value;
  var lname = document.getElementById("lname").value;
  var email = document.getElementById("email").value;
  var msg = document.getElementById("apptMsg").value;
  var treatment = document.getElementById("apptTrtmnt").value;

  var f = new FormData();

  f.append("date", date);
  f.append("fname", fname);
  f.append("lname", lname);
  f.append("email", email);
  f.append("msg", msg);
  f.append("tr", treatment);

  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      if (r.responseText == "success") {
        Swal.fire({
          title: "Success",
          text: "You will recieve a confirmation email when your request is approved",
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

  r.open("POST", "../Backend/backend.php", true);
  r.send(f);
}
