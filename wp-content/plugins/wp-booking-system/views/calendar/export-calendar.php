<?php global $wpdb;?>
<div class="wrap wpbs-wrap" id="wpbs-export">
    <div class="wrap">
        <?php
        $calendarID         = (int)$_GET['id'];


        $sql                = $wpdb->prepare('SELECT ' . $wpdb->prefix . 'bs_calendars.* FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$_GET['id']);
        $calendar           = $wpdb->get_row( $sql, ARRAY_A );
        #var_dump($calendar);
        ?>
        
        <?php echo __("<span class='header'>Export Calendar</span>",'wpbs'); ?>
        <?php echo __("<span class='subheader'>" . $calendar['calendarTitle'] . "</span>",'wpbs') ;?>

        <?php
        if ( isset( $_GET['result'] ) )
            $result         = $_GET['result'];
        
        if(!empty($result) && $result):
        ?>
        <div id="message" class="updated">
            <p><?php echo __($result,'wpbs')?></p>
        </div>
        <?php endif;?>


        <?php if($calendar): ?>

        <?php
        // Get total of bookings
        $sql                = $wpdb->prepare('SELECT COUNT(' . $wpdb->prefix . 'bs_bookings.bookingID) as total, 
            (SELECT COUNT(' . $wpdb->prefix . 'bs_bookings.bookingID) FROM ' . $wpdb->prefix . 'bs_bookings WHERE ' . $wpdb->prefix . 'bs_bookings.calendarID=%d AND ' . $wpdb->prefix . 'bs_bookings.bookingStatus = "pending") AS pendingTotal,
            (SELECT COUNT(' . $wpdb->prefix . 'bs_bookings.bookingID) FROM ' . $wpdb->prefix . 'bs_bookings WHERE ' . $wpdb->prefix . 'bs_bookings.calendarID=%d AND ' . $wpdb->prefix . 'bs_bookings.bookingStatus = "accepted") AS accepted,
            (SELECT COUNT(' . $wpdb->prefix . 'bs_bookings.bookingID) FROM ' . $wpdb->prefix . 'bs_bookings WHERE ' . $wpdb->prefix . 'bs_bookings.calendarID=%d AND ' . $wpdb->prefix . 'bs_bookings.bookingStatus = "trash") AS trash
            FROM ' . $wpdb->prefix . 'bs_bookings WHERE ' . $wpdb->prefix . 'bs_bookings.calendarID=%d', $calendarID, $calendarID, $calendarID, $calendarID);

        $bookings           = $wpdb->get_row( $sql, ARRAY_A );

        $legendTotal        = count((array)json_decode($calendar['calendarLegend'])) ? count((array)json_decode($calendar['calendarLegend'])) : 0;

        // $calendarData       = (array)json_decode($calendar['calendarData']);
        $calendarData       = json_decode($calendar['calendarData'],true);

        $availabilityYears  = 0;

        // sum of months in the array
        $availabilityMonths     = 0;

        // sum of days in the array
        $availabilityDays       = 0;

        // sum of all notes
        $notesTotal             = 0;

        $downloadableArray      = array();
        

        // Loop trought years
        foreach($calendarData as $year => $months)
        {
            $availabilityYears = $availabilityYears + count( $year );

            if($months)
            {
                // Loop trought months
                foreach($months as $month => $days)
                {
                    $availabilityMonths = $availabilityMonths + count( $month );

                    if($days)
                    {
                        // Loop trought days
                        foreach($days as $day => $status)
                        {
                            if (strpos($day,'description') !== false)
                            {
                                // unsetting the note so we can count the days
                                unset( $calendarData[$year][$month][$day] );
                                // Incrementing notes
                                $notesTotal++;
                            }
                            else
                                $availabilityDays = $availabilityDays + count( $day );
                        }
                    }
                }
            }
        }

        // sum of all availablity
        $availabilityTotal      = $availabilityYears + $availabilityMonths + $availabilityDays;
        
        ?>

        <div class="separator-top-bottom toolbar">
            <small>Export generates 2 files <strong>calendarData.csv</strong> and <strong>bookings.csv</strong></small>
            <span class="flex"></span>
            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar&id=' . $calendarID );?>" class="button secondary-button button-h2 button-h2-back-margin">Back</a>
            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=download-calendar&id=' . $calendarID . '&download=true&noheader=true' );?>" class="button button-primary button-h2">Download</a>
        </div>

        <table>
            <tbody>
                <tr>
                    <td>
                        <span><?php echo __( 'Bookings','wpbs' ); ?> <span class="totals"><?php echo $bookings['total'] ? $bookings['total'] : 0; ?></span></span>
                        <span>
                            <?php echo __( 'There will be exported all Booking types','wpbs' ); ?>
                        </span>
                    </td>
                    <td>
                        <div class="card-row">
                            <div class="mini-card">
                                <span><?php echo __( 'Pending','wpbs' ); ?></span>
                                <span><?php echo $bookings['pendingTotal'] ? $bookings['pendingTotal'] : 0; ?></span>
                            </div>

                            <div class="mini-card">
                                <span><?php echo __( 'Accepted','wpbs' ); ?></span>
                                <span><?php echo $bookings['acceptedTotal'] ? $bookings['acceptedTotal'] : 0; ?></span>
                            </div>

                            <div class="mini-card">
                                <span><?php echo __( 'Trash','wpbs' ); ?></span>
                                <span><?php echo $bookings['trashTotal'] ? $bookings['trashTotal'] : 0; ?></span>
                            </div>
                        </div>
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo __( 'Availability','wpbs'); ?> <span class="totals"><?php echo $availabilityTotal; ?></span></span>
                        <span><?php echo __( 'Export of all the availabilities','wpbs'); ?></span>
                    </td>
                    <td>
                        <div class="card-row">
                            <div class="mini-card">
                                <span><?php echo __( 'Year(s)','wpbs'); ?></span>
                                <span><?php echo $availabilityYears; ?></span>
                            </div>

                            <div class="mini-card">
                                <span><?php echo __( 'Month(s)','wpbs'); ?></span>
                                <span><?php echo $availabilityMonths; ?></span>
                            </div>

                            <div class="mini-card">
                                <span><?php echo __( 'Day(s)','wpbs'); ?></span>
                                <span><?php echo $availabilityDays; ?></span>
                            </div>


                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo __( 'Notes','wpbs'); ?> <span class="totals"><?php echo $notesTotal; ?></span></span>
                        <span><?php echo __( 'Separate export of calendar notes','wpbs'); ?></span>
                    </td>
                    <td><?php echo __( 'Notes are calendar availability descriptions','wpbs'); ?></td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo __( 'Legend','wpbs'); ?> <span class="totals"><?php echo $legendTotal; ?></span></span>
                        <span><?php echo __( 'Exporting the legend object','wpbs'); ?></span>
                    </td>
                    <td><?php echo __( 'The legend object contains','wpbs'); ?> </td>
                </tr>
            </tbody>
        </table>

        <div class="separator-top-bottom toolbar">
            <small>Export generates 2 files <strong>calendarData.csv</strong> and <strong>bookings.csv</strong></small>
            <span class="flex"></span>
            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar&id=' . $calendarID );?>" class="button secondary-button button-h2 button-h2-back-margin">Back</a>
            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=download-calendar&id=' . $calendarID . '&download=true&noheader=true' );?>" class="button button-primary button-h2">Download</a>
        </div>

        
        
        <?php else:?>
            <?php echo __('No calendars found.','wpbs')?> <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar');?>"><?php echo __("Click here to create your first calendar.",'wpbs') ;?></a>
        <?php endif;?>
        
    </div>
</div>