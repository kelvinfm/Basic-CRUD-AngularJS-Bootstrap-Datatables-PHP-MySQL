<?php

class DB {

	static function Connect() {
		$host = 'localhost';
		$name = 'test';
		$user = 'root';
		$pass = '';
		$db = new PDO("mysql: host={$host}; dbname={$name}", $user, $pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $db;
	}

}

?>


