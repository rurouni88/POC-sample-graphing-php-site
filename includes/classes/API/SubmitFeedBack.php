<?php namespace API {

// Includes
include ('includes/classes/PHPMailer/class.phpmailer.php');

use \Exception;
use Validation;
use DAO\SiteConfig as SiteConfig;
use \PHPMailer as MailOut;

# API Class for SubmitFeedBack
class SubmitFeedBack extends APIBase {
	// Constants
	const CONFIG_EMAIL  = 'FEEDBACK_EMAIL';
	const CONFIG_SERVER = 'SMTP_SERVER';
	const CONFIG_PORT   = 'SMTP_PORT';

	// Class Variables
	public $posted_var;

	public function __construct($posted_var) {
		$this->posted_var = $posted_var;
		return parent::__construct();
	}

	protected function executeRequest() {
		$var = $this->posted_var;
		$required = array ('subject', 'name', 'email_from', 'details');
		$errors = Validation::isNotEmpty($var, $required);

		$from = (isset($var['email_from'])) ? $var['email_from'] : '';

		if (!Validation::isEmail($from)) {
			array_push($errors, "'$from' is not a valid email address");
		}

		if(count($errors) <= 0) {
			try {
				$this->_execute();
				$this->success = true;
				$this->message = "Feedback sent from $from successfully. Thank you.";
			}
			catch (Exception $e) {
				array_push($errors, $e->getMessage());
			}
		}

		$this->errors = $errors;

		return true;
	}

	private function _execute() {
		$var = $this->posted_var;
		$from = $var['email_from'];
		$name = $var['name'];
		$subject = $var['subject'];
		$details = $var['details'];

		// Load up the configuration values
		$to = SiteConfig::FindByKeyname(self::CONFIG_EMAIL)->value;
		$server = SiteConfig::FindByKeyname(self::CONFIG_SERVER)->value;
		$port = SiteConfig::FindByKeyname(self::CONFIG_PORT)->value;

		// Time to send
		$mail = new MailOut();
		$mail->IsSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use), 1 = client messages, 2 = client and server messages
		$mail->SMTPDebug = 0;
		$mail->do_debug = 0;
//		$mail->Debugoutput = 'html';
		$mail->Host = $server;
		$mail->Port = $port; // Default 25, Can be 465 or 587
		$mail->SMTPAuth   = false;

		$mail->SetFrom($from, $name);

		$mail->AddAddress($to, 'PC');
		$mail->Subject = $subject;

		$mail->Body = $details;
		$mail->WordWrap = 50;

		if(!$mail->Send()) {
			$error = sprintf(
				"Mailer Error (%s,%s): %s",
				$server, $port, $mail->ErrorInfo
			);
			throw new Exception($error);
		}

		return true;
	}
}

} ?>
