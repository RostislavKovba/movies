<?php
/**
 * The archives template file
 *  *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

    <main id="site-content" role="main">

		<?php

		$archive_title    = '';
		$archive_subtitle = '';

		if ( ! have_posts() ) {
			$archive_title = __( 'Nothing Found', 'twentytwenty' );
		} else {
			$archive_title    = get_the_archive_title();
			$archive_subtitle = get_the_archive_description();
		}

		if ( $archive_title || $archive_subtitle ) {
			?>

            <header class="archive-header has-text-align-center header-footer-group">

                <div class="archive-header-inner section-inner medium">

					<?php if ( $archive_title ) { ?>
                        <h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
					<?php } ?>

					<?php if ( $archive_subtitle ) { ?>
                        <div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>
					<?php } ?>

                </div><!-- .archive-header-inner -->

            </header><!-- .archive-header -->

			<?php
		}

		if ( have_posts() ) {
			?>

            <div class="movies-list">

                <?php
                while ( have_posts() ) {
                    the_post();

                    get_template_part( 'template-parts/content', get_post_type() );
                }
                ?>

            </div><!-- .movies-list -->

        <?php } ?>

		<?php get_template_part( 'template-parts/pagination' ); ?>

    </main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php
get_footer();
