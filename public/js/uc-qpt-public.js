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
	// console.log(ajaxUrl);
	// console.log(el);

	var checkedAnswers 	= [],
		questionIds 	= [];
	el.each(function (index)
	{
		// console.log(jQuery(this).find('input[type=radio]'));
		jQuery(this).find('input[type=radio]').each(function (index)
		{
			var currAnswer = jQuery(this);
			if (currAnswer.is(':checked')){
				checkedAnswers.push(currAnswer.attr('data-id'));
			}
		});

		// console.log(checkedAnswers);
		questionIds.push(el.attr('data-id'));
	});

	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'ucqpt_submit_quiz',
			answers: checkedAnswers,
			questionIds: questionIds
		},
	}).done(function(res){
		console.log(res);
		jQuery('.wrapper-result').html(res);
	})
}