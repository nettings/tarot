<?php
require_once(__DIR__ . '/includes/globals.php');


$stats = file_get_contents(PROGRESS_FILE, false, null, 256);

$percent_done = preg_replace('/(.*)(\[([0-9]{1,3})% of .*\])(.|\n)*/', '$3', $stats);
$time_remaining = preg_replace('/(.*)([0-9]{2}:[0-9]{2}:[0-9]{2})(.|\n)*/', '$2', $stats); 

$result = [
    'percent_done' => (int) $percent_done,
    'time_remaining' => $time_remaining
];

header('Content-Type: application/json; charset=UTF-8');
print(json_encode($result));
?>