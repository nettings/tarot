<?php

/**
 * get a list of suitable image files in IMAGE_PATH 
 *
 * @returns		An array of image file names.
 */
function get_images($debug) {
	$dir = scandir(IMAGE_PATH);
	$images = array();
	$n = 0;
	foreach($dir as $file) {
		if (preg_match('/.*' . IMAGE_FILE_SUFFIX . '$/', $file)) {
			$images[$n]['name'] = $file;
			$images[$n]['size'] = shell_exec(STAT_CMD . ' ' .escapeshellarg(IMAGE_PATH . '/' . $file));
			$n++;
		}
	}
	if ($debug) {
		$images[$n]['name'] = 'zero';
		$images[$n]['size'] = 0;
	}
	return $images;
}

?>
