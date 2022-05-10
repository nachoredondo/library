<?php

class Config implements \ArrayAccess {
	public function __construct() {
		if (!$this->config = self::get_dt_config()) {
			throw new Exception();
		}
	}

	public static function get_dt_config(string $database = null) : ?array {
		// localhost connection
		return [
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'database' => 'library',
		];

		// connection with cleardb in Heroku
		$conf = parse_url(getenv("CLEARDB_DATABASE_URL"));
		return [
			'host' => $conf['host'],
			'port' => $conf['port'] ?? null,
			'user' => $conf['user'],
			'password' => $conf['pass'],
			'database' => substr($conf["path"],1),
		];
	}

	
	/* Implement ArrayAccess methods */
	public function offsetExists ($offset) : bool {
		return isset($this->config[$offset]);
	}

	public function offsetGet ($offset) {
		return $this->config[$offset];
	}

	public function offsetSet ($offset, $value ) : void {
		throw new \Exception("Configuration files are read-only.");
	}

	public function offsetUnset ($offset) : void {
		throw new \Exception("Configuration files are read-only.");
	}
}
