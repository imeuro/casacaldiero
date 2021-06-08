<?php
global $wpdb;
?>
<div class="wrap wpbs-wrap" id="wpbs-export">
    



    <h1>Export / Import</h1>

    <?php
    if ( isset( $_GET['result'] ) )
        $result     = $_GET['result'];

    if ( isset( $_GET['code'] ) )
        $code     = $_GET['code'];
    
    if(!empty($result) && $result):
    ?>
    <div id="message" class="<?php if ( $code == 200 ): ?>updated<?php else: ?>error<?php endif; ?>">
        <p><?php echo __($result,'wpbs')?></p>
    </div>
    <?php endif;?>
    


    
    
    
    <div class="postbox-container wpbs-exporter">
        <div class="metabox-holder">
            <div class="postbox">
                <div class="handlediv" title="Click to toggle" aria-expanded="true"></div>
                <h3 class="hndle ui-sortable-handle"><?php _e('Export ' . WPBS_NAME, 'wpbs'); ?></h3>

                <div class="inside">
                    <p><?php _e('To <strong>export</strong> all data of <strong>' . WPBS_NAME . '</strong>, please click the <strong>Export</strong> button.', 'wpbs');?></p>
                    <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=download-export&download=true&noheader=true' );?>" class="button button-primary"><?php _e('Export', 'wpbs'); ?></a>



                    <?php
                    if ( $code == 401 )
                    {
                        ?>
                        <div class="wpbs-manual-toggle">
                            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>../../images/chevron-down.png" width="20" height="20" alt="">
                            open manual export panel
                            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>../../images/chevron-down.png" width="20" height="20" alt="">
                        </div>
                        <div class="wpbs-manual-export">
                            
                            <h3><?php _e('Sorry, but I can\'t write the export file.'); ?></h3>
                            <strong><?php _e('You can create the <strong>export.wpbs</strong> manually.'); ?></strong>
                            <ol>
                               <li><?php _e('Copy the text from the text field below'); ?></li>
                               <li><?php _e('Open any text editor (like <strong>Notepad</strong>) and paste the copied text'); ?></li>
                               <li><?php _e('Save your file as <strong>export.wpbs</strong> (the file extension needs to be <strong>.wpbs</strong>!)'); ?></li>
                            </ol>

                            <?php
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
                            $export             = json_encode( $export );
                            ?>
                            <textarea name="exported-data" id="exported-data" cols="30" rows="20" style="width: 100%;"><?php echo $export; ?></textarea>

                            <script>
                                // After 5s remove all the .error alerts from the page
                                setTimeout(function () {
                                    jQuery.each( jQuery('div.error'), function (idx, elem) {
                                        jQuery(this).fadeOut(750, function () {
                                            jQuery(this).remove();
                                        });
                                    });
                                }, 5000);
                                
                                // Just to be select by default
                                jQuery('#exported-data').select();

                                // If user click outside then the content will be unselected,
                                // he will need to click inside the textarea to re-select the
                                // content
                                jQuery('#exported-data').on('click', function (e) {
                                    e.preventDefault();
                                    jQuery('#exported-data').select();
                                });
                            </script>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>



















        

        <div class="metabox-holder wpbs-importer">
            <div class="postbox">
                <div class="handlediv" title="Click to toggle" aria-expanded="true"></div>
                <h3 class="import hndle ui-sortable-handle"><?php _e('Import ' . WPBS_NAME, 'wpbs'); ?> </h3>

                <div class="inside">
                    <p><?php _e('To <strong>import</strong> all data from a <strong>' . WPBS_NAME . '</strong> export file, browse for that file and click the <strong>Import</strong> button.', 'wpbs'); ?></p>
                    
                    <form action="" method="post" id="wpbs-import-form" enctype="multipart/form-data">
                        <input type="file" name="file"><br /><br />
                        <button id="wpbs-import-button" class="wpbs-import-btn button button-primary"><?php _e('Import', 'wpbs'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery('#wpbs-import-button').on('click', function (e) {
    e.preventDefault();
    var file = jQuery('input[type=file]')[0];
    var importHandle = jQuery('.import.hndle');
    
    if ( typeof file.files[0] === "undefined" )
    {
        importHandle.addClass('imported-error').html('<strong>You must select the file to process the import from!</strong>');
        return false;
    }
    else
    {

        jQuery('.wpbs-exporter .inside .wpbs-manual-export').addClass('close');
        jQuery('.wpbs-exporter .inside .wpbs-manual-toggle').addClass('open');

        importHandle
            .addClass('importing')
            .removeClass('imported-error')
            .removeClass('imported-success')
            .html('<div class="import-loading"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>../../images/hourglass.gif" width="24" height="24" /> &nbsp; <strong>Importing ...</strong></div> ');
        

        var form        = jQuery("#wpbs-import-form");

        var formData    = new FormData();
        formData.append('file', file.files[0]);

        var timeout = null;

        jQuery.ajax({
            url: '<?php echo admin_url( 'admin.php?page=wp-booking-system-export&do=import&noheader=true' ); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            type: 'POST',
            success: function ( obj )
            {
                data = obj;
            }
        }).done(function (data, text, xhr) {
            if ( !data.error )
            {
                importHandle
                    .removeClass('importing')
                    .addClass('imported-success')
                    .html('<strong>' + data.message + '</strong>');
            }
            else
            {
                importHandle
                    .removeClass('importing')
                    .addClass('imported-error')
                    .html('<strong>' + data.message + '</strong>');   
            }

            // timeout = null;
            clearInterval(timeout);
        });

        importHandle.find('.import-loading').append('<span class="flex"></span> <div class="progress-wrapper"><div class="progress-bar"><div class="progress-bar-bar"></div></div><span class="progress">0%</span></div>')
        timeout = setInterval( function () {
            jQuery.ajax({
                url: '<?php echo admin_url( 'admin.php?page=wp-booking-system-export&do=progress&noheader=true' ); ?>',
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                type: 'POST',
                success: function ( obj )
                {
                    var bgColor = '#C8E6C9';

                    if ( ( obj.progress >= 5 ) && ( obj.progress <= 40 ) )
                        bgColor = '#A5D6A7';
                    else if ( ( obj.progress >= 40 ) && ( obj.progress <= 75 ) )
                        bgColor = '#81C784';
                    else if ( ( obj.progress >= 75 ) && ( obj.progress <= 95 ) )
                        bgColor = '#66BB6A';
                    else if ( ( obj.progress >= 95 ) && ( obj.progress <= 100 ) )
                        bgColor = '#4CAF50';

                    importHandle.find('div.progress-bar-bar').css( 'width', obj.progress + '%' ).css('background-color', bgColor);
                    importHandle.find('span.progress').text( obj.progress + '%' );
                }
            });
        }, 1000);



    
    } 

});

</script>