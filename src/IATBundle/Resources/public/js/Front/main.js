$(document).ready(function(){
	var ajaxCall = false;
	$(document).on('click', '.js-calender-filter', function(e){
		//$(this).closest('form').submit();
		var form = $(this).closest('form');
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
				
				resetPriceInCalender();
			}
		});
	});
});


function resetPriceInCalender(){
	
	$(document).find('#selectedDepartureDate').val('');
	$(document).find('#selectedDealPrice').val('');
	$(document).find('#defaultNoOfnights').val('');
	
	$(document).find("#calRadio").each(function(){
		if($(this).is(':checked') === true) {
			$(this).prop('checked', false);
	    }
	});
	
	$(document).find('.day').each(function(){
		if($(this).hasClass('red')){
			$(this).removeClass('red');
		}
		
		if($(this).find('.checkbox input:radio').is(':checked') === true) {
			$(this).find('.checkbox input:radio').prop('checked', false);
	    }
	});
}

function showPageAjaxLoading(){
	if (!$('.widget-box-overlay').length) {
		$('body').prepend('<div class="widget-box-overlay" style="display: block;"></div>');
	}
}

function hidePageAjaxLoading(){
	$('.widget-box-overlay').remove();
}

function hideAlertBar(){
	$( ".alert-success, .alert-warning,  .alert-danger, .alert-info").remove();
}

function showSuccessMessage(success_message){
	hideAlertBar();
	$('body .hed-main').prepend('<div class="alert alert-success"><a data-dismiss="alert" class="close" type="button">&times;</a><strong>'+'Success'+'!</strong> <span>'+ success_message+'</span></div><div class="clr"></div>');
	setTimeout("hideAlertBar();", 10000);
}

function showErrorMessage(error_message){
	hideAlertBar();
	$('body .hed-main').prepend('<div class="alert alert-danger"><a data-dismiss="alert" class="close" href="javascript:void(0)">&times;</a><strong>'+'Alert'+'!</strong> <span>'+ error_message+'</span></div><div class="clr"></div>');
	setTimeout("hideAlertBar();", 10000);
}

function checkIAgree(){
	var checkBox = $(document).find('#iAgreeCheckbox');
	if($(checkbox).is(':checked'))
		return true;
	else
		return false;
}