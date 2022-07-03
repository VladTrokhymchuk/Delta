<?php
/*
 * Create custom post type
 * */
add_action('init', 'init_post_types');
function init_post_types()
{
    register_post_type('news', array(
        'label' => null,
        'labels' => array(
            'name' => 'News', // основное название для типа записи
            'singular_name' => 'News', // название для одной записи этого типа
            'add_new' => 'Add news', // для добавления новой записи
            'add_new_item' => 'Add', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item' => 'Edit', // для редактирования типа записи
            'new_item' => 'New news', // текст новой записи
            'view_item' => 'Revision', // для просмотра записи этого типа.
            'search_items' => 'Search', // для поиска по этим типам записи
            'not_found' => 'Not found', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Not found in the cart', // если не было найдено в корзине
            'parent_item_colon' => '', // для родителей (у древовидных типов)
            'menu_name' => 'News', // название меню
        ),
        'description' => '',
        'public' => true,
        'publicly_queryable' => null,
        'exclude_from_search' => null,
        'show_ui' => null,
        'show_in_menu' => true, // показывать ли в меню адмнки
        'show_in_admin_bar' => null, // по умолчанию значение show_in_menu
        'show_in_nav_menus' => null,
        'show_in_rest' => true, // добавить в REST API. C WP 4.7
        'rest_base' => null, // $post_type. C WP 4.7
        'menu_position' => 4,
        'menu_icon' => null,
        'hierarchical' => true,
        'supports' => array('title', 'author', 'revisions'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        'taxonomies' => array(),
        'has_archive' => true,
        'rewrite' => array('slug' => 'news-post'),
        'query_var' => true,
        'menu_icon' => 'dashicons-analytics'
    ));
 
    register_post_type('cases', array(
        'label' => null,
        'labels' => array(
            'name' => 'Cases', // основное название для типа записи
            'singular_name' => 'cases', // название для одной записи этого типа
            'add_new' => 'Add cases', // для добавления новой записи
            'add_new_item' => 'Add', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item' => 'Edit', // для редактирования типа записи
            'new_item' => 'New cases', // текст новой записи
            'view_item' => 'Revision', // для просмотра записи этого типа.
            'search_items' => 'Search', // для поиска по этим типам записи
            'not_found' => 'Not found', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Not found in the cart', // если не было найдено в корзине
            'parent_item_colon' => '', // для родителей (у древовидных типов)
            'menu_name' => 'Cases', // название меню
        ),
        'description' => '',
        'public' => true,
        'publicly_queryable' => null,
        'exclude_from_search' => null,
        'show_ui' => null,
        'show_in_menu' => true, // показывать ли в меню адмнки
        'show_in_admin_bar' => null, // по умолчанию значение show_in_menu
        'show_in_nav_menus' => null,
        'show_in_rest' => true, // добавить в REST API. C WP 4.7
        'rest_base' => null, // $post_type. C WP 4.7
        'menu_position' => 4,
        'menu_icon' => null,
        'hierarchical' => true,
        'supports' => array('title', 'author', 'revisions'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        'taxonomies' => array(),
        'has_archive' => true,
        'rewrite' => array('slug' => 'cases-post'),
        'query_var' => true,
        'menu_icon' => 'dashicons-analytics'
    ));
    


}
