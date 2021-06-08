<?php 

if ( current_user_can('manage_options') )
{
	echo "Bla bla bla";
}
if(!(version_compare(PHP_VERSION, '5.2.0') >= 0))
{
	wp_redirect(admin_url('admin.php?page=wp-booking-system&do=export-calendar&id=' . $calendarId . '&result=' . urlencode('PHP version not met') ));
}


if ( !isset($_GET['id']) )
	wp_redirect(admin_url('admin.php?page=wp-booking-system&do=export-calendar&id=' . $calendarId . '&result=' . urlencode('Unsuccesfull request!') ));


if ( isset($_GET['download']) )
{
	global $wpdb;

	$calendarId 		= $_GET['id'];


	function getForms( $calendarId )
	{
		$getFormsQuery					= $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_bookings', null);
		$results['calendars']			= $wpdb->get_results( $calendarsQuery );
	}


	$calendarsQuery					= $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars', null);
	$results['calendars']			= $wpdb->get_results( $calendarsQuery );

	$formsQuery						= $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_forms', null);
	$results['forms']				= $wpdb->get_results( $formsQuery );

	$bookingsQuery					= $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_bookings', null);
	$results['bookings']			= $wpdb->get_results( $bookingsQuery );

	// echo'<pre>';var_dump(json_encode($results));echo '</pre>';
	// die();
	// $calendarData 		= json_decode( $results['calendarData'], true );
	$calendarLegend		= json_decode( $results['calendarLegend'], true );

	// echo'<pre>';var_dump($calendarData);echo'</pre>';


	// $csvArray			= array();
	// $csvArray[]			= array( 'year', 'month', 'day', 'note', 'availability', 'bookable', 'color', 'splitColor', 'auto-pending', 'sync' );

	// $csvLine 			= array();

	// foreach($calendarData as $year => $months)
 //    {
 //        $csvLine['year'] = $year;

 //        if($months)
 //        {
 //            // Loop trought months
 //            foreach($months as $month => $days)
 //            {
 //            	$csvLine['month'] = $month;
 //                $availabilityMonths = $availabilityMonths + count( $month );

 //                if($days)
 //                {
 //                    // Loop trought days
 //                    foreach($days as $day => $status)
 //                    {
 //                    	if (is_int($day))
 //                    	{
 //                    		$csvLine['day'] 			= $day;
 //                    		if ( isset( $calendarData[$year][$month]['description-' . $day] ) )
 //                    		{
 //                    			$csvLine['note'] 		= $calendarData[$year][$month]['description-' . $day];
 //                    		}
 //                    		$csvLine['availability']	= $calendarLegend[$status]['name']['default'];
 //                    		$csvLine['bookable'] 		= $calendarLegend[$status]['bookable'];
 //                    		$csvLine['color'] 			= $calendarLegend[$status]['color'];
 //                    		$csvLine['splitColor'] 		= $calendarLegend[$status]['splitColor'];
 //                    		$csvLine['auto-pending'] 	= $calendarLegend[$status]['auto-pending'];
 //                    		$csvLine['sync']		 	= $calendarLegend[$status]['sync'];
 //                    	}
    					
 //    					$csvArray[] = $csvLine;
 //                    }
 //                }
 //            }
 //        }
 //    }


	// $sql                = $wpdb->prepare('SELECT ' . $wpdb->prefix . 'bs_bookings.* FROM ' . $wpdb->prefix . 'bs_bookings WHERE ' . $wpdb->prefix . 'bs_bookings.calendarID=%d', $calendarId);

 //    $bookings           = $wpdb->get_results( $sql, ARRAY_A );
	
	// $bookingsArray 		= array();
	// $bookingsArray[] 	= array('bookingID', 'calendarID', 'formID', 'startDate', 'endDate', 'createdDate', 'bookingStatus', 'bookingRead'); 


	// $bookingLine		= array();

    foreach ( $bookings as $booking )
    {
		$bookingLine['bookingID'] 		= $booking['bookingID'];
		$bookingLine['calendarID'] 		= $booking['calendarID'];
		$bookingLine['formID'] 			= $booking['formID'];
		$bookingLine['startDate'] 		= date( "Y-m-d", $booking['startDate'] );
		$bookingLine['endDate'] 		= date( "Y-m-d", $booking['endDate'] );
		$bookingLine['createdDate'] 	= date( "Y-m-d", $booking['createdDate'] );
		$bookingLine['bookingStatus'] 	= $booking['bookingStatus'];
		$bookingLine['bookingRead'] 	= $booking['bookingRead'];

		$bookingsArray[] 				= $bookingLine;
    }



	// Generate calendars.json

	file_put_contents( 'calendars.json', json_encode( $results ) );

    $f = fopen('bookings.csv', 'w');

    foreach ($bookingsArray as $line) 
    {
        fputcsv($f, $line, ',');
    }

    $year 		= date('Y');
    $month 		= date('m');
    $day 		= date('d');

    $files 		= array('calendars.json', 'bookings.csv');
    $zipname 	= 'wpbs-export-'.$year.'-'.$month.'-'.$day.'.zip';
	$zip 		= new ZipArchive;
	
	$zip->open( $zipname, ZipArchive::OVERWRITE );
	
	foreach ( $files as $file )
	{
		$zip->addFile($file);
	}
	$zip->close();


    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="'.$zipname.'";');
    header('Content-Length: ' . filesize($zipname));
	readfile($zipname);
}

die();
?>     