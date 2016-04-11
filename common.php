<?php

class Common {
	private static $config = '';
	
	public function __construct() {
		define('ABSPATH', dirname(__FILE__).'/');
		self::$config = parse_ini_file(ABSPATH.'Config/config.ini', true);
	}

	public function get_config() {
		return self::$config;
	}
	
	public function pr($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
}
?>