<?php 
/*
 * Подключение стилей и скриптов
 * */

function my_assets()
{
    wp_deregister_script('jquery-core');
    wp_register_script('jquery-core', get_stylesheet_directory_uri() . '/build/js/jquery-3.5.0.min.js');
    wp_enqueue_script('jquery');

    // wp_enqueue_script('smooth-scroll', get_stylesheet_directory_uri() . '/build/js/libs/SmoothScroll.min.js',  array('jquery'), '1.0', true);


    // 
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/build/js/main.js',  array('jquery'), '1.0', true);

    $page_template =  mb_substr(get_page_template_slug(), 0, -4); // get template file name and cut last 4 symbols

    $css_file_path = get_template_directory_uri() . '/build/css/pages/' . $page_template . '.min.css';
    $js_file_path = get_template_directory_uri() . '/build/js/pages/' . $page_template . '.js';


    if (is_page_template('page-about.php')) {
        // wp_enqueue_script('sticky-sidebar', get_stylesheet_directory_uri() . '/build/js/libs/sticky-sidebar.min.js',  array('jquery'), '1.0', true);   
        wp_enqueue_style('swiper', get_template_directory_uri() . '/build/css/libs/swiper.min.css');
        wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/build/js/libs/swiper.min.js',  array('jquery'), '1.0', true);
        wp_enqueue_style('front-page', get_template_directory_uri() . '/build/css/pages/front-page.min.css');
        wp_enqueue_script('front-page', get_stylesheet_directory_uri() . '/build/js/pages/front-page.js',  array('jquery'), '1.0', true);
    }


    if (is_404()) {
        wp_enqueue_style('404', get_template_directory_uri() . '/build/css/pages/404.min.css');
    }
  
}

add_action('wp_enqueue_scripts', 'my_assets');