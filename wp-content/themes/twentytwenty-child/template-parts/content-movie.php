<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <header class="entry-header has-text-align-center<?php echo esc_attr( $entry_header_classes ); ?>">

        <div class="entry-header-inner section-inner medium">

			<?php the_title( '<h2 class="entry-title heading-size-1"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

        </div><!-- .entry-header-inner -->

    </header><!-- .entry-header -->

	<div class="post-inner">
		<div class="entry-content">

            <img src="<?php the_field('movie_poster'); ?>" alt="<?php the_title(); ?>">

            <?php
            if ( is_archive() || is_home() ) {
                the_excerpt();
            } else {
                the_content();
            }?>

            <ul class="movie-filters">

                <li><span><?php _e('Стоимость сеанса'); ?>: </span><?php echo get_field('movie_price') . '$' ?></li>
                <li><span><?php _e('Дата выхода в прокат'); ?>: </span><?php the_field('movie_date'); ?></li>

	            <?php if( is_singular() ) : ?>
                <?php if ( have_rows( 'movie_filters', 'movie_filters' ) ) : ?>
                <?php while (have_rows('movie_filters', 'movie_filters') ) : the_row();?>

                    <li>
                        <span><?php the_sub_field('labels'); ?>: </span>
                        <?php the_terms( get_the_ID(), get_sub_field('slug') ); ?>
                    </li>

                <?php endwhile; ?>
                <?php endif; ?>
                <?php endif; ?>
            </ul>

		</div><!-- .entry-content -->
	</div><!-- .post-inner -->

</article><!-- .post -->
