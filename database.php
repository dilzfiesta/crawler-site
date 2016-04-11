<?php

require_once 'common.php';

class Database {
	private static $link = '';

	public function __construct() {
		$objCommon = new Common();
		$config = $objCommon->get_config();
		
		self::$link = mysqli_connect($config['Database']['host'], $config['Database']['username'], $config['Database']['password'], $config['Database']['database']);
		if(!self::$link) {
			die("mysqli_connect error: ".mysql_error());
		}
	}

	public function fetch_records() {
		$query = 'select j.id, h.id as history_id, j.position, j.published_date, j.employer, j.location, j.start_date, j.url, j.email from crawler_jobs j left join email_history h on j.id=h.job_id order by j.published_date desc';
		$result = @mysqli_query(self::$link, $query);
		$data = '';
		print_r($link);
		while($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}
		return $data;
	}
	
	public function add_email_history($job_id, $email_id, $subject, $message) {
		$message = addslashes($message);
		$query = "insert into email_history(job_id, email_id, subject, message) values('".$job_id."', '".$email_id."', '".$subject."', '".$message."')";
		$result = mysqli_query(self::$link, $query);
	} 
}

?>
