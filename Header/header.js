function toggle() {
  document.getElementById("dropdown").classList.toggle("show");
  document.getElementById("drpItem").classList.toggle("hide");
  document.getElementById("drpItem1").classList.toggle("hide");
  document.getElementById("drpItem2").classList.toggle("hide");
}

function openNav() {
  document.getElementById("mySidepanel").style.width = "60%";
}

/* Set the width of the sidebar to 0 (hide it) */
function closeNav() {
  document.getElementById("mySidepanel").style.width = "0";
}



const navLinkEls = document.querySelectorAll(".header--nav");
const windowPathname = window.location.pathname;

navLinkEls.forEach((navLinkEL) => {
  if (navLinkEL.href.includes(windowPathname)) {
    navLinkEL.classList.add("active");
  }
});

// navLinkEls.forEach(navLinkEL=>{
// const navLinkPathname=new URL(navLinkEL.href).pathname;
 
// if((windowPathname===navLinkPathname) || (windowPathname==='../Home/index.php' && navLinkPathname==='/')){
//   navLinkEL.classList.add('active');
// }
// });
