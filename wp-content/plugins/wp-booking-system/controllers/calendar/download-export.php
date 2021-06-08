<?php 

if ( current_user_can('manage_options') )
{
	if(!(version_compare(PHP_VERSION, '5.2.0') >= 0))
	{
		wp_redirect(admin_url('admin.php?page=wp-booking-system-export&result=' . urlencode('PHP version not met') ));
	}


	if ( isset($_GET['download']) )
	{
		global $wpdb;

		$get_calendars      = $wpdb->prepare('SELECT ' . $wpdb->prefix . 'bs_calendars.* FROM ' . $wpdb->prefix . 'bs_calendars;', null);
        $get_bookings       = $wpdb->prepare('SELECT ' . $wpdb->prefix . 'bs_bookings.* FROM ' . $wpdb->prefix . 'bs_bookings;', null);
        $get_forms          = $wpdb->prepare('SELECT ' . $wpdb->prefix . 'bs_forms.* FROM ' . $wpdb->prefix . 'bs_forms;', null);

        $export             = array(
            'calendars'     => $wpdb->get_results( $get_calendars, ARRAY_A ),
            'bookings'      => $wpdb->get_results( $get_bookings, ARRAY_A ),
            'forms'         => $wpdb->get_results( $get_forms ),
            'options'       => array(
                'wpbs_db_version'       => get_option('wpbs_db_version'),
                'wpbs-languages'        => get_option('wpbs-languages'),
                'wpbs-options'          => get_option('wpbs-options'),
                'wpbs-default-legend'   => get_option('wpbs-default-legend'),
            )
        );

        $year 		= date('Y');
	    $month 		= date('m');
	    $day 		= date('d');

	    $filename 	= 'wpbs-export-'.$year.'-'.$month.'-'.$day.'.wpbs';


	    if ( is_writable( plugin_dir_path( __FILE__ ) ) )
	    {
	    	file_put_contents( $filename, json_encode( $export ) );
	    }
	    else
	    {
	    	wp_redirect(admin_url('admin.php?page=wp-booking-system-export&code=401&result=' . urlencode('Could not generate export file reason: <strong>Permission Denied!</strong>') ));
	    	die();
	    }
		

	    header('Content-Type: application/json');
	    header('Content-Disposition: attachment; filename="'.$filename.'";');
	    header('Content-Length: ' . filesize($filename));
		readfile($filename);
	}

	die();
	
}
?>     