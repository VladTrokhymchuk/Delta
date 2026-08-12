<?php
defined('ABSPATH') || exit;

/**
 * Безпечний вивід зображення з ACF: SVG інлайниться (щоб фарбувати через CSS `fill`),
 * будь-який інший формат віддається звичайним <img>.
 *
 * Раніше хедер/футер робили `echo file_get_contents(...)` беззастережно — якщо в поле
 * завантажували PNG/JPG (а новий логотип саме растровий), у HTML виливався бінарний
 * вміст файлу й ламав сторінку.
 *
 * @param array|false $img   Поле ACF типу image (return format = Array).
 * @param array       $args  alt, class, width, height, loading, fetchpriority.
 */
function delta_render_image($img, $args = array()) {
    if (empty($img['ID'])) return;

    $args = wp_parse_args($args, array(
        'alt'           => '',
        'class'         => '',
        'loading'       => 'lazy',
        'fetchpriority' => '',
    ));

    $file = get_attached_file($img['ID']);
    $ext  = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : '';

    // SVG — інлайнимо, щоб CSS міг керувати кольором (fill).
    if ($ext === 'svg' && is_readable($file)) {
        $markup = file_get_contents($file);
        if ($markup !== false && stripos($markup, '<svg') !== false) {
            echo wp_kses($markup, delta_svg_allowed_tags());
            return;
        }
    }

    // Растр — звичайний <img> із розмірами (проти CLS) і реальним alt.
    $alt = $args['alt'] !== '' ? $args['alt'] : (!empty($img['alt']) ? $img['alt'] : get_bloginfo('name'));

    printf(
        '<img src="%s" alt="%s"%s%s%s%s>',
        esc_url($img['url']),
        esc_attr($alt),
        !empty($img['width'])  ? ' width="'  . (int) $img['width']  . '"' : '',
        !empty($img['height']) ? ' height="' . (int) $img['height'] . '"' : '',
        $args['class'] !== ''   ? ' class="'   . esc_attr($args['class'])   . '"' : '',
        $args['fetchpriority'] !== ''
            ? ' fetchpriority="' . esc_attr($args['fetchpriority']) . '"'
            : ' loading="' . esc_attr($args['loading']) . '"'
    );
}

/**
 * Готує довгий документ із ACF WYSIWYG (правила, оферта) до виводу.
 *
 * Контент вставлений із Word, тому має типові проблеми:
 *  - порожні абзаци-розпірки `<p>&nbsp;</p>` замість відступів;
 *  - заголовки розділів зроблені НЕ заголовками, а списком з одного пункту:
 *    `<ol start="N"><li><strong>Назва</strong></li></ol>`.
 *
 * Функція нормалізує це на етапі рендеру (сам контент у CMS не змінюється):
 *  - викидає порожні абзаци;
 *  - перетворює псевдозаголовки на справжні `<h2>` з якорем — сторінка нарешті
 *    отримує структуру заголовків для SEO й скрінрідерів;
 *  - збирає зміст документа.
 *
 * Списки з кількох `<li>` — справжні переліки, їх не чіпаємо.
 *
 * @param  string $html Розмітка з get_field() (бажано вже через wp_kses_post()).
 * @return array{html: string, toc: array<int, array{id: string, num: string, title: string}>}
 */
function delta_prepare_document($html) {
    $toc = array();

    if (!is_string($html) || trim($html) === '') {
        return array('html' => '', 'toc' => $toc);
    }

    // 1. Порожні абзаци-розпірки (пробіли, &nbsp;, юнікодний NBSP).
    $html = preg_replace('#<p[^>]*>(?:\s|&nbsp;|\x{00A0})*</p>#iu', '', $html);

    // 2. Псевдозаголовки <ol><li>…</li></ol> → <h2 id="…">.
    $html = preg_replace_callback(
        '#<ol([^>]*)>\s*<li[^>]*>(.*?)</li>\s*</ol>#is',
        function ($m) use (&$toc) {
            // Кілька <li> усередині — це справжній список, а не заголовок.
            if (stripos($m[2], '<li') !== false) {
                return $m[0];
            }

            $title = trim(wp_strip_all_tags($m[2]));
            if ($title === '') {
                return $m[0];
            }

            // Номер розділу беремо з start="N"; для першого <ol> його немає.
            $num = preg_match('#start\s*=\s*["\']?(\d+)#i', $m[1], $s)
                ? $s[1]
                : (string) (count($toc) + 1);

            $id = 'rozdil-' . $num;

            $toc[] = array('id' => $id, 'num' => $num, 'title' => $title);

            return sprintf(
                '<h2 class="pravila__heading" id="%s">'
                . '<span class="pravila__heading__num" aria-hidden="true">%s</span>'
                . '<span class="pravila__heading__text">%s</span></h2>',
                esc_attr($id),
                esc_html($num),
                esc_html($title)
            );
        },
        $html
    );

    return array('html' => $html, 'toc' => $toc);
}

/** Білий список тегів/атрибутів SVG для wp_kses() — інлайн лого та іконок. */
function delta_svg_allowed_tags() {
    $attrs = array(
        'class' => true, 'id' => true, 'style' => true, 'fill' => true, 'fill-rule' => true,
        'fill-opacity' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true,
        'stroke-linejoin' => true, 'opacity' => true, 'transform' => true, 'clip-path' => true,
        'clip-rule' => true, 'mask' => true, 'filter' => true,
    );

    return array(
        'svg'            => $attrs + array('xmlns' => true, 'xmlns:xlink' => true, 'viewbox' => true,
                                           'width' => true, 'height' => true, 'preserveaspectratio' => true,
                                           'role' => true, 'aria-hidden' => true, 'focusable' => true),
        'g'              => $attrs,
        'path'           => $attrs + array('d' => true),
        'circle'         => $attrs + array('cx' => true, 'cy' => true, 'r' => true),
        'ellipse'        => $attrs + array('cx' => true, 'cy' => true, 'rx' => true, 'ry' => true),
        'rect'           => $attrs + array('x' => true, 'y' => true, 'width' => true, 'height' => true,
                                           'rx' => true, 'ry' => true),
        'line'           => $attrs + array('x1' => true, 'y1' => true, 'x2' => true, 'y2' => true),
        'polygon'        => $attrs + array('points' => true),
        'polyline'       => $attrs + array('points' => true),
        'defs'           => $attrs,
        'clippath'       => $attrs,
        'mask'           => $attrs,
        'lineargradient' => $attrs + array('x1' => true, 'y1' => true, 'x2' => true, 'y2' => true,
                                           'gradientunits' => true),
        'radialgradient' => $attrs + array('cx' => true, 'cy' => true, 'r' => true, 'gradientunits' => true),
        'stop'           => $attrs + array('offset' => true, 'stop-color' => true, 'stop-opacity' => true),
        'title'          => array(),
        'desc'           => array(),
        'use'            => $attrs + array('xlink:href' => true, 'href' => true, 'x' => true, 'y' => true,
                                           'width' => true, 'height' => true),
    );
}

function print_svg_ic($icon_id) {
  ?>
    <svg>
      <use xlink:href="<?php echo get_template_directory_uri() ?>/build/images/svg/symbol/sprite.svg#<?php echo $icon_id ?>"></use>
    </svg>
  <?php
}



function cut_p_tags($dirty_html) {
  $nice_html = $dirty_html;
  $nice_html = str_replace("<p>", "", $nice_html);
  $nice_html = str_replace("</p>", "", $nice_html);
  return $nice_html;
}


function print_store_card($post_id) {
  ?>
    <div class="stores-card">
        <?php $adress = get_field('adresa_tochky_prodazhu', $post_id); ?>
        
        <div class="stores-card__info">
            <span class="add-text green"><?php echo $adress['city'] ?></span>
            
            <span><?php echo $adress['address'] ?></span>
            <span class="stores-card__title"><?php echo get_the_title($post_id) ?></span>
            
              
            <?php if( have_rows('nomera_telefoniv', $post_id) ): ?>
                <div class="stores-card__numbers">
                <?php while ( have_rows('nomera_telefoniv', $post_id) ) : the_row();
                    $num = get_sub_field('telefon'); ?>
                    <a href="tel:<?php echo $num ?>"><?php echo $num ?></a>
                <?php endwhile; ?> 
                </div>
            <?php endif; ?> 
            
        </div>
        <div class="stores-card__logo">
            <?php $logo = get_field('logotyp_tochky', $post_id);
            if ($logo): ?>
                <img src="<?php echo $logo['sizes']['medium_large'] ?>" alt="">
            <?php else: ?>
                <span>logo</span>
            <?php endif; ?>
        </div>
    </div>
  <?php
}

function isMobile() {
  $detect = new Mobile_Detect;
  return $detect->isMobile(); 
}

function isTablet() {
  $detect = new Mobile_Detect;
  return $detect->isTablet(); 
}

function isDesktop() {
  return (!isTablet() && !isMobile());
}

