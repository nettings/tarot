<?php

require_once(__DIR__ . '/globals.php');

function make_dcfldd_cmd($image, $devices) {
	$masked_newline = " \\\n";
	$cmd = WRITE_CMD . $masked_newline;
	$cmd .= '    bs=4M ' . $masked_newline;
	$cmd .= '    if=' . escapeshellarg($image) . $masked_newline;	
	foreach($devices as $dev) {
		$cmd .= '    of=' . escapeshellarg($dev['path']) . $masked_newline;
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
 * @param image		the file name of the image (assumed to be in
 *			IMAGE_PATH.
 * @param devices	an array of devices of the form determined by
 *			get_block_devices()
 * @returns		a shell command string
 */
function make_write_cmd($image, $devices) {
	return make_dcfldd_cmd($image, $devices);
}

?>