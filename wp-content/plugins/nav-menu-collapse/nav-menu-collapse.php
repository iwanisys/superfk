<?php
/**
 * Plugin Name: Nav Menu Collapse
 * Plugin URI:  https://wordpress.org/plugins/nav-menu-collapse/
 * Description: Adds functionality to collapse and expand nav menus within the WordPress admin.
 * Version:     1.3.1
 * Author:      Robert Noakes
 * Author URI:  https://robertnoakes.com/
 * Text Domain: nav-menu-collapse
 * Domain Path: /languages/
 * Copyright:   (c) 2018-2019 Robert Noakes (mr@robertnoakes.com)
 * License:     GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Main plugin file.
 * 
 * @since 1.3.0 Removed global variable and prevent functionality load when use with Nav Menu Manager 2.0.0 or later.
 * @since 1.2.0 Optimized functionality load check.
 * @since 1.1.4 Moved core classes into their own folder.
 * @since 1.1.2 Moved the help include to the static folder.
 * @since 1.1.1 Removed plugin activation hook since it isn't fired when the plugin is updated.
 * @since 1.1.0 Updated include files as necessary and added plugin activation hook.
 * @since 1.0.0
 * 
 * @package Nav Menu Collapse
 */

if (!defined('ABSPATH')) exit;

$includes_path = plugin_dir_path(__FILE__) . 'includes/';

require_once($includes_path . 'definitions.php');

if (is_admin() || NMC_AJAX)
{
	$core_path = $includes_path . 'core/';
	
	require_once($core_path . 'class-wrapper.php');
	require_once($core_path . 'class-base.php');
	require_once($core_path . 'class-cache.php');
	require_once($core_path . 'class-settings.php');
	require_once($core_path . 'class-nav-menus.php');
	require_once($core_path . 'class-ajax.php');

	$standalone_path = $includes_path . 'standalone/';

	require_once($standalone_path . 'class-field.php');
	require_once($standalone_path . 'class-meta-box.php');

	$static_path = $includes_path . 'static/';

	require_once($static_path . 'class-help.php');
	require_once($static_path . 'class-output.php');
	require_once($static_path . 'class-setup.php');
	require_once($static_path . 'class-third-party.php');
	require_once($static_path . 'class-utilities.php');
	
	register_activation_hook(__FILE__, array('Nav_Menu_Collapse_Setup', 'activate'));

	/**
	 * Returns the main instance of Nav_Menu_Collapse.
	 * 
	 * @since 1.0.0
	 * 
	 * @return Nav_Menu_Collapse Main Nav_Menu_Collapse instance.
	 */
	function Nav_Menu_Collapse()
	{
		return Nav_Menu_Collapse::_get_instance(__FILE__);
	}
	
	Nav_Menu_Collapse();
}
