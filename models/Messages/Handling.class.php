<?php
	namespace Messages;

	class Handling {
		public static function getMessages($condition = 'TRUE', $languageCheck = false, $hidden = true, $ascending = true, $offsetLimit = false, $idsOnly = false, $lineJump = true) {
			global $language;

			return \Basics\Handling::getList($condition, 'comments', 'Messages', 'Message', $offsetLimit, $idsOnly, $ascending, $lineJump);
		}

		public static function view($receiverId, $languageCheck = false, $hidden = true) {
			global $siteDir, $linksDir, $clauses, $location, $language, $theme, $currentMemberId;

			$basicCondition = '((receiver_id = ' . $receiverId . ' AND author_id = ' . $currentMemberId . ') OR (receiver_id = ' . $currentMemberId . ' AND author_id = ' . $receiverId . '))';
			$advancedCondition = null;
			if ($languageCheck)
				$advancedCondition .= ' AND language = \'' . $language . '\'';
			if ($hidden)
				$advancedCondition .= ' AND hidden < 2';

			$messagesNbr = \Basics\Handling::countEntries('comments', $basicCondition . $advancedCondition);

			$messages = self::getMessages($basicCondition . $advancedCondition, $languageCheck, $hidden);

			include $siteDir . $theme['dir'] . 'views/Templates/messages.php';
		}
	}
