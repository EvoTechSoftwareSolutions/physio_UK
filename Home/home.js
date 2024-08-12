let slideIndex = 0;
let slides = document.getElementsByClassName("slide--feedback");
let displaySlides = window.innerWidth <= 545 ? 1 : 2;

function plusSlides(n) {
    slideIndex += n;
    if (slideIndex < 0) {
        slideIndex = slides.length - displaySlides;
    } else if (slideIndex >= slides.length) {
        slideIndex = 0;
    }
    showSlides();
}

function showSlides() {
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        slides[i].style.opacity = 0;
    }

    for (let i = 0; i < displaySlides; i++) {
        let slideToShow = (slideIndex + i) % slides.length;
        slides[slideToShow].style.display = "block";
        setTimeout(() => {
            slides[slideToShow].style.opacity = 1;
        }, 50); // Slight delay for the opacity transition
    }
}

window.addEventListener('resize', function() {
    displaySlides = window.innerWidth <= 545 ? 1 : 2;
    showSlides();
});

showSlides(slideIndex);

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



  function clearForm() {
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('subject').value = '';
    document.getElementById('message').value = '';
}