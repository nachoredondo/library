<?php
require_once("controller.php");

/*
 * Get a connection to the MySQL.
 * $dbname: name of the database. Default app db if null.
 */


class Category {
	private const TABLE = 'category';
	private const TABLE_BOOK_CATEGORIES = 'book_categories';
	private $id;


	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->id($data['id']);
			$this->name($data['name']);
			$this->description($data['description']);
		} else {
			$this->id = '';
			$this->name = '';
			$this->description = '';
		}
	}


	public static function insert($name, $description) {
		$sql = "INSERT INTO `".self::TABLE."`
				(name, description)
				VALUES ('$name', '$description')";
		$res = self::query($sql);
		return $res;
	}


	public static function update($id, $name, $description) {
		$sql = "UPDATE `".self::TABLE."`
			SET `name` = '$name',
				`description` = '$description'
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
			FROM `".self::TABLE_BOOK_CATEGORIES."`
			WHERE `id_category` = '$id'";
		$res = self::query($sql);

		return $res;
	}


	public static function get_category(string $id) : ?Category {
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
		$category = new Category($data);
		return $category;

		return $category;
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


	public function description(?string $description) : ?string {
		if (isset($description)) {
			$this->description = $description;
		}
		return $this->description;
	}


	private static function query($sql, ...$vars) {
		$conn = get_db_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
