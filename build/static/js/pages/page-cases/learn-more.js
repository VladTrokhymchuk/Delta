"use strict";

$(document).ready(function () {
  $.fn.showPopup = function () {
    if (window.innerWidth >= 1024) {
      this.on('mouseenter', function () {
        let l_link = $('#l-link');
        let coord = $(this).offset();
        let href = $(this).find('a.box').attr("href");
        let cleft = coord.left - 18;
        l_link.css('transform', 'translateX(' + cleft + 'px)');
        l_link.attr("href", href);
      });
    }

    if (window.innerWidth >= 1600) {
      this.on('mouseenter', function () {
        let l_link = $('#l-link');
        let coord = $(this).offset();
        let href = $(this).find('a.box').attr("href");
        let cleft = coord.left - 177.5;
        l_link.css('transform', 'translateX(' + cleft + 'px)');
        l_link.attr("href", href);
      });
    }

    if (window.innerWidth >= 1900) {
      this.on('mouseenter', function () {
        let l_link = $('#l-link');
        let coord = $(this).offset();
        let href = $(this).find('a.box').attr("href");
        let cleft = coord.left - 250;
        l_link.css('transform', 'translateX(' + cleft + 'px)');
        l_link.attr("href", href);
      });
    }
  };

  $("#hoveredElement li").showPopup();
});