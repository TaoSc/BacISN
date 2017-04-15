<?php
	$memberObj = (new Members\Single($params[2]));
	$member = $memberObj->getMember();

	if (!$member['edit_cond'])
		error();
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($memberObj->setMember($_POST['nickname'], $_POST['first_name'], $_POST['last_name'], $member['email'], $_POST['birth'], $_POST['password'], $member['type_id'], $_POST['img'])) {
			if ($currentMemberId == $member['id'])
				Basics\site::session('member', '');

			header('Refresh: 0');
		}
		else
			error($clauses->get('member_edit_fails'));
	}
	else {
		if (empty($member))
			error();
		else {
			$btnsGroupMenu[] = ['link' => $linksDir . 'members/' . $member['slug'] . '/', 'name' => $clauses->get('show_more')];
			if ($member['removal_cond'])
				$btnsGroupMenu[] = ['link' => $linksDir . 'admin/members/' . $member['id'] . '/delete', 'name' => $clauses->get('delete'), 'type' => 'warning'];

			$pageTitle = $clauses->get('o_quote') . $member['nickname'] . $clauses->get('c_quote') . ' - ' . $clauses->get('members');
			$viewPath = 'members/edit.rel';
		}
	}
