<?php
/**
 * Functionality for AJAX calls.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Displays
 * @uses       Nav_Menu_Collapse_Wrapper
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Ajax'))
{
	/**
	 * Class used to implement AJAX functionality.
	 *
	 * @since 1.0.0
	 * 
	 * @uses Nav_Menu_Collapse_Wrapper
	 */
	final class Nav_Menu_Collapse_Ajax extends Nav_Menu_Collapse_Wrapper
	{
		/**
		 * Constructor function.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  Nav_Menu_Collapse $base Base plugin object.
		 * @return void
		 */
		public function __construct(Nav_Menu_Collapse $base)
		{
			parent::__construct($base);

			add_action('wp_ajax_nmc_collapsed', array($this, 'nmc_collapsed'));
		}

		/**
		 * Save the menu collapsed state for the logged in user.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function nmc_collapsed()
		{
			if (isset($_POST['menu_id']) && is_numeric($_POST['menu_id']))
			{
				$valid = true;
				$collapsed_raw = (isset($_POST['collapsed']) && is_array($_POST['collapsed'])) ? $_POST['collapsed'] : array();

				foreach ($collapsed_raw as $id)
				{
					if (!is_numeric($id))
					{
						$valid = false;

						break;
					}
				}

				if ($valid)
				{
					$user_id = get_current_user_id();
					$collapsed = get_user_meta($user_id, 'nmc_collapsed', true);
					$collapsed = (is_array($collapsed)) ? $collapsed : array();
					$collapsed[$_POST['menu_id']] = $_POST['collapsed'];

					update_user_meta($user_id, 'nmc_collapsed', $collapsed);
				}
			}
		}
	}
}
