<?php

require_once(__DIR__ . '/globals.php');

/**
 * get a list of suitable image files in IMAGE_PATH 
 *
 * @returns		An array of image file names.
 */
function get_images() {
	$dir = scandir(IMAGE_PATH);
	$images = array();
	$n = 0;
	foreach($dir as $file) {
		if (preg_match('/.*' . IMAGE_FILE_SUFFIX . '$/', $file)) {
			$images[$n]['name'] = $file;
			$images[$n]['size'] = shell_exec(STATCMD . ' ' .escapeshellarg(IMAGE_PATH . '/' . $file));
			$n++;
		}
	}
	return $images;
}

?>