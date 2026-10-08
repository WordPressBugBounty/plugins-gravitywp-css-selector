<?php
/**
 * Plugin-level CSS class library.
 */

namespace GravityWP\CSSSelector;

use GFAddOn;

defined( 'ABSPATH' ) || exit;

class CSS_Selector_AddOn extends GFAddOn {
	private static $_instance = null;

	protected $_version = '1.1.1';
	protected $_slug = 'gravitywp-css-selector';
	protected $_path = 'gravitywp-css-selector/gravitywp-css-selector.php';
	protected $_full_path = __DIR__ . '/../gravitywp-css-selector.php';
	protected $_title = 'GravityWP - CSS Selector';
	protected $_short_title = 'CSS Selector';
	protected $_url = 'https://gravitywp.com/plugins/css-selector/';
	protected $_enable_rg_autoupgrade = false;
	protected $_capabilities = array( 'gwp_css_selector_manage' );
	protected $_capabilities_settings_page = 'gwp_css_selector_manage';

	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function plugin_settings_fields() {
		return array(
			array(
				'title'       => esc_html__( 'Class library', 'gravitywp-css-selector' ),
				'description' => esc_html__( 'Reusable class shortcuts for every form. Enter class names without a leading dot. This library does not create CSS rules. Removing a shortcut does not remove classes from saved fields.', 'gravitywp-css-selector' ),
				'fields'      => array(
					array(
						'name'                => 'class_library',
						'label'               => esc_html__( 'Classes', 'gravitywp-css-selector' ),
						'type'                => 'gwp_class_library',
						'validation_callback' => array( $this, 'validate_class_library' ),
						'save_callback'       => array( $this, 'save_class_library' ),
					),
				),
			),
		);
	}

	public function scripts() {
		return array_merge(
			parent::scripts(),
			array(
				array(
					'handle'  => 'gwp-css-selector-library',
					'src'     => plugins_url( 'assets/js/class-library.js', $this->_full_path ),
					'version' => $this->_version,
					'deps'    => array( 'jquery' ),
					'enqueue' => array(
						array( 'admin_page' => array( 'plugin_settings' ), 'tab' => $this->_slug ),
					),
				),
			)
		);
	}

	/** Render nested inputs; GF handles the save nonce and capability check. */
	public function settings_gwp_class_library( $field, $should_echo = true ) {
		$rows = $this->get_setting( 'class_library' );
		$rows = is_array( $rows ) ? array_values( $rows ) : array();
		ob_start();
		?>
		<div id="gwp-class-library">
			<input type="hidden" name="_gform_setting_class_library" value="">
			<table class="widefat striped">
				<thead><tr>
					<?php foreach ( $this->library_columns() as $label ) : ?>
						<th scope="col"><?php echo esc_html( $label ); ?></th>
					<?php endforeach; ?>
					<th scope="col"><?php esc_html_e( 'Actions', 'gravitywp-css-selector' ); ?></th>
				</tr></thead>
				<tbody>
					<?php foreach ( $rows as $index => $row ) : ?>
						<?php $this->render_library_row( $index, is_array( $row ) ? $row : array() ); ?>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p><button type="button" class="button gwp-library-add"><?php esc_html_e( 'Add class', 'gravitywp-css-selector' ); ?></button></p>
			<template id="gwp-library-row-template"><?php $this->render_library_row( '__index__', array() ); ?></template>
		</div>
		<?php
		$html = ob_get_clean();
		if ( $should_echo ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in template.
		}
		return $html;
	}

	private function library_columns() {
		return array(
			'class'       => __( 'Class name', 'gravitywp-css-selector' ),
			'label'       => __( 'Label', 'gravitywp-css-selector' ),
			'description' => __( 'Description', 'gravitywp-css-selector' ),
			'group'       => __( 'Group', 'gravitywp-css-selector' ),
		);
	}

	private function render_library_row( $index, $row ) {
		?>
		<tr>
			<?php foreach ( $this->library_columns() as $key => $label ) : ?>
				<td><input type="text" style="width:100%" aria-label="<?php echo esc_attr( $label ); ?>" name="<?php echo esc_attr( '_gform_setting_class_library[' . $index . '][' . $key . ']' ); ?>" value="<?php echo esc_attr( isset( $row[ $key ] ) && is_scalar( $row[ $key ] ) ? (string) $row[ $key ] : '' ); ?>"></td>
			<?php endforeach; ?>
			<td><button type="button" class="button gwp-library-remove"><?php esc_html_e( 'Remove', 'gravitywp-css-selector' ); ?></button></td>
		</tr>
		<?php
	}

	/** Reject invalid/duplicate names rather than silently rewriting tokens. */
	public function validate_class_library( $field, $value ) {
		if ( '' === $value || null === $value ) {
			return;
		}
		if ( ! is_array( $value ) ) {
			$this->set_field_error( $field, esc_html__( 'Invalid class library.', 'gravitywp-css-selector' ) );
			return;
		}
		$seen = array();
		foreach ( $value as $row ) {
			if ( ! is_array( $row ) ) {
				$this->set_field_error( $field, esc_html__( 'Invalid class library row.', 'gravitywp-css-selector' ) );
				return;
			}
			$has_value = false;
			foreach ( $this->library_columns() as $key => $label ) {
				if ( isset( $row[ $key ] ) && ! is_string( $row[ $key ] ) ) {
					$this->set_field_error( $field, esc_html__( 'Class library values must be text.', 'gravitywp-css-selector' ) );
					return;
				}
				if ( isset( $row[ $key ] ) && '' !== trim( $row[ $key ] ) ) {
					$has_value = true;
				}
			}
			if ( ! $has_value ) {
				continue;
			}
			$class = isset( $row['class'] ) ? trim( $row['class'] ) : '';
			if ( ! preg_match( '/^[a-zA-Z_-][a-zA-Z0-9_-]*$/D', $class ) ) {
				$this->set_field_error( $field, esc_html__( 'Each row needs one class name: start with a letter, underscore or hyphen; use only letters, numbers, underscores and hyphens. Do not include a dot or spaces.', 'gravitywp-css-selector' ) );
				return;
			}
			if ( isset( $seen[ $class ] ) ) {
				$this->set_field_error( $field, esc_html__( 'Class names must be unique.', 'gravitywp-css-selector' ) );
				return;
			}
			$seen[ $class ] = true;
		}
	}

	public function save_class_library( $field, $value ) {
		$rows = array();
		foreach ( is_array( $value ) ? $value : array() as $row ) {
			if ( ! is_array( $row ) || empty( $row['class'] ) || ! is_string( $row['class'] ) ) {
				continue;
			}
			$class = trim( $row['class'] );
			if ( ! preg_match( '/^[a-zA-Z_-][a-zA-Z0-9_-]*$/D', $class ) ) {
				continue;
			}
			$clean = array( 'class' => $class );
			foreach ( array( 'label', 'description', 'group' ) as $key ) {
				$clean[ $key ] = isset( $row[ $key ] ) && is_string( $row[ $key ] ) ? sanitize_text_field( $row[ $key ] ) : '';
			}
			$rows[] = $clean;
		}
		return $rows;
	}

	/** Escaped shortcut markup, grouped in saved order. */
	public function library_html() {
		$groups = array();
		foreach ( $this->save_class_library( null, $this->get_plugin_setting( 'class_library' ) ) as $row ) {
			$group = '' !== $row['group'] ? $row['group'] : __( 'Custom classes', 'gravitywp-css-selector' );
			$groups[ $group ][] = $row;
		}
		$html = '';
		foreach ( $groups as $group => $rows ) {
			$html .= '<li><a class="gwp_css_acc_link" href="#">' . esc_html( $group ) . '</a><div class="gwp_css_accordian">';
			foreach ( $rows as $row ) {
				$label = '' !== $row['label'] ? $row['label'] : $row['class'];
				$title = $row['class'] . ( '' !== $row['description'] ? ': ' . $row['description'] : '' );
				$html .= '<a class="gwp_css_link" href="#" rel="' . esc_attr( $row['class'] ) . '" title="' . esc_attr( $title ) . '">' . esc_html( $label ) . '</a>';
			}
			$html .= '</div></li>';
		}
		return $html;
	}
}
