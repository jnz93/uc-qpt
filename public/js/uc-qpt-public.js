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


function submitAnswers(ajaxUrl, el)
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
			data: checkedAnswers
		},
	}).done(function(res){
		console.log(res);
		jQuery('.wrapper-result').html(res);
	});
	// }
}