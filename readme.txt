=== Integration for Gravity Forms and Quorum ===
Contributors: davetee
Tags: Gravity Forms, Quorum, Subscribe
Requires at least: 4.8
Tested up to: 5.2
Stable tag: trunk
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This is an add-on to the Gravity Forms WordPress plugin, extending form options to integrate with the Quorum public affairs service.

== Description ==

Enable integration between a Gravity Form and Quorum public affairs system Contacts. Map Gravity Form fields to corresponding Supporter fields in the Quorum database, and insert and update Supporter records via the web form.

> Please note that this is a third-party plugin and is not affiliated with Quorum or Gravity Forms.


== Installation ==

1. Install and activate the Gravity Forms plugin. This plugin only extends functionality to Gravity Forms, and provides no functionality alone.
1. Upload the Quorum Add-on plugin files to the `/wp-content/plugins/gravityformsquorum` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the Quorum Add-on plugin through the 'Plugins' screen in WordPress
1. Use the Gravity Forms settings page to configure the Quorum Add-on API key and user name. Forms > Settings > Quorum. (/wp-admin/admin.php?page=gf_settings&subview=gravityformsquorum)


== Frequently Asked Questions ==

= Do I need to use composer? =

No. You do not need to, and should not attempt to install library dependencies of this plugin via composer. This plugin uses a general-purpose PHP-based [client library for the Quorum API](https://packagist.org/packages/njimedia/quorumapi), but, that library is already included, and no installation or configuration steps are required.

= Can I contribute? = 

Yes. Development is done on [GitHub](https://github.com/njimedia/gravityformsquorum). Pull requests welcome.

== Screenshots ==

1. Main API account settings configuration
2. Example form field map configuration

== Changelog ==

= 1.0 =
* Init release
