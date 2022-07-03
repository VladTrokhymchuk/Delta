<?php // Template name: Front page

    get_header();

    // wp_enqueue_script('scroll-magic', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/ScrollMagic.min.js',  array('jquery'), '1.0', true);
    // wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.4.0/gsap.min.js',  array('jquery'), '1.0', true);
    // wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/build/static/js/libs/swiper.min.js',  array('jquery'), '1.0', true);
    // wp_enqueue_script('front-page', get_template_directory_uri() . '/build/static/js/pages/front-page/front-page.js',  array('jquery'), '1.0', true);

    wp_enqueue_style('front-page', get_template_directory_uri() . '/build/static/css/pages/front-page/front-page.css');
    // wp_enqueue_style('swiper', get_template_directory_uri() . '/build/static/css/libs/swiper.css');

    get_template_part('./blocks/front-page/block-hero'); 

    get_footer();