# Project Skills — Готель «Дельта» (WordPress / Gulp тема)

Набір скілів під реальний стек цього проєкту.

## Стек (джерело правди)

| Шар | Що використовується |
|---|---|
| CMS | WordPress, **класичний редактор** (плагін `classic-editor`), без Gutenberg |
| Поля | **ACF Pro** + `acf-json/` (дефолтний save/load point ACF — папка в темі, фільтри не потрібні) |
| SEO | **Rank Math** (`seo-by-rank-math`) — НЕ Yoast |
| Бронювання | **Simple Hotel Booking** — CPT `shb_room`, шорткод `[shb_room_calendar]` |
| Форми | **Contact Form 7** + Flamingo (шорткод зберігається в ACF-полі `contact_form_shortcode`) |
| Збірка | **Gulp** (`gulpfile.js`, `gulp/`): `dev/` → `build/`. Ніякого Vite/manifest.json |
| Кеш/оптимізація | Autoptimize, WP Super Cache, WebP Express |
| Власні CPT теми | `news` (+ таксономія `news-types`) |

### Скрипти
```bash
npm run dev     # gulp watch — автоперезбірка dev/ → build/ (працює постійно)
npm run build   # прод-білд, лише для фінального QA/релізу
npm run css     # тільки SCSS
npm run js      # тільки JS
```

## Структура теми
```
functions.php              # лише include-и
functions-parts/           # логіка теми (_assets, _hooks, _seo-ai, …)
functions-parts/parts/     # дрібні модулі (rank_math.php, security, allow_svg, …)
acf-json/                  # 4 групи полів (версіонуються)
blocks/                    # PHP-частини сторінок: blocks/{page}/block-{name}.php
template-parts/            # popups.php
dev/styles/                # SCSS-джерела  → build/css
dev/js/                    # JS-джерела    → build/js
build/                     # ЗГЕНЕРОВАНЕ — руками не правити
```

### Шаблони сторінок
`front-page.php`, `page-news.php`, `page-pravila.php`, `single-news.php`, `single-shb_room.php`, `404.php`, `header.php` / `header-second.php`, `footer.php`.

### Групи ACF (`acf-json/`)
| Група | Location | Ключові поля |
|---|---|---|
| Головна | `page_template == front-page.php` / `default` | `title`, `subtitle`, `swiper_rep`, `about_*`, `*_room`, `info_rep`, `main_iframe_mapa`, `address_item` |
| Options | `options_page` | `img_logo`, `logo_link`, `phone_rep`, `adr_title`, `foot_delta`, `foot_strit`, `copyright`, `social_rep`, `contact_form_shortcode` |
| [post] News | `post_type == news` | `prevyu_room`, `short_character`, `desc_features`, `opis_nomeru*`, `price`, `room_rep` |
| Правила | `page_template == page-pravila.php` | `kontent` (wysiwyg) |

⚠️ Options читаються як `get_field('name', 'options')` (з **s** — так у всій темі).

## Скіли

| Скіл | Коли спрацьовує |
|---|---|
| **gulp-build** | Збірка: команди, `dev/` → `build/`, бандли SCSS, webpack-задачі JS, як додати сторінку/секцію/шрифт, пастки конфіга. |
| **modern-redesign** | «зроби сучасний редизайн / освіж дизайн» існуючого блоку без переписування з нуля. |
| **acf-fields** | Робота з ACF Pro через `acf-json`: неймінг, безпечне читання, repeater, Options. |
| **seo-optimization** | On-page + технічне SEO з **Rank Math**: семантика, schema, зображення, CWV. |
| **seo-for-ai** | GEO/AEO — щоб GPT/Claude/Perplexity/AI Overviews знаходили й цитували: llms.txt, JSON-LD, доступ краулерам, answer-first. |
| **code-testing** | Лінт/тест PHP (`php -l`, PHPCS/WPCS), JS (ESLint), SCSS (Stylelint), smoke-перевірка білда. |

## Архітектурні засади
- **Контент — через ACF**, не хардкод у PHP. Виняток — статичні підписи інтерфейсу.
- **Токени дизайну — в одному місці:** `dev/styles/utils/variables/css-vars.scss` (CSS-змінні) і `scss-vars.scss` (брейкпойнти, шрифти, transition). Не хардкодити кольори по файлах.
- **SCSS-файл на секцію:** `dev/styles/pages/{page}/sections/{section}.scss`, підключений у бандл сторінки `dev/styles/pages/{page}/{page}.scss`. Бандл імпортує `core` + `main`, тож шаблон enqueue-ить рівно один CSS. Деталі — у скілі **gulp-build**.
- **`build/` — генерований.** Правити лише `dev/`. Під час роботи крутиться `npm run dev` (watch) — ручний `npm run build` не запускати без потреби.
- **Rank Math володіє `<title>`/meta/OG/canonical/sitemap.** Тема їх не дублює (у `header.php` є лише фолбек на випадок вимкненого плагіна).

Дизайн-рішення й палітра — у [`../REDESIGN.md`](../REDESIGN.md). Скіли посилаються один на одного через `[[name]]`.
