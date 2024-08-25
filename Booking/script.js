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
    f.append("act", "addAppt");

    var r = new XMLHttpRequest();

    r.onreadystatechange = function () {
      if (r.readyState == 4) {
        console.log("Req Returned");
        console.log(r.responseText);
        if (isValidJSON(r.responseText)) {
          var json = JSON.parse(r.responseText);
          if(json.msg == "success"){
            clearForm();
            Swal.fire({
              title: "Successfully Requested",
              text: "You can pay your appointment fee either now or on your visit.",
              icon: "success",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#3c3d3c",
              confirmButtonText: "Pay Now",
              cancelButtonText: "I'll pay on visit",
            }).then((result) => {
              if (!result.isConfirmed) {
                Swal.fire({
                  title: "Success",
                  text: "You will recieve a confirmation email after your request is approved.",
                  icon: "success",
                });
              }else{
                window.location.href = json.url;
              }
            });
          }else{

          }
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

function isValidJSON(jsonString) {
  try {
    JSON.parse(jsonString);
    return true;
  } catch (e) {
    return false;
  }
}

function clearForm() {
  document.getElementById("apptDate").value = "";
  document.getElementById("fname").value = "";
  document.getElementById("lname").value = "";
  document.getElementById("email").value = "";
  document.getElementById("line1").value = "";
  document.getElementById("line2").value = "";
  document.getElementById("city").value = "";
  document.getElementById("pcode").value = "";
  document.getElementById("apptMsg").value = "";
  document.getElementById("apptTrtmnt").value = "";
}
