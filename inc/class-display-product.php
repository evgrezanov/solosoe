<?php
use SOLOSOE\Search;

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
        
        add_shortcode('display_product_card', [__CLASS__, 'render_product_card']);
        
        add_action('display_shops', [__CLASS__, 'display_shops'], 1, 1);
        
        add_action('main_product_data', [__CLASS__, 'display_main_product_data'], 1, 3);
        add_action('cima_medicamento', [__CLASS__, 'display_cima_medicamento'], 1, 1);
        add_action('cima_psuministro', [__CLASS__, 'display_cima_medicamento'], 1, 1);
        add_action('no_result', [__CLASS__, 'display_no_result'], 1, 1);

    }


    //  Shortcode for display product
    public static function render_product_card(){
        ob_start();
        ?>
        <div id="solosoe-custom-templates" class="container py-3">
            <!-- Search form-->
            <?php echo self::display_solr_search_form(); ?>
            
            <?php
                if ( !empty($_REQUEST['prd_id']) ):

                    // get current product id 
                    $product_id = $_REQUEST['prd_id'];
                    
                    //todo change - store urls at DB as plugin options
                    $prd_info_url = 'http://34.243.79.103:8000/services/product/'.$product_id.'/?format=json';
                    $prd_price_url = 'http://34.243.79.103:8000/services/optimal-price/'.$product_id.'/?format=json';

                    // get product data
                    if ( !empty($prd_price_url) && !empty($prd_info_url) ):
                        
                        // for that https://www.php.net/manual/en/migration56.openssl.php
                        $arrContextOptions = array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        ); 

                        // get properties
                        $info = file_get_contents($prd_info_url, false, stream_context_create($arrContextOptions));
                        
                        // get price
                        //var_dump($prd_price_url);
                        $price = file_get_contents($prd_price_url, false, stream_context_create($arrContextOptions));
                        
                        // decode
                        $product_info = json_decode($info);
                        $product_price = json_decode($price);
                        
                        echo self::display_main_product_data($product_id, $product_info, $product_price);
                        
                        // check - have cima info?
                        if (isset($product_info->cn_dot_7)):
                            $id_start = (int)substr($product_info->cn_dot_7, 0, 1);
                            
                            // if id_start >= 6 cima have info
                            if ($id_start >= 6):
                                
                                $cn_dot_7 = explode(".", $product_info->cn_dot_7);
                                $cima_id = $cn_dot_7[0];
                                
                            // todo delete later    
                            else:

                                // JUST FOR TEST
                                $cima_id = '912485';

                            endif;

                            // request data from cima
                            //var_dump('https://cima.aemps.es/cima/rest/medicamento?cn='.$cima_id);


                            $cima_medicamento_json = file_get_contents('https://cima.aemps.es/cima/rest/medicamento?cn='.$cima_id, false, stream_context_create($arrContextOptions));
                            //var_dump($cima_medicamento_json);
                            
                            $cima_psuministro = file_get_contents('https://cima.aemps.es/cima/rest/psuministro/'.$cima_id, false, stream_context_create($arrContextOptions));
                            //var_dump($cima_psuministro);
                            // display cima data
                            echo self::display_cima_psuministro_data(json_decode($cima_psuministro));
                            echo self::display_cima_medicamento_data(json_decode($cima_medicamento));

                        endif;

                    endif;

                endif;
            ?>
        </div>
        <?php
        return ob_get_clean(); 
    }
   
    //  Display carousel products images
    public static function display_products_imgs($images){
        $img_count = count($images);
        ob_start();
        ?>
        <div class="col-md-5">
            <div id="CarouselSolosoe" class="carousel slide" data-ride="carousel">
                <?php 
                   // if ($img_count > 1): 
                ?>
                        <ol class="carousel-indicators">
                            <?php
                            foreach ($images as $key=>$value): ?>
                                <li data-target="#CarouselSolosoe" data-slide-to="<?= $key; ?>"></li>
                            <?php    
                            endforeach;
                            ?>
                        </ol>  
                        <div class="carousel-inner">
                            <?php      
                            foreach ($images as $key=>$value): ?>
                                <div class="carousel-item active">
                                    <img class="d-block" src="<?=$value; ?>">
                                </div>
                            <?php    
                            endforeach;
                            ?>
                        </div>
                        
                        <a class="carousel-control-prev" href="#CarouselSolosoe" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#CarouselSolosoe" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                <?php
                    //endif; 
                ?>    
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    //  Display main product data from http://34.243.79.103:8000/services/product/{{144615}}/
    //  and optimal product price from http://34.243.79.103:8000/services/optimal-price/{144615}/
    public static function display_main_product_data($product_id, $product_info, $product_price){
        $master_details = $product_info->master_details;
        $min_sale_price = $product_info->min_sale_price;
        $max_sale_price = $product_info->max_sale_price;
        $price_average = $min_sale_price .'€ - '.$max_sale_price.' €';
        $images = $product_info->images;
        ob_start();
        ?>
        <!-- Card Start -->
        <div class="card solosoe-main-product-data">
            <div class="row">
                <!-- Carousel -->
                <?php echo self::display_products_imgs($images); ?>
                <!-- Product info -->
                <div class="col-md-7 px-3">
                    <div class="card-block px-6">
                        <h4 class="card-title"><small class="text-muted"><?php echo $product_info->ean; ?></small>  <?php echo $product_info->product_name; ?></h4>
                        <h5 class="card-subtitle mb-2">
                            <small class="solosoe-pr-comercios text-muted">№ de Comercios: </small> <strong><?php echo $product_id ?></strong>
                            <small class="solosoe-pr-rango-precios text-muted">Rango de precios: </small> <strong><?php echo $product_info->min_sale_price; ?> - <?php echo $product_info->max_sale_price; ?> €</strong>
                        </h5>
                        <div id="solosoe-recommended-price" class="h1 title badge badge-warning mb-2">Precio Competitevo <?php echo $product_price->price; ?> €</div>
                        <p class="card-text"><?php echo $product_info->product_description; ?></p>    
                    </div>
                    <div class="row card-block px-6">
                        <?php if (!empty($master_details)): self::display_shops($master_details); endif; ?>
                    </div>
                </div>
            </div>    
        </div>
        <?php
        return ob_get_clean();
    }

    public static function display_cima_medicamento($medicamento){
        // for example
        // $cima_info_url = https://cima.aemps.es/cima/rest/medicamento?cn=912485
        // get data
        $viasAdministracion = $medicamento->viasAdministracion;
        $principiosActivos = $medicamento->principiosActivos;
        ob_start();
        ?>
        <!-- Product data from cima -->
        <div class="card solosoe-cima-product-data">
            <div class="row">
                <div class="col-md-3">
                    <span><?= $medicamento->labtitular ?></span>
                    <br>
                    <small class="solosoe-cima-label">LABORATORIO</small>
                </div>
                <div class="col-md-3">
                    <span>COMPRIMIDO EFERVESCENTE</span>
                    <br>
                    <small class="solosoe-cima-label">FORMAS FARMACÉUTICAS</small>
                </div>
                <div class="col-md-3">
                    <span><?= $medicamento->dosis ?></span>
                    <br>
                    <small class="solosoe-cima-label">DOSIS</small>
                </div>
                <div class="col-md-3">
                    <?php 
                        foreach ($viasAdministracion as $vias):
                            foreach ($vias as $key=>$value):
                                ?>
                                    <span> <?= $value ?> </span>
                                <?php
                            endforeach;
                        endforeach;
                    ?>
                    <br>
                    <small class="solosoe-cima-label">VÍAS DE ADMINISTRACIÓN</small>
                </div>
            </div>
        </div>
        
        <ul class="cima-medicamento list-group">
            <li class="list-group-item active">Medicamento</li>
            <li class="list-group-item"><?php echo 'nregistro: '.$medicamento->nregistro; ?></li>
            <li class="list-group-item"><?php echo 'pactivos: '.$medicamento->pactivos; ?></li>
            <li class="list-group-item"><?php echo 'labtitular: '.$medicamento->labtitular; ?></li>
            <li class="list-group-item"><?php echo 'cpresc: '.$medicamento->cpresc; ?></li>
            <li class="list-group-item"><?php echo 'dosis: '.$medicamento->dosis; ?></li>
        <!--</ul>-->
        <?php
        
        // show docs
        $docs = $medicamento->docs;
        foreach ($docs as $doc):
            foreach ($doc as $key=>$value):
                ?>
                    <li class="list-group-item"><?php echo $key.' : '.$value; ?></li>
                <?php
            endforeach;
        endforeach;
        
        // show atcs
        $atcs = $medicamento->atcs;
        foreach ($atcs as $act):
            foreach ($act as $key=>$value):
                ?>
                    <li class="list-group-item"><?php echo $key.' : '.$value; ?></li>
                <?php
            endforeach;
        endforeach;
        ?>
        </ul>
        <?php
        return ob_get_clean();
        /*
        
        
        $cima_info->atcs.codigo.nombre.nivel
        $atcs->codigo
        $atcs->nombre
        $atcs->nivel
        $cima_info->principiosActivos.id.codigo.nombre.cantidad.unidad.orden
        $principiosActivos->id
        $principiosActivos->codigo
        $principiosActivos->nombre
        $principiosActivos->cantidad
        $principiosActivos->unidad
        $principiosActivos->orden
        $cima_info->excipientes.id.nombre.cantidad.unidad.orden
        $cima_info->viasAdministracion.id.nombre
        $cima_info->presentaciones.cn.nombre.comerc.psum
        $cima_info->presentaciones.estado.aut
        $cima_info->formaFarmaceutica.id.nombre
        $cima_info->formaFarmaceuticaSimplificada.id.nombre
        $cima_info->vtm.id.nombre
        $cima_info->dosis
*/
    }
    
    //  Display cima product data from  https://cima.aemps.es/cima/rest/psuministro/912485
    public static function display_cima_psuministro_data($psuministro){
        $resultados = $psuministro->resultados;
        $resultados = $resultados[0];
        if (!empty($resultados)):
            ?>
            <!-- Product psuministro data from cima -->
            <div class="card solosoe-cima-psuministro-product-data">
                <div class="row">
                    <div class="col-md-3">
                        <span><?= 'labtitular' ?></span>
                        <br>
                        <small class="solosoe-cima-label"><strong>LABORATORIO</strong></small>
                    </div>
                    <div class="col-md-3">
                        <span>COMPRIMIDO EFERVESCENTE</span>
                        <br>
                        <small class="solosoe-cima-label"><strong>FORMAS FARMACÉUTICAS</strong></small>
                    </div>
                    <div class="col-md-3">
                        <span>400/240 MG/MG</span>
                        <br>
                        <small class="solosoe-cima-label"><strong>DOSIS</strong></small>
                    </div>
                    <div class="col-md-3">
                        <span>VÍA ORAL</span>
                        <br>
                        <small class="solosoe-cima-label"><strong>VÍAS DE ADMINISTRACIÓN</strong></small>
                    </div>
                </div>
            </div>
            <ul class="cima-psuministro list-group">
                <li class="list-group-item active">Psuministro</li>
            <?php
            foreach ($resultados as $key=>$value):
                ob_start();
                ?>
                <li class="list-group-item"><?php echo $key.': '.$value; ?></li>
                <?php
            endforeach;
            ?>
            </ul>
            <?php
            return ob_get_clean();
        else:
            return false;
        endif;
    }
    
    //  Display cima product data from  https://cima.aemps.es/cima/rest/medicamento?cn=912485
    public static function display_cima_medicamento_data($medicamento){
        $viasAdministracion = $medicamento->viasAdministracion;
        $principiosActivos = $medicamento->principiosActivos;
        ob_start();
        ?>
        <div class="card solosoe-cima-product-data">
            <div class="row">
                <div class="col-md-6">
                    <span class="solosoe-cima-label-up"><strong>PRINCIPIOS ACTIVOS</strong></span>
                    <ul>
                        <li>ACETILSALICILICO</li>
                    </ul>
                    <br>
                    
                    <span class="solosoe-cima-label-up"><strong>Otras presentaicones</strong></span>
                    <ul>
                        <li>N02B - OTROS ANALGÉSICOS Y ANTIPIRÉTICOS</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    
                    <span class="solosoe-cima-label-up"><strong>CÓDIGOS ATC </strong></span>
                    <ul>
                        <li>N02B - OTROS ANALGÉSICOS Y ANTIPIRÉTICOS</li>
                    </ul>
                    <br>
            
                    <span class="solosoe-cima-label-up"><strong>EXCIPIENTES </strong></span>
                    <ul>
                        <li>HIDROGENO</li>
                    </ul>
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
			        <input id="solr-typeahead" type="search" style="width:500px;" class="search-field" placeholder="Start type product name"/>
		        </div>
	        </form>
        </div>
        <?php 
    }

    //  Display no-results message
    public static function display_no_result($http_response_header){
        ?>
        <div class="alert alert-danger" role="alert">
            <strong>We are so sorry, something wrong!</strong> <br>
            <?php print_r(self::parseHeaders($http_response_header)); ?>
        </div>
        <?php
    }

    //  Help function for display avalible shops
    public static function display_shops($master_details){
        ob_start();
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
        return ob_get_clean(); 
    }

    //  Parse response header
    public static function parseHeaders($headers){
        $head = array();
        foreach( $headers as $k=>$v ){
            $t = explode( ':', $v, 2 );
            if( isset( $t[1] ) )
                $head[ trim($t[0]) ] = trim( $t[1] );
            else
            {
                $head[] = $v;
                if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
                    $head['reponse_code'] = intval($out[1]);
            }
        }
        return $head;
    }

}

SOLOSOE_DISPLAY_PRODUCT::init();