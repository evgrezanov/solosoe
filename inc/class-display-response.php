<?php

namespace FRMBS\SolrRequest;

defined('ABSPATH') || exit;

/**
 * Class DisplayResponse
 */
class DisplayResponse {

    /**
     * DisplayResponse init.
     */
    public static function init() {
        add_filter('pre_get_posts', [__CLASS__, 'solr_search_query']);
    }

    /**
    * Change default wordpress search query to solr query
    **/
    public static function solr_search_query($query) {
        if($query->is_search() && $query->is_main_query() && get_query_var('s', false)) {

            // Get the "s" query arg from the initial search
            $desired_query = get_query_var('s', false);

            if ($frm_base_options = get_option( 'frm_base_option_name' )):
                $url  = 'http://' . $frm_base_options['ip_0'] . ':' . $frm_base_options['port_1'];
                $url .= '/solr/product_name/select?defType=' . $frm_base_options['deftype_2'];
                $url .= '&fl=' . $frm_base_options['fl_3'];
                $url .= '&mm=' . $frm_base_options['mm_4'];
                $url .= '&pf=' . $frm_base_options['pf_5'];
                $url .= '&ps=' . $frm_base_options['ps_6'];
                $url .= '&q=' . $desired_query;
                $url .= '&qf=' . $frm_base_options['qf_7'];
                $url .= '&rows=' . $frm_base_options['rows_8'];
            else:
                // default parametrs
                //http://52.209.195.0:8984/solr/product_name/select?defType=dismax&fl=*%2Cscore&mm=100%25&pf=name&ps=1&q=NEUTROGENA%20PACK&qf=name&rows=3
                $url  = 'http://' . self::$solr_arg['ip'] . ':' . self::$solr_arg['port'];
                $url .= '/solr/product_name/select?defType=' . self::$solr_arg['defType'];
                $url .= '&fl=' . self::$solr_arg['fl'];
                $url .= '&mm=' . self::$solr_arg['mm'];
                $url .= '&pf=' . self::$solr_arg['pf'];
                $url .= '&ps=' . self::$solr_arg['ps'];
                $url .= '&q=' . $desired_query;
                $url .= '&qf=' . self::$solr_arg['qf'];
                $url .= '&rows=' . self::$solr_arg['rows'];
            endif;

            $result = file_get_contents($url);
            $data = json_decode($result, true);

            var_dump($data['response']);


            /*
            $ids = array();
            foreach ($data['response']['docs'] as $item){
                array_push($ids, $item['id']);
            }*/
            // Update the main query
            //$query->set('post__in', $ids);
        }

        return $query;
    }
}

SolrRequest::init();