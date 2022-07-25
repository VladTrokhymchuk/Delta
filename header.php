<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title><?php the_field('title_head', 'options') ?></title>
    <meta name="description" content="<?php the_field('description_head', 'options') ?>">
    <meta name="keywords" content="<?php the_field('keywords_head', 'options') ?>">
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
    <main <?php body_class(); ?>>

        <header class="header-section">
            <div class="container">

                <div class="header__nav">
                    <?php
                        $img_logo = get_field('img_logo', 'options');
                        if ($img_logo):
                        $link = get_field('logo_link', 'options');
                    ?>

                    <a class="header__logo" href='<?= esc_url($link['url']); ?>'
                        title="<?=esc_html( $link['title'] ); ?>">
                        <?php
                            $svg_markup = file_get_contents( get_attached_file( $img_logo['ID'] ) );
                            echo $svg_markup;
                        ?>
                    </a>


                    <?php endif; ?>

                    <div class='navbar'>
                        <?php wp_nav_menu(array(
                            'menu' => 'Fullpage menu',
                            'container'       => 'div',
                            'container_class' => 'menu',
                            'theme_location' => 'primary',
                            'menu_class' => 'menu-list'));
                            ?>
                    </div>

                    <div class="hamburger__box">
                        <div id="hamburger-button">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <div class='main-wrap'>