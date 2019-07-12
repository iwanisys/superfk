<?php
/**
 * Nav menus functionality.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Nav Menus
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Nav_Menus'))
{
	/**
	 * Class used to implement nav menus functionality.
	 *
	 * @since 1.0.0
	 * 
	 * @uses Nav_Menu_Collapse_Wrapper
	 */
	final class Nav_Menu_Collapse_Nav_Menus extends Nav_Menu_Collapse_Wrapper
	{
		/**
		 * Constructor function.
		 * 
		 * @since 1.2.0 Changed AJAX action template function call.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  Nav_Menu_Collapse $base Base plugin object.
		 * @return void
		 */
		public function __construct(Nav_Menu_Collapse $base)
		{
			parent::__construct($base);

			add_action('load-nav-menus.php', array($this, 'load_nav_menus'));

			if ($this->_base->_cache->has_legacy_nmm)
			{
				add_action('load-nav-menus.php', array($this, 'disable_nmm'), 11);

				if (Nav_Menu_Collapse_Utilities::is_ajax_action('add-menu-item'))
				{
					add_action('admin_init', array($this, 'disable_nmm'), 11);
				}
			}
		}

		/**
		 * Load nav menus page functionality.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function load_nav_menus()
		{
			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 11);
			add_action('admin_footer', array($this, 'admin_footer'));

			$this->add_help_tabs();
		}

		/**
		 * Enqueues scripts for the nav menus page.
		 * 
		 * @since 1.3.0 Removed postbox JS dependency and changed flag variable name.
		 * @since 1.1.0 Added functionality to collapse all collapsible nav menu items by default.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_enqueue_scripts()
		{
			wp_enqueue_style('nmc-style', $this->_base->_cache->asset_path('styles', 'style.css'), array('dashicons'), NMC_VERSION);
			wp_enqueue_script('nmc-script', $this->_base->_cache->asset_path('scripts', 'script.js'), array('wp-util'), NMC_VERSION, true);

			$collapsed = ($this->_base->_settings->store_collapsed_states === '1') ? get_user_meta(get_current_user_id(), 'nmc_collapsed', true) : '1';

			wp_localize_script
			(
				'nmc-script',
				'nmc_script_options',
				
				array
				(
					'is_nav_menus' => true,
					'collapsed' => (is_array($collapsed) || $collapsed === '1') ? $collapsed : array(),
					'nested' => __('%d Nested Menu Items', 'nav-menu-collapse')
				)
			);
		}

		/**
		 * Include the HTML templates in the admin footer.
		 * 
		 * @since 1.3.0 Changed 'clean_code' call.
		 * @since 1.2.0 Changed remove spacing template function call.
		 * @since 1.1.2 Removed extra spacing from the output.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_footer()
		{
			ob_start();

			$templates_path = dirname(__FILE__) . '/../templates/';

			include_once($templates_path . 'collapse-expand-all.php');
			include_once($templates_path . 'collapse-expand.php');

			echo Nav_Menu_Collapse_Utilities::clean_code(ob_get_clean());
		}

		/**
		 * Add the help tabs to the page.
		 * 
		 * @since 1.2.0 Minor help tab improvement.
		 * @since 1.1.2 Made help tab calls static.
		 * @since 1.1.0 Modified help tab output.
		 * @since 1.0.0
		 * 
		 * @access private
		 * @return void
		 */
		private function add_help_tabs()
		{
			if ($this->_base->_cache->has_legacy_nmm)
			{
				$this->_base->_cache->screen->remove_help_tab('nmm-collapse-expand');
			}

			if (!$this->_base->_settings->disable_help_tabs)
			{
				$collapse_expand_label = __('Collapse/Expand', 'nav-menu-collapse');

				Nav_Menu_Collapse_Help::add_tab
				(
					'nmc-collapse-expand',
					$collapse_expand_label,
					
					sprintf
					(
						__('%1$s - %2$s', 'nav-menu-collapse'),
						__('Nav Menu Collapse', 'nav-menu-collapse'),
						$collapse_expand_label
					)
				);

				Nav_Menu_Collapse_Help::add_block
				(
					__('Overview', 'nav-menu-collapse'),
					
					sprintf
					(
						__('Nav menu items with children now have collapse (%1$s) and expand (%2$s) buttons on the right side of the nav menu item bar. Clicking on these buttons will hide/show child nav menu items accordingly. There are also collapse and expand all buttons above the menu to quickly hide or show all child nav menu items.', 'nav-menu-collapse'),
						'<span title="' . esc_attr__('Collapse', 'nav-menu-collapse') . '" class="nmc-collapse">&ndash;</span>',
						'<span title="' . esc_attr__('Expand', 'nav-menu-collapse') . '" class="nmc-expand">+</span>'
					)
				);
				
				Nav_Menu_Collapse_Help::add_block(__('Counts', 'nav-menu-collapse'), '<p>' . __('The number in parenthesis next to the nav menu item title indicates the total number of nested nav menu items.', 'nav-menu-collapse') . '</p>');

				Nav_Menu_Collapse_Help::add_block
				(
					__('Ordering', 'nav-menu-collapse'),
					
					'<ul>' .
					'<li>' . __('While dragging a nav menu item, hover over a collapsed nav menu item for one second to expand it.', 'nav-menu-collapse') . '</li>' .
					'<li>' . __('When a nav menu item is dropped into a collapsed nav menu item, that item will expand automatically.', 'nav-menu-collapse') . '</li>' .
					'</ul>'
				);

				Nav_Menu_Collapse_Help::output(false);
			}
		}

		/**
		 * Disable Nav Menu Manager Collapse/Expand functionality.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function disable_nmm()
		{
			$nmm_nav_menus = Noakes_Menu_Manager()->_nav_menus;

			remove_action('wp_nav_menu_item_custom_fields', array($nmm_nav_menus, 'wp_nav_menu_item_custom_buttons'), 9, 4);

			remove_filter('admin_body_class', array($nmm_nav_menus, 'admin_body_class'));
		}
	}
}
