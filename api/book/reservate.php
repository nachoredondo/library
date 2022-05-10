<?php
require("../../classes/book.php");
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();

$id_user = User::get_user_from_user($_SESSION['user'])->id();
$action = $_POST['action'] ?? 'NULL';
$id_book = $_POST['id_book'] ?? 'NULL';
$personal = $_POST['personal'] ?? 'NULL';
$id_reservation = $_POST['id_reservation'] ?? 'NULL';

try {
	if ($action == "Reserve") {
		$success = Book::reserve($id_book, $id_user);
	} else {
		$success = Book::delete_reservation($id_reservation);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	if (empty($personal)) {
		header('Location: ../../views/main/index.php?success=false&error='.$error.'&message='.$message);
	} else {
		header('Location: ../../views/main/index.php?success=false&error='.$error.'&message='.$message.'&my-books');
	}
}
if (!$errors) {
	if ($success) {
		if ($action == "Reserve") {
			header('Location: ../../views/main/index.php?action=reserve');
		} else {
			if (empty($personal)){
				header('Location: ../../views/main/index.php?action=delete');
			} else {
				header('Location: ../../views/main/index.php?action=delete&my-books');
			}
		}
	}
}
