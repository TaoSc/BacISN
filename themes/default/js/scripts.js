$(function() {
	$('button.vote-btn').parent().on('click', 'button.vote-btn', function() {
		var id = $(this).attr('data-id'),
			type = $(this).attr('data-type'),
			voteState = $(this).attr('value'),
			posting = $.post(linksDir + 'votes/' + type + '/' + id, {
				'vote_state': voteState
			});

		posting.done(function(data) {
			var decodedData = JSON.parse(data);
			btnSelector = 'button.vote-btn[data-id=' + id + '][data-type=' + type + ']'; // Not perfect but does the trick

			if (voteState === 'strip')
				$(btnSelector + '[value=' + voteState + ']').remove();

			$(btnSelector).each(function() {
				var btnState = $(this).attr('value');

				if (btnState !== 'strip') {
					$('span.votes-nbr', this).text(decodedData[btnState]);

					$(this).prop('disabled', function(index, value) {
						return !value;
					});
				}
			});
		});

		return false;
	});

	if ($('#comments').html()) {
		var commentCreateLabel = $('label[for=content]').text(),
			commentsLocation = $('input#location').attr('value'),
			commentsLatestLink = null;

		$('#comments').parent().on('click', '.comments-toolbox a', function() {
			actualLink = $(this).attr('href');
			if (commentsLatestLink !== actualLink) {
				var get = $.get(actualLink);
				get.done(function(data) {
					commentsLatestLink = actualLink;
					$('#comments').replaceWith(data);
					$('#comments input#location').attr('value', commentsLocation);
					$('.comments-list img').lazyload({
						effect: 'fadeIn'
					});
				});
			}

			return false;
		});

		$('#comments').parent().on('click', 'button.answer-btn', function() {
			var commentId = $(this).attr('value');

			$('label[for=content]').text($(this).text());
			$('textarea#content').focus();
			$('#parent_id').attr('value', $(this).attr('value'));
			if ($('#cancel-reply').css('display') === 'none')
				$('#cancel-reply').show();

			return false;
		});

		$('#comments').parent().on('click', '.comment-create #cancel-reply', function() {
			$('label[for=content]').text(commentCreateLabel);
			$('textarea[name=content]').focus();
			$('#parent_id').attr('value', 0);
			$('#cancel-reply').hide();

			return false;
		});
	}

	// Toggle Sidebar
	$('.chat-header i.fa-bars').click(function() {
		$('.people-list').toggleClass('sidebar-visible');
	});

	// Search for the Left Sidebar
	var searchFilter = {
		options: {
			valueNames: ['name']
		},
		init: function() {
			var userList = new List('people-list', this.options);
			var noItems = $('<li id="no-items-found">No items found</li>');

			userList.on('updated', function(list) {
				if (list.matchingItems.length === 0) {
					$(list.list).append(noItems);
				} else {
					noItems.detach();
				}
			});
		}
	};
	searchFilter.init();

	// People Content
	$('.chat[data-chat=person2]').addClass('active-chat');
	$('.person[data-chat=person2]').addClass('active');

	$('.people-list .people .person').mousedown(function() {
		if ($(this).hasClass('.active')) {
			return false;
		} else {
			var findChat = $(this).attr('data-chat');
			var personName = $(this).find('.name').text();
			$('.chat-about .chat-with').html(personName);
			$('.chat').removeClass('active-chat');
			$('.people-list .people .person').removeClass('active');
			$(this).addClass('active');
			$('.chat[data-chat = ' + findChat + ']').addClass('active-chat');
		}
	});

	// Send Input on Enter
	$('#message-to-send').keydown(function(event) {
		if (event.keyCode == 13) {
			alert('enter key pressed');
		}
	});

	/*$('.button-send').click(
		function() {
			
		}

	);*/

	$('#message').on('click', '.button-send', function() {
		alert('button pressed');
		$('.chat-history ul').append($('.message-to-send').text());
		
	});
});
