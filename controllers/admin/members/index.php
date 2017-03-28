<?php
	// Data gathered:
	/*
		[1] => Array
        (
            [id] => 1
            [type_id] => 1
            [nickname] => arthur
            [slug] => arthur
            [first_name] => 
            [last_name] => 
            [email] => agoldberg@montaigne-paris.fr
            [password] => 694947af650c7f5bf4e9c41bb83e383cded268fed3ee7f6e41c0a6d78967e6e4
            [avatar] => png
            [reg_date] => 2017-03-07
            [reg_time] => 11:46:37
            [birth] => 
            [name] => 
            [avatar_slug] => default
            [registration] => Array
                (
                    [date] => 2017-03-07
                    [time] => 11:46 AM
                )

            [type] => Array
                (
                    [slug] => administrators
                    [name] => Administrators
                    [id] => 1
                    [count] => 1
                )

        )
	*/
	$members = \Members\Handling::getMembers();

	$btnsGroupMenu = [['link' => $linksDir . 'admin/members/0', 'name' => $clauses->get('arzar')]];

	$pageTitle = $clauses->get('members');
	$viewPath = 'members/index';
