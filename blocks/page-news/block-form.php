<section class="cases-agency-section">
    <div class="container">
        <div class="cases-agency">
            <div class="cases-agency__info">
                <div class="cases-agency__info-desc">
                    <span class="cases-agency__info-desc__title"><?php the_field('cases_p_title') ?></span>
                    <span class="cases-agency__info-desc__subtitle"><?php the_field('cases_p_subtitle') ?></span>
                    <?php $f_p_link = get_field('f_p_link');
                        if ($f_p_link): ?>
                        <a href="<?= $f_p_link['url'] ?>" class="btn-arrow">
                            <span><?= $f_p_link['title'] ?></span>
                            <?php print_svg_ic('arrow-right') ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="cases-agency__info-user">
                    <div class="cases-agency__info-user__wrap">
                        <?php $video_people = get_field('video_people'); 
                            $preview_if_no_video = get_field('preview_if_no_video');
                        ?>
                        <div class="cases-agency__info-user__wrap__img">
                            <?php if ( $preview_if_no_video ) :?>
                                <img src='<?=esc_url($preview_if_no_video['sizes'][isMobile() ? 'medium_large' : 'full_hd' ]) ?>' alt='<?=esc_attr($preview_if_no_video['alt']); ?>'>
                            <?php endif; ?>
                        </div>
                        <?php  if ($video_people || $preview_if_no_video): ?>
                            <div class="cases-agency__info-user__wrap__video">
                                <video autoplay="true" loop="true" muted poster=" <?=esc_url($preview_if_no_video['url']) ?>">
                                    <source src="<?=esc_url($video_people['url']) ?>" type="video/webm">
                                </video>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="cases-agency__info-user__desc">
                        <span class="u-name"><?php the_field('cases_u_name') ?></span>
                        <span class="u-desc"><?php the_field('cases_u_desc') ?></span>
                    </div>
                </div>
            </div>

            <div class="cases-agency__form">
                <div class="cases-agency__form__desc">
                    <span class="cases-agency__form__desc__title"><?php the_field('cases_p_form_title') ?></span>
                    <span class="cases-agency__form__desc__subtitle"><?php the_field('cases_p_form_subtitle') ?></span>
                </div>
                <?php $cases_p_form_shortcode = get_field('cases_p_form_shortcode');
                    if ($cases_p_form_shortcode): ?>
                    <div class="form">
                        <?php echo do_shortcode( $cases_p_form_shortcode ) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>