<?php

require("config.php");

/* Convert a path to relative to document root */
function relpath($path = __FILE__) {
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	$relp = str_replace($root, '', $path);
	$relp = str_replace('\\', '/', $relp);
	return $relp;
}

function inverse_date($date){
	$date = explode("-", $date);
	return $date[2] . "-" . $date[1] . "-" . $date[0];
}

function get_db_connection($dbname = null) {
	$conn = Controller::get_global_connection();
	return $conn;
}

define('APP_ROOT', relpath(__DIR__)."/../");
define('REAL_APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . APP_ROOT);


class Controller {

	private static $conn;
	private static $config;

	public static function staticInit() {
		// Read configuration files
		if (!isset(self::$config)) {
			self::$config = new Config();
		}

		if (!isset(self::$conn)) {
			self::$conn = self::get_global_connection();
		}
	}

	public static function config() : Config {
		return self::$config;
	}

	public static function get_global_connection() {
		$config = self::$config;
		$dsn = 'mysql:dbname='.$config['database'].
			';host='.$config['host'].
			';charset=utf8';
		$conn = new \PDO($dsn, $config['user'], $config['password']);
		return $conn;
	}
}

Controller::staticInit();

?>
