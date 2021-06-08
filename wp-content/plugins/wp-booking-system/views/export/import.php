<?php
if ( !ABSPATH )
    return false;


global $wpdb;
set_time_limit(600);

$_data = $_FILES['file'];
if ( !$_data )
{
    echo json_encode( array(
        'error'     => true,
        'message'   => 'No DATA received'
    ) );
    die();
}


function get_extension($file) 
{
    $extension = end(explode(".", $file));
    return $extension ? $extension : false;
}

if ( isset( $_data['name'] ) ) 
{


    if ( !empty( $_data['name'] ) )
    {

        $extension       = get_extension( $_data['name'] );

        if ( !$extension || $extension != "wpbs" )
        {
            echo json_encode( array(
                'error'     => true,
                'message'   => __('Unsuported file extension! This is not our export file!')
            ) );
            die();
        }
        else
        {

            delete_option('wpbs-update-total');
            delete_option('wpbs-update-progress');

            $total      = 0;
            $progress   = 0;

            add_option('wpbs-update-total',     $total);
            add_option('wpbs-update-progress',  $progress);

            /**
             *
             * Everything looks good, we can proceed with the import
             *
             */


            $content            = file_get_contents( $_data['tmp_name'] );
            $content            = json_decode( $content );

            $tables             = array(
                'calendars'     => $wpdb->prefix . 'bs_calendars',
                'bookings'      => $wpdb->prefix . 'bs_bookings',
                'forms'         => $wpdb->prefix . 'bs_forms'
            );

            foreach ( $tables as $table )
            {
                $sql = "DROP TABLE IF EXISTS ". $table;
                $wpdb->query($sql);
            }


            $sql = "CREATE TABLE ".$wpdb->prefix."bs_bookings (
                  bookingID int(10) NOT NULL AUTO_INCREMENT,
                  calendarID int(10) NOT NULL DEFAULT '0',
                  formID int(10) NOT NULL DEFAULT '0',
                  startDate int(11) NOT NULL DEFAULT '0',
                  endDate int(11) NOT NULL DEFAULT '0',
                  createdDate int(11) NOT NULL DEFAULT '0',
                  bookingData text NOT NULL,
                  bookingStatus tinytext NOT NULL,
                  bookingRead varchar(1) NOT NULL DEFAULT '0',
                UNIQUE KEY (bookingID)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='WP Booking System - Bookings';";
            $sql .= "CREATE TABLE ".$wpdb->prefix."bs_calendars (
                  calendarID int(10) NOT NULL AUTO_INCREMENT,
                  calendarTitle text,
                  createdDate int(11) DEFAULT NULL,
                  modifiedDate int(11) DEFAULT NULL,
                  calendarData text,
                  calendarLegend text,
                  calendarUsers text,
                  calendarHash varchar(32),
                UNIQUE KEY (calendarID)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='WP Booking System - Calendars';";
            $sql .= "CREATE TABLE ".$wpdb->prefix."bs_forms (
                  formID int(10) NOT NULL AUTO_INCREMENT,
                  formTitle text,
                  formData text,
                  formOptions text,
                UNIQUE KEY (formID)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='WP Booking System - Forms';";
                
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql, true );



            $options                = (array) $content->options;
            
            // Adding options
            update_option( "wpbs_db_version", $options['wpbs_db_version'] );
            update_option( "wpbs-languages", $options['wpbs-languages'], '' );
            update_option( "wpbs-options", $options['wpbs-options'], '' );
            update_option( "wpbs-default-legend", $options['wpbs-default-legend'] );

            


            unset($content->options);



            $wpbsDB             = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            if ($wpbsDB->connect_errno) 
            {
                echo json_encode( array(
                    'error'     => true,
                    'message'   => __('Sorry, this website is experiencing problems.')
                ) );
                die;
            }

            $total = $total + count( $content->calendars );
            $total = $total + count( $content->forms );
            $total = $total + count( $content->bookings );

            update_option('wpbs-update-total', $total);
            


            foreach ( $content->calendars as $calendar )
            {
                // Creating the queries
                $calendarQuery = "INSERT INTO " 
                    . $wpdb->prefix 
                    ."bs_calendars (`calendarID`, `calendarTitle`, `createdDate`, `modifiedDate`, `calendarData`, `calendarLegend`, `calendarUsers`, `calendarHash`) VALUES ("
                    . $calendar->calendarID . ", "
                    . "'" . $calendar->calendarTitle . "', "
                    . $calendar->createdDate . ", "
                    . $calendar->modifiedDate . ", "
                    . "'" . $calendar->calendarData . "', "
                    . "'" . $calendar->calendarLegend . "', "
                    . "'" . $calendar->calendarUsers . "', "
                    . "'" . $calendar->calendarHash . "'"
                    . ");";

                if ( !$calendarResults = $wpbsDB->query($calendarQuery) )
                {
                    echo json_encode( array(
                        'error'     => true,
                        'message'   => __('Query failed to execute for table "calendars"!')
                    ) );
                    die;
                }

                $progress++;
                update_option('wpbs-update-progress', ($progress / $total ) * 100 );
            }

            foreach ( $content->forms as $form )
            {
                // Creating the queries
                $formQuery = "INSERT INTO " 
                    . $wpdb->prefix 
                    ."bs_forms (`formID`, `formTitle`, `formData`, `formOptions`) VALUES ("
                    . (int) $form->formID . ", "
                    . "'" . $wpbsDB->real_escape_string( $form->formTitle ) . "', "
                    . "'" . $wpbsDB->real_escape_string( $form->formData ) . "', "
                    . "'" . $wpbsDB->real_escape_string( $form->formOptions ) . "'"
                    . ");";

                if ( !$formResults = $wpbsDB->query($formQuery) )
                {
                    echo json_encode( array(
                        'error'     => true,
                        'message'   => __('Query failed to execute for table "forms"!')
                    ) );
                    die;
                }

                $progress++;
                update_option('wpbs-update-progress', ($progress / $total ) * 100 );
            }



            foreach ( $content->bookings as $booking )
            {
                // Creating the queries
                $bookingQuery = "INSERT INTO " 
                    . $wpdb->prefix 
                    ."bs_bookings (`bookingID`, `calendarID`, `formID`,  `startDate`, `endDate`, `createdDate`, `bookingData`, `bookingStatus`, `bookingRead`) VALUES ("
                    . (int) $booking->bookingID . ", "
                    . (int) $booking->calendarID . ", "
                    . (int) $booking->formID . ", "
                    . (int) $booking->startDate . ", "
                    . (int) $booking->endDate . ", "
                    . (int) $booking->createdDate . ", "
                    . "'" . $wpbsDB->real_escape_string( $booking->bookingData ) . "', "
                    . "'" . $wpbsDB->real_escape_string( $booking->bookingStatus ) . "', "
                    . "'" . $wpbsDB->real_escape_string( $booking->bookingRead ) . "'"
                    . ");";

                if ( !$bookingResults = $wpbsDB->query($bookingQuery) )
                {
                    echo json_encode( array(
                        'error'     => true,
                        'message'   => __('Query failed to execute for table "bookings"!')
                    ) );
                    die;
                }

                $progress++;
                update_option('wpbs-update-progress', ($progress / $total ) * 100 );
            }


            $time_elapsed = microtime(true) - $start;

            if ( $wpdb->last_error == '' )
            {
                echo json_encode( array(
                    'error'     => false,
                    'message'   => __('Import was succesfull!')
                ) );


            }
            else
            {
                echo json_encode( array(
                    'error'     => true,
                    'message'   => '<p>' . __('Import error: ') . $wpdb->last_error . '</p>'
                ) );
            }


            delete_option('wpbs-update-total');
            delete_option('wpbs-update-progress');
            
            die; 
            
        }
    }
    else
    {
        echo json_encode( array(
            'error'     => true,
            'message'   => __('<p>You have to add the <strong>backup/exported</strong> file first then hit <strong>Import</strong></p>')
        ) );
        die;
    }
}