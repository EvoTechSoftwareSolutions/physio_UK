let items = document.querySelectorAll('.slider .list .item');
let next = document.getElementById('next');
let prev = document.getElementById('prev');
let thumbnails = document.querySelectorAll('.thumbnail .item');

// config param
let countItem = items.length;
let itemActive = 0;
// event next click
next.onclick = function(){
    itemActive = itemActive + 1;
    if(itemActive >= countItem){
        itemActive = 0;
    }
    showSlider();
}
//event prev click
prev.onclick = function(){
    itemActive = itemActive - 1;
    if(itemActive < 0){
        itemActive = countItem - 1;
    }
    showSlider();
}
// auto run slider
let refreshInterval = setInterval(() => {
    next.click();
}, 5000)
function showSlider(){
    // remove item active old
    let itemActiveOld = document.querySelector('.slider .list .item.active');
    let thumbnailActiveOld = document.querySelector('.thumbnail .item.active');
    itemActiveOld.classList.remove('active');
    thumbnailActiveOld.classList.remove('active');

    // active new item
    items[itemActive].classList.add('active');
    thumbnails[itemActive].classList.add('active');
    setPositionThumbnail();

    // clear auto time run slider
    clearInterval(refreshInterval);
    refreshInterval = setInterval(() => {
        next.click();
    }, 5000)
}
function setPositionThumbnail () {
    let thumbnailActive = document.querySelector('.thumbnail .item.active');
    let rect = thumbnailActive.getBoundingClientRect();
    if (rect.left < 0 || rect.right > window.innerWidth) {
        thumbnailActive.scrollIntoView({ behavior: 'smooth', inline: 'nearest' });
    }
}

// click thumbnail
thumbnails.forEach((thumbnail, index) => {
    thumbnail.addEventListener('click', () => {
        itemActive = index;
        showSlider();
    })
})


var swiper = new Swiper(".slide-content", {
    slidesPerView: 1, // Default for small screens
    breakpoints: {
        480: { slidesPerView: 1 }, // 1 image for very small screens
        768: { slidesPerView: 2 }, // 2 images for medium screens
        1024: { slidesPerView: 3 } // 3 images for larger screens
    }
});




var swiper = new Swiper(".slide-content", {
    slidesPerView: 4,
    spaceBetween: 30,
    loop: true,
    centerSlide:'true',
    fade:'true',
grabCursor: 'true',
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },

    navigation:{
        nextEl : ".swiper-button-next",
        prevEl : ".swiper-button-prev",
    },

    breakpoints:{
        0:{
            slidesPerView:1,

        },
        520:{
            slidesPerView:2,
            
        }  ,
        950:{
            slidesPerView:3 ,
            
        } ,
         1250:{
            slidesPerView:4,
            
        } ,
    }
  });















  