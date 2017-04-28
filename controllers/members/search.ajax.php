<?php
	$request = Basics\Site::getDB()->prepare('SELECT id, avatar, nickname FROM members WHERE (first_name LIKE :query OR last_name LIKE :query OR nickname LIKE :query) LIMIT 3');
	$request->execute(['query' => '%' .$_POST['query'].'%']);

	$users = $request->fetchAll(PDO::FETCH_OBJ);

	foreach ($users as $user) {
		print_r($user);
		/*
?>
		<div id="display-results">
			<div class="display-box-user">
				<a href="profile.php?id=<?php echo $user['id']; ?>">
					<?php echo $user['nickname']; ?>
					<br><?php echo $user['email']; ?>
					<img src="" alt="">
				</a>
			</div>
		</div>
<?php
	*/
		}
