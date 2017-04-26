<?php
	if ($ajaxCheck AND $_POST['content'])
		$messageContent = $_POST['content'];
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST' AND $_POST['message-to-send'])
		$messageContent = $_POST['message-to-send'];

	if (isset($messageContent) AND !empty($messageContent)) {
		$postMessage = Messages\Single::create($params[1], $messageContent);

		if ($postMessage AND isset($_POST['location']))
			header('Location: ' . $linksDir . $_POST['location']);
		elseif ($postMessage AND $ajaxCheck)
			Messages\Handling::view($params[1]);
		else
			error($clauses->get('message_fail'));
	}
	else
		error();
