<div class="panel panel-default">
	<div class="panel-heading"><?php echo $clauses->get('members'); ?></div>

<?php
	if ($members) {
?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo $clauses->get('nickname'); ?></th>
					<th><?php echo $clauses->get('friends'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($members as $memberLoop) {
?>
					<tr>
						<td><a href="<?php echo $linksDir . 'admin/members/' . $memberLoop['id']; ?>"><?php echo $memberLoop['nickname']; ?></a></td>
						<td><?php echo \Basics\Dates::sexyDate($memberLoop['reg_date']); ?></td>
					</tr>
<?php
				}
?>
			</tbody>
		</table>
<?php
	}
	else
		echo '<div class="panel-body">' . $clauses->get('no_member'); '</div>';
?>
</div>