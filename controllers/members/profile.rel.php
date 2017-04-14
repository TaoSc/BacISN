<?php
	$memberObj = new Members\Single(Basics\Handling::idFromSlug($params[1], 'members'));
	$member = $memberObj->getMember();

	if (empty($member))
		error();
	else {
		$isFriend = $memberObj->befriend($currentMemberId);
		$requestPending = $memberObj->requestPending($currentMemberId, true);
		$requestPendingFromThem = $memberObj->requestPending($currentMemberId);
		$requestPendingFromYou = (new Members\Single($currentMemberId))->requestPending($member['id']);

		$pageTitle = $clauses->get('o_quote') . $member['nickname'] . $clauses->get('c_quote') . ' - ' . $clauses->get('members');
		$viewPath = 'members/profile.rel';
		$breadcrumb = [
			['name' => 'members'],
			['name' => $clauses->get('o_quote') . $member['nickname'] . $clauses->get('c_quote')]
		];
	}
