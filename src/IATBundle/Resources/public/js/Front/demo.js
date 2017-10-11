// Call this from the developer console and you can control both instances
var calendars = {};

$(document).ready( function() {
	var ajaxCall = false;
    /*console.info(
        'Welcome to the CLNDR demo. Click around on the calendars and' +
        'the console will log different events that fire.');*/

    // Assuming you've got the appropriate language files,
    // clndr will respect whatever moment's language is set to.
    // moment.locale('ru');

    // Here's some magic to make sure the dates are happening this month.
	if(typeof START_MONTH != 'undefined' && START_MONTH != ''){
		var thisMonth = START_MONTH;
	}else{
		var thisMonth = moment().format('YYYY-MM');
	}
    
    // Events to load into calendar
	/*var eventArray = [
        {
            title: 'Multi-Day Event',
            endDate: thisMonth + '-14',
            startDate: thisMonth + '-10'
        }, {
            endDate: thisMonth + '-23',
            startDate: thisMonth + '-21',
            title: 'Another Multi-Day Event'
        }, {
            date: thisMonth + '-27',
            title: 'Single Day Event'
        }
    ];*/
	
	if(typeof EVENTSARRAY != 'undefined' && EVENTSARRAY.length > 0){
		var eventArray = EVENTSARRAY;
	}else{
		var eventArray = [];
	}

    // The order of the click handlers is predictable. Direct click action
    // callbacks come first: click, nextMonth, previousMonth, nextYear,
    // previousYear, nextInterval, previousInterval, or today. Then
    // onMonthChange (if the month changed), inIntervalChange if the interval
    // has changed, and finally onYearChange (if the year changed).
    /* calendars.clndr1 = $('.cal1').clndr({
        events: eventArray,
        clickEvents: {
            click: function (target) {
                console.log('Cal-1 clicked: ', target);
            },
            today: function () {
                console.log('Cal-1 today');
            },
            nextMonth: function () {
                console.log('Cal-1 next month');
            },
            previousMonth: function () {
                console.log('Cal-1 previous month');
            },
            onMonthChange: function () {
                console.log('Cal-1 month changed');
            },
            nextYear: function () {
                console.log('Cal-1 next year');
            },
            previousYear: function () {
                console.log('Cal-1 previous year');
            },
            onYearChange: function () {
                console.log('Cal-1 year changed');
            },
            nextInterval: function () {
                console.log('Cal-1 next interval');
            },
            previousInterval: function () {
                console.log('Cal-1 previous interval');
            },
            onIntervalChange: function () {
                console.log('Cal-1 interval changed');
            }
        },
        multiDayEvents: {
            singleDay: 'date',
            endDate: 'endDate',
            startDate: 'startDate'
        },
        showAdjacentMonths: true,
        adjacentDaysChangeMonth: false
    });*/

    // Calendar 2 uses a custom length of time: 2 weeks paging 7 days
    /*calendars.clndr2 = $('.cal2').clndr({
        lengthOfTime: {
            days: 14,
            interval: 7
        },
        events: eventArray,
        multiDayEvents: {
            singleDay: 'date',
            endDate: 'endDate',
            startDate: 'startDate'
        },
        template: $('#template-calendar').html(),
        clickEvents: {
            click: function (target) {
                console.log('Cal-2 clicked: ', target);
            },
            nextInterval: function () {
                console.log('Cal-2 next interval');
            },
            previousInterval: function () {
                console.log('Cal-2 previous interval');
            },
            onIntervalChange: function () {
                console.log('Cal-2 interval changed');
            }
        }
    });*/

	
	
	// Calendar 3 renders two months at a time, paging 1 month
    calendars.clndr3 = $('.cal3').clndr({
        lengthOfTime: {
            months: 2,
            interval: 2
        },
        events: eventArray,
        multiDayEvents: {
            endDate: 'endDate',
            startDate: 'startDate',
            title: 'title',
            noOfNights: 'noOfNights'
        },
        clickEvents: {
            click: function (target) {
            	if(target.events.length > 0){
            		var price = target.events[0]['title'];
            		var departureDate = target.date._i;
            		var noOfNights = target.events[0]['noOfNights'];
            		//console.log(target);
            		//console.log(target.next.date._i);
            		$(document).find('#selectedDepartureDate').val(departureDate);
            		$(document).find('#selectedDealPrice').val(price);
            		$(document).find('#defaultNoOfnights').val(noOfNights);
            		
            		
            		$(document).find("#calRadio").each(function(){
            			if($(this).is(':checked') === true) {
            				$(this).prop('checked', false);
                	    }
            		});
            		$(this).find("#calRadio").prop('checked', true);
            		//console.log(target.element.attributes);
            		/*$(target.element).removeAttr('class');
            		$(target.element).addClass('day red');*/
            		
            		$(document).find('.day').each(function(){
            			if($(this).hasClass('red')){
            				$(this).removeClass('red');
            			}
            			if($(this).find('.checkbox input:radio').is(':checked') === true) {
            				$(this).find('.checkbox input:radio').prop('checked', false);
                	    }
            		});
            		
            		//$(target.element).removeAttr('class');
            		$(target.element).addClass('red');
            		            		
            		if($(target.element).find('input:radio').is(':checked') === false) {
            			$(target.element).find('input:radio').prop('checked', true);
            	    }
            		
            		var curElem = $(target.element);
            		for (i = 0; i < noOfNights ; i++) {
            			//curElem.removeAttr('class');
            			curElem.addClass('red');
            			//curElem.find('input:radio').prop('checked', true);
            			var temp = curElem;
            			curElem = curElem.next('.day');
            			if(curElem.length < 1){
            				curElem = $(temp).closest('.cal').next('.cal').find('.day').first();
            			}/*else{
            				console.log('not empty');
            			}*/
            		}
            		
            		//$(document).find('#selectedDepartureDate').closest('form').submit();
            		//$(document).find('.js-calender-filter').trigger('click');
            		//$(document).find('#selectedDepartureDate').closest('form').submit();
            		
            		
            		var form = $(document).find('#selectedDepartureDate').closest('form');
            		var url = $(form).attr('action');
            		var data = $(form).serialize();
            		
            		if(ajaxCall)
            			ajaxCall.abort();
            		
            		ajaxCall = $.ajax({
            			type: 'POST',
            			url: url,
            			data: data,
            			cache: false,
            			//dataType: 'json',
            			success: function(responce_data){
            				$('.js-calender-section').html('');
            				$('.js-calender-section').html(responce_data);
            				//console.log($(responce_data).find('.js-calender-section').html());
            				//$('.js-calender-section').html($(responce_data).find('.js-calender-section').html());
            			}
            		});

            	}
            }/*,
            nextInterval: function () {
                console.log('Cal-1 next interval');
            },
            previousInterval: function () {
                console.log('Cal-1 previous interval');
            },
            onIntervalChange: function () {
                console.log('Cal-3 interval changed');
            }*/
        },
        template: $('#template-calendar-months').html(),
        constraints: {
            startDate: START_MONTH,
            endDate: END_MONTH
        }
    });

    // Bind all clndrs to the left and right arrow keys
    $(document).keydown( function(e) {
        // Left arrow
        if (e.keyCode == 37) {
            /*calendars.clndr1.back();
            calendars.clndr2.back();*/
            calendars.clndr3.back();
        }

        // Right arrow
        if (e.keyCode == 39) {
            /*calendars.clndr1.forward();
            calendars.clndr2.forward();*/
            calendars.clndr3.forward();
        }
    });
});

$(document).ready(function(){
	//FOR SELECTING DAY ON PAGE LOAD
	if((typeof SELECTED_DATE != 'undefined' && SELECTED_DATE != '') && (typeof SELECTED_NO_OF_NIGHTS != 'undefined' && SELECTED_NO_OF_NIGHTS != '')){
		//console.log(SELECTED_DATE);
		//console.log('.calendar-day-' + SELECTED_DATE);
		var currentSelectedDay = $(document).find('.calendar-day-' + SELECTED_DATE);
		$(currentSelectedDay).addClass('red');
		$(currentSelectedDay).find('input:radio').prop('checked', true);
		var curElem = $(currentSelectedDay);
		for (i = 0; i < SELECTED_NO_OF_NIGHTS ; i++) {
			//curElem.removeAttr('class');
			curElem.addClass('red');
			//curElem.find('input:radio').prop('checked', true);
			var temp = curElem;
			curElem = curElem.next('.day');
			if(curElem.length < 1){
				curElem = $(temp).closest('.cal').next('.cal').find('.day').first();
			}/*else{
				console.log('not empty');
			}*/
		}
	}
});