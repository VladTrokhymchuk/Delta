<?php // Template name: Pravila page
    get_header('second');

    wp_enqueue_style('page-pravila', get_template_directory_uri() . '/build/css/pages/page-pravila/page-pravila.min.css');

    // Нормалізуємо Word-розмітку і збираємо зміст (див. delta_prepare_document()).
    $doc = delta_prepare_document( wp_kses_post( get_field('kontent') ) );
?>

<section class="pravila-section">
    <div class="container">

        <header class="pravila-head">
            <?php
            // Модуль крихт у Rank Math може бути вимкнений — тоді функція нічого
            // не друкує. Буферизуємо, щоб не лишити порожній контейнер із відступом.
            $crumbs = '';
            if ( function_exists('rank_math_the_breadcrumbs') ) {
                ob_start();
                rank_math_the_breadcrumbs();
                $crumbs = trim( ob_get_clean() );
            }
            if ( $crumbs !== '' ) :
            ?>
            <div class="pravila-head__crumbs"><?php echo $crumbs; ?></div>
            <?php endif; ?>

            <h1 class="pravila-head__title"><?php the_title(); ?></h1>

            <?php if ( get_the_modified_date('U') ) : ?>
            <p class="pravila-head__meta">
                Редакція від
                <time datetime="<?php echo esc_attr( get_the_modified_date('c') ); ?>">
                    <?php echo esc_html( get_the_modified_date('d.m.Y') ); ?>
                </time>
            </p>
            <?php endif; ?>
        </header>

        <div class="pravila-layout">

            <?php if ( count($doc['toc']) >= 3 ) : ?>
            <nav class="pravila-toc" aria-label="Зміст документа">
                <p class="pravila-toc__title">Зміст</p>
                <ol class="pravila-toc__list">
                    <?php foreach ( $doc['toc'] as $item ) : ?>
                    <li>
                        <a href="#<?php echo esc_attr( $item['id'] ); ?>">
                            <span class="pravila-toc__num"><?php echo esc_html( $item['num'] ); ?></span>
                            <span><?php echo esc_html( $item['title'] ); ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <?php endif; ?>

            <article class="pravila">
                <?php echo $doc['html']; // вже пройшло wp_kses_post() вище ?>
            </article>

        </div>

    </div>
</section>

<?php get_footer();
