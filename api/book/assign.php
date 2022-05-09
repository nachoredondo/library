<?php
require("../../classes/book.php");
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);

$errors = [];

$type_author = $_POST['type_author'] ?? 'NULL';
$id_book = $_POST['id_book'];
$id_author_category = $_POST['id_author_category'];
$checked = $_POST['checked'] ?? 'NULL';

header('Content-Type: application');

if ($type_author == "true") {
	if ($checked == "false") {
		$success = Book::assign_author($id_book, $id_author_category);
		if ($success) {
			echo '../../views/book/edit_create.php?id='.$id_book.'&action=assign_author';
		}
	} else {
		$success = Book::unassign_author($id_book, $id_author_category);
		if ($success){
			echo '../../views/book/edit_create.php?id='.$id_book.'&action=unassign_author';
		}
	}
} else {
	if ($checked == "false") {
		$success = Book::assign_category($id_book, $id_author_category);
		if ($success) {
			echo '../../views/book/edit_create.php?id='.$id_book.'&action=assign_category';
		}
	} else {
		$success = Book::unassign_category($id_book, $id_author_category);
		if ($success){
			echo '../../views/book/edit_create.php?id='.$id_book.'&action=unassign_category';
		}
	}
}
