---
name: seo-for-ai
description: >-
  Optimize for AI on two axes: (1) be found & cited by AI answer engines (ChatGPT/GPT, Claude,
  Perplexity, Google AI Overviews, Gemini) — GEO/AEO; (2) be operable by AI browsing agents —
  agentic browsing (WebMCP, Lighthouse Agentic Browsing, accessibility & CLS). Covers JSON-LD
  structured data, llms.txt, FAQ/Hotel schema, answer-first content, clean semantics, crawler
  access (GPTBot/ClaudeBot/PerplexityBot), and WebMCP tools/forms for agents. Use for "сео під
  GPT/Claude/AI", AI search, LLM SEO, GEO, AEO, llms.txt, цитованість у чатах, WebMCP, AI-агент
  на сайті, agentic browsing, Lighthouse agent audit.
---

# SEO під AI (GEO/AEO + Agentic Browsing)

Дві РІЗНІ осі — не змішуй їх:

| Вісь | Що це | Розділи нижче |
|------|-------|---------------|
| **GEO / AEO** | Щоб AI-движки **знаходили й цитували** контент. Пасивне читання. | §1–§5 |
| **Agentic Browsing** | Щоб AI-агент **діяв на сайті** — заповнював форму бронювання, проходив флоу. Активна взаємодія. | §6 |

Обидві доповнюють класичне [[seo-optimization]] (Rank Math) і спираються на ту саму чисту семантику. Для готелю GEO особливо цінне: запити «готель у {місто} з …», «скільки коштує номер» ідуть у чат-асистенти, і відповідь формується з того, що бот зміг прочитати на сайті.

## Частина A. GEO / AEO — щоб знаходили й цитували

## 1. Доступ для AI-краулерів (це треба зробити першим)
Без доступу нічого з решти не працює. Дозволені боти: `GPTBot`, `OAI-SearchBot`, `ChatGPT-User`, `ClaudeBot`, `Claude-User`, `PerplexityBot`, `Google-Extended` (керує використанням у Gemini/AI Overviews).

**У цій темі це реалізовано** у [functions-parts/_seo-ai.php](../../../functions-parts/_seo-ai.php) через WP-фільтр `robots_txt` (дописує правила у віртуальний robots.txt; фізичного файлу немає). Rank Math хукається в той самий фільтр і додає `Sitemap:` — конфлікту немає.

Перевірка: `curl -s https://<сайт>/robots.txt`. Переконайся також, що WP Super Cache / хостинг не блокує ці user-agent'и.

## 2. llms.txt
`llms.txt` у корені сайту — карта головного контенту для LLM (llmstxt.org): Markdown зі списком ключових сторінок і коротким описом.

**У цій темі це реалізовано** у `_seo-ai.php`: динамічний роут `^llms\.txt$` (rewrite rule + `template_redirect` з пріоритетом 0, щоб не було 301 на `/llms.txt/`). Карта збирається з:
- верхньорівневих опублікованих сторінок (окрім статичної головної — вона вже є як home URL);
- **номерів** — CPT `shb_room` (Simple Hotel Booking), з ціною (ACF `price`) і короткими характеристиками (repeater `short_character`);
- **новин** — CPT `news`;
- блоку контактів із ACF Options (`foot_delta`, `foot_strit`, `phone_rep`, `social_rep` — ті самі поля, що `footer.php`).

Кеш — transient `delta_llms_txt` (12 год); скидається на `save_post` / `deleted_post` / `acf/save_post` / зміну назви-слогану, а також вручну кнопкою **«llms.txt ↻»** в адмін-барі.

**Що НЕ потрапляє в карту:** дефолтна WP `Sample Page`; записи з Rank Math `noindex` (мета `rank_math_robots` — масив директив, перевірка в `delta_llms_skip()`).

Структура виводу:
```
# Готель «Дельта»

> Короткий опис (з get_bloginfo('description')).

Сайт: https://example.com/

## Сторінки
- [Назва сторінки](https://example.com/slug)

## Номери
- [Двомісний стандарт](https://example.com/rooms/standard): 1200 грн/доба. 2 особи, 18 м², балкон

## Новини
- [Назва матеріалу](https://example.com/news-post/slug): короткий опис.

## Контакти
- Телефон: …
```
Якщо додаси новий CPT, який має бути в карті — розшир `delta_build_llms_txt()`.

## 3. Структуровані дані JSON-LD (головний канал для AI)
Rank Math дає базовий граф (Organization/WebSite/WebPage/BreadcrumbList). Готельну специфіку додавай своєю схемою — через фільтр `rank_math/json_ld` або окремим тегом у `wp_head` (деталі й приклад коду — у [[seo-optimization]]).

Пріоритет типів для цього сайту:
1. **`Hotel`** (підтип `LodgingBusiness`) на головній — `address` (PostalAddress), `telephone`, `geo`, `starRating`, `amenityFeature`, `checkinTime`/`checkoutTime`, `priceRange`, `sameAs` (соцмережі), `image`. Це те, з чого AI будує відповідь «що це за готель».
2. **`HotelRoom`** на `single-shb_room.php` — `name`, `occupancy`, `floorSize`, `amenityFeature`, вкладений `Offer` з `price`/`priceCurrency`/`availability`. Ціна в schema має **збігатися** з видимою на сторінці.
3. **`FAQPage`** — правила заселення, оплата, тварини, паркінг. Дуже добре цитується в AI-відповідях.
4. **`NewsArticle`** для CPT `news` — `headline`, `datePublished`, `dateModified`.

Приклад FAQ з ACF-репітера:
```php
if (have_rows('faq_items')) {
  $schema = ['@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>[]];
  while (have_rows('faq_items')) { the_row();
    $schema['mainEntity'][] = [
      '@type'=>'Question','name'=> get_sub_field('question'),
      'acceptedAnswer'=>['@type'=>'Answer','text'=> wp_strip_all_tags(get_sub_field('answer'))],
    ];
  }
  echo '<script type="application/ld+json">'
     . wp_json_encode($schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . '</script>';
}
```
Готовий FAQ-блок у темі вже є: `blocks/block-faq.php` + `dev/styles/blocks/block-faq.scss`.

## 4. Контент під AEO (answer-first)
LLM витягують конкретні відповіді — структуруй контент так, щоб їх легко витягти:
- **Суть на початку.** Перший абзац секції = пряма відповідь, далі деталі.
- **Питання як заголовки.** `<h2>` у формі питання («Скільки коштує двомісний номер?») + короткий чіткий абзац.
- **Факти у списках/таблицях** — ціни, зручності, час заїзду/виїзду, правила скасування.
- **Самодостатні речення** — без «див. вище».
- **Конкретика й цифри** — ціна, площа, кількість осіб, відстань до центру/вокзалу.
- **Сутності явно текстом** — назва готелю, місто, район. Не лише в зображенні.
- **Дати оновлення** — `dateModified` у schema + видима дата; свіжість впливає на цитування (особливо для цін).

Наповнюється це через ACF-поля номерів (`short_character`, `desc_features`, `price`, `opis_nomeru`) — див. [[acf-fields]]. Тобто якість AI-відповіді залежить від того, наскільки конкретно менеджер заповнив поля.

## 5. Технічна доступність контенту
- Контент має бути в **HTML на сервері** (PHP-рендер), не довантажуватись лише JS — багато AI-краулерів не виконують JS. У цій темі секції рендеряться PHP — добре.
  ⚠️ Виняток: календар доступності `[shb_room_calendar]` і CF7 підвантажуються/оживають на JS. Ключові факти (ціна, зручності, контакти) мають бути в статичному HTML **поза** цими віджетами.
- Семантика й один H1 (див. [[seo-optimization]]).
- Чисті, осмислені URL. ⚠️ Slug CPT `news` — `news-post`; для новин це прийнятно, але при змінах пам'ятай про редіректи.

## Частина B. Agentic Browsing — щоб AI-агент діяв на сайті

Джерело: Chrome «Agent-Ready Toolkit» (developer.chrome.com/blog/agent-ready-toolkit). Для готелю це найцікавіша вісь у перспективі: агент, який реально **бронює номер** від імені користувача.

## 6. Три речі, які перевіряє Chrome-тулкіт
1. **Accessibility** — семантичний HTML + програмні імена елементів (`<button>`/`<a>` за призначенням, `aria-label`, `<label>` до інпутів). Агент «бачить» сайт через дерево доступності, не піксельно. Це той самий чек-лист, що й у [[seo-optimization]] — виконуй його, і цю вісь на 80% закрито безкоштовно.
   Конкретно тут: поля CF7-форми бронювання мають мати справжні `<label>`, а не лише `placeholder`.
2. **Stability (CLS)** — мінімальний layout shift, щоб агент не промахувався по кнопках. `width`/`height`/`aspect-ratio` зображенням; зарезервоване місце під swiper і календар SHB (обидва вставляються після завантаження JS — типове джерело CLS у цій темі).
3. **WebMCP** — сайт декларує машиночитабельні «інструменти» й форми (Model Context Protocol для веб). Найкорисніше саме під транзакційні флоу — перевірка вільних дат і заявка на бронювання. **Статус: експериментальний** (Chrome M150+, прапорець `#enable-webmcp-testing`) — фіксуємо як напрям, не впроваджуємо без потреби.

**Інструмент перевірки:** Lighthouse → категорія **Agentic Browsing** (Chrome M150+) + Chrome DevTools for Agents.

## Чек-ліст під AI-пошук (GEO/AEO)
- [ ] AI-боти дозволені в robots.txt (реалізовано в `_seo-ai.php`)
- [ ] llms.txt віддається й актуальний (реалізовано в `_seo-ai.php`)
- [ ] `Hotel` schema з адресою, телефоном, зручностями — на головній
- [ ] `HotelRoom` + `Offer` з ціною на кожному номері; ціна збігається з видимою
- [ ] `FAQPage` на правилах/умовах заселення
- [ ] answer-first контент, питання в заголовках
- [ ] ціни й зручності — у серверному HTML, не лише всередині JS-віджетів
- [ ] `dateModified` оновлюється

## Чек-ліст під agentic browsing
- [ ] семантичні `<button>`/`<a>`/`<label>` у формі бронювання
- [ ] низький CLS (розміри зображень, місце під swiper і календар SHB)
- [ ] прогнати Lighthouse «Agentic Browsing» (Chrome M150+)
- [ ] WebMCP — оцінити під форму бронювання (поки на радарі)

## Чого НЕ робити
- ❌ ховати ціни/зручності за клієнтським JS-фетчем;
- ❌ schema, що не відповідає видимому контенту (за розбіжність ціни карають);
- ❌ блокувати AI-ботів «про всяк випадок», якщо мета — цитованість;
- ❌ «вода» без фактів — AI цитує конкретику.
