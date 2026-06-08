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
} );