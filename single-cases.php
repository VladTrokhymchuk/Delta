<?php
    get_header(); 

    wp_enqueue_style('single-cases', get_template_directory_uri() . '/build/static/css/pages/single-cases/single-cases.css');
    
    get_template_part('./blocks/single-cases/block-header');
    get_template_part('./blocks/single-cases/block-main');

get_footer();