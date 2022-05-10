<?php

define('SESSION_EXPIRE_TIME_MINUTES', 60);

class Session {

	/* Check if the user is logged in and redirect to login */
	public static function check_login_redirect() {
		if (!self::check_login())
			self::redirect_to_login();
	}

	/* Returns true if session is valid, false otherwise. */
	public static function check_login() {
		if(!isset($_SESSION)) {
			session_start();
		}

		// If the user account has been logged in, the page is loaded if it is not redirected to log in
		if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
			return false;
		}

		return !self::has_expired();
	}

	/* Check if the session has expired and touch it (update). */
	private static function has_expired() {
		// The session expires after an ammount of time if has not been used
		$now = time();
		if($now - $_SESSION['lastcheck'] > SESSION_EXPIRE_TIME_MINUTES * 60) {
			return true;
		}

		// Expire session in 60 minutes
		$_SESSION['lastcheck'] = $now;
		return false;
	}

	private static function redirect_to_login() {
		// Clear session except lasturl for later redirection (after login)
		if (isset($_SESSION)) session_destroy();
		session_start();
		$_SESSION['lasturl'] = $_SERVER['REQUEST_URI'];

		header('Location: '.APP_ROOT.'api/user/login.php');
		exit();
	}
}
