<?php
/**
 * Security hardening — додатковий рівень захисту теми Delta.
 *
 * Закриває найпоширеніші вектори атак на WordPress:
 *  - перерахування користувачів (?author=N, REST, oEmbed)
 *  - брутфорс через Application Passwords / XML-RPC pingback
 *  - спам та зловживання коментарями (повне вимкнення)
 *  - clickjacking / MIME-sniffing (security-заголовки)
 *
 * Базові заходи (версія WP, фіди, REST /users, XML-RPC) вже є у
 * headache.php та parts/security.php — тут лише те, чого бракувало.
 */

defined('ABSPATH') || exit; // заборона прямого доступу до файлу

/* -------------------------------------------------------------------------
 * 1. Блокування перерахування користувачів (user/author enumeration)
 *    Атака: ?author=1 -> редірект на /author/admin/ розкриває логін.
 * ---------------------------------------------------------------------- */
add_action('init', function () {
	if (is_admin()) {
		return;
	}
	// ?author=N у фронтенді — типова розвідка перед брутфорсом
	if (isset($_GET['author']) && preg_match('/\d/', (string) $_GET['author'])) {
		wp_safe_redirect(home_url(), 301);
		exit;
	}
});

// Архіви автора повністю віддають 404 (логін не світиться у URL)
add_action('template_redirect', function () {
	if (is_author()) {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		nocache_headers();
	}
});

// REST: ховаємо ендпоінти користувачів і коментарів для неавторизованих
add_filter('rest_endpoints', function ($endpoints) {
	if (is_user_logged_in()) {
		return $endpoints;
	}
	foreach ([
		'/wp/v2/users',
		'/wp/v2/users/(?P<id>[\d]+)',
		'/wp/v2/comments',
		'/wp/v2/comments/(?P<id>[\d]+)',
	] as $route) {
		if (isset($endpoints[$route])) {
			unset($endpoints[$route]);
		}
	}
	return $endpoints;
});

// oEmbed також розкриває ім'я автора — прибираємо ці дані
add_filter('oembed_response_data', function ($data) {
	unset($data['author_name'], $data['author_url']);
	return $data;
});

/* -------------------------------------------------------------------------
 * 2. Application Passwords — часта ціль брутфорсу REST API. Не використовуються.
 * ---------------------------------------------------------------------- */
add_filter('wp_is_application_passwords_available', '__return_false');

/* -------------------------------------------------------------------------
 * 3. XML-RPC / pingback — добиваємо рештки (заголовок X-Pingback і методи).
 *    Сам xmlrpc вже вимкнено фільтром у headache.php.
 * ---------------------------------------------------------------------- */
add_filter('xmlrpc_methods', function ($methods) {
	unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks']);
	return $methods;
});

/* -------------------------------------------------------------------------
 * 4. Повне вимкнення коментарів (спам-бот № 1 на WordPress).
 *    comments_open вже false у headache.php — тут закриваємо всі шляхи.
 * ---------------------------------------------------------------------- */
// Прибираємо підтримку коментарів/трекбеків у всіх типів записів
add_action('init', function () {
	foreach (get_post_types() as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
});

add_filter('comments_open', '__return_false', 20);
add_filter('pings_open', '__return_false', 20);
add_filter('comments_array', '__return_empty_array', 10);

// Блокуємо будь-яку спробу надіслати коментар (wp-comments-post.php / REST)
add_filter('preprocess_comment', function () {
	wp_die('Коментарі вимкнено.', '', ['response' => 403]);
});

// Прибираємо коментарі з адмін-бару та дашборду
add_action('admin_bar_menu', function ($wp_admin_bar) {
	$wp_admin_bar->remove_node('comments');
}, 999);

add_action('admin_init', function () {
	// Редірект зі сторінки коментарів, якщо хтось відкриє її напряму
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_safe_redirect(admin_url());
		exit;
	}
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
});

/* -------------------------------------------------------------------------
 * 5. Security-заголовки відповіді (clickjacking, MIME-sniffing, витік referer).
 * ---------------------------------------------------------------------- */
add_filter('wp_headers', function ($headers) {
	unset($headers['X-Pingback']);
	$headers['X-Content-Type-Options']  = 'nosniff';
	$headers['X-Frame-Options']         = 'SAMEORIGIN';
	$headers['Referrer-Policy']         = 'strict-origin-when-cross-origin';
	$headers['Permissions-Policy']      = 'geolocation=(self), microphone=(), camera=()';
	return $headers;
});

/* -------------------------------------------------------------------------
 * 6. Прибираємо зайвий скрипт comment-reply.js (коментарів усе одно немає).
 * ---------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
	wp_dequeue_script('comment-reply');
}, 100);

/* -------------------------------------------------------------------------
 * 7. Core XML-sitemap (WP 5.5+): /wp-sitemap-users-1.xml віддає список логінів.
 *    headache.php закриває REST /users та ?author=N, але цей файл — ні.
 *    Прибираємо провайдера 'users' із карти сайту повністю.
 * ---------------------------------------------------------------------- */
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
	return ($name === 'users') ? false : $provider;
}, 10, 2);

// Підстраховка: навіть якщо провайдер лишиться — не віддаємо жодного автора
add_filter('wp_sitemaps_users_query_args', function ($args) {
	$args['include'] = [0]; // неіснуючий ID → порожня вибірка
	return $args;
});

/* -------------------------------------------------------------------------
 * 8. Примусово вимикаємо відкриту реєстрацію користувачів.
 *    Навіть якщо в Налаштуваннях хтось увімкне "Будь-хто може зареєструватися",
 *    реєстрація лишиться закритою (часта точка входу для спаму/ескалації прав).
 * ---------------------------------------------------------------------- */
add_filter('option_users_can_register', '__return_zero');
add_filter('pre_option_users_can_register', '__return_zero');

/* -------------------------------------------------------------------------
 * 9. Прибираємо заголовок X-Powered-By (розкриває версію PHP).
 * ---------------------------------------------------------------------- */
add_action('send_headers', function () {
	if (!headers_sent()) {
		header_remove('X-Powered-By');
	}
});
