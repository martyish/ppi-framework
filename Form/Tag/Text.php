<?php
/**
 * Form class will help in automating rendering forms
 *
 * @author    Paul Dragoonis <dragoonis@php.net>
 * @license   http://opensource.org/licenses/mit-license.php MIT
 * @package   Form
 * @link      www.ppiframework.com
 *
 */
class PPI_Form_Tag_Text extends PPI_Form_Tag implements PPI_Form_Tag_Interface {

	/**
	 * The constructor
	 *
	 * @param array $options
	 */
	function __construct(array $options = array()) {
		$this->_attributes = $options;
	}

	/**
	 * Getter and setter for attributes
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function attr($name, $value = '') {
		return parent::attr($name, $value);
	}

	/**
	 * Check if an attribute exists
	 *
	 * @param string $attr
	 * @return bool
	 */
	function hasAttr($attr) {
		return parent::hasAttr($attr);
	}

	/**
	 * Set the value of this field
	 *
	 * @param string $value
	 * @return void
	 */
	function setValue($value) {
		$this->_attributes['value'] = $value;
	}

	/**
	 * Render this tag
	 *
	 * @return string
	 */
	function render() {
		return '<input type="text" ' . $this->buildAttrs() . '>';
	}

	/**
	 * When echo'ing this tag class, we call render
	 *
	 * @return string
	 */
	function __toString() {
		return $this->render();
	}
}
