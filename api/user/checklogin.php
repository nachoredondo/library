<?php
require("../../classes/user.php");

function login_failed($type) {
	header('Location: ./login.php?type='.$type);
	exit();
}

function login_succeded() {
	// Redirect to the last URL or the main page
	unset($_SESSION['lasturl']);
	if (isset($_SESSION['lasturl'])) {
		$url = $_SESSION['lasturl'];
		unset($_SESSION['lasturl']);
	} else {
		$url = APP_ROOT . "views/main/";
	}
	header('Location: ' . $url);
}

session_start();

$email = $_POST['email'];
$password = $_POST['password'];
$educator = true;

try {
	$user = User::get_user_from_email($email);
} catch (InvalidArgumentException $err) {
	$_SESSION['login-error'] = true;
	login_failed($type);
} catch (Exception $err) {
	login_failed($type);
}

if ($user->password_verify($password)) {
	session_regenerate_id(true); // Regenerate to avoid session fixation
	$_SESSION['loggedin'] = true;
	$_SESSION['user'] = $user->user();
	$_SESSION['fullname'] = $user->fullname();
	$_SESSION['name'] = $user->name();
	$_SESSION['lastcheck'] = time();
	login_succeded();
} else {
	$_SESSION['login-error'] = true;
	login_failed($type);
}
