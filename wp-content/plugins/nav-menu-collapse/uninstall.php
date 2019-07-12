<?php
/**
 * Functionality for plugin uninstallation.
 * 
 * @since 1.3.0 Moved version option delete call.
 * @since 1.2.0 Improved process and added faux uninstall definition.
 * @since 1.0.0
 * 
 * @package Nav Menu Collapse
 */

if (!defined('WP_UNINSTALL_PLUGIN') && !defined('NP_FAUX_UNINSTALL_PLUGIN')) exit;

require_once(dirname(__FILE__) . '/includes/definitions.php');

$settings = get_option(NMC_OPTION_SETTINGS);

if (!empty($settings[NMC_SETTING_DELETE_SETTINGS]))
{
	delete_option(NMC_OPTION_VERSION);
	delete_option(NMC_OPTION_SETTINGS);
}

if (!empty($settings[NMC_SETTING_DELETE_USER_META]))
{
	delete_metadata('user', '', 'nmc_collapsed', '', true);
}
