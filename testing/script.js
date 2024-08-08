// Get the current URL path
const currentPath = window.location.pathname;

// Select all navigation links
const navLinks = document.querySelectorAll('.nav-link');

// Loop through each nav link and add the 'active' class if it matches the current path
navLinks.forEach(link => {
    if (link.getAttribute('href') === currentPath) {
        link.classList.add('active');
    }
});
