<?php
/**
 * SEO під AI: доступ для AI-краулерів (robots.txt) + карта контенту llms.txt.
 *
 * Доповнює Rank Math (meta/OG/canonical/sitemap/schema). Нічого не дублює:
 * тут лише те, чого Rank Math не робить — явний allow AI-ботів і файл llms.txt.
 *
 * @see .claude/skills/seo-for-ai
 */

if (!defined('ABSPATH')) exit;

/* ============================================================
 * 1. robots.txt — явний доступ для AI-краулерів.
 * Дописуємо у віртуальний robots.txt, який віддає WP (фізичного файлу немає).
 * Сумісно з Rank Math: він теж хукається у цей фільтр і додає sitemap.
 * ============================================================ */
add_filter('robots_txt', 'delta_robots_ai_crawlers', 20, 2);
function delta_robots_ai_crawlers($output, $public) {
    // Сайт закритий від індексації (Settings → Reading) — не відкриваємо ботів.
    if (!$public) return $output;

    $bots = array(
        'GPTBot',          // OpenAI — тренування/індексація
        'OAI-SearchBot',   // OpenAI — пошук у ChatGPT
        'ChatGPT-User',    // OpenAI — переходи з чату
        'ClaudeBot',       // Anthropic — індексація
        'Claude-User',     // Anthropic — переходи з чату
        'PerplexityBot',   // Perplexity
        'Google-Extended', // Gemini / AI Overviews
    );

    $rules = "\n# AI crawlers — див. .claude/skills/seo-for-ai\n";
    foreach ($bots as $bot) {
        $rules .= "User-agent: {$bot}\nAllow: /\n\n";
    }
    // Посилання на карту контенту для LLM (коментар — не директива robots).
    $rules .= '# llms.txt: ' . home_url('/llms.txt') . "\n";

    return $output . $rules;
}

/* ============================================================
 * 2. llms.txt — карта головного контенту для LLM (llmstxt.org).
 * Динамічний роут ^llms.txt$ → генерується зі сторінок, номерів і новин,
 * кешується у transient (12 год); кеш скидається на save_post / deleted_post.
 * ============================================================ */
add_action('init', 'delta_llms_rewrite');
function delta_llms_rewrite() {
    add_rewrite_rule('^llms\.txt$', 'index.php?delta_llms=1', 'top');

    // Одноразовий flush після додавання правила (self-healing, без ручного
    // скидання permalinks). Бампни версію, якщо колись зміниш правило.
    if (get_option('delta_llms_rewrite_v') !== '1') {
        flush_rewrite_rules(false);
        update_option('delta_llms_rewrite_v', '1');
    }
}

add_filter('query_vars', 'delta_llms_query_var');
function delta_llms_query_var($vars) {
    $vars[] = 'delta_llms';
    return $vars;
}

// Пріоритет 0 — вивести й вийти ДО redirect_canonical (пріоритет 10), інакше WP
// 301-редіректить /llms.txt → /llms.txt/ (нам потрібен канонічний URL без слеша).
add_action('template_redirect', 'delta_llms_output', 0);
function delta_llms_output() {
    if (!get_query_var('delta_llms')) return;

    $txt = get_transient('delta_llms_txt');
    if ($txt === false) {
        $txt = delta_build_llms_txt();
        set_transient('delta_llms_txt', $txt, 12 * HOUR_IN_SECONDS);
    }

    header('Content-Type: text/plain; charset=utf-8');
    header('X-Robots-Tag: noindex'); // сам файл у видачі не потрібен
    echo $txt;
    exit;
}

/** Чистий однорядковий текст: без тегів, з розкодованими сутностями, стиснені пробіли. */
function delta_llms_clean($text) {
    $text = wp_strip_all_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/', ' ', $text));
}

/** Збирає вміст llms.txt зі сторінок, номерів і новин. Результат кешується викликачем. */
function delta_build_llms_txt() {
    $name = delta_llms_clean(get_bloginfo('name'));
    $desc = delta_llms_clean(get_bloginfo('description'));

    $out = '# ' . $name . "\n\n";
    if ($desc !== '') {
        $out .= '> ' . $desc . "\n\n";
    }
    $out .= 'Сайт: ' . home_url('/') . "\n\n";

    // --- Верхньорівневі опубліковані сторінки -------------------------------
    // Виключаємо статичну головну — вона вже є як home URL вище.
    $front = (int) get_option('page_on_front');
    $pages = get_posts(array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'post_parent'    => 0,
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
        'exclude'        => $front ? array($front) : array(),
    ));
    $out .= delta_llms_section('Сторінки', $pages);

    // --- Номери (Simple Hotel Booking) — головний комерційний контент -------
    $out .= delta_llms_rooms();

    // --- Новини --------------------------------------------------------------
    $news = get_posts(array(
        'post_type'      => 'news',
        'post_status'    => 'publish',
        'posts_per_page' => 100,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));
    $out .= delta_llms_section('Новини', $news);

    // --- Контакти (ті самі ACF-поля, що footer.php) --------------------------
    $out .= delta_llms_contacts();

    return rtrim($out) . "\n";
}

/**
 * Секція карти: "## Label" + рядки постів. Пропускає noindex / Sample Page.
 * Якщо після фільтрів рядків не лишилось — секція не виводиться (без порожнього заголовка).
 */
function delta_llms_section($label, $posts) {
    $lines = '';
    foreach ($posts as $p) {
        if (delta_llms_skip($p)) continue;
        $lines .= delta_llms_line($p);
    }
    return $lines === '' ? '' : "## {$label}\n{$lines}\n";
}

/**
 * Чи ховати запис із карти:
 *  - дефолтна WP Sample Page;
 *  - Rank Math noindex (мета `rank_math_robots` — масив директив).
 */
function delta_llms_skip($post) {
    if ($post->post_name === 'sample-page') return true;

    $robots = get_post_meta($post->ID, 'rank_math_robots', true);
    if (is_array($robots) && in_array('noindex', $robots, true)) return true;

    return false;
}

/**
 * Номери: назва + ціна з ACF, щоб LLM могла відповісти «скільки коштує».
 * CPT `shb_room` реєструє плагін Simple Hotel Booking — якщо плагін вимкнено,
 * секція просто не виводиться.
 */
function delta_llms_rooms() {
    if (!post_type_exists('shb_room')) return '';

    $rooms = get_posts(array(
        'post_type'      => 'shb_room',
        'post_status'    => 'publish',
        'posts_per_page' => 100,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ));

    $lines = '';
    foreach ($rooms as $room) {
        if (delta_llms_skip($room)) continue;

        $line  = '- [' . delta_llms_clean(get_the_title($room)) . '](' . get_permalink($room) . ')';
        $parts = array();

        if (function_exists('get_field')) {
            $price = delta_llms_clean((string) get_field('price', $room->ID));
            if ($price !== '') $parts[] = $price;

            // Короткі характеристики номера (repeater `short_character`).
            if (have_rows('short_character', $room->ID)) {
                $chars = array();
                while (have_rows('short_character', $room->ID)) {
                    the_row();
                    $item = delta_llms_clean((string) get_sub_field('s_h_item'));
                    if ($item !== '') $chars[] = $item;
                }
                if ($chars) $parts[] = implode(', ', $chars);
            }
        }

        if ($parts) $line .= ': ' . implode('. ', $parts);
        $lines .= $line . "\n";
    }

    return $lines === '' ? '' : "## Номери\n{$lines}\n";
}

/** Блок контактів — дзеркалить джерела footer.php (ACF Options). */
function delta_llms_contacts() {
    if (!function_exists('get_field')) return '';

    $out   = '';
    $name  = delta_llms_clean((string) get_field('foot_delta', 'options'));
    $addr  = delta_llms_clean((string) get_field('foot_strit', 'options'));
    $lines = array();

    if ($name !== '') $lines[] = '- Назва: ' . $name;
    if ($addr !== '') $lines[] = '- Адреса: ' . $addr;

    if (have_rows('phone_rep', 'options')) {
        while (have_rows('phone_rep', 'options')) {
            the_row();
            $phone = get_sub_field('phone');
            if (empty($phone['title'])) continue;
            $lines[] = '- Телефон: ' . delta_llms_clean($phone['title']);
        }
    }

    if (have_rows('social_rep', 'options')) {
        while (have_rows('social_rep', 'options')) {
            the_row();
            $social = get_sub_field('social_item');
            if (empty($social['url'])) continue;
            $label   = !empty($social['title']) ? delta_llms_clean($social['title']) : 'Соцмережа';
            $lines[] = "- {$label}: " . esc_url_raw($social['url']);
        }
    }

    if (!$lines) return '';

    return "## Контакти\n" . implode("\n", $lines) . "\n\n";
}

/** Один рядок карти: "- [Заголовок](url): короткий опис". */
function delta_llms_line($post) {
    $title = delta_llms_clean(get_the_title($post));
    $url   = get_permalink($post);
    $line  = "- [{$title}]({$url})";

    $excerpt = delta_llms_clean(get_the_excerpt($post));
    if ($excerpt !== '') {
        if (mb_strlen($excerpt) > 160) {
            $excerpt = mb_substr($excerpt, 0, 157) . '…';
        }
        $line .= ': ' . $excerpt;
    }

    return $line . "\n";
}

/** Скидання кешу llms.txt при зміні контенту або назви/слогану сайту. */
add_action('save_post', 'delta_llms_flush_cache');
add_action('deleted_post', 'delta_llms_flush_cache');
add_action('acf/save_post', 'delta_llms_flush_cache', 20); // зміна Options (контакти)
add_action('update_option_blogname', 'delta_llms_flush_cache');
add_action('update_option_blogdescription', 'delta_llms_flush_cache');
function delta_llms_flush_cache() {
    delete_transient('delta_llms_txt');
}

/* ============================================================
 * 3. Кнопка в адмін-барі — оновити (скинути й прогріти) кеш llms.txt вручну.
 * Видно лише адмінам; захищено nonce.
 * ============================================================ */
add_action('admin_bar_menu', 'delta_llms_admin_bar', 90);
function delta_llms_admin_bar($bar) {
    if (!current_user_can('manage_options')) return;
    $bar->add_node(array(
        'id'    => 'delta-llms-flush',
        'title' => 'llms.txt ↻',
        'href'  => wp_nonce_url(admin_url('admin-post.php?action=delta_llms_flush'), 'delta_llms_flush'),
        'meta'  => array('title' => 'Оновити кеш llms.txt'),
    ));
}

add_action('admin_post_delta_llms_flush', 'delta_llms_flush_action');
function delta_llms_flush_action() {
    if (!current_user_can('manage_options') || !check_admin_referer('delta_llms_flush')) {
        wp_die('Недостатньо прав.');
    }
    delete_transient('delta_llms_txt');
    set_transient('delta_llms_txt', delta_build_llms_txt(), 12 * HOUR_IN_SECONDS); // прогріти наново
    wp_safe_redirect(add_query_arg('delta_llms_flushed', '1', wp_get_referer() ?: admin_url()));
    exit;
}

add_action('admin_notices', 'delta_llms_flush_notice');
function delta_llms_flush_notice() {
    if (empty($_GET['delta_llms_flushed'])) return;
    echo '<div class="notice notice-success is-dismissible"><p>Кеш <code>llms.txt</code> оновлено.</p></div>';
}
