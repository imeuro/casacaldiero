<?php
/* Template Name: Appartamenti */

get_header(); ?>

	<?php // add the class "panel" below here to wrap the content-padder in Bootstrap style ;) ?>
	<div class="content-padder row">

		<?php if ( have_posts() ) : ?>

			<header>
				<h1 class="page-title text-center">
					<?php pll_e('Gli Appartamenti'); ?>
				</h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="col-xs-12 bgbianco entry-main-content">
					<?php the_content(); ?>
					<!--p class='checkinout-time'>
						<span class="checkin-time"><?php // pll_e('check in time'); ?></span><br />
						<span class="checkout-time"><?php // pll_e('check out time'); ?></span>
					</p-->
				</div>
			<?php endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

	</div><!-- .content-padder -->

	<?php
	// WP_Query arguments
	$args = array (
		'post_type'              => array( 'appartamenti' ),
		'post_status'            => array( 'publish' ),
		'posts_per_page'         => '10',
		'order'									 => 'ASC'
	);

	// The Query
	$apts = new WP_Query( $args );

	// The Loop
	if ( $apts->have_posts() ) {
		while ( $apts->have_posts() ) {
			$apts->the_post();
			?>
			<div class="apt-list-item bgbianco row">
				<figure class="col-xs-12 col-sm-3 col-md-3"><a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank"><?php the_post_thumbnail('thumbnail'); ?></a></figure>
				<div class="col-xs-12 col-sm-4 col-md-5 apt-list-text">
					<h2><a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank"><?php the_title(); ?></a></h2>
					<p><?php the_excerpt(); ?></p>
					<p class="text-right"><a class="link" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank"><?php pll_e('scheda appartamento'); ?> <i class="fa fa-arrow-right" aria-hidden="true"></i></a></p>
				</div>
				<div class="col-xs-12 col-sm-5 col-md-4 apt-list-details">

					<ul class="acf-fieldgroup acf-dettagli">
						<?php
						$posti_letto = get_field('alloggio_posti_letto',$post->ID);
						$letti_matr = get_field('alloggio_letti_matrimoniali',$post->ID);
						$letti_sing = get_field('alloggio_letti_singoli',$post->ID);
						$clima = '';
						if(get_field('servizi_aircon',$post->ID)==1): $clima = 'Climatizzato'; endif;
						$wifi = '';
						if(get_field('servizi_wifi',$post->ID)==1): $wifi = 'Wi-Fi'; endif;
						?>
						<li class="det-ospiti"><?php echo $posti_letto." ".pll__('posti letto'); ?></li>
						<li class="det-letti"><?php echo $letti_matr." ".pll__('letti matrimoniali'); ?></li>
						<?php if($letti_sing > 0) : ?>
							<li class="det-letti-s"><?php echo $letti_sing." ".pll__('letti singoli'); ?></li>
						<?php endif; ?>
						<li class="det-clima"><?php pll_e($clima); ?></li>
						<li class="det-wifi"><?php echo $wifi; ?></li>
					</ul>

					<?php $apt_owner = get_field('apt_owner',$post->ID); ?>
					<div class="acf-fieldgroup owner-data">
					 	<figure class="pic-<?php echo $apt_owner; ?>"></figure>
						<span><?php echo $apt_owner; ?></span>
					</div>

				</div>
			</div>
			<?php
		}
	} else {
		// no posts found
	}

	// Restore original Post Data
	wp_reset_postdata();

	?>


<?php get_footer(); ?>
