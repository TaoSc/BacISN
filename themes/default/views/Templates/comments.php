<div id="comments">
	<div class="row">
		<div class="col-sm-3">
			<h3><?php echo $member['nickname']; ?> <span class="label label-default comments-nbr"><?php echo $messagesNbr;?></span></h3>
		</div>
		<div class="col-sm-9 comments-toolbox">
			<div class="btn-group pull-left coms-dropdowns">
				<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo $clauses->get('lang_options'); ?> <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
					<li role="presentation" class="dropdown-header"><?php echo $clauses->get('coms_lang_header'); ?></li>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $dummy . '/' . $receiverId . '/' . $dummy . '/1/' . (int) $order . '">' . $clauses->get('coms_lang_op1') . '</a></li>'; ?>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $dummy . '/' . $receiverId . '/' . $dummy . '/0/' . (int) $order . '">' . $clauses->get('coms_lang_op2') . '</a></li>'; ?>
				</ul>
			</div>

			<div class="btn-group pull-left coms-dropdowns">
				<button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown"><?php echo $clauses->get('order_options'); ?> <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $dummy . '/' . $receiverId . '/' . $dummy . '/' . (int) $languageCheck . '/0">' . $clauses->get('coms_order_op1') . '</a></li>'; ?>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $dummy . '/' . $receiverId . '/' . $dummy . '/' . (int) $languageCheck . '/1">' . $clauses->get('coms_order_op2') . '</a></li>'; ?>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $dummy . '/' . $receiverId . '/' . $dummy . '/' . (int) $languageCheck . '/2">' . $clauses->get('coms_order_op3') . '</a></li>'; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="comment-create col-lg-offset-3 col-lg-6">

			<form class="form-horizontal" method="post" action="<?php echo $linksDir . 'comments/' . $dummy . '/' . $receiverId . '/' . $dummy . '/' . (int) $languageCheck . '/' . (int) $order; ?>">
				<fieldset>
					<legend><?php echo $clauses->get('send_comment_title'); ?></legend>

					<div class="form-group">
						<label class="col-md-2 control-label" for="content"><?php echo $clauses->get('content'); ?></label>
						<div class="col-md-10">
							<textarea class="form-control" id="content" name="content" placeholder="<?php echo $clauses->get('message_placeholder'); ?>" required></textarea>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<button id="send" name="send" class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
							<button id="cancel-reply" name="cancel-reply" class="btn btn-inverse"><?php echo $clauses->get('cancel'); ?></button>
						</div>
					</div>

					<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">
				</fieldset>
			</form>

		</div>
	</div>

<?php
	if ($messagesNbr) {
?>
		<hr>
		<div class="row">
			<div class="comments-list col-lg-12">
<?php
				foreach ($comments as $messageLoop)
					Basics\Templates::comment($messageLoop, true);
?>
			</div>
		</div>
<?php
	}
?>
</div>
