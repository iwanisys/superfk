<?php
	/**
	 * Collapse/Expand All button template.
	 * 
	 * @since 1.1.0 Changed help button function call.
	 * @since 1.0.0
	 * 
	 * @package Nav Menu Collapse
	 */

	if (!defined('ABSPATH')) exit;
?>

<script type="text/html" id="tmpl-nmc-collapse-expand-all">

	<div id="nmc-collapse-expand-all" class="nmc-collapse-expand-all">

		<button class="nmc-collapse-all button" type="button"><?php

			printf
			(
				__('%s Collapse All', 'nav-menu-collapse'),
				'<span title="' . esc_attr__('Collapse', 'nav-menu-collapse') . '" class="nmc-collapse">–</span>'
			);

		?></button>

		<button class="nmc-expand-all button" type="button"><?php

			printf
			(
				__('%s Expand All', 'nav-menu-collapse'),
				'<span title="' . esc_attr__('Expand', 'nav-menu-collapse') . '" class="nmc-expand">+</span>'
			);

		?></button>

		<?php echo Nav_Menu_Collapse_Output::help_button('nmc-collapse-expand'); ?>

	</div>

</script>
