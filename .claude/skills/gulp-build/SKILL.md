---
name: gulp-build
description: >-
  Робота зі збіркою теми на Gulp — команди, мапа dev/ → build/, архітектура SCSS-бандлів
  і webpack-задачі для JS, як додати секцію/сторінку/модуль/шрифт/картинку, і відомі пастки
  конфіга. Use for gulp, збірка, білд, build, watch, "стилі не оновились", "css не підтягнувся",
  browser-sync, webpack, новий бандл, нова сторінка, додати SCSS-файл, dev/ build/.
---

# Збірка теми (Gulp)

Тема збирається **Gulp 4** (ESM, `"type": "module"`): джерела в `dev/`, вивід у `build/`. Ніякого Vite/manifest.json — це не той стек, що в інших проєктах.

## Команди

```bash
npm run dev     # gulp (default) — основний режим: reset → збірка → watch + browser-sync
npm run build   # gulp build --build — прод-білд (мініфікація, autoprefixer, webp)
npm run css     # gulp scssCompress — тільки SCSS, завжди «прод»-режимом
npm run js      # gulp js — тільки JS
```

**Робочий режим — `npm run dev` у фоні.** Він перезбирає на кожну зміну в `dev/`. Не запускай `npm run build` вручну, поки watch працює: обидва починають з `reset`, який чистить `build/`, і вони конфліктують.

### Прапорець `--build`
`gulpfile.js` виставляє `global.app.isBuild = process.argv.includes('--build')`. Від нього залежить, чи вмикаються `groupCssMediaQueries`, `autoprefixer`, `webpcss`, `cleanCss`, `imagemin`, `webp`. Тобто **`npm run build` без `--build` дасть немініфікований вивід** — тому в `package.json` прапорець уже прописаний, не прибирай його.

## Мапа шляхів (`gulp/config/path.js`)

| Задача | Джерело | Призначення |
|---|---|---|
| `scss` | `dev/styles/**/*.{sass,scss,css}` | `build/css/` (з `.min.css`) |
| `js` | `dev/js/**/*.js` (webpack) | `build/js/` |
| `images` | `dev/img/**/*.*` | `build/img/` |
| `copy` (шрифти) | `dev/fonts/**/*.*` | `build/fonts/` |
| `html` | `dev/*.html` | `build/` — файлів немає, задача холоста |

## Сценарії (`gulpfile.js`)

```
dev   = reset → parallel(copy, html, scss, js, images) → parallel(watcher, server)
build = reset → parallel(copy, html, scss, js, images)
```

## Архітектура SCSS — головне, що треба розуміти

Три рівні:

| Файл | Роль | Компілюється в CSS? |
|---|---|---|
| `dev/styles/core.scss` | **тільки** змінні/міксини/утиліти (`scss-vars`, `mixins`, `sup-classes`) — нічого не друкує | ні (порожній вивід) |
| `dev/styles/main.scss` | глобальні стилі: `css-vars`, `fonts`, `reset`, header, footer, buttons, popup | так, але **нікуди не підключається** |
| `dev/styles/pages/{page}/{page}.scss` | **бандл сторінки** — імпортує `core` + `main` + свої секції | так — це те, що реально вантажиться |

```scss
// dev/styles/pages/front-page/front-page.scss
@import '../../core';        // змінні/міксини (без виводу)
@import '../../main';        // ГЛОБАЛЬНІ стилі — тому бандл самодостатній
@import './sections/front-page-head';
@import './sections/front-page-about';
```

**Ключове:** кожен бандл сторінки **містить у собі всю глобальну частину**. Тому шаблон підключає рівно **один** файл стилів, а `main.min.css` окремо не enqueue-иться ніде (це побічний продукт).

Підключення — **у самому шаблоні сторінки**, не в `_assets.php`:
```php
// front-page.php
wp_enqueue_style('front-page', get_template_directory_uri() . '/build/css/pages/front-page/front-page.min.css');
```

⚠️ Це означає, що глобальні стилі (header/footer) дублюються в кожному бандлі. Так задумано (одна мережева, повний CSS), але при правці `main.scss` **перевіряй усі сторінки**, а не одну.

## Архітектура JS (`gulp/tasks/js.js`)

Не один webpack-конфіг, а **ланцюг викликів webpack — по одному на кожну папку**:

```
dev/js/*.js                    → build/js/
dev/js/pages/*.js              → build/js/pages/
dev/js/pages/front-page/*.js   → build/js/pages/front-page/
dev/js/pages/single-news/*.js  → build/js/pages/single-news/
dev/js/modules/*.js            → build/js/modules/
dev/js/libs/*.js               → build/js/libs/
```

Кожен блок — `glob_entries('./dev/js/<папка>/*.js')`, `mode: 'production'` (JS мініфікується **завжди**, і в dev теж).

⚠️ **Глоби не рекурсивні.** Якщо створиш `dev/js/pages/page-news/main.js`, воно **не збереться** — треба дописати ще один блок у `gulp/tasks/js.js`:
```js
.pipe(webpack({ entry: glob_entries('./dev/js/pages/page-news/*.js'), mode: 'production' }))
.pipe(app.gulp.dest('./build/js/pages/page-news/'))
```

## Рецепти

### Додати секцію до наявної сторінки
1. `dev/styles/pages/{page}/sections/{section}.scss`
2. `@import './sections/{section}';` у бандлі `dev/styles/pages/{page}/{page}.scss`
3. PHP-частина — `blocks/{page}/block-{section}.php`
4. Watch підхопить сам. Нічого в gulp правити не треба.

### Додати нову сторінку
1. `dev/styles/pages/{page}/{page}.scss` з обов'язковими `@import '../../core'; @import '../../main';`
2. `{page}.php` у корені теми + `wp_enqueue_style(...)` на початку шаблону (шлях `build/css/pages/{page}/{page}.min.css`)
3. Якщо потрібен свій JS — папка `dev/js/pages/{page}/` **і новий блок у `gulp/tasks/js.js`** (див. вище)

### Додати шрифт
Клади готовий `.woff2` у `dev/fonts/` → задача `copy` перенесе в `build/fonts/`, далі `@font-face` у `dev/styles/utils/fonts.scss`.
⚠️ Задача автоконвертації `fonts = series(otfToTtf, ttfToWoff, fontsStyle)` у `gulpfile.js:37` **оголошена, але не підключена до жодного сценарію** і не експортована — вона ніколи не виконується. Конвертуй у woff2 сам.

### Додати зображення
`dev/img/` (не `dev/images/` — див. пастки). У прод-білді проганяється через `imagemin` + `webp`.

## Відомі пастки конфіга

Це не помилки, які треба «попутно» чинити — це те, про що треба знати, щоб не витрачати годину на діагностику.

1. **`reset` видаляє весь `build/`.** `deleteAsync('./build')` на старті `dev` і `build`. Усе, що покладено в `build/` руками, зникне назавжди. Кладеш тільки в `dev/`.
2. **browser-sync марний для WordPress.** `gulp/tasks/server.js` піднімає `server: { baseDir: './build/' }` — статичний сервер по папці `build/` на `localhost:3000`. Сайт це не відкриє (WP крутиться в Local/Flywheel). Живого релоаду немає — оновлюй сторінку вручну. Щоб він запрацював, треба замінити `server` на `proxy: '<сайт>.local'`.
3. **Компілюється кожен SCSS-файл, а не тільки бандли.** `path.src.scss` бере `dev/styles/**/*.{sass,scss,css}`; фільтр `path.src.scss_ignore` у `path.js:20` **оголошений, але не переданий у `gulp.src`**. Тому в `build/css/` лежать сміттєві файли на кшталт `utils/mixins.min.css`, `utils/variables/css-vars.min.css` (порожні або майже). Ігноруй їх — enqueue-иш тільки бандли сторінок.
4. **`@img/` резолвиться по-різному.** У задачі `scss` (`npm run dev`) `@img/` → `../img/`, а в `scssCompress` (`npm run css`) → `../../img/`. Тому **не змішуй режими**: якщо зібрав щось через `npm run css`, шляхи до картинок можуть поламатись. Для повсякденної роботи використовуй `npm run dev`. У поточних стилях `@img/` не використовується — не починай.
5. **`dev/images/` — мертва папка.** Пайплайн читає `dev/img/`. Файли в `dev/images/` не потраплять у білд.
6. **`gulp/tasks/svgSprive.js` і `gulp/tasks/zip.js` не імпортовані** в `gulpfile.js` — недоступні задачі.
7. **JS завжди мініфікований** (`mode: 'production'` в обох режимах) — дебажити в браузері незручно; читай `dev/js/`.
8. **`gulp/version.json`** — у `.gitignore`, у задачах не використовується. Артефакт.

## Чого НЕ робити

- ❌ правити `build/` — його стирає `reset` при кожному запуску;
- ❌ запускати `npm run build` паралельно з активним watch;
- ❌ прибирати `--build` з npm-скрипта `build`;
- ❌ enqueue-ити `main.min.css` або `core.min.css` — глобалка вже всередині бандла сторінки;
- ❌ створювати підпапку в `dev/js/pages/` і чекати, що вона збереться сама;
- ❌ лінтувати/чіпати `dev/js/libs/` і `dev/styles/libs/` — вендорні файли ([[code-testing]]).

Пов'язане: [[code-testing]] (лінт і smoke-перевірка білда), [[modern-redesign]] (де лежать токени стилів).
