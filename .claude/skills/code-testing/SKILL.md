---
name: code-testing
description: >-
  Test and lint the theme codebase — PHP (WPCS/PHPCS, PHPStan, WP best practices), JS
  (ESLint), SCSS (Stylelint), plus Gulp build & render smoke checks.
  Use for "тестування", "перевір код", lint, linting, phpcs, eslint, stylelint,
  "чи валідний код", before commit/release QA, або коли треба переконатись що код не зламаний.
---

# Тестування та лінтинг коду (PHP / JS / SCSS)

Мета — впіймати помилки до релізу: статичний аналіз + лінтери + smoke-перевірка білда й рендера. Тест-раннерів у проєкті немає, тож скіл і ставить інструменти, і запускає.

## Перед будь-якою перевіркою
- Локальне середовище — **Local (Flywheel)**; PHP/WP-CLI доступні через нього або системний `php`.
- Джерела — тільки `dev/`. `build/` і `node_modules/` завжди виключай із лінту.

## 1. PHP

### Синтаксис (швидкий чек, без залежностей)
```bash
find . -name '*.php' -not -path './node_modules/*' -not -path './vendor/*' -not -path './build/*' -print0 \
  | xargs -0 -n1 -P4 php -l
```

### WordPress Coding Standards (PHPCS + WPCS)
```bash
composer require --dev wp-coding-standards/wpcs dealerdirect/phpcodesniffer-composer-installer
./vendor/bin/phpcs  --standard=WordPress --extensions=php \
  --ignore=*/vendor/*,*/node_modules/*,*/build/*,*/dev/* .
./vendor/bin/phpcbf --standard=WordPress .   # автофікс
```
Перевіряє неймінг, екранування виводу (критично — узгоджено з [[acf-fields]]), nonce, санітизацію.

⚠️ Тема писана до WPCS — «чистого» прогону не буде. Не запускай `phpcbf` на всю тему одним махом; лінтуй **лише файли, які змінюєш**:
```bash
./vendor/bin/phpcs --standard=WordPress functions-parts/_seo-ai.php
```

### PHPStan
```bash
composer require --dev phpstan/phpstan szepeviktor/phpstan-wordpress
./vendor/bin/phpstan analyse --level=5 functions.php functions-parts/ blocks/
```

**На що дивитись у WP-PHP вручну:** усе виводиться через `esc_html/esc_url/esc_attr/wp_kses_post`; форми мають nonce + перевірку прав; немає прямих SQL без `$wpdb->prepare`; хуки на `init`/`wp_enqueue_scripts`; ACF-масиви (`link`, `image`) перевіряються на порожнечу перед розіменуванням.

## 2. JavaScript (ESLint)
```bash
npm i -D eslint @eslint/js
npx eslint "dev/js/**/*.js" --ignore-pattern "dev/js/libs/**" --ignore-pattern "dev/js/jquery-*"
```
Мінімальний `eslint.config.js` (flat config, ESM — проєкт `"type":"module"`):
```js
import js from '@eslint/js';
export default [
  js.configs.recommended,
  { ignores: ['node_modules/**', 'build/**', 'dev/js/libs/**', 'dev/js/jquery-*.js'] },
  { languageOptions: { ecmaVersion: 'latest', sourceType: 'module',
      globals: { window: 'readonly', document: 'readonly', jQuery: 'readonly', $: 'readonly', Swiper: 'readonly' } },
    rules: { 'no-unused-vars': 'warn', 'no-undef': 'error' } },
];
```
`dev/js/libs/` — мініфіковані вендорні бандли (swiper, wow, scrollMagic, SmoothScroll), їх лінтувати немає сенсу.

## 3. SCSS (Stylelint)
```bash
npm i -D stylelint stylelint-config-standard-scss
npx stylelint "dev/styles/**/*.scss" --ignore-pattern "dev/styles/libs/**"
```
`.stylelintrc.json`:
```json
{ "extends": "stylelint-config-standard-scss",
  "ignoreFiles": ["dev/styles/libs/**", "build/**", "node_modules/**"],
  "rules": { "no-descending-specificity": null, "scss/at-rule-no-unknown": true } }
```
Проєкт використовує старий `@import '../../core'` (не `@use`) — це LibSass-стиль, який sass позначає deprecated. Міграція на `@use` — окрема задача, не роби її «попутно».

## 4. Build & render smoke-test
**Білд — Gulp**; повний опис збірки, шляхів і пасток — у [[gulp-build]]. Під час роботи зазвичай крутиться watch:
```bash
npm run dev     # gulp watch: dev/ → build/, browser-sync
```
**НЕ запускай `npm run build` вручну**, поки працює watch — дочекайся, поки він оновить `build/`. Ручний прод-білд — лише для фінального QA/релізу:
```bash
npm run build   # gulp build --build
npm run css     # тільки SCSS
npm run js      # тільки JS
```
Після білда:
- перевір, що змінені файли реально з'явились у `build/css/` та `build/js/` (порівняй mtime);
- відкрий ключові сторінки локально: головна, сторінка номера (`shb_room`), новини, правила;
- консоль браузера без JS-помилок; swiper і календар SHB ініціалізуються; CF7 сабмітиться;
- перевір, що потрібний page-bundle підключився — логіка в `functions-parts/_assets.php`.

## 5. Специфічні перевірки цієї теми
- `curl -s localhost/robots.txt | grep GPTBot` — правила AI-ботів на місці ([[seo-for-ai]]).
- `curl -s localhost/llms.txt | head -30` — карта віддається без 301 і не порожня.
- ACF: після зміни полів — ACF → Field Groups → Sync, і `git status acf-json/` має показати оновлений JSON ([[acf-fields]]).
- Rank Math не вимкнено → у `<head>` немає дубльованих `<title>` (фолбек у `header.php` спрацьовує лише без плагіна).

## Рекомендований порядок «перевір усе»
```bash
find . -name '*.php' -not -path './node_modules/*' -not -path './build/*' -print0 | xargs -0 -n1 php -l
npx stylelint "dev/styles/**/*.scss" --ignore-pattern "dev/styles/libs/**"
npx eslint "dev/js/**/*.js" --ignore-pattern "dev/js/libs/**"
npm run build      # лише якщо watch не працює
```

## Чого НЕ робити
- ❌ комітити код, що не проходить `php -l`;
- ❌ ганяти `phpcbf` по всій темі — це створить величезний нечитабельний діф;
- ❌ лінтувати `build/`, `node_modules/`, `dev/js/libs/`, `dev/styles/libs/`;
- ❌ правити згенерований `build/` руками — тільки `dev/`.
