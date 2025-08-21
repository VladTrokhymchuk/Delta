<?php // Template name: Front page

    get_header();

    wp_enqueue_script('gsap', 'https://unpkg.co/gsap@3/dist/gsap.min.js',  array('jquery'), '1.0', true);
    wp_enqueue_script('Scroll-Trigger', 'https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js',  array('jquery'), '1.0', true);
    wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/build/js/libs/swiper.min.js',  array('jquery'), '1.0', true);
    wp_enqueue_style('front-page', get_template_directory_uri() . '/build/css/pages/front-page/front-page.min.css');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/build/css/libs/swiper.min.css');

    get_template_part('./blocks/front-page/block-main'); 

    get_footer();