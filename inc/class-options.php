<?php

class FRMBASE {
	private $frm_base_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'frm_base_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'frm_base_page_init' ) );
	}

	public function frm_base_add_plugin_page() {
		add_menu_page(
			'FRM-BASE', // page_title
			'FRM-BASE', // menu_title
			'manage_options', // capability
			'frm-base', // menu_slug
			array( $this, 'frm_base_create_admin_page' ), // function
			'dashicons-admin-tools', // icon_url
			3 // position
		);
	}

	public function frm_base_create_admin_page() {
		$this->frm_base_options = get_option( 'frm_base_option_name' ); ?>

		<div class="wrap">
			<h2>FRM-BASE</h2>
			<p>Solr connection parameters</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'frm_base_option_group' );
					do_settings_sections( 'frm-base-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function frm_base_page_init() {
		register_setting(
			'frm_base_option_group', // option_group
			'frm_base_option_name', // option_name
			array( $this, 'frm_base_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'frm_base_setting_section', // id
			'Settings', // title
			array( $this, 'frm_base_section_info' ), // callback
			'frm-base-admin' // page
		);

		add_settings_field(
			'ip_0', // id
			'IP', // title
			array( $this, 'ip_0_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'port_1', // id
			'Port', // title
			array( $this, 'port_1_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'deftype_2', // id
			'defType', // title
			array( $this, 'deftype_2_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'fl_3', // id
			'fl', // title
			array( $this, 'fl_3_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'mm_4', // id
			'mm', // title
			array( $this, 'mm_4_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'pf_5', // id
			'pf', // title
			array( $this, 'pf_5_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'ps_6', // id
			'ps', // title
			array( $this, 'ps_6_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'qf_7', // id
			'qf', // title
			array( $this, 'qf_7_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'rows_8', // id
			'rows', // title
			array( $this, 'rows_8_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'core_name_9', // id
			'core_name', // title
			array( $this, 'core_name_9_callback' ), // callback
			'frm-base-admin', // page
			'frm_base_setting_section' // section
		);
	}

	public function frm_base_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['ip_0'] ) ) {
			$sanitary_values['ip_0'] = sanitize_text_field( $input['ip_0'] );
		}

		if ( isset( $input['port_1'] ) ) {
			$sanitary_values['port_1'] = sanitize_text_field( $input['port_1'] );
		}

		if ( isset( $input['deftype_2'] ) ) {
			$sanitary_values['deftype_2'] = sanitize_text_field( $input['deftype_2'] );
		}

		if ( isset( $input['fl_3'] ) ) {
			$sanitary_values['fl_3'] = sanitize_text_field( $input['fl_3'] );
		}

		if ( isset( $input['mm_4'] ) ) {
			$sanitary_values['mm_4'] = sanitize_text_field( $input['mm_4'] );
		}

		if ( isset( $input['pf_5'] ) ) {
			$sanitary_values['pf_5'] = sanitize_text_field( $input['pf_5'] );
		}

		if ( isset( $input['ps_6'] ) ) {
			$sanitary_values['ps_6'] = sanitize_text_field( $input['ps_6'] );
		}

		if ( isset( $input['qf_7'] ) ) {
			$sanitary_values['qf_7'] = sanitize_text_field( $input['qf_7'] );
		}

		if ( isset( $input['rows_8'] ) ) {
			$sanitary_values['rows_8'] = sanitize_text_field( $input['rows_8'] );
		}

		if ( isset( $input['core_name_9'] ) ) {
			$sanitary_values['core_name_9'] = sanitize_text_field( $input['core_name_9'] );
		}

		return $sanitary_values;
	}

	public function frm_base_section_info() {

	}

	public function ip_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[ip_0]" id="ip_0" value="%s">',
			isset( $this->frm_base_options['ip_0'] ) ? esc_attr( $this->frm_base_options['ip_0']) : ''
		);
	}

	public function port_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[port_1]" id="port_1" value="%s">',
			isset( $this->frm_base_options['port_1'] ) ? esc_attr( $this->frm_base_options['port_1']) : ''
		);
	}

	public function deftype_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[deftype_2]" id="deftype_2" value="%s">',
			isset( $this->frm_base_options['deftype_2'] ) ? esc_attr( $this->frm_base_options['deftype_2']) : ''
		);
	}

	public function fl_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[fl_3]" id="fl_3" value="%s">',
			isset( $this->frm_base_options['fl_3'] ) ? esc_attr( $this->frm_base_options['fl_3']) : ''
		);
	}

	public function mm_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[mm_4]" id="mm_4" value="%s">',
			isset( $this->frm_base_options['mm_4'] ) ? esc_attr( $this->frm_base_options['mm_4']) : ''
		);
	}

	public function pf_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[pf_5]" id="pf_5" value="%s">',
			isset( $this->frm_base_options['pf_5'] ) ? esc_attr( $this->frm_base_options['pf_5']) : ''
		);
	}

	public function ps_6_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[ps_6]" id="ps_6" value="%s">',
			isset( $this->frm_base_options['ps_6'] ) ? esc_attr( $this->frm_base_options['ps_6']) : ''
		);
	}

	public function qf_7_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[qf_7]" id="qf_7" value="%s">',
			isset( $this->frm_base_options['qf_7'] ) ? esc_attr( $this->frm_base_options['qf_7']) : ''
		);
	}

	public function rows_8_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[rows_8]" id="rows_8" value="%s">',
			isset( $this->frm_base_options['rows_8'] ) ? esc_attr( $this->frm_base_options['rows_8']) : ''
		);
	}

	public function core_name_9_callback() {
		printf(
			'<input class="regular-text" type="text" name="frm_base_option_name[core_name_9]" id="core_name_9" value="%s">',
			isset( $this->frm_base_options['core_name_9'] ) ? esc_attr( $this->frm_base_options['core_name_9']) : ''
		);
	}

}
if ( is_admin() )
	$frm_base = new FRMBASE();

/*
 * Retrieve this value with:
 * $frm_base_options = get_option( 'frm_base_option_name' ); // Array of All Options
 * $ip_0 = $frm_base_options['ip_0']; // IP
 * $port_1 = $frm_base_options['port_1']; // Port
 * $deftype_2 = $frm_base_options['deftype_2']; // defType
 * $fl_3 = $frm_base_options['fl_3']; // fl
 * $mm_4 = $frm_base_options['mm_4']; // mm
 * $pf_5 = $frm_base_options['pf_5']; // pf
 * $ps_6 = $frm_base_options['ps_6']; // ps
 * $qf_7 = $frm_base_options['qf_7']; // qf
 * $rows_8 = $frm_base_options['rows_8']; // rows
 * $core_name_9 = $frm_base_options['core_name_9']; // core_name
 */