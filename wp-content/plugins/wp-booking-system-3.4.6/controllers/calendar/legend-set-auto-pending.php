<?php
global $wpdb;

$calendarId = $_GET['id'];
$legendId = $_GET['legendID'];


$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$calendarId);
$calendar = $wpdb->get_row( $sql, ARRAY_A );
if($wpdb->num_rows > 0):
    $calendarLegend = json_decode($calendar['calendarLegend'],true);
    
    foreach($calendarLegend as $ID => $value){
        if($ID == $legendId){
        $calendarLegend[$ID]['auto-pending'] = 'yes';
        } else {
            $calendarLegend[$ID]['auto-pending'] = 'no';
        }  
    }    

    
    $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarLegend' => json_encode($calendarLegend)), array('calendarID' => $calendarId));     
    
endif;

wp_redirect(admin_url('admin.php?page=wp-booking-system&do=edit-legend&id='.$_GET['id']));
die();
?>     