<?php
	$activeLang = $clauses->getLanguage();
	$languagesList = Basics\Languages::getLanguages('code != \'' . $activeLang['code'] . '\' AND enabled = true', $activeLang['code']);

	if ($admin) {
		$navigation = [
			['caption' => $clauses->get('home'),			'link' => 'admin/index'],
			['caption' => $clauses->get('members'),			'link' => 'admin/members/index'],
			['caption' => $clauses->get('members_types'),	'link' => 'admin/members-types/index'],
			['caption' => $clauses->get('config'),			'link' => 'admin/configuration']
		];
	}
	else {
		$navigation = [
			['caption' => $clauses->get('home'), 'link' => 'index', 'icon' => 'home']
		];

		if ($currentMemberId) {
			$navigation = array_merge($navigation, [['caption' => $clauses->get('profile'), 'link' => 'members/' . $currentMember['slug'] . '/', 'icon' => 'user']]);

			if ($rights['admin_access'])
				$navigation = array_merge($navigation, [['caption' => $clauses->get('admin'), 'link' => 'admin/', 'icon' => 'cog']]);

			$navigationRight = [
				['caption' => $clauses->get('log_out'), 'link' => 'members/logout/' . str_replace('%', '=', urlencode($location)), 'icon' => 'log-out']
			];

			$currentMemberObj = new Members\Single($currentMemberId);
			$notificationsCount = $currentMemberObj->notificationsCount();
			$notifications = $currentMemberObj->getNotifications();
		}
		else {
			$navigation = array_merge($navigation, [
				['caption' => $clauses->get('log_in'),		'link' => 'members/login/' . str_replace('%', '=', urlencode($location)),	'icon' => ''],
				['caption' => $clauses->get('register'),	'link' => 'members/registration',											'icon' => '']
			]);

			$navigationRight = [];
		}
	}

	if (isset($breadcrumb)) {
		foreach ($breadcrumb as $key => $helperElem) {
			$name = $clauses->get($helperElem['name']);
			if (!empty($name))
				$breadcrumb[$key]['name'] = $name;
		}
	}

	include $siteDir . $theme['dir'] . 'views/template.rel.php';
