<?php
function wpbs_replaceCustom($str){
    return stripslashes(str_replace( 
        array(
            '--AMP--',
            '--DOUBLEQUOTE--',
            '--QUOTE--',
            '--LT--',
            '--GT--'
        ),
        array(
            '&',
            '"',
            "'",
            '<',
            '>'
        ),
        $str ));
}

function wpbs_print_legend_css($legend, $calendarID = null, $hoverCSS = true)
{
    $output = "<style>";
    foreach(json_decode($legend,true) as $key => $value )
    {
        if( !empty( $value["splitColor"] ) )
        {
            $output .= ".wpbs-calendar-".$calendarID." .wpbs-day-split-top-" . $key . " {border-color: " . $value['color'] ." transparent transparent transparent; _border-color: " . $value['color'] ." #000000 #000000 #000000;}";
            $output .= ".wpbs-calendar-".$calendarID." .wpbs-day-split-bottom-" . $key . " {border-color: transparent transparent " . $value['splitColor'] ." transparent; _border-color:  #000000 #000000 " . $value['splitColor'] ." #000000;}";
        }
        else
        {
            $output .= ".wpbs-calendar-".$calendarID." .status-" . $key . " {background-color: " . $value['color'] ."}";
            $output .= ".wpbs-calendar-".$calendarID." .wpbs-day-split-top-" . $key . " {display:none;} ";
            $output .= ".wpbs-calendar-".$calendarID." .wpbs-day-split-bottom-" . $key . " {display:none;} ";
        }
        /**
         * @since  3.5
         */
        $output .= '.wpbs-disabled { opacity: .6 !important; cursor: default !important; }';
        $output .= '.status-' . $key . '.wpbs-disabled:hover { background-color: '.$value["color"].' !important; opacity: .6 !important; border-color: transparent !important }';

        $output .= '.status-' . $key . '.wpbs-disabled:hover span { color: #000000 !important; cursor: normal !important; }';


        // $output .= '.status-' . $key . '.wpbs-selected:hover { background-color: '.$wpbsOptions['selectedColor'].' !important; opacity: .6 !important; border-color: transparent !important }';

        // $output .= '.status-' . $key . '.wpbs-selected:hover span { color: #000000 !important; cursor: normal !important; }';
    }
    
    $wpbsOptions = json_decode( get_option('wpbs-options'), true );
    /**
     * @since  3.5
     */
    $output .= 'li.wpbs-selected {height:28px !important; width:28px !important; line-height:28px !important; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; border: 1px solid '.$wpbsOptions['selectedBorder'].' !important; background:'.$wpbsOptions['selectedColor'].' !important; cursor:pointer; color:#fff !important; }';

    // $output .= '.status-' . $key . '.wpbs-selected:hover { background-color: '.$wpbsOptions['selectedColor'].' !important; }';

    $output .= '.wpbs-selected span { color: #fff !important; }';
    
    $output .= ".status-wpbs-grey-out-history {background-color:".$wpbsOptions['historyColor']."}";
    $output .= ".status-wpbs-grey-out-history .wpbs-day-split-top, .status-wpbs-grey-out-history .wpbs-day-split-bottom {display:none;}";
    

    if($hoverCSS == true):
    
        $output .= "li.wpbs-bookable:hover, li.wpbs-bookable-clicked, li.wpbs-bookable-clicked:hover, li.wpbs-bookable-hover, li.wpbs-bookable-hover:hover {height:28px !important; width:28px !important; border: 1px solid ".$wpbsOptions['selectedBorder']." !important; background:".$wpbsOptions['selectedColor']." !important; cursor:pointer; line-height:28px !important; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; color:#fff !important; }";
        $output .= "li.wpbs-bookable:hover span {color:#fff !important;}";
        $output .= "li.wpbs-bookable-hover span.wpbs-day-split-bottom, li.wpbs-bookable-hover span.wpbs-day-split-top, li.wpbs-bookable-hover:hover span.wpbs-day-split-bottom, li.wpbs-bookable-hover:hover span.wpbs-day-split-top, li.wpbs-bookable:hover span.wpbs-day-split-top, li.wpbs-bookable:hover span.wpbs-day-split-bottom {display:none !important;}";
        $output .= "li.wpbs-bookable-clicked span, li.wpbs-bookable-clicked:hover span, li.wpbs-bookable-hover span, li.wpbs-bookable-hover:hover span {color:#ffffff !important;  }";
        $output .= "li.wpbs-bookable-clicked .wpbs-day-split-top, li.wpbs-bookable-clicked:hover .wpbs-day-split-top, li.wpbs-bookable-hover .wpbs-day-split-top, li.wpbs-bookable-hover:hover .wpbs-day-split-top {border-color:".$wpbsOptions['selectedColor']." !important;}";
        $output .= "li.wpbs-bookable-clicked .wpbs-day-split-bottom, li.wpbs-bookable-clicked:hover .wpbs-day-split-bottom, li.wpbs-bookable-hover .wpbs-day-split-bottom, li.wpbs-bookable-hover:hover .wpbs-day-split-bottom {border-color:".$wpbsOptions['selectedColor']." !important;}";
    endif;
    $output .= "</style>";
    
    return $output;
}

function wpbs_timeFormat($timestamp){
    $wpbsOptions = json_decode(get_option('wpbs-options'),true);
    $date = date($wpbsOptions['dateFormat'], $timestamp);
    if(strstr($wpbsOptions['dateFormat'], 'F')) {
        $month = date('F', $timestamp);
        $date = str_replace($month, __($month), $date);
    }
    return $date;
}

function wpbs_defaultCalendarLegend(){
    return get_option('wpbs-default-legend');
}

function wpbs_print_legend($legend,$language,$hideLegend = true){
    $output = '';
    foreach(json_decode($legend,true) as $key => $value ):
        if(!(!empty($value['hide']) && $value['hide'] == 'hide') || $hideLegend == false){
           if(!empty($value['name'][$language])) $legendName = $value['name'][$language]; else $legendName = $value['name']['default'];
            $output .= '<div class="wpbs-legend-item"><div class="wpbs-legend-color status-' . $key . '">
                    <div class="wpbs-day-split-top wpbs-day-split-top-'.$key.'"></div>
                    <div class="wpbs-day-split-bottom wpbs-day-split-bottom-'.$key.'"></div>    
                </div><p>' . $legendName . '</p></div>'; 
        }
        
    endforeach;
    return $output;
}
function wpbs_get_admin_language(){
    $activeLanguages = json_decode(get_option('wpbs-languages'),true);
    if(array_key_exists(substr(get_bloginfo('language'),0,2),$activeLanguages)){
        return substr(get_bloginfo('language'),0,2);    
    }
    return 'en';
    
}

function wpbs_get_locale(){
    return substr(get_locale(),0,2);
}

function wpbs_check_if_bookable($legend,$calendarLegend,$y,$m,$d){
    $calendarLegend = json_decode($calendarLegend,true);
    if(!empty($calendarLegend[$legend]['bookable']) && $calendarLegend[$legend]['bookable'] == 'yes' && wpbs_days_passed($y,$m,$d))
        return "wpbs-bookable";
    return false;
}

/**
 * @since  3.5
 * @param  [type] $calendarLegend [description]
 * @return boolean              true if booked or false otherwise
 */
function wpbs_check_if_booked($calendarData, $calendarLegend, $yearToShow, $monthToShow, $actday )
{
    if(!empty($calendarData[$yearToShow][$monthToShow][$actday]))
        $status = $calendarData[$yearToShow][$monthToShow][$actday];
    else 
        $status = 'default';

    $booked = wpbs_check_if_bookable($status, $calendarLegend, $yearToShow, $monthToShow, $actday);

    // echo '<pre>';
    // var_dump($actday . " - " . $booked);
    // echo '</pre>';

    if ( empty($booked) )
        return true;

    return false;
}

function wpbs_days_passed($y,$m,$d){
    $day = (mktime(0,0,0,$m,$d,$y) - mktime(0,0,0,date('n'),date('j'),date('y'))) / 60 / 60 / 24;
    if($day >= 0)
        return $day + 1;
    return false;
}

function wpbs_check_range($int,$min,$max){
    return ($int > $min && $int < $max);
}


function wpbs_html_cut($text, $max_length)
{
    $tags   = array();
    $result = "";

    $is_open   = false;
    $grab_open = false;
    $is_close  = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);

    while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
    {
        $symbol  = $text{$i};
        $result .= $symbol;

        switch ($symbol)
        {
           case '<':
                $is_open   = true;
                $grab_open = true;
                break;

           case '"':
               if ($in_double_quotes)
                   $in_double_quotes = false;
               else
                   $in_double_quotes = true;

            break;

            case "'":
              if ($in_single_quotes)
                  $in_single_quotes = false;
              else
                  $in_single_quotes = true;

            break;

            case '/':
                if ($is_open && !$in_double_quotes && !$in_single_quotes)
                {
                    $is_close  = true;
                    $is_open   = false;
                    $grab_open = false;
                }

                break;

            case ' ':
                if ($is_open)
                    $grab_open = false;
                else
                    $stripped++;

                break;

            case '>':
                if ($is_open)
                {
                    $is_open   = false;
                    $grab_open = false;
                    array_push($tags, $tag);
                    $tag = "";
                }
                else if ($is_close)
                {
                    $is_close = false;
                    array_pop($tags);
                    $tag = "";
                }

                break;

            default:
                if ($grab_open || $is_close)
                    $tag .= $symbol;

                if (!$is_open && !$is_close)
                    $stripped++;
        }

        $i++;
    }

    while ($tags)
        $result .= "</".array_pop($tags).">";

    return $result;
}
function wpbs_tz_offset_to_name($offset){
    $offset *= 3600;
    $abbrarray = timezone_abbreviations_list();
    foreach ($abbrarray as $abbr){
        foreach ($abbr as $city){
            if ($city['offset'] == $offset){
                    return $city['timezone_id'];
            }
        }
    }
    
    return false;
}

function wpbs_generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function captcha_verification() {
    $wpbsOptions = json_decode(get_option('wpbs-options'),true);
    $secret_key = $wpbsOptions['recaptcha_secret'];
    
	$response = isset( $_POST['g-recaptcha-response'] ) ? esc_attr( $_POST['g-recaptcha-response'] ) : '';

	$remote_ip = $_SERVER["REMOTE_ADDR"];

	$request = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response=' . $response . '&remoteip=' . $remote_ip);

	$response_body = wp_remote_retrieve_body( $request );

	$result = json_decode( $response_body, true );

	return $result['success'];
}

function wpbs_leadingZero($day)
{
    if ( !$day )
        return false;

    if ( strlen( $day ) == 1 )
        $day = '0' . $day;

    return $day;
}

/**
 * Check if week has bookings
 * 
 * Introduced for Selection Type = Week to disable week selection if 
 * day(s) are already booked.
 * 
 * @since  v3.5
 * 
 * @param  array $params An array containing all the needed variables
 */
function wpbs_show_day( $params, $increment = true )
{
    extract( $params );

    $output = '';


    $ac     = ( $increment ) ? $calendarData[$yearToShow][$monthToShow][++$actday] : $calendarData[$yearToShow][$monthToShow][$actday];

    if ( !empty( $ac ) )
        $status = $calendarData[$yearToShow][$monthToShow][$actday];
    else 
        $status = 'default';
        
    $dataOrder = ceil( wpbs_days_passed ( $yearToShow,$monthToShow,$actday ) );
    $dataTimestamp = mktime(0,0,0,$monthToShow,$actday,$yearToShow);    
        
    //handle past dates    
    if($dataTimestamp + (60*60*24) < time()  && $calendarHistory != 1)
    {
        if($calendarHistory == 2) $status = 'default'; //show default
        if($calendarHistory == 3) $status = 'wpbs-grey-out-history'; //grey-out
    }
    
    $selectedClass = ''; 
    if (wpbs_check_range($dataTimestamp, $selectionStart, $selectionEnd)) 
        $selectedClass = ( $calendarSelectionType == "week" ) ? 'wpbs-selected' : 'wpsb-bookable-hover';
    elseif($dataTimestamp == $selectionStart || $dataTimestamp == $selectionEnd) 
        $selectedClass = 'wpbs-bookable-clicked';
    
    if($actday == 1 && $currentCalendar == 1 && $dataTimestamp > $selectionStart && $selectionStart != 0 && $selectionEnd == 0 && $goingBackwards == false)
    {
        $selectedClass = 'wpbs-bookable-clicked';
    }

    if($dataTimestamp > $selectionStart && $selectionStart != 0 && $selectionEnd == 0)
    {
        for($c = $selectionStart; $c <= $dataTimestamp; $c = $c + 60*60*24)
        {
            if(!empty($calendarData[date('Y',$c)][date('n',$c)][date('j',$c)]))
                $searchStatus = $calendarData[date('Y',$c)][date('n',$c)][date('j',$c)];
            else 
                $searchStatus = 'default';

            if(wpbs_check_if_bookable($searchStatus,$calendarLegend,date('Y',$c),date('n',$c),date('j',$c)) != 'wpbs-bookable')
                $notBookable = true;
        }
    }

    
    
    $bookableClass = wpbs_check_if_bookable($status,$calendarLegend,$yearToShow,$monthToShow,$actday);

    if($notBookable == true)
    {
        $selectedClass = '';
        $bookableClass = 'wpbs-not-bookable';
    }

    if($selectionStart != 0 && $selectionStart < $dataTimestamp && $selectionStart > $dataTimestamp &&  $bookableClass != '' && $selectionEnd == 0 ) 
        $bookableClass = 'wpbs-not-bookable';
    
    
    $tooltip = false; 

    if( !empty( $calendarData[$yearToShow][$monthToShow]['description-' . $actday] ) && in_array( $showTooltip, array( 2,3 ) ) )
        $tooltip = ' data-tooltip="'.htmlentities(wpbs_replaceCustom($calendarData[$yearToShow][$monthToShow]['description-' . $actday])).'" data-tooltip-date="'.wpbs_timeFormat($dataTimestamp).'"';

    /**
     * @since   3.5
     * 
     * If selection = week disable week if set to tru
     */

    global $freeWeeks;

    $disabledClass = '';

    if ( $calendarSelectionType == "week" )
        $disabledClass  = ( array_key_exists( date( 'W', $dataTimestamp ), $freeWeeks ) ) ? 'wpbs-disabled' : '';

    
    
    $output .= '<li'.$tooltip.' data-timestamp="'.$dataTimestamp.'" data-order="'.$dataOrder.'" class="'.$bookableClass.'  wpbs-bookable-'.$dataOrder.' wpbs-day wpbs-day-'.$actday.' status-' . $status .  ' '.$selectedClass.' '. $disabledClass .' ">';


    
    if ($tooltip && $showTooltip == 3)
    {
        $output .= '<span class="wpbs-tooltip-corner"></span>';
    }
    
    $output .= '<span class="wpbs-day-split-top wpbs-day-split-top-'.$status.'"></span>';
    $output .= '<span class="wpbs-day-split-bottom wpbs-day-split-bottom-'.$status.'"></span>';    
    $output .= '<span class="wpbs-day-split-day">'.$actday.'</span></li>';

    return $output;
}