<?php
	// Basic configuration
	error_reporting(E_ALL);
	$siteDir = dirname(__FILE__) . '/';

	$configFile = $siteDir . 'config.inc.php';
	$ajaxCheck = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	session_start();
	mb_internal_encoding('UTF-8');

	// Classes auto-loading
	spl_autoload_register(function ($class) {
		global $siteDir;
		// echo $siteDir . 'models/' .  str_replace('\\', '/', $class) . '.class.php<br>' . PHP_EOL;
		if (file_exists($siteDir . 'models/' . str_replace('\\', '/', $class) . '.class.php'))
			require $siteDir . 'models/' . str_replace('\\', '/', $class) . '.class.php';
		elseif (file_exists($siteDir . 'models/' . str_replace('\\', '/', $class) . '.php'))
			require $siteDir . 'models/' . str_replace('\\', '/', $class) . '.php';
	});

	// System installation check
	if (file_exists($configFile))
		require $configFile;
	else {
		die('No configuration file found. Abort.');
	}

	// Database connection
	Basics\Site::getDB($dbHost, $dbName, $dbUser, $dbPass);

	// Variables related to the site
	$topDir = '/';
	$siteName = Basics\Site::parameter('name');

	if (!$topDir) {
		die('Missing key database parameters. Abort.');
	}

	// Language management
	if (!Basics\site::cookie('lang')) {
		Basics\site::cookie('lang', Basics\Site::parameter('default_language'));
		$language = Basics\Site::parameter('default_language');
	}
	else
		$language = Basics\site::cookie('lang');

	// Connected member management
	if (empty(Basics\site::session('member_id')) AND (Basics\site::cookie('name') AND Basics\site::cookie('password') AND !Basics\site::session('member')))
		Members\Handling::login(Basics\site::cookie('name'), Basics\site::cookie('password'));
	if (empty(Basics\site::session('member_id')))
		$currentMemberId = 0;
	else
		$currentMemberId = Basics\site::session('member_id');

	// Path handling
	if (mb_substr_count($_SERVER['REQUEST_URI'], '//'))
		die('Error while decoding the URI. Abort.');
	if (isset($_GET['location']) AND !empty($_GET['location'])) {
		$location = $_GET['location'];
		if (mb_substr($location, -1) === '/')
			$location .= 'index';
	}
	else
		$location = 'index';

	$foldersDepth = mb_substr_count($location, '/');
	if ($ajaxCheck AND isset($_SERVER['HTTP_REFERER']))
		$relativeFoldersDepth = mb_substr_count($_SERVER['HTTP_REFERER'], '/') - (mb_substr_count($topDir, '/') + 2);
	else
		$relativeFoldersDepth = &$foldersDepth;

	$params = explode('/', $location);
	if ($params[$foldersDepth] === '')
		unset($params[$foldersDepth]);

	if (Basics\Site::parameter('url_rewriting')) {
		if (!empty($_SERVER['QUERY_STRING']) AND mb_substr_count($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING']))
			header('Location: ' . $topDir);

		for ($i = 0, $subDir = null; $i < $relativeFoldersDepth; $i++)
			$subDir .= '../';
		if (empty($subDir))
			$subDir = './';
		$linksDir = &$subDir;
	}
	else {
		$subDir = './';
		$linksDir = $subDir . 'index.php?location=';
	}

	// Expensive inclusions
	if ($params[0] === 'admin' AND $foldersDepth !== 0) {
		include $siteDir . 'themes/admin/theme.php';
		$admin = true;
	}
	else {
		include $siteDir . 'themes/' . \Basics\Site::parameter('theme') . '/theme.php';
		$admin = false;
	}
	$theme['dir'] = 'themes/' . $theme['dir'];
	$clauses = new Basics\Languages($language);
	if ($currentMemberId) {
		if (!Basics\site::session('member'))
			Basics\site::session('member', (new Members\Single(Basics\site::session('member_id')))->getMember());
		$currentMember = Basics\site::session('member');
		$rights = (new Members\Type($currentMember['type']['id']))->getRights();
	}
	else
		$rights = (new Members\Type(3))->getRights();
	$CMSVersion = 'dev';

	// Errors management
	function error($errorMsg = 404, $showHomeBtn = true) {
		global $siteDir, $clauses, $theme, $language, $admin, $siteName, $location, $linksDir, $subDir, $currentMemberId, $rights, $currentMember;

		if ($errorMsg === 404)
			header('HTTP/1.0 404 Not Found');
		elseif ($errorMsg === 403)
			header('HTTP/1.0 403 Forbidden');

		if (is_int($errorMsg))
			$errorMsg = $clauses->get('error') . ' ' . $errorMsg . '.';

		include $siteDir . 'themes/' . \Basics\Site::parameter('theme') . '/theme.php';
		$admin = false;
		$theme['dir'] = 'themes/' . $theme['dir'];

		$pageTitle = $clauses->get('error');
		$viewPath = $siteDir . $theme['dir'] . 'views/error.rel.php';
		include $siteDir . 'controllers/template.rel.php';
		die();
	}

	// Routing
	$controllerPath = $siteDir . 'controllers/' . $location . '.php';

	if ($admin)
		include $siteDir . 'controllers/admin/routing.php';
	elseif (file_exists($controllerPath))
		include $controllerPath;
	elseif ($params[0] === 'lang' AND isset($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/lang.rel.php';

	elseif ($params[0] === 'messages' AND isset($params[1]) AND is_numeric($params[1]) AND $foldersDepth === 1)
		include $siteDir . 'controllers/messages/handling.ajax.php';

	elseif ($params[0] === 'members' AND isset($params[3]) AND $params[1] === 'login' AND $params[2] === 'ajax' AND $foldersDepth === 3)
		include $siteDir . 'controllers/members/login.ajax.php';
	elseif ($params[0] === 'members' AND $params[1] === 'search' AND $foldersDepth === 1)
		include $siteDir . 'controllers/members/search.ajax.php';
	elseif ($params[0] === 'members' AND isset($params[2]) AND ($params[1] === 'login' OR $params[1] === 'logout') AND $foldersDepth === 2)
		include $siteDir . 'controllers/members/' . $params[1] . '.php';
	elseif ($params[0] === 'members' AND isset($params[1]) AND $foldersDepth === 2 AND $params[2] === 'index')
		include $siteDir . 'controllers/members/profile.rel.php';
	elseif ($params[0] === 'members' AND isset($params[1]) AND isset($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/members/friendship.rel.php';


	elseif ($params[0] === 'votes' AND isset($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/votes/ajax.rel.php';

	else
		error();

	// Page display management
	if (isset($viewPath)) {
		$viewPath = $siteDir . $theme['dir'] . 'views/' . $viewPath . '.php';
		include $siteDir . 'controllers/template.rel.php';
	}
