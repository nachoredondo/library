<?php
require("../../classes/user.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$id = $_POST['id'];
$form = $_POST['form'];
$user = $_POST['user'] ?? 'NULL';
$old_email = $_POST['old_email'] ?? 'NULL';
$new_email = $_POST['new_email'] ?? 'NULL';
$name = $_POST['name'] ?? 'NULL';
$surnames = $_POST['surnames'] ?? 'NULL';
$password = $_POST['pwd'] ?? 'NULL';
$password_confirm = $_POST['pwd-confirm'] ?? 'NULL';


if($form == "pwd" && (strlen($password) < 8 || strlen($password) > 128)) {
	header('Location: ../../views/users/profile.php?success=false&error=password-length');
	exit();
}

try {
	if ($form == "data") {
		$success = User::update($id, $user, $old_email, $new_email, $name, $surnames);
	} else if ($form == "pwd") {
		$success = User::password_update($id, $password, $password_confirm);
	} else {
		$success = User::delete($id);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ../../views/users/profile.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if ($form == "data") {
			$_SESSION['user'] = $user;
			$_SESSION['fullname'] = ($surnames == "") ? $name : $surnames . ', ' . $name;
			$_SESSION['name'] = $name;
			header('Location: ../../views/users/profile.php?action=data');
		} else if ($form == "pwd") {
			header('Location: ../../views/users/profile.php?action=pwd');
		} else {
			header('Location: ./logout.php');
		}
	} else {
		header('Location: ../../views/users/profile.php?success='.($success === false ? 'false' : 'true'));
	}
}
