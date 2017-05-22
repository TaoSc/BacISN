<div class="jumbotron">
    <div class="container text-center<?php if ($currentMemberId) echo ' flex'; ?>">
<?php
		if ($currentMemberId) {
?>
            <a href="<?php echo $linksDir . 'admin/members/' . $currentMember['id']; ?>" title="<?php echo $clauses->get('modify_profile'); ?>" class="btn btn-link btn-lg"><span class="user-action-icon glyphicon glyphicon-pencil"></span></a>

            <div class="user-jumbo">
                <img src="<?php echo Basics\Templates::getImg('avatars/' . $currentMember['avatar']['slug'], $currentMember['avatar']['format'], 100, 100); ?>" class="img-circle pull-left" alt="<?php echo $clauses->get('avatar'); ?>">
                <h2 class="pull-right"><?php echo $currentMember['nickname']; ?></h2>
            </div>

            <a href="<?php echo $linksDir . 'members/' . $currentMember['slug']; ?>/" title="<?php echo $clauses->get('profile'); ?>" class="btn btn-link btn-lg"><span class="user-action-icon glyphicon glyphicon-user"></span></a>
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
<?php
                if (!$friends)
					echo '<button type="button" class="btn btn-success btn-lg" onclick="$(\'#search-box\').focus();">' . $clauses->get('add_friends') . ' <span class="glyphicon glyphicon-plus"></span></button>';

                foreach ($friends as $member) {
?>

                    <li class="clearfix person" data-chat="person<?php echo $i; ?>">
                        <img class="img-circle" src="<?php echo Basics\Templates::getImg('avatars/' . $member['avatar']['slug'], $member['avatar']['format'], 100, 100); ?>" alt="<?php echo $clauses->get('avatar'); ?>">
                        <div class="about">
                            <span class="name"><?php echo $member['nickname']; ?></span>
                            <div class="status">
                                <i class="fa fa-circle online"></i>
                                <?php echo $clauses->get('online'); ?>
                            </div>
                        </div>
                    </li>
<?php
					$i++;
				}
?>
            </ul>
        </div>

        <div class="chat-holder">
<?php
			$i = 1;
			foreach ($friends as $member) {
?>
                <div class="chat" data-chat="person<?php echo $i; ?>" data-id="<?php echo $member['id']; ?>">
                    <div class="chat-history">
                        <div class="chat-header clearfix">
                            <i class="fa fa-bars"></i>
                            <a href="<?php echo $linksDir . 'members/' . $member['slug']; ?>/">
							<img class="img-circle" src="<?php echo Basics\Templates::getImg('avatars/' . $member['avatar']['slug'], $member['avatar']['format'], 100, 100); ?>" alt="<?php echo $clauses->get('avatar'); ?>">
						</a>

                            <div class="chat-about">
                                <div class="chat-with">
                                    <?php echo $member['nickname']; ?>
                                </div>
                                <?php echo $member['name']; ?>
                            </div>
                        </div>

                        <ul>
                            <?php Messages\Handling::view($member['id']); ?>
                        </ul>
                    </div>

                    <form class="form-horizontal chat-message clearfix" method="post" action="<?php echo $linksDir . 'messages/' . $member['id']; ?>">
                        <input type="text" id="message-to-send-<?php echo $member['id']; ?>" name="message-to-send" placeholder="<?php echo $clauses->get('message_placeholder'); ?>" required>

                        <button id="send" name="send" class="pull-right button-send">
                            <?php echo $clauses->get('send'); ?>
                        </button>

                        <input type="hidden" name="location" id="location" value="<?php echo $location; ?>">
                    </form>

                </div>
<?php
				$i++;
			}
?>
        </div>
    </div>
<?php
	}
