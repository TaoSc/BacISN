<?php
	if ($messagesNbr) {
		foreach ($messages as $messageLoop)
			Basics\Templates::comment($messageLoop, true);
	}
