<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package _tk
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h1 class="page-title text-center"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content bgbianco row">
		<div class="col-xs-12">
			<?php the_content(); ?>
		</div>
	</div><!-- .entry-content -->
	<?php edit_post_link( __( 'Edit', '_tk' ), '<span class="edit-link"><i class="fa fa-pencil" aria-hidden="true"></i>
&nbsp;', '</span>' ); ?>
</article><!-- #post-## -->
