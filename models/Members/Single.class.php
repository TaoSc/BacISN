<?php
	namespace Members;

	class Single {
		private $member;

		public function __construct($id) {
			$request = \Basics\Site::getDB()->prepare('
				SELECT id, type_id, nickname, slug, first_name, last_name, email, password, avatar avatar_id, DATE(registration) reg_date, TIME(registration) reg_time, birth
				FROM members
				WHERE id = ?
			');
			$request->execute([$id]);
			$this->member = $request->fetch(\PDO::FETCH_ASSOC);

			if ($this->member) {
				global $currentMemberId, $rights;

				$this->member['id'] = (int) $this->member['id'];
				$this->member['edit_cond'] = ($currentMemberId AND (($currentMemberId === $this->member['id'] AND $this->member['type_id'] != 3) OR ($rights['admin_access'] AND $rights['config_edit'])));
				$this->member['removal_cond'] = $this->member['edit_cond'] AND $this->member['type_id'] != 3;
			}
		}

		public function getMember() {
			if ($this->member) {
				// password is salted (slug + pass)
				global $clauses;

				if ($this->member['first_name'])
					$this->member['name'] = $this->member['first_name'] . ' ' .  $this->member['last_name'];
				else
					$this->member['name'] = false;

				if (!$this->member['avatar_id']) {
					$this->member['avatar']['slug'] = 'default';
					$this->member['avatar']['format'] = 'png';
				}
				else {
					$this->member['avatar'] = (new \Medias\Image($this->member['avatar_id']))->getImage();
				}

				if ($this->member['birth']) {
					$this->member['age'] = \Basics\Dates::age($this->member['birth']);
					$this->member['birth'] = $this->member['birth'];
				}
				$this->member['registration']['date'] = $this->member['reg_date'];
				$this->member['registration']['time'] = \Basics\Dates::sexyTime($this->member['reg_time']);
				$this->member['type'] = (new Type($this->member['type_id']))->getType();
			}

			return $this->member;
		}

		public function setMember($newNickname, $newFirstName, $newLastName, $newEmail, $newBirthDate, $newPwd, $newType, $newAvatar, $uploadedFile) {
			$newNickname = htmlspecialchars($newNickname);
			$newSlug = \Basics\Strings::slug($newNickname);
			$newFirstName = htmlspecialchars($newFirstName);
			$newLastName = htmlspecialchars($newLastName);
			$newEmail = htmlspecialchars($newEmail);

			if ($newBirthDate === '0000-00-00')
				$newBirthDate = null;

			$pwdBypass = empty($newPwd) ? true : false;
			$nicknameTest = $newNickname !== $this->member['nickname'];
			$namesTest = (!empty($newFirstName) OR !empty($newLastName));

			if ($this->member AND Handling::check($newNickname, $newSlug, $newFirstName, $newLastName, $newEmail, $pwdBypass ? '123456' : $newPwd, $nicknameTest, (empty($newBirthDate)) ? '0000-00-01' : $newBirthDate, $namesTest) AND !empty($newType)) {
				global $siteDir;

				if (empty($newAvatar) OR !$newAvatar = \Medias\Image::create($newAvatar, $newNickname, [[100, 100]])) {


					if (!empty($uploadedFile) AND $uploadedFile['size'] < 20971520 AND $uploadedFile['error'] == UPLOAD_ERR_OK AND in_array($uploadedFile['type'], ['image/png', 'image/jpeg'])) {
						$uploads_dir = trim($siteDir, '/') . '\images\avatars';

                        $tmp_name = $uploadedFile['tmp_name'];
                        $extension = $uploadedFile['type'] === 'image/png' ? 'png' : 'jpg';
                        $saltedName = hash('sha256', time());
                        $name = $saltedName . '.' . $extension;
                        echo $tmp_name, $uploads_dir . '\\' . $name;

                        if (!$name OR !move_uploaded_file($tmp_name, trim($siteDir, '/') . '\image.png'))
                            die('Erreur lors de l\'envoi de l\'image');
						die();
                        $newAvatar = \Medias\Image::create($uploads_dir . '\\' . $name, $newNickname, [[100, 100]]);
                        crop($uploads_dir . '\\' . $name, $uploads_dir . '\\' . $saltedName, [[100, 100]], $extension);
                    }
                    else
                        die('Vous devez envoyer une image');
					if (!empty($uploadedFile)) {
						$maxFileSize = 2897152;
                        $taille = filesize($uploadedFile['tmp_name']);
                        $extensions = array('.png','.gif','.jpg','.jpeg','.GIF','.PNG','.JPG','.JPEG');
                        $extension = strrchr($uploadedFile['name'], '.');
                        if (!in_array($extension, $extensions))
                        {
                            $error = '<div>You_have_to_upload_a_file_in_png,_gif,_jpeg_or_jpeg</div>';
                        }
                        if ($taille > $maxFileSize) {
                            $error = '<div>Your_file_is_too_bulky</div>';
                        }
                        if (!isset($error)) {
                            // die('create');
							print_r($uploadedFile);
                            move_uploaded_file($uploadedFile['tmp_name'], 'C:\inetpub\wwwroot\lycees\atfchat\images\avatars\\' . $newSlug . $extension);
							echo $uploadedFile['tmp_name'] . ', ' . 'C:\inetpub\wwwroot\lycees\atfchat\images\avatars\\' . $newSlug . $extension;
							die('moved');

                        }
                        else {
                            echo $error;
                        }
					}


                    $newAvatar = $this->member['avatar_id'];
                }

				if ($newAvatar == $this->member['avatar_id'] AND $newSlug !== $this->member['slug']) {
					(new \Medias\Image($this->member['avatar_id']))->setImage($newNickname, $newSlug, null, null); // if the image slug is already taken nothing will change for it, the error is silenced.
				}


				$request = \Basics\Site::getDB()->prepare('UPDATE members SET nickname = ?, slug = ?, avatar = ?, first_name = ?, last_name = ?, email = ?, birth = ?, password = ?, type_id = ? WHERE id = ?');
				$request->execute([
					$newNickname, $newSlug, $newAvatar, $newFirstName, $newLastName, $newEmail, $newBirthDate,
					$pwdBypass ? $this->member['password'] : hash('sha256', $newSlug . $newPwd),
					(int) $newType,
					$this->member['id']
				]);

				return true;
			}
			else
				return false;
		}

		public function notificationsCount() {
			return \Basics\Handling::countEntries('notifications', 'subject_id = ' . $this->member['id'] . ' AND seen = \'0\'');
		}

		public function getNotifications() {
			$request = \Basics\Site::getDB()->prepare('SELECT * FROM notifications WHERE subject_id = ? ORDER BY created_at DESC');
			$request->execute([$this->member['id']]);
			$notifications = $request->fetchAll(\PDO::FETCH_ASSOC);

			foreach ($notifications as $key => $notificationLoop) {
				$notifications[$key]['sender'] = (new Single($notificationLoop['user_id']))->getMember();
				$notifications[$key]['receiver'] = $this->member;
			}

			return $notifications;
		}

		public function getFriendRequests() {
			return \Basics\Handling::getList('to_u = ' . $this->member['id'], 'friend_requests', 'Members', 'Member', false, false, true);
		}

		public function getFriends() {
			$friendsList = [];


			$request = \Basics\Site::getDB()->query('SELECT applicant id FROM friends WHERE acceptor = ' . $this->member['id'] . ' ORDER BY id ASC');
			$ids = $request->fetchAll(\PDO::FETCH_ASSOC);

			foreach ($ids as $member)
				$friendsList[] = (new Single($member['id']))->getMember();


			$request = \Basics\Site::getDB()->query('SELECT acceptor id FROM friends WHERE applicant = ' . $this->member['id'] . ' ORDER BY id ASC');
			$ids = $request->fetchAll(\PDO::FETCH_ASSOC);

			foreach ($ids as $member)
				$friendsList[] = (new Single($member['id']))->getMember();

			return array_values(array_filter($friendsList));
		}

		public function befriend($requestedId) {
			if ($requestedId == $this->member['id'])
				return true;
			return \Basics\Handling::countEntries('friends', '(applicant = ' . $this->member['id'] . ' AND acceptor = ' . $requestedId . ') OR (applicant = ' . $requestedId . ' AND acceptor = ' . $this->member['id'] . ')');
		}

		public function requestPending($requestedId, $bothWays = false) {
			if ($requestedId == $this->member['id'])
				return false;

			if ($bothWays)
				$condition = '(from_u = ' . $this->member['id'] . ' AND to_u = ' . $requestedId . ') OR (from_u = ' . $requestedId . ' AND to_u = ' . $this->member['id'] . ')';
			else
				$condition = '(from_u = ' . $this->member['id'] . ' AND to_u = ' . $requestedId . ')';

			return \Basics\Handling::countEntries('friend_requests', $condition);
		}

		public function sendFriendRequest($requestedId) {
			$requestedMember = (new Single($requestedId))->getMember();

			if ($this->member['type_id'] != 3 AND $requestedMember['type_id'] != 3 AND !$this->befriend($requestedId) AND !$this->requestPending($requestedId, true)) {
				$request = \Basics\Site::getDB()->prepare('INSERT INTO friend_requests (from_u, to_u) VALUES (?, ?)');
				$request->execute([$this->member['id'], $requestedId]);

				$request = \Basics\Site::getDB()->prepare('INSERT INTO notifications (subject_id, name, user_id) VALUES (:subject_id, :name , :user_id)');
				$request->execute(['subject_id'=> $requestedId,
					'name' => 'friend_request_sent',
					'user_id' => $this->member['id']
				]);

				return true;
			}
			else
				return false;
		}

		public function acceptFriendRequest($senderId) {
			$requestedMemberObj = new Single($senderId);
			$requestedMember = $requestedMemberObj->getMember();

			if ($this->member['type_id'] != 3 AND $requestedMember['type_id'] != 3 AND !$this->befriend($senderId) AND $requestedMemberObj->requestPending($this->member['id'])) {
				$request = \Basics\Site::getDB()->prepare('DELETE FROM friend_requests WHERE from_u = ? AND to_u = ?');
				$request->execute([$senderId, $this->member['id']]);

				$request = \Basics\Site::getDB()->prepare('DELETE FROM notifications WHERE user_id = ? AND subject_id = ?');
				$request->execute([$senderId, $this->member['id']]);

				$request = \Basics\Site::getDB()->prepare('INSERT INTO friends (applicant, acceptor) VALUES (?, ?)');
				$request->execute([$senderId, $this->member['id']]);

				return true;
			}
			else
				return false;
		}

		public function cancelFriendRequest($requestedId) {
			$requestedMember = (new Single($requestedId))->getMember();

			if ($this->befriend($requestedId)) {
				$request = \Basics\Site::getDB()->prepare('DELETE FROM friends WHERE (applicant = ? AND acceptor = ?) OR (applicant = ? AND acceptor = ?)');
				$request->execute([$requestedId, $this->member['id'], $this->member['id'], $requestedId]);
			}
			elseif ($this->requestPending($requestedId, true)) {
				$request = \Basics\Site::getDB()->prepare('DELETE FROM friend_requests WHERE (from_u = ? AND to_u = ?) OR (from_u = ? AND to_u = ?)');
				$request->execute([$requestedId, $this->member['id'], $this->member['id'], $requestedId]);

				$request = \Basics\Site::getDB()->prepare('DELETE FROM notifications WHERE (user_id = ? AND subject_id = ?) OR (subject_id = ? AND user_id = ?)');
				$request->execute([$requestedId, $this->member['id'], $this->member['id'], $requestedId]);
			}

			return true;
		}



			/*
		public function deleteAvatar() {
			if ($this->member) {
				global $siteDir;

				unlink($siteDir . '/images/avatars/' . $this->member['id'] . '-100x100.' . $this->member['img']);

				$request = \Basics\Site::getDB()->prepare('UPDATE members SET img = ? WHERE id = ?');
				$request->execute([null, $this->member['id']]);

				return true;
			}
			else
				return false;
		}*/
	}
