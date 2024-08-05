function bookAppt() {
  var date = document.getElementById("apptDate").value;
  var fname = document.getElementById("fname").value;
  var lname = document.getElementById("lname").value;
  var email = document.getElementById("email").value;
  var line1 = document.getElementById("line1").value;
  var line2 = document.getElementById("line2").value;
  var city = document.getElementById("city").value;
  var pcode = document.getElementById("pcode").value;
  var msg = document.getElementById("apptMsg").value;
  var treatment = document.getElementById("apptTrtmnt").value;

  var f = new FormData();

  f.append("date", date);
  f.append("fname", fname);
  f.append("lname", lname);
  f.append("email", email);
  f.append("line1", line1);
  f.append("line2", line2);
  f.append("city", city);
  f.append("pcode", pcode);
  f.append("msg", msg);
  f.append("tr", treatment);
  f.append("act","addAppt");

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
