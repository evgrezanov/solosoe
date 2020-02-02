<?php

namespace SOLOSOE\Product;

defined('ABSPATH') || exit;

/**
 * Class for display product from API
 */
class SOLOSOE_DISPLAY_PRODUCT {
   
   /**
    * Init class
    */
    public static function init(){
        add_shortcode('display_product', [__CLASS__, 'display_product']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        add_action('display_shops', [__CLASS__, 'display_shops'], 1, 1);
    }

    /**
     * Enqueue scripts.
     */
    public static function assets(){
        wp_enqueue_style('bootstrap', SOLOSOE_URL . 'asset/lib/bootstrap/css/bootstrap.min.css');
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
            
            if ($id_start >= 6):
                $prd_info_url = 'https://cima.aemps.es/cima/rest/medicamento?cn='.$product_id;
                $prd_price_url = 'https://cima.aemps.es/cima/rest/psuministro/'.$product_id;
            else:
                $prd_info_url = 'http://34.243.79.103:8000/services/product/'.$product_id.'/?format=json';
                $prd_price_url = 'http://34.243.79.103:8000/services/optimal-price/'.$product_id.'/?format=json';
            endif;

            // get product data
            if ( !empty($prd_price_url) && !empty($prd_info_url) ):
                $product_info = json_decode(file_get_contents($prd_info_url));
                $product_price = json_decode(file_get_contents($prd_price_url));
                echo self::display_product_card($product_id, $product_info, $product_price);
            endif;

        endif;    
    }

   /**
    * Help function for diusplay avalible shops
    */
    public static function display_shops($master_details){ ?>
        <div class='list-bottom'>
        <?php foreach ($master_details as $details): ?>
            <div class='list-bottom-section'>
                <span><?php echo $details->marketplace;?></span>
                <span class="badge badge-success"><?php echo $details->no_of_shops;?></span>
                <span><?php echo $details->min_price . ' - ' . $details->max_price;?></span>
            </div>
        <?php endforeach; ?> 
        </div>
      <?php
    }


   /**
    *   Display product detail with id < 6
    *   http://34.243.79.103:8000/services/product/{{144615}}/
    *   http://34.243.79.103:8000/services/optimal-price/{144615}/
    *
    *   Display product detail with id > 6    
    *   https://cima.aemps.es/cima/rest/psuministro/{{912485}}
    *   https://cima.aemps.es/cima/rest/medicamento?cn={{912485}}
    */
    public static function display_product_card($product_id, $product_info, $product_price){
        $master_details = $product_info->master_details;
        ob_start();
        ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                        <div class="card-header">
                            <?php echo $product_info->product_description; ?>
                        </div>
                        
                        <div class="col-auto d-none d-lg-block">
                            <!--img-->
                            <img src="<?php echo $product_info->images[0]; ?>" class="card-img" alt="<?php echo $product_info->product_name; ?>">
                        </div>
                        
                        <div class="col p-4 d-flex flex-column position-static">
                            <!--id-->
                            <strong class="d-inline-block mb-2 text-primary"><?php echo '#'.$product_id; ?></strong>
                            
                            <!--product name-->
                            <h3 class="mb-0"><?php echo $product_info->product_name; ?></h3>
                            
                            <!--price-->
                            <h4>Precio Competitevo <span class="badge badge-warning"><?php echo $product_price->price; ?> &#8364;</span></h4>
                            
                            <!--product properties-->
                            <ul class="list-group list-group-flush">
                                <?php if (isset($product_info->min_sale_price) && isset($product_info->max_sale_price)): ?>
                                    <li class="list-group-item">Rango de precios: <?php echo $product_info->min_sale_price.' - '.$product_info->max_sale_price; ?> &#8364; </li>
                                <?php endif; ?>

                                <?php if (isset($product_info->mst_prd_id)): ?>
                                    <li class="list-group-item">№ de Comercios: <?php echo $product_info->mst_prd_id; ?></li>
                                <?php endif; ?> 

                                <?php if (isset($product_info->no_of_shops)): ?>
                                    <li class="list-group-item">no_of_shops: <?php echo $product_info->no_of_shops; ?></li>
                                <?php endif; ?>

                                <?php if (isset($product_info->ean)): ?>
                                    <li class="list-group-item">ean: <?php echo $product_info->ean; ?></li>
                                <?php endif; ?>
                            </ul>
                            
                            <!--shops-->
                            <div class="card-text">
                                <?php if (!empty($master_details)): self::display_shops($master_details); endif; ?>
                            </div>
                            
                                <!--<a href="#" class="stretched-link">Continue reading</a>-->
                        </div>
                        
                        <div class="card-footer">
                            <p class="card-text mb-auto">
                                <?php echo $product_info->product_description; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }


}

SOLOSOE_DISPLAY_PRODUCT::init();