<?php namespace Process {

// Includes
use \Exception;
use \DateTime;
use KLogger;

use \DAO\SiteConfig as SiteConfig;

# Abstract Process class.
# Business level processes here
abstract class ProcessBase {

	// Constants
	const LOGLEVEL_KEYNAME = 'LOG_LEVEL';

	// Class vars
	protected $log;
	protected $log_level;

	public function __construct() {
		$date = new DateTime();
		$log_path = 'log/'.get_class($this).'/'.$date->format('Y');

		# Set default log levels
		$this->log_level = SiteConfig::FindByKeyname(self::LOGLEVEL_KEYNAME)->value | KLogger::INFO;
		$this->log = new KLogger($log_path, $this->log_level);

		return $this;
	}

	# Stub for execute
	public function execute() {
		$pid = getmypid();

		$this->log->logInfo("$pid: Started ".get_class($this));

		// Something can be anything, depending upon the child class
		$something = false;
		$error = false;
		try {
			$something = $this->_execute();
		}
		catch (Exception $e) {
			$error = "$pid: EXCEPTION $e";
			$this->log->logFatal($error);
		}

		$this->log->logInfo("$pid: Finished ".get_class($this));

		// Process classes need to throw the exception
		// In case the highest level is bothering to listen
		// I made that rule up just then :)
		if ($error) {
			throw new Exception($error);
		}

		return $something;
	}

	// I can do abstracted protected, but throwing my own dies is more
	// entertaining
	protected function _execute() {
		die (__FUNCTION__." is not implemented for ".get_class($this));
	}

}

} ?>
