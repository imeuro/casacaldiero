<?php 
/**
 *
 * Check if the user trying to PRINT the reports out has administrative privilages
 *
 */

if ( !current_user_can('manage_options') )
{
	die();
}

/**
 *
 * Check if we have the $calendarId in the request URL
 *
 * If not redirect the user back to the calendars list!
 *
 */

if ( !isset($_GET['id']) )
	wp_redirect(admin_url('admin.php?page=wp-booking-system&result=' . urlencode('Unsuccesfull request!') ));

$calendarId 		= $_GET['id'];

/**
 *
 * Get all the bookings associated with $calendarId and not with a "trash" status
 *
 *
 * @param 	int 	$calendarId
 *
 * @return 			associative array with all the bookings associated with $calendarId
 *
 * @access public
 * @static
 *
 */

function get_bookings( $calendarId )
{
	global $wpdb;

	if ( !$calendarId )
		return false;

	$bookingsQuery 		= $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'bs_bookings.* FROM ' . $wpdb->prefix . 'bs_bookings WHERE ' . $wpdb->prefix . 'bs_bookings.calendarID = %d AND ' . $wpdb->prefix . 'bs_bookings.bookingStatus != "trash" AND ' . $wpdb->prefix . 'bs_bookings.startDate >= ' . strtotime( date('Y-m-d') . ' 00:00:00' ) . ' ORDER BY ' . $wpdb->prefix . 'bs_bookings.startDate', $calendarId );
	$bookingsResult 	= $wpdb->get_results( $bookingsQuery, ARRAY_A );

	return $bookingsResult;
}

/**
 *
 * Get the Calendar Info
 *
 *
 * @param 	int 	$calendarId
 *
 * @return 			associative array with the calendarTitle field from database
 *
 * @access public
 * @static
 *
 */

function get_calendar_info( $calendarId )
{
	global $wpdb;

	if ( !$calendarId )
		return false;

	$calendarInfoQuery 		= $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'bs_calendars.calendarTitle FROM ' . $wpdb->prefix . 'bs_calendars WHERE ' . $wpdb->prefix . 'bs_calendars.calendarID = %d', $calendarId );
	$calendarInfoResult 	= $wpdb->get_row( $calendarInfoQuery, ARRAY_A );

	return $calendarInfoResult['calendarTitle'];
}

/**
 *
 * GEt calendar info, wee need the title for now
 *
 */

$calendarTitle 	= get_calendar_info( $calendarId );

/**
 *
 * Get all the bookings attached to calendarId!
 *
 */

$bookings 		= get_bookings( $calendarId );
?>
<style>
	@page {
		size: 'A4';
		margin: 1.5em;
	}
	@media all {
		.page-break	{ display: none; }
	}

	@media print 
	{
		html, body {
			width: 210mm;
			height: 297mm;
			counter-reset: page;
		}
		

		.page-break	{ display: block;  page-break-before: always; }
		#print .footer .pages::after {
			counter-increment: page;
			content: "Page " counter(page);

		}
	}
	#print 
	{
		font-family:'Helvetica Neue', helvetica, arial, sans-serif;
	}

	#print .page-content
	{
		border: 1px solid rgba(0,0,0,.2);
		padding: 12px;
		width: 205mm;
		height: 270mm;
	}
	#print .page-footer
	{
		margin-top: 8px;
		border: 1px solid rgba(0,0,0,.2);
		padding: 12px;
		width: 205mm;
		height: 10mm;
	}
	#print .table 
	{
		width: 100%;
		background-color: #fff;
		border-spacing: 0;
		border-collapse: collapse;
		border: 1px solid rgba(0,0,0,.1);
		margin-bottom: 8px;
	}
	#print .table > thead > tr > th,
	#print .table > tbody > tr > td,
	#print .table > tbody > tr > th
	{
		padding: 4px 8px;
		text-align: left;
		font-size: 13px;
	}
	#print .table > thead > tr > th,
	#print .table > tbody > tr > th
	{
		background-color: rgba(0,0,0,.04);
		color: rgba(0,0,0,.87);
		border-bottom: 1px solid rgba(0,0,0,.1);
		text-align: left;
	}

	#print .table > thead > tr > td
	{
		color: rgba(0,0,0,.54);
	}
	#print .table.table-details th
	{
		padding: 4px 8px;
		background-color: rgba(0,0,0,.04);
		font-size: 12px;
	}
	#print .table.table-details th::last-child
	{
		border-bottom: 0;
	}
	.table-bordered > thead > tr > th,
	.table-bordered > tbody > tr > th,
	.table-bordered > tfoot > tr > th,
	.table-bordered > thead > tr > td,
	.table-bordered > tbody > tr > td,
	.table-bordered > tfoot > tr > td {
		border: 1px solid rgba(0,0,0,.1);
	}
	.table-bordered > thead > tr > th,
	.table-bordered > thead > tr > td {
		border-bottom-width: 1px;
	}
	#print .item {
		border: 1px solid rgba(0,0,0,.2);
		border-radius: 2px;
		padding: 8px 16px;
		margin-bottom: 8px;
	}
	#print .item > .header,
	#print .item > .footer
	{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
	}
	#print .item.open > .header > div,
	#print .item.open > .footer > div
	{
		width: 23%;
	}
	#print .item.open > .header
	{
		padding-bottom: 8px;
		border-bottom: 1px solid rgba(0,0,0,.2);
	}
	#print .item.open > .footer
	{
		padding-top: 8px;
	}

	#print .item.open > .body
	{
		padding: 8px 0;
	}
	#print .item strong
	{
		display: inline-block;
		min-width: 100px;
		width: 100px;
	}
	#print .item > .header strong ,
	#print .item > .footer strong ,
	#print .item > .body strong
	{
		display: block;
	}
	#print .item > .footer > p > strong
	{
		display: inline-block;
		width: auto;
		min-width: auto;
	}
	#print .item strong,
	#print .item span
	{
		font-size: 13px;
		color: rgba(0,0,0,.87);
	}
	#print .item span
	{
		color: rgba(0,0,0,.54);
	}
	#print .item > .body div
	{
		margin-bottom: 8px;
	}
	#print .item > .body .info
	{
		padding: 4px 0;
	}
	#print > h1
	{
		color: rgba(0,0,0,.87);
		font-size: 22px;
	}
	#print h2
	{
		color: rgba(0,0,0,.64);
		font-size: 18px;
		margin: 0;
	}
	#print .footer
	{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		font-size: 12px;
		color: rgba(0,0,0,.45);
	}

</style>


<div id="print">
		
	<h1><?php echo __('Calendar: ', 'wpbs'); ?> <?php echo $calendarTitle; ?></h1>
	<?php
	/**
	 *
	 * Check if we've got back some results if not display a message!
	 *
	 */
	
	if ( count( $bookings ) > 0 )
	{
		/**
		 *
		 * Looping trought the bookings
		 *
		 */
		
		$i 				= 0;
		$page_break 	= 4;
		foreach ( $bookings as $booking )
		{
			
			$bookingData = json_decode($booking['bookingData'], true);
			?>
			<div class="item open">
				<div class="header">
					<div>
						<strong>Check-in:</strong>
						<span><?php echo date( "Y-m-d", $booking['startDate'] ); ?></span>
					</div>

					<div>
						<strong>Check-out:</strong>
						<span><?php echo date( "Y-m-d", $booking['endDate'] ); ?></span>
					</div>

					<div>
						<strong>Created Date:</strong>
						<span><?php echo date( "Y-m-d", $booking['createdDate'] ); ?></span>
					</div>
					<div>
						<strong>Status:</strong>
						<span><?php echo ucwords( $booking['bookingStatus'] ); ?></span>
					</div>
					<div>
						<strong>Read:</strong>
						<span><?php echo ( $booking['bookingRead'] == 1 ) ? 'Yes' : 'No'; ?></span>
					</div>

				</div>
				<div class="body">
					<?php
					$break 		= 4;
					$dataCount 	= count( $bookingData );
					?>
					<div>
						<?php
						$count 	= 0;
						foreach ( $bookingData as $key => $value):
							$count++;
						?>
						<div>
							<strong><?php echo ( $key == "submittedLanguage" ) ? 'Language' : ucwords( $key ); ?>:</strong>
							<span><?php echo $value; ?></span>
						</div>
						<?php

							if ( $count == $break):
								?>
								</div><div>
								<?php
							endif;


						endforeach;
						?>
					</div>

				</div>
			</div>
			<?php


			$i++;

			if ( $i == $page_break )
			{
				?>
				<div class="page-break"></div>
				<?php
				$i = 0;
			}
		}
	}
	else
	{
		echo __('No <strong>Bookings</strong> have been made on calendar <strong>' . $calendarTitle . '</strong>!', 'wpbs');
	}
	?>
		
</div>











<script>
	(function () {
		window.print();
	})(window);
</script>

<?php die(); ?>