<?php namespace API {

// Includes
use \Exception;
use \DateTime;
use KLogger;
use SiteMessage;
use DAO\SiteConfig as SiteConfig;

# Simple API Base class.
# Logs my API requests
abstract class APIBase {
	// Constants
	const LOGLEVEL_KEYNAME = 'LOG_LEVEL';

	// Class vars
	protected $log;
	protected $log_level;

	protected $success;
	protected $message;
	protected $errors;
	protected $item;

	public function __construct() {

		# Set default log levels
		$this->log_level = SiteConfig::FindByKeyname(self::LOGLEVEL_KEYNAME)->value | KLogger::INFO;
		$date = new DateTime();
		$log_path = 'log/'.get_class($this).'/'.$date->format('Y');
		$this->log = new KLogger($log_path, $this->log_level);

		$this->success = false;
		$this->message = false;
		$this->errors = array();
		$this->item = false;

		return $this;
	}

	# Stub for execute
	public function execute() {
		$pid = getmypid();
		$this->log->logInfo("$pid: Request From ".$_SERVER['SERVER_NAME']."(".$_SERVER['REMOTE_ADDR'].")"); 
		$this->log->logInfo("$pid: Started ".get_class($this));

		$this->log->logDebug("$pid: LOG LEVEL = ".$this->log_level);
		$this->log->logDebug("$pid: POST VAR = ".json_encode($_POST));
		$this->log->logDebug("$pid: GET VAR = ".json_encode($_GET));

		try {
			$this->executeRequest();
		}
		catch (Exception $e) {
			$this->log->logFatal("$pid: EXCEPTION $e");
		}

		$response = new SiteMessage(
			$this->success, $this->message,
			$this->errors, $this->item
		);

		$this->log->logDebug("$pid: RESPONSE = ".json_encode($response));
		$this->log->logInfo("$pid: Finished ".get_class($this));

		return $response;
	}

	// I can do abstracted protected, but throwing my own dies is more
	// entertaining
	protected function executeRequest() {
		die (__FUNCTION__." is not implemented for ".get_class($this));
	}
}

} ?>
