<!DOCTYPE html>
<html lang="en">

<head>

	<?php \Basics\Templates::basicHeaders(); ?>

	<title><?php echo $siteName; ?> | <?php echo $pageTitle; ?></title>

	<!-- <link href="https://bootswatch.com/superhero/bootstrap.min.css" rel="stylesheet"> -->
	<link href="<?php echo $subDir . $theme['dir']; ?>css/styles.css" rel="stylesheet">

</head>

<body>

	<nav class="navbar navbar-fixed-top">
		<div class="container">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $linksDir; ?>index"><?php echo $siteName; ?></a>
			</div>

			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
<?php
					foreach ($navigation as $item) {
						echo '<li';
						if (rtrim($item['link'], 'index') === rtrim($location, 'index'))
							echo ' class="active"';
						echo '><a href="' . $linksDir . $item['link'] . '" title="' . $item['caption'] . '">';
						if (!empty($item['icon']))
							echo '<span class="glyphicon glyphicon-' . $item['icon'] . '"></span>';
						elseif (!empty($item['caption']))
							echo '&nbsp;' . $item['caption'];
						echo '</a></li>' . PHP_EOL;
					}
?>
				</ul>


				<ul class="nav navbar-nav navbar-right">
<?php
					foreach ($navigationRight as $item) {
						echo '<li';
						if (rtrim($item['link'], 'index') === rtrim($location, 'index'))
							echo ' class="active"';
						echo '><a href="' . $linksDir . $item['link'] . '" title="' . $item['caption'] . '">';
						if (!empty($item['icon']))
							echo '<span class="glyphicon glyphicon-' . $item['icon'] . '"></span>';
						echo '</a></li>' . PHP_EOL;
					}
?>
				</ul>

				<form class="navbar-form navbar-right">
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="<?php echo $clauses->get('search_friends_placeholder'); ?>">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default" title="<?php echo $clauses->get('search'); ?>"><span class="glyphicon glyphicon-search"></span></button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!--/.nav-collapse -->

		</div>
	</nav>

	<div class="container" role="main">

		<?php include $viewPath; ?>

	</div>
	<!-- /.container -->

	<footer class="footer">
		<div class="container">
			<p><a href="//github.com/TaoSc/BacISN"><?php echo $clauses->get('source_code'); ?></a> - Licensed <a href="<?php echo $subDir; ?>LICENSE">GPL v3</a>.</p>
		</div>
	</footer>

	<script src='https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js'></script>
	<script>
//Toggle Sidebar
$('.chat-header i.fa-bars').click(function() {
	$('.people-list').toggleClass('sidebar-visible');
});

//Search for the Left Sidebar
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

//Send Input on Enter
$('#message-to-send').keydown(function(event) {
	if (event.keyCode == 13) {
		alert('enter key pressed');
	}
});
	</script>
</body>

</html>
