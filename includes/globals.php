<?php

/**
 * The lsblk invocation to list all candidate block devices. 
 * 
 * The "--output" configuration determines the structure of the
 * array of devices returned by get_block_devices() and
 * get_card_devices()
 */
define('LSBLKCMD', '/usr/bin/lsblk --json --bytes --output PATH,TYPE,MODEL,SERIAL,HOTPLUG,SIZE,MOUNTPOINT,RO');

define('DEPTH', 4);
define('BLOCKDEVICES', 'blockdevices');
define('IMAGE_PATH', '/local/data/images');
define('IMAGE_FILE_SUFFIX', '\.img');
define('WRITE_CMD', '/usr/bin/dcfldd');

?>