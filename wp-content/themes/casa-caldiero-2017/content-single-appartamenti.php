<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>

	<header>
		<figure><?php the_post_thumbnail( 'main-for-apt' ); ?></figure>
		<h1 class="page-title text-center"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="post-content">
		<div class="post-content-main col-sm-7 col-md-8">

			<div class="bgbianco">
				<?php the_content(); ?>
			</div>



			<div id="carousel-apartment" class="carousel slide" data-ride="carousel" data-interval="10000">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
					<?php
					$gallery_apt = get_field('galleria_immagini');
					$slide = 0;
					foreach ($gallery_apt as $sliderbullet) {
						if ($slide == 0) : $active = 'active'; else : $active = ''; endif;
			    	echo '<li data-target="#carousel-apartment" data-slide-to="'.$slide.'" class="'.$active.'"></li>';
						$slide++;
					}
					?>
			  </ol>

				<!-- Wrapper for slides -->
			  <div class="carousel-inner" role="listbox">
					<?php
					$gallery_apt = get_field('galleria_immagini');
					$i = 1;
					foreach ($gallery_apt as $sliderpic) {
						if ($i == 1) : $active = ' active'; else : $active = ''; endif;
						echo '<div class="item'.$active.'">';
						echo '<a href="'.wp_get_attachment_image_src($sliderpic['id'],'large')[0].'" data-rel="lightbox" rel="gallery-'.get_field('numero_appartamento').'">';
						echo wp_get_attachment_image($sliderpic['id'],'slider-hp','', array( "rel" => "lightbox-'.get_field('numero_appartamento').'", "alt" => 'Casa Caldiero - Positano - '.pll__('Appartamento').' '.get_field('numero_appartamento') ));
						echo '</a>';
						echo '</div>';
						$i++;
					}
					?>
			  </div>

				<!-- Controls
			  <a class="left carousel-control" href="#carousel-apartment" role="button" data-slide="prev">
			    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#carousel-apartment" role="button" data-slide="next">
			    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
				-->
			</div>

			<div id="acf-alloggio" class="bgbianco">
				<h2 class="acf-title col-xs-12 col-md-3"><?php pll_e('INFO'); ?></h2>
				<div class="acf-content col-xs-12 col-md-9">
					<ul class="acf-fieldgroup col-xs-12 row">
						<?php if (get_field('phone_number_owner')) : ?>
						<li class="col-xs-12 col-sm-6">
							<strong class="acf-field-label"><?php echo '<strong>'.pll__('Tel.').'</strong> ' ?></strong><br/>
							<span class="acf-field-value"><?php the_field('phone_number_owner'); ?></span>
						</li>
						<?php endif; ?>
						<?php if (get_field('email_owner')) : ?>
						<li class="col-xs-12 col-sm-6">
							<strong class="acf-field-label"><?php echo '<strong>'.pll__('Email').'</strong> ' ?></strong><br/>
							<span class="acf-field-value"><?php echo createMailto(get_field('email_owner')); ?></span>
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>

			<div id="acf-alloggio" class="bgbianco">
				<h2 class="acf-title col-xs-12 col-md-3"><?php pll_e('L\'ALLOGGIO'); ?></h2>
				<div class="acf-content col-xs-12 col-md-9">
					<?php printACFgroup(63); ?>
				</div>
			</div>


			<div id="acf-alloggio" class="bgbianco">
				<h2 class="acf-title col-xs-12 col-md-3"><?php pll_e('I SERVIZI'); ?></h2>
				<div class="acf-content col-xs-12 col-md-9">
					<?php printACFgroup(84,$isachecklist=true); ?>
				</div>
			</div>

			<?php
			if( have_rows('links_esterni') || have_rows('documenti_allegati') ): ?>
				<div id="acf-alloggio" class="bgbianco">
					<h2 class="acf-title col-xs-12 col-md-3"><?php pll_e('ALLEGATI'); ?></h2>
					<div class="acf-content col-xs-12 col-md-9">
						<ul class="acf-fieldgroup col-xs-12 row">
				    <?php
						if( have_rows('links_esterni') ):
							while ( have_rows('links_esterni') ) : the_row(); ?>
								<li class="col-xs-12">
									<a href="<?php echo get_sub_field('link_allegato'); ?>" title="<?php echo get_sub_field('testo_allegato'); ?>" target="_blank"><i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<?php the_sub_field('testo_allegato'); ?></a>
								</li>
						<?php
					    endwhile;
						endif;
						?>

						<?php
						if( have_rows('documenti_allegati') ):
							while ( have_rows('documenti_allegati') ) : the_row(); ?>
							<li class="col-xs-12">
								<a href="<?php echo get_sub_field('file_allegato')['url']; ?>" title="<?php echo get_sub_field('file_allegato')['filename']; ?>" target="_blank"><i class="fa fa-file" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<?php the_sub_field('testo_allegato'); ?></a>
							</li>
						<?php
					    endwhile;
						endif;
						?>

						</ul>
					</div>
				</div>
			<?php	endif; ?>

		</div>


		<aside class="post-aside col-sm-5 col-md-4">

			<div class="bgbianco apt-single-details">
				<h2 class="acf-title"><?php pll_e('DETTAGLI APPARTAMENTO'); ?></h2>
				<div class="apt-price">
					<strong class="est-price">
						<span><?php echo get_field('prezzo_appartamento'); ?></span> &euro;/day *
						<small>* <?php pll_e('price disclaimer'); ?></small>

					</strong>
				</div>
				<ul class="acf-fieldgroup acf-dettagli">
					<?php
					$posti_letto = get_field('alloggio_posti_letto');
					$letti_matr = get_field('alloggio_letti_matrimoniali');
					$letti_sing = get_field('alloggio_letti_singoli');
					$clima = '';
					if(get_field('servizi_aircon')==1): $clima = 'Climatizzato'; endif;
					$wifi = '';
					if(get_field('servizi_wifi')==1): $wifi = 'Wi-Fi'; endif;
					?>
					<li class="det-ospiti"><?php echo pll__($posti_letto) . " ". pll__('posti letto'); ?></li>
					<li class="det-letti"><?php echo pll__($letti_matr). " ".pll__('letti matrimoniali'); ?></li>
					<?php  if ($letti_sing > 0) : ?>
						<li class="det-letti-s"><?php echo pll__($letti_sing). " ".pll__('letti singoli'); ?></li>
					<?php endif; ?>
					<li class="det-clima"><?php echo pll__($clima); ?></li>
					<li class="det-wifi"><?php echo $wifi; ?></li>
				</ul>

				<?php $apt_owner = get_field('apt_owner',$post->ID); ?>
				<div class="acf-fieldgroup owner-data">
				 	<figure class="pic-<?php echo $apt_owner; ?>"></figure>
					<span><?php echo $apt_owner; ?></span>
				</div>

			</div>

			<a name="availability"></a>
			<div class="bgbianco">
				<h2 class="acf-title"><?php pll_e('VERIFICA DISPONIBILITA'); ?></h2>
				<div id="apt-calendario">
					<div id="preventchanges" class="hidden">
						<small>
						<?php
							$checkin = str_replace('/','-',$_GET["check_in"]);
							$checkout = str_replace('/','-',$_GET["check_out"]);
							$guests = $_GET['guests'];
						 	if ($checkin && $checkout && $guests) {
								$format = 'm-j-Y';
								$date_in = DateTime::createFromFormat($format, $checkin)->format('j M Y');
								$date_out = DateTime::createFromFormat($format, $checkout)->format('j M Y');

								echo '<strong>check in: </strong>'.$date_in.'<br/>';
								echo '<strong>check out: </strong>'.$date_out.'<br/>';
								echo '<strong>guests: </strong>'.$guests.'<br/>';
						 	}
						?>
							<a href="<?php echo get_permalink().'#availability'; ?>"><?php pll_e('Modifica dettagli'); ?></a>
						</small>
					</div>
					<?php
					$apt_num = get_field('numero_appartamento');
					if ($apt_owner =='francesca') :
						$form = 1;
					elseif ($apt_owner =='letizia') :
						$form = 2;
					elseif ($apt_owner =='piera') :
						$form = 3;
					else :
						echo 'apt_owner: '.$apt_owner;
					endif;

					echo do_shortcode( '[wpbs id="'.$apt_num.'" form="'.$form.'" title="no" legend="yes" tooltip="2" language="auto"]' ) ?>
				</div>
			</div>
		</aside>


	</div><!-- .entry-content -->

</article><!-- #post-## -->
