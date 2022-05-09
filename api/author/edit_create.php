<?php
require("../../classes/author.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id = $_POST['id'];
$name = $_POST['name'] ?? 'NULL';
$pseudonym = $_POST['pseudonym'] ?? 'NULL';
$birthdate = $_POST['birthdate'] ?? 'NULL';
$death_date = $_POST['death_date'] ?? 'NULL';

try {
	if ($form == "Crear") {
		$success = Author::insert($name, $pseudonym, $birthdate, $death_date);
	} else if ($form == "Editar") {
		$success = Author::update($id, $name, $pseudonym, $birthdate, $death_date);
	} else {
		$success = Author::delete($id);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ../../views/author/edit_create.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if ($form == "Crear") {
			header('Location: ../../views/author/index.php?action=create');
		} else if ($form == "Editar") {
			header('Location: ../../views/author/index.php?action=update&id='.$id);
		} else {
			header('Location: ../../views/author/index.php?action=delete');
		}
	}
}
