<?php
	$message = new Messages\Single($params[2]);

	if (empty($message->getMessage(false, false)) OR !$message->deleteMessage())
		error();
	else
		header('Location: ' . $_SERVER['HTTP_REFERER']);
