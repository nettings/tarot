<?php

define('PROGNAME', 'tarot');
define('VERSION', '0.0.1');
define('AUTHORS', 'Jörn Nettingsmeier [nettings@luchtbeweging.nl]');
define('PROJECT_HOME', 'https://github.com/nettings/tarot');
define('HOST', gethostname());
@define('DOCROOT', dirname($_SERVER['REQUEST_URI']. '42')); // add arbitrary string in case URI is just a dir

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
define('WRITE_CMD', '/usr/bin/dcfldd');
define('TRIGGER_WRITE', 'Toast \'em!');
define('TRIGGER_PARTPROBE', 'Show me what you\'ve got!');
define('B2GIB', 1.0 / (2.0 ** 30.0));

include(__DIR__ . '/config.php');

?>
