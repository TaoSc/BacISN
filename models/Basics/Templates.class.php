<?php
	namespace Basics;

	class Templates {
		public static function basicHeaders() {
			global $linksDir, $subDir, $CMSVersion;

			echo '<meta charset="utf-8">', PHP_EOL,
				 '<meta http-equiv="X-UA-Compatible" content="IE=edge">', PHP_EOL,
				 '<meta name="viewport" content="width=device-width, initial-scale=1">', PHP_EOL,
				 // '<link rel="icon" href="' . $subDir . 'images/favicon.ico">', PHP_EOL,
				 '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">', PHP_EOL,
				 '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">', PHP_EOL,
				 '<script>var linksDir = \'' . $linksDir . '\';</script>', PHP_EOL,
				 '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>', PHP_EOL,
				 '<script async src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>' . PHP_EOL;
		}

		public static function textList($array) {
			$list = null;

			foreach ($array as $element) {
				$list .= '
					<span class="label label-default">' . $element['label'] . '</span>
					<a href="' . $element['link'] . '">' . $element['text'] . '</a>
					<hr>' . PHP_EOL;
			}

			echo trim($list, '<hr>' . PHP_EOL);
		}

		public static function dateTime($date, $time) {
			global $clauses;

			echo Dates::sexyDate($date, true, true) . ' ' . $clauses->get('at') . ' ' . Dates::sexyTime($date . ' ' . $time);
		}

		public static function getImg($slug, $extension, $width, $height, $relativeLoc = true) {
			if ($relativeLoc) {
				global $subDir;
				$dir = &$subDir;
			}
			else {
				global $siteDir;
				$dir = &$siteDir;
			}

			return $dir . 'images/' . $slug . '-' . $width . 'x' . $height .  '.' . $extension;
		}

		public static function smallUserBox($member, $size = 'col-sm-5') {
			global $siteDir, $linksDir, $clauses, $theme;

			include $siteDir . $theme['dir'] . 'views/Templates/smallUserBox.php';
		}

		public static function comment($message, $messagesTemplate = false) {
			global $siteDir, $linksDir, $language, $clauses, $currentMemberId, $theme;

			$hasVoted = \Votes\Handling::did($message['id'], 'comments');
			$voteBtnsCond = ($hasVoted OR (!$currentMemberId AND !Site::parameter('anonymous_votes')) OR $message['hidden'] == 1);

			include $siteDir . $theme['dir'] . 'views/Templates/comment.php';
		}
	}
