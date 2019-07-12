<?php
/**
 * Cached functions and flags.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Cache
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Cache'))
{
	/**
	 * Class used to implement cache functionality.
	 *
	 * @since 1.0.0
	 * 
	 * @uses Nav_Menu_Collapse_Wrapper
	 */
	final class Nav_Menu_Collapse_Cache extends Nav_Menu_Collapse_Wrapper
	{
		/**
		 * Get a default cached item based on the provided name.
		 * 
		 * @since 1.2.0 Changed plugin active template function call.
		 * @since 1.1.2 Added variable comments.
		 * @since 1.1.0 Optimized Nav Menu Manager variables and added screen object.
		 * @since 1.0.2 Fixed definition string issue.
		 * @since 1.0.0
		 * 
		 * @access protected
		 * @param  string $name Name of the cached item to return.
		 * @return string       Default cached item if it exists, otherwise an empty string.
		 */
		protected function _default($name)
		{
			switch ($name)
			{
				/* @var string Path to the plugin assets folder. */
				case 'assets_url':

					$folder = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? 'debug' : 'release';

					return trailingslashit(plugins_url('/' . $folder . '/', $this->_base->_plugin));

				/* @var boolean True if a legacy version of Nav Menu Manager is active. */
				case 'has_legacy_nmm':

					return (Nav_Menu_Collapse_Utilities::is_plugin_active('noakes-menu-manager/noakes-menu-manager.php') && (!defined('NMM_VERSION') || version_compare(NMM_VERSION, '1.5.3', '<')));

				/* @var array Asset file names pulled from the manifest JSON. */
				case 'manifest':

					ob_start();

					include(dirname(__FILE__) . '/../../manifest.json');

					return json_decode(ob_get_clean(), true);

				/* @var WP_Screen Current screen object if it exists. */
				case 'screen':

					return (function_exists('get_current_screen')) ? get_current_screen() : '';
			}

			return parent::_default($name);
		}

		/**
		 * Obtain a path to an asset.
		 * 
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  string $path      Path to the asset folder.
		 * @param  string $file_name File name for the asset.
		 * @return string            Full path to the requested asset.
		 */
		public function asset_path($path, $file_name)
		{
			$manifest = $this->manifest;

			if (isset($manifest[$file_name]))
			{
				$file_name = $manifest[$file_name];
			}

			return trailingslashit($this->assets_url . $path) . $file_name;
		}
	}
}
