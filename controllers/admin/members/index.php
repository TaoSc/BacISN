<?php
	/* Data gathered:
		Array
		(
			[id] => 2
			[type_id] => 2
			[nickname] => tao
			[slug] => tao
			[first_name] => Tao
			[last_name] => Schreiner
			[email] => tschreiner@montaigne-paris.fr
			[password] => 31657f9f73f540e928872e7f0ade150f4b7dfd2dd8939a5f2475fbcbce34fb67
			[avatar_id] => mFmtU6MHvWA
			[reg_date] => 2017-03-21
			[reg_time] => 12:28:03
			[birth] => 1999-12-07
			[edit_cond] => 1
			[removal_cond] => 1
			[name] => Tao Schreiner
			[avatar] => Array
				(
					[id] => mFmtU6MHvWA
					[format] => jpg
					[author_id] => 2
					[name] => tao
					[sizes] => Array
						(
							[0] => Array
								(
									[0] => 100
									[1] => 100
								)

						)

					[date] => 2017-04-17
					[time] => 16:06
					[slug] => tao
					[type] => images
					[sizes_nbr] => 1
					[address] => ./images/avatars/tao.jpg
				)

			[age] => 17
			[registration] => Array
				(
					[date] => 2017-03-21
					[time] => 12:28
				)

			[type] => Array
				(
					[name] => Membres
					[slug] => membres
					[id] => 2
					[count] => 1
				)

		)
	*/
	$members = \Members\Handling::getMembers();

	$pageTitle = $clauses->get('members');
	$viewPath = 'members/index';
