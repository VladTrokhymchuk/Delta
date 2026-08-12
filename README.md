# Delta — тема WordPress готелю «Дельта»

Класична (не-Gutenberg) тема зі збіркою на Gulp.

## Стек

| Шар | Що використовується |
|---|---|
| CMS | WordPress + плагін `classic-editor` |
| Поля | ACF Pro + `acf-json/` (дефолтний save/load point ACF — фільтри не потрібні) |
| SEO | **Rank Math** (`seo-by-rank-math`) |
| Бронювання | Simple Hotel Booking — CPT `shb_room`, шорткод `[shb_room_calendar]` |
| Форми | Contact Form 7 + Flamingo |
| Збірка | Gulp: `dev/` → `build/` |
| Оптимізація | Autoptimize, WP Super Cache, WebP Express |
| Власні CPT теми | `news` (slug `news-post`) + таксономія `news-types` |

## Команди

```bash
npm install
npm run dev      # gulp watch: dev/ → build/ + browser-sync (основний режим роботи)
npm run build    # прод-білд — лише для фінального QA/релізу
npm run css      # тільки SCSS
npm run js       # тільки JS
```

**`build/` — згенерований.** Правити тільки `dev/`.

## Структура

```
functions.php              # лише include-и
functions-parts/           # логіка теми
  _assets.php              #   умовне підключення CSS/JS по шаблонах
  _post-types-registration.php
  _taxonomies-registration.php
  _breadcrumbs.php         #   кастомізація крихт Rank Math
  _seo-ai.php              #   robots.txt для AI-ботів + /llms.txt
  parts/                   #   rank_math, security, allow_svg, configure_menu…
acf-json/                  # 4 групи полів (версіонуються)
blocks/{page}/block-*.php  # PHP-частини сторінок
template-parts/popups.php
dev/styles/                # SCSS → build/css
  utils/variables/         #   ДЖЕРЕЛО ПРАВДИ по токенах
dev/js/                    # JS  → build/js
```

### Шаблони
`front-page.php` · `page-news.php` · `page-pravila.php` · `single-news.php` · `single-shb_room.php` · `404.php` · `header.php` / `header-second.php` · `footer.php`

### Групи ACF

| Група | Location |
|---|---|
| Головна | `page_template == front-page.php` / `default` |
| Options | `options_page` — лого, контакти, соцмережі, футер, шорткод CF7 |
| [post] News | `post_type == news` |
| Правила | `page_template == page-pravila.php` |

⚠️ Options читаються як `get_field('name', 'options')` — з **`s`**, так у всій темі.

## Конвенції

- **Контент — через ACF**, не хардкод у PHP.
- **Токени дизайну — в одному місці:** `dev/styles/utils/variables/css-vars.scss` (кольори, розміри контейнера) і `scss-vars.scss` (брейкпойнти, шрифти, transition). Не хардкодити кольори по файлах.
- **SCSS-файл на секцію:** `dev/styles/pages/{page}/sections/{section}.scss`, підключається через `dev/styles/main.scss`.
- **Rank Math володіє** `<title>`/meta/OG/canonical/sitemap. Тема їх не дублює — у `header.php` є лише фолбек на випадок вимкненого плагіна.

## Документація

- [`REDESIGN.md`](REDESIGN.md) — дизайн-рішення: палітра, ребрендинг під новий логотип, редизайн сторінки номера.
- [`.claude/README.md`](.claude/README.md) — набір скілів під цей стек (SEO, ACF, редизайн, лінтинг).

## SEO під AI

`functions-parts/_seo-ai.php` дає те, чого не робить Rank Math:
- явний `Allow` для AI-краулерів (GPTBot, ClaudeBot, PerplexityBot, Google-Extended…) у віртуальному `robots.txt`;
- `/llms.txt` — карта контенту для LLM (сторінки, номери з цінами, новини, контакти), кеш 12 год + кнопка **«llms.txt ↻»** в адмін-барі.

Перевірка: `curl -s <сайт>/robots.txt | grep GPTBot` і `curl -s <сайт>/llms.txt`.
