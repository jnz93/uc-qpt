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
function createNewQuiz(ajaxUrl)
{
	'use strict';
	var quizName = jQuery('#ucqpt_test_name').val(),
		quizDescription = jQuery('#ucqpt_test_description').val();

	var dataToSend = quizName + '||' + quizDescription;

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
			jQuery('.uk-modal-body').children('form').fadeOut();
			// Add loading
			jQuery('.uk-modal-body').append('<div id="spinner" uk-spinner></div>');
		},
		success: function(res)
		{
			UIkit.notification({message: '<span uk-icon=\'icon: check\'></span> <b>'+ quizName +'</b> criado com sucesso!', status: 'success', pos: 'bottom-center'});
			console.log(res);
		},
		error: function(res)
		{
			UIkit.notification({message: '<span uk-icon=\'icon: close\'></span> Houve um problema com o cadastro. Tente novamente! <br /> Err: ' + res, status: 'error', pos: 'bottom-center'});
		},
		complete: function()
		{
			// Esconder loading
			jQuery('#spinner').fadeOut();
			jQuery('.uk-modal-body').children('.wrapper-new-question').fadeIn();
		}
	}).done(function(res) {
		// mostrar título, descrição e botão atualizar teste
		jQuery('.uk-modal-title').text(quizName);
		jQuery('.uk-modal-title').siblings('p').text(quizDescription);
		jQuery('.uk-modal-title').siblings('p').after(res);
		jQuery('#test-update').fadeIn();
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
						<div class="uk-card-header">
							<h3 class="">Nova pergunta</h3>
						</div>
						
						<!-- Wrapper body -->
						<div class="wrapper-question uk-card-body">
							<div class="uk-width-1-1">
								<div class="uk-margin">
									<input class="uk-input question-title" type="text" placeholder="Insira a pergunta">
								</div>

								<div class="uk-margin">
									<textarea class="question-description" cols="" rows="" style="width: 100%">Adicione uma descrição</textarea>
								</div>

								<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid" style="display: none;">
									<label><input class="uk-checkbox" id="disable-question" type="checkbox"> Desabilitar pergunta</label>
								</div>
							</div>

							<!-- Respostas -->
							<div class="uk-width-1-2">
								<h4 class="">Respostas</h4>
								<div class="uk-margin">
									<input class="uk-input answer-one" type="text" placeholder="Resposta #1">
									<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
										<label><input class="uk-checkbox answer-one-check" type="checkbox"> Resposta correta?</label>
									</div>
								</div>
								<div class="uk-margin">
									<input class="uk-input answer-two" type="text" placeholder="Resposta #2">
									<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
										<label><input class="uk-checkbox answer-two-check" type="checkbox"> Resposta correta?</label>
									</div>
								</div>
								<div class="uk-margin">
									<input class="uk-input answer-three" type="text" placeholder="Resposta #3">
									<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
										<label><input class="uk-checkbox answer-three-check" type="checkbox"> Resposta correta?</label>
									</div>
								</div>
								<div class="uk-margin">
									<input class="uk-input answer-four" type="text" placeholder="Resposta #4">
									<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
										<label><input class="uk-checkbox answer-four-check" type="checkbox"> Resposta correta?</label>
									</div>
								</div>
							</div>

						</div>

						<div class="uk-card-footer">
							<button class="uk-button uk-button-primary" type="button" onclick="saveQuestionAndAnswer('${ajaxUrl}', jQuery(this))">Adicionar Pergunta</button>
							<button class="uk-button" type="button" onclick="getCheckVal()">Check Val</button>
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
		headerTitle 		= el.parent().siblings('.uk-card-header').children('h3');

	// console.log(wrapperQuestion);
	var questionTitle 		= wrapperQuestion.find('.question-title').val(),
		questionDescription = wrapperQuestion.find('.question-description').val(),
		answerOne 			= wrapperQuestion.find('.answer-one').val(),
		answerTwo 			= wrapperQuestion.find('.answer-two').val(),
		answerThree 		= wrapperQuestion.find('.answer-three').val(),
		answerFour 			= wrapperQuestion.find('.answer-four').val();

	var isCorrectOne 		= wrapperQuestion.find('.answer-one-check').is(':checked'),
		isCorrectTwo 		= wrapperQuestion.find('.answer-two-check').is(':checked'),
		isCorrectThree 		= wrapperQuestion.find('.answer-three-check').is(':checked'),
		isCorrectFour 		= wrapperQuestion.find('.answer-four-check').is(':checked');

	var newQuestion 		= questionTitle + '||' + questionDescription,
		newAnswers 			= answerOne + '>>' + isCorrectOne + '||' + answerTwo + '>>' + isCorrectTwo +  '||' + answerThree + '>>' + isCorrectThree + '||' + answerFour + '>>' + isCorrectFour;

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
		success: function (res) {
			console.log(res);
		}
	}).done(function(){
		console.log(el.parent().parent());
		el.parent().parent().css({'overflow': 'hidden', 'height' : '75px'});
		headerTitle.text(questionTitle);
	});
}


function getCheckVal()
{
	'use strict';
	var checkVal = jQuery('#test-check').is(':checked');

	console.log(checkVal);
}