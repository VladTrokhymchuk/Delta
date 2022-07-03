<section class="cases-learn-section">
    <div class="runline" data-runline style="--runline-text-color: #FFFFFF;">
        <div class="container">
            <div class="runline__inner">
                <?php $services_runline_text = get_field('c_runline_text') ?>
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <?php $i = 0;
                        while ($i < 5): ?>
                        <div class="swiper-slide">
                            <span><?php echo $services_runline_text ?></span>
                        </div>
                        <?php $i++; endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="cases-learn__inform">
            <div class="bg-grid">
                <div class="cases-learn__inform__desc">
                    <span class="fontn"><?php the_field('l_desc') ?></span>
                    <span class="fontn fontn-italic"><?php the_field('l_desc_italic') ?></span>
                </div>
                <div class="cases-learn__inform__items">
                    <?php if( have_rows('inform_rep') ): while( have_rows('inform_rep') ): the_row(); ?>
                    <div class="cases-learn__inform__items__list">
                        <?php $list_img = get_sub_field('list_img');?>
                        <img src='<?=esc_url( $list_img['url'] ); ?>' alt='<?=esc_attr($list_img['alt']); ?>'>
                        <p> <?php the_sub_field('list_desc'); ?></p>
                    </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>
        </div>

        <div class="cases-learn__more">
            <div class="cases-learn__more__title">
                <span><?php the_field('m_title') ?></span>
                <span class="animated-gradient-title"><?php the_field('m_title_grad') ?></span>
            </div>
            <div class="cases-learn__more__subtitle">
                <span><?php the_field('m_subtitle') ?></span>
            </div>
            <div class="cases-learn__serv">
                <ul id="hoveredElement">
                    <?php if( have_rows('learn_serv_rep') ): while( have_rows('learn_serv_rep') ): the_row(); ?>
                    <li>
                        <?php $serv_link = get_sub_field('serv_link');?>
                        <a href='<?=esc_url( $serv_link['url'] ); ?>' class="box">
                            <?php $serv_icon = get_sub_field('serv_icon');?>
                            <img src=' <?=esc_url( $serv_icon['url'] ); ?>' alt='<?=esc_attr($serv_icon['alt']); ?>'>
                            <span><?php the_sub_field('serv_title'); ?></span>
                            <p><?php the_sub_field('serv_desc'); ?></p>
                        </a>
                        <a href='<?=esc_url( $serv_link['url'] ); ?>'
                            class="btn btn--accent-1 btn-primary custom-btn-style">
                            <span class="btn__front"><?=esc_html( $serv_link['title'] ); ?></span>
                            <span class="btn__back"><?=esc_html( $serv_link['title'] ); ?></span>
                        </a>
                    </li>
                    <?php endwhile; endif; ?>
                </ul>
                <?php if( get_field('all_serv_link') ): $all_serv_link = get_field('all_serv_link');?>
                <a class="btn btn-all" href='<?=esc_url( $all_serv_link['url'] ); ?>'>
                    <span><?=esc_html( $all_serv_link['title'] ); ?> </span>
                </a>
                <?php endif; ?>
                <a href="#replace" id="l-link" class="btn btn--accent-1 btn-primary custom-btn-style big-btn">
                    <span class="btn__front">Learn More</span>
                    <span class="btn__back">Learn More</span>
                </a>
            </div>
        </div>
    </div>
</section>