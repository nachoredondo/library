<?php
require("../../classes/category.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id = $_POST['id'];
$name = $_POST['name'] ?? 'NULL';
$description = $_POST['description'] ?? 'NULL';

try {
	if ($form == "Crear") {
		$success = Category::insert($name, $description);
	} else if ($form == "Editar") {
		$success = Category::update($id, $name, $description);
	} else {
		$success = Category::delete($id);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ../../views/category/edit_create.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if ($form == "Crear") {
			header('Location: ../../views/category/index.php?action=create');
		} else if ($form == "Editar") {
			header('Location: ../../views/category/index.php?action=update&id='.$id);
		} else {
			header('Location: ../../views/category/index.php?action=delete');
		}
	}
}
