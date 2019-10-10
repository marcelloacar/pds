=== Add Admin CSS ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: admin, css, style, stylesheets, admin theme, customize, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7
Tested up to: 5.1
Stable tag: 1.7

Interface for easily defining additional CSS (inline and/or by URL) to be added to all administration pages.


== Description ==

Ever want to tweak the appearance of the WordPress admin pages by hiding stuff, moving stuff around, changing fonts, colors, sizes, etc?  Any modification you may want to do with CSS can easily be done via this plugin.

Using this plugin you'll easily be able to define additional CSS (inline and/or files by URL) to be added to all administration pages. You can define CSS to appear inline in the admin head (within style tags), or reference CSS files to be linked (via "link rel='stylesheet'" tags). The referenced CSS files will appear in the admin head first, listed in the order defined in the plugin's settings. Then any inline CSS are added to the admin head. Both values can be filtered for advanced customization (see Advanced section).

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/add-admin-css/) | [Plugin Directory Page](https://wordpress.org/plugins/add-admin-css/) | [GitHub](https://github.com/coffee2code/add-admin-css/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `add-admin-css.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Go to "Appearance" -> "Admin CSS" and specify some CSS to be added into all admin pages. (You can also use the "Settings" link in the plugin's entry on the admin "Plugins" page).


== Frequently Asked Questions ==

= Can I add CSS I defined via a file, or one that is hosted elsewhere? =

Yes, via the "Admin CSS Files" input field on the plugin's settings page.

= Can I limit what admin pages the CSS gets output on? =

No, not presently. At least not directly. By default, the CSS is added for every admin page on the site.

However, you can preface your selectors with admin page specific class(es) on 'body' tag to ensure CSS only applies on certain admin pages. (e.g. `body.index-php h2, #icon-index { display: none; }`).

Or, you can hook the 'c2c_add_admin_css' and 'c2c_add_admin_css_files' filters and determine the current admin page content to decide whether the respective hook argument should be returned (and thus output) or not.

= Can I limit what users the CSS applies to? =

No, not presently. At least not directly. By default, the CSS is added for any user that can enter the admin section of the site.

You can hook the 'c2c_add_admin_css' and 'c2c_add_admin_css_files' filters and determine the current user to decide whether the respective hook argument should be returned (and thus output) for the user or not.

= How can I edit the plugin's settings in the event I supplied CSS that prevents the admin pages from properly functioning or being seen? =

It is certainly possible that you can put yourself in an unfortunate position by supplying CSS that could hide critical parts of admin pages, making it seeminly impossible to fix or revert your changes. Fortunately, there are a number of approaches you can take to correct the problem.

The recommended approach is to visit the URL for the plugin's settings page, but appended with a special query parameter to disable the output of its JavaScript. The plugin's settings page would typically be at a URL like `https://example.com/wp-admin/themes.php?page=add-admin-css%2Fadd-admin-css.php`. Append `&c2c-no-css=1` to that, so that the URL is `https://example.com/wp-admin/themes.php?page=add-admin-css%2Fadd-admin-css.php&c2c-no-css=1` (obviously change example.com with the domain name for your site).

There are other approaches you can use, though they require direct database or server filesystem access:

* Some browsers (such as Firefox, via View -> Page Style -> No Style) allow you to disable styles for sites loaded in that tab. Other browsers may also support such functionality natively or through an extension. Chrome has an extension called [Web Developer](https://chrome.google.com/webstore/detail/web-developer/bfbameneiokkgbdmiekhjnmfkcnldhhm?hl=en-US) that adds the functionality.
* If you're familiar with doing so and have an idea of what CSS style you added that is causing problems, you can use your browser's developer tools to inspect the page, find the element in question, and disable the offending style.
* In the site's `wp-config.php` file, define a constant to disable output of the plugin-defined JavaScript: `define( 'C2C_ADD_ADMIN_CSS_DISABLED', true );`. You can then visit the site's admin. Just remember to remove that line after you've fixed the CSS (or at least change "true" to "false"). This is an alternative to the query parameter approach described above, though it persists while the constant remains defined. There will be an admin notice on the plugin's setting page to alert you to the fact that the constant is defined and effectively disabling the plugin from adding any CSS.
* Presuming you know how to directly access the database: within the site's database, find the row with the option_name field value of `c2c_add_admin_css` and delete that row. The settings you saved for the plugin will be deleted and it will be like you've installed the plugin for the first time.
* If your server has WP-CLI installed, you can delete the plugin's setting from the commandline: `wp option delete c2c_add_admin_css`

The initial reaction by some might be to remove the plugin from the server's filesystem. This will certainly disable the plugin and prevent the CSS you configured through it from taking effect, restoring the access and functionality to the backend. However, reinstalling the plugin will put you back into the original predicament because the plugin will use the previously-configured settings, which wouldn't have changed.

= How do I disable syntax highlighting? =

The plugin's syntax highlighting of CSS (available on WP 4.9+) honors the built-in setting for whether syntax highlighting should be enabled or not.

To disable syntax highlighting, go to your profile page. Next to "Syntax Highlighting", click the checkbox labeled "Disable syntax highlighting when editing code". Note that this checkbox disables syntax highlighting throughout the admin interface and not just specifically for the plugin's settings page.

= Does this plugin include unit tests? =

Yes.


== Screenshots ==

1. A screenshot of the plugin's admin settings page.


== Hooks ==

The plugin exposes two filters for hooking. Typically, code making use of filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain). Bear in mind that the features controlled by these filters are also configurable via the plugin's settings page. These filters are likely only of interest to advanced users able to code.

**c2c_add_admin_css (filter)**

The 'c2c_add_admin_css' filter allows customization of CSS that should be added directly to the admin page head.

Arguments:

* $css (string): CSS styles.

Example:

`
/**
 * Add CSS to admin pages.
 *
 * @param string $css String to be added to admin pages.
 * @return string
 */
function my_admin_css( $css ) {
	$css .= "
		#site-heading a span { color:blue !important; }
		#favorite-actions { display:none; }
	";
	return $css;
}
add_filter( 'c2c_add_admin_css', 'my_admin_css' );
`

**c2c_add_admin_css_files (filter)**

The 'c2c_add_admin_css_files' filter allows programmatic modification of the list of CSS files to enqueue in the admin.

Arguments:

* $files (array): Array of CSS files.

Example:

`
/**
 * Add CSS file(s) to admin pages.
 *
 * @param array $files CSS files to be added to admin pages.
 * @return array
 */
function my_admin_css_files( $files ) {
	$files[] = 'http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css';
	return $files;
}
add_filter( 'c2c_add_admin_css_files', 'my_admin_css_files' );
`


== Changelog ==

= 1.7_(2019-04-13) =
Highlights:

* This release adds a recovery mode to disable output of CSS via the plugin (and an admin notice when it is active), improves documentation, updates the plugin framework, notes compatibility through WP 5.1+, drops compatibility with versions of WP older than 4.7, and more documentation and code improvements.

Details:

* New: Add recovery mode to be able to disable output of CSS via the plugin
    * Add support for `c2c-no-css` query parameter for enabling recovery mode
    * Add support for `C2C_ADD_ADMIN_CSS_DISABLED` constant for enabling recovery mode
    * Display admin notice when recovery mode is active
    * Add `can_show_css()`, `remove_query_param_from_redirects()`, `recovery_mode_notice()`
* Change: Initialize plugin on `plugins_loaded` action instead of on load
* Change: Update plugin framework to 049
    * 049:
    * Correct last arg in call to `add_settings_field()` to be an array
    * Wrap help text for settings in `label` instead of `p`
    * Only use `label` for help text for checkboxes, otherwise use `p`
    * Ensure a `textarea` displays as a block to prevent orphaning of subsequent help text
    * Note compatibility through WP 5.1+
    * Update copyright date (2019)
    * 048:
    * When resetting options, delete the option rather than setting it with default values
    * Prevent double "Settings reset" admin notice upon settings reset
    * 047:
    * Don't save default setting values to database on install
    * Change "Cheatin', huh?" error messages to "Something went wrong.", consistent with WP core
    * Note compatibility through WP 4.9+
    * Drop compatibility with version of WP older than 4.7
* New: Add README.md file
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add FAQ entry describing ways to fix having potentially crippled the admin
* New: Add inline documentation for hooks
* New: Add GitHub link to readme
* Unit tests:
    * New: Add unit tests for `add_css()`
    * New: Add unit test for defaults for settings
    * Change: Improve tests for settings handling
    * Change: Update `set_option()` to accept an array of setting values to use
    * Change: Explicitly set 'twentyseventeen' as the theme to ensure testing against a known theme
    * Change: Invoke `setup_options()` within each test as needed instead of `setUp()`
* Change: Store setting name in constant
* Change: Cast return value of `c2c_add_admin_css_files` filter as an array
* Change: Improve documentation for hooks within readme.txt
* Change: Note compatibility through WP 5.1+
* Change: Drop compatibility with version of WP older than 4.7
* Change: Rename readme.txt section from 'Advanced' to 'Hooks' and provide a better section intro
* Change: Update installation instruction to prefer built-in installer over .zip file
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

= 1.6 (2017-11-03) =
* New: Add support for CodeMirror (as packaged with WP 4.9)
    * Adds code highlighting, syntax checking, and other features
* Fix: Show admin notifications for settings page
* Change: Update plugin framework to 046
    * 046:
    * Fix `reset_options()` to reference instance variable `$options`.
	* Note compatibility through WP 4.7+.
	* Update copyright date (2017)
    * 045:
    * Ensure `reset_options()` resets values saved in the database.
    * 044:
    * Add `reset_caches()` to clear caches and memoized data. Use it in `reset_options()` and `verify_config()`.
    * Add `verify_options()` with logic extracted from `verify_config()` for initializing default option attributes.
    * Add `add_option()` to add a new option to the plugin's configuration.
    * Add filter 'sanitized_option_names' to allow modifying the list of whitelisted option names.
    * Change: Refactor `get_option_names()`.
    * 043:
    * Disregard invalid lines supplied as part of hash option value.
    * 042:
    * Update `disable_update_check()` to check for HTTP and HTTPS for plugin update check API URL.
    * Translate "Donate" in footer message.
* Change: Update unit test bootstrap
    * Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable
    * Enable more error output for unit tests
* Change: Note compatibility through WP 4.9+
* Change: Remove support for WordPress older than 4.6
* Change: Update copyright date (2018)

= 1.5 (2016-04-21) =
* Change: Declare class as final.
* Change: Update plugin framework to 041:
    * For a setting that is of datatype array, ensure its default value is an array.
    * Make `verify_config()` public.
    * Use `<p class="description">` for input field help text instead of custom styled span.
    * Remove output of markup for adding icon to setting page header.
    * Remove styling for .c2c-input-help.
    * Add braces around the few remaining single line conditionals.
* Change: Note compatibility through WP 4.5+.
* Change: Remove 'Domain Path' from plugin header.
* New: Add LICENSE file.

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/add-admin-css/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 1.7 =
Recommended update: added recovery mode, tweaked plugin initialization process, updated plugin framework, compatibility is now WP 4.7 through WP 5.1+, updated copyright date (2019), and more documentation and code improvements.

= 1.6 =
Recommended update: added code highlighting, syntax checking, etc as introduced elsewhere in WP 4.9; show admin notifications for settings page; updated plugin framework to version 046; verified compatibility through WP 4.9; dropped compatibility with versions of WordPress older than 4.6; updated copyright date (2018).

= 1.5 =
Minor update: updated plugin framework to version 041; verified compatibility through WP 4.5.

= 1.4 =
Recommended update: bugfixes for CSS file links containing query arguments; improved support for localization; verified compatibility through WP 4.4; removed compatibility with WP earlier than 4.1; updated copyright date (2016)

= 1.3.4 =
Bugfix release: fixed line-wrapping display for Firefox and Safari; noted compatibility through WP 4.2+.

= 1.3.3 =
Bugfix release: reverted use of __DIR__ constant since it isn't supported on older installations (PHP 5.2).

= 1.3.2 =
Trivial update: improvements to unit tests; updated plugin framework to version 039; noted compatibility through WP 4.1+; updated copyright date (2015).

= 1.3.1 =
Trivial update: updated plugin framework to version 038; noted compatibility through WP 4.0+; added plugin icon.

= 1.3 =
Minor update: added unit tests; minor improvements; noted compatibility through WP 3.8+.

= 1.2 =
Recommended update. Highlights: stopped wrapping long input field text; updated plugin framework; updated WP compatibility as 3.1 - 3.5+; explicitly stated license; and more.

= 1.1 =
Recommended update: renamed class and filters by prefixing 'c2c_'; noted compatibility through WP 3.3; dropped support for versions of WP older than 3.0; updated plugin framework; deprecate global variable.

= 1.0 =
Initial public release!
