<form class="form-horizontal col-lg-12 col-xs-no-padding col-xs-no-padding" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10 col-xs-no-padding col-xs-no-padding">
		<legend><?php echo $clauses->get('edit_member'); ?></legend>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="nickname"><?php echo $clauses->get('nickname'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="nickname" id="nickname" type="text" class="form-control" value="<?php echo $member['nickname']; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="first_name"><?php echo $clauses->get('first_name'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="first_name" id="first_name" type="text" class="form-control" placeholder="<?php echo $clauses->get('first_name_placeholder'); ?>" value="<?php echo $member['first_name']; ?>">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="last_name"><?php echo $clauses->get('last_name'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="last_name" id="last_name" type="text" class="form-control" placeholder="<?php echo $clauses->get('last_name_placeholder'); ?>" value="<?php echo $member['last_name']; ?>">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="birth"><?php echo $clauses->get('birth'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="birth" id="birth" type="date" class="form-control" value="<?php echo $member['birth']; ?>" placeholder="YYYY-MM-DD">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="password"><?php echo $clauses->get('password'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="password" id="password" type="password" class="form-control" placeholder="<?php echo $clauses->get('pwdu_placeholder'); ?>">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="img"><?php echo $clauses->get('avatar'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="img" id="img" type="url" class="form-control" placeholder="<?php echo $clauses->get('img_placeholder'); ?>">
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-offset-4 col-xs-offset-3" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>
