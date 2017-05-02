<?php
	if (!$ajaxCheck OR !$currentMemberId)
		return false;

	$request = \Basics\Site::getDB()->prepare('SELECT id FROM members WHERE (first_name LIKE :query OR last_name LIKE :query OR nickname LIKE :query) AND type_id != 3 LIMIT 3');
	$request->execute(['query' => '%' . $_POST['query'] . '%']);
	$ids = $request->fetchAll(\PDO::FETCH_ASSOC);

	$users = [];
	foreach ($ids as $element)
		$users[] = (new \Members\Single($element['id']))->getMember();
	$users = array_values(array_filter($users));

	foreach ($users as $user) {
?>
		<div class="display-box-user">
			<a href="<?php echo $linksDir . 'members/' . $user['slug']; ?>/">
				<img src="<?php echo Basics\Templates::getImg('avatars/' . $user['avatar']['slug'], $user['avatar']['format'], 100, 100); ?>" class="img-circle pull-left" alt="<?php echo $clauses->get('avatar'); ?>">
				<?php echo $user['nickname']; ?>
				<br><?php echo $user['email']; ?>
			</a>
		</div>
<?php
		}
