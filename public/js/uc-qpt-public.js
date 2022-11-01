(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 */

    /**
     * Validação do voucher via ajax 
     * Esta etapa avalia apenas a existência do código voucher digitado
     * Caso ele seja válido um formulário de autenticação é retornado
     * 
     * @returns mixed
     */
    function validateVoucher(){
        let vCode = $('#ucqpt_voucher_code').val();
        if ( vCode.length < 10 ) {
            UIkit.notification( "<span uk-icon='icon: warning'></span> Voucher Inválido", {status:'danger', pos: 'bottom-right'} );
            return;
        }

        let payload = {
            action: 'validate_voucher',
            nonce: ajax.nonce,
            voucher: vCode
        }

        jQuery.ajax({
            url: ajax.url,
            type: 'POST',
            data: payload,
        })
        .done( function(res) {
            if( res ){
                jQuery('#form-wrapper').html(res);
            } else {
                UIkit.notification( "<span uk-icon='icon: warning'></span> Voucher Inválido", {status:'danger', pos: 'bottom-right'} );
            }
        });
    }


    $(document).ready( function(){
        //Validar voucher
        $('#validateVoucher').click( function(){
            validateVoucher();
        });
    });

})( jQuery );


/**
 * Marcação do peso selecionado
 * Esta função marca o peso da resposta selecionada e torna indisponível o peso igual em outra resposta
 * 
 */
function selectWeight(self){
    // console.log(self);
    var hasSelected = hasSelectedWeight(self);
    if( hasSelected == 1 ){
        return;
    }
    
    let answer = self.parent().parent().siblings();
    
    self.addClass('answerList__weightItem--selected');
    answer.addClass('answerList__answered');

    // Desabilitar itens "irmãos" e o mesmo valor em outras respostas
    disableWeights(self);

    // Habilitar botão "Próxima Pergunta"
    toggleNextButton();
    
}

/**
 * Verifica se algum peso já foi selecionado para aquela resposta
 * 
 * @param {*} element
 * 
 * @return bool
 */
function hasSelectedWeight( clickedItem ){
    var hasSelected = 0,
        items       = clickedItem.siblings();
    
    items.each( function(i){
        let item = jQuery(this);
    
        if( item.hasClass('answerList__weightItem--selected') || clickedItem.hasClass('answerList__weightItem--disabled') ){
            hasSelected = 1;
        }
    });

    return hasSelected;
}

/**
 * Desabilitar itens "irmãos" do peso selecionado
 * e também o mesmo valor em outras respostas
 * 
 * @param {*} element
 */
function disableWeights( element ){
    var siblings        = element.siblings(),
        weightSelected  = element.attr('data-value'),
        answers         = element.parents('.answerList__item').siblings();

    // Desabilitando pesos "irmãos" do item selecionado
    siblings.each( function(i){
        let item = jQuery(this);

        item.addClass('answerList__weightItem--disabled');
    });
    
    // Desabilitando pesos com mesmo valor em outras respostas
    answers.each( function(i){
        let aWeights = jQuery(this).find('.answerList__weightItem');
        aWeights.each( function(i){
            let item = jQuery(this);

            if( item.attr('data-value') == weightSelected ){
                item.addClass('answerList__weightItem--disabled');
            }
        });
    });
}


/**
 * Habilitar o botão de "próxima pergunta" quando todos os pesos forem selecionados
 * 
 */
function toggleNextButton(){
    var answers = jQuery('.answerList__weightItem--selected'),
        btnNext = jQuery('.btnNext'),
        btnFinish = jQuery('#btnFinish');

    if( answers.length % 4 == 0 ){
        btnNext.addClass('btnNext--enabled');
    } else {
        btnNext.removeClass('btnNext--enabled');
    }

    if( answers.length == 100 ){
        btnFinish.addClass('btnFinish--enabled');
        btnNext.removeClass('btnNext--enabled');
        console.info(answers.length);
        console.info('total = 100');
        console.log(btnFinish);
    }
}


/**
 * Esconder botão de "Próxima Pergunta" ao passar o slider.
 * 
 */
function listenerNextEvent(){
    addEventListener('itemshow', () => {
        var btnNext = jQuery('.btnNext');

        if(btnNext){
            btnNext.removeClass('btnNext--enabled');
        }

        // toggleNextQuestion();

    })
}

/**
 * Trocar título e número da próxima questão
 * 
 * @return mixed
 */
function authenticateVoucher(){
    let userName    = jQuery('#user_full_name').val(),
        userEmail   = jQuery('#user_email').val(),
        userPhone   = jQuery('#user_phone').val(),
        voucherId   = jQuery('#voucher_id').val(),
        quizId      = jQuery('#quiz_id').val();

    if(userName.length < 4 || userEmail < 4 || userPhone < 8 ){
        UIkit.notification( "<span uk-icon='icon: warning'></span> Preencha todas as informações para prosseguir.", {status:'danger', pos: 'bottom-right'} );
        return;
    }

    let payload = {
        action: 'authenticate_voucher',
        nonce: ajax.nonce,
        name: userName,
        email: userEmail,
        phone: userPhone,
        voucher: voucherId,
        quiz: quizId
    }

    jQuery.ajax({
        url: ajax.url,
        type: 'POST',
        data: payload,
    })
    .done( function(res) {
        console.log(res);
        if( res ){
            jQuery('#modal-content').html(res);
        } else {
            UIkit.notification( "<span uk-icon='icon: warning'></span> Voucher Inválido", {status:'danger', pos: 'bottom-right'} );
        }
    });
}



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
function setDataVoucherOnModal(voucherId, voucherCode, ajaxUrl)
{
	'use strict';

	jQuery('#edit-voucher').attr('data-voucher', voucherId);
	jQuery('#edit-voucher').find('.uk-modal-title').text('Editar Voucher: ' + voucherCode);
	
	var dataSend = {
		action: 'ucqpt_get_voucher_data',
		id: voucherId,
	}

	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: dataSend
	})
	.done( function (res) {
		var userData = JSON.parse(res);

		if (userData.name.length == 0 && userData.email.length == 0 && userData.doc.length == 0 && userData.tel.length == 0 ) {
			return;
		}
		
		jQuery('#ucqpt_customer_name').val(userData.name);
		jQuery('#ucqpt_customer_email').val(userData.email);
		jQuery('#ucqpt_customer_cpf').val(userData.doc);
		jQuery('#ucqpt_customer_tel').val(userData.tel);
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