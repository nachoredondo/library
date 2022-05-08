<?php
require_once("controller.php");

/*
 * Get a connection to the MySQL.
 * $dbname: name of the database. Default app db if null.
 */


class Book {
	private const TABLE = 'book';
	private const TABLE_AUTHOR = 'author';
	private const TABLE_BOOK_AUTHORS = 'book_authors';
	private const TABLE_BOOK_CATEGORIES = 'book_categories';
	private const TABLE_CATEGORY = 'category';
	private $id;


	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->id($data['id']);
			$this->title($data['title']);
			$this->ISBN($data['ISBN']);
			$this->description($data['description']);
		} else {
			$this->id = '';
			$this->title = '';
			$this->ISBN = '';
			$this->description = '';
		}
	}


	public static function insert_book($title, $ISBN, $description) {
		$sql = "INSERT INTO `".self::TABLE."`
				(title, ISBN, description)
				VALUES ('$title', '$ISBN', '$description')";
		$res = self::query($sql);
		return $res;
	}


	public static function update_book($id, $title, $ISBN, $description) {
		$sql = "UPDATE `".self::TABLE."`
			SET `title` = '$title',
				`ISBN` = '$ISBN',
				`description` = '$description'
			WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}


	public static function delete_book($id) {
		$sql = "DELETE
			FROM `".self::TABLE."`
			WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}


	public static function get_book(string $id) : ?Book {
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
		$book = new Book($data);
		return $book;

		return $book;
	}


	public static function get_book_authors(string $id) {
		$sql = "SELECT id_author
			FROM `".self::TABLE_BOOK_AUTHORS."` 
			WHERE `id_book` = '$id'";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		return $data;
	}


	public static function search_books(string $search) {
		$sql = "SELECT * 
			FROM `".self::TABLE."` 
			WHERE `title` LIKE '%$search%'
				OR `ISBN` LIKE '%$search%'
			ORDER BY `id` DESC
			LIMIT 10";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		return $data;
	}


	public static function get_book_categories(string $id) {
		$sql = "SELECT id_category
			FROM `".self::TABLE_BOOK_CATEGORIES."` 
			WHERE `id_book
			` = '$id'";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		return $data;
	}


	public function id() : int {
		return $this->id;
	}


	public function title(?string $title = null) : ?string {
		if (isset($title)) {
			$this->title = $title;
		}
		return $this->title;
	}


	public function ISBN(?string $ISBN = null) : ?string {
		if (isset($ISBN)) {
			$this->ISBN = $ISBN;
		}
		return $this->ISBN;
	}


	public function description(?string $description = "") : ?string {
		if (isset($description)) {
			$this->description = $description;
		}
		return $description;
	}


	private static function query($sql, ...$vars) {
		$conn = get_db_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
