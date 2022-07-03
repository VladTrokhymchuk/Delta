<section class="faq-section three-line-section">
    <div class="container">
        <div class="faq-partners">
            <span class="faq__title"><?php the_field('partners_title', 'options') ?></span>
            <div class="faq-partners__list">
                <?php 
                $images = get_field('partners_logos', 'options');
                if( $images ): ?>
                    <?php foreach( $images as $image ): ?>
                        <div class="faq-partners__item">
                            <img src="<?php echo $image['sizes']['medium'] ?>" alt="">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="faq">
            <div class="faq__title">
                <span><?php the_field('questions_list_title') ?></span>
            </div>
            <div class="faq__list">
                <?php if( have_rows('questions') ): ?>
                    <?php while ( have_rows('questions') ) : the_row(); ?>
                        <div class="faq__item dropdown <?php if (get_row_index() == 1) echo 'open' ?>" data-dropdown>
                            <div class="faq__item__question dropdown__trigger" data-dropdown-trigger>
                                <span><?php the_sub_field('question') ?></span>
                            </div>
                            <div class="faq__item__answer dropdown__cont" data-dropdown-cont>
                                <div class="faq__item__answer__inner">
                                    <?php the_sub_field('answer') ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?> 
                <?php endif; ?> 
            </div>
        </div>
    </div>
</section>