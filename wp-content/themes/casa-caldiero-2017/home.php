<?php
/* Template Name: Home Page */

get_header(); ?>
	</div>
</div>
<div class="container">
	<div class="main-content-inner row">
	<?php while ( have_posts() ) : the_post(); ?>

		<div id="carousel-home-page" class="carousel slide" data-ride="carousel" data-interval="10000">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
				<?php
				$sliderpics = get_field('immagini_slider_hp');
				$slide = 0;
				foreach ($sliderpics as $sliderbullet) {
					if ($slide == 0) : $active = 'active'; else : $active = ''; endif;
		    	echo '<li data-target="#carousel-home-page" data-slide-to="'.$slide.'" class="'.$active.'"></li>';
					$slide++;
				}
				?>
		  </ol>

			<!-- Wrapper for slides -->
		  <div class="carousel-inner" role="listbox">
				<?php
				$sliderpics = get_field('immagini_slider_hp');
				$i = 1;
				foreach ($sliderpics as $sliderpic) {
					if ($i == 1) : $active = ' active'; else : $active = ''; endif;
					echo '<div class="item'.$active.'">';
					echo wp_get_attachment_image($sliderpic['id'],'slider-hp');
					echo '</div>';
					$i++;
				}
				?>
		  </div>

			<div id="slider-infobox" class="panel panel-default hidden-xs col-sm-5">
			  <div class="panel-heading">
			    <h2 class="panel-title"><?php the_field('titolo_box_slider_hp'); ?></h2>
			  </div>
			  <div class="panel-body"><?php the_field('testo_box_slider_hp'); ?></div>
			</div>

		</div>

		<?php include 'searchform-apt-home.php'; ?>

	</div>
</div>

<div class="container">
	<div class="main-content-inner">

		<div class="bggrigio HPblocks row">
			<div class="col-xs-12 col-sm-6">
				<?php
				$chi_siamo_ID = 2;
				if (function_exists('pll_get_post') && ($tr_id = pll_get_post($chi_siamo_ID)) && !empty($tr_id))
					$chi_siamo_ID = pll_get_post($chi_siamo_ID);
				$chi_siamo = get_post($chi_siamo_ID);
				?>
				<h3 class="headline"><?php echo $chi_siamo->post_title; ?></h3>
				<figure class="bgbianco">
					<a class="clearfix" href="<?php echo get_permalink($chi_siamo->ID); ?>" title="<?php echo $chi_siamo->post_title; ?>">
						<?php echo get_the_post_thumbnail($chi_siamo->ID,'thumbnail-hp'); ?>
					</a>
				</figure>
				<div class="bgbianco virgolette">
					<?php echo get_field('testo_hp',$chi_siamo->ID); ?>
					<a class="link-continua text-right" href="<?php echo get_permalink($chi_siamo->ID); ?>" title="<?php echo $chi_siamo->post_title; ?>">[continua...]</a>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<?php
				$dove_siamo_ID = 10;
				if (function_exists('pll_get_post') && ($tr_id = pll_get_post($dove_siamo_ID)) && !empty($tr_id))
					$dove_siamo_ID = pll_get_post($dove_siamo_ID);
				$dove_siamo = get_post($dove_siamo_ID);
				?>
				<h3 class="headline"><?php echo $dove_siamo->post_title; ?></h3>
				<figure class="bgbianco">
					<a class="clearfix" href="<?php echo get_permalink($dove_siamo->ID); ?>" title="<?php echo $dove_siamo->post_title; ?>">
						<?php echo get_the_post_thumbnail($dove_siamo->ID,'thumbnail-hp'); ?>
					</a>
				</figure>
				<div class="bgbianco virgolette">
					<?php echo get_field('testo_hp',$dove_siamo->ID); ?>
					<a class="link-continua text-right" href="<?php echo get_permalink($dove_siamo->ID); ?>" title="<?php echo $dove_siamo->post_title; ?>">[continua...]</a>
				</div>
			</div>

			<hr />

			<div class="col-xs-12 col-sm-6">
				<?php
				$appartamenti_ID = 6;
				if (function_exists('pll_get_post') && ($tr_id = pll_get_post($appartamenti_ID)) && !empty($tr_id))
					$appartamenti_ID = pll_get_post($appartamenti_ID);
				$appartamenti = get_post($appartamenti_ID);
				?>
				<h3 class="headline"><?php echo $appartamenti->post_title; ?></h3>
				<figure class="bgbianco">
					<a class="clearfix" href="<?php echo get_permalink($appartamenti->ID); ?>" title="<?php echo $appartamenti->post_title; ?>">
						<?php echo get_the_post_thumbnail($appartamenti->ID,'thumbnail-hp'); ?>
					</a>
				</figure>
				<div class="bgbianco virgolette">
					<?php echo get_field('testo_hp',$appartamenti->ID); ?>
					<a class="link-continua text-right" href="<?php echo get_permalink($appartamenti->ID); ?>" title="<?php echo $appartamenti->post_title; ?>">[continua...]</a>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<?php
				$dintorni_ID = 8;
				if (function_exists('pll_get_post') && ($tr_id = pll_get_post($dintorni_ID)) && !empty($tr_id))
					$dintorni_ID = pll_get_post($dintorni_ID);
				$dintorni = get_post($dintorni_ID);
				?>
				<h3 class="headline"><?php echo $dintorni->post_title; ?></h3>
				<figure class="bgbianco">
					<a class="clearfix" href="<?php echo get_permalink($dintorni->ID); ?>" title="<?php echo $dintorni->post_title; ?>">
						<?php echo get_the_post_thumbnail($dintorni->ID,'thumbnail-hp'); ?>
					</a>
				</figure>
				<div class="bgbianco virgolette">
					<?php echo get_field('testo_hp',$dintorni->ID); ?>
					<a class="link-continua text-right" href="<?php echo get_permalink($dintorni->ID); ?>" title="<?php echo $dintorni->post_title; ?>">[continua...]</a>
				</div>
			</div>

			<hr />

		</div>

		<div class="bggrigio HPblocks HPfeedbacks row">
			<div class="col-xs-12">
				<h3 class="headline"><?php pll_e('Dicono di noi'); ?></h3>
				<div class="bgbianco virgolette">


					<div id="carousel-feedback" class="carousel slide row" data-ride="carousel" data-interval="10000">
							<?php
							// WP_Query arguments
							$textsargs = array (
								'post_type'              => array( 'dicono_di_noi' ),
								'post_status'            => array( 'publish' ),
								'nopaging'               => true,
								'posts_per_page'         => '10',
							);

							$slidertexts = new WP_Query( $textsargs );
							?>

						<!-- Wrapper for slides -->
					  <div class="carousel-inner" role="listbox">
							<?php
							$slide_txt= 0;
							if ( $slidertexts->have_posts() ) {
								while ( $slidertexts->have_posts() ) {
									$slidertexts->the_post();

									if ($slide_txt == 0) : $active = ' active'; else : $active = ''; endif;
									echo '<div class="item'.$active.'">';
									echo '<p>'.get_the_content().'</p>';
									if(!empty(get_field('feed_author',$slidertexts->ID)) || !empty(get_field('feed_from',$slidertexts->ID)))
										echo '<strong>'.get_field('feed_author',$slidertexts->ID)." - ".get_field('feed_from',$slidertexts->ID)."</strong>";
									echo '</div>';
									$slide_txt++;

								}
							}
							wp_reset_postdata();
							?>
					  </div>

					</div>



				</div>
			</div>
		</div>

	<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
