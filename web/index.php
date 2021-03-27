<?php

define('INC', __DIR__ . '/includes');
define('HTM', __DIR__ . '/html');
require_once(INC . '/globals.php');
require(INC . '/card_devices.php');
require(INC . '/images.php');
require(INC . '/tarot_state.php');
require(INC . '/trigger.php');
require(INC . '/write_cmd.php');
require(INC . '/util.php');

$caller_ip = $_SERVER['REMOTE_ADDR'];
$form_data = $_POST;
$state = null;
$lastmod = 0;
$cmd = '';

$debug = array_key_exists('debug', $_GET)
        || array_key_exists('DEBUG', $_GET)
        || array_key_exists('Debug', $_GET);
        
// Restore state unless user requests to forget or steal the session
if (!(array_key_exists('reset', $form_data)
        || array_key_exists('steal', $form_data))) {
        $state = tarot_state::restore(STATE_FILE);
}

// If there is no state yet, create one.
if (!$state) {
        $state = new tarot_state();
        $state->set_caller_ip($caller_ip);
        error_log('Created new tarot_state.');
} else {
        error_log('Using existing tarot_state.');
}

// Developer wants to test session stealing code
if ($debug && array_key_exists('fudgeip', $form_data)) {
        $state->set_caller_ip('0.0.0.0');
}

// This web GUI must be a singleton!
// Check if caller IP is the same that created the session.
// If not, ask to steal session explicitly.
if ($state->get_caller_ip()
        && $state->get_caller_ip() != $caller_ip
        && !array_key_exists('steal', $form_data)) {
        $lastmod = howlongago($state->last_changed());
        include(HTM . '/header.php');
        include(HTM . '/steal.php');
        include(HTM . '/footer.php');
        exit;
}

// Scan for images if requested.
// Otherwise update image selection if it has changed.
if (array_key_exists('scanimg', $form_data)) {
        $state->set_image_list(get_images($debug));
} else if (array_key_exists('image', $form_data)) {
        $state->select_image($form_data['image']);
}

// Scan for card writers if requested.
// Otherwise update device selection if it has changed.
if (array_key_exists('scanwrt', $form_data)) {
        $state->set_device_list(get_card_devices($debug));
} else {
        foreach($state->get_device_list() as $n => $writer) {
                // selecting a device is straightforward
                if (array_key_exists('writer_' . $n, $form_data)
                        && $writer['status'] == 'ok'
                ) {
                        $state->select_device($n);
                // unselect only if user clicked to confirm
                // the selection (otherwise we lose selections
                // when returning from writing etc.), or if the
                // device is not in state 'ok'.
                } else if (array_key_exists('confwrt', $form_data)
                        || $writer['status'] != 'ok') {
                        $state->unselect_device($n);
                }
        }
}

$cmd = get_write_cmd($state);
$state->store(STATE_FILE);
$lastmod = howlongago($state->last_changed());

include(HTM . '/header.php');
// Perform write if requested, show config screen otherwise
if (array_key_exists('write', $form_data)) {
        trigger_write();
        include(HTM . '/write.php');
} else if (array_key_exists('partprb', $form_data)) {
        trigger_partprobe();
        include(HTM . '/partprobe.php');
} else {
        include(HTM . '/main.php');
}
include(HTM . '/footer.php');

?>
