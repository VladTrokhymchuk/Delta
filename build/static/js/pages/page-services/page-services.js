"use strict";

document.addEventListener('DOMContentLoaded', function () {
  // Services elevator
  var currentDevice = {
    isMob: document.body.dataset.isMob === 'true',
    isTab: document.body.dataset.isTab === 'true' && window.matchMedia('(min-width: 768px)').matches,
    isPc: document.body.dataset.isPc === 'true' && window.matchMedia('(min-width: 1280px)').matches
  };
  var mobileFloorHeight = $(window).innerHeight();
  var controller = new ScrollMagic.Controller();
  var elvtrTrack = document.querySelector('[data-serv-elvtr-track]');
  var elvtrHolder = document.querySelector('[data-serv-elvtr-holder]');
  var elvtrInner = document.querySelector('[data-serv-elvtr-inner]');
  var elvtrFloors = document.querySelectorAll('[data-serv-elvtr-item]');
  var elvtrHeader = document.querySelector('[data-elvtr-header]');
  var elvtrFloorIndicators = document.querySelectorAll('[data-floor-indicator]');
  var elvtrHeaderTitle = document.querySelector('[data-elvtr-header-title]');
  var mobileDownScenes = [];
  var mobileUpScenes = [];
  var mobScenes = [];
  var pcScenes = [];

  var setFloorState = function setFloorState(fIndex) {
    var _elvtrFloors$fIndex$d = elvtrFloors[fIndex].dataset,
        color = _elvtrFloors$fIndex$d.bg,
        title = _elvtrFloors$fIndex$d.title;
    elvtrTrack.style.setProperty('--elvtr-track-color', color);
    elvtrFloorIndicators.forEach(function (indicator) {
      return indicator.classList.remove('active');
    });
    elvtrFloorIndicators[fIndex].classList.add('active');
    elvtrHeaderTitle.textContent = title;
  };

  elvtrFloors.forEach(function (floor, floorIndex) {
    // mobileDownScenes[floorIndex] = new ScrollMagic.Scene({
    //     triggerElement: floor,
    //     triggerHook: 0.95,
    //     duration: $(floor).height()
    // })
    // .on('enter', ({ scrollDirection }) => {
    //     if (scrollDirection == 'FORWARD') {
    //         setFloorState(floorIndex);
    //         $('html, body').animate({
    //             scrollTop: $(floor).offset().top
    //         });
    //     } 
    // })
    // mobileUpScenes[floorIndex] = new ScrollMagic.Scene({
    //     triggerElement: floor,
    //     triggerHook: 0.05,
    //     duration: $(floor).height()
    // })
    // .on('enter', ({ scrollDirection }) => {
    //     if (scrollDirection == 'REVERSE') {
    //         setFloorState(floorIndex);
    //         $('html, body').animate({
    //             scrollTop: $(floor).offset().top
    //         });
    //     } 
    // })
    mobScenes[floorIndex] = new ScrollMagic.Scene({
      triggerElement: elvtrTrack,
      triggerHook: 0.1,
      duration: mobileFloorHeight,
      offset: mobileFloorHeight * floorIndex
    }).on('enter leave', function (_ref) {
      var type = _ref.type;

      if (type == 'enter') {
        setFloorState(floorIndex);
        $(elvtrInner).css('transform', "translateY(".concat($(window).height() * -floorIndex, "px)"));
      }
    }); // .addIndicators()

    pcScenes[floorIndex] = new ScrollMagic.Scene({
      triggerElement: elvtrTrack,
      triggerHook: 0.5,
      duration: $(window).height(),
      offset: $(window).height() * floorIndex
    }).on('enter leave', function (_ref2) {
      var type = _ref2.type;

      if (type == 'enter') {
        setFloorState(floorIndex);
        $(elvtrInner).css('transform', "translateY(".concat($(window).height() * -floorIndex, "px)"));
      }
    }); // .addIndicators()
  });

  var initMobileScenes = function initMobileScenes() {
    // elvtrTrack.style.height = mobileFloorHeight * elvtrFloors.length;
    $(elvtrTrack).height(mobileFloorHeight * elvtrFloors.length); // mobileDownScenes.forEach((scene) => scene.addTo(controller));
    // mobileUpScenes.forEach((scene) => scene.addTo(controller));

    mobScenes.forEach(function (scene) {
      return scene.addTo(controller);
    });
  };

  var removeMobileScenes = function removeMobileScenes() {
    // mobileDownScenes.forEach((scene) => scene.remove());
    // mobileUpScenes.forEach((scene) => scene.remove());
    mobScenes.forEach(function (scene) {
      return scene.remove();
    });
  };

  var initPcScenes = function initPcScenes() {
    // elvtrTrack.style.height = ;
    $(elvtrTrack).height($(window).height() * (elvtrFloors.length + 1));
    pcScenes.forEach(function (scene) {
      return scene.addTo(controller);
    });
  };

  var removePcScenes = function removePcScenes() {
    pcScenes.forEach(function (scene) {
      return scene.remove(controller);
    });
  };

  if (currentDevice.isMob) {
    initMobileScenes();
  }

  if (currentDevice.isPc || currentDevice.isTab) {
    initPcScenes();
  }

  window.matchMedia('(min-width: 768px)').addEventListener('change', function (evt) {
    if (evt.matches) {
      removeMobileScenes();
      initPcScenes();
    } else {
      removePcScenes();
      initMobileScenes();
    }
  }); // Services list
  // =====================

  var servicesItems = document.querySelectorAll('[data-serv-list-item');
  servicesItems === null || servicesItems === void 0 ? void 0 : servicesItems.forEach(function (sItem) {
    sItem.addEventListener('mouseenter', function () {
      if (window.matchMedia('(min-width: 1280px)').matches) {
        var wrap = sItem.querySelector('.serv-list__item__desc');
        var content = wrap.querySelector('span');
        var wrapH = $(wrap).height();
        var contH = $(content).height();

        if (wrapH < contH) {
          $(content).animate({
            'margin-top': -contH + wrapH
          }, 3000);
        }

        ;
      }
    });
    sItem.addEventListener('mouseleave', function () {
      var wrap = sItem.querySelector('.serv-list__item__desc');
      var content = wrap.querySelector('span');
      setTimeout(function () {
        $(content).stop();
        $(content).animate({
          'margin-top': 0
        }, 0);
      }, 500);
    });
  });
});