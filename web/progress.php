<?php
require_once(__DIR__ . '/includes/globals.php');

$raw = null;
$stats = null;
$now = time();

$fp = @fopen(PROGRESS_FILE, 'r');
$expired = (($now - filemtime(PROGRESS_FILE)) > STALE_PROGRESS_TIMEOUT_SECS);

if (!$fp || $expired) {
    error_log("No write in progress.");
    header('HTTP/1.1 204 No Content', true, 204);
} else {
    fseek($fp, -128, SEEK_END);

    // The dcfldd stats are self-overwriting on a single line,
    // which gives us a royal mess of carriage returns (not only
    // at the end of the line, so splitting on those gives pretty
    // random results.
    // Eliminate all control codes:
    $raw = preg_replace('/[[:cntrl:]]/', '', fread($fp, 128));

    // Manually constructing JSON rather than making an object and
    // using json_decode, because we're stingy and the data is simple.
    // Famous last words.
    // Fun fact: we have to eat leading zeros in ints, because they
    // are illegal in JSON (Javascript would treat them as octals).
    // dcfldd prints something like this (writing from /dev/zero to /dev/null,
    // so the totals are meaningless:
// [0% of 775782400Mb] 1366468 blocks (5465872Mb) written. 202:56:31 remaining.
    $stats = preg_replace(
        '/.*\[0*([0-9]+)%\s+of\s+0*([0-9]+)([A-Z-a-z]{2})\]\s+0*([0-9]+)\s+blocks\s+\(0*([0-9]+)([A-Za-z]{2})\)\swritten\.\s+0*([0-9]+):0*([0-9]+):0*([0-9]+)\s+remaining\..*/',
        '{"percent_done":$1,"blocks_done":$4,"data_done":$5,"data_done_unit":"$6","data_total":$2,"data_total_unit":"$3","hours_remaining":$7,"minutes_remaining":$8,"seconds_remaining":$9}',
        $raw);
    //var_dump(json_decode($stats));
    header('Content-Type: application/json; charset=UTF-8');
    print($stats);
}
if ($fp) fclose($fp);

?>