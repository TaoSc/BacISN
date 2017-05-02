<?php
	namespace Messages;

	class Single {

		protected $message;
        protected $key;

		public function __construct($id) {
            $request = Site::getDB()->prepare('
				SELECT id, author_id, receiver_id, hidden, content, language, DATE(post_date) date, TIME(post_date) time,
				DATE(modif_date) modif_date, TIME(modif_date) modif_time
				FROM messages
				WHERE id = ?
			');

			$request->execute([$id]);
			$this->message = $request->fetch(\PDO::FETCH_ASSOC);

			if ($this->message) {
				global $currentMemberId, $rights;

				$this->message['author_id'] = (int) $this->message['author_id'];
				$this->message['removal_cond'] =
				$this->message['edit_cond'] = ($currentMemberId AND (($currentMemberId === $this->message['author_id'] AND $rights['messages_edit'] AND $this->message['hidden'] != 2) OR $rights['messages_moderate']));
			}
		}

		public function getMessage($lineJump = true, $parsing = true) {
			if ($this->message AND $parsing) {
				global $language;

				$this->message['time'] = Dates::sexyTime($this->message['time']);
				if ($this->message['modif_date'])
					$this->message['modif_time'] = Dates::sexyTime($this->message['modif_time']);
				// Gère la date d'envoie des messages
                $data = base64_decode($this->message['content']);
                $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

                $this->message['content'] = rtrim(
                    mcrypt_decrypt(
                        MCRYPT_RIJNDAEL_128,
                        hash('sha256', Site::getSecret(), true),
                        substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
                        MCRYPT_MODE_CBC,
                        $iv
                    ),
                    "\0"
                );

				$this->message['content'] = htmlspecialchars($this->message['content']);
				// Histoire de sécurité
				if ($lineJump)
					$this->message['content'] = nl2br($this->message['content'], false);
				// Saut de ligne

				$this->message['author'] = (new \Members\Single($this->message['author_id']))->getMember();
				$this->message['language'] = (new \Basics\Languages($this->message['language'], false))->getLanguage($language);
				$this->message['likes'] = \Votes\Handling::number($this->message['id'], 'messages');
				$this->message['dislikes'] = \Votes\Handling::number($this->message['id'], 'messages', -1);
				$this->message['popularity'] = $this->message['likes'] - $this->message['dislikes'];
			}
			// Renvoie les données nécéssaires à l'affichage
			return $this->message;
		}

		public function setMessage($content, $hidden = false) {
			if ($this->message AND !empty($content) AND !empty($content) AND $this->message['edit_cond']) {
				$hidden = (int) $hidden;

				$request = Site::getDB()->prepare('UPDATE messages SET content = ?, hidden = ?, modif_date = NOW() WHERE id = ?');
				$request->execute([$content, $hidden, $this->message['id']]);

				return true;
			}
			else
				return false;
		}

		public function deleteMessage($realRemoval = true) {
			if ($this->message AND $this->message['removal_cond']) {
				$db = \Basics\Site::getDB();

				if ($realRemoval) {
					$request = $db->prepare('DELETE FROM messages WHERE id = ?');
					$request->execute([$this->message['id']]);
				}
				else {
					$request = $db->prepare('UPDATE messages SET hidden = 2 WHERE id = ?');
					$request->execute([$this->message['id'], $this->message['id']]);
				}
				// Permet de supprimer un message envoyé
				return true;
			}
			else
				return false;
		}

		public static function create($receiverId, $content) {

			global $currentMemberId;

			if ($currentMemberId AND $currentMemberId != $receiverId AND !empty($content) AND (new \Members\Single($receiverId))->befriend($currentMemberId)) {
				global $language;


                $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
                $content = base64_encode($iv . mcrypt_encrypt(
                        MCRYPT_RIJNDAEL_128,
                        hash('sha256', Site::getSecret(), true),
                        $content,
                        MCRYPT_MODE_CBC,
                        $iv
                    )
                );

				$request = \Basics\Site::getDB()->prepare('INSERT INTO messages (author_id, ip, receiver_id, hidden, content, language, post_date) VALUES (?, ?, ?, 0, ?, ?, NOW())');
				$request->execute([$currentMemberId, \Basics\Handling::ipAddress(), $receiverId, $content, $language]);

				return true;
			}
			else
				return false;
		}
	}
