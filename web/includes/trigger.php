<?php

require_once(__DIR__ . '/globals.php');

function trigger($what) {
	$fp = @fopen(TRIGGER_FILE, 'w');
        if (!$fp) {
        	error_log("Error opening " . TRIGGER_FILE
			. __FILE__ . " on line " . __LINE__);
		return $fp;
	}
	$res = @fwrite($fp, $what);
	if (!$res) {
		error_log("Error writing to " . TRIGGER_FILE
			.__FILE__ . " on line " . __LINE__);
		return $res;
	}
	$res = @fclose($fp);
	if (!$res) {
		error_log("Error closing " . TRIGGER_FILE
			. __FILE__ . " on line " . __LINE__);
	}
	return $res;
}


function trigger_write() {
	trigger(TRIGGER_WRITE);
}

function trigger_partprobe() {
	trigger(TRIGGER_PARTPROBE);
}
?>
