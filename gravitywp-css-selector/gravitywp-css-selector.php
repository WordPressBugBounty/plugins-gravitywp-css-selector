<?php
/**
 * Plugin Name: GravityWP - CSS Selector
 * Plugin URI: https://gravitywp.com/plugins/css-selector/
 * Description: Quickly select custom and add-on CSS classes.
 * Author: GravityWP
 * Version: 1.1.1
 * Author URI: http://gravitywp.com
 * License: GPL2
 * Text Domain: gravitywp-css-selector
 * Domain Path: /languages
 */

// Tribute to Brad Vincent for making the first version of this plugin https://profiles.wordpress.org/bradvin.
// Tribute to Bryan Willis for making a revised version of this plugin available on Github: https://wordpress.org/support/users/codecandid/.

if ( class_exists( 'GFForms' ) || class_exists( 'RGForms' ) ) {
	add_action( 'gform_editor_js', 'gwp_css_selector_render_editor_js' );
}

add_action( 'gform_loaded', 'gwp_css_selector_load_addon' );

/** Register plugin settings after Gravity Forms loads. */
function gwp_css_selector_load_addon() {
	if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
		return;
	}
	GFForms::include_addon_framework();
	require_once __DIR__ . '/includes/class-css-selector-addon.php';
	GFAddOn::register( '\\GravityWP\\CSSSelector\\CSS_Selector_AddOn' );
	// Also support load order where GF was not available at initial plugin load.
	if ( ! has_action( 'gform_editor_js', 'gwp_css_selector_render_editor_js' ) ) {
		add_action( 'gform_editor_js', 'gwp_css_selector_render_editor_js' );
	}
}

/**
 * Gwp_css_selector_render_editor_js.
 *
 * @global
 * @return  void
 */
function gwp_css_selector_render_editor_js() {
	$custom_start = '';

	$custom_css = apply_filters( 'gwp_css_selector_add_custom_css', $custom_start );
	$library_html = class_exists( '\\GravityWP\\CSSSelector\\CSS_Selector_AddOn' )
		? \GravityWP\CSSSelector\CSS_Selector_AddOn::get_instance()->library_html()
		: '';

	$modal_html = "
		<div id='css_ready_modal'><style>
		#css_ready_selector,a.gwp_css_acc_link,a.gwp_css_link {text-decoration:none}#css_ready_selector {display:block}#css_ready_modalh4{margin-bottom:2px}.gwp_css_accordian{display:-ms-flexbox;display:-webkit-box;display:flex;-ms-flex-direction:row;-webkit-box-orient:horizontal;-webkit-box-direction:normal;flex-direction:row;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-pack:center;-webkit-box-pack:center;justify-content:center;-ms-flex-line-pack:justify;align-content:space-between;-ms-flex-align:center;-webkit-box-align:center;align-items:center;margin:5px 0}a.gwp_css_acc_link{font-weight:700;display:block;padding:5px;text-align:left;background:#d2e0eb;border:1px solid #ddd;color:#47759B}a.gwp_css_link{margin:2px;text-align:center;padding:3px; padding-left:10px;padding-right:10px;border:1px solid #aaa;background:#eee;display:inline-block;box-sizing:border-box;-ms-flex-order:0;-webkit-box-ordinal-group:1;order:0;-ms-flex:1 0 auto;-webkit-box-flex:1;flex:1 0 auto;-ms-flex-item-align:stretch;align-self:stretch}a.gwp_css_link:hover{background:#ddd}ul.gwp_css_ul{margin:0;padding:0}a.gwp_css_link_doc{margin:2px;text-align:center;padding:3px;border:1px solid #aaa;background:#eee;display:inline-block;box-sizing:border-box;-ms-flex-order:0;-webkit-box-ordinal-group:1;order:0;-ms-flex:1 0 auto;-webkit-box-flex:1;flex:1 0 auto;-ms-flex-item-align:stretch;align-self:stretch; text-decoration:none;}a.gwp_css_link_doc:hover{background:#ddd}ul.gwp_css_ul{margin:0;padding:0} ul.gwp_css_ul li{margin:2px;padding:0}.gwp_title {margin-top: 12px;margin-bottom: 10px;font-weight: bold;}</style>              
		<div class='gwp_title'>" . esc_html__( 'Select a CSS class', 'gravitywp-css-selector' ) . "</div>
		<ul class='gwp_css_ul'>
		<li id='gwp-used-classes'><div class='gwp_title'>" . esc_html__( 'Used on other fields', 'gravitywp-css-selector' ) . "</div><div class='gwp-used-class-links'></div></li>
		" . $library_html . $custom_css;

	// Build legacy Ready Classes separately; never modify saved field classes.
	$legacy_html = '';

	// Add column CSS selectors only for Gravity Forms version 2.4 and earlier.
	if ( version_compare( GFCommon::$version, '2.5', '<' ) ) {
		$legacy_html .= "
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Two Columns (2)', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>
			<a class='gwp_css_link' href='#' rel='gf_left_half' title='gf_left_half'>" . esc_html__( 'Left Half', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' href='#' rel='gf_right_half' title='gf_right_half'>" . esc_html__( 'Right Half', 'gravitywp-css-selector' ) . "</a>
		  </div>
		</li>
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Three Columns (3)', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>
			<a class='gwp_css_link' href='#' rel='gf_left_third' title='gf_left_third'>" . esc_html__( 'Left Third', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' href='#' rel='gf_middle_third' title='gf_middle_third'>" . esc_html__( 'Middle Third', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' href='#' rel='gf_right_third' title='gf_right_third'>" . esc_html__( 'Right Third', 'gravitywp-css-selector' ) . "</a>
		  </div>
		</li>
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Four Columns (4)', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>
			<a class='gwp_css_link' href='#' rel='gf_first_quarter' title='gf_first_quarter'>" . esc_html__( '1st Quarter', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' href='#' rel='gf_second_quarter' title='gf_second_quarter'>" . esc_html__( '2nd Quarter', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' href='#' rel='gf_third_quarter' title='gf_third_quarter'>" . esc_html__( '3rd Quarter', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' href='#' rel='gf_fourth_quarter' title='gf_fourth_quarter'>" . esc_html__( '4th Quarter', 'gravitywp-css-selector' ) . '</a>
		  </div>
		</li>';
	}

	// Add Radio Buttons and Checkboxes CSS Classes.
	$legacy_html .= "
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Radio Buttons & Checkboxes', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>                
			<a class='gwp_css_link' rel='gf_list_inline' title='gf_list_inline: " . esc_html__( 'Turn choices into an inline horizontal list (not evenly spaced columns).', 'gravitywp-css-selector' ) . "' href='#'> " . esc_html__( 'List Inline', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_list_2col' title='gf_list_2col: " . esc_html__( 'Show choices in two (2) columns, left to right and then descending in rows.', 'gravitywp-css-selector' ) . "' href='#'>2 " . esc_html__( 'Columns', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_list_3col' title='gf_list_3col: " . esc_html__( 'Show choices in three (3) columns, left to right and then descending in rows.', 'gravitywp-css-selector' ) . "' href='#'>3 " . esc_html__( 'Columns', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_list_4col' title='gf_list_4col: " . esc_html__( 'Show choices in four (4) columns, left to right and then descending in rows.', 'gravitywp-css-selector' ) . "' href='#'>4 " . esc_html__( 'Columns', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_list_5col' title='gf_list_5col: " . esc_html__( 'Show choices in five (5) columns, left to right and then descending in rows.', 'gravitywp-css-selector' ) . "' href='#'>5 " . esc_html__( 'Columns', 'gravitywp-css-selector' ) . '</a>
			</div>';

	// Add List Columns Vertical only for Gravity Forms version 2.5 and later.
	if ( version_compare( GFCommon::$version, '2.5', '>=' ) ) {
		$legacy_html .= "
			<div class='gwp_css_accordian'>                
			<a class='gwp_css_link' rel='gf_list_2col_vertical' title='gf_list_2col_vertical: " . esc_html__( 'Show choices in two (2) columns, top to bottom and then the next column.', 'gravitywp-css-selector' ) . "' href='#'>2 " . esc_html__( 'Col Vertical', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_list_3col_vertical' title='gf_list_3col_vertical: " . esc_html__( 'Show choices in three (3) columns, top to bottom and then the next column.', 'gravitywp-css-selector' ) . "' href='#'>3 " . esc_html__( 'Col Vertical', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_list_4col_vertical' title='gf_list_4col_vertical: " . esc_html__( 'Show choices in four (4) columns, top to bottom and then the next column.', 'gravitywp-css-selector' ) . "' href='#'>4 " . esc_html__( 'Col Vertical', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_list_5col_vertical' title='gf_list_5col_vertical: " . esc_html__( 'Show choices in five (5) columns, top to bottom and then the next column.', 'gravitywp-css-selector' ) . "' href='#'>5 " . esc_html__( 'Col Vertical', 'gravitywp-css-selector' ) . '</a>
			</div>';
	}

	// Add List Height CSS Classes.
	$legacy_html .= "
			<div class='gwp_css_accordian'>                   
			<a class='gwp_css_link' rel='gf_list_height_25' title='gf_list_height_25: " . esc_html__( 'Applies 25px height to all choices.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Height', 'gravitywp-css-selector' ) . " 25px </a>
			<a class='gwp_css_link' rel='gf_list_height_50' title='gf_list_height_50: " . esc_html__( 'Applies 50px height to all choices.', 'gravitywp-css-selector' ) . "' href='#'>50px</a>
			<a class='gwp_css_link' rel='gf_list_height_75' title='gf_list_height_75: " . esc_html__( 'Applies 75px height to all choices.', 'gravitywp-css-selector' ) . "' href='#'>75px</a>
			<a class='gwp_css_link' rel='gf_list_height_100' title='gf_list_height_100: " . esc_html__( 'Applies 100px height to all choices.', 'gravitywp-css-selector' ) . "' href='#'>100px</a>
			<a class='gwp_css_link' rel='gf_list_height_125' title='gf_list_height_125: " . esc_html__( 'Applies 125px height to all choices.', 'gravitywp-css-selector' ) . "' href='#'>125px</a>
			<a class='gwp_css_link' rel='gf_list_height_150' title='gf_list_height_150: " . esc_html__( 'Applies 150px height to all choices.', 'gravitywp-css-selector' ) . "' href='#'>150px</a>
		</div>
		</li>";

	// Add HTML Block Classes only for Gravity Forms version 2.5 and later.
	if ( version_compare( GFCommon::$version, '2.5', '>=' ) ) {
		$legacy_html .= "
				<li>
					<a class='gwp_css_acc_link' href='#'>" . esc_html__( 'HTML Block Classes', 'gravitywp-css-selector' ) . "</a>
					<div class='gwp_css_accordian'>                   
					<a class='gwp_css_link' rel='gf_alert_green' title='gf_alert_green: " . esc_html__( 'This turns an HTML field and its contents into a green banner message.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Green Alert', 'gravitywp-css-selector' ) . "</a>
					<a class='gwp_css_link' rel='gf_alert_red' title='gf_alert_red: " . esc_html__( 'This turns an HTML field and its contents into a red banner message.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Red Alert', 'gravitywp-css-selector' ) . "</a>
					<a class='gwp_css_link' rel='gf_alert_yellow' title='gf_alert_yellow: " . esc_html__( 'This turns an HTML field and its contents into a yellow banner message.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Yellow Alert', 'gravitywp-css-selector' ) . "</a>
					<a class='gwp_css_link' rel='gf_alert_gray' title='gf_alert_gray: " . esc_html__( 'This turns an HTML field and its contents into a gray banner message.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Gray Alert', 'gravitywp-css-selector' ) . "</a>
					<a class='gwp_css_link' rel='gf_alert_blue' title='gf_alert_blue: " . esc_html__( 'This turns an HTML field and its contents into a blue banner message.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Blue Alert', 'gravitywp-css-selector' ) . '</a>
					</div>
				  </li>';
	}

	// Add other GF CSS Classes.
	$legacy_html .= "
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Others', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>                   
			<a class='gwp_css_link' rel='gf_invisible' title='gf_invisible: " . esc_html__( 'Hides a field, useful for field types where the Visibility setting is not available, like product fields.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Invisible Field', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_inline' title='gf_inline: " . esc_html__( 'Places the field inline horizontally with other fields but does not create equally-spaced column layouts.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Inline Field', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_scroll_text' title='gf_scroll_text: " . esc_html__( 'Converts a section break field into a box with a fixed height that will automatically show a scroll bar if there’s a large amount of text.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Scrolling Paragraph Text', 'gravitywp-css-selector' ) . "</a>
			</div>
			<div class='gwp_css_accordian'>  
			<a class='gwp_css_link' rel='gf_hide_ampm' title='gf_hide_ampm: " . esc_html__( 'Hides the am/pm selector in the time field.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Hide Time am/pm', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='gf_hide_charleft' title='gf_hide_charleft: " . esc_html__( 'Hides the characters left counter beneath paragraph text fields when using the maximum characters option.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Hide Character Counter', 'gravitywp-css-selector' ) . '</a>
		  </div>
		</li>';

	// Ready Classes are legacy and unavailable in the picker on Gravity Forms 3.0+.
	if ( version_compare( GFCommon::$version, '3.0', '<' ) ) {
		$modal_html .= "<li class='gwp_title'>" . esc_html__( 'Legacy Ready Classes', 'gravitywp-css-selector' ) . '</li>' . $legacy_html;
	}

	// Add Gravity PDF CSS Classes.
	$modal_html .= "
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Gravity PDF', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>                   
			<a class='gwp_css_link' rel='exclude' title='exclude: " . esc_html__( 'Excludes the field from the PDF.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Exclude from PDF', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link' rel='pagebreak' title='pagebreak: " . esc_html__( 'Starts a new PDF page with this field at the top of the new page.', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Pagebreak', 'gravitywp-css-selector' ) . "</a>					
		  </div>
		</li>
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Gravity Wiz (Perks)', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>                   
			<a class='gwp_css_link' rel='copy-1-to-2' title='copy-1-to-2: " . esc_html__( 'Copies the value of Field ID 1 to Field ID 2 (Gravity Perks needed)', 'gravitywp-css-selector' ) . "' href='#'>" . esc_html__( 'Copy Cat', 'gravitywp-css-selector' ) . '</a>
		  </div>
		</li>';

	// Add help for custom classes and native layout settings.
	$modal_html .= "
		</ul>
		<ul class='gwp_css_ul'>
		<li>
		  <a class='gwp_css_acc_link' href='#'>" . esc_html__( 'Help', 'gravitywp-css-selector' ) . "</a>
		  <div class='gwp_css_accordian'>
			<a class='gwp_css_link_doc' href='" . esc_url( admin_url( 'admin.php?page=gf_settings&subview=gravitywp-css-selector' ) ) . "'>" . esc_html__( 'Manage class library', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link_doc' href='https://gravitywp.com/doc/add-custom-css-buttons/' target='_blank'>" . esc_html__( 'Add custom css', 'gravitywp-css-selector' ) . "</a>
			<a class='gwp_css_link_doc' href='https://docs.gravityforms.com/css-ready-classes/' target='_blank'>" . esc_html__( 'Legacy Ready Classes Documentation', 'gravitywp-css-selector' ) . "</a>
			</div>
			<div class='gwp_css_accordian'>
			<p>" . esc_html__( 'Use native Choice Layout settings instead of legacy choice layout shortcuts. Ready Classes are hidden on Gravity Forms 3.0 and later. Saved classes are not removed.', 'gravitywp-css-selector' ) . "</p>
			<p>" . esc_html__( 'Tip: double-click a class to add it and close the window.', 'gravitywp-css-selector' ) . '</p>
		  </div>
		</li>
		</ul>';
	?>

	<script>
		function removeTokenFromInput(input, tokenPos, seperator) {
			var text = input.val();
			var tokens = text.split(seperator);
			var newText = '';
			for (i = 0; i < tokens.length; i++) {
				if (tokens[i].replace(' ', '').replace(seperator, '') == '') {
					continue;
				}
				if (i != tokenPos) {
					newText += (tokens[i].trim() + seperator);
				}
			}
			input.val(fixTokens(newText, seperator));
		}

		function addTokenToInput(input, tokenToAdd, seperator) {
			var text = input.val().trim();
			if (text == '') {
				input.val(tokenToAdd);
			} else {
				if (!tokenExists(input, tokenToAdd, seperator)) {
					input.val(fixTokens(text + seperator + tokenToAdd, seperator));
				}
			}
		}

		function fixTokens(tokens, seperator) {
			var text = tokens.trim();
			var tokens = text.split(seperator);
			var newTokens = '';
			for (i = 0; i < tokens.length; i++) {
				var token = tokens[i].trim().replace(seperator, '');
				if (token == '') {
					continue;
				}
				newTokens += (token + seperator);
			}
			return newTokens;
		}

		function tokenExists(input, tokenToCheck, seperator) {
			var text = input.val().trim();
			if (text == '') return false;
			var tokens = text.split(seperator);
			for (i = 0; i < tokens.length; i++) {
				var token = tokens[i].trim().replace(seperator, '');
				if (token == '') {
					continue;
				}
				if (token == tokenToCheck) {
					return true;
				}
			}
			return false;
		}
		var gwpCssLegacyClasses = <?php
			// Include column classes even when current GF version hides their catalogue.
			preg_match_all( "/rel='([^']+)'/", $legacy_html, $legacy_matches );
			echo wp_json_encode( array_values( array_unique( array_merge(
				$legacy_matches[1],
				array( 'gf_left_half', 'gf_right_half', 'gf_left_third', 'gf_middle_third', 'gf_right_third', 'gf_first_quarter', 'gf_second_quarter', 'gf_third_quarter', 'gf_fourth_quarter' )
			) ) ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
		?>;
		var gwpCssStrings = <?php echo wp_json_encode( array(
			'legacy' => __( 'Legacy', 'gravitywp-css-selector' ),
			'legacyHelp' => __( 'Legacy Ready Class. Prefer native layout settings where available; behavior depends on form theme and Gravity Forms version.', 'gravitywp-css-selector' ),
			'empty' => __( 'No classes used on other fields.', 'gravitywp-css-selector' ),
			'field' => __( 'Field', 'gravitywp-css-selector' ),
		), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>;

		function gwpCssMarkLegacy($modal) {
			$modal.find('.gwp_css_link').each(function () {
				var $link = jQuery(this);
				var tokens = ($link.attr('rel') || '').split(/\s+/);
				if (tokens.some(function (token) { return gwpCssLegacyClasses.indexOf(token) !== -1; }) && !$link.find('.gwp-legacy-badge').length) {
					$link.append(jQuery('<small class="gwp-legacy-badge">').text(' — ' + gwpCssStrings.legacy));
					$link.attr('title', ($link.attr('title') || '') + ' — ' + gwpCssStrings.legacyHelp);
				}
			});
		}

		function gwpCssUsedClasses($modal, selected, editorForm) {
			var usage = Object.create(null);
			(editorForm.fields || []).forEach(function (other) {
				if (String(other.id) === String(selected.id)) {
					return;
				}
				var seen = Object.create(null);
				String(other.cssClass || '').split(/\s+/).forEach(function (token) {
					if (!token || seen[token]) {
						return;
					}
					seen[token] = true;
					if (!usage[token]) {
						usage[token] = [];
					}
					usage[token].push(gwpCssStrings.field + ' ' + other.id + (other.label ? ': ' + other.label : ''));
				});
			});
			var $links = $modal.find('.gwp-used-class-links').empty();
			var classes = Object.keys(usage).sort();
			if (!classes.length) {
				$links.append(jQuery('<p>').text(gwpCssStrings.empty));
			}
			classes.forEach(function (token) {
				$links.append(jQuery('<a class="gwp_css_link" href="#">')
					.attr('rel', token).attr('title', usage[token].join('\n'))
					.text(token + ' (' + usage[token].length + ')'));
			});
			gwpCssMarkLegacy($modal);
		}

		jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
			var $existingModal = jQuery('#css_ready_modal');
			if ($existingModal.length) {
				gwpCssUsedClasses($existingModal, field, form);
			}
			if (jQuery("#css_ready_selector").length == 0) {
				//add some html after the CSS Class Name input
				var $select_link = jQuery("<a id='css_ready_selector' class='thickbox' href='#TB_inline?width=500&height=550&inlineId=css_ready_modal'><span class='dashicons dashicons-text'></span></a>")
					.attr('aria-label', <?php echo wp_json_encode( __( 'Select a CSS class', 'gravitywp-css-selector' ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>);
				var $modal = jQuery(<?php echo wp_json_encode( $modal_html, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>).hide();
				gwpCssUsedClasses($modal, field, form);
				jQuery(".css_class_setting").append($select_link).append($modal);
				jQuery(".gwp_css_accordian").hide();
				$select_link.click(function(e) {
					e.preventDefault();
					var $m = jQuery("#css_ready_modal");
					var selected = typeof GetSelectedField === 'function' ? GetSelectedField() : field;
					var editorForm = window.form || form;
					if (selected) {
						gwpCssUsedClasses($m, selected, editorForm);
					}
					$m.find(".gwp_css_acc_link").unbind("click").click(function(e) {
						e.preventDefault();
						jQuery('.gwp_css_accordian:visible').slideUp();
						jQuery(this).parent("li:first").find(".gwp_css_accordian").slideDown();
					});
					var $links = $m.find(".gwp_css_link");
					$links.unbind("click").click(function(e) {
						e.preventDefault();
						var css = jQuery(this).attr("rel");
						addTokenToInput(jQuery("#field_css_class"), css, ' ');
						SetFieldProperty('cssClass', jQuery("#field_css_class").val().trim());
					});
					$links.unbind("dblclick").dblclick(function(e) {
						e.preventDefault();
						var css = jQuery(this).attr("rel");
						addTokenToInput(jQuery("#field_css_class"), css, ' ');
						SetFieldProperty('cssClass', jQuery("#field_css_class").val().trim());
						tb_remove();
					});
					tb_show('', this.href, false);
				});
			}
		});
	</script>
	<?php
}
// Translation files of the plugin.
add_action( 'plugins_loaded', 'gwp_css_selector_load_textdomain' );
/**
 * Gwp_css_selector_load_textdomain.
 *
 * @global
 * @return  void
 */
function gwp_css_selector_load_textdomain() {
	load_plugin_textdomain( 'gravitywp-css-selector', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
