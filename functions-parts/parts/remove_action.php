<?php
defined('ABSPATH') || exit;
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'index_rel_link');

add_action ( 'after_setup_theme' , function() {
	# удалить SVG и глобальные стили 
	remove_action ( 'wp_enqueue_scripts' , 'wp_enqueue_global_styles' );

	# удалить действия wp_footer, которые добавляют глобальные встроенные стили 
	remove_action ( 'wp_footer' , 'wp_enqueue_global_styles' , 1 );

	# удаляем фильтры render_block, добавляющие лишнее 
	remove_filter ( 'render_block' , 'wp_render_duotone_support' );
	remove_filter ( 'render_block' , 'wp_restore_group_inner_container' );
	remove_filter ( 'render_block' , 'wp_render_layout_support_flag' );
});

add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
# Filter out the tinymce emoji plugin.
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

# Отменить регистрацию стилей CF7 и Gutenberg
add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
function wps_deregister_styles() {
	wp_deregister_style( 'contact-form-7' );
	wp_deregister_style( 'wp-block-library' );
	wp_deregister_style( 'wp-block-library-theme' );
	wp_deregister_style( 'wc-block-style' );
}

// Disable https://ce.smart-it.com/wp-content/plugins/sitepress-multilingual-cms/templates/language-switchers/legacy-dropdown/style.min.css
// wpml-legacy-dropdown-0-css
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);