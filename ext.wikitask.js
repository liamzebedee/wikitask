/*
 * Interactive task management without going to Special:ManageTasks. 
 * 
 * When a task is clicked, a dialog is shown aside:
 *  - complete
 *  - delete
 *
 * An add task box is shown at the bottom of the task list:
 *  - when clicked, it will add a new row above and
 *    add additional controls to the add task row
 *
 * An AJAX based category search is implemented. 
 */

var wikitask = {
	taskEditDialog: "<button id='done'>Complete/Remove</button><button id='edit'>Edit</button>",
};

(function($,mw){$(document).ready(function(){

	$('.wikitask-tasks').data('editToken', mw.user.tokens.get('editToken'));
	$(".wikitask-tasks #newtask").hide();
	
	$(".wikitask-tasks #addtask").live("click", function() {
		$(this).parent().find('#newtask').toggle();
	});
	
	$(".wikitask-tasks #newtask #cat input, .wikitask-tasks #newtask #type input").keyup(function(e) {
		if((e.keyCode == 38) || (e.keyCode == 40)) {
			var resultsDialog = $('#wikitask-search-results');
			var results = $('.suggestions-result', resultsDialog);
			var x = 0;
			var cnt = 0;
			results.each(function(i) {
				cnt++;
				if(!$(this).hasClass('suggestions-result-current')) return;
				$(this).removeClass('suggestions-result-current');
				
				if(e.keyCode == 38) { 
					// up
					x = i - 1;
				} else {
					// down
					x = i + 1;
				}
			});
			x = x % cnt;
			results.each(function(i) {
				if(i == x) {
					$(this).addClass('suggestions-result-current');
				}
			});
			
			// XXX this algorithm is of O(2n) efficiency. It can be O(1). 
			
			return;
		} else if (e.keyCode == 13) { // enter
			$(this).attr('value', $('#wikitask-search-results .suggestions-result-current').text());
			$('#wikitask-search-results').fadeOut('fast', function(){ $(this).remove() });
			return;
		}	
		
		var self = this;
		var api = new mw.Api();
		api.post({
			action: 'opensearch',
			limit: 10,
			namespace: 14, // constant for category namespace
			search: $(this).val()
		}).done(function(data) {
			$('#wikitask-search-results').remove();
			if(data[1].length < 1) {
				return;
			}
			
			var resultsDialog = $('<aside>')
				.addClass('suggestions-results')
				.css('left', $(self).parent().offset().left)
				.css('top', $(self).parent().offset().top + $(self).height()) // Just below textbox
				.attr('id', 'wikitask-search-results');
			
        	for (var i = 0; i < data[1].length; i++) { // data[1] contains the results
				var result = $('<a>')
					.prepend($('<div>').addClass('suggestions-result').prepend(data[1][i].replace(/^Category:/, '')))
					.addClass('mw-searchSuggest-link');
				result.hover(function(){
					$('.suggestions-result', resultsDialog).removeClass('suggestions-result-current');
					$('div', this).addClass('suggestions-result-current');
				});
				/*
				// this was broken by the "#newtask #cat input".onblur code below
				// XXX fix this for mouse-based users
				
				result.live("click", function(){
					self.attr('value', $(this).text());
					resultsDialog.fadeOut('fast', function(){ $(this).remove() });
				});
				*/
				resultsDialog.prepend(result);
			}
			
			$('.suggestions-result', resultsDialog).first().addClass('suggestions-result-current');
			
			$('body').prepend(resultsDialog);
			resultsDialog.fadeIn('fast');
		});
	});
	
	$(".wikitask-tasks #newtask #cat input, .wikitask-tasks #newtask #type input").blur(function() {
		$('#wikitask-search-results').remove();
	});
	
	$(".wikitask-tasks #newtask #controls").bind('resetNewTaskForm', function() {
		// Reset fields
		$(':input', $(this).closest('#newtask')).not(':button, :submit, :reset, :hidden').val('');
	});
	
	$(".wikitask-tasks #newtask #controls #cancel").click(function() {
		$(this).parent().trigger('resetNewTaskForm');
	});
	
	$(".wikitask-tasks #newtask #controls #submit").click(function() {
		var self = this;
		var newtask = $(this).closest('#newtask');
		var cat = $('#cat input', newtask).val();
		var type = $('#type input', newtask).val();
		var title = $('#title', newtask).val();
		var daysTillDue = $('#daysTillDue', newtask).val();
		
		if ((!cat) || (!type) || (!title) || (!daysTillDue)) {
			alert('Fields must be set.');
			return;
		}
		
		$.ajax({
			type: "POST",
			url: mw.util.wikiScript('api'),
			data: {
				'action': 'put.task', 
				'format': 'json', 
				'cat': cat,
				'type': type,
				'title': title,
				'daysTillDue': daysTillDue,
				'wpEditToken': newtask.parent().data('editToken')
			},
			dataType: 'json',
		}).done(function(data){
			$(self).parent().trigger('resetNewTaskForm');
		});
	});
	
	
	$(".wikitask-tasks .task").click(function() {
		var dialog = $('.wikitask-dialog');
		if( $(this).data('task-id') === dialog.data('selectTaskID') ) {
			dialog.fadeOut('fast', function() { $(this).remove(); });
			return;
		}
		dialog.fadeOut('fast', function() { $(this).remove(); });
		dialog = $('<aside>').addClass('wikitask-dialog')
							 .html(wikitask.taskEditDialog)
							 .css('left', $(this).position().left);
		$(this).after(dialog);
		$(dialog).data('selectTaskID', $(this).data('task-id'));
		dialog.fadeIn('fast');
	});
	
	$('.wikitask-dialog #done').live("click", function() {
		
		// $(this).parent().data('selectTaskID')
		$(this).parent().fadeOut('fast', function() { $(this).remove(); });
	});
	
	$('.wikitask-dialog #edit').live("click", function() {
		// Just do a redirect
		$(this).parent().fadeOut('fast', function() { $(this).remove(); });
	});
	
});})(window.jQuery,window.mediaWiki);
