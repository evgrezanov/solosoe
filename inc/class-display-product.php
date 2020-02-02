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
                $product_info = self::get_product_info($product_id, $prd_info_url);
                $product_price = self::get_product_price($product_id, $prd_price_url);
                
                //echo self::display_product_details($product_id, $product_info, $product_price);
                //echo self::display_product_details2();
                echo self::display_product_card($product_id, $product_info, $product_price);
            endif;

        endif;    
    }

   /**
    *   Display product detail with id < 6
    *   http://34.243.79.103:8000/services/product/
    *   http://34.243.79.103:8000/services/optimal-price/
    */
    public static function display_product_details($product_id, $product_info, $product_price){
    $master_details = $product_info->master_details;
    ob_start();
    ?>
    <div class="row">
        <div class="card">
            <div class="row no-gutters">
                <div class="col-md-4">
                <img src="<?php echo $product_info->images[1]; ?>" class="card-img" alt="<?php echo $product_info->product_name; ?>">
            </div>
            <div class="col-md-8">
                
                <div class="card-header">
                    <h5 class="card-title"><?php echo $product_info->product_name; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $product_info->ean; ?></h6>
                    <small class="text-muted"><?php echo $product_info->cn_dot_7; ?></small>
                    <div class="text-warning text-center mt-3">
                        <h4>Precio Competitevo</h4>
                    </div>
                    <div class="text-warning text-center mt-2">
                        <h1><?php echo $product_price->price; ?></h1>
                    </div>
                    
                </div>
                
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Rango de precios: <?php echo $product_info->min_sale_price.' - '.$product_info->max_sale_price; ?></li>
                        <li class="list-group-item">№ de Comercios: <?php echo $product_info->mst_prd_id; ?></li>
                        <li class="list-group-item">no_of_shops: <?php echo $product_info->no_of_shops; ?></li>
                    </ul>
                    <p class="card-text">
                        <?php echo $product_info->product_description; ?>
                    </p>
                    <?php 
                        if (isset($master_details)):
                            do_action('display_shops', $master_details); 
                        endif;    
                    ?>
                </div>
                
                <div class="card-footer">
                    <p class="card-text">
                        <?php echo $product_info->product_description; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php 
    return ob_get_clean();
    }
    

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

    public static function display_product_card($product_id, $product_info, $product_price){
    $master_details = $product_info->master_details;
    ob_start();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col-auto d-none d-lg-block">
                        <img src="<?php echo $product_info->images[0]; ?>" class="card-img" alt="<?php echo $product_info->product_name; ?>">
                    </div>
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong class="d-inline-block mb-2 text-primary"><?php echo '#'.$product_id; ?></strong>
                        
                        <h3 class="mb-0"><?php echo $product_info->product_name; ?></h3>
                        
                        <h4>Precio Competitevo <span class="badge badge-warning"><?php echo $product_price->price; ?> &#8364;</span></h4>
                        
                        <div class="mb-1 text-muted">
                            <?php echo $product_info->ean; ?>
                        </div>
                        
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
                        </ul>
                        
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
   /**
    *   Display product detail with id > 6
    *   https://cima.aemps.es/cima/rest/medicamento?cn={{product_id}}
    *   https://cima.aemps.es/cima/rest/psuministro/{{product_id}}
    */
    public static function display_product_details2(){
        ob_start();
        ?>
        <div class="card">
	        <div class="row">
		        <aside class="col-sm-5 border-right">
                    <article class="gallery-wrap"> 
                        <div class="img-big-wrap">
                            <div> <a href="#"><img src="https://s9.postimg.org/tupxkvfj3/image.jpg"></a></div>
                        </div> <!-- slider-product.// -->
                        <div class="img-small-wrap">
                            <div class="item-gallery"> <img src="https://s9.postimg.org/tupxkvfj3/image.jpg"> </div>
                            <div class="item-gallery"> <img src="https://s9.postimg.org/tupxkvfj3/image.jpg"> </div>
                            <div class="item-gallery"> <img src="https://s9.postimg.org/tupxkvfj3/image.jpg"> </div>
                            <div class="item-gallery"> <img src="https://s9.postimg.org/tupxkvfj3/image.jpg"> </div>
                        </div> <!-- slider-nav.// -->
                    </article> <!-- gallery-wrap .end// -->
		        </aside>
		        <aside class="col-sm-7">
                    <article class="card-body p-5">
	                    <h3 class="title mb-3">Original Version of Some product name</h3>
                        <p class="price-detail-wrap"> 
	                        <span class="price h3 text-warning"> 
		                        <span class="currency">US $</span><span class="num">1299</span>
	                        </span> 
	                        <span>/per kg</span> 
                        </p> <!-- price-detail-wrap .// -->
                        <dl class="item-property">
                            <dt>Description</dt>
                            <dd><p>Here goes description consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco </p></dd>
                        </dl>
                        <dl class="param param-feature">
                            <dt>Model#</dt>
                            <dd>12345611</dd>
                        </dl>  <!-- item-property-hor .// -->
                        <dl class="param param-feature">
                            <dt>Color</dt>
                            <dd>Black and white</dd>
                        </dl>  <!-- item-property-hor .// -->
                        <dl class="param param-feature">
                            <dt>Delivery</dt>
                            <dd>Russia, USA, and Europe</dd>
                        </dl>  <!-- item-property-hor .// -->
                        <a href="#" class="btn btn-lg btn-primary text-uppercase"> Buy now </a>
                        <a href="#" class="btn btn-lg btn-outline-primary text-uppercase"> <i class="fas fa-shopping-cart"></i> Add to cart </a>
                    </article> <!-- card-body.// -->
                </aside> <!-- col.// -->
            </div> <!-- row.// -->
        </div> <!-- card.// -->
        <?php 
        return ob_get_clean();
        }
    
   
    
   
   /**
    *  Get product  price by API
    */
    public static function get_product_price($product_id, $price_url) {
        
        $price = json_decode(file_get_contents($price_url));
        //var_dump($price);
        return $price;
    }
   
   /**
    *  Get product info by API
    */
    public static function get_product_info($product_id, $info_url) {
        //var_dump($info_url);
        $data = json_decode(file_get_contents($info_url));
        //var_dump($data);
        return $data;
    }


}

SOLOSOE_DISPLAY_PRODUCT::init();