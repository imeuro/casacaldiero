<?php
$searchres_ID = 182;
if (function_exists('pll_get_post') && ($tr_id = pll_get_post($searchres_ID)) && !empty($tr_id))
	$searchres_ID = pll_get_post($searchres_ID);
?>

<form role="search" method="get" id="searchform" action="<?php echo get_permalink($searchres_ID); ?>">
	<div id="datepicker" class="bgbianco">
		<h4 class="col-xs-12 col-lg-2"><?php pll_e('VERIFICA DISPONIBILITA'); ?></h4>
		<span class="col-xs-6 col-lg-3">
			<label>Check in</label>
			<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
			<input type="text" name="check_in" value="" id="dpd1" autocomplete="off" placeholder="Check in">
		</span>
		<span class="col-xs-6 col-lg-3">
			<label>Check out</label>
			<i class="fa fa-calendar-times-o" aria-hidden="true"></i>
			<input type="text" name="check_out" value="" id="dpd2" placeholder="Check out">
		</span>
		<span class="col-xs-6 col-lg-2">
			<label>Persone</label>
			<i class="fa fa-users" aria-hidden="true"></i>
			<input type="text" name="guests" value="" id="dpd2" placeholder="Persone">
		</span>
		<span class="col-xs-6 col-lg-2">
			<i class="fa fa-search" aria-hidden="true"></i>
			<input type="submit" value="cerca" id="searchsubmit" class="cerca" />
		</span>
	</div>
</form>
