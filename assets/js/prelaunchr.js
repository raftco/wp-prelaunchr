jQuery( document ).ready(function( $ ) {

	/**
	 * Hide the response/validation div
	 */
	$('.prelaunchr .response').hide();

	/**
	 * Hide spam bot test
	 */
	$('.ignore').hide();

	var share_button_top = new Share(".share", {
		url: $("#referral-link").html(),
		ui: {
			flyout: "top center",
			button_text: "Share"
		},
		networks: {
			google_plus:  {
				enabled: false,
			},
			pinterest:  {
				enabled: false,
			}
		}
	});

	setTimeout( function() {

		var progress = $('.progress').data('prelaunchr-progress');

		if ( progress < 1 ) {
			$('.progress .progress-bar').addClass( 'progress-0' );
		} else if ( progress >= 1 && progress <= 5 ) {
			$('.progress .progress-bar').addClass( 'progress-5' );
		} else if ( progress > 5 && progress <= 25 ) {
			$('.progress .progress-bar').addClass( 'progress-25' );
		} else if ( progress > 25 && progress <= 50 ) {
			$('.progress .progress-bar').addClass( 'progress-50' );
		} else if ( progress > 50 && progress <= 75 ) {
			$('.progress .progress-bar').addClass( 'progress-75' );
		} else if ( progress > 75 && progress <= 100 ) {
			$('.progress .progress-bar').addClass( 'progress-100' );
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
	 * Handle form submission
	 */
	$('.pform').submit(function(e){

		e.preventDefault();

		name = $.trim( $(this).find("#name").val() );

		if ( name ) {
			return;
		}

		$('body').trigger('prelaunchr_form_submit');

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
					'rid'			: rid,
					'nonce'			: PrelaunchrSubmit.nonce
				},
				dataType: 'JSON',
				success: function( response, textStatus, XMLHttpRequest ) {

					$('body').trigger('prelaunchr_response', response );

					/**
					 * If email passes server validation and is stored
					 */
					if ( response.success ) {
						window.location.href = PrelaunchrSubmit.return+'?pid='+response.data.pid;
						return;
					} else {
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
	
	/**
	 * Selecet referral link on click
	 */
	$('#referral-link').on('click', function() {

		selectElementContents(document.getElementById('referral-link'));

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

/**
 * Selecet the contents of a div
 */
function selectElementContents( el ) {

	if (window.getSelection && document.createRange) {
		// IE 9 and non-IE
		var range = document.createRange();
		range.selectNodeContents(el);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);
	} else if (document.body.createTextRange) {
		// IE < 9
		var textRange = document.body.createTextRange();
		textRange.moveToElementText(el);
		textRange.select();
	}

}