<?php
/**
 * Meta box functionality.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Meta Box
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Meta_Box'))
{
	/**
	 * Class used to implement the meta box object.
	 *
	 * @since 1.1.2 Removed call to _default.
	 * @since 1.0.0
	 * 
	 * @uses Nav_Menu_Collapse_Wrapper
	 */
	class Nav_Menu_Collapse_Meta_Box extends Nav_Menu_Collapse_Wrapper
	{
		/**
		 * Constructor function.
		 * 
		 * @since 1.2.0 Added help description default and output.
		 * @since 1.1.2 Added default values and updated call to set properties.
		 * @since 1.1.0 Removed base object argument, optimized action call and added help tab creation.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array $options Optional options for the meta box.
		 * @return void
		 */
		public function __construct($options = array())
		{
			parent::__construct();

			$defaults = array
			(
				/* @var callable Function used to populate the meta box. */
				'callback' => array($this, 'callback'),

				/* @var array Data that should be set as the $args property of the box array. */
				'callback_args' => null,

				/* @var array CSS classes added to the meta box. */
				'classes' => array('nmc-meta-box'),

				/* @var string Context within the screen where the boxes should display. */
				'context' => 'advanced',

				/* @var array Field displayed in the meta box. */
				'fields' => array(),

				/* @var string Description displayed below the heading in the help tab. */
				'help_description' => '',

				/* @var string ID for the help tab associated with the meta box. */
				'help_tab_id' => '',

				/* @var string Base ID for the meta box. */
				'id' => '',

				/* @var string Option name for the fields in the meta box. */
				'option_name' => '',

				/* @var string Priority within the context where the boxes should show. */
				'priority' => 'default',

				/* @var string Title displayed in the meta box. */
				'title' => ''
			);

			$this->_set_properties($defaults, $options);

			if (is_callable($this->callback) && !empty($this->id) && $this->title != '')
			{
				$this->id = NMC_TOKEN . '_meta_box_' . $this->id;

				if (!$this->_base->_settings->disable_help_tabs && !empty($this->help_tab_id))
				{
					Nav_Menu_Collapse_Help::add_tab($this->help_tab_id, $this->title);
					
					if (!empty($this->help_description))
					{
						Nav_Menu_Collapse_Help::add_block('', $this->help_description);
					}
				}

				add_action('add_meta_boxes', array($this, 'add_meta_box'));
			}
		}

		/**
		 * Add the meta box to the page.
		 * 
		 * @since 1.1.0 Changed help button function call and screen object reference.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @return void
		 */
		public function add_meta_box()
		{
			$title = esc_html($this->title);
			$title .= ($this->help_tab_id == '') ? '' : Nav_Menu_Collapse_Output::help_button($this->help_tab_id);

			add_meta_box($this->id, $title, $this->callback, $this->_base->_cache->screen, $this->context, $this->priority, $this->callback_args);

			add_filter('postbox_classes_' . esc_attr($this->_base->_cache->screen->id) . '_' . esc_attr($this->id), array($this, 'postbox_classes'));
		}

		/**
		 * The default callback that is fired for the meta box when one isn't provided.
		 * 
		 * @since 1.1.2 Improved check array call.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  object $post Post object associated with the callback.
		 * @return void
		 */
		public function callback($post)
		{
			$has_option_name = ($this->option_name != '');

			$this->fields = Nav_Menu_Collapse_Utilities::check_array($this->fields);

			foreach ($this->fields as $field)
			{
				if (is_a($field, 'Nav_Menu_Collapse_Field'))
				{
					if ($has_option_name)
					{
						$field->option_name = $this->option_name;
					}

					$field->output(true);
				}
			}

			wp_nonce_field($this->id, $this->id . '_nonce', false);
		}

		/**
		 * Add additional classes to meta boxes.
		 * 
		 * @since 1.3.0 Changed 'check_array' call.
		 * @since 1.1.2 Improved check array call.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array $classes Current meta box classes.
		 * @return array          Modified meta box classes.
		 */
		public function postbox_classes($classes)
		{
			$this->classes = Nav_Menu_Collapse_Utilities::check_array($this->classes);

			return array_merge($classes, $this->classes);
		}

		/**
		 * Add a field to the meta box.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array $options Options for the field to add.
		 * @return void
		 */
		public function add_field($options)
		{
			$this->_push('fields', new Nav_Menu_Collapse_Field($options));
		}

		/**
		 * Finalize the settings meta boxes.
		 * 
		 * @since 1.1.0 Moved from settings class, made the function static and removed the screen argument.
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @return void
		 */
		public static function finalize_meta_boxes()
		{
			self::side_meta_boxes();

			Nav_Menu_Collapse_Third_Party::remove_meta_boxes();

			do_action('add_meta_boxes', Nav_Menu_Collapse()->_cache->screen->id, null);
		}

		/**
		 * Generate the side meta boxes.
		 * 
		 * @since 1.3.0 Restructured side meta boxes.
		 * @since 1.1.3 Changed domain name.
		 * @since 1.1.0 Removed arguments and reorganized side meta boxes.
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @return void
		 */
		public static function side_meta_boxes()
		{
			$version = '-' . str_replace('.', '-', NMC_VERSION);
			
			$support_box = new Nav_Menu_Collapse_Meta_Box(array
			(
				'context' => 'side',
				'id' => 'support' . $version,
				'title' => __('Support', 'nav-menu-collapse')
			));

			$support_box->add_field(array
			(
				'type' => 'html',

				'content' => __('Plugin developed by', 'nav-menu-collapse') . '<br />' .
					'<a href="https://robertnoakes.com/" target="_blank"><img src="' . Nav_Menu_Collapse()->_cache->asset_path('images', 'robert-noakes.png') . '" height="67" width="514" alt="Robert Noakes" class="robert-noakes" /></a>'
			));
			
			$support_box->add_field(array
			(
				'type' => 'html',
				
				'content' => __('Running into issues with the plugin?', 'nav-menu-collapse') . '<br />' .
					'<a href="' . NMC_URL_SUPPORT . '" target="_blank">' . __('Please submit a ticket.', 'nav-menu-collapse') . '</a>'
			));
			
			$support_box->add_field(array
			(
				'type' => 'html',
				
				'content' => __('Have some feedback you\'d like to share?', 'nav-menu-collapse') . '<br />' .
					'<a href="' . NMC_URL_REVIEW . '" target="_blank">' . __('Please submit a review.', 'nav-menu-collapse') . '</a>'
			));
			
			$support_box->add_field(array
			(
				'type' => 'html',
				
				'content' => __('Would you like to support development?', 'nav-menu-collapse') . '<br />' .
					'<a href="' . NMC_URL_DONATE . '" target="_blank">' . __('Make a small donation.', 'nav-menu-collapse') . '</a>'
			));
			
			$support_box->add_field(array
			(
				'type' => 'html',
				
				'content' => __('Want to see the plugin in your language?', 'nav-menu-collapse') . '<br />' .
					'<a href="' . NMC_URL_TRANSLATE . '" target="_blank">' . __('Assist with plugin translation.', 'nav-menu-collapse') . '</a>'
			));
			
			$advertising_box = new Nav_Menu_Collapse_Meta_Box(array
			(
				'context' => 'side',
				'id' => 'advertising' . $version,
				'title' => __('Better Hosting with WPEngine', 'nav-menu-collapse')
			));
			
			$advertising_box->add_field(array
			(
				'content' => '<a target="_blank" href="https://shareasale.com/r.cfm?b=1144535&amp;u=1815763&amp;m=41388&amp;urllink=&amp;afftrack="><img src="https://static.shareasale.com/image/41388/YourWordPressDXP300x600.png" border="0" /></a>',
				'type' => 'html'
			));
		}
	}
}
