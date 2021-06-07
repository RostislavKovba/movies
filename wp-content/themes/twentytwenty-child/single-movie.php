<?php
/**
 * The template for displaying single movies.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<main id="site-content" role="main">

	<?php
    if( have_posts() ) {
        while ( have_posts() ) {
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );
        }
	}
	?>

</main><!-- #site-content -->

<h3><?php _e('Последние фильмы'); ?></h3>

<div class="movies-list">
	<?php echo do_shortcode('[movies]'); ?>
</div>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>


<?php get_footer(); ?>
