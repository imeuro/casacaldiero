<?php
/* Template Name: Feedbacks */


get_header();
?>

<header>
	<h1 class="page-title text-center"><?php pll_e('Dicono di noi'); ?></h1>
</header>

<?php
// WP_Query arguments
$args = array (
	'post_type'              => array( 'dicono_di_noi' ),
	'post_status'            => array( 'publish' ),
	'posts_per_page'         => '10',
	'order'									 => 'DESC'
);

// The Query
$feedbacks = new WP_Query( $args );

// The Loop
if ( $feedbacks->have_posts() ) {
	while ( $feedbacks->have_posts() ) {
		$feedbacks->the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('HPblocks HPfeedbacks'); ?>>
			<div class="bgbianco virgolette">
							<?php the_content(); ?>
			</div>
			<h3 class="page-title text-right"><?php edit_post_link( __( 'Edit', '_tk' ), '<span class="edit-link"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;', '</span>&nbsp;' ); ?><em><?php the_field('feed_author'); ?> - <?php the_field('feed_from'); ?></em></h3>
		</article>
	<?php
	}
} // end of the loop. ?>

<?php get_footer(); ?>
