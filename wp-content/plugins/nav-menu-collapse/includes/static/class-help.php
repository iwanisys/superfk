<?php
/**
 * Functionality for plugin help.
 * 
 * @since 1.1.2 Moved from the includes folder.
 * @since 1.1.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Help
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Help'))
{
	/**
	 * Class used to implement plugin help functionality.
	 *
	 * @since 1.1.2 Made all functions and variable static.
	 * @since 1.1.0
	 */
	final class Nav_Menu_Collapse_Help
	{
		/**
		 * Current help tab ID.
		 * 
		 * @since 1.1.2
		 * 
		 * @access private static
		 * @var    array
		 */
		private static $_current_id = '';

		/**
		 * Help tabs.
		 * 
		 * @since 1.1.2 Made the access static.
		 * @since 1.1.0
		 * 
		 * @access private
		 * @var    array
		 */
		private static $_tabs = array();

		/**
		 * Add a help tab.
		 * 
		 * @since 1.1.2 Made the access static.
		 * @since 1.1.0
		 * 
		 * @access public static
		 * @param  string $id      DOM ID for the help tab.
		 * @param  string $title   Title for the help tab button.
		 * @param  string $heading Option heading for the help tab content. If left empty, the title is used.
		 * @return void
		 */
		public static function add_tab($id, $title, $heading = '')
		{
			if (empty($id) || empty($title)) return;

			$heading = (empty($heading)) ? $title : $heading;

			if (isset(self::$_tabs[$id]))
			{
				self::$_tabs[$id]['title'] = $title;
				self::$_tabs[$id]['heading'] = $heading;
			}
			else
			{
				self::$_tabs[$id] = array
				(
					'title' => $title,
					'heading' => $heading,
					'blocks' => array()
				);
			}

			self::$_current_id = $id;
		}

		/**
		 * Add a content block to the current help tab.
		 * 
		 * @since 1.1.2 Made the access static.
		 * @since 1.1.0
		 * 
		 * @access public static
		 * @param  string $subheading Subheading for the block.
		 * @param  string $content    Content for the block.
		 * @return void
		 */
		public static function add_block($subheading, $content)
		{
			$id = self::$_current_id;

			if (!empty($id))
			{
				self::$_tabs[$id]['blocks'][] = array
				(
					'subheading' => $subheading,
					'content' => $content
				);
			}
		}

		/**
		 * Output the help tabs.
		 * 
		 * @since 1.3.0 Improved help sidebar.
		 * @since 1.1.3 Changed domain name.
		 * @since 1.1.2 Made the access static.
		 * @since 1.1.0
		 * 
		 * @access public static
		 * @param  boolean $settings_page True if the the help tabs are being added to a plugin settings page.
		 * @return void
		 */
		public static function output($settings_page = true)
		{
			if (!NMC_AJAX)
			{
				$screen = Nav_Menu_Collapse()->_cache->screen;

				if ($settings_page)
				{
					$screen->set_help_sidebar('<p><strong>' . __('Plugin developed by', 'nav-menu-collapse') . '</strong><br />' .
						'<a href="https://robertnoakes.com/" target="_blank">Robert Noakes</a></p>' .
						'<hr />' .
						'<p><a href="' . NMC_URL_SUPPORT . '" target="_blank" class="button">' . __('Plugin Support', 'nav-menu-collapse') . '</a></p>' .
						'<p><a href="' . NMC_URL_REVIEW . '" target="_blank" class="button">' . __('Review Plugin', 'nav-menu-collapse') . '</a></p>' .
						'<p><a href="' . NMC_URL_DONATE . '" target="_blank" class="button">' . __('Plugin Donation', 'nav-menu-collapse') . '</a></p>' .
						'<p><a href="' . NMC_URL_TRANSLATE . '" target="_blank" class="button">' . __('Translate Plugin', 'nav-menu-collapse') . '</a></p>');
				}

				foreach (self::$_tabs as $id => $tab)
				{
					$tab_content = '<h3>' . $tab['heading'] . '</h3>';

					foreach ($tab['blocks'] as $block)
					{
						$tab_content .= (empty($block['subheading'])) ? '' : '<h4 style="margin: 0.875em 0 -0.25em; font-size: 1.125em;">' . $block['subheading'] . '</h4>';
						$tab_content .= (empty($block['content'])) ? '' : wpautop($block['content']);
					}

					$screen->add_help_tab(array
					(
						'content' => $tab_content,
						'id' => $id,
						'title' => $tab['title']
					));
				}
			}
		}
	}
}
