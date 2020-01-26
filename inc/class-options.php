<?php

/*
 * Retrieve this value with:
 * 
 * $solosoe_options = get_option( 'solrurl_param' ); // Array of All Options
 * $ip_0 = $solosoe_options['ip_0']; // IP
 * $port_1 = $solosoe_options['port_1']; // Port
 * $deftype_2 = $solosoe_options['deftype_2']; // defType
 * $fl_3 = $solosoe_options['fl_3']; // fl
 * $mm_4 = $solosoe_options['mm_4']; // mm
 * $pf_5 = $solosoe_options['pf_5']; // pf
 * $ps_6 = $solosoe_options['ps_6']; // ps
 * $qf_7 = $solosoe_options['qf_7']; // qf
 * $core_name_9 = $solosoe_options['core_name_9']; // core_name
 * 
 */


 class SOLOSOE_OPTIONS {
	
	private $solosoe_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'solosoe_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'solosoe_page_init' ) );
	}

	public function solosoe_add_plugin_page() {
		add_menu_page(
			'SOLOSOE Options', 
			'SOLOSOE', 
			'manage_options',
			'solosoe-options',
			array( $this, 'solosoe_create_admin_page' ),
			'dashicons-admin-tools', // icon_url
			3 // position
		);
	}

	public function solosoe_create_admin_page() {
		$this->solosoe_options = get_option( 'solrurl_param' ); ?>

		<div class="wrap">
			<h2>SOLOSOE options page</h2>
			<p>Solr connection parameters</p>
			<a href="http://52.209.195.0:8984/solr/product_name_code_v2/select?defType=dismax&fl=*%2Cscore&mm=70%25&pf=name&ps=1&q=Peusek%20Arcandol%20spray&qf=name_code&wt=json" target="_blank">http://52.209.195.0:8984/solr/product_name_code_v2/select?defType=dismax&fl=*%2Cscore&mm=70%25&pf=name&ps=1&q=Peusek%20Arcandol%20spray&qf=name_code&wt=json</a>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'solosoe_option_group' );
					do_settings_sections( 'solosoe-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function solosoe_page_init() {
		register_setting(
			'solosoe_option_group', // option_group
			'solrurl_param', // option_name
			array( $this, 'solosoe_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'frm_base_setting_section', // id
			'Settings', // title
			array( $this, 'frm_base_section_info' ), // callback
			'solosoe-admin' // page
		);

		add_settings_field(
			'ip_0', // id
			'IP', // title
			array( $this, 'ip_0_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'port_1', // id
			'Port', // title
			array( $this, 'port_1_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'deftype_2', // id
			'defType', // title
			array( $this, 'deftype_2_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'fl_3', // id
			'fl', // title
			array( $this, 'fl_3_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'mm_4', // id
			'mm', // title
			array( $this, 'mm_4_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'pf_5', // id
			'pf', // title
			array( $this, 'pf_5_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'ps_6', // id
			'ps', // title
			array( $this, 'ps_6_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'qf_7', // id
			'qf', // title
			array( $this, 'qf_7_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);

		add_settings_field(
			'core_name_9', // id
			'core_name', // title
			array( $this, 'core_name_9_callback' ), // callback
			'solosoe-admin', // page
			'frm_base_setting_section' // section
		);
	}

	public function solosoe_sanitize($input) {
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

		if ( isset( $input['core_name_9'] ) ) {
			$sanitary_values['core_name_9'] = sanitize_text_field( $input['core_name_9'] );
		}

		return $sanitary_values;
	}

	public function frm_base_section_info() {

	}

	public function ip_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[ip_0]" id="ip_0" value="%s">',
			isset( $this->solosoe_options['ip_0'] ) ? esc_attr( $this->solosoe_options['ip_0']) : ''
		);
	}

	public function port_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[port_1]" id="port_1" value="%s">',
			isset( $this->solosoe_options['port_1'] ) ? esc_attr( $this->solosoe_options['port_1']) : ''
		);
	}

	public function deftype_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[deftype_2]" id="deftype_2" value="%s">',
			isset( $this->solosoe_options['deftype_2'] ) ? esc_attr( $this->solosoe_options['deftype_2']) : ''
		);
	}

	public function fl_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[fl_3]" id="fl_3" value="%s">',
			isset( $this->solosoe_options['fl_3'] ) ? esc_attr( $this->solosoe_options['fl_3']) : ''
		);
	}

	public function mm_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[mm_4]" id="mm_4" value="%s">',
			isset( $this->solosoe_options['mm_4'] ) ? esc_attr( $this->solosoe_options['mm_4']) : ''
		);
	}

	public function pf_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[pf_5]" id="pf_5" value="%s">',
			isset( $this->solosoe_options['pf_5'] ) ? esc_attr( $this->solosoe_options['pf_5']) : ''
		);
	}

	public function ps_6_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[ps_6]" id="ps_6" value="%s">',
			isset( $this->solosoe_options['ps_6'] ) ? esc_attr( $this->solosoe_options['ps_6']) : ''
		);
	}

	public function qf_7_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[qf_7]" id="qf_7" value="%s">',
			isset( $this->solosoe_options['qf_7'] ) ? esc_attr( $this->solosoe_options['qf_7']) : ''
		);
	}

	public function core_name_9_callback() {
		printf(
			'<input class="regular-text" type="text" name="solrurl_param[core_name_9]" id="core_name_9" value="%s">',
			isset( $this->solosoe_options['core_name_9'] ) ? esc_attr( $this->solosoe_options['core_name_9']) : ''
		);
	}
}
if ( is_admin() )
	$frm_base = new SOLOSOE_OPTIONS();

