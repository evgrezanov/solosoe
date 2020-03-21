<?php
use SOLOSOE\Search;

namespace SOLOSOE\Product;

defined('ABSPATH') || exit;

/**
 * Class for display product from API
 */
class SOLOSOE_DISPLAY_PRODUCT {

    public static $product = array(
        'info'                  => NULL,
        'info_error'            => NULL,
        'price'                 => NULL,
        'price_error'           => NULL,
        'cima_psuministro'      => NULL,
        'cima_psuministro_error'=> NULL,
        'cima_medicamento'      => NULL,
        'cima_medicamento_error'=> NULL,
    );
    
    public static $links = array(
        'link1' => NULL,
        'link2' => NULL,
        'link3' => NULL,
        'link4' => NULL,
    );
   
   /**
    * Init class
    */
    public static function init(){
        add_shortcode('display_product_card', [__CLASS__, 'render_product_card']);
        add_action('display_shops', [__CLASS__, 'display_shops'], 1, 1);
    }

    // Take all API data
    public static function get_data(){
        if ( !empty($_REQUEST['prd_id']) ):
                    
            // get current product id 
            $product_id = $_REQUEST['prd_id'];
            
            //todo change - store urls at DB as plugin options without .'/?format=json'
            $prd_info_url = 'http://34.243.79.103:8000/services/product/'.$product_id;
            self::$links['link1'] = $prd_info_url;
            $prd_price_url = 'http://34.243.79.103:8000/services/optimal-price/'.$product_id;
            self::$links['link2'] = $prd_price_url;

            $product_info = wp_remote_get($prd_info_url);
            if (is_wp_error($product_info)):
                $product_info_error = $product_info->get_error_message();
                self::$product['info_error'] = $product_info_error;
            elseif( wp_remote_retrieve_response_code( $product_info ) == 500 ):
                self::$product['info_error'] = '500  Internal Server Error';
            elseif( wp_remote_retrieve_response_code( $product_info ) === 200 ):
                $info = self::$product['info'] = wp_remote_retrieve_body( $product_info );
            endif;

            $product_price = wp_remote_get($prd_price_url);
            if (is_wp_error($product_price)):
               $product_price_error = $product_price->get_error_message();
               self::$product['price_error'] = $product_price_error;
            elseif( wp_remote_retrieve_response_code( $product_price ) == 500 ):
                self::$product['price_error'] = '500  Internal Server Error';
            elseif( wp_remote_retrieve_response_code( $product_price ) === 200 ):
                self::$product['price']  = wp_remote_retrieve_body( $product_price );
            endif;
                
            $info = json_decode($info);
            
            //$cn_dot_7 = round($info->cn_dot_7,0);
            $cn_dot_7 = floor($info->cn_dot_7);
            $cn_dot_1_7 = substr($cn_dot_7, 0, 1);
            if ( !is_null($cn_dot_7) && $cn_dot_1_7 >= 6):
                $cn_dot_7_tmp = $info->cn_dot_7;
                // test data
                //$cima_id = 912485;
                //$cima_id = '009258';
                $cima_id = $cn_dot_7;

                $cima_psuministro_url = 'https://cima.aemps.es/cima/rest/psuministro/'.$cima_id;
                self::$links['link3'] = $cima_psuministro_url;
                $cima_psuministro = wp_safe_remote_request($cima_psuministro_url, array('timeout'=>20));
                if (is_wp_error($cima_psuministro)):
                    $cima_psuministro_error = $cima_psuministro->get_error_message();
                    self::$product['cima_psuministro_error'] = $cima_psuministro_error;
                elseif( wp_remote_retrieve_response_code( $cima_psuministro ) === 200 ):
                    self::$product['cima_psuministro'] = wp_remote_retrieve_body($cima_psuministro);
                endif;

                $cima_medicamento_url = 'https://cima.aemps.es/cima/rest/medicamento?cn='.$cima_id;
                self::$links['link4'] = $cima_medicamento_url;
                $cima_medicamento = wp_safe_remote_request($cima_medicamento_url, array('timeout'=>20));
                if (is_wp_error($cima_medicamento)):
                    $cima_medicamento_error = $cima_medicamento->get_error_message();
                    // if request return error - display alert
                    self::$product['cima_medicamento_error'] = $cima_medicamento_error;
                elseif( wp_remote_retrieve_response_code( $cima_medicamento ) === 200 ):
                    self::$product['cima_medicamento'] = wp_remote_retrieve_body($cima_medicamento);
                endif;

            endif;
            
            return self::$product;
        endif;
    }

    //  Shortcode for display product
    public static function render_product_card(){
        $prod_data = self::get_data();
        $cima_medicamento = json_decode($prod_data['cima_medicamento']);
        $info = json_decode($prod_data['info']);
        ob_start();
        ?>
        <div id="solosoe-custom-templates" class="container">
            
            <!-- Search form-->
            <?php echo self::display_solr_search_form(); ?>
            
            <?php if( current_user_can('manage_options') && !empty(self::$links['link1'])): ?>
	            <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">You see this message, becourse you are administrator!</h4>
                    <p>link1: <a href="<?=self::$links['link1'] ?>" class="alert-link"><?=self::$links['link1'] ?></a></p>
                    <p>link2: <a href="<?=self::$links['link2'] ?>" class="alert-link"><?=self::$links['link2'] ?></a></p>
                    <p>link3: <a href="<?=self::$links['link3'] ?>" class="alert-link"><?=self::$links['link3'] ?></a></p>
                    <p>link4: <a href="<?=self::$links['link4'] ?>" class="alert-link"><?=self::$links['link4'] ?></a></p>
                    <hr>
                    <p class="mb-0">follow the links to view the returned data on services</p>
                </div>
            <?php
                endif;
            ?>    

            <!-- Card Start -->
            <div class="card solosoe-main-product-data">
                
                <div class="row solosoe-product-main-data">
                                
                    <!-- Carousel -->
                    <div class="col-md-5">
                        <?php 
                            echo self::display_products_imgs($info->images);
                            
                            if ( !empty($fotos = $cima_medicamento->fotos) ): 
                                foreach ($fotos as $foto):?>
                                    <img src="<?=$foto->url?>" alt="<?=$foto->tipo?>" class="img-thumbnail">
                                <?php
                                endforeach;
                            endif;
                        ?>
                    </div>    
                                
                    <!-- Product info -->
                    <div class="col-md-7 px-3">           
                       <?php 
                        $cima_psuministro = json_decode($prod_data['cima_psuministro']);
                        if (!empty($cima_psuministro->resultados)):
                            $resultados = $cima_psuministro->resultados;
                            echo self::display_cima_psuministro_data($info, $resultados[0]);
                        else:
                            echo self::display_product_price($info, $prod_data['price'], $info->master_details);
                        endif;
                       ?>
                    </div>

                </div>    
                
                <div class="row solosoe-product-info">
                    <div class="col-12">
                        <?php echo self::display_cima_medicamento_data(json_decode($prod_data['cima_medicamento'])); ?>
                    </div>
                </div>
                        
                <?php if (!empty($cima_psuministro->resultados)): ?>
                <div class="row solosoe-product-info">
                    <div class="col-12">
                        <?php echo self::display_cima_medicamento_data_footer(json_decode($prod_data['cima_medicamento'])); ?>
                    </div>
                </div>  
                <?php endif; ?>  
            </div>         
        
        </div>
        <?php
        return ob_get_clean(); 
    }
   
        
    //  Display cima product data from  https://cima.aemps.es/cima/rest/medicamento?cn=912485
    public static function display_cima_medicamento_data($medicamento){
        $viasAdministracion = $medicamento->viasAdministracion;
        $formaFarmaceutica = $medicamento->formaFarmaceutica;
        $docs = $medicamento->docs;
        $principiosActivos = $medicamento->principiosActivos;
        $excipientes = $medicamento->excipientes;
        $atcs = $medicamento->atcs;
        if ($viasAdministracion && $formaFarmaceutica):
        ?>
        <!-- Product psuministro data from cima -->
        <div class="card solosoe-cima-medicamento-product-data">
            <div class="row px-3 py-3">
                <div class="col-md-3">
                    <span class="blue"><?php echo $formaFarmaceutica->nombre; ?></span>
                    <br>
                    <small class="solosoe-cima-label"><strong>FORMAS FARMACÉUTICAS</strong></small>
                </div>
                <div class="col-md-3">
                    <span class="blue"><?php echo $medicamento->dosis; ?></span>
                    <br>
                    <small class="solosoe-cima-label"><strong>DOSIS</strong></small>
                </div>
                <div class="col-md-3">
                    <span class="blue"><?php echo $viasAdministracion[0]->nombre; ?></span>
                    <br>
                    <small class="solosoe-cima-label"><strong>VÍAS DE ADMINISTRACIÓN</strong></small>
                </div>
                <div class="col-md-3">
					<small class="solosoe-cima-label"><strong>LABORATORIO:</strong> <span class="blue"><?php echo $medicamento->labtitular; ?></span></small>
                    <br>
                    <?php 
                        if ($docs): 
                            ?>
                            <ul class="list-group list-group-flush solosoe-laboratorio-docs-links">
                            <?php
                            foreach ($docs as $doc):
                                $pdf_logo_url = SOLOSOE_URL . '/asset/img/pdf.svg';
                                $html_logo_url = SOLOSOE_URL . '/asset/img/doc-file.svg';
                            ?>    
                                <li class="list-group-item">
                                    <div>
                                        <a href="<?= $doc->url; ?>" target="_blank"><img src="<?= $pdf_logo_url; ?>" width="40" height="40"></a> 
                                        <a href="<?= $doc->urlHtml; ?>" target="_blank"><img src="<?= $html_logo_url; ?>" width="40" height="40"></a>
                                    </div>
                                </li>
                            <?php    
                            endforeach;
                            ?>
                            </ul>
                            <?php
                        endif;
                    ?>
                </div>
            </div>
        </div>
        
        <div class="card solosoe-cima-medicamento-product-data blue">
            <div class="row px-3 py-3">
                
                <div class="col-md-4">
                    <?php if ($principiosActivos): ?>
                    <span class="solosoe-cima-label-up"><strong>PRINCIPIOS ACTIVOS</strong></span>
                    <ul>
                    <?php foreach ($principiosActivos as $activos): ?>
                        <li><?= $activos->nombre.' '.$activos->cantidad.' '.$activos->unidad; ?></li>
                    <?php endforeach; ?>
                    </ul>
                    <br>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-4">
                    <?php if ($excipientes): ?>
                    <span class="solosoe-cima-label-up"><strong>EXCIPIENTES</strong></span>
                    <ul>
                    <?php foreach ($excipientes as $excipient): ?>
                        <li><?= $excipient->nombre.' '.$excipient->cantidad.' '.$excipient->unidad; ?></li>
                    <?php endforeach; ?>
                    </ul>
                    <br>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-4">
                    <?php if ($atcs): ?>
                    <span class="solosoe-cima-label-up"><strong>CÓDIGOS ATC</strong></span>
                    <ul>
                    <?php foreach ($atcs as $atc): ?>
                        <li><?= $atc->codigo.' - '.$atc->nombre; ?></li>
                    <?php endforeach; ?>
                    </ul>
                    <br>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php
    endif; 
    }

    public static function display_cima_medicamento_data_footer($medicamento){
    ?>
        <div class="card solosoe-cima-medicamento-product-footer-data">
            <div class="row justify-content-around">
                <div class="card bg-light xs-2">
                    <div class="card-body">
                        <h5 class="card-title">
                        <span class="product-footer-properties">Comercialiazado</span>                            
                            <?php 
                                if ($medicamento->comerc): 
                                    $class='slosoe-yes'; 
                                    $value='Si'; 
                                else: 
                                    $class='slosoe-no'; 
                                    $value='No';
                                endif;
                            ?>
                            <span class="solosoe-boolean-product-properties <?=$class; ?>"><?=$value; ?></span>
                        </h5>
                    </div>
                </div>
                <div class="card bg-light xs-2">
                    <div class="card-body">
                        <h5 class="card-title">
                        <span class="product-footer-properties">Require Receta</span>                            
                            <?php 
                                if ($medicamento->receta): 
                                    $class='slosoe-yes'; 
                                    $value='Si'; 
                                else: 
                                    $class='slosoe-no'; 
                                    $value='No';
                                endif;
                            ?>
                            <span class="solosoe-boolean-product-properties <?=$class; ?>"><?=$value; ?></span>
                        </h5>
                    </div>
                </div>
                <div class="card bg-light xs-1">
                    <div class="card-body">
                        <h5 class="card-title">
                        <span class="product-footer-properties">Generico</span>                            
                            <?php 
                                if ($medicamento->generico): 
                                    $class='slosoe-yes'; 
                                    $value='Si'; 
                                else: 
                                    $class='slosoe-no'; 
                                    $value='No';
                                endif;
                            ?>
                            <span class="solosoe-boolean-product-properties <?=$class; ?>"><?=$value; ?></span>
                        </h5>
                    </div>
                </div>
                <div class="card bg-light xs-1">
                    <div class="card-body">
                        <h5 class="card-title">
                        <span class="product-footer-properties">Conduc</span>                            
                            <?php 
                                if ($medicamento->conduc): 
                                    $class='slosoe-yes'; 
                                    $value='Si'; 
                                else: 
                                    $class='slosoe-no'; 
                                    $value='No';
                                endif;
                            ?>
                            <span class="solosoe-boolean-product-properties <?=$class; ?>"><?=$value; ?></span>
                        </h5>
                    </div>
                </div>
                <div class="card bg-light xs-2">
                    <div class="card-body">
                        <h5 class="card-title">
                        <span class="product-footer-properties">Triangulo</span>                            
                            <?php 
                                if ($medicamento->triangulo): 
                                    $class='slosoe-yes'; 
                                    $value='Si'; 
                                else: 
                                    $class='slosoe-no'; 
                                    $value='No';
                                endif;
                            ?>
                            <span class="solosoe-boolean-product-properties <?=$class; ?>"><?=$value; ?></span>
                        </h5>
                    </div>
                </div>
                <div class="card bg-light xs-2">
                    <div class="card-body">
                        <h5 class="card-title">
                        <span class="product-footer-properties">Huerfano</span>
                            <?php 
                                if ($medicamento->huerfano): 
                                    $class='slosoe-yes'; 
                                    $value='Si'; 
                                else: 
                                    $class='slosoe-no'; 
                                    $value='No';
                                endif;
                            ?>
                        <span class="solosoe-boolean-product-properties <?=$class; ?>"><?=$value; ?></span>
                        </h5>
                    </div>
                </div>
                <div class="card bg-light xs-2">
                    <div class="card-body">
                        <h5 class="card-title">
                            <span class="product-footer-properties">Biosimilar</span>
                            <?php 
                                if ($medicamento->biosimilar): 
                                    $class='slosoe-yes'; 
                                    $value='Si'; 
                                else: 
                                    $class='slosoe-no'; 
                                    $value='No';
                                endif;
                            ?>
                            <span class="solosoe-boolean-product-properties <?=$class; ?>"><?=$value; ?></span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    //  Display search form
    public static function display_solr_search_form(){   
        ?> 
        <div id="solosoe-custom-templates" class="container p-3">
	        <form>
		        <div class="form-group">
			        <input id="solr-typeahead" type="search" class="search-field" placeholder="Start type product name"/>
		        </div>
	        </form>
        </div>
        <?php 
    }

    //  Display cima product data from  https://cima.aemps.es/cima/rest/psuministro/912485
    public static function display_cima_psuministro_data($product_info, $resultados){
        ?>
        <h4 class="card-title pt-3 pb-3 greenbg">
            <small class="text-muted cima"><?php echo $resultados->cn; ?></small>  
            <?php echo $resultados->nombre; ?>
        </h4>
        <div class="card-block bg-danger solosoe-psuministro-resultados px-6">
            
            <div class="card-header">    
                <div class="row">
                    <div class="col-md-3">
                        <span>
                            <?php echo date("d/m/Y", substr($resultados->fini, 0, 10)); ?>
                        </span>
                        <br>
                        <small class="solosoe-cima-label zagsm"><strong>PREVISIÓN DE INICIO</strong></small>
                    </div>
                    <div class="col-md-3">
                        <span>
                            <?php echo date("d/m/Y", substr($resultados->ffin, 0, 10)); ?>
                        </span>
                        <br>
                        <small class="solosoe-cima-label zagsm"><strong>PREVISIÓN DE FINALIZACIÓN</strong></small>
                    </div>
                    <div class="col-md-6">
                        <h3 class="solosoe-psuministro-error">Problema de Suministro</h3>
                    </div>
                </div>
            </div> 
            <p class="card-text pl-3"><?php echo $resultados->observ; ?></p>    
        </div>
   
        <?php
    }

    public static function display_product_price($product_info, $product_price, $master_details){
        if (!empty($product_price = json_decode($product_price))):
        ?>
        <div class="card-block px-6">
            <h4 class="card-title">
                <small class="text-muted price"><?php echo $product_info->ean; ?></small>  
                <?php echo $product_info->product_name; ?>
            </h4>
            <h5 class="card-subtitle mb-2">
                <small class="solosoe-pr-comercios text-muted">№ de Comercios: </small> <strong><?php echo $product_info->mst_prd_id ?></strong>
                <small class="solosoe-pr-rango-precios text-muted">Rango de precios: </small> <strong><?php echo $product_info->min_sale_price; ?> - <?php echo $product_info->max_sale_price; ?> €</strong>
                <div id="solosoe-recommended-price" class="title badge badge-warning">Precio Competitevo <span class="solosoe-optimal-price-big"><?php echo $product_price->price; ?> €</span></div>    
            </h5>
            <p class="card-text"><?php echo $product_info->product_description; ?></p>
        </div>
        <div class="row card-block px-6">
            <?php 
            if (!empty($master_details)): 
                echo self::display_shops($master_details); 
            else:  
                ?>
                <div class="alert alert-light" role="alert">
                    <h4 class="alert-heading">No se ha encontrado en Internet!</h4>
                </div>
                <?php
            endif; 
            ?>
        </div>       
    <?php
        endif;
    }
    
    //  Help function for display avalible shops
    public static function display_shops($master_details){
        //ob_start();
        foreach ($master_details as $details): ?>
            <div class="card-link">
                <h5>
                    <span class="badge badge-success">
                        <?php echo $details->no_of_shops;?>
                    </span> 
                    <?php echo $details->marketplace;?>
                </h5>
                <span><?php echo $details->min_price . ' - ' . $details->max_price;?>€</span>
            </div>
        <?php 
        endforeach;
        //return ob_get_clean(); 
    }

    //  Display carousel products images
    public static function display_products_imgs($images){
        if (!empty($images)):
    ?>
            <div id="CarouselSolosoe" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php foreach ($images as $key=>$value): ?>
                        <li data-target="#CarouselSolosoe" data-slide-to="<?= $key; ?>" <?php if ($key == 0): ?> class="active" <?php endif;?>></li>
                    <?php endforeach; ?>
                </ol>  
                
                <div class="carousel-inner">
                    <?php foreach ($images as $key=>$value): ?>
                        <div class="carousel-item<?php if ($key == 0): ?> active<?php endif;?>">
                            <img class="d-block" src="<?=$value; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                            
                <a class="carousel-control-prev" href="#CarouselSolosoe" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#CarouselSolosoe" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            
            </div>
        
    <?php
        endif;
    }

}

SOLOSOE_DISPLAY_PRODUCT::init();