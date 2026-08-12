    </div>
    <footer class="footer" role="contentinfo" id="site-footer">
        <div class="container">

            <div class="footer__top">

                <!-- Бренд: лого + таглайн + соцмережі -->
                <div class="footer__brand">
                    <?php $img_logo = get_field('img_logo', 'options'); if ($img_logo): ?>
                    <a class="footer__logo" href="<?php echo esc_url( home_url('/') ); ?>"
                        aria-label="<?php echo esc_attr( get_bloginfo('name') ); ?>">
                        <?php delta_render_image( $img_logo, array( 'alt' => get_bloginfo('name') ) ); ?>
                    </a>
                    <?php endif; ?>

                    <?php if ( get_bloginfo('description') ): ?>
                    <p class="footer__tagline"><?php echo esc_html( get_bloginfo('description') ); ?></p>
                    <?php endif; ?>

                    <?php if( have_rows('social_rep', 'options') ): ?>
                    <div class="footer__social">
                        <?php while( have_rows('social_rep', 'options') ): the_row();
                            $social_item = get_sub_field('social_item');
                            $img_soc     = get_sub_field('img_soc');
                            if ( ! $social_item ) continue;
                        ?>
                        <a class="footer__social__item" href="<?php echo esc_url( $social_item['url'] ); ?>"
                            title="<?php echo esc_attr( $social_item['title'] ); ?>"
                            aria-label="<?php echo esc_attr( $social_item['title'] ); ?>"
                            target="_blank" rel="noopener">
                            <?php if ( $img_soc ) delta_render_image( $img_soc, array(
                                'alt' => $social_item['title'] ?: '',
                            ) ); ?>
                        </a>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Навігація -->
                <nav class="footer__col footer__nav" aria-label="Меню у футері">
                    <h2 class="footer__title">Навігація</h2>
                    <?php wp_nav_menu(array(
                        'menu'           => 'Fullpage menu',
                        'container'      => false,
                        'menu_class'     => 'footer__menu',
                        'theme_location' => 'primary',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    )); ?>
                </nav>

                <!-- Контакти -->
                <div class="footer__col">
                    <h2 class="footer__title"><?php echo esc_html( get_field('adr_title', 'options') ?: 'Контакти' ); ?></h2>
                    <address class="footer__contacts">
                        <?php if ( get_field('foot_delta', 'options') ): ?>
                        <span class="footer__contacts__name"><?php echo esc_html( get_field('foot_delta', 'options') ); ?></span>
                        <?php endif; ?>
                        <?php if ( get_field('foot_strit', 'options') ): ?>
                        <span class="footer__contacts__line"><?php echo esc_html( get_field('foot_strit', 'options') ); ?></span>
                        <?php endif; ?>

                        <?php if( have_rows('phone_rep', 'options') ): ?>
                        <?php if ( get_field('contact_title', 'options') ): ?>
                        <span class="footer__subtitle"><?php echo esc_html( get_field('contact_title', 'options') ); ?></span>
                        <?php endif; ?>
                        <ul class="footer__phones">
                            <?php while( have_rows('phone_rep', 'options') ): the_row(); $phone = get_sub_field('phone'); ?>
                            <li><a href="<?php echo esc_url( $phone['url'] ); ?>"><?php echo esc_html( $phone['title'] ); ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                        <?php endif; ?>
                    </address>
                </div>

                <!-- Форма-заявка -->
                <?php $contact_form_shortcode = get_field('contact_form_shortcode', 'options'); if ($contact_form_shortcode): ?>
                <div class="footer__col footer__col--form">
                    <h2 class="footer__title">Залишити заявку</h2>
                    <p class="footer__form-note">Залиште контакти — ми підберемо номер і підтвердимо бронювання.</p>
                    <div class="form footer__form">
                        <?php echo do_shortcode( $contact_form_shortcode ); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <div class="footer__bott">
                <div class="copyright">
                    <p><?php echo esc_html(get_field('copyright', 'options')); ?>
                        <?php if( get_field('vlad', 'options') ): $vlad = get_field('vlad', 'options');?>
                        <a href='<?=esc_url( $vlad['url'] ); ?>' target="_blank" rel="noopener"><?=esc_html( $vlad['title'] ); ?></a>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

        </div>
    </footer>
    </main>

    <?php
    get_template_part('./template-parts/popups');

    wp_footer(); ?>
    </body>

    </html>
