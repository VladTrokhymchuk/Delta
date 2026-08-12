<?php
defined('ABSPATH') || exit;
# 3. Удаление пунктов меню
function remove_menus(){
	// remove_menu_page('index.php');                  //Консоль
	remove_menu_page('edit.php');                   //Записи
	// remove_menu_page('upload.php');                 //Медиафайлы
	// remove_menu_page('edit.php?post_type=page');    //Страницы
	remove_menu_page('edit-comments.php');          //Комментарии Заблокувати
	// remove_menu_page('themes.php');                 //Внешний вид
	// remove_menu_page('plugins.php');                //Плагины
	// remove_menu_page('users.php');                  //Пользователи
	// remove_menu_page('tools.php');                  //Инструменты
	// remove_menu_page('options-general.php');        //Настройки
}
add_action('admin_menu', 'remove_menus');

# 5.1 Регистрация меню
add_theme_support('menus');
add_action( 'after_setup_theme', function(){
	register_nav_menus( [
		'header_menu' => 'Меню в шапке'
	] );

	# 5.2 Регистрация обложки для постов
	add_theme_support( 'post-thumbnails' );

	# 5.3 <title> у <head>.
	# Без цієї підтримки WordPress не виводить <title> ВЗАГАЛІ: Rank Math лише
	# фільтрує заголовок (через pre_get_document_title), а друкує його ядро WP —
	# і лише за наявності title-tag. Наслідок був такий, що og:title і canonical
	# від Rank Math були на місці, а самого <title> на сторінках не було.
	add_theme_support( 'title-tag' );
} );