<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		Basics\Site::parameter('private_emails', isset($_POST['private_emails']) ? true : 0);
		Basics\Site::parameter('url_rewriting', isset($_POST['url_rewriting']) ? true : 0);
		if (isset($_POST['default_language']))
			Basics\Site::parameter('default_language', $_POST['default_language']);
		if (isset($_POST['default_users_type']))
			Basics\Site::parameter('default_users_type', $_POST['default_users_type']);

		if (isset($_POST['name'])) {
			die('Can\'t be changed');
			Basics\Site::parameter('name', $_POST['name']);

			// TODO: Fix bug with cookies.
		}

		header('Refresh: 0');
	}

	$languages = \Basics\Languages::getLanguages();
	$membersTypes = \Members\Types::getTypes();

	$pageTitle = $clauses->get('config');
	$viewPath = 'configuration';
