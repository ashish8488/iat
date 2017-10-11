$(document).ready(function(){
	$(document).on('click', '.js-inquiry-change-status, .js-deal-change-status', function(){
		showPageAjaxLoading();
		var id = $(this).attr('data-id');
		var tempUrl = $(this).attr('data-url');
		var url = Routing.generate(tempUrl, {'id': id});
		changeStatus(url);
	});
	
	$(document).on('click', '.js-dealLink-change-status', function(){
		showPageAjaxLoading();
		var dealLink = $(this).attr('data-deal-link');
		var tempUrl = $(this).attr('data-url');
		var url = Routing.generate(tempUrl, {'dealLink': dealLink});
		changeStatus(url);
	});
	
	$(document).on('change', '.js-merchant-name', function(){
		/*showPageAjaxLoading();
		var id = $(this).val();
		var tempUrl = 'iatbundle_admin_listDeal';
		var url = Routing.generate(tempUrl);
		$.ajax({
			type: 'GET',
			url: url,
			cache: false,
			success: function(responce_data){
				if(responce_data.status == 'success'){
					hidePageAjaxLoading();
					showSuccessMessage(responce_data.message);
				}
			}
		});*/
		//if($(this).val() != '' && $(this).val() > 0){
			$(this).closest('form').submit();
		//}
	});
});

function changeStatus(url){
	$.ajax({
		type: 'GET',
		url: url,
		cache: false,
		success: function(responce_data){
			if(responce_data.status == 'success'){
				hidePageAjaxLoading();
				showSuccessMessage(responce_data.message);
			}
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

$(document).on('click', '.js_delete_btn',function(e){
	e.preventDefault();
	var data_url = $(this).data('url');
	var id		= $(this).data('id');
	bootbox.dialog(
			{
				message:'Are you sure you want to delete?',
				buttons: {								
					success:{ 
						label: 'Ok',
						className: 'btn btn-primary',
						callback:function (result) {					
							if (result) {
									showPageAjaxLoading();
									$.ajax({
										type : 'GET',
										url : data_url, 
										data : {'id':id},
										cache : false,
										success : function(response_data) {
											hidePageAjaxLoading();
											 if (response_data.status == 'success') {
												 	showSuccessMessage(response_data.message);
											 } else if (response_data.status == 'error') {
												showErrorMessage(response_data.message);
											}
										}
									});
								  }
					}								
		        },
				danger:{
					label: "Cancel",
					className: 'btn btn-default',
				}							
	}});
});

$(document).on('click','.js-view-popup', function (e) {
	$('#modal-table').html('');
	e.preventDefault();
	//showTopAjaxLoading();
	showPageAjaxLoading();
	var url = $(this).data('url');
	var modalId = $(this).data('target');
	$.ajax({
		type: 'GET',
		url: url,
		data: '',
		cache: false,
		success: function(responce_data){
			//hideAlertBar();
			hidePageAjaxLoading();
			$(modalId).html('');
			$(modalId).html(responce_data);
			$(modalId).modal('show');				
		}
	});			
	return false;
});