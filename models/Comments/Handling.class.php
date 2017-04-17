<?php
	namespace Comments;

	class Handling {
		public static function getMessages($condition = 'TRUE', $languageCheck = false, $hidden = true, $ascending = false, $offsetLimit = false, $idsOnly = false, $lineJump = true) {
			global $language;

			return \Basics\Handling::getList($condition, 'comments', 'Comments', 'Message', $offsetLimit, $idsOnly, $ascending, $lineJump);
		}

		public static function view($receiverId, $languageCheck = false, $order = false, $hidden = true) {
			global $siteDir, $linksDir, $clauses, $location, $language, $currentMemberId, $theme;

			/* not unused yet, but should be */
			$dummy = 1;

			$member = (new \Members\Single($receiverId))->getMember();

			$basicCondition = '((receiver_id = ' . $receiverId . ' AND author_id = ' . $currentMemberId . ') OR (receiver_id = ' . $currentMemberId . ' AND author_id = ' . $receiverId . '))';
			$advancedCondition = null;
			if ($languageCheck)
				$advancedCondition .= ' AND language = \'' . $language . '\'';
			if ($hidden)
				$advancedCondition .= ' AND hidden < 2';

			$messagesNbr = \Basics\Handling::countEntries('comments', $basicCondition . $advancedCondition);


			if ($order > 1)
				$order = $sortNeeded = 1;

			$comments = self::getMessages($basicCondition . $advancedCondition, $languageCheck, $hidden, $order);
			if (isset($sortNeeded))
				$comments = \Basics\Handling::twoDimSorting($comments, 'popularity');

			include $siteDir . $theme['dir'] . 'views/Templates/comments.php';
		}
	}
