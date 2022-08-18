<?php
     get_header('second'); 

    wp_enqueue_style('swiper', get_template_directory_uri() . '/build/static/css/libs/swiper-bundle.css');
    wp_enqueue_style('single-news', get_template_directory_uri() . '/build/static/css/pages/single-news/single-news.css');

    wp_enqueue_script('swiper-js', get_stylesheet_directory_uri() . '/build/static/js/libs/swiper-bundle.min.js',  array('jquery'), '1.0', true);
    wp_enqueue_script('single-news-js', get_template_directory_uri() . '/build/static/js/pages/single-news/single-news.js',  array('jquery'), '1.0', true);

?>

<section class="room-section">
    <div class="container">
        <div class="room__wrap">
            <div class="room__info">
                <div class="room__slider">
                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                        class="swiper mySwiper2">
                        <div class="swiper-wrapper">

                            <?php if( have_rows('room_rep') ): while( have_rows('room_rep') ): the_row(); ?>
                            <div class="swiper-slide">
                                <?php $room_img = get_sub_field('room_img');?>
                                <img src='<?=esc_url( $room_img['sizes'][isMobile() ? 'medium_large' : 'full_hd']); ?>'
                                    alt='<?=esc_attr($room_img['alt']); ?>'>
                            </div>
                            <?php endwhile; endif; ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <div thumbsSlider="" class="swiper mySwiperP">
                        <div class="swiper-wrapper">
                            <?php if( have_rows('room_rep') ): while( have_rows('room_rep') ): the_row(); ?>
                            <div class="swiper-slide">
                                <div class="room-thmb">
                                    <?php $room_img = get_sub_field('room_img');?>
                                    <img src='<?=esc_url( $room_img['sizes'][isTablet() ? 'medium_large' : 'medium']); ?>'
                                        alt='<?=esc_attr($room_img['alt']); ?>'>
                                </div>
                            </div>
                            <?php endwhile; endif; ?>
                        </div>
                    </div>

                </div>
                <div class="room__info__price">
                    <span><?php the_field('price'); ?></span>
                </div>

                <div class="room__desc">
                    <h5><?php the_field('opis_nomeru_zagolovok'); ?></h5>
                    <p><?php the_field('opis_nomeru'); ?></p>
                </div>
            </div>
            <div class="room__sidebar">
                <div class="room__sidebar__form">
                    <div class="room__sidebar__form__info">
                        <h4>БРОНЮВАННЯ</h4>
                        <span><?php the_title(); ?></span>
                    </div>

                    <?php $contact_form_shortcode = get_field('contact_form_shortcode');
                    if ($contact_form_shortcode): ?>
                    <div class="form">
                        <?php echo do_shortcode( $contact_form_shortcode ) ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="room__sidebar__features">
                    <div class="room__sidebar__features__info">
                        <h4>Особливості кімнати</h4>
                        <span>Забронювати кімнату</span>
                    </div>

                    <div class="room__sidebar__features__list">
                        <span class="room__sidebar__features__sub">Зручності:</span>
                        <ul class>
                            <?php if( have_rows('desc_features') ): while( have_rows('desc_features') ): the_row(); ?>
                            <li>
                                <?php $check = get_field('check', 'options'); if ($check): ?>
                                <div class="check">
                                    <?php $svg_markup_check = file_get_contents( get_attached_file( $check['ID'] ) );
                                        echo $svg_markup_check;
                                    ?>
                                </div>
                                <?php endif; ?>
                                <span><?php the_sub_field('d_f_item'); ?></span>
                            </li>
                            <?php endwhile; endif; ?>
                        </ul>
                    </div>

                    <div class="room__sidebar__features__short">
                        <span class="room__sidebar__features__sub">Параметри:</span>

                        <ul class="">
                            <?php if( have_rows('short_character') ): while( have_rows('short_character') ): the_row(); ?>
                            <li><span> <?php the_sub_field('s_h_item'); ?></span></li>
                            <?php endwhile; endif; ?>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>



<?php get_footer();