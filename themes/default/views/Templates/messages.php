<?php
	if ($messagesNbr) {
		foreach ($messages as $messageLoop)
			Basics\Templates::message($messageLoop, true);
	}
