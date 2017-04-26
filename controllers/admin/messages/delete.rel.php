<?php
	$message = new Messages\Single($params[2]);

	if (empty($message->getMessage(false, false)) OR !$message->deleteMessage($rights['admin_access']))
		error();
	else
		header('Location: ' . $_SERVER['HTTP_REFERER']);
