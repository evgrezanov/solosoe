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
            
            <!-- Search form -->
            <div id="solosoe-search-form">
                <div class="row">
                    <div id="solosoe-custom-templates" class="col-md-12">
                        <form>
                            <div class="form-group">
                                <input id="solr-typeahead" type="search" style="width:100%;" class="title h1 search-field" placeholder="Start type product name or code..."/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Card Start -->
            <!-- Carousel start -->
            <div class="card solosoe-main-product-data">
                <div class="row">
                    <!-- Carousel -->
                    <div class="col-md-5">
                        <div id="CarouselTest" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#CarouselTest" data-slide-to="0" class="active"></li>
                            <li data-target="#CarouselTest" data-slide-to="1"></li>
                            <li data-target="#CarouselTest" data-slide-to="2"></li>
                            <li data-target="#CarouselTest" data-slide-to="3"></li>
                            <li data-target="#CarouselTest" data-slide-to="4"></li>
                            <li data-target="#CarouselTest" data-slide-to="5"></li>
                            <li data-target="#CarouselTest" data-slide-to="6"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=15767675.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=vitae-kyodophilus-one-per-day-30-caps.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=kyo-dophilus-one-per-day-probiotico-30-capsulas.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=166717_5.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=kyo-dophilus-one-per-day-30-capsulas.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=leukomed-8x10cm-5-unidades.df9cee.jpg" alt="">
                            </div>   
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=14579342.jpg" alt="">
                            </div>
                            <a class="carousel-control-prev" href="#CarouselTest" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#CarouselTest" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                    
            <!-- Product info -->
            <div class="col-md-7 px-3">

                <div class="card-block px-6">
                    <h4 class="card-title"><small class="text-muted">8470000714641</small>  KYODOPHILUS ONE PER DAY 30 CAPS</h4>
                    <h5 class="card-subtitle mb-2">
                        <small class="solosoe-pr-comercios text-muted">№ de Comercios: </small> <strong>71217</strong>
                        <small class="solosoe-pr-rango-precios text-muted">Rango de precios: </small> <strong>8.20 - 8.20 €</strong>
                    </h5>
                    <div id="solosoe-recommended-price" class="h1 title badge badge-warning mb-2">Precio Competitevo 9.84 €</div>
                    <p class="card-text">Con malvavisco, sahúco, tomillo, malva y espino amarillo, ayuda a aliviar los síntomas del resfriado. Indicado para adultos y</p>    
                </div>

                <div class="row card-block px-6">
                    <div class="card-link">
                        <h5><span class="badge badge-success">23</span> PROMOFARMA</h5><span>16.65€ - 25.57€</span>
                    </div>
                    <div class="card-link">
                        <h5><span class="badge badge-success">12</span> WEBS</h5><span>5.03€ - 18.95€</span>
                    </div>
                </div>
            </div>
                
            <!-- Product data from cima -->
            <div class="card solosoe-cima-product-data">
                <div class="row">
                <div class="col-md-3">
                    <span>BAYER HISPANIA, S.L.</span>
                    <br>
                    <small class="solosoe-cima-label">LABORATORIO</small>
                </div>
                <div class="col-md-3">
                    <span>COMPRIMIDO EFERVESCENTE</span>
                    <br>
                    <small class="solosoe-cima-label">FORMAS FARMACÉUTICAS</small>
                </div>
                <div class="col-md-3">
                    <span>400/240 MG/MG</span>
                    <br>
                    <small class="solosoe-cima-label">DOSIS</small>
                </div>
                <div class="col-md-3">
                    <span>VÍA ORAL</span>
                    <br>
                    <small class="solosoe-cima-label">VÍAS DE ADMINISTRACIÓN</small>
                </div>
                </div>
            </div>
                
            <!-- Product data from cima 2d -->
            <div class="card solosoe-cima-product-data">
                <div class="row">
                    <div class="col-md-6">
                        <span class="solosoe-cima-label-up">PRINCIPIOS ACTIVOS</span>
                        <small>ACETILSALICILICO ACIDO ASCORBICO ACIDO</small>
                        <br>
                        <span class="solosoe-cima-label-up">Otras presentaicones</span>
                        <small>ASPIRINA C 400 mg/240 mg COMPRIMIDOS EFERVESCENTES , 10 comprimidos CN: 712729</small>
                    </div>
                    <div class="col-md-6">
                        <span class="solosoe-cima-label-up">CÓDIGOS ATC </span>
                        <small>N02B - OTROS ANALGÉSICOS Y ANTIPIRÉTICOS N02BA - ÁCIDO SALICÍLICO Y DERIVADOS N02BA51 - ÁCIDO ACETILSALICÍLICO, COMBINACIONES EXCLUYENDO PSICOLÉPTICOS</small>
                        <br>
                        <span class="solosoe-cima-label-up">EXCIPIENTES </span>
                        <small>HIDROGENO CARBONATO DE SODIO CARBONATO DE SODIO ANHIDRO CITRATO DE SODIO (E-331)</small>
                    </div>
                </div>
            </div>

        </div>

        <?php
        if ( !empty($_REQUEST['prd_id']) ):

            // get current product id 
            $product_id = $_REQUEST['prd_id'];
            
            $prd_info_url = 'http://34.243.79.103:8000/services/product/'.$product_id.'/?format=json';
            $prd_price_url = 'http://34.243.79.103:8000/services/optimal-price/'.$product_id.'/?format=json';

            // get product data
            if ( !empty($prd_price_url) && !empty($prd_info_url) ):
                // get properties
                $info = file_get_contents($prd_info_url);
                // get price
                $price = file_get_contents($prd_price_url);
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
                        
                        // request data from cima
                        $cima_medicamento = json_decode(file_get_contents('https://cima.aemps.es/cima/rest/medicamento?cn='.$cima_id));
                        $cima_psuministro = json_decode(file_get_contents('https://cima.aemps.es/cima/rest/psuministro/'.$cima_id));
                        
                        // display data
                        if ( $psuministro = self::display_cima_psuministro($cima_psuministro) ):
                            echo $psuministro;
                        endif;
                        if ( $medicamento = self::display_cima_medicamento($cima_psuministro) ):
                            echo $medicamento;
                        endif;

                    endif;
                endif;

            endif;

        endif;
        
        return ob_get_clean(); 
    }
   

    //  Display main product data from http://34.243.79.103:8000/services/product/{{144615}}/
    //  and http://34.243.79.103:8000/services/optimal-price/{144615}/
    public static function display_main_product_data($product_id, $product_info, $product_price){
        $master_details = $product_info->master_details;
        ob_start();
        ?>
        <!-- Card Start -->
        <div class="card solosoe-main-product-data">
            <div class="row">
                
                <!-- Carousel -->
                <div class="col-md-5">
                    <div id="CarouselTest" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#CarouselTest" data-slide-to="0" class="active"></li>
                            <li data-target="#CarouselTest" data-slide-to="1"></li>
                            <li data-target="#CarouselTest" data-slide-to="2"></li>
                            <li data-target="#CarouselTest" data-slide-to="3"></li>
                            <li data-target="#CarouselTest" data-slide-to="4"></li>
                            <li data-target="#CarouselTest" data-slide-to="5"></li>
                            <li data-target="#CarouselTest" data-slide-to="6"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=15767675.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=vitae-kyodophilus-one-per-day-30-caps.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=kyo-dophilus-one-per-day-probiotico-30-capsulas.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=166717_5.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=kyo-dophilus-one-per-day-30-capsulas.jpg" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=leukomed-8x10cm-5-unidades.df9cee.jpg" alt="">
                            </div>   
                            <div class="carousel-item">
                                <img class="d-block" src="http://34.243.79.103:8000/image/?key=14579342.jpg" alt="">
                            </div>
                            <a class="carousel-control-prev" href="#CarouselTest" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#CarouselTest" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
                    
                <!-- Product info -->
                <div class="col-md-7 px-3">
                    <div class="card-block px-6">
                        <h4 class="card-title"><small class="text-muted"><?php echo $product_info->ean; ?></small>  <?php echo $product_info->product_name; ?></h4>
                        <h5 class="card-subtitle mb-2">
                            <small class="solosoe-pr-comercios text-muted">№ de Comercios: </small> <strong><?= $product_id ?></strong>
                            <small class="solosoe-pr-rango-precios text-muted">Rango de precios: </small> <strong><?= $product_price->min_sale_price ?> - <?= $product_price->max_sale_price ?> €</strong>
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
    public static function display_cima_psuministro($psuministro){
        $resultados = $psuministro->resultados;
        $resultados = $resultados[0];
        if (!empty($resultados)):
            ?>
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