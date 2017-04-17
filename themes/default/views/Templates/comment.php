<div class="row">
	<div class="col-xs-offset-1 col-xs-10 no-padding" id="message-<?php echo $message['id']; ?>">
		<div class="col-xs-2 no-padding user-box text-center">
			<a href="<?php echo $linksDir . 'members/' . $message['author']['slug'] . '/'; ?>">
				<img src="<?php echo Basics\Templates::getImg('avatars/' . $message['author']['avatar']['slug'], $message['author']['avatar']['format'], 100, 100); ?>" alt="<?php echo $clauses->get('avatar'); ?>" class="img-circle img-responsive">
				<h4><?php echo $message['author']['nickname']; ?></h4>
			</a>
		</div>

		<div class="col-xs-10 content-box">
			<div class="row infos-box">
				<div class="col-lg-12">
<?php
				Basics\Templates::dateTime($message['date'], $message['time']);

				if ($message['modif_date']) {
					echo ' · ' . $clauses->get('last_modified');
					Basics\Templates::dateTime($message['modif_date'], $message['modif_time']);
				}

				if ($message['language']['code'] !== $language)
					echo ' · <b>' . $clauses->get('comment_lang_info') . Basics\Strings::lcFirst($message['language']['lang_name']) . '</b>';
?>
				</div>
			</div>

			<div class="row content-itself<?php if ($message['hidden'] == 1) echo ' bg-warning'; ?>">
				<div class="col-lg-12">
					<div class="btn-group btn-group-xs pull-right">
<?php
						if ($message['removal_cond'])
							echo '<a href="' . $linksDir . 'admin/comments/' . $message['id'] . '/delete' . '" type="button" class="btn btn-warning">' . $clauses->get('delete') . '</a>';
						if ($message['edit_cond'])
							echo '<a href="' . $linksDir . 'admin/comments/' . $message['id'] . '' . '" type="button" class="btn btn-warning">' . $clauses->get('modify') . '</a>';
						if ($hasVoted AND $currentMemberId AND !$message['hidden'])
							echo '<button type="button" class="btn btn-inverse vote-btn" data-id="' . $message['id'] . '" data-type="comments" value="strip">' . $clauses->get('remove_vote') . '</button>';
?>
					</div>
<?php
					if ($message['hidden'])
						echo '<span class="text-danger">' . $clauses->get('com_hidden_lvl1') . '</span>';
					else
						echo $message['content'];
?>
				</div>
			</div>

			<div class="row options-box">
				<div class="col-xs-<?php if ($messagesTemplate AND (\Basics\Site::parameter('anonymous_coms') OR $currentMemberId)) echo '7'; else echo '12'; ?>">
					<div class="btn-group btn-group-justified">
						<div class="btn-group">
							<button type="button" class="btn btn-success btn-sm vote-btn"<?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $message['id']; ?>" data-type="comments" value="up">
								<span class="glyphicon glyphicon-thumbs-up"></span> <?php echo $clauses->get('to_like'); ?>
								(<span class="votes-nbr"><?php echo $message['likes']; ?></span>)
							</button>
						</div>

						<div class="btn-group">
							<button type="button" class="btn btn-danger btn-sm vote-btn"<?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $message['id']; ?>" data-type="comments" value="down">
								<span class="glyphicon glyphicon-thumbs-down"></span> <?php echo $clauses->get('to_dislike'); ?>
								(<span class="votes-nbr"><?php echo $message['dislikes']; ?></span>)
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
