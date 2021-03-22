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
	foreach($dir as $file) {
		if (preg_match('/.*' . IMAGE_FILE_SUFFIX . '$/', $file)) {
			$images[] = $file;
		}
	}
	return $images;
}

?>
