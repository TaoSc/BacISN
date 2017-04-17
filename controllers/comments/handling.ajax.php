<?php
	if ($ajaxCheck AND is_numeric($params[2] . $params[4] . $params[5]))
		Comments\Handling::view($params[2], $params[4], $params[5]);
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['location']) AND Comments\Single::create($params[2], $_POST['content']))
			header('Location: ' . $linksDir . $_POST['location']);
		else
			error($clauses->get('comment_fail'));
	}
	else
		error();
