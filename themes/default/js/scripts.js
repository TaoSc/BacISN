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

	// Friends search
	$('#search-box').on('keyup', function() {
		var query = $(this).val(),
			url = linksDir + 'members/search';

		if (query.length > 0) {
			$.ajax({
				type: 'POST',
				url: url,
				data: {query: query},
				dataType: 'html',
				success: function(data) {
					$("#display-results").html(data).show();
				}
			});
		}
	});

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
	$('.chat[data-chat=person1]').addClass('active-chat');
	$('.person[data-chat=person1]').addClass('active');

	$('.people-list .people .person').mousedown(function() {
		if ($(this).hasClass('.active')) {
			return false;
		} else {
			var findChat = $(this).attr('data-chat');

			$('.chat').removeClass('active-chat');
			$('.chat[data-chat = ' + findChat + ']').addClass('active-chat');

			$('.people-list .people .person').removeClass('active');
			$(this).addClass('active');
		}
	});

	// Gather Messages
	/*var url = "tchatAjax.php",
		memberId = 0,
		timer = setInterval(getMessages, 5000);

	function getMessages() {
		$.post(url, {
				action: "getMessages",
				memberId: memberId
			}, function(data) {
				if (data.error === "ok") {
					$("#tchat").append(data.result);
					memberId = data.memberId;
				} else {
					alert(data.error);
				}
			},
			"json");
		return false;
	};*/

	// Send Input on Enter
	function sendMessage(id) {
		var currentChatBox = '.chat[data-id=' + id + ']',
			value = $(currentChatBox + ' #message-to-send-' + id).val(),
			postDate = 'Now',
			messageBlock = '<li class="clearfix"><div class="message-status align-right"><span class="message-data-time">' + postDate + '</span></div><div class="message other-message pull-right">' + value + '</div></li>';

		if (!value)
			return false;

		$(currentChatBox + ' > .chat-history ul').append(messageBlock);
		$(currentChatBox + ' #message-to-send-' + id).val('');

		var posting = $.post(linksDir + 'messages/' + id, {
			'content': value
		});

		posting.done(function(data) {
			$(currentChatBox + ' > .chat-history ul').html(data);
			return false;
		});
	}

	$('.button-send').parent().on('click', '.button-send', function() {
		sendMessage($(this).parent().parent().attr('data-id'));
		return false;
	});
});
