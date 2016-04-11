<?php
	require_once 'database.php';
	require_once 'common.php';
	require_once "Mail.php";
	
	$to = $_POST['to'];
	$job_id = $_POST['job_id'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	
	if(!empty($to) && !empty($subject) && !empty($message)) {
		
		$objCommon = new Common();
		$objDatabase = new Database();

		$config = $objCommon->get_config();
		$from = $config['Email']['from'];
		$host = $config['Email']['host'];
		$port = $config['Email']['port'];
		$username = $config['Email']['username'];
		$password = $config['Email']['password'];
		
		$headers = array ('From' => $from,
				'To' => $to,
				'Subject' => $subject);
		
		$smtp = Mail::factory('smtp',
				array ('host' => $host,
						'port' => $port,
						'auth' => true,
						'username' => $username,
						'password' => $password));
		
		$mail = $smtp->send($to, $headers, $message);
		
		if (PEAR::isError($mail)) {
			echo $mail->getMessage();
		} else {
			$objDatabase->add_email_history($job_id, $to, $subject, $message);
			echo "Message successfully sent!";
		}
		
	} else {
		echo 'Parameters missing!';
	}
?>