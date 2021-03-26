<?php 
require_once(__DIR__ . '/../includes/globals.php');
require(__DIR__ . '/../includes/tarot_state.php');
require(__DIR__ . '/../includes/write_cmd.php');

function delete($file) {
	if (!unlink($file)) {
		fwrite(STDERR, "Error: Could not delete file $file.\n");
		return false;
	}
	return true;
}

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
	delete(TRIGGER_FILE);
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
	$cmd .= '  2>&1  | cat > ' . PROGRESS_FILE;
	print("Executing $cmd\n");
	exec($cmd, $out, $retval);
	return $retval;
}

function partprobe() {
	fwrite(STDERR, "partprobe is not implemented yet.\n");
}

?>
