<?php
	$message = new Comments\Single($params[2]);

	if (empty($message->getMessage(false, false)) OR !$message->deleteComment($rights['admin_access']))
		error();
	else
		header('Location: ' . $_SERVER['HTTP_REFERER']);
