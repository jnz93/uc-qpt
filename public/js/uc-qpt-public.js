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

})( jQuery );


function submitAnswers(el, quizId, voucherId, ajaxUrl)
{
	var checkedAnswers 	= [];
	el.each(function (index)
	{
		// Coletando as respostas marcadas
		var qId = jQuery(this).attr('data-id');
		jQuery(this).find('input[type=checkbox]').each(function (index)
		{
			var currAnswer = jQuery(this);
			if (currAnswer.is(':checked')) {

				var answerId = currAnswer.attr('data-id'),
					weight = currAnswer.siblings('input[type=number]').val()
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
	parentSiblings = el.parent().siblings();
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