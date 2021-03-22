#!/usr/bin/php
<?php

require_once(__DIR__ . '/globals.php');

/**
 * get a list of candidate block devices from the kernel
 */
function get_block_devices() {
	$devices = json_decode(
		shell_exec(LSBLKCMD), 
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
 *   <li>Its size is greater than $size.</li>
 * </ul>
 *
 * @param size 		The minimum size of a device in bytes.
 * @returns		An associative array of card devices, the 
 *                      format of which is determined by LSBLKCMD.
 */ 
function get_card_devices($size) {
	$disks = array();
	$partitions = array();
	$carddevs = array();
	$device_list = get_block_devices();
	pick_block_devices($device_list, $disks, $partitions);
	foreach($disks as $disk){
		if ($disk['mountpoint']) continue;
		if ($disk['hotplug'] != 1) continue;
		if ($disk['size'] < $size) continue;
		if ($disk['ro']) continue;
		$used = false;
		foreach ($partitions as $partition) {
			// we recognize partitions of a device by common prefix
			$len = strlen($disk['path']);
			if (substr($partition['path'], 0, $len) == $disk['path'] 
				&& $partition['mountpoint']
			) { 
				$used = true;
			}
		}
		if (!$used) {
			$carddevs[] = $disk;
		}
	}
	return $carddevs;
}

?>