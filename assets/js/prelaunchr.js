jQuery( document ).ready(function( $ ) {


	setTimeout( function() {

		var progress = $('.progress').data('prelaunchr-progress');

		if ( progress <= 5 ) {
			$('.progress').addClass( 'progress-5' );
		} else if ( progress > 5 && progress <= 25 ) {
			$('.progress').addClass( 'progress-25' );
		} else if ( progress > 25 && progress <= 50 ) {
			$('.progress').addClass( 'progress-50' );
		} else if ( progress > 50 && progress <= 75 ) {
			$('.progress').addClass( 'progress-75' );
		} else if ( progress > 75 && progress <= 100 ) {
			$('.progress').addClass( 'progress-100' );
		}

		$('.progress-bar').css({
			"width": progress+"%"
		});

	}, 800);

	var pid = uuid.v4();
	var cookie_pid = $.cookie('prelaunchr[id]');
	var email;
	var rid;

	/**
	 * If no cookie_pid set a new cookie with pid
	 */
	if ( cookie_pid == undefined ) {

		$.cookie( 'prelaunchr[id]' , pid , { expires: 730, path: '/' });

	}

	/**
	 * Hide the response/validation div
	 */
	$('.prelaunchr .response').hide();

	/**
	 * Handle form submission
	 */
	$('.pform').submit(function(e){

		e.preventDefault();

		email = $.trim( $(this).find("input[type='email']").val() );

		rid = getUrlParameter('ref');

		if ( email ) {

			$.ajax({

				type: 'POST',
				url: PrelaunchrSubmit.ajaxurl,
				data: {
					'action'		: 'prelaunchr-submit', 
					'pid'			: pid,
					'cookie_pid'	: cookie_pid,
					'email'			: email,
					'rid'			: rid
				},
				dataType: 'JSON',
				success: function( response, textStatus, XMLHttpRequest ) {

					//console.log( response );

					/**
					 * If email passes server validation and is stored
					 */
					if ( response.success ) {
						$('body').trigger('prelaunchr_response_success');
						window.location.href = '/'+PrelaunchrSubmit.return+'/'+response.data.pid;
						return;
					} else {
						$('body').trigger('prelaunchr_response_fail');
						$('.prelaunchr .response').html(response.data).fadeIn();
						return;
					}

				},
				error: function( XMLHttpRequest, textStatus, errorThrown) {
					console.log( errorThrown );
				},
				complete: function( XMLHttpRequest, textStatus) {
					//something
				}

			});

		}

	});

});

/**
 * Get a specific url paramater
 *
 * http://stackoverflow.com/questions/19491336/get-url-parameter-jquery
 */
function getUrlParameter(sParam) {

	var sPageURL = window.location.search.substring(1);

	var sURLVariables = sPageURL.split('&');

	for (var i = 0; i < sURLVariables.length; i++) {

		var sParameterName = sURLVariables[i].split('=');

		if (sParameterName[0] == sParam) {

			return sParameterName[1];

		}

	}

}