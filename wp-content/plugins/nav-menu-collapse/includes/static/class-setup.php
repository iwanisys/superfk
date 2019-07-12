<?php
/**
 * Plugin setup functionality.
 * 
 * @since 1.1.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Setup
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Setup'))
{
	/**
	 * Class used to implement setup functions.
	 *
	 * @since 1.1.0
	 */
	final class Nav_Menu_Collapse_Setup
	{
		/**
		 * Plugin activation hook.
		 * 
		 * @since 1.3.0
		 * 
		 * @access public static
		 * @return void
		 */
		public static function activate()
		{
			//Nothing to see here.
		}
		
		/**
		 * Check and update the plugin version.
		 * 
		 * @since 1.3.0 Changed functionality layout.
		 * @since 1.2.0 Improved version check.
		 * @since 1.1.1 Renamed function.
		 * @since 1.1.0 Moved from the base class, renamed the function and made it static.
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @return void
		 */
		public static function check_version()
		{
			$current_version = get_option(NMC_OPTION_VERSION);

			if (empty($current_version))
			{
				add_option(NMC_OPTION_VERSION, NMC_VERSION);
			}
			else if ($current_version != NMC_VERSION)
			{
				update_option(NMC_OPTION_VERSION, NMC_VERSION);
				
				if (version_compare($current_version, '1.1.0', '<'))
				{
					self::pre_one_one_zero();
				}
			}
		}

		/**
		 * Clean up plugin settings for Nav Menu Collapse versions earlier than 1.1.0.
		 * 
		 * @since 1.1.2 Improved call to exclude the array check.
		 * @since 1.1.0
		 * 
		 * @access private
		 * @return void
		 */
		private static function pre_one_one_zero()
		{
			$plugin_settings = get_option(NMC_OPTION_SETTINGS);
			$plugin_settings['store_collapsed_states'] = '1';

			update_option(NMC_OPTION_SETTINGS, $plugin_settings);

			Nav_Menu_Collapse()->_settings->load_option($plugin_settings);
		}
	}
}
