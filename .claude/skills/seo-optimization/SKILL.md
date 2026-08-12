---
name: seo-optimization
description: >-
  On-page & technical SEO with Rank Math — semantic HTML, single H1, meta/OpenGraph,
  JSON-LD schema, XML sitemap, image alt & lazy-load, internal linking, Core Web Vitals/
  performance. Use for SEO, "сео оптимізація", Rank Math, мета-теги, schema, structured data,
  перформанс, sitemap, canonical, хлібні крихти.
---

# SEO-оптимізація (Rank Math + технічне SEO)

SEO-движок проєкту — **Rank Math** (плагін `seo-by-rank-math`). Тема НЕ дублює того, що робить Rank Math — вона дає чисту семантику й коректний markup. Для оптимізації під AI — окремий скіл [[seo-for-ai]].

## Розподіл відповідальності
- **Rank Math робить:** `<title>`, meta description, canonical, OpenGraph/Twitter, `robots`, XML sitemap (`/sitemap_index.xml`), базову schema (Organization/WebSite/WebPage/BreadcrumbList), хлібні крихти.
- **Тема робить:** семантичну розмітку, ієрархію заголовків, alt/lazy зображень, внутрішні посилання, продуктивність, додаткову JSON-LD під готельні сутності.

## Що вже налаштовано в темі
| Файл | Що робить |
|---|---|
| `functions-parts/parts/rank_math.php` | Фільтри sitemap: прибирає `/golovna` з URL, вимикає archive-лінки CPT |
| `functions-parts/_breadcrumbs.php` | Кастомізує крихти через `rank_math/frontend/breadcrumb/items` |
| `header.php` | `<title>`/`description` виводяться **лише** якщо `! class_exists('RankMath')` — фолбек |
| `functions-parts/_seo-ai.php` | robots.txt для AI-ботів + `llms.txt` (див. [[seo-for-ai]]) |

Виводити крихти в шаблоні:
```php
if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
    rank_math_the_breadcrumbs();
}
```

## Семантика та заголовки
- **Один `<h1>` на сторінку** — у hero. Решта секцій — `<h2>`, підрозділи `<h3>`. Не перестрибувати рівні.
- Семантичні теги: `<header> <main> <section> <article> <nav> <footer> <address>`. Кожна секція — `<section>`.
- Списки/таблиці справжніми тегами, не `<div>`.
- Landmark-доступність = SEO: `<nav aria-label>`, кнопки/посилання за призначенням.
- ⚠️ У цій темі `<main>` обгортає весь документ разом із `<header>`/`<footer>` (`header.php` → `<main <?php body_class(); ?>>`). Це відхилення від стандарту; якщо чіпаєш ці файли — виправляй на `<div class="page-wrap">` + `<main id="main-content">` навколо контенту.

## Зображення
- реальний `alt` з контексту (з ACF або заголовка), не назва файлу;
- `loading="lazy"` усім, КРІМ first-screen (там `fetchpriority="high"`);
- `width`/`height` або `aspect-ratio` проти CLS;
- WebP роздає плагін **WebP Express** — окремих `<picture>` руками не будуємо.

## Rank Math: інтеграція
- НЕ виводь власні `<title>`/meta/canonical/OG — це робить плагін.
- Для CPT (`news`, `shb_room`) увімкни їх у Rank Math → Titles & Meta і задай шаблони title/description.
- Службовий CPT, який не має бути в індексі — `noindex` там же.
- Sitemap: Rank Math → Sitemap Settings. Правки URL — у `functions-parts/parts/rank_math.php`.

## Додаткова JSON-LD
Rank Math дає базову схему; готельну специфіку додавай своєю. Два шляхи:

**А. Влитись у граф Rank Math** (краще — без дублювання `@id`):
```php
add_filter( 'rank_math/json_ld', function ( $data, $jsonld ) {
    if ( ! is_singular( 'shb_room' ) ) return $data;
    $data['hotelRoom'] = [
        '@context' => 'https://schema.org',
        '@type'    => 'HotelRoom',
        'name'     => get_the_title(),
        'url'      => get_permalink(),
    ];
    return $data;
}, 99, 2 );
```

**Б. Окремим тегом у `wp_head`** — коли схема не мапиться на граф (наприклад `FAQPage` з ACF-репітера).

Релевантні типи для готелю: `Hotel` / `LodgingBusiness` (головна), `HotelRoom` + `Offer` з ціною (сторінка номера), `NewsArticle` (CPT `news`), `FAQPage`, `BreadcrumbList` (вже від Rank Math).

Куди класти код: новий файл у `functions-parts/` + `include_once` у `functions.php` (там лише include-и).

## Технічна продуктивність (Core Web Vitals)
- CSS/JS збирає **Gulp** (`npm run build` → `build/`); мініфікацію/конкатенацію додатково робить **Autoptimize**, сторінковий кеш — **WP Super Cache**.
- Ассети підключаються умовно в `functions-parts/_assets.php` — не вантаж page-bundle там, де він не потрібен.
  ⚠️ Зараз там є мертва гілка `is_page_template('page-about.php')` (такого шаблону в темі немає), через яку swiper/front-page не підключаються за умовою. Перевіряй реальні імена шаблонів.
- JS у футері (`wp_enqueue_script(..., true)`).
- `font-display: swap` у `dev/styles/utils/fonts.scss` (зараз там помилка — `swup` замість `swap`, шрифти блокують рендер).
- мінімум сторонніх скриптів; аналітику — з `defer`.

## Чого НЕ робити
- ❌ дублювати title/meta/OG, які генерує Rank Math;
- ❌ кілька `<h1>` або порушена ієрархія заголовків;
- ❌ `alt`=ім'я файлу або порожній на змістовних зображеннях;
- ❌ правити `build/` замість `dev/`.
