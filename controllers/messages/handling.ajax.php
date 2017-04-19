<?php
	if ($ajaxCheck AND false)
		die('not ready yet.');
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['location']) AND Comments\Single::create($params[1], $_POST['message-to-send']))
			header('Location: ' . $linksDir . $_POST['location']);
		else
			error($clauses->get('message_fail'));
	}
	else
		error();
