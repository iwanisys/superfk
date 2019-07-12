<?php
/**
 * Abstract wrapper for core class functionality.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Wrapper
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Wrapper'))
{
	/**
	 * Abstract class used to implement core class functionality.
	 *
	 * @since 1.3.0 Removed '_check_array' function.
	 * @since 1.1.2 Added '_set_properties' and '_check_array' functions.
	 * @since 1.0.0
	 */
	abstract class Nav_Menu_Collapse_Wrapper
	{
		/**
		 * Base plugin object.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @var    Nav_Menu_Collapse
		 */
		public $_base = null;

		/**
		 * The stored properties.
		 * 
		 * @since 1.3.0 Changed access.
		 * @since 1.1.2 Changed access to private.
		 * @since 1.0.0
		 * 
		 * @access protected
		 * @var    array
		 */
		protected $_properties = array();

		/**
		 * Constructor function.
		 * 
		 * @since 1.1.0 Made base object argument optional.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  Nav_Menu_Collapse $base Optional base plugin object. If empty, the global object is used.
		 * @return void
		 */
		public function __construct(Nav_Menu_Collapse $base = null)
		{
			$this->_base = (empty($base)) ? Nav_Menu_Collapse() : $base;
		}

		/**
		 * Get a property based on the provided name.
		 * 
		 * @since 1.1.2 Modified for null default value.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  string $name Name of the property to return.
		 * @return string       Property if it is found, otherwise an empty string.
		 */
		public function __get($name)
		{
			if (!isset($this->_properties[$name]) || is_null($this->_properties[$name]))
			{
				return $this->_properties[$name] = $this->_default($name);
			}

			return $this->_properties[$name];
		}

		/**
		 * Check to see if a property exists with the provided name.
		 * 
		 * @since 1.1.2 Modified to work with empty() calls.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  string  $name Name of the property to check.
		 * @return boolean       True if the property is set, otherwise false.
		 */
		public function __isset($name)
		{
			if (!isset($this->_properties[$name]) || is_null($this->_properties[$name]))
			{
				$default = $this->_default($name);

				if (!is_null($default))
				{
					$this->_properties[$name] = $default;
				}
			}

			return isset($this->_properties[$name]);
		}

		/**
		 * Set the property with the provided name to the provided value.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  string $name  Name of the property to set.
		 * @param  string $value Value of the property to set.
		 * @return void
		 */
		public function __set($name, $value)
		{
			$this->_properties[$name] = $value;
		}

		/**
		 * Unset the property with the provided name.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  string $name Name of the property to unset.
		 * @return void
		 */
		public function __unset($name)
		{
			unset($this->_properties[$name]);
		}

		/**
		 * Set the initial properties for the object.
		 * 
		 * @since 1.3.0 Changed 'check_array' calls.
		 * @since 1.1.2
		 * 
		 * @access protected
		 * @param  array $defaults Default properties for the object.
		 * @param  array $options  Optional specific options for the object.
		 * @return void
		 */
		protected function _set_properties($defaults, $options = array())
		{
			$defaults = Nav_Menu_Collapse_Utilities::check_array($defaults);

			$this->_properties = (empty($options)) ? $defaults : array_merge($defaults, Nav_Menu_Collapse_Utilities::check_array($options));
		}

		/**
		 * Get a default property based on the provided name.
		 * 
		 * @since 1.1.2 Changed default value to null.
		 * @since 1.0.0
		 * 
		 * @access protected
		 * @param  string $name Name of the property to return.
		 * @return string       Empty string.
		 */
		protected function _default($name)
		{
			return null;
		}

		/**
		 * Push a value into a property array.
		 * 
		 * @since 1.1.2 Modified to allow for the array index to be specified.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  string $name  Name of the property array to push the value into.
		 * @param  string $value Value to push into the property array.
		 * @param  mixed  $index Optional array index for the value to push.
		 * @return void
		 */
		public function _push($name, $value, $index = null)
		{
			$property = $this->$name;

			if (is_array($property))
			{
				if (is_null($index))
				{
					$property[] = $value;
				}
				else
				{
					$property[$index] = $value;
				}
			}

			$this->$name = $property;
		}
	}
}
