<?php
get_header('memberarea');
?>

<main id="primary" class="site-main">
    <div class="site-content">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h1>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>

            <?php endwhile; ?>

            <?php
            the_posts_navigation(array(
                'prev_text' => '<i class="fas fa-arrow-left"></i> ' . __('Ältere Beiträge', 'custom-theme'),
                'next_text' => __('Neuere Beiträge', 'custom-theme') . ' <i class="fas fa-arrow-right"></i>',
            ));
            ?>

        <?php else : ?>

            <div class="no-content">
                <h1><?php esc_html_e('Nichts gefunden', 'custom-theme'); ?></h1>
                <p><?php esc_html_e('Es sieht so aus, als ob an dieser Stelle nichts gefunden wurde.', 'custom-theme'); ?></p>
            </div>

        <?php endif; ?>

    </div>
</main>
