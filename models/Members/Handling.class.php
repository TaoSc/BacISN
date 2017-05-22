<?php
	namespace Members;

	class Handling {
		// Récupère la liste de tous les membres en base de données
		public static function getMembers($condition = 'TRUE') {
			return \Basics\Handling::getList($condition, 'members', 'Members', 'Member', false, false, true);
		}

		public static function check($nickname, $slug, $firstName, $lastName, $email, $pwd, $nicknameTest = true, $birthDate = '0000-00-01', $namesTest = true) {
			if (!empty($birthDate) AND $birthDate !== '0000-00-00' AND !empty($nickname) AND !empty($email) AND mb_strlen($pwd) >= 6) {
				$birthDateRegex = preg_match('#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#', $birthDate);
				$emailRegex = preg_match('#^[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4}$#', $email);
				$namesTestCond = $namesTest ? mb_strlen($lastName) >= 2 AND mb_strlen($firstName) >= 2 : true;

				if ($namesTestCond AND $birthDateRegex AND mb_strlen($nickname) >= 3 AND $emailRegex) {
					if ($nicknameTest) {
						$db = \Basics\Site::getDB();

						$request = $db->prepare('SELECT id FROM members WHERE slug = ?');
						$request->execute([$slug]);
						$otherMbrsIds = $request->fetch(\PDO::FETCH_ASSOC)['id'];
						$request->closeCursor();

						$request = $db->prepare('SELECT id FROM members WHERE email = ?');
						$request->execute([$email]);
						$otherMbrsIds .= $request->fetch(\PDO::FETCH_ASSOC)['id'];
						$request->closeCursor();
					}
					else
						$otherMbrsIds = null;

					if (empty($otherMbrsIds) AND mb_substr_count($nickname, '@') === 0 AND $slug !== 'default')
						return true;
					else
						return false;
				}
				else
					return false;
			}
			else
				return false;
		}

		// Connecte un membre, en utilisant les cookies ou non selon son choix
		public static function login($nickname, $pwd, $cookies = false) {
			global $clauses;

			// L'utilisateur peut se connecter aussi bien avec un e-mail qu'un pseudo,
			// on cherche donc duquel des deux il s'agit grâce à la même regex que celle utilisée pour vérifier que l'e-mail est conforme.
			// De plus, le pseudo ne peut pas contenir d'@, donc pas de risque de mal-interpréter et de risquer d'empêcher un utilisateur de se connecter
			if (preg_match('#^[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4}$#', $nickname))
				$columnName = 'email';
			else
				$columnName = 'nickname';

			// On récupère l'id et le mot de passe correspondant au pseudo/e-mail
			$request = \Basics\Site::getDB()->prepare('SELECT id, password FROM members WHERE ' . $columnName . ' = ? AND type_id != 3');
			$request->execute([$nickname]);
			$member = $request->fetch(\PDO::FETCH_ASSOC);
			$request->closeCursor();

			// S'il n'y a rien, mauvais identifiant
			if (!$member)
				return error($clauses->get('invalid_user'));
			elseif (empty($member['id']) OR empty($nickname) OR empty($pwd) OR !empty(\Basics\site::session('member_id')))
				return false;
			// Ici $pwd = hash('sha256', slug($nomDutilisateur) . $motDePasse)
			// Ce qui correspond avec ce qui est entré en base de données :
			// $member['password'] = hash('sha256', $slug . $pwd)
			// Donc si il s'agit du même hash c'est tout bon
			elseif ($pwd === $member['password']) {
				// Si le membre veut une connexion persistante (aka. cookies), on stocke pseudo et salt du mdp en cookies
				// Suffisant pour re-tenter une connexion dès que la session expire et fournit sécurité correcte
				if ($cookies) {
					\Basics\site::cookie('name', $nickname);
					\Basics\site::cookie('password', $member['password']);
				}
				\Basics\site::session('member_id', (int) $member['id']);

				return true;
			}
			else
				return false;
		}

		// Détruit la session et les éventuels cookies
		public static function logout() {
			global $topDir;

			session_destroy();
			setcookie(\Basics\Strings::slug(\Basics\Site::parameter('name')) . '_name', '', time(), $topDir, null, false, true);
			setcookie(\Basics\Strings::slug(\Basics\Site::parameter('name')) . '_password', '', time(), $topDir, null, false, true);

			return true;
		}

		// Créé un membre dont on peut forcer le statut d'admin si souhaité
		public static function registration($nickname, $email, $pwd1, $pwd2, $cookies = false, $admin = false) {
			// On "nettoie" les valeurs utilisateur
			$nickname = htmlspecialchars($nickname);
			$email = htmlspecialchars($email);
			// Note : le slug est toujours parsé après que sa valeur originelle soit nettoyée
			$slug = \Basics\Strings::slug($nickname);

			// Si les données entrées passent les conditions requises
			if (self::check($nickname, $slug, null, null, $email, $pwd2, true, '0000-00-01', false) AND $pwd1 === $pwd2) {
				// On ajoute à la base de données
				$request = \Basics\Site::getDB()->prepare('
					INSERT INTO members (type_id, nickname, slug, email, password, registration)
					VALUES (?, ?, ?, ?, ?, NOW())
				');
				$request->execute([
					// On choisit donc le type désiré en fonction du booléen $admin
					$admin ? 1 : \Basics\Site::parameter('default_users_type'),
					$nickname, $slug, $email,
					// Le mot de passe n'est jamais stocké en clair, on stocke un hash de $slug + $pwd afin d'éviter possible collision et des failles au niveau des sessions / cookies
					// (ce qui n'est en réalité pas parfait, en choisissant conscienscieusement son slug et pass il y aurait encore moyen de tomber sur le même hash qu'un autre membre)
					hash('sha256', $slug . $pwd2)
				]);

				// Et connecte le membre
				if (self::login($nickname, hash('sha256', $slug . $pwd2), $cookies))
					return true;
				elseif (!$admin) {
					global $clauses;

					return error(stripslashes(eval($clauses->getMagic('login_fail'))));
				}
			}
			else
				return false;
		}
	}
