<?php
	$memberObj = new Members\Single(Basics\Handling::idFromSlug($params[1], 'members'));
	$member = $memberObj->getMember();
	$currentMemberObj = (new Members\Single($currentMemberId));

	if (empty($member) OR !$currentMemberId)
		$abort = true;
	elseif ($params[2] === 'request') {
		$currentMemberObj->sendFriendRequest($member['id']);
	}
	elseif ($params[2] === 'accept') {
		$currentMemberObj->acceptFriendRequest($member['id']);
	}
	elseif ($params[2] === 'decline' OR $params[2] === 'cancel') {
		$currentMemberObj->cancelFriendRequest($member['id']);
	}
	else
		$abort = true;

	if (isset($abort) AND $abort === true)
		error();
	else
		header('Location: ' . $linksDir . 'members/' . $member['slug'] . '/');