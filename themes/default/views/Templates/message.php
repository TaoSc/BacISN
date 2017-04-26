<li class="clearfix" id="message-<?php echo $message['id']; ?>">
    <div class="message-status<?php if ($currentMemberId == $message['author']['id']) echo ' align-right'; ?>">
        <span class="message-data-time">
<?php
				Basics\Templates::dateTime($message['date'], $message['time']);

				if ($message['modif_date']) {
					echo ' · ' . $clauses->get('last_modified');
					Basics\Templates::dateTime($message['modif_date'], $message['modif_time']);
				}

				if ($message['language']['code'] !== $language)
					echo ' · <b>' . $clauses->get('message_lang_info') . Basics\Strings::lcFirst($message['language']['lang_name']) . '</b>';
?>
		</span>
    </div>

    <div class="message<?php if ($currentMemberId == $message['author']['id']) echo ' other-message pull-right'; else echo ' my-message'; ?>">
        <div class="btn-group btn-group-xs pull-right">
<?php
			if ($message['removal_cond'])
				echo '<a href="' . $linksDir . 'admin/messages/' . $message['id'] . '/delete' . '" type="button" class="btn btn-warning">' . $clauses->get('delete') . '</a>';
			if ($message['edit_cond'])
				echo '<a href="' . $linksDir . 'admin/messages/' . $message['id'] . '' . '" type="button" class="btn btn-warning">' . $clauses->get('modify') . '</a>';
			if ($hasVoted AND $currentMemberId AND !$message['hidden'])
				echo '<button type="button" class="btn btn-inverse vote-btn" data-id="' . $message['id'] . '" data-type="messages" value="strip">' . $clauses->get('remove_vote') . '</button>';
?>
        </div>

        <div class="btn-group btn-group-xs pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-xs vote-btn" <?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $message['id']; ?>" data-type="messages" value="up">
                        <span class="votes-nbr"><?php echo $message['likes']; ?></span> <span class="glyphicon glyphicon-thumbs-up"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-danger btn-xs vote-btn" <?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $message['id']; ?>" data-type="messages" value="down">
                    <span class="votes-nbr"><?php echo $message['dislikes']; ?></span> <span class="glyphicon glyphicon-thumbs-down"></span>
                </button>
            </div>
        </div>

<?php
			if ($message['hidden'])
				echo '<span class="text-danger">' . $clauses->get('message_hidden') . '</span>';
			else
				echo $message['content'];
?>
    </div>
</li>
