<?php
/**
 * Functionality for outputting content.
 * 
 * @since 1.1.0 Changed file name and class name.
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Output
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Output'))
{
	/**
	 * Class used to implement output functions.
	 *
	 * @since 1.0.0
	 */
	final class Nav_Menu_Collapse_Output
	{
		/**
		 * Generates a button that opens a specified help tab.
		 * 
		 * @since 1.1.0 Made function static.
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @param  string  $help_tab_id Optional ID for the help tab to open.
		 * @param  boolean $disabled    Optional flag to disable the help button by default.
		 * @return string               Generated help button.
		 */
		public static function help_button($help_tab_id = '', $disabled = false)
		{
			if (function_exists('is_customize_preview') && is_customize_preview()) return '';

			$leading_space = (empty($help_tab_id)) ? '' : ' ';
			$help_label = __('Help', 'nav-menu-collapse');
			$class = ($disabled) ? ' nmc-disabled' : '';

			return (empty(Nav_Menu_Collapse()->_settings->disable_help_buttons) || empty($leading_space)) ? $leading_space . '<a href="javascript:;" title="' . esc_attr($help_label) . '" class="nmc-help-button dashicons dashicons-editor-help' . $class . '" tabindex="-1" data-nmc-help-tab-id="' . esc_attr($help_tab_id) . '">' . $help_label . '</a>' : '';
		}

		/**
		 * Outputs an options page.
		 * 
		 * @since 1.1.0 Made function static.
		 * @since 1.0.0
		 * 
		 * @access public static
		 * @param  string $option_name Option name to generate the page for.
		 * @param  string $heading     Optional heading to display at the top of the options page.
		 * @return void
		 */
		public static function options_page($option_name, $heading = '')
		{
			$option_name = (empty($option_name)) ? '' : sanitize_key($option_name);

			if ($option_name != '')
			{
				$heading = ($heading == '') ? __('Nav Menu Collapse', 'nav-menu-collapse') : $heading;
				$screen = Nav_Menu_Collapse()->_cache->screen;
				$columns = $screen->get_columns();
				$columns = (empty($columns)) ? 2 : $columns;

				echo '<div id="' . $option_name . '" class="wrap">' .
					'<h1>' . $heading . '</h1>' .
					'<form action="options.php" method="post">';

				settings_fields($option_name);
				wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
				wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);

				echo '<div id="poststuff">' .
					'<div id="post-body" class="metabox-holder columns-' . $columns . '">' .
					'<div id="postbox-container-1" class="postbox-container">';

				do_meta_boxes($screen->id, 'side', '');

				echo '</div>' .
					'<div id="postbox-container-2" class="postbox-container">';

				do_meta_boxes($screen->id, 'advanced', '');
				do_meta_boxes($screen->id, 'normal', '');

				echo '</div>' .
					'</div>' .
					'</div>' .
					'</form>' .
					'</div>';
			}
		}
	}
}
