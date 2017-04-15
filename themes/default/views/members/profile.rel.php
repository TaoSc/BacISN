<div class="row">
	<div class="col-lg-12">
		<div class="page-header no-margin flex">
			<img src="<?php echo Basics\Templates::getImg('avatars/' . $member['avatar_slug'], $member['avatar'], 100, 100); ?>" class="img-circle pull-left" alt="<?php echo $clauses->get('avatar'); ?>">
			<h1>
<?php
				echo $member['nickname'];
				if (!Basics\Site::parameter('private_emails') AND $member['email'])
					echo '<a href="mailto:' . $member['email'] . '" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-envelope"></span> ' . $clauses->get('send_message') . '</a>';
?>
			</h1>
		</div>

		<dl class="dl-horizontal">
			<dt><?php echo $clauses->get('reg_date'); ?></dt>
			<dd><?php Basics\Templates::dateTime($member['registration']['date'], $member['registration']['time']); ?></dd>
<?php
			if ($member['name']) {
?>
				<dt><?php echo $clauses->get('name'); ?></dt>
				<dd><?php echo $member['name']; ?></dd>
<?php
			}

			if ($member['birth']) {
?>
				<dt><?php echo $clauses->get('age'); ?></dt>
				<dd><?php echo Basics\Dates::age($member['birth']) . ' ' . $clauses->get('years_old'); ?></dd>
<?php
			}

			if (!Basics\Site::parameter('private_emails') AND $member['email']) {
?>
				<dt><?php echo $clauses->get('email'); ?></dt>
				<dd><?php echo $member['email']; ?></dd>
<?php
			}
?>
			<dt><?php echo $clauses->get('type'); ?></dt>
			<dd><a href="<?php echo $linksDir . 'members/types/' . $member['type']['slug']; ?>"><?php echo $member['type']['name']; ?></a></dd>
		</dl>

<?php
		if ($currentMemberId) {
			echo '<hr>';

			if ($currentMemberId == $member['id'])
				echo $clauses->get('your_profile');
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
