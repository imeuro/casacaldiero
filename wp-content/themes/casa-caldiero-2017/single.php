<?php
/**
 * The Template for displaying all single posts.
 *
 * @package _tk
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single-'.get_post_type() ); ?>

	<?php endwhile; // end of the loop. ?>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
