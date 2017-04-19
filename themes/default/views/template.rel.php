<!DOCTYPE html>
<html lang="en">

<head>

	<?php \Basics\Templates::basicHeaders(); ?>

	<title><?php echo $siteName; ?> | <?php echo $pageTitle; ?></title>

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

				<ul class="nav navbar-nav lang-selector pull-right">
					<li class="dropdown">
						<a data-toggle="dropdown" href="#null"><?php echo $activeLang['country_name']; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
<?php
							foreach ($languagesList as $languageLoop)
								echo '<li>
									<a href="' . $linksDir . 'lang/' . $languageLoop['code'] . '/' . str_replace('%', '=', urlencode($location)) . '">
										<span class="sprites ' . $languageLoop['code'] . ' flag"></span>' . $languageLoop['name'] . '
									</a>
								</li>';
?>
						</ul>
					</li>
				</ul>

<?php
				if ($currentMemberId) {
?>

				<ul class="nav navbar-nav notification pull-right">
					<li class="dropdown">
						<a data-toggle="dropdown" href="#null"><i class="fa fa-bell"></i> <?php echo (isset($notificationsCount) AND $notificationsCount > 0 )? '(' . $notificationsCount . ')' : '';?></a>
						<ul class="dropdown-menu">

						</ul>
					</li>
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
<?php
				}
?>
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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
	<script src="<?php echo $subDir . $theme['dir']; ?>js/scripts.js"></script>
</body>

</html>
