<?php

namespace SOLOSOE\Search;

defined('ABSPATH') || exit;

/**
 * Class for display typehead script
 */
class SOLOSOE_SEARCH_FORM {

    public static $solr_default_args = [
        'ip'        =>  '52.209.195.0',
        'port'      =>  '8984',
        'defType'   =>  'dismax',
        'fl'        =>  '*%2Cscore',
        'mm'        =>  '70%25',
        'pf'        =>  'name',
        'ps'        =>  '1',
        'qf'        =>  'name_code',
        'core_name' =>  'product_name_code_v2'
    ];
    
    /**
     * SolrRequest init.
     */
    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        add_shortcode('solr_search_form', [__CLASS__, 'solr_search_form']);
    }

    /**
     * Enqueue scripts.
     */
    public static function assets(){
        
        wp_enqueue_script( 'typeahead', SOLOSOE_URL . 'asset/lib/typeahead/typeahead.bundle.min.js', array(), '1.0.0' );
        
        $arg_array = [
            'solr_url'  =>  self::get_solr_url(),
            'site_url'  =>  get_site_url().'/solosoe-search/?prd_id=',
        ];

        wp_register_script(
            'solosoe_script',
            plugins_url('solosoe/asset/script.js')
        );
        
        wp_localize_script(
            'solosoe_script',
            'solrUrl',
            $arg_array
        );

        wp_enqueue_script(
            'solosoe_script',
            plugins_url('solosoe/asset/script.js'),
            ['jquery', 'typeahead'],
            $ver = '1.8',
            true
        );

        wp_enqueue_style('solosoe_styles', SOLOSOE_URL . 'asset/style.css');
        wp_enqueue_style('bootstrap', SOLOSOE_URL . 'asset/lib/bootstrap/css/bootstrap.min.css');
    }

    /**
     * Get Solr url from option
     */
    public static function get_solr_url(){
        // to do delete all parameters - store it at 1 field
        $desired_query = '%QUERY';
        if ($frm_base_options = get_option('solrurl_param')):
            $url  = 'http://' . $frm_base_options['ip_0'] . ':' . $frm_base_options['port_1'];
            $url .= '/solr/';
            $url .= $frm_base_options['core_name_9'] . '/select?defType=' . $frm_base_options['deftype_2'];
            $url .= '&fl=' . $frm_base_options['fl_3'];
            $url .= '&mm=' . $frm_base_options['mm_4'];
            $url .= '&pf=' . $frm_base_options['pf_5'];
            $url .= '&ps=' . $frm_base_options['ps_6'];
            $url .= '&qf=' . $frm_base_options['qf_7'];
            $url .= '&q=' . $desired_query;
        else:
            // default parametrs
            //http://52.209.195.0:8984/solr/product_name_code_v2/select?defType=dismax&fl=*%2Cscore&mm=70%25&pf=name&ps=1&qf=name_code%20&q=%QUERY
            $url  = 'http://' . self::$solr_default_args['ip'] . ':' . self::$solr_default_args['port'];
            $url .= '/solr/';
            $url .= $solr_default_args['core_name_9'] . '/select?defType=' . self::$solr_default_args['defType'];
            $url .= '&fl=' . self::$solr_default_args['fl'];
            $url .= '&mm=' . self::$solr_default_args['mm'];
            $url .= '&pf=' . self::$solr_default_args['pf'];
            $url .= '&ps=' . self::$solr_default_args['ps'];
            $url .= '&qf=' . self::$solr_default_args['qf'];
            $url .= '&q=' . $desired_query;
        endif;
        
        return $url;
    }

    /**
     * Add shortcode for search form
     */
    public static function solr_search_form(){
        ob_start();    
        ?> 
        <div id="solosoe-custom-templates" class="container p-3">
	        <form>
		        <div class="form-group">
			        <input id="solr-typeahead" type="search" style="width:500px;" class="search-field" placeholder="Start type product name"/>
		        </div>
	        </form>
        </div>

    <?php 
        return ob_get_clean();
    }
}

SOLOSOE_SEARCH_FORM::init();
