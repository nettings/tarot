<?php

define('INC', __DIR__ .'/includes/');
require_once(INC . 'globals.php');
require(INC . 'card_devices.php');
require(INC . 'images.php');
require(INC . 'write_cmd.php');

print("SD Card duplicator\n");

$size = 12 * (2 ** 30);
$sd_writers = get_card_devices($size);
//print_r($devices);
print_r($sd_writers);
$images = get_images();
print_r($images);
print(make_write_cmd($images[0], $sd_writers));



?>