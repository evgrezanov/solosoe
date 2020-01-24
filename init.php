<?php
/**
 * Plugin Name: Farma Base MVP
 * Plugin URI:  <a href="https://www.upwork.com/freelancers/~01ea58721977099d53">Evgeniy Rezanov</a>
 * Description: Custom plugin
 * Author:      Evgeniy Rezanov
 * Author URI:  https://www.upwork.com/freelancers/~01ea58721977099d53
 * Version:     1.1.0
 *
 * @copyright  Copyright (c) 2019
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'PRM_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRM_FILE', __FILE__ );
define( 'PRM_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main class
 *
 * @since 1.0.0
 * @package PRM_BASE
 */
class PRM_BASE {

    /**
    * The init
    */
    public static function init(){
        add_action( 'wp', [__CLASS__, 'members_only'] );

        require_once('inc/class-options.php');
        require_once('inc/class-solr-request.php');
        
        //require_once('inc/class-custom-metafields.php');
    }

    // Redirect users who arent logged in...
    public static function members_only() {
        global $pagenow;
        // Check to see if user in not logged in and not on the login page
        if( !is_user_logged_in() && $pagenow != 'wp-login.php' )
          auth_redirect();
    }
}

PRM_BASE::init();