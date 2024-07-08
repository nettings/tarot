<?php 

/* This file is executed by the systemd unit tarot.path when the trigger
 * file appears. The trigger file is exclusively created by the web UI, and
 * only read (and subsequently deleted) by this handler.
 */

if (! defined('STDIN')) die(); //command line only!

require_once(__DIR__ . '/../includes/globals.php');
require(__DIR__ . '/../includes/tarot_state.php');
require(__DIR__ . '/../includes/write_cmd.php');

function check_root() {
	if (posix_getuid() != 0) {
		fwrite(STDERR, "Fatal error: " . __FILE__ . " must be run as root.\n");
		exit(2);
	}
	return true;
}

function get_trigger() {
	$fp = @fopen(TRIGGER_FILE, 'r');
	if (!$fp) {
		fwrite(STDERR, "Fatal error: Could not open trigger file " . TRIGGER_FILE . "\n");
		exit(3);
	}
	$trigger = @fread($fp, 128);
	if (!$trigger) {
		fwrite(STDERR, "Fatal error: Could not read trigger file " . TRIGGER_FILE . "\n");
		exit(3);
	}
	fclose($fp);
	return $trigger;
}

function get_state() {
	$state = tarot_state::restore(STATE_FILE);
	if (!$state) {
		fwrite(STDERR, "No tarot_state found at " . STATE_FILE . ".\n");
		fwrite(STDERR, "Please run web interface first!\n");
		exit(4);
	}
	return $state;
}

function write($state) {
	$cmd = get_write_cmd($state);
	$cmd .= '  2>&1 | cat > ' . PROGRESS_FILE;
	print("Executing $cmd\n");
	exec($cmd, $out, $retval);
	return $retval;
}

function partprobe() {
	fwrite(STDERR, "partprobe is not implemented yet.\n");
}

function delete_trigger() {
	if (!unlink(TRIGGER_FILE)) {
		fwrite(STDERR, "Error: Could not delete file " . TRIGGER_FILE . ".\n");
		return false;
	}
	return true;
}

check_root();
$trigger = get_trigger();
delete_trigger();
$state = get_state();

switch ($trigger) {
case TRIGGER_WRITE:
	write($state);
	break;
case TRIGGER_PARTPROBE:
	partprobe();
	break;
default:
	fwrite(STDERR, "Fatal error: Unknown trigger $trigger.\n");
	exit(4);
}


?>
