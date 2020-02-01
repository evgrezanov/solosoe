<?php

namespace SOLOSOE\Product;

defined('ABSPATH') || exit;

/**
 * Class for display product from API
 */
class SOLOSOE_DISPLAY_PRODUCT {

    public static function init(){
        add_shortcode('display_product', [__CLASS__, 'display_product']);
    }

    /**
    * Shortcode for display product
    */
    public static function display_product(){
        
        if ( !empty($_REQUEST['prd_id']) ):

            // get current product id 
            $product_id = $_REQUEST['prd_id'];
        
            // check id and chose corret link
            $id_start = (int)substr($product_id, 0, 1);
            if ($id_start > 6):
                $prd_info_url = 'https://cima.aemps.es/cima/rest/medicamento?cn='.$product_id;
                $prd_price_url = 'https://cima.aemps.es/cima/rest/psuministro/'.$product_id.'/';
                $prd_data = self::get_product_data($product_id, $prd_info_url, $prd_price_url);
            else:
                $prd_info_url = 'http://34.243.79.103:8000/services/product/'.$product_id.'/';
                $prd_price_url = 'http://34.243.79.103:8000/services/optimal-price/'.$product_id.'/';
                $prd_data = self::get_product_data($product_id, $prd_info_url, $prd_price_url);
            endif;

        endif;    
    }
    
    /**
    *  Get product data by API
    */
    public static function get_product_data($product_id, $info_url, $price_url) {
        $data = file_get_contents($info_url);
        $price = file_get_contents($price_url);
        var_dump($data);
        var_dump($price);
    }
}

SOLOSOE_DISPLAY_PRODUCT::init();