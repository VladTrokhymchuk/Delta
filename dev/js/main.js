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
    slideMenu(); /*attaching click handler to the burger button*/
    slideLogo();

    var isOpen = $('#hamburger-button').hasClass('open');
    $(this)
        .attr('aria-expanded', isOpen ? 'true' : 'false')
        .attr('aria-label', isOpen ? 'Закрити меню' : 'Відкрити меню');

    if (window && window.innerWidth < 1024) {
        $("body").css('overflow', isOpen ? 'hidden' : 'unset');
    }

});

$('li a').click(function () {
    // Клік по пункту меню завжди ЗАКРИВАє меню (а не перемикає стан),
    // інакше на новій сторінці/десктопі лого отримує клас --open і зникає.
    $('#hamburger-button')
        .removeClass('open')
        .attr('aria-expanded', 'false')
        .attr('aria-label', 'Відкрити меню');
    $('.navbar').removeClass('open');
    $('.header__logo').removeClass('header__logo--open');

    if (window && window.innerWidth < 1024) {
        $("body").css('overflow', 'unset');
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const dateInputStart = document.querySelector('input[name="start-date"]');
    if (dateInputStart) {
        dateInputStart.addEventListener('click', function () {
            this.showPicker && this.showPicker(); // сучасні браузери
        });
    }

    const dateInputEnd = document.querySelector('input[name="end-date"]');
    if (dateInputEnd) {
        dateInputEnd.addEventListener('click', function () {
            this.showPicker && this.showPicker(); // сучасні браузери
        });
    }
});


import { documentLoaded } from './modules/documentLoaded.js';
document.addEventListener('DOMContentLoaded', documentLoaded());