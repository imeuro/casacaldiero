<?php
/**
 * This function prepares the calendar
 */
function wpbs_calendar($options = array()){
    
    $default_options = array('ajaxCall' => false, 'monthToShow' => null, 'yearToShow' => null, 'currentCalendar' => 1, 'totalCalendars' => 1 , 'firstDayOfWeek' => 1, 'showDropdown' => 'yes', 'calendarLanguage' => 'en', 'calendarData' => null, 'currentTimestamp' => mktime(0, 0, 0, date('n') , 15, date('Y')),'calendarLegend' => false, 'calendarID' => false, 'formID' => false, 'showDateEditor' => false, 'showLegend' => false, 'calendarSelection' => '', 'calendarSelectionType' => 'multiple', 'showTooltip' => 1, 'calendarHistory' => 1, 'autoPending' => 'no', 'weekNumbers' => 'no', 'formID' => '', 'minDays' => 0, 'maxDays' => 0, 'jump' => 'no', 'formPosition' => 'below');
   
    
    foreach($default_options as $key => $value)
    {
        if(empty($$key))
            $$key = $value;
    }
    extract($options);  
    
    
    $output = '';
    if($ajaxCall == false):    
        $formPositionClass = ($formPosition == 'side') ? 'two-columns' : 'one-column';   
        $output .= '<div class="wpbs-container wpbs-calendar-'.$calendarID.' wpbs-form-'.$formID.' '.$formPositionClass.' ">';
        $output .= '<div class="wpbs-calendars">';
    endif;
    
    if($showDateEditor == true){
        
        $output .= "<div class='wpbs-calendar-backend-wrap'>";
    }
    
    
    /**
     * Getting and populating booked weeks array if selectionType = "week"
     * 
     * @since  3.5
     */
    if ( $calendarSelectionType == "week" )
    {
        global $freeWeeks;

        $weeksCalendarData  = json_decode( $calendarData, true );
        $freeWeeks          = array( );
        $weeksStart         = mktime( 0, 0, 0, date('n',$currentTimestamp) -1, 1, date('Y',$currentTimestamp));
        $weeksEnd           = mktime( 0, 0, 0, date('n',$currentTimestamp) + $totalCalendars + 1, 1, date('Y',$currentTimestamp));

        for ( $i = $weeksStart; $i <= $weeksEnd; $i = $i + (60*60*24) )
        {
            if ( $weeksCalendarData[ date( 'Y', $i ) ][ date( 'n', $i ) ][ date( 'j', $i ) ] )
                $freeWeeks[date('W', $i)] = 'booked';
        }
    }

    for ( $i = 0; $i < $totalCalendars; $i++ )
    {
        $calendarTimestamp  = mktime(0, 0, 0, date('n',$currentTimestamp) + $i, 1, date('Y',$currentTimestamp));    
        $displayMonth       = date('n', $calendarTimestamp);
        $displayYear        = date('Y', $calendarTimestamp);

        $output             .= showCalendar(
            array(
                'monthToShow'           => $displayMonth, 
                'yearToShow'            => $displayYear, 
                'currentCalendar'       => $i + 1, 
                'totalCalendars'        => $totalCalendars , 
                'firstDayOfWeek'        => ($firstDayOfWeek == 7) ? 0 : $firstDayOfWeek, 
                'calendarLanguage'      => ($showDateEditor) ? wpbs_get_admin_language() : $calendarLanguage , 
                'showDropdown'          => $showDropdown, 
                'calendarData'          => $calendarData, 
                'calendarID'            => $calendarID, 
                'calendarLegend'        => $calendarLegend, 
                'calendarSelection'     => $calendarSelection, 
                'calendarSelectionType' => $calendarSelectionType, 
                'calendarHistory'       => $calendarHistory, 
                'showTooltip'           => $showTooltip, 
                'weekNumbers'           => $weekNumbers, 
                'minDays'               => $minDays, 
                'maxDays'               => $maxDays, 
                'jump'                  => $jump, 
                'formID'                => $formID
            )
        );
    }
    
    if ( $showDateEditor == true )
    {
        $output .= wpbs_edit_legend( $calendarLegend, true, $calendarID );
        $output .= wpbs_batch_update( $calendarLegend );
        $output .= wpbs_edit_users( $calendarID );
        
    }
    
    if ( $showDateEditor == false && $showTooltip == 1 )
    {
        $calendarData = json_decode($calendarData,true);
        
        foreach ( $calendarData as $year => $months )
        {
            if ( $months )
            {
                foreach ( $months as $month => $days )
                {
                    if ( $days )
                    {
                        foreach ( $days as $day => $status )
                        {
                            if ( strpos( $day, 'description' ) !== false )
                            {
                                unset( $calendarData[$year][$month][$day] );
                            }
                        }
                    }
                }
            }
        }
        $calendarData = json_encode($calendarData);
    }
    
    $output .= "<div class='wpbs-clear'></div>";
    
    $output .= '<div class="wpbs-calendar-options">';
    $output .= '    <div class="wpbs-show-dropdown">' . $showDropdown . '</div>'; 
    $output .= '    <div class="wpbs-current-timestamp">' . $currentTimestamp . '</div>'; 
    $output .= '    <div class="wpbs-total-calendars">' . $totalCalendars . '</div>';
    $output .= "    <div class='wpbs-calendar-data' data-info='".html_entity_decode( $calendarData )."'></div>";
    $output .= "    <div class='wpbs-calendar-legend' data-info='".html_entity_decode( $calendarLegend )."'></div>";
    $output .= '    <div class="wpbs-calendar-history">' . $calendarHistory . '</div>';
    $output .= '    <div class="wpbs-calendar-language">' . $calendarLanguage . '</div>';
    $output .= '    <div class="wpbs-calendar-tooltip">' . $showTooltip . '</div>';
    $output .= '    <div class="wpbs-calendar-week-start">' . $firstDayOfWeek . '</div>';
    $output .= '    <div class="wpbs-calendar-ID">' . $calendarID . '</div>';
    $output .= '    <div class="wpbs-calendar-form-ID">' . $formID . '</div>';
    $output .= '    <div class="wpbs-calendar-selection">'.$calendarSelection.'</div>';
    $output .= '    <div class="wpbs-calendar-selection-type">'.$calendarSelectionType.'</div>';
    $output .= '    <div class="wpbs-calendar-auto-pending">'.$autoPending.'</div>';
    $output .= '    <div class="wpbs-calendar-minumum-days">'.$minDays.'</div>';
    $output .= '    <div class="wpbs-calendar-maximum-days">'.$maxDays.'</div>';
    $output .= '    <div class="wpbs-calendar-jump">'.$jump.'</div>';
    $output .= '    <div class="wpbs-calendar-week-numbers">'.$weekNumbers.'</div>';
    $output .= '    <div class="wpbs-calendar-form-position">'.$formPosition.'</div>';
    $output .= '</div>';
    
    if ( $showDateEditor == true )
    {
        $output .= '</div>';
        $output .= wpbs_edit_dates(
            array(
                'calendarData'      => $calendarData, 
                'calendarLegend'    => $calendarLegend, 
                'currentTimestamp'  => $currentTimestamp, 
                'calendarLanguage'  => ($showDateEditor) ? wpbs_get_admin_language() : $calendarLanguage
            )
        );
    }
    
    
    if ( $ajaxCall == false )
    {   
        $output .= '</div><div class="wpbs-form-and-legend">';
        
        if ( $showLegend == 'yes' )
        {
            $output .= '<div class="wpbs-legend">';
                $output .= wpbs_print_legend($calendarLegend,$calendarLanguage);  
            $output .= '<div class="wpbs-clear"><!-- --></div></div>';   
        }

        if ( $showDateEditor == false && $formID != 'no-form' )
        {
            $output .= '<div class="wpbs-form"><form class="wpbs-form-form" data-id="'.$formID.'" data-calendar-id="'.$calendarID.'">';
                $output .= wpbs_display_form($formID,$calendarLanguage,false,$calendarID, $autoPending, $minDays, $maxDays);
            $output .= '<div class="wpbs-clear"><!-- --></div></form></div>';
        }
        
        $output .= '</div></div><div class="wpbs-clear"></div>';
        
    }
    return $output;
}
/**
 * This function is displays the calendar with the parameters given from the previous function
 */
function showCalendar($options = array())
{   
    
    
    foreach($options as $key => $value)
    {
        $$key = $value;
    }



    
    $calendarData = json_decode($calendarData,true);
    
    if ( ( $monthToShow === null ) || ( $yearToShow === null ) )
    {
        $today          = getdate();
        $monthToShow    = $today['mon'];
        $yearToShow     = $today['year'];
    }
    else
    {
        $today          = getdate(mktime(0, 0, 0, $monthToShow, 1, $yearToShow));
    }
    
    $calendarSelection  = explode('-',$calendarSelection);
    $selectionStart     = (!empty($calendarSelection[0])) ?  $calendarSelection[0] : 0;
    $selectionEnd       = (!empty($calendarSelection[1])) ?  $calendarSelection[1] : 0;
    $goingBackwards     = false;

    if ($selectionStart != 0 && $selectionEnd !=0 && $selectionStart > $selectionEnd)
    {
        $temp           = $selectionStart;
        $selectionStart = $selectionEnd;
        $selectionEnd   = $temp;
        $goingBackwards = true;
    }

    
    
    $notBookable = false;
    if ($formID == 'no-form')
    {
        $notBookable = true;
    }

    
    // get first and last days of the month
    $firstDay   = getdate( mktime( 0, 0, 0, $monthToShow, 1, $yearToShow ) );
    $lastDay    = getdate( mktime( 0, 0, 0, $monthToShow + 1, 0, $yearToShow ) ); //trick! day = 0


    /**
     * Get all the booked days in current month
     * 
     * @var array
     */
    $disabledDays   = array();

    $d = new DateTime(date('Y-m-d', mktime(0, 0, 0, $monthToShow + 1, 0, $yearToShow)));
    $lDay           = $d->format('d');
    $weeks          = array();

    for ($x = 1; $x <= $lDay; $x++)
    {
        $booked     = wpbs_check_if_booked( $calendarData, $calendarLegend, $yearToShow, $monthToShow, $x );

        $weekOptions= array(
            'firstDayOfWeek'    => $firstDayOfWeek,
            'yearToShow'        => $yearToShow,
            'monthToShow'       => $monthToShow,
            'dayToShow'         => $x
        );

        if ( $booked )
            $disabledDays[$yearToShow . '-' . wpbs_leadingZero( $monthToShow ) . '-' . wpbs_leadingZero( $x ) ] = true;
    }

    
    // Create a table with the necessary header information
    $output = '<div class="wpbs-calendar';
    
    if ( $weekNumbers == 'yes' )
    {
        $output .= ' wpbs-week-numbers';
    }        

    $output .= '">';
    $output .= '<div class="wpbs-heading">';

    if ( $currentCalendar == 1 )
    {
        $output .= '<a href="#" class="wpbs-prev" title="'.__('Previous Month','wpbs').'"></a>';

        if($showDropdown == 'yes')
        {
            $output .= '<div class="wpbs-select-container"><select class="wpbs-dropdown">';

            for( $d = 0; $d < 12; $d++ )
            {
                $output .= '<option value="' . mktime(0, 0, 0, $monthToShow + $d, 15, $yearToShow) . '">' . wpbsMonth(date('F',mktime(0, 0, 0, $monthToShow + $d, 15, $yearToShow)), $calendarLanguage) . " " . date('Y',mktime(0, 0, 0, $monthToShow + $d, 15, $yearToShow)) . '</option>';
            }

            $output .= '</select></div>';
        } 
        else 
        {
            $output .=  "<span>" . wpbsMonth( $today['month'], $calendarLanguage) . " " . $today['year'] . "</span>";    
        }
    } 
    else 
    {
        $output .= "<span>" . wpbsMonth( $today['month'], $calendarLanguage) . " " . $today['year'] . "</span>";    
    }        
    
    if ( $currentCalendar == $totalCalendars )
        $output .= '<a href="#" class="wpbs-next" title="'.__('Next Month','wpbs').'"></a>';
    
    $output .= "</div>";

    $output .= '<ul class="wpbs-weekdays">';

    if( $weekNumbers == 'yes' )
    {
        $output .= '<li class="wpbs-week-number"></li>';
    }
    
    $dayText = wpbsDoW($calendarLanguage);

    for ( $i = 0; $i < 7; $i++ ) 
    { // put 7 days in header, starting at appropriate day ($firstDayOfWeek)
        $output .= '<li>' . $dayText[$firstDayOfWeek + $i] . '</li>';
    }

    $output .= '</ul>';
    $actday = 0; // used to count and represent each day
    $output .= '<ul>';

    if( $weekNumbers == 'yes' )
    {
        $output .= '<li class="wpbs-week-number" title="'.__('Week').' '.date('W',mktime(0,0,0,$monthToShow,$actday+1,$yearToShow)).'">'.date('W',mktime(0,0,0,$monthToShow,1,$yearToShow)).'</li>';
    }

    // Display the first calendar row with correct start of week
    if ( $firstDayOfWeek <= $firstDay['wday'] ) 
    {
        $blanks = $firstDay['wday'] - $firstDayOfWeek;
    } 
    else 
    {
        $blanks = $firstDay['wday'] - $firstDayOfWeek + 7;
    }

    for ( $i = 1; $i <= $blanks; $i++ ) {
        $output .= '<li class="wpbs-pad"><!-- --></li>';
    }
    
    // Note: loop below starts using the residual value of $i from loop above
    for ( /* use value of $i resulting from last loop*/; $i <= 7; $i++) 
    {
        $output .= wpbs_show_day(
            array(
                'calendarData'              => $calendarData,
                'yearToShow'                => $yearToShow,
                'monthToShow'               => $monthToShow,
                'actday'                    => $actday++,
                'calendarHistory'           => $calendarHistory,
                'calendarLegend'            => $calendarLegend,
                'disabledDays'              => $disabledDays,
                'calendarSelectionType'     => $calendarSelectionType,
                'selectionStart'            => $selectionStart,
                'selectionEnd'              => $selectionEnd,
                'showTooltip'               => $showTooltip
            )
        );
        
    }
    

    $output .= '</ul>';


    // Get how many complete weeks are in the actual month
    $fullWeeks = floor(($lastDay['mday'] - $actday) / 7);



    for ( $i = 0; $i < $fullWeeks; $i++ ) 
    {
        $output .= '<ul>';
        
        if ( $weekNumbers == 'yes' )
        {
            $date   = new DateTime( $yearToShow . '-' . $monthToShow . '-' . ($actday+1) );
            $a      = $date->format("W");
            $output .= '<li class="wpbs-week-number" title="'.__('Week').' '. $a .'">'.$a.'</li>';
        }
        
        for ( $j = 0; $j < 7; $j++ ) 
        {
            $output .= wpbs_show_day(
                array(
                    'calendarData'              => $calendarData,
                    'yearToShow'                => $yearToShow,
                    'monthToShow'               => $monthToShow,
                    'actday'                    => $actday++,
                    'calendarHistory'           => $calendarHistory,
                    'calendarLegend'            => $calendarLegend,
                    'disabledDays'              => $disabledDays,
                    'calendarSelectionType'     => $calendarSelectionType,
                    'selectionStart'            => $selectionStart,
                    'selectionEnd'              => $selectionEnd,
                    'showTooltip'               => $showTooltip
                )
            );
        }
        $output .= '</ul>';
    }

    //Now display the partial last week of the month (if there is one)
    if ($actday < $lastDay['mday']) 
    {
        $output .= '<ul>';
        $actday++;
        if($weekNumbers == 'yes')
        {
            $output .= '<li class="wpbs-week-number" title="'.__('Week').' '.date('W',mktime(0,0,0,$monthToShow,$actday+1,$yearToShow)).'">'.date('W',mktime(0,0,0,$monthToShow,$actday+1,$yearToShow)).'</li>';
        }
        for ($i = 0; $i < 7; $i++) 
        {
            if ($actday <= $lastDay['mday']) 
            {
                $output .= wpbs_show_day(
                    array(
                        'calendarData'              => $calendarData,
                        'yearToShow'                => $yearToShow,
                        'monthToShow'               => $monthToShow,
                        'actday'                    => $actday++,
                        'calendarHistory'           => $calendarHistory,
                        'calendarLegend'            => $calendarLegend,
                        'disabledDays'              => $disabledDays,
                        'calendarSelectionType'     => $calendarSelectionType,
                        'selectionStart'            => $selectionStart,
                        'selectionEnd'              => $selectionEnd,
                        'showTooltip'               => $showTooltip
                    ),
                    false
                );
                
            } else {
                $output .= '<li class="wpbs-pad"><!-- --></li>';
            }
        }
        $output .= '</ul>';
    }
    $output .= '<div class="wpbs-loading"><img src="'.WPBS_PATH.'/images/ajax-loader.gif" alt="loading..." /></div></div>';
    return $output;
}