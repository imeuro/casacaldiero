jQuery(document).ready(function() {

	// datepicker init
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	var checkin = jQuery('#dpd1').datepicker({
	  onRender: function(date) {
	    return date.valueOf() < now.valueOf() ? 'disabled' : '';
	  }
	}).on('changeDate', function(ev) {
	  if (ev.date.valueOf() > checkout.date.valueOf()) {
	    var newDate = new Date(ev.date)
	    newDate.setDate(newDate.getDate() + 1);
	    checkout.setValue(newDate);
	  }
	  checkin.hide();
	  jQuery('#dpd2')[0].focus();
	}).data('datepicker');
	var checkout = jQuery('#dpd2').datepicker({
	  onRender: function(date) {
	    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
	  }
	}).on('changeDate', function(ev) {
	  checkout.hide();
	}).data('datepicker');

	if (getUrlParameter('check_in')!= null) {
		populate_form();
	}

	setAvgPrice();

	wpbs('div.wpbs-container').each(function() {
		var $instance           = wpbs(this);
		wpbs($instance).on('change','.wpbs-dropdown',function(e) {
			setAvgPrice();
		});
		wpbs($instance).on('click','.wpbs-next',function(e) {
			setAvgPrice();
		});
		wpbs($instance).on('click','.wpbs-prev',function(e) {
			setAvgPrice();
		});
	});


});

// set average price from tooltip
var setAvgPrice = function setAvgPrice() {
	setTimeout(function(){
		var firstdayprice = $('#apt-calendario li.wpbs-day').first().attr('data-tooltip');
		if (firstdayprice && firstdayprice!='') {
			$('.est-price span').addClass('hidden');
			$('.est-price').prepend("<span class='avgprice'>"+firstdayprice+"</span>");
		} else {
			$('.est-price span.hidden').removeClass('hidden');
			$('.est-price span.avgprice').remove();
		}
	},2000);

}

// get checkin/checkout dates from url
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function java_mktime(hour,minute,month,day,year) {
    return new Date(year, month - 1, day, hour, minute, 0, 0).getTime() / 1000;
}

function wpbs_selectdays(TSin,TSout) {

	wpbs_clear_selection();
	var $instance = $('#apt-calendario');
	// CLICK START DATE
	$instance.find('li[data-timestamp="'+TSin+'"]').addClass('wpbs-bookable-clicked');
	$instance.find('.wpbs-start-date').val(TSin);
	$instance.find('.wpbs-calendar-selection').html(TSin + "-" + TSout);

	// CLICK END DATE
	$instance.find('li[data-timestamp="'+TSout+'"]').addClass('wpbs-bookable-clicked');
	$instance.find('.wpbs-end-date').val(TSout);
	$instance.find('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');
	$instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-" + $instance.find('.wpbs-end-date').val());
}

var populate_form = function populate_form() {
	var check_in = getUrlParameter('check_in');
	var check_out = getUrlParameter('check_out');
	var guests = getUrlParameter('guests');

	check_in=check_in.split("/");
	check_out=check_out.split("/");
	var check_in_TS = java_mktime(1,0,check_in[0],check_in[1],check_in[2]);
	var check_out_TS = java_mktime(1,0,check_out[0],check_out[1],check_out[2]);

	//console.log('check_in_TS: '+check_in_TS);
	//console.log('check_out_TS: '+check_out_TS);

	var $instance = $('#apt-calendario');
	// select days previously asked
	wpbs_selectdays(check_in_TS,check_out_TS);

	// select guests number
	$instance.find('select.wpbs-form-field-dropdown option:nth-child('+guests+')').attr('selected', true).trigger('change');

	// activate a transparent layer to prevent accidental changes
	$instance.find('#preventchanges').removeClass('hidden');

	setAvgPrice();

	// move the calendar to the first booked day
	setTimeout(function(){
		changeDay('jump',check_in_TS,$instance)
	},1500);
}
