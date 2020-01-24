<?php
/**
 * Plugin Name: SOLOSOE
 * Plugin URI:  <a href="https://www.upwork.com/freelancers/~01ea58721977099d53">Evgeniy Rezanov</a>
 * Description: Custom plugin
 * Author:      Evgeniy Rezanov
 * Author URI:  https://www.upwork.com/freelancers/~01ea58721977099d53
 * Version:     1.1.0
 *
 * @copyright  Copyright (c) 2019
 */

// Exit if accessed directly
//https://codepen.io/redmonkey73/pen/rNaNKGB
//https://www.pronamic.nl/2018/10/twitter-typeahead-js-en-de-wordpress-rest-api/
//https://www.kiesuwcursus.nl/
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SOLOSOE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SOLOSOE_FILE', __FILE__ );
define( 'SOLOSOE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main class
 *
 * @since 1.0.0
 * @package SOLOSOE
 */
class SOLOSOE {

    /**
    * The init
    */
    public static function init(){
        add_action( 'wp', [__CLASS__, 'members_only'] );
        require_once('inc/class-options.php');
        require_once('inc/class-solr-request.php');
    }

    // Redirect users who arent logged in...
    public static function members_only() {
        global $pagenow;
        // Check to see if user in not logged in and not on the login page
        if( !is_user_logged_in() && $pagenow != 'wp-login.php' )
          auth_redirect();
    }
}

SOLOSOE::init();