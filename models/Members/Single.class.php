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
			if ($this->member)
				$this->member['id'] = (int) $this->member['id'];
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

		public function setMember($newNickname, $newFirstName, $newFamilyName, $newEmail, $newPwd, $newType, $newAvatar, $pwdCript = true) {
			$nicknameTest = $newNickname !== $this->member['nickname'];
			$pwdCript = $pwdCript ? hash('sha256', $newPwd) : $newPwd;
			$namesTest = !empty($newFirstName) AND !empty($newFamilyName);

			if ($this->member AND \Members\Handling::check($newNickname, $newFirstName, $newFamilyName, $newEmail, $newPwd, $nicknameTest, '0000-00-01', $namesTest) AND !empty($newType)) {
				global $siteDir;

				if (empty($newAvatar)) {
					if ($this->member['img_id'] === 'default')
						$newAvatar = null;
					else
						$newAvatar = $this->member['img'];
				}
				else {
					$newAvatar = \Basics\Images::crop($newAvatar, 'avatars/' . $this->member['id'], [[100, 100]]);
					if (!$newAvatar)
						die('Une erreur est survenue lors de l\'envoi de votre avatar.');

					if ($this->member['img_id'] !== 'default' AND $newAvatar !== $this->member['img']) {
						unlink($siteDir . '/images/avatars/' . $this->member['id'] . '-100x100.' . $this->member['img']);
					}
				}

				$request = \Basics\Site::getDB()->prepare('UPDATE members SET nickname = ?, img = ?, first_name = ?, last_name = ?, email = ?, password = ?, type_id = ? WHERE id = ?');
				$request->execute([
					htmlspecialchars($newNickname),
					$newAvatar,
					htmlspecialchars($newFirstName),
					htmlspecialchars($newFamilyName),
					htmlspecialchars($newEmail),
					$pwdCript,
					htmlspecialchars($newType),
					$this->member['id']
				]);

				return true;
			}
			else
				return false;
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
