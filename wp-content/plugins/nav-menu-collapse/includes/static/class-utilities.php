<?php
/**
 * Functionality for third party plugins.
 *
 * @since 1.3.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Utilities
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Utilities'))
{
	/**
	 * Class used to implement utilities functions.
	 *
	 * @since 1.3.0
	 */
	final class Nav_Menu_Collapse_Utilities
	{
		/**
		 * Check a value to see if it is an array or convert to an array if necessary.
		 * 
		 * @since 1.3.0
		 * 
		 * @access public static
		 * @param  mixed $value        Value to turn into an array.
		 * @param  mixed $return_false True if a false value should be returned as-is.
		 * @return array               Checked value as an array.
		 */
		public static function check_array($value, $return_false = false)
		{
			if ($value === false && $return_false)
			{
				return $value;
			}

			if (empty($value))
			{
				$value = array();
			}

			if (!is_array($value))
			{
				$value = array($value);
			}

			return $value;
		}
		
		/**
		 * Remove comments, line breaks and tabs from a code.
		 * 
		 * @since 1.3.0
		 * 
		 * @param  string $code Raw code to clean up.
		 * @return string       Code without comments, line breaks and tabs.
		 */
		public static function clean_code($code)
		{
			$code = preg_replace('/<!--(.*)-->/Uis', '', $code);
			$code = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $code);
			
			return str_replace(array(PHP_EOL, "\r", "\n", "\t"), '', $code);
		}
		
		/**
		 * Check to see if an AJAX action is being executed.
		 * 
		 * @since 1.3.0
		 * 
		 * @access public static
		 * @param  string  $action Action to check for.
		 * @return boolean         True if the action is being executed.
		 */
		public static function is_ajax_action($action)
		{
			if (!NMC_AJAX) return false;

			$current_action = (isset($_GET['action'])) ? $_GET['action'] : '';
			$current_action = (empty($current_action) && isset($_POST['action'])) ? $_POST['action'] : $current_action;

			return ($action == $current_action);
		}

		/**
		 * Check to see if a plugin is active.
		 * 
		 * @since 1.3.0
		 * 
		 * @access public static
		 * @param  string  $path Path for the plugin to check.
		 * @return boolean       True if the plugin is active.
		 */
		public static function is_plugin_active($path)
		{
			if (!function_exists('is_plugin_active'))
			{
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			return is_plugin_active($path);
		}
	}
}
