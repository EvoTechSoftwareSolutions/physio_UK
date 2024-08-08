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
