<?php
if ( !ABSPATH )
    return false;

$progress = get_option('wpbs-update-progress');

$progress = number_format( $progress, 0 );
echo json_encode(array('progress'=>$progress));
die;