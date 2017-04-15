<?php
	namespace Members;

	class Single {
		private $member;

		public function __construct($id) {
			$request = \Basics\Site::getDB()->prepare('
				SELECT id, type_id, nickname, slug, first_name, last_name, email, password, avatar, DATE(registration) reg_date, TIME(registration) reg_time, birth
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

				if (!$this->member['avatar']) {
					$this->member['avatar_slug'] = 'default';
					$this->member['avatar'] = 'png';
				}
				else
					$this->member['avatar_slug'] = $this->member['slug'];

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

		public function setMember($newNickname, $newFirstName, $newLastName, $newEmail, $newBirthDate, $newPwd, $newType, $newAvatar) {
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

				if (empty($newAvatar)) {
					// $newAvatar = $this->member['avatar'];
					$newAvatar = null;
				}
				else {
					die('Not ready yet.');
					$newAvatar = \Basics\Images::crop($newAvatar, 'avatars/' . $this->member['id'], [[100, 100]]);
					if (!$newAvatar)
						die('Une erreur est survenue lors de l\'envoi de votre avatar.');

					if ($this->member['img_id'] !== 'default' AND $newAvatar !== $this->member['img']) {
						unlink($siteDir . '/images/avatars/' . $this->member['id'] . '-100x100.' . $this->member['img']);
					}
				}

				$request = \Basics\Site::getDB()->prepare('UPDATE members SET nickname = ?, slug = ?, avatar = ?, first_name = ?, last_name = ?, email = ?, birth = ?, password = ?, type_id = ? WHERE id = ?');
				$request->execute([
					$newNickname,
					$newSlug,
					$newAvatar,
					$newFirstName,
					$newLastName,
					$newEmail,
					$newBirthDate,
					$pwdBypass ? $this->member['password'] : hash('sha256', $newSlug . $newPwd),
					(int) $newType,
					$this->member['id']
				]);

				return true;
			}
			else
				return false;
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
			}

			return true;
		}

		/*public function deleteMember() {
			if ($this->member) {
				$db = \Basics\Site::getDB();

				$this->deleteAvatar();

				$request = $db->prepare('DELETE FROM members WHERE id = ?');
				$request->execute([$this->member['id']]);

				$request = $db->prepare('UPDATE categories SET author_id = ? WHERE author_id = ?');
				$request->execute([1, $this->member['id']]);

				$request = $db->prepare('DELETE FROM comments WHERE author_id = ?');
				$request->execute([$this->member['id']]);

				$request = $db->prepare('DELETE FROM posts WHERE author_id = ?');
				$request->execute([$this->member['id']]);

				$request = $db->prepare('DELETE FROM likes WHERE author_id = ?');
				$request->execute([$this->member['id']]);

				return true;
			}
			else
				return false;
		}

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
