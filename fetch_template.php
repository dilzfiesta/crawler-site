<?php
	$type = strtolower($_REQUEST['type']);
	$type_cat = array('java', 'php');
	
	if(empty($type) && !in_array($type, $type_cat)) {
		return "";
	} else {
		
		define('ABSPATH', dirname(__FILE__).'/');
		$data = file_get_contents(ABSPATH.'Templates/'.$type);
		echo $data;
	}
?>