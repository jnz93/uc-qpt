(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	// $(document).ready(function () {

	// 	$("#wizard").steps();
	// })

})( jQuery );


function submitAnswers(wrapperQuestion, quizId, voucherId, ajaxUrl)
{
	var checkedAnswers 	= [];
	wrapperQuestion.each(function (index)
	{
		// Collect checked answers data
		var qId = jQuery(this).attr('data-id');
		jQuery(this).find('input[type=checkbox]').each(function (index)
		{
			var currAnswer = jQuery(this);
			if (currAnswer.is(':checked')) {

				var answerId = currAnswer.attr('data-id'),
					weight = currAnswer.siblings('select').val()
					data = qId + ":" + answerId + ":" + weight;

				checkedAnswers.push(data);
				
			};
		});
	});
	console.log(checkedAnswers);
	
	// if (false) {
	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'ucqpt_submit_quiz',
			data: checkedAnswers,
			quizId: quizId,
			voucherId: voucherId
		},
	}).done(function(res){
		console.log(res);
		jQuery('.wrapper-result').html(res);
	});
	// }
}


function autenticateVoucher(ajaxUrl) 
{
    var voucherCode = jQuery('#ucqpt_voucher_code').val();

	if ( voucherCode.length < 10 ) {
		console.log('Voucher inválido.');
		return;
	}

	if ( ajaxUrl.length == 0 ) {
		console.log('ajaxUrl inválida.');
		return;
	}

	var dataSend = {
		action: 'ucqpt_checking_voucher',
		voucherCode: voucherCode
	};

	jQuery.ajax({
		url: ajaxUrl,
		type: 'POST',
		data: dataSend,
		beforeSend: function() {
			console.log('Autenticando...');
		},
	})
	.done( function(res) {
		console.log('Requisição finalizada');
		jQuery('.entry-content').html(res);
	});
}

function submitUserData(ajaxUrl)
{
	var userName = jQuery('#user_full_name').val(),
		userEmail = jQuery('#user_email').val(),
		userPhone = jQuery('#user_phone').val();

	if ( userName.length < 4 || userEmail.length < 4 || userPhone.length < 8 ) {
		console.log('Dados do usuário estão incompletos');
		return;
	}

	var dataUser = {
		action: 'uqpt_record_user_data',
		name: userName,
		email: userEmail,
		phone: userPhone,
		quiz: quizId,
		voucher: voucherId
	};

	jQuery.ajax({
		url: ajaxUrl,
		type: 'POST',
		data: dataUser,
		beforeSend: function(){
			console.log('Salvando dados do usuário');
		}
	})
	.done( function (res) {
		jQuery('.entry-content').html(res);
	});

}

function filterWeights(el)
{
	parentSiblings = el.parents('div').siblings().children('label');
	selectedValue = el.val();

	parentSiblings.each(function(){
		options = jQuery(this).find('option');

		options.each(function (){
			optVal = jQuery(this).val();
			
			if ( optVal == selectedValue ) {
				jQuery(this).hide();
			}
		});
	});
}

function resetAnswer(el)
{
	siblings = el.siblings();

	siblings.each(function() {
		options = jQuery(this).find('option');

		options.each(function() {
			jQuery(this).show();
		});
	});
}

// Funções relativas ao step-by-step das perguntas
var currentTab = 0; // Current tab is set to be the first tab (0)
function showTab(n) {
	// This function will display the specified tab of the form ...
	var x = document.getElementsByClassName("tab");
	x[n].style.display = "block";
	// ... and fix the Previous/Next buttons:
	if (n == 0) {
		document.getElementById("prevBtn").style.display = "none";
	} else {
		document.getElementById("prevBtn").style.display = "inline";
	}
	if (n == (x.length - 1)) {
		// document.getElementById("nextBtn").innerHTML = "Submit";
		document.getElementById("nextBtn").style.display = "none";
	} else {
		document.getElementById("nextBtn").style.display = "inline";
	}
	// ... and run a function that displays the correct step indicator:
	fixStepIndicator(n)
}

function fixStepIndicator(n) {
	// This function removes the "active" class of all steps...
	var i, x = document.getElementsByClassName("step");
	for (i = 0; i < x.length; i++) {
		x[i].className = x[i].className.replace(" active", "");
	}
	//... and adds the "active" class to the current step:
	x[n].className += " active";
}

function validateForm() {
	// This function deals with validation of the form fields
	var x, y, i, valid = true;
	x = document.getElementsByClassName("tab");
	y = x[currentTab].getElementsByTagName("select");
	// A loop that checks every input field in the current tab:
	for (i = 0; i < y.length; i++) {
		// If a field is empty...
		if (y[i].value == "") {
		// add an "invalid" class to the field:
		y[i].className += " invalid";
		// and set the current valid status to false:
		valid = false;
		}
	}
	// If the valid status is true, mark the step as finished and valid:
	if (valid) {
		document.getElementsByClassName("step")[currentTab].className += " finish";
	}
	return valid; // return the valid status
}

function nextPrev(n) {
	// This function will figure out which tab to display
	var x = document.getElementsByClassName("tab");
	// Exit the function if any field in the current tab is invalid:
	if (n == 1 && !validateForm()) return false;
	// Hide the current tab:
	x[currentTab].style.display = "none";
	// Increase or decrease the current tab by 1:
	currentTab = currentTab + n;
	// if you have reached the end of the form... :
	if (currentTab >= x.length) {
		//...the form gets submitted:
		document.getElementById("regForm").submit();
		return false;
	}
	// Otherwise, display the correct tab:
	showTab(currentTab);
	jQuery('#nextBtn').hide();
}

/**
 * 
 * @param {*} el = clicked label 
 */
function validateAnswers(el)
{
	'use strict';
	var currEl 				= el,
		currSelect 			= el.find('select'),
		siblings 			= currEl.parent().siblings('.uk-grid'),
		tabs				= jQuery('.tab'),
		totalTabs 			= tabs.length -1,
		invalidAnswers		= 0; 

	currSelect.attr('disabled', true);
	currEl.find('span.uk-alert-success').css({'opacity': '1'});
	
	siblings.each( function ( i ) {
		
		var currSelect = jQuery(this).find('select');

		if ( currSelect.val() == 0 ) {
			invalidAnswers++;
		}
	});

	// Esconder ou mostrar os botões
	if ( invalidAnswers == 0 ) {

		if (currentTab > 0 && currentTab < totalTabs ) {
			jQuery('#prevBtn, #nextBtn').show();
		} else if ( currentTab == totalTabs ) {
			jQuery('#prevBtn, #wrapper-submit').show();
		} else {
			jQuery('#nextBtn').show();
		}

	} else {
		jQuery('#nextBtn').hide();
	}
}