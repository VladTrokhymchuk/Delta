    </div>
    <footer class="footer">
        <div class="container">

            <div class="footer__logo">
                <?php $img_logo = get_field('img_logo', 'options'); if ($img_logo): ?>
                <div class="footer__logo__img">
                    <?php $svg_markup = file_get_contents( get_attached_file( $img_logo['ID'] ) );
                        echo $svg_markup;
                    ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="footer__sidebar">
                <div class="footer__sidebar__address footer__sidebar--items">
                    <span class="footer__sidebar__title"><?php the_field('adr_title', 'options'); ?></span>
                    <div class="footer__sidebar__box">
                        <span class="name"><?php the_field('foot_delta', 'options'); ?></span>
                        <span><?php the_field('foot_strit', 'options'); ?></span>
                    </div>
                </div>

                <div class="footer__sidebar__numb footer__sidebar--items">
                    <span class="footer__sidebar__title"><?php the_field('contact_title', 'options'); ?></span>
                    <ul class="footer__sidebar__box">
                        <?php if( have_rows('phone_rep', 'options') ): while( have_rows('phone_rep', 'options') ): the_row(); ?>
                        <?php $phone = get_sub_field('phone');?>
                        <li><a href='<?=esc_url( $phone['url'] ); ?>'> <?=esc_html( $phone['title'] ); ?> </a></li>
                        <?php endwhile; endif; ?>
                    </ul>
                </div>

                <div class="footer__sidebar__form">
                    <?php $contact_form_shortcode = get_field('contact_form_shortcode', 'options');
                    if ($contact_form_shortcode): ?>
                    <div class="form">
                        <?php echo do_shortcode( $contact_form_shortcode ) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="footer__bott">
                <div class="copyright">
                    <p><?php the_field('copyright', 'options'); ?>
                        <?php if( get_field('vlad', 'options') ): $vlad = get_field('vlad', 'options');?>
                        <a href='<?=esc_url( $vlad['url'] ); ?>'> <?=esc_html( $vlad['title'] ); ?> </a>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="social">
                    <?php if( have_rows('social_rep', 'options') ): while( have_rows('social_rep', 'options') ): the_row(); ?>
                    <?php $social_item = get_sub_field('social_item');?>
                    <a class="social__item" href='<?=esc_url( $social_item['url'] ); ?>' title="<?=esc_html( $social_item['title'] ); ?>">
                        <?php $img_soc = get_sub_field('img_soc', 'options'); ?>
                        <?php $svg_markup_soc = file_get_contents( get_attached_file( $img_soc['ID'] ) );
                            echo $svg_markup_soc;
                        ?>
                    </a>
                    <?php endwhile; endif; ?>
                </div>
            </div>

        </div>
    </footer>
    </main>

    <?php wp_footer(); ?>
    </body>

    </html>