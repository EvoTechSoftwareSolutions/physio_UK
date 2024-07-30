function accept(id){
    event.preventDefault();
    // alert("Request " + id + " accepted");
    var f = new FormData();
    f.append("id", id);

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