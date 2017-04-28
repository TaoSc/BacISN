<?php
	$request = Basics\Site::getDB()->prepare('SELECT id, avatar, nickname FROM members WHERE (first_name LIKE :query OR last_name LIKE :query OR nickname LIKE :query) LIMIT 3');
	$request->execute(['query' => '%' . $_POST['query'] . '%']);

	$users = $request->fetchAll(\PDO::FETCH_ASSOC);

	foreach ($users as $user) {
?>
		<div class="display-box-user">
			<a href="profile.php?id=<?php echo $user['id']; ?>">
				<img src="<?php echo Basics\Templates::getImg('avatars/' . $currentMember['avatar']['slug'], $currentMember['avatar']['format'], 100, 100); ?>" class="img-circle pull-left" alt="<?php echo $clauses->get('avatar'); ?>">
				<?php echo $user['nickname']; ?>
				<br><?php echo $user['email']; ?>
			</a>
		</div>
<?php
		}
