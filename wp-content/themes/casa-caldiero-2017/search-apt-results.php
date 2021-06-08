<?php
/* Template Name: Ris. ricerca */

get_header(); ?>

<div class="content-padder row">

		<?php if ( have_posts() ) : ?>
		<header>
			<h1 class="page-title text-center">
				<?php pll_e('le nostre disponibilita'); ?>
			</h1>
		</header><!-- .page-header -->

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="col-xs-12 bgbianco entry-main-content">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>

	<?php else : ?>

		<?php get_template_part( 'no-results', 'archive' ); ?>

	<?php endif; ?>

</div><!-- .content-padder -->

<?php
$checkin = str_replace('/','-',$_GET['check_in']);
$checkout = str_replace('/','-',$_GET['check_out']);
$guests = $_GET['guests'];
$num_available = 0;

//***** PRE-FILTRO PER NUMERO DI GUESTS: *****//

// WP_Query arguments
$args = array (
	'post_type'              => array( 'appartamenti' ),
	'post_status'            => array( 'publish' ),
	'meta_query'	=> array(
		array(
		'key'		=> 'alloggio_posti_letto',
		'value'		=> $guests,
		'type'		=> 'NUMERIC',
		'compare'	=> '>='
		)
	),
	'posts_per_page'         => '10',
	'order'									 => 'ASC'
);

// The Query
$apts_res = new WP_Query( $args );

// The Loop
if ( $apts_res->have_posts() ) {
	$available = 'NO';
	while ( $apts_res->have_posts() ) {
		$apts_res->the_post();
		$num_available++;

		//***** CONTROLLO SE LE DATE SONO DISPONIBILI: *****//

		$apt_ID = get_field('numero_appartamento');
		$jsoncal =  $wpdb->get_var("SELECT `calendarData` FROM `wpcc2017_bs_calendars` WHERE `calendarID` = ".$apt_ID." LIMIT 0, 30 ");
		$arraycal = json_decode($jsoncal,true);

		list($in_month, $in_day, $in_year) = explode("-", $checkin);
		list($out_month, $out_day, $out_year) = explode("-", $checkout);
		$in_month=ltrim($in_month,"0");
		$in_day=ltrim($in_day,"0");
		$out_month=ltrim($out_month,"0");
		$out_day=ltrim($out_day,"0");

		$format = 'm-j-Y';
		$date_in = DateTime::createFromFormat($format, $checkin)->format('Y-m-j');
		$date_out = DateTime::createFromFormat($format, $checkout)->format('Y-m-j');
		$datetime1 = new DateTime($date_in);
		$datetime2 = new DateTime($date_out);
		$interval = $datetime1->diff($datetime2);

		//var_dump($arraycal[$in_year][$in_month][$in_day]);

		

		

			// is check-in day available ?
			if ( isset($arraycal[$in_year][$in_month][$in_day]) && $arraycal[$in_year][$in_month][$in_day] == 1 ) {
				$available = 'NO';
				$num_available--;
				break;
			}
			// are the other days available ?
			for($i = 0; $i < $interval->days; ++$i) {
				$date_to_check = $datetime1->add(new DateInterval('P1D')); // add 1 day
				$dttchk_d = $date_to_check->format('d');
				$dttchk_m = $date_to_check->format('m');
				$dttchk_y = $date_to_check->format('Y');
				if ( isset($arraycal[$dttchk_y][$dttchk_m][$dttchk_d]) && $arraycal[$dttchk_y][$dttchk_m][$dttchk_d] == 1 ) {
					$available = 'NO';
					$num_available--;
					//echo '<br>'.$date_to_check->format('d/m/Y').' ......NO';
					break;
				} else {
					$available = 'YES';
					//echo '<br>'.$date_to_check->format('d/m/Y').' ......ok';
				}
			}

		if ($available == 'YES') :

			$apt_date_permalink = get_permalink();
			$apt_date_permalink .='?check_in='.urlencode($_GET["check_in"]);
			$apt_date_permalink .='&check_out='.urlencode($_GET["check_out"]);
			$apt_date_permalink .='&guests='.urlencode($_GET["guests"]);

		?>
			<div class="apt-list-item bgbianco row">
				<figure class="col-xs-12 col-sm-3 col-md-3"><a href="<?php echo $apt_date_permalink; ?>" title="<?php echo get_the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a></figure>
				<div class="col-xs-12 col-sm-4 col-md-5 apt-list-text">
					<h2><a href="<?php echo $apt_date_permalink; ?>" title="<?php echo get_the_title(); ?>"><?php the_title(); ?></a></h2>
					<p><?php the_excerpt(); ?></p>
					<p class="text-right"><a class="link" href="<?php echo $apt_date_permalink; ?>" title="<?php echo get_the_title(); ?>"><?php pll_e('scheda appartamento'); ?> <i class="fa fa-arrow-right" aria-hidden="true"></i></a></p>
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
		endif;

	}
}

if ($num_available == 0) {
	// no posts found
	?>
	<div class="apt-list-item bgbianco row">
		<br><br>
		<?php if (pll_current_language() == 'it') : ?>
			<h2 class="text-center">Ci dispiace,</h2>
			<p class="text-center">non abbiamo disponibilità per il periodo selezionato. <br />Magari puoi provare cambiando di qualche giorno le tue date di arrivo e partenza?</p>
		<?php else : ?>
			<h2 class="text-center">We're sorry,</h2>
			<p class="text-center">we dont't have any vacancy for the period selected. <br />Maybe you can try changing your check-in and check-out dates?</p>
		<?php endif; ?>
		<br><br>
	</div>
	<?php
}

// Restore original Post Data
wp_reset_postdata();

?>


<?php get_footer(); ?>
