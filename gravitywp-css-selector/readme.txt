=== GravityWP - CSS Selector ===
Contributors: gravitywp
Donate link: http://gravitywp.com/support/
Tags: gravity forms, css ready classes, form, forms, gravity form
Requires at least: 3.0.1
Tested up to: 6.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily select CSS Ready Classes for your fields within Gravity Forms

== Description ==

> This plugin is an add-on for the amazing Gravity Forms Plugin. 
Special thanks to [Brad Vincent](https://profiles.wordpress.org/bradvin/) and [Bryan Willis](https://github.com/bryanwillis) for developing the first and revised version of this plugin. 

Gravity Forms has CSS Ready Classes to style your form fields. Using these classes, you can easily create more advanced layouts for the fields in your forms. Excellent idea, however, the problem is you always need to remember what the exact class name is. Now with this CSS Selector, you don’t need to remember. Simply click on a button to launch the pop-up and choose the class you want to add.

= Features =
* Convenient button added under the advanced tab next to the CSS Class field
* Clean and simple pop-up that lists all the CSS Ready Classes
* HTML Field Classes (alerts), Gravity PDF, Gravity Perks and CSS Ready Classes selectable
* Add more than one CSS Ready Class
* Double-click a CSS Ready Class to add it and auto-close the popup
* Add your own custom CSS to the pop-up modal

== Installation ==

1. Upload the plugin folder to your `/wp-content/plugins/` folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure you also have Gravity Forms activated.

== Screenshots ==

1. The button that gets added in the advanced tab
2. The pop-up modal that is displayed
3. Add custom css

== Changelog ==
= 1.0.2 =
* Added Gravity Forms version check, hide deprecated CSS Ready Classes in 2.5 and higher
* Added title tags with more detailed description of the function of every CSS class
* Added new vertical choices columns (gf_list_2col_vertical, gf_list_3col_vertical, gf_list_4col_vertical, gf_list_5col_vertical), only visible in GF 2.5 and higher
* Added new HTML Field Classes (gf_alert_green, gf_alert_red, gf_alert_yellow, gf_alert_gray, gf_alert_blue), only visible in GF 2.5 and higher
* Added gf_inline CSS Ready Class
* Updated url to GravityWP documentation to add custom CSS buttons to the CSS selector (https://gravitywp.com/doc/add-custom-css-buttons/)
* Security enhancements

= 1.0.1 =
* Updated url to official Gravity Forms CSS Ready Classes documentation (https://docs.gravityforms.com/css-ready-classes/)

= 1.0 =
* Add gwp_css_selector_add_custom_css filter to add custom css
* Updated translation files

= 0.2.2 =
* Adjusted the Gravity PDF pagebreak CSS
* Added gf_invisible CSS class
* Combined list columns and list heights to Radio Buttons & Checkboxes
* Changed links to documentation

= 0.2.1 =
* Added pagebreak support for Gravity PDF
* Updated translation file
* Updated Dutch translation

= 0.2 =
* Added CSS classes for Gravity PDF (exclude) and Gravity Wiz (Copy Cat)
* Updated layout (documentation available on click)

= 0.1 =
* Initial Release
* Added localisation 

== Frequently Asked Questions ==

= Does this plugin rely on anything? =
Yes, you need to install the [Gravity Forms Plugin](http://www.gravityforms.com/) for this plugin to work. And it needs to be at least v1.5.

= How to add custom CSS buttons? =
You can add your own CSS to the CSS Selector easily in your functions.php file. Just add the following example code there. It adds quick buttons and an accordion on top of the modal. That way you can put easily your own CSS in the layout you want.

`// Add custom css: quick buttons and accordion at the top of the GravityWP - CSS Selector modal
function my_custom_gwp_css_selector_add_css() {
    $html .= "<div class='gwp_quick_links'>
	<a class='gwp_css_link' href='#' rel='your_custom_css_class' title='Adds your_custom_css_class to the CSS field'>Custom css</a>
	<a class='gwp_css_link' href='#' rel='your_custom_css_class_2' title='Adds your_custom_css_class_2 to the CSS field'>2nd custom css</a></div>
	<li>
	<a class='gwp_css_acc_link' href='#'>Custom CSS</a>
	<div class='gwp_css_accordian'>
	<a class='gwp_css_link' href='#' rel='your_custom_css_class' title='Adds your_custom_css_class to the CSS field'>Custom css</a>
	<a class='gwp_css_link' href='#' rel='your_custom_css_class_2' title='Adds your_custom_css_class_2 to the CSS field'>2nd custom css</a>
	</div>
	</li>";
    return $html;
}
add_filter( 'gwp_css_selector_add_custom_css', 'my_custom_gwp_css_selector_add_css' );`