<?php
	$messageObj = new Comments\Single($params[2], false);
	$message = $messageObj->getMessage(false, false);

	if (empty($message) OR !$message['edit_cond'])
		error();
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($messageObj->setMessage($_POST['content'], $_POST['hidden']))
			header('Refresh: 0');
		else
			error($clauses->get('message_edit_fails'));
	}
	else {
		$message = $messageObj->getMessage(false);
		$hideOptions = [
			['id' => 0, 'name' => 'visible'],
			['id' => 1, 'name' => 'hidden']
		];
		if ($rights['messages_moderate'])
			$hideOptions[] = ['id' => 2, 'name' => 'act_as_deleted'];

		if (!$message['hidden'])
			$btnsGroupMenu[] = ['link' => $linksDir . '#message-' . $message['id'], 'name' => $clauses->get('show_more')];

		$pageTitle = Basics\Strings::cropTxt($message['content'], 10) . ' - ' . $clauses->get('messages');
		$viewPath = 'messages/edit.rel';
	}
