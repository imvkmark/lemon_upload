<?php namespace App\Lemon\Repositories\Sour;
/*
 * bzip2 class
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */

class Bzip2 {

	const BZIP2_MODE_READ  = 'r';
	const BZIP2_MODE_WRITE = 'w';

	/**
	 * 资源
	 * @var resource
	 */
	private $_rs;

	public function __construct($file, $mode) {
		if ($this->_moduleLoad()) {
			$this->_rs = bzopen($file, $mode);
		}
	}

	private function _moduleLoad() {
		if (!extension_loaded('bz2')) {
			return false;
		} else {
			return true;
		}
	}

	public function compress($source, $blocksize = 4, $workfactor = 0) {
		return bzcompress($source, $blocksize, $workfactor);
	}

	public function decompres($source, $small = 0) {
		return bzdecompress($source, $small);
	}

	public function close() {
		bzclose($this->_rs);
	}

	public function error() {
		return bzerror($this->_rs);
	}

	public function errno() {
		return bzerrno($this->_rs);
	}

	public function errstr() {
		return bzerrstr($this->_rs);
	}

	public function flush() {
		return bzflush($this->_rs);
	}

	public function read($length = '1024') {
		return bzread($this->_rs, $length);
	}

	public function write($data, $length = 0) {
		if ($length) {
			return bzwrite($this->_rs, $data, $length);
		} else {
			return bzwrite($this->_rs, $data);
		}
	}
}