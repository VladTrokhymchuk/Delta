<?php
defined('ABSPATH') || exit;

// Підтримка SVG у медіабібліотеці — ЛИШЕ для адміністраторів (manage_options).
// SVG може містити виконуваний JS, тому довіряємо завантаження тільки адмінам.
function allow_svg($mimes) {
	if ( current_user_can('manage_options') ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter('upload_mimes', 'allow_svg');

// ⚠️ ALLOW_UNFILTERED_UPLOADS вимкнено навмисно.
// Цей прапорець дозволяв завантажувати БУДЬ-ЯКІ файли (включно з .php) в обхід
// перевірок WordPress — пряма дорога до RCE. SVG працює і без нього (див. нижче).
// define('ALLOW_UNFILTERED_UPLOADS', true);

// WordPress інколи не визначає MIME для SVG і блокує коректний файл — підказуємо тип.
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
	if ( empty($data['type']) ) {
		$ext = strtolower( pathinfo($filename, PATHINFO_EXTENSION) );
		if ( $ext === 'svg' ) {
			$data['type'] = 'image/svg+xml';
			$data['ext']  = 'svg';
		}
	}
	return $data;
}, 10, 4);

// Базова санітизація SVG при завантаженні: вирізаємо потенційно небезпечні вектори
// (script, on*-обробники, javascript:, foreignObject, XXE-сутності).
// Для повноцінного очищення рекомендовано плагін Safe SVG.
add_filter('wp_handle_upload_prefilter', function ($file) {
	if ( ! isset($file['type']) || $file['type'] !== 'image/svg+xml' ) {
		return $file;
	}

	$content = @file_get_contents($file['tmp_name']);
	if ( $content === false ) {
		return $file;
	}

	$clean = $content;
	$clean = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $clean);          // <script>
	$clean = preg_replace('#<foreignObject\b[^>]*>.*?</foreignObject>#is', '', $clean); // вбудований HTML/JS
	$clean = preg_replace('#\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $clean); // onload, onclick...
	$clean = preg_replace('#(href|xlink:href)\s*=\s*("|\')\s*javascript:[^"\']*\2#i', '', $clean); // javascript:
	$clean = preg_replace('#<!ENTITY\b[^>]*>#i', '', $clean);                       // XXE-сутності
	$clean = preg_replace('#<!DOCTYPE\b[^>]*>#is', '', $clean);                     // DTD

	if ( $clean !== null && $clean !== $content ) {
		@file_put_contents($file['tmp_name'], $clean);
	}

	return $file;
}, 10, 1);
