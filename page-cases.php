<?php // Template name: Cases page
    get_header(); 

    wp_enqueue_style('page-cases', get_template_directory_uri() . '/build/static/css/pages/page-cases/page-cases.css');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/build/static/css/libs/swiper.css');

    wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/build/static/js/libs/swiper.min.js',  array('jquery'), '1.0', true);

    get_template_part('./blocks/page-cases/block-learn');
    get_template_part('./blocks/page-cases/block-form');

 get_footer(); ?>