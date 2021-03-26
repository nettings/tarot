<?php

define('PREFIX', '/usr/local');
define('DESTDIR', '/');
define('WEBROOT', '/var/www/html');
define('APPROOT', WEBROOT . '/tarot');
define('WEBUSER', 'www-data');
define('WEBGROUP', 'www-data');
define('IMAGE_PATH', '/local/images');
define('IMAGE_FILE_SUFFIX', '\.img'); // escape for preg_match!
define('STATE_PATH', '/var/lib/tarot');
define('STATE_FILE', STATE_PATH . '/tarot.state');
define('TRIGGER_FILE', STATE_PATH . '/tarot.trigger');
define('PROGRESS_FILE', STATE_PATH . '/tarot.progress');
?>
