<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title><?php wp_title(); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body>


    <main <?php body_class(); ?>>

        <header class="header">
            <div class="container">
                <div class="header__cont">

                    <?php if (is_front_page()): ?>
                    <div class="header__menu">
                        <?php wp_nav_menu(array(
                                'menu' => 'Fullpage menu',
                            )) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class='main-wrap'>