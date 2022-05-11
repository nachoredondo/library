<?php
require_once("controller.php");

/*
 * Get a connection to the MySQL.
 * $dbname: name of the database. Default app db if null.
 */


class Author {
	private const TABLE = 'author';
	private const TABLE_BOOK_AUTHORS = 'book_authors';

	private $id;


	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->id($data['id']);
			$this->name($data['name']);
			$this->pseudonym($data['pseudonym']);
			$this->birthdate($data['birthdate']);
			$this->death_date($data['death_date']);
		} else {
			$this->id = '';
			$this->name = '';
			$this->pseudonym = '';
			$this->birthdate = '';
			$this->death_date = '';
		}
	}


	public static function insert($name, $pseudonym, $birthdate, $death_date) {
		$birthdate = inverse_date($birthdate);
		if (empty($death_date)) {
			$death_date = " NULL ";
		} else {
			$death_date = inverse_date($death_date);
			$death_date = " '$death_date' ";
		}
		$sql = "INSERT INTO `".self::TABLE."`
				(name, pseudonym, birthdate, death_date)
				VALUES ('$name', '$pseudonym', '$birthdate', $death_date)";
		$res = self::query($sql);
		return $res;
	}


	public static function update($id, $name, $pseudonym, $birthdate, $death_date) {
		$birthdate = inverse_date($birthdate);
		if (empty($death_date)) {
			$death_date = " NULL ";
		} else {
			$death_date = inverse_date($death_date);
			$death_date = " '$death_date' ";
		}
		$sql = "UPDATE `".self::TABLE."`
			SET `name` = '$name',
				`pseudonym` = '$pseudonym',
				`birthdate` = '$birthdate',
				`death_date` = $death_date
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

		$sql = "DELETE
			FROM `".self::TABLE_BOOK_AUTHORS."`
			WHERE `id_author` = '$id'";
		$res = self::query($sql);

		return $res;
	}


	public static function get_author(string $id) : ?Author {
		$sql = "SELECT * 
			FROM `".self::TABLE."` 
			WHERE `id` = '$id'";
		$result = self::query($sql);
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$author = new Author($data);
		return $author;

		return $author;
	}


	public function id() {
		return $this->id;
	}


	public function name(?string $name = null) : ?string {
		if (isset($name)) {
			$this->name = $name;
		}
		return $this->name;
	}


	public function pseudonym(?string $pseudonym = null) : ?string {
		if (isset($pseudonym)) {
			$this->pseudonym = $pseudonym;
		}
		return $this->pseudonym;
	}


	public function birthdate(?string $birthdate = "") : ?string {
		if (isset($birthdate)) {
			$this->birthdate = $birthdate;
		}
		return $this->birthdate;
	}


	public function death_date(?string $death_date = "") : ?string {
		if (isset($death_date)) {
			$this->death_date = $death_date;
		}
		return $this->death_date;
	}


	private static function query($sql, ...$vars) {
		$conn = get_db_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
