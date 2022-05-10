<?php
require("../../classes/book.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id = $_POST['id'];
$title = $_POST['title'] ?? 'NULL';
$isbn = $_POST['ISBN'] ?? 'NULL';
$description = $_POST['description'];
$file = $_POST['file_saved'] ?? 'NULL';
$fileTmpPath = $_FILES['file']['tmp_name'] ?? 'NULL';
$fileName = $_FILES['file']['name'] ?? 'NULL';
$fileSize = $_FILES['file']['size'] ?? 'NULL';
$fileType = $_FILES['file']['type'] ?? 'NULL';

if (!$fileName && !$file){
	header('Location: ../../views/book/edit_create.php?action=err&message=Sin adjuntar imagen');
	exit();
}

if (!$title){
	header('Location: ../../views/book/edit_create.php?action=err&message=Título vacío');
	exit();
}

if (!$isbn){
	header('Location: ../../views/book/edit_create.php?action=err&message=ISBN vacío');
	exit();
}

if ($fileSize > 8000000) {
	header('Location: ../../views/book/edit_create.php?action=err&message=Tamaño máximo del archivo adjuntado sobrepasado (8 Megabytes)');
	exit();
}

$uploadFileDir = '../../files/book/';
$destPath = $uploadFileDir . $fileName;

if ($fileName == null) {
	$fileName = $file;
}

try {
	if ($form == "Crear") {
		$success = Book::insert($title, $isbn, $fileName, $description);
	} else if ($form == "Editar") {
		$success = Book::update($id, $title, $isbn, $fileName, $description);
	} else {
		$success = Book::delete($id);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ../../views/book/edit_create.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if ($form == "Crear") {
			if ($fileName != null) {
				if (!file_exists($uploadFileDir)) {
					mkdir($uploadFileDir, 0777, true);
				}
				if(!move_uploaded_file($fileTmpPath, $destPath)) {
					$message = 'Image is not saved';
					header('Location: ../../views/book/edit_create.php?action=create_option&message='.$message);
				}
			}
			$id = Book::get_last_id();
			header('Location: ../../views/book/edit_create.php?action=create&id='.$id);
		} else if ($form == "Editar") {
			if ($fileName != null) {
				if (!file_exists($uploadFileDir)) {
					mkdir($uploadFileDir, 0777, true);
				}
				if(!move_uploaded_file($fileTmpPath, $destPath)) {
					$message = 'Image is not saved';
					header('Location: ../../views/book/edit_create.php?action=update_option&message='.$message.'&id='.$id);
				}
			}
			header('Location: ../../views/book/edit_create.php?action=update&id='.$id);
		} else {
			header('Location: ../../views/book/edit_create.php?action=delete');
		}

	} else {
		header('Location: ../../views/book/edit_create.php?&success=true');
	}
}
