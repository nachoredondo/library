<?php
require_once("controller.php");

class User {
	private const TABLE = 'user';
	private $id;
	private $user;
	private $email;
	private $name;
	private $surnames;
	private $password;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->user($data['user']);
			$this->email($data['email']);
			$this->name($data['name']);
			$this->surnames($data['surnames']);
			$this->password($data['password']);
		} else {
			$this->user = '';
			$this->email = '';
			$this->name = '';
			$this->surnames = '';
			$this->password = '';
		}

	}

	public static function insert_user($user=null, $email=null, $name=null, $surnames=null, $password=null) {
		if (!self::validate_user($user))
			throw new InvalidArgumentException('Usuario no válido');

		if (!self::validate_string_with_especial_characters($name))
			throw new InvalidArgumentException('Nombre no válido');

		if ($surnames != "") {
			if (!self::validate_string_with_especial_characters($surnames)) {
				throw new InvalidArgumentException('Apellidos no válidos');
			}
		}

		if (!self::validate_email($email))
			throw new InvalidArgumentException('Correo no válido');

		$result = self::get_user('user', $user);
		if ($result)
			throw new InvalidArgumentException('El usuario '. $user .' ya existe');

		$result = self::get_user('email', $email);
		if ($result)
			throw new InvalidArgumentException('El correo '. $email .' ya está registrado');

		$pwd = self::hash($password);

		$sql = "INSERT INTO `".self::TABLE."`
				(name, surnames, user, email, password)
				VALUES ('$name', '$surnames', '$user', '$email', '$pwd')";
		$res = self::query($sql);

		return $res;
	}

	public static function get_user_from_user(string $user) {
		if (!self::validate_user($user))
			throw new InvalidArgumentException('Usuario no válido');
		return self::get_user('user', $user);
	}

	public static function get_user_from_email(string $email) {
		// if (!self::validate_email($email) && $email != " ")
		// 	throw new InvalidArgumentException('Correo no válido');
		$sql = "SELECT * FROM `".self::TABLE."` WHERE email = '$email'";
		$result = self::query($sql);
		if (!$result){
			throw new InvalidArgumentException("Correo '$email' no existe");
		} else if ($result->rowCount() !== 1) {
			throw new InvalidArgumentException("Correo '$email' no existe");
		}
		return self::get_user('email', $email);
	}

	public static function get_user_from_id(int $id) {
		return self::get_user('id', strval($id));
	}

	private static function get_user(string $id, string $value) {
		$sql = "SELECT * 
			FROM `".self::TABLE."` 
			WHERE `$id` = '$value'";
		$result = self::query($sql);
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$user = new User($data);
		$user->id = intval($data['id']);
		$user->password = $data['password']; // Set hash instead of hashing the hash
		return $user;
	}

	public function id() : int {
		return $this->id;
	}

	public function user(?string $user = null) : string {
		if (isset($user)) {
			if (!self::validate_user($user))
				throw new InvalidArgumentException('Usuario no válido');
			$this->user = $user;
		}
		return $this->user;
	}

	public function email(?string $email = null) : ?string {
		if (isset($email)) {
			$this->email = $email;
		}
		return $this->email;
	}

	public function name(?string $name = null) : string {
		if (isset($name)) {
			$this->name = $name;
		}
		return $this->name;
	}

	public function surnames(?string $surnames = null) : string {
		if (isset($surnames)) {
			$this->surnames = $surnames;
		}
		return $this->surnames;
	}

	public function password(string $newpass, ?string $oldpass = '') : void {
		// The password cannot be the same as the old one
		if ($this->password_verify($newpass))
			throw new InvalidArgumentException('Las contraseñas coinciden');

		$hash = self::hash($newpass);
		$this->password = $hash;
	}

	public function password_verify($password) {
		return password_verify($password, $this->password);
	}

	public static function update($id, $user, $old_email, $new_email, $name, $surnames) {
		if (!self::validate_user($user))
			throw new InvalidArgumentException('Usuario no válido');
		
		if (!self::validate_string_with_especial_characters($name))
			throw new InvalidArgumentException('Nombre no válido');

		if (!self::validate_string_with_especial_characters($surnames))
			throw new InvalidArgumentException('Apellidos no válidos');

		if (!self::validate_email($new_email)) 
			throw new InvalidArgumentException('Correo no válido');

		$result_user = User::get_user_from_id($id);
		if ($result_user->user() != $user){
			$result = self::get_user('user', $user);
			if ($result) {
				throw new InvalidArgumentException('El usuario '. $user .' ya existe');
			}
		}
		if ($old_email != $new_email){
			$result = self::get_user('email', $new_email);
			if ($result) {
				throw new InvalidArgumentException('El correo '. $user .' ya está registrado');
			}
		}

		$sql = "UPDATE `".self::TABLE."`
				SET `user` = '$user',
					`email` = '$new_email',
					`name` = '$name',
					`surnames` = '$surnames'
				WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public static function delete($id) {
		$sql = "DELETE
					FROM `".self::TABLE."`
					WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public static function password_update(int $id, string $password, string $password_confirm) {
		// The new password cannot be less than 8 characters
		if (strlen($password) < 8)
			throw new InvalidArgumentException("El tamaño mínimo de la contraseña es de 8 caracteres");

		if ($password != $password_confirm)
			throw new InvalidArgumentException('Las contraseñas no coinciden');

		$hash = self::hash($password);
		$sql = "UPDATE `".self::TABLE."`
					SET `password` = '$hash'
					WHERE id = '$id'";

		$result = self::query($sql);

		return $result;
	}


	public function fullname() : string {
		$name = $this->name;
		$surnames = $this->surnames;
		return empty($surnames) ? $name : $surnames . ', ' . $name;
	}

	// Centralize password hashing
	private static function hash(string $password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public static function validate_user(string $user) {
		return preg_match('/^[a-zA-Z0-9ñÑ\-. ]+$/', $user);
	}

	public static function validate_email(string $email) : string {
		// var_dump($email);
		// var_dump(FILTER_VALIDATE_EMAIL);
		// var_dump(filter_var($email, FILTER_VALIDATE_EMAIL));
		// exit();

		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function validate_string_with_especial_characters(string $id) : bool {
		return preg_match('/^[a-zA-ZñÑäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙ\ ]+$/', $id);
	}

	private static function query($sql, ...$vars) {
		$conn = Controller::get_global_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
