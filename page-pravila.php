<?php // Template name: Pravila page
    get_header('second'); 

    wp_enqueue_style('page-news', get_template_directory_uri() . '/build/css/pages/page-pravila/page-pravila.min.css');

?>

<section class="pravila-section">
    <div class="container">
        <div class="pravila">
            <?php the_field('kontent'); ?>
        </div>
    </div>
</section>

<?php get_footer(); 