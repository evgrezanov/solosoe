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
                
                echo self::display_product_details($product_id, $product_info, $product_price);
                echo self::display_product_details2();
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
    

    public static function display_shops($master_details){
      foreach ($master_details as $details):
        ?>
        <ul>
            <li><?php echo $details->marketplace;?></li>
            <li><?php echo $details->no_of_shops;?></li>
            <li><?php echo $details->min_price;?></li>
            <li><?php echo $details->max_price;?></li>
        </ul>
        <?php
      endforeach;
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
                        <hr>
                            <div class="row">
                                <div class="col-sm-5">
                                    <dl class="param param-inline">
                                    <dt>Quantity: </dt>
                                    <dd>
                                        <select class="form-control form-control-sm" style="width:70px;">
                                            <option> 1 </option>
                                            <option> 2 </option>
                                            <option> 3 </option>
                                        </select>
                                    </dd>
                                    </dl>  <!-- item-property .// -->
                                </div> <!-- col.// -->
                                <div class="col-sm-7">
                                    <dl class="param param-inline">
                                        <dt>Size: </dt>
                                        <dd>
                                            <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                            <span class="form-check-label">SM</span>
                                            </label>
                                            <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                            <span class="form-check-label">MD</span>
                                            </label>
                                            <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                            <span class="form-check-label">XXL</span>
                                            </label>
                                        </dd>
                                    </dl>  <!-- item-property .// -->
                                </div> <!-- col.// -->
                            </div> <!-- row.// -->
                            <hr>
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
        var_dump($price);
        return $price;
    }
   
   /**
    *  Get product info by API
    */
    public static function get_product_info($product_id, $info_url) {
        var_dump($info_url);
        $data = json_decode(file_get_contents($info_url));
        var_dump($data);
        return $data;
    }


}

SOLOSOE_DISPLAY_PRODUCT::init();