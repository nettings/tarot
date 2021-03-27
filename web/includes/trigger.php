<?php

require_once(__DIR__ . '/globals.php');

/**
 /  writes a magic trigger file that is acted upon by
 * a systemd path unit.
 * It is that unit's responsibility to clear the trigger
 * file afterwards, hence there is no deletion function 
 * implemented here.
 *
 * @param $what		one of TRIGGER_WRITE or TRIGGER_PARTPROBE.
 */
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
}

function trigger_write() {
	trigger(TRIGGER_WRITE);
}

function trigger_partprobe() {
	trigger(TRIGGER_PARTPROBE);
}
?>
