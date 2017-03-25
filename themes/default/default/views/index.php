<div class="container text-center">
	<h1><?php echo $clauses->get('home'); ?></h1>
	<?php echo stripslashes(eval('return "' . addslashes($clauses->getDB('pages', 1, 'index_text')) . '";')); ?>
</div>

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
						<div class="name">Vincent Porter</div>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person2">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_02.jpg" alt="avatar">
					<div class="about">
						<div class="name">Aiden Chavez</div>
						<div class="status">
							<i class="fa fa-circle offline"></i> left 7 mins ago
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person3">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_03.jpg" alt="avatar">
					<div class="about">
						<div class="name">Mike Thomas</div>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person4">
					<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_04.jpg" alt="avatar">
					<div class="about">
						<div class="name">Erica Hughes</div>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person5">
					<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_05.jpg" alt="avatar">
					<div class="about">
						<div class="name">Ginger Johnston</div>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person6">
					<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_06.jpg" alt="avatar">
					<div class="about">
						<div class="name">Tracy Carpenter</div>
						<div class="status">
							<i class="fa fa-circle offline"></i> left 30 mins ago
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person7">
					<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_07.jpg" alt="avatar">
					<div class="about">
						<div class="name">Christian Kelly</div>
						<div class="status">
							<i class="fa fa-circle offline"></i> left 10 hours ago
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person8">
					<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_08.jpg" alt="avatar">
					<div class="about">
						<div class="name">Monica Ward</div>
						<div class="status">
							<i class="fa fa-circle online"></i> online
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person9">
					<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_09.jpg" alt="avatar">
					<div class="about">
						<div class="name">Dean Henry</div>
						<div class="status">
							<i class="fa fa-circle offline"></i> offline since Oct 28
						</div>
					</div>
				</li>

				<li class="clearfix person" data-chat="person10">
					<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_10.jpg" alt="avatar">
					<div class="about">
						<div class="name">Peyton Mckinney</div>
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
						<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar">

						<div class="chat-about">
							<div class="chat-with">Vincent Porter</div>
						</div>
					</div>
					<!-- end chat-header -->

					<ul>
						<li class="clearfix">
                            <div class="message-status align-right">
									<span class="message-data-name"> Olia</span>
									<span class="message-data-time">10:10 AM, Today</span>
								</div>
							<div class="message other-message pull-right">
								Hi Vincent, how are you? How is the project coming along? blabalalalala
							</div>
						</li>

						<li>
							<div class="message my-message">
								Are we meeting today? Project has been already finished and I have results to show you.
								<div class="message-status">
									<span class="message-data-name"> Vincent</span>
									<span class="message-data-time">10:12 AM, Today</span>
								</div>
							</div>
						</li>

						<li class="clearfix">
							<div class="message other-message pull-right">
								Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced any problems at the last phase of the project?

								<div class="message-status align-right">
									<span class="message-data-name">Olia</span>
									<span class="message-data-time">10:14 AM, Today</span>
								</div>
							</div>
						</li>

						<li>
							<!--<div class="message-data">

							</div>-->
							<div class="message my-message">
								Actually everything was fine. I'm very excited to show this to our team.
								<div class="message-status">
									<span class="message-data-name">Vincent</span>
									<span class="message-data-time">10:20 AM, Today</span>
								</div>
							</div>

						</li>

					</ul>

				</div>
				<!-- end chat-history -->

				<div class="chat-message clearfix">
					<!--<textarea name="message-to-send" id="message-to-send" placeholder="Type your message" rows="3"></textarea>
					<button>Send</button>-->
					<input name="message-to-send" id="message-to-send" placeholder="Type your message" type="text" class="float-left">
					<button class="pull-right">Send</button>
				</div>
				<!-- end chat-message -->
			</div>

			<div class="chat" data-chat="person2">
				<div class="chat-history">
					<div class="chat-header clearfix">
						<i class="fa fa-bars"></i>
						<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_02.jpg" alt="avatar">

						<div class="chat-about">
							<div class="chat-with">Aiden Chavez</div>
						</div>
					</div>
					<!-- end chat-header -->

					<ul>
						<li class="clearfix">
							<div class="message other-message pull-right">
								Hi Vincent, how are you? How is the project coming along?
								<div class="message-status align-right">
									<span class="message-data-name"> Olia</span>
									<span class="message-data-time">10:10 AM, Today</span>
								</div>
							</div>
						</li>

						<li>
							<div class="message my-message">
								Are we meeting today? Project has been already finished and I have results to show you.
								<div class="message-status">
									<span class="message-data-name"> Vincent</span>
									<span class="message-data-time">10:12 AM, Today</span>
								</div>
							</div>
						</li>

						<li class="clearfix">
							<div class="message other-message pull-right">
								Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced any problems at the last phase of the project?

								<div class="message-status align-right">
									<span class="message-data-name">Olia</span>
									<span class="message-data-time">10:14 AM, Today</span>
								</div>
							</div>
						</li>

						<li>
							<!--<div class="message-data">

							</div>-->
							<div class="message my-message">
								Actually everything was fine. I'm very excited to show this to our team.
								<div class="message-status">
									<span class="message-data-name">Vincent</span>
									<span class="message-data-time">10:20 AM, Today</span>
								</div>
							</div>

						</li>

					</ul>

				</div>
				<!-- end chat-history -->

				<div class="chat-message clearfix">
					<!--<textarea name="message-to-send" id="message-to-send" placeholder="Type your message" rows="3"></textarea>
					<button>Send</button>-->
					<input name="message-to-send" id="message-to-send" placeholder="Type your message" type="text" class="float-left">
					<button class="pull-right">Send</button>
				</div>
				<!-- end chat-message -->
			</div>

			<div class="chat" data-chat="person3">
				<div class="chat-history">
					<div class="chat-header clearfix">
						<i class="fa fa-bars"></i>
						<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_03.jpg" alt="avatar">

						<div class="chat-about">
							<div class="chat-with">Mike Thomas</div>
						</div>
					</div>
					<!-- end chat-header -->

					<ul>
						<li class="clearfix">
							<div class="message other-message pull-right">
								Hi Vincent, how are you? How is the project coming along?
								<div class="message-status align-right">
									<span class="message-data-name"> Olia</span>
									<span class="message-data-time">10:10 AM, Today</span>
								</div>
							</div>
						</li>

						<li>
							<div class="message my-message">
								Are we meeting today? Project has been already finished and I have results to show you.
								<div class="message-status">
									<span class="message-data-name"> Vincent</span>
									<span class="message-data-time">10:12 AM, Today</span>
								</div>
							</div>
						</li>

						<li class="clearfix">
							<div class="message other-message pull-right">
								Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced any problems at the last phase of the project?

								<div class="message-status align-right">
									<span class="message-data-name">Olia</span>
									<span class="message-data-time">10:14 AM, Today</span>
								</div>
							</div>
						</li>

						<li>
							<!--<div class="message-data">

							</div>-->
							<div class="message my-message">
								Actually everything was fine. I'm very excited to show this to our team.
								<div class="message-status">
									<span class="message-data-name">Vincent</span>
									<span class="message-data-time">10:20 AM, Today</span>
								</div>
							</div>

						</li>

					</ul>

				</div>
				<!-- end chat-history -->

				<div class="chat-message clearfix">
					<!--<textarea name="message-to-send" id="message-to-send" placeholder="Type your message" rows="3"></textarea>
					<button>Send</button>-->
					<input name="message-to-send" id="message-to-send" placeholder="Type your message" type="text" class="float-left">
					<button class="pull-right">Send</button>
				</div>
				<!-- end chat-message -->
			</div>

			<div class="chat" data-chat="person4">
				<div class="chat-history">
					<div class="chat-header clearfix">
						<i class="fa fa-bars"></i>
						<img class="img-circle" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_04.jpg" alt="avatar">

						<div class="chat-about">
							<div class="chat-with">Erica Hughes</div>
						</div>
					</div>
					<!-- end chat-header -->

					<ul>
						<li class="clearfix">
							<div class="message other-message pull-right">
								Hi Vincent, how are you? How is the project coming along?
								<div class="message-status align-right">
									<span class="message-data-name"> Olia</span>
									<span class="message-data-time">10:10 AM, Today</span>
								</div>
							</div>
						</li>

						<li>
							<div class="message my-message">
								Are we meeting today? Project has been already finished and I have results to show you.
								<div class="message-status">
									<span class="message-data-name"> Vincent</span>
									<span class="message-data-time">10:12 AM, Today</span>
								</div>
							</div>
						</li>

						<li class="clearfix">
							<div class="message other-message pull-right">
								Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced any problems at the last phase of the project?

								<div class="message-status align-right">
									<span class="message-data-name">Olia</span>
									<span class="message-data-time">10:14 AM, Today</span>
								</div>
							</div>
						</li>

						<li>
							<!--<div class="message-data">

							</div>-->
							<div class="message my-message">
								Actually everything was fine. I'm very excited to show this to our team.
								<div class="message-status">
									<span class="message-data-name">Vincent</span>
									<span class="message-data-time">10:20 AM, Today</span>
								</div>
							</div>

						</li>

					</ul>

				</div>
				<!-- end chat-history -->

				<div class="chat-message clearfix">
					<!--<textarea name="message-to-send" id="message-to-send" placeholder="Type your message" rows="3"></textarea>
					<button>Send</button>-->
					<input name="message-to-send" id="message-to-send" placeholder="Type your message" type="text" class="float-left">
					<button class="pull-right">Send</button>
				</div>
				<!-- end chat-message -->
			</div>

		</div>
		<!-- end chat -->

	</div>
	<!-- end container -->