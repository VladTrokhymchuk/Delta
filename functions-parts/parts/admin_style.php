<?php
# 5.4 Регистрация нового размера изображений
add_image_size( 'full_hd', 1920, 1080 );

# 6. Стилізація адмін панелі
	# 6.1 Додати CSS стилі
	add_action('admin_enqueue_scripts', 'my_admin_css', 99);
	function my_admin_css(){
		wp_enqueue_style('my-wp-admin', get_template_directory_uri() .'/wp-admin.css' );
	}