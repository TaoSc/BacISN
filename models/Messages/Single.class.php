<?php
	namespace Messages;

    use \Basics\Site;
    use \Basics\Dates;

	class Single {

		protected $message;
        protected $key;

		public function __construct($id) {
			// Récupère les données concernant le message dans la base de données
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

				// Conditions autorisant la suppression du message
				$this->message['removal_cond'] = ($currentMemberId AND (($currentMemberId === $this->message['author_id'] AND $rights['messages_edit'] AND $this->message['hidden'] != 2) OR $rights['messages_moderate']));
			}
		}

		public function getMessage($lineJump = true, $parsing = true) {
			if ($this->message AND $parsing) {
				global $language;

				// Formatte la date d'envoi du message
				$this->message['time'] = Dates::formattedTime($this->message['time']);
				if ($this->message['modif_date'])
					$this->message['modif_time'] = Dates::formattedTime($this->message['modif_time']);

				// Décrypte le message
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

				// Histoire de sécurité
				$this->message['content'] = htmlspecialchars($this->message['content']);

				// Saut de ligne
				if ($lineJump)
					$this->message['content'] = nl2br($this->message['content'], false);


				// Récupère toutes les infos sur l'auteur du message
				$this->message['author'] = (new \Members\Single($this->message['author_id']))->getMember();
				$this->message['language'] = (new \Basics\Languages($this->message['language'], false))->getLanguage($language);

				// Récupère les votes dudit message
				$this->message['likes'] = \Votes\Handling::number($this->message['id'], 'messages');
				$this->message['dislikes'] = \Votes\Handling::number($this->message['id'], 'messages', -1);
				$this->message['popularity'] = $this->message['likes'] - $this->message['dislikes'];
			}

			// Renvoie les données nécéssaires à l'affichage
			return $this->message;
		}

		// Permet de supprimer un message envoyé
		public function deleteMessage() {
			global $rights;

			if ($this->message AND $this->message['removal_cond']) {
				$db = \Basics\Site::getDB();

				// Seul un administratuer peut réellement supprimer un message
				if ($rights['admin_access']) {
					$request = $db->prepare('DELETE FROM messages WHERE id = ?');
					$request->execute([$this->message['id']]);
				}
				// Les autres membres ne font que le masquer
				else {
					$request = $db->prepare('UPDATE messages SET hidden = 2 WHERE id = ?');
					$request->execute([$this->message['id'], $this->message['id']]);
				}

				return true;
			}
			else
				return false;
		}

		public static function create($receiverId, $content) {
			global $currentMemberId;

			// Effectue les vérifications nécessaires
			if ($currentMemberId AND $currentMemberId != $receiverId AND !empty($content) AND (new \Members\Single($receiverId))->befriend($currentMemberId)) {
				global $language;

				// Crypte le message
                $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
                $content = base64_encode($iv . mcrypt_encrypt(
                        MCRYPT_RIJNDAEL_128,
                        hash('sha256', Site::getSecret(), true),
                        $content,
                        MCRYPT_MODE_CBC,
                        $iv
                    )
                );

				// Envoie le message et ses méta-données dans la base de données
				$request = \Basics\Site::getDB()->prepare('INSERT INTO messages (author_id, ip, receiver_id, hidden, content, language, post_date) VALUES (?, ?, ?, 0, ?, ?, NOW())');
				$request->execute([$currentMemberId, \Basics\Handling::ipAddress(), $receiverId, $content, $language]);

				return true;
			}
			else
				return false;
		}
	}
