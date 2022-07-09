"use strict";

var swiper = new Swiper(".mySwiper", {
  effect: "fade",
  autoplay: {
    delay: 3000,
    disableOnInteraction: false
  }
});
gsap.utils.toArray("section").forEach(function (section) {
  let imagesHead = section.querySelectorAll('.front-parallax picture img');
  let images = section.querySelectorAll('.front-head__swip .swiper-slide img');
  gsap.fromTo(imagesHead, {
    y: "-6vh"
  }, {
    y: "6vh",
    scrollTrigger: {
      trigger: section,
      scrub: true,
      start: "top bottom" // markers: true,
      // snap: {
      //   snapTo: 0.5,
      //   duration: 1,
      //   ease: 'power4.inOut'
      // }

    },
    ease: 'none'
  });
  gsap.fromTo(images, {
    y: "-6vh"
  }, {
    y: "6vh",
    scrollTrigger: {
      trigger: section,
      scrub: true,
      start: "top bottom"
    },
    ease: 'none'
  });
});