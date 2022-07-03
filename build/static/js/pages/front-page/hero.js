"use strict";

document.addEventListener('DOMContentLoaded', () => {
  const device = {
    isMob: document.body.dataset.isMob === 'true',
    isTab: document.body.dataset.isTab === 'true' && window.matchMedia('(min-width: 767px)').matches,
    isPc: document.body.dataset.isPc === 'true' && window.matchMedia('(min-width: 1280px)').matches,
    isFullHd: document.body.dataset.isPc === 'true' && window.matchMedia('(min-width: 1900px)').matches
  };
  const controller = new ScrollMagic.Controller();
  const heroSection = document.querySelector('[data-hero-section]');
  const heroHolder = document.querySelector('[data-hero-holder]');
  const TL_SPEED = 0.7; // 0.5

  const steps = {
    main: {
      self: document.querySelector('[data-hero-step="main"]'),
      head: document.querySelector('[data-hero-head]'),
      headLogo: document.querySelector('[data-hero-head-logo]'),
      title: document.querySelector('[data-hero-title]'),
      titlePrefix: document.querySelector('[data-hero-title-prefix]'),
      titleEnd: document.querySelector('[data-hero-title-end]'),
      flyingText: document.querySelector('[data-hero-flying-text]'),
      flyingTextFake: document.querySelector('[data-hero-flying-text-fake]'),
      textCont: document.querySelector('[data-hero-cont]'),
      bottomCont: document.querySelector('[data-hero-bottom]'),
      facts: document.querySelector('[data-hero-facts]')
    },
    person: document.querySelector('[data-hero-step="person"]'),
    bullets: document.querySelector('[data-hero-step="bullets"]'),
    string: document.querySelector('[data-hero-step="string"]'),
    menu: document.querySelector('[data-hero-step="menu"]')
  };
  const canvasDomElem = document.querySelector('[data-hero-canvas-place]');
  const wh = $(window).innerHeight();
  const ww = $(window).innerWidth();
  const commonSettings = {
    duration: TL_SPEED
  };
  let textTemporaryScale = 1;

  if (ww >= 1000) {
    textTemporaryScale = 0.5;
  }

  if (device.isFullHd) {
    textTemporaryScale = 0.7;
  }

  const homePageTimeline = gsap.timeline({
    paused: true
  }).add('start').to(steps.main.title, { ...commonSettings,
    marginLeft: device.isFullHd ? () => {
      const leftOffset = steps.main.title.getBoundingClientRect().left;
      const diff = ww / 2 - leftOffset;
      return diff - $(steps.main.title).width() / 2;
    } : 0
  }, 'step main 0.5').to(steps.main.titlePrefix, { ...commonSettings,
    width: 0
  }, 'step main 0.5').to(steps.main.titleEnd, { ...commonSettings,
    width: 'auto'
  }, 'step main 0.5').to(steps.main.textCont, { ...commonSettings,
    autoAlpha: 0
  }, 'step main 0.5').to(steps.main.bottomCont, { ...commonSettings,
    autoAlpha: 0,
    height: 0
  }, 'step main 0.5').to(steps.main.headLogo, { ...commonSettings,
    autoAlpha: 0
  }, 'step main 0.5').add('step main 0.5').to(steps.main.facts, { ...commonSettings,
    autoAlpha: 1
  }, 'step main 1').to(steps.main.flyingText, { ...commonSettings,
    x: device.isFullHd ? () => {
      const leftOffset = steps.main.flyingTextFake.getBoundingClientRect().left;
      const diff = ww / 2 - leftOffset;
      return diff - $(steps.main.flyingTextFake).width() / 3;
    } : 0,
    y: 0,
    scale: textTemporaryScale
  }, 'step main 1').add('step main 1').to(steps.main.head, { ...commonSettings,
    autoAlpha: 0
  }, 'step text').to(steps.main.title, { ...commonSettings,
    autoAlpha: 0
  }, 'step text').to(steps.main.facts, { ...commonSettings,
    autoAlpha: 0
  }, 'step text').to(steps.main.flyingText, { ...commonSettings,
    x: 0,
    y: () => {
      const diff = wh / 2 > steps.main.flyingTextFake.getBoundingClientRect().top ? wh / 2 - steps.main.flyingTextFake.getBoundingClientRect().top : 0;
      return diff - $(steps.main.flyingTextFake).height() / 3;
    },
    scale: 1
  }, 'step text').add('step text').to(heroHolder, {
    duration: TL_SPEED * 2,
    y: -wh
  }, 'step person').add('step person').to(steps.person, { ...commonSettings,
    autoAlpha: 0
  }, 'step bullets').to(steps.bullets, { ...commonSettings,
    autoAlpha: 1
  }, 'step bullets').to(heroHolder, {
    duration: TL_SPEED * 2,
    y: -wh * 3
  }, 'step bullets').to(heroHolder, {
    duration: TL_SPEED * 3
  }, 'step bullets').add('step bullets').to(steps.string, { ...commonSettings,
    autoAlpha: 1
  }, 'step single string').add('step single string').to(steps.string, { ...commonSettings,
    autoAlpha: 0
  }, 'step menu 0.5').add('step menu 0.5').to(heroHolder, { ...commonSettings,
    y: -wh * 4
  }, 'step menu 1').to(steps.menu, {
    duration: TL_SPEED * 3,
    autoAlpha: 1
  }, 'step menu 1').add('step menu 1');
  let currProgress = 0;
  let sDirection = 'FORWARD'; // const scrollFinished = () => {
  //     if (currProgress > 0) {
  //         if (sDirection == 'FORWARD') {
  //             homePageTimeline.tweenTo( homePageTimeline.nextLabel() );
  //         }
  //         if (sDirection == 'REVERSE') {
  //             homePageTimeline.tweenTo( homePageTimeline.previousLabel() );
  //         }
  //     }
  //     action.pause = true;
  // }
  // const throttledBodyScrollEnd = throttle(() => {
  //     document.body.style.backgroundColor = "white";
  //     if (scrollTimer != -1) clearTimeout(scrollTimer);
  //     scrollTimer = window.setTimeout(() => {
  //         scrollFinished()
  //     }, 1000);
  // }, 500)

  const throttledHeroAnimHandler = throttle(() => {
    homePageTimeline.progress(currProgress);
  }, 20);
  setTimeout(() => {
    const baseHeroScene = new ScrollMagic.Scene({
      triggerElement: heroSection,
      triggerHook: 0,
      duration: $(window).height() * 9,
      offset: 5
    }).on('progress', ({
      progress,
      scrollDirection
    }) => {
      currProgress = progress;
      sDirection = scrollDirection;
      requestAnimationFrame(() => {
        throttledHeroAnimHandler(); // Play back correctly to start point

        if (currProgress == 0 && sDirection == 'REVERSE') {
          homePageTimeline.tweenTo('start');
        }
      });
    }).addTo(controller); // .addIndicators()

    const canvasFadeInOutScene = new ScrollMagic.Scene({
      triggerElement: heroSection,
      triggerHook: 0.5,
      duration: $(window).height() / 2,
      offset: $(window).height() * 9
    }).on('progress', ({
      progress
    }) => {
      gsap.to(canvasDomElem, {
        autoAlpha: 1 - 1 * progress
      });
    }).addTo(controller); // .addIndicators()
    // const mainScreenScene = new ScrollMagic.Scene({
    //     triggerElement: heroSection,
    //     triggerHook: 1,
    //     offset: wh + 5,
    // }).on('enter leave', ({ type }) => {
    //     homePageTimeline
    //             // .timeScale(Math.abs(getScrollSpeed()))
    //             .tweenTo(type == 'enter' ? 'step main 1' : 'start')
    //             // .then(() => {
    //             //     homePageTimeline.timeScale(1)
    //             // });
    //     if (type == 'enter') {
    //         // window.heroAnimationFromToPrecent(0, 20);
    //         // window.animationProgressHandler(30);
    //     } else {
    //         // window.animationProgressHandler(1);
    //     }
    // }).addTo(controller)
    // const onlyTextLeftScene = new ScrollMagic.Scene({
    //     triggerElement: heroSection,
    //     triggerHook: 1,
    //     offset: (wh * 1.5) + 5,
    // }).on('enter leave', ({ type }) => {
    //     homePageTimeline.tweenTo(type == 'enter' ? 'step text' : 'step main 1')
    // }).addTo(controller)
    // const personScene = new ScrollMagic.Scene({
    //     triggerElement: heroSection,
    //     triggerHook: 1,
    //     offset: (wh * 2) + 5,
    // }).on('enter leave', ({ type }) => {
    //         homePageTimeline.tweenTo(type == 'enter' ? 'step person' : 'step text');
    // }).addTo(controller)
    // const bulletsAppearScene = new ScrollMagic.Scene({
    //     triggerElement: heroSection,
    //     triggerHook: 1,
    //     offset: (wh * 2.5) + 5,
    // }).on('enter leave', ({ type }) => {
    //         homePageTimeline.tweenTo(type == 'enter' ? 'step bullets' : 'step person');
    // }).addTo(controller)
    // const oneStringScene = new ScrollMagic.Scene({
    //     triggerElement: heroSection,
    //     triggerHook: 1,
    //     offset: (wh * 4.5) + 5,
    // }).on('enter leave', ({ type }) => {
    //         homePageTimeline.tweenTo(type == 'enter' ? 'step single string' : 'step bullets');
    // }).addTo(controller)
    // const oneStringHideScene = new ScrollMagic.Scene({
    //     triggerElement: heroSection,
    //     triggerHook: 1,
    //     offset: (wh * 5.2) + 5,
    // }).on('enter leave', ({ type }) => {
    //         homePageTimeline.tweenTo(type == 'enter' ? 'step menu 0.5' : 'step single string');
    // }).addTo(controller)
    // const menuScene = new ScrollMagic.Scene({
    //     triggerElement: heroSection,
    //     triggerHook: 1,
    //     offset: (wh * 5.5) + 5,
    // }).on('enter leave', ({ type }) => {
    //         homePageTimeline.tweenTo(type == 'enter' ? 'step menu 1' : 'step menu 0.5');
    // }).addTo(controller)
  }, 1000); // TODO Rewrite it to some better page load event listener 
});