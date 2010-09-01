<?php

/**
 *
 * @version   1.0
 * @author    Paul Dragoonis <dragoonis@php.net>
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Digiflex Development
 * @package   PPI
 */

class PPI_Cache_Memcached implements PPI_Interface_Cache {
	
	private $_handler;
	
	function __construct() {
		$this->_handler = new Memcached();
	}
	
	/**
	 * Get a value from cache
	 * @param string $p_sKey The Key
	 * @return mixed
	 */	
	function get($p_sKey) {
		return $this->_handler->get($p_sKey);
	}
	
	/**
	 * Set a value in the cache
	 * @param string $p_sKey The Key
	 * @param mixed $p_mData The Data
	 * @param integer $p_iTTL The Time To Live
	 */	
	function set($p_sKey, $p_mData, $p_iTTL) {
		return $this->_handler->set($p_sKey, $p_mData,0, $p_iTTL);
	}
	
	/**
	 * Check if a key exists in the cache
	 * @param string $p_mKey The Key
	 * @return boolean
	 */	
	function exists($p_sKey) {}
	
	/**
	 * Remove a key from the cache
	 * @param string $p_sKey The Key
	 * @return boolean
	 */	
	function remove($p_sKey) {		
		return $this->_handler->delete($key);
	}
	
	/**
	 * Add a memcached server to connect to
	 * @param string $host The Hostname
	 * @param numeric $port The Port
	 * @param $weight
	 */
	function addServer($host, $port = 11211, $weight = 10) {
		$this->_handler->addServer($host, $port, true, $weight);
	}
	
}