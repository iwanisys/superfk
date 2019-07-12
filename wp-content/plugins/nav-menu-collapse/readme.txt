=== Nav Menu Collapse ===
Contributors: rnoakes3rd
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BE5MGPAKBG8TQ
Tags: ajax, all, collpase, collpased, expand, expanded, menu, meta, nav, store, stored, user
Requires at least: 4.6.14
Tested up to: 5.2.0
Stable tag: 1.3.1
Copyright: (c) 2018-2019 Robert Noakes (mr@robertnoakes.com)
License: GNU General Public License v3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds functionality to collapse and expand nav menus within the WordPress admin.

== Description ==

Adds functionality to collapse and expand nav menus within the WordPress admin. All nav menu items are collpased by default, but you have the option to store the collapsed/expanded state for the menus on a user-by-user basis. 

If you are looking for more than just collapse/expand functionality for Nav Menus, the [Nav Menu Manager](https://wordpress.org/plugins/noakes-menu-manager/) may be a better choice for you.

= Compatibility =

The collapse/expand buttons and functionality are added after the page loads via jQuery. Since there is no need for an action hook, this plugin should be compatible with most themes and plugins.

== Installation ==

= From Your WordPress Dashboard =

1. Log into the WordPress admin
2. Go to **Plugins > Add New**
3. Search for **Nav Menu Collapse**
4. Click **Install Now** for the 'Nav Menu Collapse' plugin
5. Click **Activate**

= From WordPress.org =

1. Download the plugin
2. Log into the WordPress admin
3. Go to **Plugins > Add New**
4. Click **Upload Plugin**
5. Click **Browse** and select the downloaded ZIP file
6. Click **Install Now**
7. Click **Activate Plugin**

= Via File Transfer =

1. Download the plugin
2. Extract the ZIP file
3. Upload the contents of the ZIP file to **wp-content/plugins/**
4. Log into the WordPress admin
5. Go to **Plugins**
6. Click **Activate** under 'Nav Menu Collapse'

= What's Next? =

Once the plugin is active, simply visit **Settings > Nav Menu Collapse** and enable the settings appropriate for this site.

== Screenshots ==

1. Plugin settings page
2. Nav menu collapse/expand functionality

== Changelog ==

= 1.3.1 =

Released 2019-05-08

* Added: Made sure the plugin works in WordPress 5.2.0

= 1.3.0 =

Released 2019-04-25

* Added: Made sure the plugin works in WordPress 5.1.1
* Added: Nested nav menu item count.
* Added: Prevent functionality load when used with the latest version of Nav Menu Manager
* Improved: Tons of improvements

= 1.2.1 =

Released 2018-08-27

* Added: Made sure the plugin works in WordPress 4.9.8
* Improved: Optimized field ID value

= 1.2.0 =

Released 2018-06-14

* Added: Made sure the plugin works in WordPress 4.9.6
* Updated: Asset generation tools
* Improved: Removed static utilities class and replaced it with template functions
* Improved: Help tab overview output
* Improved: Version check functionality
* Improved: Uninstall process
* Improved: Various other minor improvements

= 1.1.4 =

Released 2018-04-20

* Fixed: Disabled state for collapse/expand all buttons when reordering nav menu items
* Added: Made sure the plugin works in WordPress 4.9.5
* Improved: Moved core object classes to their own folder

= 1.1.3 =

Released 2018-03-28

* Changed: Domain name

= 1.1.2 =

Released 2018-03-27

* Added: Full RTL support
* Changed: Made help class static
* Improved: Default object values
* Improved: High-level variable comments
* Improved: Abstract wrapper class
* Improved: Other minor structural improvements

= 1.1.1 =

Released 2018-03-08

* Removed: Plugin activation hook. Forgot that isn't fired when the plugin is updated

= 1.1.0 =

Released 2018-03-08

* Added: Plugin activation hook
* Added: New option to store menu collapsed states in user meta. By default, all menu items are collapsed unless this option is enabled
* Improved: Help tab functionality and output
* Improved: Various structural improvements

= 1.0.3 =

Released 2018-02-19

* Fixed: Removed Essential Grid plugin meta box from the setting page

= 1.0.2 =

Released 2018-02-15

* Fixed: Minor definition string issues

= 1.0.1 =

Released on 2018-02-15

* Fixed: Collapsed state resetting when adding a menu item
* Improved: Animated the initial collapsed state
* Updated: POT file

= 1.0.0 =

Released on 2018-02-15

* Initial release

== Upgrade Notice ==

= 1.2 =

Plugin settings and user meta are now preserved by default when the plugin is uninstalled. This can be changed in the plugin settings.

= 1.1 =

Storing the state of collapsed nav menu items in user meta is now optional. By default, the plugin now collapses all collapsible nav menu items by default. Disable Store Collapsed States in the settings to use the new default behavior.
