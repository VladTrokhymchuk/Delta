"use strict";

document.addEventListener('DOMContentLoaded', () => {
  const device = {
    isMob: document.body.dataset.isMob === 'true',
    isTab: document.body.dataset.isTab === 'true' && window.matchMedia('(min-width: 767px)').matches,
    isPc: document.body.dataset.isPc === 'true' && window.matchMedia('(min-width: 1280px)').matches
  };
  const isNeedElvtr = device.isTab || device.isPc;
  const controller = new ScrollMagic.Controller();
  const header = document.querySelector('.header');
  const wrapper = document.querySelector('.page-template-page-services');
  const elvtrSection = document.querySelector('[data-serv-elvtr-section]');
  const elvtrTrack = document.querySelector('[data-serv-elvtr-track]');
  const elvtrSlider = document.querySelector('.serv-elvtr-section .swiper');
  const elvtrFloors = [...document.querySelectorAll('[data-serv-elvtr-item]')];
  const elvtrFloorIndicators = document.querySelectorAll('[data-floor-indicator]');
  const elvtrHeaderTitle = document.querySelector('[data-elvtr-header-title]');
  const ELVTR_SLIDER_SPEED = 1000;
  let isElvtrOpenAnimPlayed = false;
  let isElvtrBottomReached = false;
  let isNeedToGoBackToTheElvtr = false;
  let isLastSlide = false;
  let isSliderTouchDown = false;

  const setFloorState = fIndex => {
    const {
      bg: color,
      title
    } = elvtrFloors[fIndex].dataset;
    elvtrTrack.style.setProperty('--elvtr-track-color', color);
    elvtrFloorIndicators.forEach(indicator => indicator.classList.remove('active'));
    elvtrFloorIndicators[fIndex].classList.add('active');
    elvtrHeaderTitle.textContent = title;
  };

  const goExitAnim = () => {
    document.body.classList.add('elvtr-exit-anim');
  };

  const backExitAnim = () => {
    document.body.classList.remove('elvtr-exit-anim');
    setTimeout(() => {
      isElvtrBottomReached = false;
      isNeedToGoBackToTheElvtr = false;
    }, ELVTR_SLIDER_SPEED);
  };

  const checkAndApplyExitAnim = isScrollDown => {
    if (isScrollDown && isLastSlide && isElvtrBottomReached) {
      goExitAnim();
    }

    if (!isScrollDown && isLastSlide && isElvtrBottomReached && isNeedToGoBackToTheElvtr) {
      backExitAnim();
    }
  };

  let elvtrSwiper;

  const initElvtrSwiper = () => {
    elvtrSwiper = new Swiper(elvtrSlider, {
      direction: "vertical",
      slidesPerView: 1,
      spaceBetween: 0,
      mousewheel: true,
      speed: ELVTR_SLIDER_SPEED,
      preventInteractionOnTransition: true,
      on: {
        init: function () {
          wrapper.classList.add('elvtr-down');
        },
        slideChangeTransitionEnd: function () {
          isLastSlide = this.activeIndex === this.slides.length - 1; // User slided to first slide

          if (this.activeIndex === 0) {
            elvtrSlider.classList.remove('avalible'); // Leave slider and scroll up (slide from down)

            if (wrapper.matches('.elvtr-up')) {
              wrapper.classList.add('elvtr-down');
              wrapper.classList.remove('elvtr-up');
              elvtrTrack.classList.add('fixed');
              $('html, body').animate({
                scrollTop: $('.serv-elvtr-section').offset().top
              }, 0);
              elvtrTrack.classList.remove('fixed');
            }
          } // Release scroll down after last slide


          if (isLastSlide) {
            elvtrSlider.classList.remove('avalible');
            wrapper.classList.remove('elvtr-down');
            wrapper.classList.add('elvtr-up');
            setTimeout(() => {
              isElvtrOpenAnimPlayed = true;
              isElvtrBottomReached = true;
            }, ELVTR_SLIDER_SPEED * 0.3);
          }
        },
        slideChange: function () {
          setFloorState(this.activeIndex);
        },
        // Tablet slider actions 
        touchStart: function () {
          isSliderTouchDown = true;
        },
        touchEnd: function () {
          isSliderTouchDown = false;
        }
      }
    });
  }; // For PC Only listener


  document.addEventListener('wheel', evt => {
    checkAndApplyExitAnim(evt.deltaY > 0);
  });
  let st = 0,
      prevSt = 0;
  /* 
      For Tab Only
      NOTE Listener currently is not responsive between devices!
      works only if device is tablet only
   */

  document.addEventListener('scroll', () => {
    if (isElvtrOpenAnimPlayed && wrapper.matches('.elvtr-up') && device.isTab) {
      st = $(document).scrollTop();
      checkAndApplyExitAnim(st > prevSt);
      prevSt = st;
    }
  });

  const destroyElvtrSwiper = () => {
    elvtrSwiper.destroy(true, true);
  };

  const elvtrPcScene = new ScrollMagic.Scene({
    triggerElement: elvtrSection,
    triggerHook: 0.001,
    duration: 10
  }).on('enter leave', ({
    type,
    scrollDirection
  }) => {
    isLastSlide = elvtrSwiper.activeIndex === elvtrSwiper.slides.length - 1; // Come to elvtr from up

    if (type == 'enter') {
      if (!isLastSlide) {
        elvtrSlider.classList.add('avalible');
      }
    } // Back to elvtr from down


    if (scrollDirection === 'REVERSE' && isLastSlide && isElvtrOpenAnimPlayed) {
      elvtrSlider.classList.add('avalible');
      setTimeout(() => {
        isElvtrOpenAnimPlayed = false;
      }, ELVTR_SLIDER_SPEED);
    }
  });
  const elvtrMobileScenes = elvtrFloors.map((floor, floorIndex) => {
    return new ScrollMagic.Scene({
      triggerElement: floor,
      triggerHook: 0.25,
      duration: $(floor).height()
    }).on('enter leave', () => {
      setFloorState(floorIndex);
    });
  }); // Elvtr inside scene
  // ===========================================

  const insideElvtrClasses = ['inside-elvtr'];
  const insideElvtrDuration = isNeedElvtr ? $(elvtrSlider).height() : $(elvtrSlider).height() + $(header).height() * 2;
  const insideElvtrOffset = isNeedElvtr ? -5 : -$(header).height() * 2;
  new ScrollMagic.Scene({
    triggerElement: elvtrSlider,
    triggerHook: 0,
    duration: insideElvtrDuration,
    offset: insideElvtrOffset
  }).on('enter leave', ({
    type
  }) => {
    if (type == 'enter') {
      document.body.classList.add(...insideElvtrClasses);
    } else {
      document.body.classList.remove(...insideElvtrClasses);
    }
  }) // .addIndicators()
  .addTo(controller); // Exit elvtr anim scene
  // ==========================================

  const orderFormSection = document.querySelector('.serv-order-section');
  const mobileExitElvtrAnimScene = new ScrollMagic.Scene({
    triggerElement: orderFormSection,
    triggerHook: 1
  }).on('enter', () => {
    $('html, body').animate({
      scrollTop: $(orderFormSection).offset().top
    }, ELVTR_SLIDER_SPEED);
  }); // .addTo(controller)
  // .addIndicators()

  const pcExitElvtrAnimScene = new ScrollMagic.Scene({
    triggerElement: elvtrSlider,
    triggerHook: 0.49,
    offset: $(window).height() / 2
  }).on('leave', ({
    scrollDirection
  }) => {
    if (scrollDirection == 'REVERSE') {
      if (isNeedToGoBackToTheElvtr) {
        if (!isElvtrBottomReached) {
          isElvtrBottomReached = true;
        }
      } else {
        setTimeout(() => {
          isNeedToGoBackToTheElvtr = true;
        }, ELVTR_SLIDER_SPEED / 2);
      }
    }
  });

  const initMobileScenes = () => {
    elvtrMobileScenes.forEach(scene => scene.addTo(controller));
    mobileExitElvtrAnimScene.addTo(controller);
  };

  const removeMobileScenes = () => {
    elvtrMobileScenes.forEach(scene => scene.remove());
    document.body.classList.remove(...insideElvtrClasses);
    mobileExitElvtrAnimScene.remove();
  };

  const initPcScenes = () => {
    initElvtrSwiper();
    elvtrPcScene.addTo(controller);
    pcExitElvtrAnimScene.addTo(controller);
  };

  const removePcScenes = () => {
    destroyElvtrSwiper();
    elvtrPcScene.remove();
    pcExitElvtrAnimScene.remove();
  }; // Init elvtr handlers on page load


  if (isNeedElvtr) {
    initPcScenes();
  } else {
    initMobileScenes();
  }

  ; // Handlers on resize 

  window.matchMedia('(min-width: 767px)').addEventListener('change', evt => {
    if (evt.matches) {
      removeMobileScenes();
      initPcScenes();
    } else {
      removePcScenes();
      initMobileScenes();
    }
  }); // Services list
  // =====================

  const servicesItems = document.querySelectorAll('[data-serv-list-item');
  servicesItems === null || servicesItems === void 0 ? void 0 : servicesItems.forEach(sItem => {
    sItem.addEventListener('mouseenter', () => {
      if (window.matchMedia('(min-width: 1280px)').matches) {
        const wrap = sItem.querySelector('.serv-list__item__desc');
        const content = wrap.querySelector('span');
        const wrapH = $(wrap).height();
        const contH = $(content).height();

        if (wrapH < contH) {
          $(content).animate({
            'margin-top': -contH + wrapH
          }, 3000);
        }

        ;
      }
    });
    sItem.addEventListener('mouseleave', () => {
      const wrap = sItem.querySelector('.serv-list__item__desc');
      const content = wrap.querySelector('span');
      setTimeout(() => {
        $(content).stop();
        $(content).animate({
          'margin-top': 0
        }, 0);
      }, 500);
    });
  });
});