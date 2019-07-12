<?php
/**
 * Global plugin definitions.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Definitions
 */

if (!defined('ABSPATH')) exit;

/**
 * Plugin AJAX check.
 * 
 * @since 1.2.0
 * 
 * @var boolean
 */
define('NMC_AJAX', (defined('DOING_AJAX') && DOING_AJAX));

/**
 * Plugin token.
 * 
 * @since 1.0.0
 * 
 * @var string
 */
define('NMC_TOKEN', 'nav_menu_collapse');

/**
 * Plugin version.
 * 
 * @since 1.0.0
 * 
 * @var string
 */
define('NMC_VERSION', '1.3.1');

/**
 * Plugin version option name.
 * 
 * @since 1.0.0
 * 
 * @var string
 */
define('NMC_OPTION_VERSION', NMC_TOKEN . '_version');

/**
 * Plugin settings option name.
 * 
 * @since 1.0.0
 * 
 * @var string
 */
define('NMC_OPTION_SETTINGS', NMC_TOKEN . '_settings');

/**
 * Setting name for preserving options.
 * 
 * @since 1.2.0
 * 
 * @var string
 */
define('NMC_SETTING_DELETE_SETTINGS', 'delete_settings');

/**
 * Setting name for removing user meta.
 * 
 * @since 1.2.0
 * 
 * @var string
 */
define('NMC_SETTING_DELETE_USER_META', 'delete_user_meta');

/**
 * Plugin support URL.
 * 
 * @since 1.1.0 Renamed definition.
 * @since 1.0.0
 * 
 * @var string
 */
define('NMC_URL_SUPPORT', 'https://wordpress.org/support/plugin/nav-menu-collapse/');

/**
 * Plugin review URL.
 * 
 * @since 1.1.0 Renamed definition.
 * @since 1.0.0
 * 
 * @var string
 */
define('NMC_URL_REVIEW', NMC_URL_SUPPORT . 'reviews/?rate=5#new-post');

/**
 * Plugin translate URL.
 * 
 * @since 1.3.0
 * 
 * @var string
 */
define('NMC_URL_TRANSLATE', 'https://translate.wordpress.org/projects/wp-plugins/nav-menu-collapse');

/**
 * Plugin donate URL.
 *
 * @since 1.3.0
 *
 * @var string
 */
define('NMC_URL_DONATE', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BE5MGPAKBG8TQ');
