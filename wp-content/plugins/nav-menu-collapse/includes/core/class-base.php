<?php
/**
 * Base plugin functionality.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Base
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse'))
{
	/**
	 * Class used to implement base plugin functionality.
	 *
	 * @since 1.3.0 Improved overall structure for localization.
	 * @since 1.1.2 Removed help object variable.
	 * @since 1.1.0 Added help object, removed display object and moved plugin activation functionality to the setup class.
	 * @since 1.0.0
	 * 
	 * @uses Nav_Menu_Collapse_Wrapper
	 */
	final class Nav_Menu_Collapse extends Nav_Menu_Collapse_Wrapper
	{
		/**
		 * Main instance of Nav_Menu_Collapse.
		 * 
		 * @since 1.0.0
		 * 
		 * @access private static
		 * @var    Nav_Menu_Collapse
		 */
		private static $_instance = null;

		/**
		 * Returns the main instance of Nav_Menu_Collapse.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @param  string            $file Main plugin file.
		 * @return Nav_Menu_Collapse       Main Nav_Menu_Collapse instance. 
		 */
		public static function _get_instance($file)
		{
			if (is_null(self::$_instance))
			{
				self::$_instance = new self($file);
			}

			return self::$_instance;
		}

		/**
		 * File path for the plugin.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @var    string
		 */
		public $_plugin;

		/**
		 * Global cache object.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @var    Nav_Menu_Collapse_Cache
		 */
		public $_cache;

		/**
		 * Global settings object.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @var    Nav_Menu_Collapse_Settings
		 */
		public $_settings;

		/**
		 * Global nav menus object.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @var    Nav_Menu_Collapse_Nav_Menus
		 */
		public $_nav_menus;

		/**
		 * Global AJAX object.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @var    Nav_Menu_Collapse_Ajax
		 */
		public $_ajax;

		/**
		 * Constructor function.
		 * 
		 * @since 1.3.0 Moved functionality to the 'plugins_loaded' function.
		 * @since 1.2.0 Changed AJAX check.
		 * @since 1.1.2 Remove help class declaration.
		 * @since 1.1.1 Added admin init action hook back in so the plugin version is checked when it is updated.
		 * @since 1.1.0 Removed displays object call and admin init action hook.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  string $file Main plugin file.
		 * @return void
		 */
		public function __construct($file)
		{
			if (!empty($file) && file_exists($file))
			{
				$this->_plugin = $file;
				
				add_action('plugins_loaded', array($this, 'plugins_loaded'));
				
				add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
			}
		}
		
		/**
		 * Load plugin text domain.
		 * 
		 * @since 1.3.0 Modified to contain plugin setup functionality.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function plugins_loaded()
		{
			if (Nav_Menu_Collapse_Utilities::is_plugin_active('noakes-menu-manager/noakes-menu-manager.php') && defined('NMM_VERSION') && version_compare(NMM_VERSION, '2.0.0', '>='))
			{
				add_filter('plugin_action_links_' . plugin_basename($this->_plugin), array($this, 'plugin_action_links'), 11);
			}
			else
			{
				$this->_cache = new Nav_Menu_Collapse_Cache($this);
				$this->_settings = new Nav_Menu_Collapse_Settings($this);
				$this->_nav_menus = new Nav_Menu_Collapse_Nav_Menus($this);

				if (NMC_AJAX)
				{
					$this->_ajax = new Nav_Menu_Collapse_Ajax($this);
				}

				add_action('admin_init', array('Nav_Menu_Collapse_Setup', 'check_version'), 0);
				add_action('init', array($this, 'init'), 1000);
			}
		}

		/**
		 * Add links to the plugin page.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array  $links Default links for the plugin.
		 * @param  string $file  Main plugin file name.
		 * @return array         Modified links for the plugin.
		 */
		public function plugin_row_meta($links, $file)
		{
			if ($file == plugin_basename($this->_plugin))
			{
				$links[] = '<a href="' . NMC_URL_SUPPORT . '" target="_blank">' . __('Support', 'nav-menu-collapse') . '</a>';
				$links[] = '<a href="' . NMC_URL_REVIEW . '" target="_blank">' . __('Review', 'nav-menu-collapse') . '</a>';
				$links[] = '<a href="' . NMC_URL_TRANSLATE . '" target="_blank">' . __('Translate', 'nav-menu-collapse') . '</a>';
				$links[] = '<a href="' . NMC_URL_DONATE . '" target="_blank">' . __('Donate', 'nav-menu-collapse') . '</a>';
			}

			return $links;
		}

		/**
		 * Add action links to the plugin list.
		 * 
		 * @since 1.3.0
		 * 
		 * @access public
		 * @param  array $links Existing action links.
		 * @return array        Modified action links.
		 */
		public function plugin_action_links($links)
		{
			array_unshift($links, __('Disabled', 'nav-menu-collapse'));

			return $links;
		}

		/**
		 * Initialize the plugin.
		 * 
		 * @since 1.3.0
		 * 
		 * @access public
		 * @return void
		 */
		public function init()
		{
			load_plugin_textdomain('nav-menu-collapse', false, dirname(plugin_basename($this->_plugin)) . '/languages/');
		}
	}
}
