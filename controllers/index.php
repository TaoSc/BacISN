<?php
	if ($currentMemberId) {
		$friends = (new Members\Single($currentMemberId))->getFriends();
		$i = 1;
	}

	$pageTitle = $clauses->get('home');
	$viewPath = 'index';
	$breadcrumb = [['name' => 'home']];
