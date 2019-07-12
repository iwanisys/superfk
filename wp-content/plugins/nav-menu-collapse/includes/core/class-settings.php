<?php
/**
 * Settings page functionality.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Settings
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Settings'))
{
	/**
	 * Class used to implement settings functionality.
	 *
	 * @since 1.1.2 Moved default values to the load_option function.
	 * @since 1.1.0 Moved finalize meta boxes function to the meta box class and optimized meta boxes and help tab output.
	 * @since 1.0.0
	 * 
	 * @uses Nav_Menu_Collapse_Wrapper
	 */
	final class Nav_Menu_Collapse_Settings extends Nav_Menu_Collapse_Wrapper
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

			$this->load_option();

			add_action('admin_init', array($this, 'admin_init'));
			add_action('admin_menu', array($this, 'admin_menu'));

			add_filter('plugin_action_links_' . plugin_basename($this->_base->_plugin), array($this, 'plugin_action_links'), 11);
		}

		/**
		 * Load the plugin settings option.
		 * 
		 * @since 1.3.0 Updated default uninstall settings.
		 * @since 1.1.2 Added default values and update call to set properties.
		 * @since 1.1.0 Removed return false flag from check array call.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array $settings Setting array to load, or null of the settings should be loaded from the database.
		 * @return void
		 */
		public function load_option($settings = null)
		{
			$defaults = array
			(
				/* @var boolean True if collapse/expand states should be saved for each user. */
				'store_collapsed_states' => false,

				/* @var boolean True if help buttons should be disabled. */
				'disable_help_buttons' => false,

				/* @var boolean True if help tabs should be disabled. */
				'disable_help_tabs' => false,

				/* @var boolean True if plugin settings should be deleted when the plugin is uninstalled. */
				NMC_SETTING_DELETE_SETTINGS => false,

				/* @var boolean True if user meta should be deleted when the plugin is uninstalled. */
				NMC_SETTING_DELETE_USER_META => false
			);

			$settings = (empty($settings)) ? get_option(NMC_OPTION_SETTINGS) : $settings;

			$this->_set_properties($defaults, $settings);
		}

		/**
		 * Register the settings option.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_init()
		{
			register_setting(NMC_OPTION_SETTINGS, NMC_OPTION_SETTINGS, array($this, 'sanitize'));
		}

		/**
		 * Sanitize the settings.
		 * 
		 * @since 1.2.0 Added check for new uninstall settings.
		 * @since 1.1.0 Added array check for the raw settings.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array $input Raw settings array.
		 * @return array        Sanitized settings array.
		 */
		public function sanitize($input)
		{
			if (!is_array($input)) return array();

			foreach ($input as $name => $value)
			{
				if ($name == 'disable_help_tabs')
				{
					$input[$name] = (isset($input['disable_help_buttons']) && $input['disable_help_buttons']) ? $input[$name] : false;
				}
				else if ($name == NMC_SETTING_DELETE_SETTINGS)
				{
					$delete_settings_unconfirmed = NMC_SETTING_DELETE_SETTINGS . '_unconfirmed';
					
					$input[$name] = (isset($input[$delete_settings_unconfirmed]) && $input[$delete_settings_unconfirmed]) ? $input[$name] : false;
				}
				else if ($name == NMC_SETTING_DELETE_USER_META)
				{
					$delete_user_meta_unconfirmed = NMC_SETTING_DELETE_USER_META . '_unconfirmed';
					
					$input[$name] = (isset($input[$delete_user_meta_unconfirmed]) && $input[$delete_user_meta_unconfirmed]) ? $input[$name] : false;
				}
				else
				{
					$input[$name] = sanitize_text_field($input[$name]);
				}
			}

			return $input;
		}

		/**
		 * Add the settings page.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_menu()
		{
			$plugin_label = __('Nav Menu Collapse', 'nav-menu-collapse');
			$settings_page = add_options_page($plugin_label, $plugin_label, 'manage_options', NMC_OPTION_SETTINGS, array($this, 'settings_page'));

			add_action('load-' . $settings_page, array($this, 'load_settings'));
		}

		/**
		 * Generate the settings page.
		 * 
		 * @since 1.1.0 Changed options page function call.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function settings_page()
		{
			Nav_Menu_Collapse_Output::options_page
			(
				NMC_OPTION_SETTINGS,
				
				sprintf
				(
					__('%1$s %2$s', 'nav-menu-collapse'),
					__('Nav Menu Collapse', 'nav-menu-collapse'),
					__('Settings', 'nav-menu-collapse')
				)
			);
		}

		/**
		 * Load settings page functionality.
		 * 
		 * @since 1.1.0 Optimized meta box and help tab calls.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function load_settings()
		{
			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 11);

			add_screen_option
			(
				'layout_columns',
				
				array
				(
					'default' => 2,
					'max' => 2
				)
			);

			$this->add_meta_boxes();

			Nav_Menu_Collapse_Help::output();
		}

		/**
		 * Enqueues scripts for the settings page.
		 * 
		 * @since 1.3.0 Removed plugin CSS/JS dependencies and changed flag variable name.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_enqueue_scripts()
		{
			wp_enqueue_style('nmc-style', $this->_base->_cache->asset_path('styles', 'style.css'), array('dashicons'), NMC_VERSION);
			wp_enqueue_script('nmc-script', $this->_base->_cache->asset_path('scripts', 'script.js'), array('postbox'), NMC_VERSION, true);

			wp_localize_script
			(
				'nmc-script',
				'nmc_script_options',
				
				array
				(
					'is_settings' => true
				)
			);
		}

		/**
		 * Add meta boxes to the page.
		 * 
		 * @since 1.2.0 Added additional help descriptions and more efficient uninstall settings.
		 * @since 1.1.0 Removed screen argument and optimized meta box generation.
		 * @since 1.0.2 Fixed definition string issue.
		 * @since 1.0.0
		 * 
		 * @access private
		 * @return void
		 */
		private function add_meta_boxes()
		{
			$plugin_label = __('Nav Menu Collapse', 'nav-menu-collapse');

			$general_settings_box = new Nav_Menu_Collapse_Meta_Box(array
			(
				'context' => 'normal',
				'help_tab_id' => 'nmc-general-settings',
				'id' => 'general_settings',
				'option_name' => NMC_OPTION_SETTINGS,
				'title' => __('General Settings', 'nav-menu-collapse'),
				
				'help_description' => sprintf
				(
					__('The settings in this box are general settings for %s.', 'faucet-manager'),
					$plugin_label
				)
			));

			$general_settings_box->add_field(array
			(
				'description' => __('Store collapsed states for each menu on a user-by-user basis.', 'nav-menu-collapse'),
				'help_description' => __('All collapsible nav menu items are collapsed by default. With this option enabled, the state of the collapased items is stored within the meta for each user.', 'nav-menu-collapse'),
				'label' => __('Store Collapsed States', 'nav-menu-collapse'),
				'name' => 'store_collapsed_states',
				'type' => 'checkbox',
				'value' => $this->store_collapsed_states
			));

			$general_settings_box->add_field(array
			(
				'label' => __('Disable Help Buttons', 'nav-menu-collapse'),
				'name' => 'disable_help_buttons',
				'type' => 'checkbox',
				'value' => $this->disable_help_buttons,

				'description' => sprintf
				(
					__('Remove help buttons specific to %s.', 'nav-menu-collapse'),
					$plugin_label
				),


				'help_description' => sprintf
				(
					__('Removes all help buttons (%1$s) associated with %2$s functionality. The help buttons are meant for users that aren\'t yet familiar with the plugin.', 'nav-menu-collapse'),
					Nav_Menu_Collapse_Output::help_button(),
					$plugin_label
				)
			));

			$general_settings_box->add_field(array
			(
				'classes' => ($this->disable_help_buttons) ? array() : array('nmc-hidden'),
				'label' => __('Disable Help Tabs', 'nav-menu-collapse'),
				'name' => 'disable_help_tabs',
				'type' => 'checkbox',
				'value' => $this->disable_help_tabs,

				'conditional' => array
				(
					array
					(
						'field' => 'disable_help_buttons',
						'value' => '1'
					)
				),

				'description' => sprintf
				(
					__('Remove help tabs specific to %s.', 'nav-menu-collapse'),
					$plugin_label
				),

				'help_description' => sprintf
				(
					__('Removes all help tabs associated with %s functionality. The help tabs are meant for users that aren\'t yet familiar with the plugin.', 'nav-menu-collapse'),
					$plugin_label
				)
			));

			$save_all_field = array
			(
				'content' => __('Save All Settings', 'nav-menu-collapse'),
				'type' => 'submit'
			);

			$general_settings_box->add_field($save_all_field);

			$uninstall_settings_box = new Nav_Menu_Collapse_Meta_Box(array
			(
				'context' => 'normal',
				'help_tab_id' => 'nmc-uninstall-settings',
				'id' => 'uninstall_settings',
				'option_name' => NMC_OPTION_SETTINGS,
				'title' => __('Uninstall Settings', 'nav-menu-collapse'),
				
				'help_description' => sprintf
				(
					__('The settings in this box give you control over what data should be removed when %s is uninstalled.', 'faucet-manager'),
					$plugin_label
				)
			));

			$delete_settings_description = sprintf
			(
				__('Delete settings for %s when the plugin is uninstalled.', 'nav-menu-collapse'),
				$plugin_label
			);
			
			$delete_settings_label = __('Delete Plugin Settings', 'nav-menu-collapse');
			$delete_settings_unconfirmed = NMC_SETTING_DELETE_SETTINGS . '_unconfirmed';
			$delete_settings_value = $this->{NMC_SETTING_DELETE_SETTINGS};
			
			$uninstall_settings_box->add_field(array
			(
				'classes' => ($delete_settings_value) ? array('nmc-hidden') : array(),
				'description' => $delete_settings_description,
				'label' => $delete_settings_label,
				'name' => $delete_settings_unconfirmed,
				'type' => 'checkbox',
				'value' => $delete_settings_value,

				'help_description' => sprintf
				(
					__('When %s is uninstalled, all associated plugin settings are deleted in the process. This option must be confirmed before it is saved.', 'nav-menu-collapse'),
					$plugin_label
				)
			));

			$uninstall_settings_box->add_field(array
			(
				'classes' => ($delete_settings_value) ? array() : array('nmc-confirmation nmc-hidden'),
				'description' => $delete_settings_description,
				'label' => ($delete_settings_value) ? $delete_settings_label : __('Confirm Delete Plugin Settings', 'nav-menu-collapse'),
				'name' => NMC_SETTING_DELETE_SETTINGS,
				'type' => 'checkbox',
				'value' => $delete_settings_value,

				'conditional' => array
				(
					array
					(
						'field' => $delete_settings_unconfirmed,
						'value' => '1'
					)
				)
			));

			$delete_user_meta_description = sprintf
			(
				__('Delete user meta for %s when the plugin is uninstalled.', 'nav-menu-collapse'),
				$plugin_label
			);
			
			$delete_user_meta_label = __('Delete User Meta', 'nav-menu-collapse');
			$delete_user_meta_unconfirmed = NMC_SETTING_DELETE_USER_META . '_unconfirmed';
			$delete_user_meta_value = $this->{NMC_SETTING_DELETE_USER_META};
			
			$uninstall_settings_box->add_field(array
			(
				'classes' => ($delete_user_meta_value) ? array('nmc-hidden') : array(),
				'description' => $delete_user_meta_description,
				'label' => $delete_user_meta_label,
				'name' => $delete_user_meta_unconfirmed,
				'type' => 'checkbox',
				'value' => $delete_user_meta_value,

				'help_description' => sprintf
				(
					__('When %s is uninstalled, all associated user meta is deleted in the process. This option must be confirmed before it is saved.', 'nav-menu-collapse'),
					$plugin_label
				)
			));

			$uninstall_settings_box->add_field(array
			(
				'classes' => ($delete_user_meta_value) ? array() : array('nmc-confirmation nmc-hidden'),
				'description' => $delete_user_meta_description,
				'label' => ($delete_user_meta_value) ? $delete_user_meta_label : __('Confirm Delete User Meta', 'nav-menu-collapse'),
				'name' => NMC_SETTING_DELETE_USER_META,
				'type' => 'checkbox',
				'value' => $delete_user_meta_value,

				'conditional' => array
				(
					array
					(
						'field' => $delete_user_meta_unconfirmed,
						'value' => '1'
					)
				)
			));

			$uninstall_settings_box->add_field($save_all_field);

			Nav_Menu_Collapse_Meta_Box::finalize_meta_boxes();
		}

		/**
		 * Add action links to the plugin list.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array $links Existing action links.
		 * @return array        Modified action links.
		 */
		public function plugin_action_links($links)
		{
			array_unshift($links, '<a href="' . esc_url(admin_url('options-general.php?page=' . NMC_OPTION_SETTINGS)) . '">' . __('Settings', 'nav-menu-collapse') . '</a>');

			return $links;
		}
	}
}
