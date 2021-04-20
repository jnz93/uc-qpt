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

		var answer 	= jQuery(this).find('span.answer');

		answer.each( function (i) {

			var	answerId 	= jQuery(this).attr('data-id'),
				weight 		= jQuery(this).siblings().find('select').val(),
				data 		= qId + ":" + answerId + ":" + weight;

				checkedAnswers.push(data);
		})
	});

	if (checkedAnswers) {
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
			jQuery('.wrapper-result').html(res);
		});
	}
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
	})
	.done( function(res) {
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

function resetAnswers(el)
{
	siblings = el.siblings();

	siblings.each(function() {
		select 	= jQuery(this).find('select');
		options = jQuery(this).find('option');
		checkedIcons = jQuery(this).find('.uk-alert-success');
		invalidIcons = jQuery(this).find('.uk-alert-danger');


		select.each( function (i) {
			jQuery(this).removeAttr('disabled');
		});
		options.each(function (i) {
			jQuery(this).show();
		});

		checkedIcons.each(function (i) {
			jQuery(this).css('opacity', '0');
		});
		invalidIcons.each(function (i) {
			jQuery(this).css('opacity', '1');
		})
	});

	jQuery('#wrapper-submit, #nextBtn').hide();
}

// Funções relativas ao step-by-step das perguntas
var currentTab = 0; // Current tab is set to be the first tab (0)
function showTab(n) {
	// This function will display the specified tab of the form ...
	var x = document.getElementsByClassName("tab");
	x[n].style.display = "block";
	// ... and fix the Previous/Next buttons:
	// if (n == 0) {
	// 	document.getElementById("prevBtn").style.display = "none";
	// } else {
	// 	document.getElementById("prevBtn").style.display = "inline";
	// }
	// if (n == (x.length - 1)) {
	// 	// document.getElementById("nextBtn").innerHTML = "Submit";
	// 	document.getElementById("nextBtn").style.display = "none";
	// } else {
	// 	document.getElementById("nextBtn").style.display = "inline";
	// }
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
	currEl.find('span.uk-alert-success').css('opacity', '1');
	currEl.find('span.uk-alert-danger').css('opacity', '0');
	
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

/**
 * Setar voucher id no modal de update
 * @param {*} voucherId
 * @param {*} voucherCode
 */
function setVoucherIdOnModal(voucherId, voucherCode)
{
	'use strict';

	jQuery('#edit-voucher').attr('data-voucher', voucherId);
	jQuery('#edit-voucher').find('.uk-modal-title').text('Editar Voucher: ' + voucherCode);
}
 

 
/**
 * Salvar dados do usuário no voucher via painel da empresa
 * @param {*} ajaxUrl 
 * 
 * @since v1.3.0
 */
function updateVoucherUserData(ajaxUrl)
{
'use strict';

var userName 	= jQuery('#ucqpt_customer_name').val(),
	userEmail 	= jQuery('#ucqpt_customer_email').val(),
	userTel 	= jQuery('#ucqpt_customer_tel').val(),
	userDoc 	= jQuery('#ucqpt_customer_cpf').val(),
	voucherId 	= jQuery('#edit-voucher').attr('data-voucher');

var dataSend = {
	action: 'ucqpt_update_voucher_data',
	voucherId: voucherId,
	userName: userName,
	userEmail: userEmail,
	userTel: userTel,
	userDoc: userDoc
};

jQuery.ajax({
	type: 'POST',
	url: ajaxUrl,
	data: dataSend
})
.done( function (res) {
	if (res == 'error') {
		console.log(res);
	} else {
		UIkit.notification({ message: '<span uk-icon=\'icon: check\'></span> Os dados foram salvos com sucesso!', pos: 'bottom-center', status:'success' });
		UIkit.modal('#edit-voucher').hide();
		document.getElementById('form-voucher').reset();
	}
});
}
