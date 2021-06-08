<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package _tk
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('dicono_di_noi'); ?>>
	<header>
		<h1 class="page-title text-center"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->


			<?php
			// WP_Query arguments
			$args = array (
				'post_type'              => array( 'dicono_di_noi' ),
				'post_status'            => array( 'publish' ),
				'posts_per_page'         => '10',
				'order'									 => 'ASC'
			);

			// The Query
			$feedbacks = new WP_Query( $args );

			// The Loop
			if ( $feedbacks->have_posts() ) {
				while ( $feedbacks->have_posts() ) {
					$feedbacks->the_post();
					?>
					<div class="entry-content bgbianco virgolette row">
						<div class="col-xs-12">
							<?php
							the_content();
							if(!empty(get_field('feed_author',$feedbacks->ID)) || !empty(get_field('feed_from',$feedbacks->ID))) {
								echo '<small class="text-right"><strong>'.get_field('feed_author',$feedbacks->ID)." - ".get_field('feed_from',$feedbacks->ID)."</strong></small>";
							}
							?>
						</div>
					</div><!-- .entry-content -->
					<?php
				}
			}
			?>


	<?php edit_post_link( __( 'Edit', '_tk' ), '<span class="edit-link"><i class="fa fa-pencil" aria-hidden="true"></i>
&nbsp;', '</span>' ); ?>
</article><!-- #post-## -->
