$(function() {
	$('img').lazyload({
		effect: 'fadeIn'
	});

	$('body').on('hidden.bs.modal', '.modal', function() {
		$(this).removeData('bs.modal');
	});
	$('#login-btn').click(function() {
		$('#login').modal({
			remote: this.attr(href)
		}, 'show');
	});

	var sideSlider = $('[data-toggle=collapse-side]'),
		sel = sideSlider.attr('data-target'),
		sel2 = sideSlider.attr('data-target-2');
	sideSlider.click(function(event) {
		$(sel).toggleClass('in');
		$(sel2).toggleClass('out');
	});

	$('#headlines-carousel').carousel({
		interval: 2500
	});

	$('button.vote-btn').parent().on('click', 'button.vote-btn', function() {
		var id = $(this).attr('data-id'),
			type = $(this).attr('data-type'),
			voteState = $(this).attr('value'),
			posting = $.post(linksDir + 'votes/' + type + '/' + id, {
				'vote_state': voteState
			});

		posting.done(function(data) {
			var decodeddata = JSON.parse(data);
			btnSelector = 'button.vote-btn[data-id=' + id + '][data-type=' + type + ']'; // Not perfect but does the trick

			if (voteState === 'strip')
				$(btnSelector + '[value=' + voteState + ']').remove();

			$(btnSelector).each(function() {
				var btnState = $(this).attr('value');

				if (btnState !== 'strip') {
					$('span.votes-nbr', this).text(decodeddata[btnState]);

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
});
