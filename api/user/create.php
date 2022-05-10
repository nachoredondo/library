<?php
require("../../classes/user.php");

$errors = [];

$user = $_POST['user'] ?? 'NULL';
$email = $_POST['email'] ?? 'NULL';
$name = $_POST['name'];
$surnames = $_POST['surnames'] ?? 'NULL';
$password = $_POST['password'] ?? 'NULL';
$password_confirm = $_POST['password-confirm'] ?? 'NULL';

$parameter = "&user=".$user."&name=".$name."&email=".$email."&surnames=".$surnames;

if(strlen($password) < 8 || strlen($password) > 128) {
	header('Location: ../../views/users/registrer.php?success=false&error=password-length'.$parameter);
	exit();
}

if($password != $password_confirm) {
	header('Location: ../../views/users/registrer.php?success=false&error=no-same-password'.$parameter);
	exit();
}

try {
	$success = User::insert_user($user, $email, $name, $surnames, $password, $tutor);
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ../../views/users/registrer.php?success=false&error='.$error.'&message='.$message.$parameter);
}
if (!$errors) {
	if ($success) {
		header('Location: ./login.php?action=created');
	} else {
		header('Location: ../../views/users/registrer.php?success='.($success === false ? 'false' : 'true'));
	}
}
