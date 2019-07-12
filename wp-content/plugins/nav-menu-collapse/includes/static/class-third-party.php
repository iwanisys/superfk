<?php
/**
 * Functionality for third party plugins.
 *
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Third Party
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Third_Party'))
{
	/**
	 * Class used to implement third party functions.
	 *
	 * @since 1.0.0
	 */
	final class Nav_Menu_Collapse_Third_Party
	{
		/**
		 * Remove third party plugin meta boxes from the settings page.
		 * 
		 * @since 1.1.0 Changed function name and action priority.
		 * @since 1.0.3 Increased meta boxes action priority.
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @return void
		 */
		public static function remove_meta_boxes()
		{
			add_action('add_meta_boxes', array('Nav_Menu_Collapse_Third_Party', 'remove_third_party_meta_boxes'), 1000);
		}

		/**
		 * Remove third party plugin meta boxes.
		 * 
		 * @since 1.0.3 Added removal for the Essential Grid plugin meta box.
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @return void
		 */
		public static function remove_third_party_meta_boxes()
		{
			$screen = Nav_Menu_Collapse()->_cache->screen;

			remove_meta_box('eg-meta-box', $screen->id, 'normal');
			remove_meta_box('mymetabox_revslider_0', $screen->id, 'normal');
		}
	}
}
