<?php

	// Allow displaying errors
	@ini_set('display_errors', 0);
	@ini_set('display_startup_errors', 0);
	// @error_reporting(E_ALL | E_STRICT);
	@error_reporting(E_ALL | E_STRICT);
	@ini_set('log_errors', 0);
	@ini_set('error_log', dirname(dirname(__FILE__)) . '/temp/php.' . time() . '.log');

?>