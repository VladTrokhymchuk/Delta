<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <?php // Rank Math керує <title> та мета-описом через wp_head(); ручні теги — лише фолбек, якщо плагін вимкнено.
    if ( ! class_exists( 'RankMath' ) ) : ?>
    <title><?php echo esc_html(get_field('title_head', 'options')) ?></title>
    <meta name="description" content="<?php echo esc_html(get_field('description_head', 'options')) ?>">
    <?php endif; ?>
    <meta name="theme-color" content="#1E4A38"><?php // = --brand-green-700, колір хедера ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet"> -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122481211-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-122481211-1');
    </script>
    <?php wp_head(); ?>
</head>

<body>
    <a class="skip-link" href="#main-content">Перейти до вмісту</a>
    <main <?php body_class(); ?>>

        <header class="header-section" role="banner">
            <div class="container">

                <nav class="header__nav" aria-label="Головне меню">
                    <?php
                        $img_logo = get_field('img_logo', 'options');
                        if ($img_logo):
                        $link = get_field('logo_link', 'options');
                    ?>

                    <a class="header__logo" href='<?= esc_url($link['url']); ?>'
                        title="<?=esc_html( $link['title'] ); ?>" aria-label="<?=esc_attr( $link['title'] ?: get_bloginfo('name') ); ?>">
                        <?php
                            // Логотип — первинний екран, тому fetchpriority замість lazy.
                            delta_render_image( $img_logo, array(
                                'alt'           => get_bloginfo('name'),
                                'fetchpriority' => 'high',
                            ) );
                        ?>
                    </a>


                    <?php endif; ?>

                    <div class='navbar' id="primary-navigation">
                        <?php wp_nav_menu(array(
                            'menu' => 'Second menu',
                            'container'       => 'div',
                            'container_class' => 'menu',
                            'theme_location' => 'primary',
                            'menu_class' => 'menu-list'));
                            ?>
                    </div>

                    <div class="hamburger__box">
                        <button type="button" id="hamburger-button" aria-label="Відкрити меню"
                            aria-expanded="false" aria-controls="primary-navigation">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>

                </nav>
            </div>
        </header>

        <div class='main-wrap' id="main-content">