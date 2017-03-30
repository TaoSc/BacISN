<div class="jumbotron">
	<div class="container text-center">
<?php
		if ($currentMemberId) {
?>
			<div class="pull-left">
				<a href="<?php echo $linksDir . 'admin/members/' . $currentMember['id']; ?>" title="<?php echo $clauses->get('modify_profile'); ?>" class="btn btn-link btn-lg"><span class="user-action-icon glyphicon glyphicon-pencil"></span></a>
			</div>
			<div class="pull-right">
				<a href="<?php echo $linksDir . 'members/' . $currentMember['slug']; ?>/" title="<?php echo $clauses->get('profile'); ?>" class="btn btn-link btn-lg"><span class="user-action-icon glyphicon glyphicon-user"></span></a>
			</div>

			<img src="<?php echo Basics\Templates::getImg('avatars/' . $currentMember['avatar_slug'], $currentMember['avatar'], 100, 100); ?>" class="img-circle pull-left" alt="<?php echo $clauses->get('avatar'); ?>">
			<h1><?php echo $currentMember['nickname']; ?></h1>
<?php
		}
		else {
?>
			<h1><?php echo $clauses->get('home'); ?></h1>
			<?php echo stripslashes(eval('return "' . addslashes($clauses->getDB('pages', 1, 'index_text')) . '";')); ?>

			<p><a class="btn btn-primary btn-lg" href="<?php echo $linksDir; ?>members/registration" role="button"><?php echo $clauses->get('register'); ?> &raquo;</a></p>
<?php
		}
?>
	</div>
</div>

<?php
	if ($currentMemberId) {
?>
	<div class="container-fluid clearfix" id="chat-wrapper">
		<div class="people-list" id="people-list">
			<div class="search">
				<input type="text" placeholder="<?php echo $clauses->get('search_placeholder'); ?>">
				<span class="glyphicon glyphicon-search"></span>
			</div>

			<ul class="list people">
				<li class="clearfix person" data-chat="person1">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01.jpg" alt="avatar">
					<div class="about">
						<span class="name">Vincent Porter</span>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person2">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_02.jpg" alt="avatar">
					<div class="about">
						<span class="name">Aiden Chavez</span>
						<div class="status">
							<i class="fa fa-circle offline"></i> left 7 mins ago
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person3">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_03.jpg" alt="avatar">
					<div class="about">
						<span class="name">Mike Thomas</span>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person4">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_04.jpg" alt="avatar">
					<div class="about">
						<span class="name">Erica Hughes</span>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person5">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_05.jpg" alt="avatar">
					<div class="about">
						<span class="name">Ginger Johnston</span>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person6">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_06.jpg" alt="avatar">
					<div class="about">
						<span class="name">Tracy Carpenter</span>
						<div class="status">
							<i class="fa fa-circle offline"></i> left 30 mins ago
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person7">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_07.jpg" alt="avatar">
					<div class="about">
						<span class="name">Christian Kelly</span>
						<div class="status">
							<i class="fa fa-circle offline"></i> left 10 hours ago
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person8">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_08.jpg" alt="avatar">
					<div class="about">
						<span class="name">Monica Ward</span>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person9">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_09.jpg" alt="avatar">
					<div class="about">
						<span class="name">Dean Henry</span>
						<div class="status">
							<i class="fa fa-circle offline"></i> offline since Oct 28
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person10">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_10.jpg" alt="avatar">
					<div class="about">
						<span class="name">Peyton Mckinney</span>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>
			</ul>
		</div>

		<div class="chat-holder">

			<div class="chat" data-chat="person1">
				<div class="chat-history">
					<div class="chat-header clearfix">
						<i class="fa fa-bars"></i>
						<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar">

						<div class="chat-about">
							<div class="chat-with">Vincent Porter</div>
						</div>
					</div>
					<!-- end chat-header -->

					<ul>
                        <div class="message-status align-right">
									<span class="message-data-time">10:10 AM, Today</span>
								</div>
						<li class="clearfix">
							<div class="message other-message pull-right">
								Hi Vincent, how are you? How is the project coming along?

								
							</div>
						</li>

						<li>
                            <div class="message-status">
									<span class="message-data-time">10:12 AM, Today</span>
								</div>
							<div class="message my-message">
								Are we meeting today? Project has been already finished and I have results to show you.

							</div>
						</li>
                                <div class="message-status align-right">
									<span class="message-data-time">10:14 AM, Today</span>
								</div>
						<li class="clearfix">
							<div class="message other-message pull-right">
								Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced any problems at the last phase of the project?
							</div>
						</li>

						<li>
                            <div class="message-status">
									<span class="message-data-time">10:20 AM, Today</span>
								</div>
							<div class="message my-message">
								Actually everything was fine. I'm very excited to show this to our team.

								</div>

						</li>
					</ul>

				</div>
				<!-- end chat-history -->

				<div class="chat-message clearfix">
					
					<input name="message-to-send" id="message-to-send" placeholder="Type your message" type="text" class="float-left">
					<button class="pull-right button-send">Send</button>
				</div>
				<!-- end chat-message -->
			</div>

			
					

					

				

		</div>
		

	</div>

<?php
	}