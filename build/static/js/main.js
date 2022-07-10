"use strict";

// borger
function animateHamburger() {
  $('#hamburger-button').toggleClass('open');
  /*animation from burger to X*/
  // $("body").css('overflow', 'hidden');
}

function slideMenu() {
  $('.navbar').toggleClass('open');
  /*animation for slide down menu*/
}

function slideLogo() {
  $('.header__logo').toggleClass('header__logo--open');
  /*animation for slide down menu*/
}

$('#hamburger-button').click(function () {
  // console.log('trying');
  animateHamburger();
  slideMenu();
  /*attaching click handler to the burger button*/

  slideLogo();

  if (window && window.innerWidth < 1024) {
    if ($('#hamburger-button').hasClass('open')) {
      $("body").css('overflow', 'hidden');
    } else {
      $("body").css('overflow', 'unset');
    }
  }
}); //animate
// gsap.from(".header", {
//     duration: 1.2,
//     ease: "power2.out",
//     top: "-100%"
// });
// const showAnim = gsap.from('header', {
//     yPercent: -100,
//     paused: true,
//     duration: 0.2
// }).progress(1);
// ScrollTrigger.create({
//     start: "top top",
//     end: 99999,
//     onUpdate: (self) => {
//         self.direction === -1 ? showAnim.play() : showAnim.reverse()
//     }
// });