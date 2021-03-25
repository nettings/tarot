<?php

function howlongago($when, $now = 0) {
    if (!$now) $now = time();
    $dt = $now - $when;
    if ($dt < 5) return 'now';
    else if ($dt < 60) return $dt . ' seconds ago';
    else if ($dt < 3600) return ceil($dt / 60) . ' minutes ago';
    else if ($dt <  86400) return ceil($dt / 3600) . ' hours ago';
    else return ceil($dt / 86400) . ' days ago';
}

?>
