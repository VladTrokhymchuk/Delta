---
name: acf-fields
description: >-
  Work with ACF Pro field groups via acf-json (local JSON sync). Conventions for creating/
  editing field groups, naming, safe get_field/get_sub_field retrieval, repeaters and the
  Options page. Use for ACF, acf-json, "поля", field group, repeater, options page,
  або читання полів у шаблоні.
---

# ACF Pro + acf-json

Усі поля версіонуються як JSON у `acf-json/`. Редагування в адмінці автоматично пише JSON; на іншому середовищі ACF пропонує «Sync».

## Save/Load point — вже працює, нічого не додавати
`acf-json/` у корені активної теми — це **дефолтний** save/load point ACF. Фільтри `acf/settings/save_json` / `load_json` тут **не потрібні** й у темі їх немає. Просто тримай папку writable. Після `git pull` — ACF → Field Groups → **Sync available**.

## Групи полів у цьому проєкті

| Файл | Title | Location |
|---|---|---|
| `group_62c2d66662771.json` | Головна | `page_template == front-page.php` або `default` |
| `group_62c36406c67a4.json` | Options | `options_page` |
| `group_62caa5d8ddbcc.json` | [post] News | `post_type == news` |
| `group_62d2d4950275c.json` | Правила | `page_template == page-pravila.php` |

### Головна (`front-page.php`)
`title`, `subtitle` · `swiper_rep`→`img_r` · `about_title`, `about_desc`, `id_hotel` · `img_room`, `title_room`, `desc_room`, `link_room`, `id_room` · `info_title`, `info_rep`→(`icon_inform`, `item_title`, `item_desc`), `id_info` · `main_iframe_mapa`, `str_title`, `address_item`→(`title_r`, `desc_r`), `id_mapa`

### Options (глобальні дані)
`img_logo`, `logo_link`, `title_head`, `description_head`, `keywords_head` · `phone_rep`→`phone` (link) · `adr_title`, `contact_title`, `foot_delta`, `foot_strit`, `copyright`, `contact_form_shortcode` · `social_rep`→(`social_item` link, `img_soc`), `vlad` · `btn_p_room`, `check`

### [post] News (CPT `news` — використовується і під картки номерів)
`prevyu_room` · `short_character`→`s_h_item` · `desc_features`→`d_f_item` · `opis_nomeru_zagolovok`, `opis_nomeru`, `price` · `room_rep`→`room_img` · `contact_form_shortcode`

## ⚠️ Читання Options: `'options'`, не `'option'`
У цій темі скрізь використовується рядок **`'options'`** (з `s`):
```php
get_field('copyright', 'options');
have_rows('phone_rep', 'options');
```
ACF приймає обидва варіанти, але **не змішуй** — тримайся `'options'` для консистентності з `header.php` / `footer.php`.

## Іменування полів
- snake_case; у нових полях — з префіксом контексту (`hero_title`, `room_price`).
- ⚠️ Історичний неймінг у проєкті **непослідовний** (транслітерація: `opis_nomeru`, `foot_strit`, `vlad`; суфікси `_rep`/`_r`). Нові поля називай нормально англійською — але **старі не перейменовувати**: `name`/`key` після релізу ламає прив'язку даних у БД.
- repeater для повторюваного; group для логічного блоку.

## Читання у шаблонах (безпечно)

```php
// звичайне поле
$title = get_field('about_title');

// зображення (return format = Array)
$img = get_field('img_room');
if ($img) printf('<img src="%s" alt="%s" width="%d" height="%d" loading="lazy">',
  esc_url($img['url']), esc_attr($img['alt']), (int) $img['width'], (int) $img['height']);

// link (масив url / title / target) — завжди перевіряй на порожнечу
$link = get_field('link_room');
if ($link) printf('<a href="%s">%s</a>', esc_url($link['url']), esc_html($link['title']));

// repeater
if (have_rows('info_rep')) {
  while (have_rows('info_rep')) { the_row();
    echo esc_html(get_sub_field('item_title'));
  }
}

// rich text (WYSIWYG) — дозволений HTML
echo wp_kses_post(get_field('kontent'));

// CF7 у полі — шорткод
echo do_shortcode( get_field('contact_form_shortcode', 'options') );
```

Завжди: перевірка на порожнечу + екранування (`esc_html`/`esc_url`/`esc_attr`/`wp_kses_post`). Для зображень — реальний `alt` і `width`/`height` (важливо для [[seo-optimization]] і CLS).

### ⚠️ Інлайн SVG з ACF-поля
`header.php` / `footer.php` роблять `echo file_get_contents( get_attached_file( $img['ID'] ) )` — це працює **лише якщо файл SVG**. Якщо в `img_logo` завантажать PNG/JPG, у сторінку виллється бінарний сміттєвий рядок. При правці цих файлів додавай перевірку:
```php
$file = get_attached_file( $img_logo['ID'] );
if ( $file && strtolower( pathinfo($file, PATHINFO_EXTENSION) ) === 'svg' ) {
    echo wp_kses( file_get_contents( $file ), delta_svg_allowed_tags() );
} else {
    printf('<img src="%s" alt="%s" width="%d" height="%d">', esc_url($img_logo['url']), …);
}
```
SVG-завантаження дозволяє `functions-parts/parts/allow_svg.php`.

## Правка JSON руками (коли без адмінки)
Можна, але обережно: зберігай валідний `key` (`field_…`, `group_…`), `parent`, оновлюй `modified` (unix timestamp). Краще — створити/змінити в адмінці й закомітити згенерований JSON.

## Чого НЕ робити
- ❌ зберігати поля лише в БД без acf-json (втрата версіонування);
- ❌ дублювати `key` між полями;
- ❌ виводити поля без екранування;
- ❌ перейменовувати наявні `name`/`key`;
- ❌ хардкодити в PHP контент, який має бути полем.
