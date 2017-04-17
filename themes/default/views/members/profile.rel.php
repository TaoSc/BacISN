<div class="row">
	<div class="col-lg-12">
		<div class="content-profile-page">
			<div class="profile-user-page card">
				<div class="img-user-profile">
					<img class="profile-bgHome" src="images/covers/default.png">
					<img class="avatar" src="<?php echo Basics\Templates::getImg('avatars/' . $member['avatar']['slug'], $member['avatar']['format'], 100, 100); ?>" class="img-circle pull-left" alt="<?php echo $clauses->get('avatar'); ?>">
				</div>

<?php
				if ($currentMemberId) {
?>
					<button><?php echo $clauses->get('request_friendship'); ?></button>
<?php
				}
?>

				<div class="user-profile-data">
					<h1>
<?php
						echo $member['nickname'];
						if (!Basics\Site::parameter('private_emails') AND $member['email'])
							echo ' <a href="mailto:' . $member['email'] . '" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-envelope"></span> ' . $clauses->get('send_message') . '</a>';
?>
					</h1>
				</div>

				<div class="description-profile">
<?php
					echo $clauses->get('reg_date') . ' : ';
					Basics\Templates::dateTime($member['registration']['date'], $member['registration']['time']);

					if ($member['name'])
						echo ' | ' . $clauses->get('name') . ' : ' . $member['name'];

					if ($member['birth'])
						echo ' | ' . $clauses->get('age') . ' : ' . Basics\Dates::age($member['birth']) . ' ' . $clauses->get('years_old');

					if (!Basics\Site::parameter('private_emails') AND $member['email'])
						echo ' | ' . $clauses->get('email') . ' : ' . $member['email'];
?>
				</div>
			</div>
		</div>

<?php
		if ($currentMemberId) {
			echo '<hr>';

			if ($currentMemberId == $member['id'])
				echo $clauses->get('your_profile') . ' <a href="' . $linksDir . 'admin/members/' . $currentMember['id'] . '">' . $clauses->get('modify_profile') . ' <span class="glyphicon glyphicon-pencil"></span></a>';
			elseif (!$isFriend AND !$requestPending)
				echo '<a class="btn btn-primary btn-lg" href="' . $linksDir . 'members/' . $member['slug'] . '/request" role="button">' . $clauses->get('request_friendship') . '</a>';
			elseif (!$isFriend AND $requestPendingFromYou) {
				echo $clauses->get('replay_awaits'),
					 '<a class="btn btn-info btn-lg" href="' . $linksDir . 'members/' . $member['slug'] . '/cancel" role="button">' . $clauses->get('cancel') . '</a>';
			}
			elseif (!$isFriend AND $requestPendingFromThem) {
				echo '<a class="btn btn-success btn-lg" href="' . $linksDir . 'members/' . $member['slug'] . '/accept" role="button">' . $clauses->get('accept') . '</a>',
					 '<a class="btn btn-danger btn-lg" href="' . $linksDir . 'members/' . $member['slug'] . '/decline" role="button">' . $clauses->get('decline') . '</a>';
			}
			else
				echo '<b>' . $clauses->get('befriended') . '</b>';
		}
?>
	</div>
</div>
