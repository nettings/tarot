<?php

require_once(__DIR__ . '/globals.php');

function get_dcfldd_cmd($state) {
	$img = $state->get_image_list()[$state->get_selected_image()]['name'];
	$devices = $state->get_device_list();
	$masked_newline = " \\\n";
	$cmd = WRITE_CMD . ' bs=4M sizeprobe=if statusinterval=4' . $masked_newline;
	$cmd .= '  if=' . escapeshellarg(IMAGE_PATH . '/' . $img) . $masked_newline;
	foreach($devices as $n => $dev) {
		if ($state->device_is_selected($n)) {
			$cmd .= '  of=' . escapeshellarg($dev['path']) . $masked_newline;
		}
	}
	return $cmd;
}

/**
 * construct a command to write images to list of devices
 *
 * @param state		a tarot_state object
 *
 * @returns		a shell command string
 */
function get_write_cmd($state) {
	if ($state->is_ready()) {
		return get_dcfldd_cmd($state);
	} else {
		return '';
	}
}

?>
