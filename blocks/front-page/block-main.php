<section class="front-head-section">
    <div class="container">
        <div class="front-head__info">
            <h1 style="
    color: crimson;
    margin-bottom: 50px;
">Сайт тимчасово на реконструкції, телефонуйте за номерами</h1>
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