var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function () {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}


function sendmassage() {

  var f = new FormData();
  f.append("name", document.getElementById("name").value);
  f.append("email", document.getElementById("email").value);
  f.append("phone", document.getElementById("phone").value);
  f.append("subject", document.getElementById("subject").value);
  f.append("message", document.getElementById("message").value);




  var r = new XMLHttpRequest();
  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      if (r.responseText == "Message Sent successfully") {
        document.getElementById("name").value = "";
        document.getElementById("email").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("subject").value = "";
        document.getElementById("message").value = "";
        swal("Message sent", "We'll get back to you soon", "success");


      } else {
        swal("Try Again", r.responseText, "error");
      }

      document.getElementById("btn").disabled = false;
      document.getElementById("btn").classList.remove("disable");

    }
  }

  r.open("POST", "../mail/sendEmailProcess.php", true);
  r.send(f);
  document.getElementById("btn").disabled = true;
  document.getElementById("btn").classList.add("disable");


}