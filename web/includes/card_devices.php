<?php

/**
 * get a list of candidate block devices from the kernel
 */
function get_block_devices() {
	$devices = json_decode(
		shell_exec(LSBLK_CMD),
		null, 
		DEPTH, 
		JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR
	);
	return $devices[BLOCKDEVICES];
}


/**
 * splits a list of candidate block devices into disks and partitions
 *
 * @param device_list	A list previously obtained from get_block_devices()
 * @param &disks	An array of disks, to which found disks will be 
 *			appended.
 * @param &partitions	An array of partitions, to which found partitions
 *			will be appended.
 */
function pick_block_devices($device_list, &$disks, &$partitions) {
	foreach($device_list as $device) {
		switch ($device['type']) {
			case 'disk':
				$disks[] = $device;
				break;
			case 'part':
				$partitions[] = $device;
				break;
		}
	}
}


/**
 * get all suitable card writers
 *
 * A device is considered a suitable card writer if all of the
 * following conditions are met:
 * <ul>
 *   <li>It is a block device of type "disk".</li>
 *   <li>It has the "hotplug" flag set.</li>
 *   <li>It does not have the read-only flag set.</li>
 *   <li>It is not mounted.</li>
 *   <li>None of its partitions are mounted.</li>
*    <li>
 * </ul>
 *
 * @returns		An associative array of card devices.
 */ 
function get_card_devices($debug) {
	$disks = array();
	$partitions = array();
	$carddevs = array();
	$device_list = get_block_devices();
	pick_block_devices($device_list, $disks, $partitions);
	foreach($disks as $d){
		$d['status'] = 'ok';
		if ($d['hotplug'] != 1) continue;
		if ($d['mountpoint']) {
			$d['status'] = 'mounted';
		} else foreach ($partitions as $p) {
			// If any partition is mounted, skip the
			// entire device.
			// We recognize partitions of a device
			// by common prefix.
			$len = strlen($d['path']);
			if (substr($p['path'], 0, $len) == $d['path']
				&& $p['mountpoint']) {
				$d['status'] = 'mounted';
			}
		}
		if ($d['ro']) $d['status'] = 'read-only';
		if ($d['size'] == 0) $d['status'] = 'no_card';
		$carddevs[] = $d;
	}
	if ($debug) {
		unset($d);
		$d['status'] = 'ok';
		$d['path'] = '/dev/null';
		$d['size'] = '1099511627776';
		$carddevs[] = $d;
	}
	return $carddevs;
}
?>