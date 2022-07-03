"use strict";

document.addEventListener('DOMContentLoaded', () => {
  const runlines = document.querySelectorAll('[data-runline]');
  runlines.forEach(runline => {
    const runlineSwiper = new Swiper(runline.querySelector('.swiper'), {
      loop: true,
      centredSlides: true,
      slidesPerView: 3,
      speed: 5000,
      centeredSlides: true,
      spaceBetween: 42,
      autoplay: {
        delay: 5000
      },
      breakpoints: {
        768: {
          slidesPerView: 4,
          spaceBetween: 56
        },
        1280: {
          slidesPerView: 6,
          spaceBetween: 56
        },
        1600: {
          slidesPerView: 3.5 // centeredSlides: false,

        }
      }
    });
  });
});