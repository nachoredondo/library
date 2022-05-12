<?php
require("../../classes/author.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'] ?? 'NULL';
$id = $_POST['id'] ?? 'NULL';
$name = $_POST['name'] ?? 'NULL';
$pseudonym = $_POST['pseudonym'] ?? 'NULL';
$birthdate = $_POST['birthdate'] ?? 'NULL';
$death_date = $_POST['death_date'] ?? 'NULL';


// start control errors
if (!$name && $form == "Crear"){
	header('Location: ../../views/author/edit_create.php?success=false&message=Nombre vacío');
	exit();
} else if (!$name && $form == "Editar") {
	// print("punto de control");
	// exit();
	header('Location: ../../views/author/edit_create.php?id='.$id.'&success=false&message=Nombre vacío');
	exit();
}

if (!$pseudonym && $form == "Crear"){
	header('Location: ../../views/author/edit_create.php?success=false&message=Pseudónimo vacío');
	exit();
} else if (!$pseudonym && $form == "Editar") {
	header('Location: ../../views/author/edit_create.php?id='.$id.'&success=false&message=Pseudónimo vacío');
	exit();
}

if (!$birthdate && $form == "Crear"){
	header('Location: ../../views/author/edit_create.php?success=false&message=Fecha nacimiento vacía');
	exit();
} else if (!$birthdate && $form == "Editar") {
	header('Location: ../../views/author/edit_create.php?id='.$id.'&success=false&message=Fecha nacimiento vacía');
	exit();
}
// end control errors


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
