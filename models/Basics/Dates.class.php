<?php
	namespace Basics;

	class Dates {
		public static function sexyDate($date, $cutDays = false, $today = false) {
			global $language, $clauses;

			if ($today AND $date === (new \DateTime)->format('Y-m-d'))
				return $clauses->get('today');

			$localDays = [$clauses->get('sunday'), $clauses->get('monday'), $clauses->get('tuesday'), $clauses->get('wednesday'),
						  $clauses->get('thursday'), $clauses->get('friday'), $clauses->get('saturday')];
			$localMonths = [$clauses->get('january'), $clauses->get('february'), $clauses->get('march'), $clauses->get('april'),
							$clauses->get('may'), $clauses->get('june'), $clauses->get('july'), $clauses->get('august'),
							$clauses->get('september'), $clauses->get('october'), $clauses->get('november'), $clauses->get('december')];

			$date = \DateTime::createFromFormat('Y-m-d', $date);
			$dayWord = $localDays[$date->format('w')];
			if ($cutDays)
				$dayWord = Strings::cropTxt($dayWord, 3, '.');
			$dayNum = $date->format('j');
			$daySuffix = Dates::enDaySuffix($date->format('j'));
			$monthWord = $localMonths[$date->format('n') - 1];
			$yearNum = $date->format('Y');

			return stripslashes(eval($clauses->getMagic('sexy_date_format')));
		}

		public static function enDaySuffix($day) {
			switch ($day) {
				case 1: case 21: case 31: return 'st';
				case 2: case 22:          return 'nd';
				case 3: case 23:          return 'rd';
			}
			return 'th';
		}

		public static function sexyTime($date) {
			global $clauses;

			return (new \DateTime($date))->format($clauses->get('time_format'));
		}

		public static function age($birthDate) {
			list($year, $month, $day) = explode('-', $birthDate);
			$now = (new \DateTime());
			$todayMonth = $now->format('n');
			$todayDay = $now->format('j');
			$todayYear = $now->format('Y');
			$years = $todayYear - $year;

			if ($todayMonth < $month)
				$years--;

			return $years;
		}
	}
