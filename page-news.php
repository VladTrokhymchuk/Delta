<?php // Template name: News page
    get_header('second'); 

    wp_enqueue_style('page-news', get_template_directory_uri() . '/build/static/css/pages/page-news/page-news.css');

?>

<section class="room-list-section">
    <div class="container">
        <?php
            $posts_per_page = 10;
            $args = [
                'post_type' => 'news',
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_status' => 'publish',
                ];

            $query = new WP_Query($args);
        ?>


        <?php if ($query->have_posts()) : ?>
        <div class="room-list">
            <?php while ($query->have_posts()) : $query->the_post();?>
            <a class="room-list__item" href="<?php the_permalink(); ?>">

                <div class="room-list__photo">
                    <?php if (get_field('prevyu_room')): $prevyu_room = get_field('prevyu_room'); ?>
                    <div class="room-list__photo__img">
                        <img src="<?= esc_url($prevyu_room['sizes'][isMobile() ? 'medium_large' : 'full_hd']); ?>"
                            alt="<?= esc_attr($prevyu_room['alt']); ?>" />
                    </div>
                    <?php endif; ?>
                </div>

                <div class="room-list__desc">
                    <h2><?php the_title(); ?></h2>

                    <ul class="room-list__desc__short">
                        <?php if( have_rows('short_character') ): while( have_rows('short_character') ): the_row(); ?>
                        <li><span> <?php the_sub_field('s_h_item'); ?></span></li>
                        <?php endwhile; endif; ?>
                    </ul>

                    <div class="room-list__desc__features">
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
                </div>

                <div class="room-list__price">
                    <div class="room-list__price__inner">
                        <h3><?php the_field('price'); ?></h3>
                        <div class="btn btn--more"> <span class="btn__prime"><?php the_field('btn_p_room', 'options'); ?></span></div>
                    </div>
                </div>

            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); 