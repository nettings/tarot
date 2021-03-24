<?php

require_once(__DIR__ . '/globals.php');


class tarot_state {

	private $last_changed;
	private $devices_last_changed;
	private $caller_ip;
	private $image_list;
	private $image_selected;
	private $device_list;
	private $devices_selected;

	function __construct() {
		$this->last_changed = 0;
		$this->devices_last_changed = 0;
		$this->caller_ip = '';
		$this->image_list = array();
		$this->image_selected = -1;
		$this->device_list = array();
		$this->devices_selected = array();
		$this->update();
	}

	private function update() {
		return $this->last_changed = time();
	}
	// recalculate overflow/ok status depending on size of selected image
	private function update_device_status() {
		if (array_key_exists($this->image_selected, $this->image_list)) {
			$imgsize = (float) $this->image_list[$this->image_selected]['size'];
		} else {
			$imgsize = 0.0;
		}
		foreach ($this->device_list as $k => $v) {
			$devsize = (float) $v['size'];
			if ($v['status'] == 'ok' && $devsize < $imgsize)
				$this->device_list[$k]['status'] = 'overflow';
			if ($v['status'] == 'overflow' && $devsize >= $imgsize)
				$this->device_list[$k]['status'] = 'ok';
		}
	}


	public function is_ready() {
		return (
			$this->last_changed
			&& $this->devices_last_changed
			&& $this->caller_ip
			&& $this->image_list
			&& $this->image_selected > -1
			&& $this->device_list
			&& in_array(true, $this->devices_selected, true)
		);
	}
	public function last_changed() { return $this->last_changed; }
	public function devices_last_changed() { return $this->devices_last_changed; }
	public function set_caller_ip($ip) {
		if (filter_var($ip, FILTER_VALIDATE_IP)) {
			$this->caller_ip = $ip;
			$this->update();
		} else return null;
	}
	public function get_caller_ip() { return $this->caller_ip; }
	public function set_image_list($image_list = array()) {
		$this->image_list = $image_list;
		$this->unselect_image();
		$this->update();
	}
	public function get_image_list() { return $this->image_list; }
	public function select_image($index) {
		if ($index != $this->image_selected) {
			$this->image_selected = (int) $index;
			$this->update_device_status();
		}
	}
	public function unselect_image() {
		if ($this->image_selected != -1) {
			$this->image_selected = -1;
			$this->update_device_status();
		}
	}
	public function get_selected_image() { return $this->image_selected; }
	public function set_device_list($device_list = array()) {
		$this->device_list = $device_list;
		$this->unselect_all_devices();
		$this->devices_last_changed = $this->update();
		if ($this->image_selected > -1) $this->update_device_status();
	}
	public function get_device_list() { return $this->device_list; }
	public function device_is_selected($index) {
		return ($this->devices_selected[(int) $index] === true);
	}
	public function select_device($index) {
		if (array_key_exists($index, $this->device_list)) {
			$this->devices_selected[(int) $index] = true;
			return true;
		} else 	return false;
	}
	public function unselect_device($index) {
		if (array_key_exists($index, $this->device_list)) {
			$this->devices_selected[(int) $index] = false;
			return true;
		} else return false;
	}
	public function unselect_all_devices() {
		$this->devices_selected = array();
		foreach ($this->device_list as $k => $v) {
			$this->devices_selected[$k] = false;
		}
	}
	public function store($location) {
		$fp = @fopen($location, 'w');
		if (!$fp) {
			error_log("Error opening $location for writing in "
				. __FILE__ . " on line " . __LINE__);
			return $fp;
		}
		$res = @fwrite($fp, serialize($this));
		if (!$res) {
			error_log("Error writing to $location in "
				.__FILE__ . " on line " . __LINE__);
			return $res;
		}
		$res = @fclose($fp);
		if (!$res) {
			error_log("Error closing $location in "
			. __FILE__ . " on line " . __LINE__);
		}
		return $res;
	}
	public static function restore($location) {
		$fp = @fopen($location, 'r');
		if (!$fp) {
	                error_log("Error opening $location for reading in "
				. __FILE__ . " on line " . __LINE__);
	                return $fp;
	        }
		$data = @fread($fp, 32767);
		if (!$data) {
			error_log("Error reading from $location in "
				. __FILE__ . " on line " .__LINE__);
			return $data;
		}
		$obj = unserialize($data);
		if ($obj instanceof tarot_state) {
			return $obj;
		} else {
			return null;
		}
	}
}
?>
