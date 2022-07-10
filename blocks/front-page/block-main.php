<section class="front-head-section">
    <div class="container">
        <div class="front-head__info">
            <h1 style="color: crimson; margin-bottom: 50px;">Сайт тимчасово на реконструкції, телефонуйте за номерами
            </h1>
            <h1><?php the_field('title'); ?></h1>
            <p><?php the_field('subtitle'); ?></p>
        </div>
        <div class="front-head__swip">
            <div class="front-head__numb">
                <ul>
                    <?php if( have_rows('phone_rep', 'options') ): while( have_rows('phone_rep', 'options') ): the_row(); ?>
                    <?php $phone = get_sub_field('phone');?>
                    <li><a href='<?=esc_url( $phone['url'] ); ?>'> <?=esc_html( $phone['title'] ); ?> </a></li>
                    <?php endwhile; endif; ?>
                </ul>
            </div>
            <div class="front-head__lay">
                <?php $img_logo = get_field('img_logo', 'options'); 
                    if ($img_logo):
                ?>
                <div class="front-head__lay__logo">
                    <div class="front-head__lay__logo__img">
                        <?php
                                $svg_markup = file_get_contents( get_attached_file( $img_logo['ID'] ) );
                                echo $svg_markup;
                            ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php if( have_rows('swiper_rep') ): while( have_rows('swiper_rep') ): the_row(); ?>
                        <div class="swiper-slide">
                            <?php $img_r = get_sub_field('img_r');?>
                            <div class="front-head__swip__img">
                                <img src='<?=esc_url( $img_r['sizes'][isMobile() ? 'medium_large' : 'full_hd'] ); ?>'
                                    alt='<?=esc_attr($img_r['alt']); ?>'>
                            </div>
                        </div>
                        <?php endwhile; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="front-about-section">
    <div class="container">
        <div class="front-about">
            <h3><?php the_field('about_title'); ?></h3>
            <p><?php the_field('about_desc'); ?></p>
        </div>
    </div>
</section>

<section class="front-parallax-section">
    <div class="front-parallax">
        <?php $img_room = get_field('img_room');?>
        <div class="front-parallax__bg">
            <img src='<?=esc_url( $img_room['sizes'][isMobile() ? 'medium_large' : 'full_hd'] ); ?>'
                alt='<?=esc_attr($img_room['alt']); ?>'>
        </div>
        <div class="content front-parallax__cont">
            <div class="front-parallax__cont__info">
                <h1><?php the_field('title_room'); ?></h1>
                <p><?php the_field('desc_room'); ?></p>
            </div>
            <?php if( get_field('link_room') ): $link_room = get_field('link_room');?>
            <a class="btn btn--accent-1" href='<?=esc_url( $link_room['url'] ); ?>'><span
                    class="btn__prime"><?=esc_html( $link_room['title'] ); ?> </span></a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="front-informations-section">
    <div class="container">
        <div class="front-informations">
            <h3><?php the_field('info_title'); ?></h3>
            <div class="front-informations__inform">
                <?php if( have_rows('info_rep') ): while( have_rows('info_rep') ): the_row(); ?>
                <div class="front-informations__inform__item">
                    <div class="front-informations__inform__img">
                        <?php $icon_inform = get_sub_field('icon_inform');?>
                        <img src='<?=esc_url( $icon_inform['url'] ); ?>' alt='<?=esc_attr($icon_inform['alt']); ?>'>
                    </div>
                    <h4><?php the_sub_field('item_title'); ?></h4>
                    <p><?php the_sub_field('item_desc'); ?></p>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="front-location-section">
    <div class="front-location">
        <div class="front-location__mapa">
            <?php $main_iframe = get_field('main_iframe_mapa'); ?>
            <?php echo $main_iframe; ?>
        </div>
        <div class="front-location__address">
            <h3 class="front-location__title"><?php the_field('str_title'); ?></h3>

            <div class="front-location__wrap">
                <?php if( have_rows('address_item') ): while( have_rows('address_item') ): the_row(); ?>
                <div class="front-location__wrap__item">
                    <h4><?php the_sub_field('title_r'); ?></h4>
                    <p><?php the_sub_field('desc_r'); ?></p>
                </div>
                <?php endwhile; endif; ?>
            </div>


        </div>
    </div>
</section>