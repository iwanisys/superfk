<?php
	/**
	 * Collapse/Expand button template.
	 * 
	 * @since 1.0.0
	 * 
	 * @package Nav Menu Collapse
	 */

	if (!defined('ABSPATH')) exit;
?>

<script type="text/html" id="tmpl-nmc-collapse-expand">

	<a href="javascript:;" class="nmc-collapse-expand"><span title="<?php esc_attr_e('Collapse', 'nav-menu-collapse'); ?>" class="nmc-collapse">–</span><span title="<?php esc_attr_e('Expand', 'nav-menu-collapse'); ?>" class="nmc-expand">+</span></a>

</script>
