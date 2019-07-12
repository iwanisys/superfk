/*! Primary plugin JavaScript. * @since 1.0.0 * @package Nav Menu Collapse */

/**
 * Options object.
 * 
 * @since 1.3.0 Changed variable name.
 * @since 1.0.0
 * 
 * @var object
 */
var nmc_script_options = nmc_script_options || {};

/**
 * Main WordPress utilities object.
 * 
 * @since 1.0.0
 * 
 * @var object
 */
var wp = window.wp || {};

/**
 * Main WordPress nav menus object.
 * 
 * @since 1.0.0
 * 
 * @var object
 */
var wpNavMenu = wpNavMenu || {};

/**
 * Nav menu item currently being dragged.
 * 
 * @since 1.0.0
 * 
 * @var object
 */
var nmc_dragged_item = null;

/**
 * Last nav menu item dropped.
 * 
 * @since 1.0.0
 * 
 * @var object
 */
var nmc_dropped_item = null;

/**
 * Item currently hovered over.
 * 
 * @since 1.0.0
 * 
 * @var object
 */
var nmc_hovered_item = null;

/**
 * WordPress AJAX URL.
 * 
 * @since 1.0.0
 * 
 * @var string
 */
var ajaxurl = ajaxurl || '';

/**
 * Current WordPress admin page ID.
 * 
 * @since 1.0.0
 * 
 * @var string
 */
var pagenow = pagenow || '';

/**
 * WordPress postboxes object.
 * 
 * @since 1.0.0
 * 
 * @var object
 */
var postboxes = postboxes || {};

(function ($)
{
	'use strict';

	$.fn.extend(
	{
		/**
		 * Add a custom event to all provided elements.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.fn.nmc_add_event
		 * @this   object     Elements to add the event to.
		 * @param  string   e Event name to add to all elements.
		 * @param  function f Function executed when the event is fired.
		 * @return object     Updated elements.
		 */
		"nmc_add_event": function (e, f)
		{
			return this.addClass(e).on(e, f).nmc_trigger_all(e);
		},

		/**
		 * Fire an event on all provided elements.
		 * 
		 * @since 1.1.0 Modified function to allow for extra arguments.
		 * @since 1.0.0
		 * 
		 * @access jQuery.fn.nmc_trigger_all
		 * @this   object      Elements to fire the event on.
		 * @param  string e    Event name to fire on all elements.
		 * @param  array  args Extra arguments to pass to the event call.
		 * @return object      Triggered elements.
		 */
		"nmc_trigger_all": function (e, args)
		{
			args = ($.type(args) === 'undefined') ? [] : args;
			args = ($.isArray(args)) ? args : [args];

			return this
			.each(function ()
			{
				$(this).triggerHandler(e, args);
			});
		},
		
		/**
		 * Check for and return unprepared elements.
		 * 
		 * @since 1.3.0
		 * 
		 * @access jQuery.fn.nmc_unprepared
		 * @this   object              Elements to check.
		 * @param  string class_suffix Suffix to add to the prepared class name.
		 * @return object              Unprepared elements.
		 */
		"nmc_unprepared": function (class_suffix)
		{
			var class_name = 'nmc-prepared';
			class_name += (class_suffix) ? '-' + class_suffix : '';
			
			return this.not('.' + class_name).addClass(class_name);
		}
	});

	/**
	 * General variables.
	 * 
	 * @since 2.0.0
	 * 
	 * @access jQuery.nmc
	 * @var    object
	 */
	$.nmc = $.nmc || {};

	$.extend($.nmc,
	{
		"body": $(document.body),
		"document": $(document),
		"options": nmc_script_options,
		"window": $(window),
		
		"scroll_element": $('html,body')
		.on('DOMMouseScroll keyup mousedown mousewheel scroll touchmove wheel', function ()
		{
			$(this).stop();
		})
	});

	/**
	 * Custom data variable names.
	 * 
	 * @since 1.0.0
	 * 
	 * @access jQuery.nmc.data
	 * @var    object
	 */
	$.nmc.data = $.nmc.data || {};
	
	$.extend($.nmc.data,
	{
		"help_tab_id": 'nmc-help-tab-id'
	});

	/**
	 * Custom event names.
	 * 
	 * @since 1.0.0
	 * 
	 * @access jQuery.nmc.events
	 * @var    object
	 */
	$.nmc.events = $.nmc.events || {};
	
	$.extend($.nmc.events,
	{
		"setup": 'nmc-setup'
	});

	/**
	 * Global JSON object.
	 * 
	 * @since 1.0.0
	 * 
	 * @access jQuery.nmc.global
	 * @var    object
	 */
	$.nmc.global = $.nmc.global || {};

	$.extend($.nmc.global,
	{
		/**
		 * Prepare plugin help buttons.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.global.help_buttons
		 * @param  object parent Parent object that contains the help buttons to prepare.
		 * @return void
		 */
		"help_buttons": function (parent)
		{
			var buttons = (typeof parent === 'undefined') ? $('#contextual-help-wrap .nmc-help-button[data-' + $.nmc.data.help_tab_id + '],.wrap .nmc-help-button[data-' + $.nmc.data.help_tab_id + ']').not('.nmc-disabled') : parent.find('.nmc-help-button');

			buttons.nmc_unprepared()
			.css(
			{
				"display": 'inline-block',
				"opacity": '1'
			})
			.click(function (e)
			{
				e.stopPropagation();

				$.nmc.scroll_element
				.animate(
				{
					"scrollTop": '0px'
				},
				{
					"queue": false
				});
				
				var clicked = $(this);
				var screen_options = $('#show-settings-link');
				
				var open_help = function ()
				{
					$('#tab-link-' + clicked.data($.nmc.data.help_tab_id) + ' > a').click();
					$('#contextual-help-link').not('.screen-meta-active').click();
				};
				
				if (screen_options.hasClass('screen-meta-active'))
				{
					screen_options.click();
					
					setTimeout(open_help, 250);
				}
				else
				{
					open_help();
				}
			});

			$('#screen-options-wrap .nmc-help-button').remove();
		},

		/**
		 * Prepare plugin help tabs.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.global.help_tabs
		 * @return void
		 */
		"help_tabs": function ()
		{
			var help = $('#contextual-help-columns');

			help.find('li[id^="tab-link-nmc-"],.help-tab-content[id^="tab-panel-nmc-"]')
			.each(function ()
			{
				var current = $(this);
				current.appendTo(current.parent());
			});

			help.find('.contextual-help-tabs > ul,.contextual-help-tabs-wrap')
			.each(function ()
			{
				$(this).children().removeClass('active').first().addClass('active');
			});
		}
	});
	
	$.nmc.document
	.ready(function ()
	{
		$.nmc.global.help_buttons();
		$.nmc.global.help_tabs();
	});

	if ($.nmc.options.is_settings)
	{
		/**
		 * Custom data variable names specific to settings.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.data
		 * @var    object
		 */
		$.extend($.nmc.data,
		{
			"compare": 'nmc-compare',
			"conditional": 'nmc-conditional',
			"field": 'nmc-field',
			"value": 'nmc-value'
		});

		/**
		 * Custom event names specific to settings.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.events
		 * @var    object
		 */
		$.extend($.nmc.events,
		{
			"check_conditions": 'nmc-check-conditions'
		});

		/**
		 * Settings JSON object.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.settings
		 * @var    object
		 */
		$.nmc.settings = $.nmc.settings || {};

		$.extend($.nmc.settings,
		{
			/**
			 * Prepare fields with conditional logic.
			 * 
			 * @since 1.3.0 Increased animation speed.
			 * @since 1.2.0 Changed hidden class.
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.settings.conditional_logic
			 * @return void
			 */
			"conditional_logic": function ()
			{
				$('.nmc-condition[data-' + $.nmc.data.conditional + '][data-' + $.nmc.data.field + '][data-' + $.nmc.data.value + '][data-' + $.nmc.data.compare + ']')
				.each(function ()
				{
					var condition = $(this);
					var conditional = $('[name="' + condition.data($.nmc.data.conditional) + '"]');
					var field = $('[name="' + condition.data($.nmc.data.field) + '"]');

					if (!conditional.hasClass($.nmc.events.check_conditions))
					{
						conditional
						.nmc_add_event($.nmc.events.check_conditions, function ()
						{
							var current_conditional = $(this);
							var show_field = true;

							$('.nmc-condition[data-' + $.nmc.data.conditional + '="' + current_conditional.attr('name') + '"][data-' + $.nmc.data.field + '][data-' + $.nmc.data.value + '][data-' + $.nmc.data.compare + ']')
							.each(function ()
							{
								var current_condition = $(this);
								var current_field = $('[name="' + current_condition.data($.nmc.data.field) + '"]');
								var current_value = (current_field.is(':radio')) ? current_field.filter(':checked').val() : current_field.val();
								var compare = current_condition.data($.nmc.data.compare);
								var compare_matched = false;

								if (current_field.is(':checkbox'))
								{
									current_value = (current_field.is(':checked')) ? current_value : '';
								}

								if (compare === '!=')
								{
									compare_matched = (current_condition.data($.nmc.data.value) + '' !== current_value + '');
								}
								else
								{
									compare_matched = (current_condition.data($.nmc.data.value) + '' === current_value + '');
								}

								show_field = (show_field && compare_matched);
							});

							var parent = current_conditional.closest('.nmc-field');
							parent.next('.nmc-field-spacer').remove();

							if (show_field)
							{
								parent.stop(true).slideDown(125);
							}
							else
							{
								parent.stop(true).slideUp(125).after($('<div/>').addClass('nmc-hidden nmc-field-spacer'));
							}
						});
					}

					if (!field.hasClass('nmc-has-condition'))
					{
						field.addClass('nmc-has-condition')
						.on('change', function ()
						{
							$('.nmc-condition[data-' + $.nmc.data.conditional + '][data-' + $.nmc.data.field + '="' + $(this).attr('name') + '"][data-' + $.nmc.data.value + '][data-' + $.nmc.data.compare + ']')
							.each(function ()
							{
								$('[name="' + $(this).data($.nmc.data.conditional) + '"]').nmc_trigger_all($.nmc.events.check_conditions);
							});
						});
					}
				});
			},
			
			/**
			 * Finalize the form functionality.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.settings.form
			 * @return void
			 */
			"form": function ()
			{
				$('#nav_menu_collapse_settings form')
				.submit(function ()
				{
					$(this).find('[type="submit"]').prop('disabled', true);
				})
				.find('[type="submit"]').prop('disabled', false);
			},

			/**
			 * Include postboxes functionality.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.settings.postboxes
			 * @return void
			 */
			"postboxes": function ()
			{
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');

				if (typeof postboxes !== 'undefined' && typeof pagenow !== 'undefined')
				{
					postboxes.add_postbox_toggles(pagenow);
				}
			}
		});

		$.nmc.document
		.ready(function ()
		{
			$.nmc.settings.conditional_logic();
			$.nmc.settings.form();
			$.nmc.settings.postboxes();
		});
	}
	else if ($.nmc.options.is_nav_menus)
	{
		$.fn.extend(
		{
			/**
			 * Return the direct children for the provided nav menu item.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.fn.nmc_direct_child_menu_items
			 * @this   object Nav menu item to get children for.
			 * @return object Direct nav menu item children.
			 */
			"nmc_direct_child_menu_items": function ()
			{
				var result = $();

				this
				.each(function ()
				{
					var menu_item = $(this);
					var depth = menu_item.menuItemDepth();
					var next = menu_item.next('.menu-item');
					var target_depth = (next.length === 0) ? depth : next.menuItemDepth();
					var current_depth = target_depth;

					while (next.length > 0 && current_depth > depth)
					{
						if (next.hasClass('deleting'))
						{
							result = result.add(next.nmc_direct_child_menu_items());
						}
						else if (current_depth === target_depth)
						{
							result = result.add(next);
						}

						next = next.next('.menu-item');
						current_depth = (next.length === 0) ? depth : next.menuItemDepth();
					}
				});

				return result;
			}
		});

		/**
		 * Functionality to store and fire callbacks.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.callbacks
		 * @var    object
		 */
		$.nmc.callbacks = $.nmc.callbacks || {};
		
		$.extend($.nmc.callbacks,
		{
			/**
			 * Array of stored callbacks.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.callbacks.stored
			 * @var    array
			 */
			"stored": [],

			/**
			 * Store a callback.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.callbacks.add
			 * @param  function callback Callback to store.
			 * @return void
			 */
			"add": function (callback)
			{
				if (typeof callback === 'function')
				{
					$.nmc.callbacks.stored[$.nmc.callbacks.stored.length] = callback;
				}
			},

			/**
			 * Fire the first stored callback.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.callbacks.fire
			 * @return void
			 */
			"fire": function ()
			{
				if ($.nmc.callbacks.stored.length > 0)
				{
					$.nmc.callbacks.stored.shift()();
				}
			}
		});

		/**
		 * Custom data variable names specific to nav menus.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.data
		 * @var    object
		 */
		$.extend($.nmc.data,
		{
			"timeout": 'nmc-timeout'
		});

		/**
		 * Custom event names specific to nav menus.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.events
		 * @var    object
		 */
		$.extend($.nmc.events,
		{
			"extend": 'nmc-extend'
		});

		$.nmc.nav_menus = $.nmc.nav_menus || {};

		/**
		 * Nav menus JSON object.
		 * 
		 * @since 1.0.0
		 * 
		 * @access jQuery.nmc.nav_menus
		 * @var    object
		 */
		$.extend($.nmc.nav_menus,
		{
			/**
			 * Tap into the built-in WordPress nav menus functionality.
			 * 
			 * @since 1.1.4 Added disabled states checks for collapse/expand all buttons.
			 * @since 1.1.0 Excluded AJAX call if all collapsible nav menu items should be collapsed by default.
			 * @since 1.0.0
			 * 
			 * @see wp-admin/js/nav-menu.js
			 * @access jQuery.nmc.nav_menus.override_nav_menus
			 * @return void
			 */
			"override_nav_menus": function ()
			{
				$.fn.default_shiftDepthClass = $.fn.shiftDepthClass;

				$.fn.shiftDepthClass = function (change)
				{
					this.default_shiftDepthClass(change);

					return this
					.each(function ()
					{
						var current = $(this);

						if (current.menuItemDepth() === 0)
						{
							current.find('.is-submenu').hide();
						}
					});
				};

				wpNavMenu.menuList
				.on('sortstart', function (e, ui)
				{
					nmc_dragged_item = ui.item;

					$.nmc.window.mousemove($.nmc.nav_menus.mousemove);
				})
				.on('sortstop', function (e, ui)
				{
					$.nmc.window.unbind('mousemove', $.nmc.nav_menus.mousemove);
					$.nmc.nav_menus.clear_hovered();

					nmc_dragged_item = null;
					nmc_dropped_item = ui.item;
				});

				$.extend(wpNavMenu,
				{
					"default_addItemToMenu": wpNavMenu.addItemToMenu,
					"default_eventOnClickMenuItemDelete": wpNavMenu.eventOnClickMenuItemDelete,
					"default_registerChange": wpNavMenu.registerChange,
					"default_eventOnClickMenuSave": wpNavMenu.eventOnClickMenuSave
				});

				$.extend(wpNavMenu,
				{
					"addItemToMenu": function (menu_item, process_method, callback)
					{
						$('.menu-item.pending:hidden').addClass('nmc-hidden');

						$.nmc.callbacks.add(callback);

						callback = function ()
						{
							$('.menu-item.nmc-hidden').stop(true, true).hide().removeClass('nmc-hidden');

							$.nmc.callbacks.fire();
							$.nmc.nav_menus.collapse_expand();
						};

						wpNavMenu.default_addItemToMenu(menu_item, process_method, callback);
					},

					"eventOnClickMenuItemDelete": function (clicked)
					{
						var menu_item = $(clicked).closest('.menu-item');

						if (menu_item.is('.nmc-collapsed'))
						{
							menu_item.find('.nmc-collapse-expand').nmc_trigger_all('click');
						}
						
						$.nmc.nav_menus.check_all_buttons();

						wpNavMenu.default_eventOnClickMenuItemDelete(clicked);

						return false;
					},

					"registerChange": function ()
					{
						wpNavMenu.default_registerChange();

						$.nmc.nav_menus.check_collapsibility();

						if (nmc_dropped_item !== null)
						{
							var current_depth = nmc_dropped_item.menuItemDepth();

							while (current_depth > 0)
							{
								current_depth -= 1;

								var parent = nmc_dropped_item.prevAll('.menu-item-depth-' + current_depth).first();

								if (parent.hasClass('nmc-collapsed'))
								{
									parent.find('.nmc-collapse-expand').triggerHandler('click');
								}
							}

							nmc_dropped_item = null;
						}
						
						$.nmc.nav_menus.check_all_buttons();
					},

					"eventOnClickMenuSave": function (target)
					{
						if ($.nmc.options.collapsed !== '1' && !$.nmc.body.hasClass('nmc-ajax'))
						{
							$.nmc.body.addClass('nmc-ajax');

							var collapsed = [];

							$('.menu-item.nmc-collapsed')
							.each(function ()
							{
								collapsed.push($(this).find('input.menu-item-data-db-id').val());
							});

							$.post(
							{
								"url": ajaxurl,
								"async": false,
								"dataType": 'json',

								"data": 
								{
									"action": 'nmc_collapsed',
									"menu_id": $('#menu').val(),
									"collapsed": collapsed
								},

								"complete": function ()
								{
									$.nmc.body.removeClass('nmc-ajax');
								}
							});
						}

						return wpNavMenu.default_eventOnClickMenuSave(target);
					}
				});
			},

			/**
			 * Prepare the collapse/expand all buttons.
			 * 
			 * @since 1.1.0 Added functionality to disabled the clicked button.
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.nav_menus.collapse_expand_all
			 * @return void
			 */
			"collapse_expand_all": function ()
			{
				var collapse_expand_all = $(wp.template('nmc-collapse-expand-all')());

				if (collapse_expand_all)
				{
					collapse_expand_all.insertBefore($('#menu-to-edit')).find('.nmc-collapse-all')
					.click(function ()
					{
						$(this).prop('disabled', true).siblings().prop('disabled', false);

						$('.nmc-collapsible').not('.nmc-collapsed').find('.nmc-collapse-expand').nmc_trigger_all('click', [true]);
					});

					collapse_expand_all.find('.nmc-expand-all')
					.click(function ()
					{
						$(this).prop('disabled', true).siblings().prop('disabled', false);

						$('.nmc-collapsed').find('.nmc-collapse-expand').nmc_trigger_all('click', [true]);
					});

					$.nmc.global.help_buttons();
				}
			},

			/**
			 * Prepare collapse/expand functionality.
			 * 
			 * @since 1.3.0 Increased animation speed.
			 * @since 1.1.4 Moved collapse/expand all buttons check to a separate function.
			 * @since 1.1.0 Added call to check the collapse/expand all buttons.
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.nav_menus.collapse_expand
			 * @return void
			 */
			"collapse_expand": function ()
			{
				var collapse_expand = $(wp.template('nmc-collapse-expand')())
				.click(function (e, skip_all_buttons_check)
				{
					var menu_item = $(this).closest('.menu-item');

					var complete = function ()
					{
						$(this).css('height', '');
					};

					if (menu_item.hasClass('nmc-collapsed'))
					{
						menu_item.removeClass('nmc-collapsed');

						var children = menu_item.nmc_direct_child_menu_items();

						while (children.length > 0)
						{
							children.stop(true).slideDown(125, complete);
							children = children.filter('.nmc-collapsible').not('.nmc-collapsed').nmc_direct_child_menu_items();
						}
					}
					else
					{
						menu_item.addClass('nmc-collapsed');
						menu_item.childMenuItems().stop(true).slideUp(125, complete);
					}

					if (skip_all_buttons_check !== true)
					{
						$.nmc.nav_menus.check_all_buttons();
					}
				});

				$('#menu-to-edit').children().not('.nmc-active').addClass('nmc-active')
				.each(function ()
				{
					collapse_expand.clone(true).appendTo($(this).find('.item-controls'));
				})
				.on($.nmc.events.extend, function ()
				{
					var current = $(this);
					var is_null = (nmc_hovered_item === null);

					if (is_null || !nmc_hovered_item.is(current))
					{
						if (!is_null)
						{
							$.nmc.nav_menus.clear_hovered();
						}

						nmc_hovered_item = current;

						nmc_hovered_item
						.data($.nmc.data.timeout, setTimeout(function ()
						{
							nmc_hovered_item.find('.nmc-collapse-expand').triggerHandler('click');

							$.nmc.nav_menus.clear_hovered();
						},
						1000));
					}
				});

				$.nmc.nav_menus.check_collapsibility();
			},

			/**
			 * Set the collapsed items.
			 * 
			 * @since 1.1.0 Modified functionality to allow for all items to be collapsed by default.
			 * @since 1.0.1
			 * 
			 * @access jQuery.nmc.nav_menus.override_nav_menus
			 * @return void
			 */
			"set_collapsed": function ()
			{
				if ($.isPlainObject($.nmc.options.collapsed))
				{
					var menu_id = $('#menu').val();

					if (menu_id in $.nmc.options.collapsed)
					{
						$.each($.nmc.options.collapsed[menu_id], function (index, value)
						{
							$('input.menu-item-data-db-id[value=' + value + ']').closest('.menu-item').find('.nmc-collapse-expand').triggerHandler('click');
						});
					}
				}
				else
				{
					$('#nmc-collapse-expand-all .nmc-collapse-all').triggerHandler('click');
				}
			},
			
			/**
			 * Check the disabled states for the collapse/expand all buttons.
			 * 
			 * @since 1.1.4
			 * 
			 * @access jQuery.nmc.nav_menus.check_all_buttons
			 * @return void
			 */
			"check_all_buttons": function ()
			{
				$('#nmc-collapse-expand-all .nmc-collapse-all').prop('disabled', ($('#menu-to-edit > .menu-item.nmc-collapsible').not('.deleting').not('.nmc-collapsed').length === 0));
				$('#nmc-collapse-expand-all .nmc-expand-all').prop('disabled', ($('#menu-to-edit > .menu-item.nmc-collapsible.nmc-collapsed').not('.deleting').length === 0));
			},

			/**
			 * Check nav menu items for collapsibility.
			 * 
			 * @since 1.3.0 Increased animation speed and added child counter.
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.nav_menus.check_collapsibility
			 * @return void
			 */
			"check_collapsibility": function ()
			{
				var has_collapsible = false;

				$('#menu-to-edit > .menu-item')
				.each(function ()
				{
					var menu_item = $(this);

					if (menu_item.hasClass('deleting') || menu_item.nmc_direct_child_menu_items().length === 0)
					{
						menu_item.removeClass('nmc-collapsible nmc-collapsed');
					}
					else
					{
						has_collapsible = true;

						menu_item.addClass('nmc-collapsible');
					}
					
					var child_count = menu_item.childMenuItems().length;
					var title = menu_item.find('.menu-item-title');
					var counter = title.next('.nmc-counter').hide().removeAttr('title').empty();

					if (child_count > 0)
					{
						counter = (counter.length === 0) ? $('<abbr/>').addClass('nmc-counter').insertAfter(title) : counter;
						counter.attr('title', $.nmc.options.nested.replace('%d', child_count)).html('(' + child_count + ')').show();
					}
				});

				var all_buttons = $('#nmc-collapse-expand-all').stop(true);
				var is_visible = all_buttons.is(':visible');

				if (has_collapsible && !is_visible)
				{
					all_buttons
					.slideDown(125, function ()
					{
						$(this).css('height', '');
					});
				}
				else if (!has_collapsible && is_visible)
				{
					all_buttons
					.slideUp(125, function ()
					{
						$(this).css('height', '');
					});
				}
			},

			/**
			 * Clear the timeout when hovering out of a nav menu item.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.nav_menus.clear_hovered
			 * @return void
			 */
			"clear_hovered": function ()
			{
				if (nmc_hovered_item !== null)
				{
					clearTimeout(nmc_hovered_item.data($.nmc.data.timeout));

					nmc_hovered_item = null;
				}
			},

			/**
			 * Check the position of the dragged item.
			 * 
			 * @since 1.0.0
			 * 
			 * @access jQuery.nmc.nav_menus.mousemove
			 * @return void
			 */
			"mousemove": function ()
			{
				var dragged_position = nmc_dragged_item.position();
				dragged_position.right = dragged_position.left + nmc_dragged_item.width();
				dragged_position.bottom = dragged_position.top + nmc_dragged_item.height();

				var collapsed = wpNavMenu.menuList.children('.menu-item.nmc-collapsed:visible').not(nmc_dragged_item)
				.filter(function ()
				{
					var current = $(this);
					var position = current.position();

					return (position.top <= dragged_position.bottom && position.top + current.height() >= dragged_position.top && position.left <= dragged_position.right && position.left + current.width() >= dragged_position.left);
				})
				.first();

				if (collapsed.length === 0)
				{
					$.nmc.nav_menus.clear_hovered();
				}
				else if (!collapsed.is(nmc_hovered_item))
				{
					collapsed.triggerHandler($.nmc.events.extend);
				}
			}
		});

		$.nmc.document
		.ready(function ()
		{
			$.nmc.nav_menus.override_nav_menus();
			$.nmc.nav_menus.collapse_expand_all();
			$.nmc.nav_menus.collapse_expand();
			$.nmc.nav_menus.set_collapsed();
		});
	}
})(jQuery);
