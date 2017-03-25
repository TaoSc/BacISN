<?php
	$members = \Members\Handling::getMembers('TRUE');
	print_r($members);

	$btnsGroupMenu = [['link' => $linksDir . 'admin/members/0', 'name' => $clauses->get('arzar')]];

	$pageTitle = $clauses->get('members');
	$viewPath = 'members/index';
