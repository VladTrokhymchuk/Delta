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

    if (window && window.innerWidth < 1024) {
        if ($('#hamburger-button').hasClass('open')) {
            $("body").css('overflow', 'hidden');
        } else {
            $("body").css('overflow', 'unset');
        }
    }

});

$('li a').click(function () {
    animateHamburger();
    slideMenu(); 
    slideLogo();

    if (window && window.innerWidth < 1024) {
        if ($('li a').hasClass('open')) {
            $("body").css('overflow', 'hidden');
        } else {
            $("body").css('overflow', 'unset');
        }
    }
});