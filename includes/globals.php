<?php

define('PROGNAME', 'tarot');
define('VERSION', '0.0.1');
define('AUTHORS', 'Jörn Nettingsmeier [nettings@luchtbeweging.nl]');
define('ROOT', realpath(__DIR__ . '/../'));
define('DOCROOT', @dirname($_SERVER['REQUEST_URI']));
define('HOST', gethostname());

/**
 * The lsblk invocation to list all candidate block devices. 
 * 
 * The "--output" configuration determines the structure of the
 * array of devices returned by get_block_devices() and
 * get_card_devices()
 */
define('LSBLKCMD', '/usr/bin/lsblk --json --bytes --output PATH,TYPE,MODEL,SERIAL,HOTPLUG,SIZE,MOUNTPOINT,RO');
define('DEPTH', 4); // parse depth of returned json. only three levels actually used
define('BLOCKDEVICES', 'blockdevices'); // json subtree element we're interested in

define('STATCMD', "/usr/bin/stat -c '%s'");
define('IMAGE_PATH', ROOT . '/images');
define('IMAGE_FILE_SUFFIX', '\.img'); // escape for preg_match!
define('WRITE_CMD', '/usr/bin/dcfldd');

define('B2GIB', 1.0 / (2.0 ** 30.0));

?>
