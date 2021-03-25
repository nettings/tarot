<?php

require_once(__DIR__ . '/globals.php');

function make_dcfldd_cmd($state) {
	$img = $state->get_image_list()[$state->get_selected_image()]['name'];
	$devices = $state->get_device_list();
	$masked_newline = " \\\n";
	$cmd = WRITE_CMD . $masked_newline;
	$cmd .= '    bs=4M ' . $masked_newline;
	$cmd .= '    if=' . escapeshellarg($img) . $masked_newline;
	foreach($devices as $n => $dev) {
		if ($state->device_is_selected($n)) {
			$cmd .= '    of=' . escapeshellarg($dev['path']) . $masked_newline;
		}
	}
	$cmd .= '    sizeprobe=if' .$masked_newline;
	$cmd .= '    statusinterval=4';
	return $cmd;
}

/*
     dd_cmd = "sudo dcfldd bs=4M if=" + img_file
        dd_cmd += " of=" + " of=".join(devices)
        dd_cmd += " sizeprobe=if statusinterval=4 2>&1 | sudo tee "
        dd_cmd += config['DuplicatorSettings']['Logs'] + "/progress.info"
        dd_cmd += " && echo \"osid_completed_task\" | sudo tee -a "
        dd_cmd += config['DuplicatorSettings']['Logs'] + "/progress.info"
*/

/**
 * construct a command to write images to list of devices
 *
 * @param state		a tarot_state object
 *
 * @returns		a shell command string
 */
function make_write_cmd($state) {
	if ($state->is_ready()) {
		return make_dcfldd_cmd($state);
	} else {
		return '';
	}
}

?>
