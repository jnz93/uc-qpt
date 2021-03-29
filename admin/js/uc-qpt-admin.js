(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

/**
 * Create new draft quiz with generic name
 * @param {*} ajaxUrl 
 * @since 1.0.0
 */
function createDraftQuiz(ajaxUrl)
{
	'use strict';
	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'ucqpt_create_draft_quiz'
		},
		success: function(res)
		{
			console.log('Teste rascunho: ' + res);
		},
		erro: function(res)
		{
			console.log(res);
		}
	});
}


/**
 * Create new quiz with data input
 * @param {*} ajaxUrl 
 * @since 1.0.0
 */
function createNewQuiz(el, ajaxUrl)
{
	'use strict';
	var quizName = jQuery('#ucqpt_test_name').val(),
		quizDescription = jQuery('#ucqpt_test_description').val();

	var dataToSend = quizName + '||' + quizDescription;
	var bodyEl = el.parents('.uk-modal-body');
	
	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'ucqpt_create_new_quiz',
			data: dataToSend
		},
		beforeSend: function()
		{
			// Esconder inputs
			bodyEl.children('form').fadeOut();
			// Add loading
			bodyEl.append('<div id="spinner" uk-spinner></div>');
		},
	}).done(function(res) {
		bodyEl.find('#spinner').hide();
		bodyEl.children('.wrapper-new-question').fadeIn();

		// mostrar título, descrição e botão atualizar teste
		bodyEl.find('.uk-modal-title').text(quizName);
		bodyEl.find('.uk-modal-title').siblings('p').text(quizDescription);
		bodyEl.find('.uk-modal-title').siblings('p').after(res);
		jQuery('#test-update').fadeIn();

		UIkit.notification({message: '<span uk-icon=\'icon: check\'></span> <b>'+ quizName +'</b> criado com sucesso!', status: 'success', pos: 'bottom-center'});
	});
}

/**
 * Add new question template
 * @param {*} ajaxUrl
 * @since 1.0.0
 */
function addTplQuestion(ajaxUrl)
{
	var template = `<div id="" class="uk-card uk-card-default uk-width-1-1 uk-margin-small-bottom">
						<!-- Title new question -->
						<div class="uk-card-header uk-flex uk-flex-between">
							<div class="uk-flex uk-flex-middle" style="width: 80%;">
								<h4 class="" style="margin:0;">Nova pergunta</h4>
							</div>
							<div class="uk-flex uk-flex-column ucqpt-actions" style="display: none;">
								<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
									<label><input class="uk-checkbox" type="checkbox" data-value="yes" onclick="setShowHide('${ajaxUrl}', jQuery(this))"> Desativar pergunta</label>
								</div>
								<div>
									<span class="" uk-icon="file-edit" uk-tooltip="Indisponível no momento"></span>
									<span class="" uk-icon="close" uk-tooltip="Indisponível no momento"></span>
								</div>
							</div>
						</div>
						
						<!-- Wrapper body -->
						<div class="wrapper-question uk-card-body">
							<div class="uk-width-1-1">
								<div class="uk-margin">
									<input class="uk-input question-title" type="text" placeholder="Insira a pergunta">
								</div>
								<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid" style="display: none;">
									<label><input class="uk-checkbox" id="disable-question" type="checkbox"> Desabilitar pergunta</label>
								</div>
							</div>

							<!-- Respostas -->
							<div class="uk-width-1-1">
								<h4 class="">Respostas</h4>
								<div class="uk-margin uk-flex uk-flex-row">
									<input class="uk-input uk-form-width-large answer-one" type="text" placeholder="Resposta #1">
									<div class="uk-margin-left">
										<div uk-form-custom="target: > * > span:first-child">
											<select class="answer-one-perfil">
												<option value="">Perfil da resposta</option>
												<option value="A">Afetivo</option>
												<option value="P">Pragmático</option>
												<option value="R">Racional</option>
												<option value="V">Visionário</option>
											</select>
											<button class="uk-button uk-button-default" type="button" tabindex="-1">
												<span></span>
												<span uk-icon="icon: chevron-down"></span>
											</button>
										</div>
									</div>
								</div>
								<div class="uk-margin uk-flex uk-flex-row">
									<input class="uk-input uk-form-width-large answer-two" type="text" placeholder="Resposta #2">
									<div class="uk-margin-left">
										<div uk-form-custom="target: > * > span:first-child">
											<select class="answer-two-perfil">
												<option value="">Perfil da resposta</option>
												<option value="A">Afetivo</option>
												<option value="P">Pragmático</option>
												<option value="R">Racional</option>
												<option value="V">Visionário</option>
											</select>
											<button class="uk-button uk-button-default" type="button" tabindex="-1">
												<span></span>
												<span uk-icon="icon: chevron-down"></span>
											</button>
										</div>
									</div>
								</div>
								<div class="uk-margin uk-flex uk-flex-row">
									<input class="uk-input uk-form-width-large answer-three" type="text" placeholder="Resposta #3">
									<div class="uk-margin-left">
										<div uk-form-custom="target: > * > span:first-child">
											<select class="answer-three-perfil">
												<option value="">Perfil da resposta</option>
												<option value="A">Afetivo</option>
												<option value="P">Pragmático</option>
												<option value="R">Racional</option>
												<option value="V">Visionário</option>
											</select>
											<button class="uk-button uk-button-default" type="button" tabindex="-1">
												<span></span>
												<span uk-icon="icon: chevron-down"></span>
											</button>
										</div>
									</div>
								</div>
								<div class="uk-margin uk-flex uk-flex-row">
									<input class="uk-input uk-form-width-large answer-four" type="text" placeholder="Resposta #4">
									<div class="uk-margin-left">
										<div uk-form-custom="target: > * > span:first-child">
											<select class="answer-four-perfil">
												<option value="">Perfil da resposta</option>
												<option value="A">Afetivo</option>
												<option value="P">Pragmático</option>
												<option value="R">Racional</option>
												<option value="V">Visionário</option>
											</select>
											<button class="uk-button uk-button-default" type="button" tabindex="-1">
												<span></span>
												<span uk-icon="icon: chevron-down"></span>
											</button>
										</div>
									</div>
								</div>
							</div>

						</div>

						<div class="uk-card-footer">
							<button class="uk-button uk-button-primary" type="button" onclick="saveQuestionAndAnswer('${ajaxUrl}', jQuery(this))">Adicionar Pergunta</button>
						</div>

					</div>`;
	
	jQuery('#container-questions').append(template);
}

/**
 * Save question and answers
 * 
 * @since 1.0.0
 */
function saveQuestionAndAnswer(ajaxUrl, el)
{
	'use strict';
	var wrapperQuestion		= el.parent().siblings('.wrapper-question'),
		headerTitle 		= el.parent().siblings('.uk-card-header').find('h4');

	// console.log(wrapperQuestion);
	var questionTitle 		= wrapperQuestion.find('.question-title').val(),
		answerOne 			= wrapperQuestion.find('.answer-one').val(),
		answerTwo 			= wrapperQuestion.find('.answer-two').val(),
		answerThree 		= wrapperQuestion.find('.answer-three').val(),
		answerFour 			= wrapperQuestion.find('.answer-four').val();

	var perfilOne 		= wrapperQuestion.find('.answer-one-perfil').val(),
		perfilTwo 		= wrapperQuestion.find('.answer-two-perfil').val(),
		perfilThree 	= wrapperQuestion.find('.answer-three-perfil').val(),
		perfilFour 		= wrapperQuestion.find('.answer-four-perfil').val();

	var newQuestion 		= questionTitle,
		newAnswers 			= answerOne + '>>' + perfilOne + '||' + answerTwo + '>>' + perfilTwo +  '||' + answerThree + '>>' + perfilThree + '||' + answerFour + '>>' + perfilFour;

	var quizId 				= jQuery('#quiz-id').text();

	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'ucqpt_create_question_and_answers',
			question: newQuestion,
			answers: newAnswers,
			quizId: quizId
		},
	}).done(function(res){
		el.parent().parent('.uk-card').attr('data-question-id', res);
		el.parent().siblings('.uk-card-body').hide();
		el.parent().hide();
		el.parent().siblings('.uk-card-header').find('.ucqpt-actions').show();

		headerTitle.text(questionTitle);
	});
}


function getCheckVal()
{
	'use strict';
	var checkVal = jQuery('#test-check').is(':checked');

	console.log(checkVal);
}

/**
 * Submit company data for register in back-end
 * @param {*} ajaxUrl
 * 
 * @since 1.1.0
 */
function submitCompanyData(ajaxUrl)
{
	'use strict';

	var companyName 	= jQuery('#ucqpt_company_name').val(),
		companyEmail 	= jQuery('#ucqpt_company_email').val(),
		companyPass		= jQuery('#ucqpt_company_pass').val(),
		companyTel 		= jQuery('#ucqpt_company_phone').val(),
		companyCnpj 	= jQuery('#ucqpt_company_cnpj').val(),
		companyVouchers = jQuery('#ucqpt_company_vouchers').val();

	var dataToSend = {
		action: 'ucqpt_register_company',
		name: companyName,
		email: companyEmail,
		pass: companyPass,
		tel: companyTel,
		cnpj: companyCnpj,
		vouchers: companyVouchers
	};

	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: dataToSend,
	})
	.done( function(res) {

		if (res == 'error') {
			UIkit.notification({ message: '<span uk-icon=\'icon: close\'></span> Erro ao registrar usuário. Tente novamente.', pos: 'bottom-center', status:'danger' })
		}

		if (res == 'success') {
			UIkit.notification({ message: '<span uk-icon=\'icon: check\'></span> Usuário registrado com sucesso!', pos: 'bottom-center', status:'success' })
			UIkit.modal('#register-company').hide();
			document.getElementById('form-company').reset();

			jQuery('#table-companies').children('tbody').append('<tr><td>'+ companyName +'</td><td>'+ companyVouchers +'</td><td>0</td><td>'+ companyVouchers +'</td></tr>');
		}
	});

}

/**
 * Generate new code and replace the current
 * 
 * @param {*} ajaxUrl
 * 
 * @since 1.1.0
 */
function generateCodVoucher(ajaxUrl)
{
	'use strict';
	var dataSend = {
		action: 'ucqpt_generate_voucher_code',
	};

	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: dataSend,
	})
	.done( function (res) {
		// console.log(res);
		var response = res; 

		if (response == 'success') {
			UIkit.notification({ message: '<span uk-icon=\'icon: check\'></span> Usuário registrado com sucesso!', pos: 'bottom-center', status:'success' });
			document.getElementById('form-company').reset();
		} else {

		}
		jQuery('#ucqpt_company_voucher').val(res);
	})
}


/**
 * Sumit code and company id to create a new voucher
 * 
 * @param {*} ajaxUrl
 * 
 * @since 1.1.0
 */
function createNewVoucher(ajaxUrl)
{
	'use strict';

	var code 		= jQuery('#ucqpt_company_voucher').val(),
		ciaId 		= jQuery('#ucqpt_company_selected').val(),
		userName 	= jQuery('#ucqpt_customer_name').val(),
		userEmail 	= jQuery('#ucqpt_customer_email').val(),
		userTel 	= jQuery('#ucqpt_customer_cpf').val(),
		userCpf 	= jQuery('#ucqpt_customer_tel').val();
	// Add validação dos campos

	var dataSend = {
		action: 'ucqpt_create_voucher',
		voucherCode: code,
		companyId: ciaId,
		userName: userName,
		userEmail: userEmail,
		userTel: userTel,
		userCpf: userCpf
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
			UIkit.notification({ message: '<span uk-icon=\'icon: check\'></span> Voucher registrado com sucesso! Cód: '+ res, pos: 'bottom-center', status:'success' });
			document.getElementById('form-voucher').reset();
		}
	});
}

function setShowHide(ajaxUrl, el)
{
	
	var questionId = el.parent().parent().parent().parent().parent('.uk-card').attr('data-question-id');
	var showQuestion = '';

	if( el.is(':checked') ) {
		showQuestion = 'no';
	} else {
		showQuestion = 'yes';
	}
	
	var dataSend = {
		action: 'ucqpt_switch_show_question',
		qId: questionId,
		show: showQuestion
	};

	jQuery.ajax({
		url: ajaxUrl,
		method: 'POST',
		data: dataSend
	}).done(function(res)
	{
		if ( res == 'success' ) {
			if ( showQuestion == 'yes' ) {
				UIkit.notification("<span uk-icon='icon: check'></span> A pergunta foi Habilitada!", {pos: 'bottom-center'});
			} else {
				UIkit.notification("<span uk-icon='icon: ban'></span> A pergunta foi Desabilitada!", {pos: 'bottom-center'});
			}
		} else {
			UIkit.notification("<span uk-icon='icon: close'></span> Houve um erro. Tente novamente", {pos: 'bottom-center'});
		}
	});
	
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
 * Setar voucher id no modal de resultado
 * @param {*} voucherId
 * @param {*} voucherCode 
 */
function setVoucherIdOnResultModal(voucherId, voucherCode, ajaxUrl)
{
	'use strict';

	jQuery('#result-voucher').attr('data-voucher', voucherId);
	jQuery('#result-voucher').find('.uk-modal-title').text('Voucher: ' + voucherCode);

	var dataSend = {
		action: 'ucqpt_get_used_voucher_data',
		voucherId: voucherId,
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
			// UIkit.notification({ message: '<span uk-icon=\'icon: check\'></span> Os dados foram salvos com sucesso!', pos: 'bottom-center', status:'success' });
			// document.getElementById('edit-voucher').reset();
			jQuery('#result-voucher').find('.result').html(res);
		}
	});
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