<?php $bg_color = get_field('bg_color'); ?>

<section class="cases-header <?php echo $bg_color; ?>">
    <div class="container">
        <div class="cases-header__top">
            <div class="bg-grid">
                <div class="cases-header__top__info">
                    <div class="h-box">
                        <h1 class="cases-header__title"><?php echo get_field('head_title'); ?></h1>
                        <h2 class="cases-header__subtitle"><?php echo get_field('head_subtitle'); ?></h2>
                    </div>
                </div>
            </div>

            <div class="cases-header__top__cat">
                <div class="c-logo">
                    <?php if ( get_field('c_logo') ) : $c_logo = get_field('c_logo'); ?>
                    <img src='<?=esc_url($c_logo['sizes'][isMobile() ? 'medium_large' : 'full_hd' ]) ?>' alt='<?=esc_attr($c_logo['alt']); ?>'>
                    <?php endif; ?>
                </div>
                <ul>
                    <?php $terms = wp_get_post_terms($post->ID, 'cases-types', array("fields" => "all")); ?>
                    <?php foreach($terms as $term): ?>
                    <li>
                        <span><?php echo $term->name; ?></span>
                    </li>
                    <?php endforeach;  ?>
                </ul>
            </div>
        </div>

        <div class="cases-header__bott">
            <div class="cases-header__bott__info">
                <span class="cases-header__bott__title"><?php echo get_field('head_title_for_description'); ?></span>
                <p class="cases-header__bott__desc"><?php echo get_field('head_description'); ?></p>
            </div>

            <div class="cases-header__bott__items">
                <span class="cases-header__bott__title"><?php echo get_field('head_title_for_items'); ?></span>
                <?php if( get_field('head_description_item') ): ?>
                <p class="cases-header__bott__desc"><?php echo get_field('head_description_item'); ?></p>
                <?php endif; ?>

                <ul>
                    <?php if( have_rows('key_results_rep') ): while( have_rows('key_results_rep') ): the_row(); ?>
                    <li>
                        <div class="item-top">
                            <div class="item-top__icon">
                                <?php $icon_arrow_up = get_field('icon_arrow_up', 'options');
                                    $svg_markup = file_get_contents( get_attached_file( $icon_arrow_up['ID'] ) );
                                    echo $svg_markup;
                                ?>
                            </div>
                            <span><?php the_sub_field('numb_r'); ?></span>
                        </div>

                        <div class="item-bott">
                            <p><?php the_sub_field('desc_r'); ?></p>
                        </div>
                    </li>
                    <?php endwhile; endif; ?>
                </ul>

            </div>
        </div>

        <div class="cases-header__screen">
            <div class="cases-header__screen__img">
                <?php if ( get_field('h_screen') ) : $h_screen = get_field('h_screen'); ?>
                <img src='<?=esc_url($h_screen['sizes'][isMobile() ? 'medium_large' : 'full_hd' ]) ?>' alt='<?=esc_attr($h_screen['alt']); ?>'>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>