<?php 
if (! defined('STDIN')) die(); //command line only!

require_once(__DIR__ . '/../includes/globals.php');
require(__DIR__ . '/../includes/handler.php');

/* tarot_handler is called by the systemd unit tarot.path when the trigger
 * file appears. The trigger file is exclusively created by the web UI, and
 * only read by this handler.
 * systemd is then responsible to clear the trigger file after completion.
 */

check_root();
$trigger = get_trigger();
$state = get_state();

switch ($trigger) {
case TRIGGER_WRITE:
	write($state);
	break;
case TRIGGER_PARTPROBE:
	partprobe();
	break;
default:
	fwrite(STDERR, "Fatal error: Unknown trigger $trigger.\n");
	exit(4);
}
?>
