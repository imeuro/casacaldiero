var wpbs = jQuery.noConflict();
function showLoader($this){
    $this.find('.wpbs-loading').fadeTo(0,0).css('display','block').fadeTo(200,1);
    $this.find('.wpbs-calendar ul').animate({
        'opacity' : '0.7'
    },200);
}
function hideLoader(){
    wpbs('.wpbs-loading').css('display','none');
}
function wpbs_changeDay(direction, timestamp, $this){
    var data = {
		action: 'wpbs_changeDay',
        calendarDirection: direction,
		totalCalendars: $this.find(".wpbs-total-calendars").html(), 
        currentTimestamp: timestamp,
        calendarData: $this.find(".wpbs-calendar-data").attr('data-info'),
        calendarHistory: $this.find(".wpbs-calendar-history").html(),
        calendarLegend: $this.find(".wpbs-calendar-legend").attr('data-info'),
        showDropdown: $this.find(".wpbs-show-dropdown").html(),
        calendarLanguage: $this.find(".wpbs-calendar-language").html(),
        weekStart : $this.find(".wpbs-calendar-week-start").html(),
        showTooltip: $this.find(".wpbs-calendar-tooltip").html(),
        calendarSelection : $this.find(".wpbs-calendar-selection").html(),
        calendarSelectionType : $this.find(".wpbs-calendar-selection-type").html(),
        autoPending : $this.find(".wpbs-calendar-auto-pending").html(),
        weekNumbers : $this.find(".wpbs-calendar-week-numbers").html(),
        minDays : $this.find(".wpbs-calendar-minumum-days").html(),
        maxDays : $this.find(".wpbs-calendar-maximum-days").html(),
        jump : $this.find(".wpbs-calendar-jump").html(),
        calendarID : $this.find(".wpbs-calendar-ID").html(),
        formID : $this.find(".wpbs-calendar-form-ID").html(),
        formPosition : $this.find(".wpbs-calendar-form-position").html()
	};
	wpbs.post(ajaxurl, data, function(response) {
		$this.find('.wpbs-calendars').html(response);
        hideLoader();   
        // $this.find('.wpbs-dropdown').selectpicker('destroy');  
        // $this.find('.wpbs-dropdown').customSelect();  
	});
}

function wpbs_clear_selection(){
    startDate = endDate = false; 
    wpbs('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');
    wpbs('.wpbs-bookable-clicked').removeClass('wpbs-bookable-clicked');
    wpbs('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
    wpbs('.wpbs-selected').removeClass('wpbs-selected');
    wpbs('.wpbs-start-date, .wpbs-end-date').val('')
    wpbs('.wpbs-calendar-selection').html('');
}

var CaptchaCallback = function(){
    wpbs('.g-recaptcha').each(function(index, el) {
        grecaptcha.render(el, {'sitekey' : recaptcha_public});
    });
};


var startDate   = false,
    endDate     = false;


var checkselectedDays = function ()
{
    var periodStart, periodEnd = null,
        start_val = wpbs('.wpbs-form-form .wpbs-start-date').val(),
        end_val   = wpbs('.wpbs-form-form .wpbs-end-date').val();

    if ( !start_val )
        return false;

    if ( !end_val )
        return false;

    if ( start_val.length > 0 )
        periodStart = wpbs('[data-timestamp="' + parseInt(wpbs('.wpbs-form-form .wpbs-start-date').val() ) + '"]').data('order');
    if ( end_val.length > 0 )
        periodEnd = wpbs('[data-timestamp="' + parseInt(wpbs('.wpbs-form-form .wpbs-end-date').val() ) + '"]').data('order');

    for ( var i = periodStart; i <= periodEnd; i++ )
    {
        wpbs('[data-order=" ' + parseInt(i) + ' "]').addClass('wpbs-bookable-hover').removeClass('wpbs-bookable');
    }
}

wpbs(document).ready(function()
{
    // On load
    checkselectedDays();


    wpbs('div.wpbs-container').each(function()
    {
        
        var $instance           = wpbs(this);

        /* Calendar */
        
        wpbs($instance).on('change','.wpbs-dropdown',function(e)
        {
            showLoader($instance);     
            e.preventDefault();        
            wpbs_changeDay('jump',wpbs(this).val(), $instance);
        });
        
        wpbs($instance).on('click','.wpbs-prev',function(e)
        {
            showLoader($instance);
            e.preventDefault();

            if($instance.find(".wpbs-current-timestamp a").length == 0)
                timestamp = $instance.find(".wpbs-current-timestamp").html();
            else 
                timestamp = $instance.find(".wpbs-current-timestamp a").html();

            wpbs_changeDay('prev',timestamp, $instance);
        });
        
        
        wpbs($instance).on('click','.wpbs-next',function(e){  
            showLoader($instance);
            e.preventDefault();     
            if($instance.find(".wpbs-current-timestamp a").length == 0)
                timestamp = $instance.find(".wpbs-current-timestamp").html();
            else 
                timestamp = $instance.find(".wpbs-current-timestamp a").html()   
            wpbs_changeDay('next',timestamp, $instance);
        });
        
        /* Form */
        
        wpbs($instance).on('click','.wpbs-form-submit',function(e){  
            e.preventDefault(); 
            $instance.find('.wpbs-form-loading').fadeTo(0,0).css('display','block').fadeTo(200,1);
            $instance.find('.wpbs-form-item').animate({
                'opacity' : '0.7'
            },200);    
            var wpbsFormData = $instance.find('.wpbs-form-form').serialize();
            wpbsFormData = "action=submitForm&" + wpbsFormData;      
        	wpbs.post(ajaxurl, wpbsFormData, function(response) {
        	   
        		$instance.find(".wpbs-form-form").html(response);
                if($("#recaptcha-" + $instance.find('.wpbs-form-form').data('id') + '-' + $instance.find('.wpbs-form-form').data('calendar-id')).length > 0)
        	       grecaptcha.render("recaptcha-" + $instance.find('.wpbs-form-form').data('id') + '-' + $instance.find('.wpbs-form-form').data('calendar-id'),{'sitekey' : recaptcha_public});
        	});            
        });
        
        
        /* Booking - Multiple Days */
        if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'multiple')
        {
            wpbs($instance).on('click','.wpbs-bookable',function(e){
                
                e.preventDefault(); 
                $this = wpbs(this);
                
                if (startDate == false)
                {
                    wpbs_clear_selection();                
                    $this.addClass('wpbs-bookable-clicked');
                    $instance.find('.wpbs-start-date').val($this.attr('data-timestamp'));
                    startDate = true;
                    $instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-0");
                    //$instance.find('.wpbs-bookable').each(function(){
                    //    if(parseInt(wpbs(this).attr('data-order')) < parseInt($this.attr('data-order'))) wpbs(this).addClass('wpbs-not-bookable').removeClass('wpbs-bookable');
                    //})
                } else if(endDate == false){
                    $this.addClass('wpbs-bookable-clicked');
                    $instance.find('.wpbs-end-date').val($this.attr('data-timestamp'));
                    $instance.find('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');
                    
                    endDate = true;
                    startDate = false;
                    $instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-" + $instance.find('.wpbs-end-date').val());
                }
            });
            
            wpbs($instance).find(".wpbs-calendars").mouseleave(function(){
                if(wpbs($instance).find('.wpbs-bookable-clicked').length == 1 && endDate == false){
                    // wpbs($instance).find('.wpbs-bookable-clicked').trigger('click');
                    // $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
                }
            })
        }
        
        
        
        
        /* Booking - Single Days */
        if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'single')
        {
            wpbs($instance).on('click','.wpbs-bookable',function(e)
            {
                e.preventDefault(); 
                $this = wpbs(this);
                
                wpbs_clear_selection();                
                wpbs(this).addClass('wpbs-bookable-clicked');
                $instance.find('.wpbs-start-date').val($this.attr('data-timestamp'));
                $instance.find('.wpbs-end-date').val($this.attr('data-timestamp'));
                $instance.find('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');

                endDate = false;
                startDate = false;
                $instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-" + $instance.find('.wpbs-end-date').val());
               
            });
        }

        /*=============================================
        =       Booking fixed dates (7 days)          =
        =       current day + next 6 days             =
        =============================================*/
        
        if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'fixed')
        {
            wpbs($instance).on('click','.wpbs-bookable',function(e)
            {
                e.preventDefault(); 
                $this = wpbs(this);
                
                wpbs_clear_selection();

                startHover      = parseInt($this.data('order'));
                endHover        = startHover+6;

                selectable      = true;

                for(i = startHover; i <= endHover; i++)
                {
                    elem    = $instance.find('.wpbs-bookable-' + i);
                    
                    if ( elem.hasClass('status-1') )
                    {
                        alert('There is already booked date in this range!');
                        selectable      = false;
                        return false;
                    }


                }

                if ( selectable )
                {
                    wpbs(this).addClass('wpbs-bookable-clicked');

                    for(i = startHover; i <= endHover; i++)
                    {
                        elem    = $instance.find('.wpbs-bookable-' + i);

                        if ( elem.hasClass('wpbs-bookable') )
                            elem.addClass('wpbs-selected');


                    }

                    endElem     = $instance.find('.wpbs-selected').last();

                    $instance.find('.wpbs-start-date').val($this.attr('data-timestamp'));
                    $instance.find('.wpbs-end-date').val(endElem.attr('data-timestamp'));
                    $instance.find('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');

                    endDate = false;
                    startDate = false;
                    $instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-" + $instance.find('.wpbs-end-date').val());
                }

               
            });
        }       
        
        /*=====  End of Section comment block  ======*/
        

        /*=============================================
        =       Booking week (current week)           =
        =       the week of current selected day      =
        =============================================*/
        
        if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'week')
        {            
            wpbs($instance).on('click','.wpbs-bookable',function(e)
            {
                e.preventDefault(); 
                $this = wpbs(this);
                
                wpbs_clear_selection();


                currentWeek         = $this.parent('ul');
                selectable          = true;
                expandedSelection   = false;

                currentWeek.children('li').each(function () {
                    elem    = wpbs(this);

                    if ( elem.hasClass('wpbs-disabled') )
                    {
                        alert('There is already booked date in this range!');
                        selectable      = false;
                        return false;
                    }

                    if ( elem.hasClass('wpbs-pad') )
                        expandedSelection = true;
                });
                
                
                if ( selectable && !expandedSelection )
                {
                    wpbs(this).addClass('wpbs-bookable-clicked');
                    
                    currentWeek.children('li').each(function () 
                    {
                        elem    = wpbs(this);

                        if ( elem.hasClass('wpbs-bookable') || elem.hasClass('status-default') )
                            elem.addClass('wpbs-selected');
                    });


                }

                currentWeek         = $this.parent('ul');
                hoverable           = true;
                expandedSelection   = false;

                currentWeek.children('li').each(function () 
                {
                    elem    = wpbs(this);

                    if ( elem.hasClass('status-1') || elem.hasClass('wpbs-disabled') )
                    {
                        hoverable           = false;
                        return false;
                    }

                    if ( hoverable && elem.hasClass('wpbs-pad') )
                        expandedSelection   = true;
                });

                if ( hoverable && expandedSelection )
                {
                    currentWeekStart= currentWeek.children('li:not(.wpbs-week-number):not(.wpbs-pad)').first();
                    firstItem       = currentWeek.children('li:not(.wpbs-week-number)').first();
                    onwords         = true;


                    if ( firstItem.hasClass('wpbs-pad') )
                        onwords     = false;


                    if ( onwords )
                    {
                        hoverStart      = currentWeekStart.data('order');
                        hoverEnd        = hoverStart+6;
                    }
                    else
                    {
                        newfirstItem    = currentWeek.children('li:not(.wpbs-week-number)').last();
                        hoverEnd        = newfirstItem.data('order');
                        hoverStart      = hoverEnd-6;
                    }

                    for(i = hoverStart; i <= hoverEnd; i++)
                    {
                        elem    = $instance.find('.wpbs-bookable-' + i);

                        if ( elem.hasClass('wpbs-bookable') )
                            elem.addClass('wpbs-selected');
                    }
                }

                selectionStart  = currentWeek.find('li.wpbs-selected').first();
                selectionEnd    = currentWeek.find('li.wpbs-selected').last();



                if ( selectionEnd.hasClass('wpbs-pad') )
                    selectionEnd= currentWeek.find('li.wpbs-bookable').last();

                $instance.find('.wpbs-start-date').val(selectionStart.attr('data-timestamp'));
                $instance.find('.wpbs-end-date').val(selectionEnd.attr('data-timestamp'));
                $instance.find('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');

                endDate = false;
                startDate = false;
                $instance.find('.wpbs-calendar-selection').html(selectionStart.attr('data-timestamp') + "-" + selectionEnd.attr('data-timestamp'));

               
            });
        }

        
        
        /*=====  End of Section comment block  ======*/
        

        
        wpbs($instance).on('mouseenter','.wpbs-bookable',function(e)
        {
            e.preventDefault();
            
            $this = wpbs(this);
            
            var temp = 0;

            if ( $instance.find('.wpbs-bookable-clicked').length == 0 )
                $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
            

            if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'week')
            {
                // de-hoverize 
                $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');

                currentWeek         = $this.parent('ul');
                hoverable           = true;
                expandedSelection   = false;

                currentWeek.children('li').each(function () 
                {
                    elem    = wpbs(this);

                    if ( elem.hasClass('status-1') || elem.hasClass('wpbs-disabled') )
                    {
                        hoverable           = false;
                        return false;
                    }

                    if ( hoverable && elem.hasClass('wpbs-pad') )
                        expandedSelection   = true;
                });

                if ( hoverable && !expandedSelection )
                {
                    currentWeek.children('li').each(function () 
                    {
                        elem    = wpbs(this);

                        if ( elem.hasClass('wpbs-bookable') || elem.hasClass('status-default') )
                            elem.addClass('wpbs-bookable-hover');
                    });
                    
                }

                if ( hoverable && expandedSelection )
                {
                    currentWeekStart= currentWeek.children('li:not(.wpbs-week-number):not(.wpbs-pad)').first();
                    firstItem       = currentWeek.children('li:not(.wpbs-week-number)').first();
                    onwords         = true;


                    if ( firstItem.hasClass('wpbs-pad') )
                        onwords     = false;


                    if ( onwords )
                    {
                        hoverStart      = currentWeekStart.data('order');
                        hoverEnd        = hoverStart+6;
                    }
                    else
                    {
                        newfirstItem    = currentWeek.children('li:not(.wpbs-week-number)').last();
                        hoverEnd        = newfirstItem.data('order');
                        hoverStart      = hoverEnd-6;
                    }

                    for(i = hoverStart; i <= hoverEnd; i++)
                    {
                        elem    = $instance.find('.wpbs-bookable-' + i);

                        if ( elem.hasClass('wpbs-bookable') )
                            elem.addClass('wpbs-bookable-hover');
                    }
                }
            }

            if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'fixed')
            {
                // de-hoverize 
                $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
                
                startHover      = parseInt($this.data('order'));
                endHover        = startHover+6;

                selectable      = true;

                // for(i = startHover; i <= endHover; i++)
                // {
                //     elem    = $instance.find('.wpbs-bookable-' + i);
                    
                //     if ( elem.hasClass('status-1') )
                //     {
                //         alert('There is already booked date in this range!');
                //         selectable      = false;
                //         return false;
                //     }


                // }

                if ( selectable )
                {
                    for(i = startHover; i <= endHover; i++)
                    {
                        elem    = $instance.find('.wpbs-bookable-' + i);

                        if ( elem.hasClass('wpbs-bookable') )
                            elem.addClass('wpbs-bookable-hover');
                    }
                }
                // currentWeek.children('li').each(function () 
                // {
                //     elem    = wpbs(this);

                //     if ( elem.hasClass('status-1') )
                //     {
                //         hoverable      = false;
                //         return false;
                //     }
                // });

                // if ( hoverable )
                // {
                //     currentWeek.children('li').each(function () 
                //     {
                //         elem    = wpbs(this);

                //         if ( elem.hasClass('wpbs-bookable') || elem.hasClass('status-default') )
                //             elem.addClass('wpbs-bookable-hover');
                //     });
                    
                // }

            }


            if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'multiple')
            {
                if(startDate == true && endDate == false)
                {
                    $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
                    
                    startHover = parseInt($instance.find('.wpbs-bookable-clicked').attr('data-order'));

                    endHover = parseInt(wpbs(this).attr('data-order'));
                    
                    if(startHover > endHover)
                    {
                        temp = startHover;
                        startHover = endHover;
                        endHover = temp;
                    }

                    for(i = startHover; i <= endHover; i++)
                    {

                     
                        if(wpbs('.wpbs-bookable-' + parseInt(i)).hasClass('wpbs-bookable'))
                        {
                            wpbs('.wpbs-bookable-' + parseInt(i)).addClass('wpbs-bookable-hover');    
                        } 
                        // else 
                        // {
                        //     $instance.find('.wpbs-bookable').each(function()
                        //     {
                        //         if (temp == 0)
                        //         {
                        //             console.log('temp == 0');
                        //             if (parseInt(wpbs(this).attr('data-order')) > i)
                        //                 wpbs(this).addClass('wpbs-not-bookable').removeClass('wpbs-bookable wpbs-bookable-hover');    
                        //         } 
                        //         else 
                        //         {
                        //             console.log('temp != 0');
                        //             if (parseInt(wpbs(this).attr('data-order')) <= i)
                        //                 wpbs(this).addClass('wpbs-not-bookable').removeClass('wpbs-bookable wpbs-bookable-hover');
                        //         }
                        //     })
                        // }
                                
                    }
                    
                    
                }                   
                
            }

        });

        // wpbs($instance).on('mouseleave', '.wpbs-bookable', function (e)
        // {
        //     $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
        // });
        
        
        
        // wpbs('.wpbs-booking-clear').click(function(e){
        //     e.preventDefault();
        //     wpbs_clear_selection()
        // })
    
    })
    
    
    wpbs("div.wpbs-container").on('mouseenter','li.wpbs-day', function(){        
        $li = wpbs(this);
        if(typeof $li.attr('data-tooltip') != 'undefined'){
            $li.addClass('wpbs-tooltip-active');
            $li.append('<div class="wpbs-tooltip"><strong>' + $li.attr('data-tooltip-date') + '</strong>' + $li.attr('data-tooltip') + '</div>');    
        }            
    });    
      
    wpbs("div.wpbs-container").on('mouseleave','li.wpbs-day', function(){
        wpbs(".wpbs-tooltip-active").removeClass('wpbs-tooltip-active');        
        wpbs("li.wpbs-day .wpbs-tooltip").remove();
            
    });
    
})

var defaultCalendars    = wpbs('div.wpbs-total-calendars').text();

wpbs(window).resize(function (e) 
{
    e.preventDefault();

    var width = wpbs(window).width();

    // Check this only if there are more then 1 calendar
    // On the page
    if ( defaultCalendars > 1 )
    {
        // XS Layout (Mobile)
        if ( width < 768 )
        {
            wpbs('div.wpbs-total-calendars').text(1);

            wpbs('div.wpbs-container').each(function()
            {
                var $instance = wpbs(this);
                var timestamp = null;
                
                if($instance.find(".wpbs-current-timestamp a").length == 0)
                    timestamp = $instance.find(".wpbs-current-timestamp").html();
                else 
                    timestamp = $instance.find(".wpbs-current-timestamp a").html()

                wpbs_changeDay('refresh',timestamp, $instance);
            });
            
        }

        // SM Layout (Tablet)
        if ( width >= 768 && width < 992)
        {
            wpbs('div.wpbs-total-calendars').text(2);

            wpbs('div.wpbs-container').each(function()
            {
                var $instance = wpbs(this);
                var timestamp = null;
                
                if($instance.find(".wpbs-current-timestamp a").length == 0)
                    timestamp = $instance.find(".wpbs-current-timestamp").html();
                else 
                    timestamp = $instance.find(".wpbs-current-timestamp a").html()

                wpbs_changeDay('refresh',timestamp, $instance);
            });
            
        }

        // MD Layout (Laptops/Desktops)
        if ( width >= 992 )
        {
            wpbs('div.wpbs-total-calendars').text(defaultCalendars);

            wpbs('div.wpbs-container').each(function()
            {
                var $instance = wpbs(this);
                var timestamp = null;
                
                if( $instance.find(".wpbs-current-timestamp a").length == 0 )
                    timestamp = $instance.find(".wpbs-current-timestamp").html();
                else 
                    timestamp = $instance.find(".wpbs-current-timestamp a").html()

                wpbs_changeDay('refresh',timestamp, $instance);
            });
            
        }
        
    }
});
$ = jQuery.noConflict();