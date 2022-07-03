<?php $bg_color = get_field('bg_color'); ?>

<section class="cases-main parent parent-overflow parent-best">
    <?php $cases_form_shortcode = get_field('cases_form_shortcode');
        if ($cases_form_shortcode): ?>
    <div class="cases-main__sidebar">
        <div class="cases-main__sidebar__wrap">
            <div class="cases-main__sidebar__wrap__info">
                <span class="cases-main__sidebar__wrap__info__title"><?php the_field('sidebar_form_title') ?></span>
                <span
                    class="cases-main__sidebar__wrap__info__subtitle"><?php the_field('sidebar_form_subtitle') ?></span>
            </div>
            <div class="cases-main__sidebar__wrap__form">
                <div class="form">
                    <?php echo do_shortcode( $cases_form_shortcode ) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (have_rows('cases_main_content')) : ?>
    <?php while (have_rows('cases_main_content')) : the_row(); ?>

    <?php if (get_row_layout() == 'content_zone_lay') : ?>
    <div class="container">
        <div class="cases-main__box">
            <div class="cases-main__content">
                <?php the_sub_field('content_zone'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (get_row_layout() == 'work_scope_lay') : ?>
    <div class="container">
        <div class="cases-main__box">
            <div class="cases-main__content">
                <div class="work-scope">
                    <?php if( have_rows('work_scope_rep') ): while( have_rows('work_scope_rep') ): the_row(); ?>
                    <div class="work-scope__list">
                        <div class="work-scope__list__title">
                            <span><?php the_sub_field('work_scope_title'); ?></span>
                        </div>
                        <div class="work-scope__list__percent">
                            <div class="work-scope__list__percent--line"
                                style="width: calc(<?php the_sub_field('work_scope_percent'); ?>% - var(--fly))"></div>
                        </div>
                        <div class="work-scope__list__hour">
                            <span> <?php the_sub_field('work_scope_title_hour'); ?></span>
                        </div>
                    </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
 
    <?php if (get_row_layout() == 'grid_content_lay') : ?>
    <div class="container">
        <div class="cases-main__box">
            <div class="cases-main__content">
                <?php $order = get_sub_field('order'); ?>
                <div class="grid-content <?php echo $order; ?>">
                    <div class="grid-content__img">
                        <?php if ( get_sub_field('images_g') ) : $images_g = get_sub_field('images_g'); ?>
                        <img src='<?=esc_url($images_g['sizes'][isMobile() ? 'large' : 'full_hd' ]) ?>' alt='<?=esc_attr($images_g['alt']); ?>'>
                        <?php endif; ?>
                    </div>
                    <?php if ( get_sub_field('simple_content_g') ) : get_sub_field('simple_content_g'); ?>
                    <div class="grid-content__simple">
                        <?php the_sub_field('simple_content_g'); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( get_sub_field('list_rep_g') ) : get_sub_field('list_rep_g'); ?>
                    <div class="grid-content__list-lo">
                        <?php if( have_rows('list_rep_g') ): while( have_rows('list_rep_g') ): the_row(); ?>
                        <div class="grid-content__list-lo__item">
                            <p><?php the_sub_field('title_r_g'); ?></p>
                            <?php if ( get_sub_field('images_r_g') ) : $images_r_g = get_sub_field('images_r_g'); ?>
                            <img src='<?=esc_url($images_r_g['sizes'][isMobile() ? 'large' : 'full_hd' ]) ?>'
                                alt='<?=esc_attr($images_r_g['alt']); ?>'>
                            <?php endif; ?>

                        </div>
                        <?php endwhile; endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (get_row_layout() == 'content_images_lay') : ?>
    <div class="cases-main__images">
        <?php if ( get_sub_field('cont_img') ) : $cont_img = get_sub_field('cont_img'); ?>
        <img src='<?=esc_url($cont_img['sizes'][isMobile() ? 'large' : 'full_hd' ]) ?>' alt='<?=esc_attr($cont_img['alt']); ?>'>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (get_row_layout() == 'result_zone_lay') : ?>
    <div class="result <?php echo $bg_color; ?>">
        <span><?php the_sub_field('result_title_zone'); ?></span>
        <p><?php the_sub_field('result_desc_zone'); ?></p>
    </div>
    <?php endif; ?>

    <?php if (get_row_layout() == 'coment_zone_lay') : ?>
    <div class="coment <?php echo $bg_color; ?>">
        <p><?php the_sub_field('coment_desc_zone'); ?></p>
        <span><?php the_sub_field('coment_name_zone'); ?></span>

        <div class="coment__logo">
            <?php if ( get_sub_field('coment_logo') ) : $coment_logo = get_sub_field('coment_logo'); ?>
            <img src='<?=esc_url($coment_logo['url']) ?>' alt='<?=esc_attr($coment_logo['alt']); ?>'>
            <?php endif; ?>
        </div>

        <div class="coment__bott">
            <div class="coment__rating">
                <span><?php the_sub_field('coment_rating_numb_zone'); ?></span>
                <div class="star">
                    <?php if( have_rows('cometn_star_rep') ): while( have_rows('cometn_star_rep') ): the_row(); ?>
                    <?php $star = get_sub_field('star');?>
                    <img src='<?=esc_url( $star['url'] ); ?>' alt='<?=esc_attr($star['alt']); ?>'>
                    <?php endwhile; endif; ?>
                </div>
            </div>

            <div class="coment__reviews">
                <span><?php the_sub_field('coment_reviews_zone'); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (get_row_layout() == 'content_images_q_lay') : ?>
    <div class="cases-main__q-img">
        <div class="cases-main__q-img__user">
            <?php if ( get_sub_field('q_img') ) : $q_img = get_sub_field('q_img'); ?>
            <img src='<?=esc_url($q_img['url']) ?>' alt='<?=esc_attr($q_img['alt']); ?>'>
            <?php endif; ?>
        </div>
        <p><?php the_sub_field('q_text'); ?></p>
    </div>
    <?php endif; ?>

    <?php endwhile; ?>
    <?php endif; ?>

    <div class="cases-main__figure">
        <?php if( have_rows('figure_rep') ): while( have_rows('figure_rep') ): the_row(); ?>
        <div class="cases-main__figure__bg-lines bg-<?php the_sub_field('figure_select'); ?>">
            <div class="flying <?php the_sub_field('figure_select'); ?>"></div>
        </div>
        <?php endwhile; endif; ?>
    </div>
</section>