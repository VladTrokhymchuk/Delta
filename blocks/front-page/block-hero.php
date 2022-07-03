<section class="h-hero-section" data-hero-section>
    <div class="h-hero">
         <!-- <canvas id="canvas"></canvas> -->
        <div class="h-hero__track">

            <div class="h-hero__sticky">
                <div class="h-hero__canvas" data-hero-canvas-place></div>
                <div class="h-hero__holder" data-hero-holder>
                    
                    <!-- 1 -->
                    <?php 
                        $main_screen = get_field('hero_main_screen');
                        $head_logo = $main_screen['head_logotype'];
                    ?>
                    <div class="h-hero__step h-hero-main" data-hero-step="main">
                        <div class="container">
                            <div class="h-hero-main__left">
                                <div class="h-hero-main__head" data-hero-head>
                                    <span><?= $main_screen['head_subtitle'] ?></span>
                                    <?php if ($head_logo): ?>
                                        <div class="h-hero-main__head__logo" data-hero-head-logo>
                                            <img src="<?= $head_logo['sizes']['medium'] ?>" alt="">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="h-hero-main__title" data-hero-title>
                                    <h1>
                                        <div class="h-hero-main__title__prefix" data-hero-title-prefix>
                                            <span><?= $main_screen['main_title_1'] ?></span>
                                        </div>
                                        <span><?= $main_screen['main_title_2'] ?></span>
                                    </h1>
                                    <div class="h-hero-main__title__end">
                                        <div class="inner" data-hero-title-end>
                                            <span><?= $main_screen['main_title_hidden_part'] ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="h-hero-main__flyingtext">
                                    <div class="inner h-hero-main__flyingtext__fake"  data-hero-flying-text-fake>
                                        <span><?= $main_screen['flying_text'] ?></span>
                                    </div>
                                    <div class="inner" data-hero-flying-text>
                                        <span><?= $main_screen['flying_text'] ?></span>
                                    </div>
                                </div>

                                <div class="h-hero-main__cont" data-hero-cont>
                                    <span class="h-hero-main__subtitle"><?= $main_screen['main_subtitle'] ?></span>
                                    <div class="h-hero-main__text">
                                        <span class="apercu"><?= $main_screen['main_text_font_1'] ?></span>
                                        <span class="boska"><?= $main_screen['main_text_font_2'] ?></span>
                                    </div>
                                </div>

                                <div data-hero-bottom>

                                    <div class="h-hero-main__btns">
                                        <div class="h-hero-main__btn">
                                            <?php $btn_1 = $main_screen['btn_1'];
                                            if ($btn_1): ?>
                                                <a href="<?= $btn_1['url']; ?>" 
                                                    class="btn btn--accent-2">
                                                    <span class="btn__front"><?= $btn_1['title'] ?></span>
                                                    <span class="btn__back"><?= $btn_1['title'] ?></span>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <div class="h-hero-main__btn">
                                            <?php $btn_2 = $main_screen['btn_2'];
                                            if ($btn_2): ?>
                                                <a href="<?= $btn_2['url']; ?>" 
                                                    class="btn btn--accent-1">
                                                    <span class="btn__front"><?= $btn_2['title'] ?></span>
                                                    <span class="btn__back"><?= $btn_2['title'] ?> </span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="h-hero-main__arrowdown">
                                        <?php print_svg_ic('arrow-down-bold'); ?>
                                        <span><?= $main_screen['scroll_down_label'] ?></span>
                                        <?php print_svg_ic('arrow-down-bold'); ?>
                                    </div>

                                    <div class="round-text-arrow-btn">
                                        <svg viewBox="0 0 100 100">
                                            <defs>
                                                <path id="circle"
                                                d="
                                                    M 50, 50
                                                    m -37, 0
                                                    a 37,37 0 1,1 74,0
                                                    a 37,37 0 1,1 -74,0"/>
                                            </defs>
                                            <text>
                                                <textPath xlink:href="#circle">
                                                    <?= $main_screen['scroll_down_label'] ?> &nbsp; 
                                                    <?= $main_screen['scroll_down_label'] ?> &nbsp; 
                                                    <?= $main_screen['scroll_down_label'] ?> &nbsp;
                                                </textPath>
                                            </text>
                                        </svg>

                                        <?php print_svg_ic('arrow-in-circle-text') ?>
                                    </div>

                                </div>
                            </div>
                            
                            <div class="h-hero-main__right" data-hero-facts>
                                <div class="h-hero-main__grid">
                                    <?php 
                                    $main_facts = $main_screen['main_facts'];
                                    if ($main_facts):
                                        foreach ($main_facts as $main_facts_item):
                                            if ($main_facts_item['fact_or_logo'] == 'logo'): 
                                                $logo = $main_facts_item['logo'];
                                                if ($logo): ?>
                                                <div class="h-hero-main__grid__item">
                                                    <img src="<?= $logo['sizes']['medium'] ?>" alt="">
                                                </div>
                                            <?php endif; 
                                            endif;

                                            if ($main_facts_item['fact_or_logo'] == 'fact'): ?>
                                                <div class="h-hero-main__grid__item">
                                                    <strong><?= $main_facts_item['fact_title'] ?></strong>
                                                    <span><?= $main_facts_item['fact_text'] ?></span>
                                                </div>
                                            <?php endif;
                                        endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2 -->
                    <?php $person_screen = get_field('hero_person_screen'); ?>
                    <div class="h-hero__step h-hero-person" data-hero-step="person">
                        <div class="h-hero-person__inner">
                            <div class="container">
                                <?php $person_img = $person_screen['person_image'];
                                if ($person_img): ?>
                                    <div class="h-hero-person__img">
                                        <img src="<?= $person_img['sizes'][isMobile() ? 'medium_large' : 'large' ]; ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="h-hero-person__text">
                                    <span><?= $person_screen['person_text'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4 -->
                    <?php $one_line_text_screen = get_field('hero_one_line_step'); ?>
                    <div class="h-hero__step h-hero-string" data-hero-step="string">
                        <div class="h-hero-string__inner">
                            <div class="container">
                                <div class="h-hero-string__title">
                                    <span><?= $one_line_text_screen['one_line_text'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5 -->
                    <?php $hero_menu_screen = get_field('hero_menu_screen'); ?>
                    <div class="h-hero__step h-hero-menu" data-hero-step="menu">
                        <div class="h-hero-menu__inner">
                            <div class="container">
                                <ul>
                                    <?php $menu = $hero_menu_screen['menu_list'];
                                    if ($menu):
                                        foreach ($menu as $menu_item):
                                            $link = $menu_item['menu_link'];
                                            if ($link): ?>
                                                <li>
                                                    <a href="<?= $link['url'] ?>"><?= $link['title'] ?></a>
                                                </li>
                                            <?php
                                            endif; 
                                        endforeach;
                                    endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3 -->
        <?php $bullets_screen = get_field('hero_bullets_screen'); ?>
        <div class="h-hero__step h-hero-bullets" data-hero-step="bullets">
            <div class="h-hero-bullets__inner">
                <div class="container">
                    <span class="h-hero-bullets__title">
                        <span class="apercu"><?= $bullets_screen['bullets_title_1'] ?></span>
                        <span class="boska"><?= $bullets_screen['bullets_title_2'] ?></span>
                    </span>
                    <ul>
                        <?php $bullets = $bullets_screen['bullets_list'];
                        if ($bullets):
                              foreach ($bullets as $b): ?>
                                <li><?= $b['bullet_text'] ?></li>
                        <?php endforeach;
                        endif; ?>
                    </ul>
                </div>
            </div>
        </div>
       
    </div>
</section>