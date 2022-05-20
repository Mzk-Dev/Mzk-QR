<?php
/**
 * Plugin Name: Mzk QR 
 * Description: Mzk QR code is generate QR code for post,page,product and custom post URL.
 * Plugin URI:  
 * Author URI:  https://github.com/Mzk-Dev
 * Author:      Max Cherenov
 * Version:     1.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;




if ( ! class_exists( 'MQR' ) ) {

  class MQR {

    public function __construct() {
			// add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'plugins_loaded', array( $this, 'constants' ));
      add_action( 'plugins_loaded', array( $this, 'includes' ) );
      add_action( 'plugins_loaded', array( $this, 'activator' ) );
			// add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'easy_slider_plugin_action_links' );
    }

    		/**
		 * Constants
		 *
		 * @since 1.0
		*/
		public function constants() {
			if ( !defined( 'QCODE_FIELDS_DIR' ) )
				define( 'QCODE_FIELDS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			if ( !defined( 'QCODE_FIELDS_URL' ) )
			    define( 'QCODE_FIELDS_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

			if ( ! defined( 'QCODE_FIELDS_VERSION' ) )
			    define( 'QCODE_FIELDS_VERSION', '1.0' );

			if ( ! defined( 'QCODE_FIELDS_INCLUDES' ) )
			    define( 'QCODE_FIELDS_INCLUDES', QCODE_FIELDS_DIR . trailingslashit( 'includes' ) );
		}

     /**
		* Loads the initial files needed by the plugin.
		*
		* @since 1.0
		*/
		public function includes() {

    require_once( QCODE_FIELDS_INCLUDES . 'qr.php' );
    require_once( QCODE_FIELDS_INCLUDES . 'options.php' );
    require_once( QCODE_FIELDS_INCLUDES . 'metaboxes.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'input.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'textarea.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'image.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'wysiwyg.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'color.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'video.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'slider.php' );
	  // require_once( CUSTOM_FIELDS_INCLUDES . 'helpers.php' );
		
    }

    public function activator(){
      register_activation_hook( __FILE__, array($this, 'mzk_qr_fields_activate') );
      register_deactivation_hook( __FILE__, array($this, 'mzk_qr_fields_deactivate') );
    }


    public function mzk_qr_fields_activate() {
    
    }


    public function mzk_qr_fields_deactivate() {
      // require_once(__DIR__ . '/includes/DB.php');

      // global $wpdb;
      
      
    }
  }

  $mzk_qr_fields = new MQR();

  register_activation_hook( __FILE__, array($mzk_qr_fields, 'mzk_qr_fields_activate') );
  register_deactivation_hook( __FILE__, array($mzk_qr_fields, 'mzk_qr_fields_deactivate') );
};

function mzk_qr_admin_styles() {
	wp_enqueue_style( 'mzk-qr-style', plugins_url( 'mzk-qr/css/mzk-qr.css', dirname(__FILE__) ) );

	wp_deregister_script('qr_lib');
  wp_enqueue_script('qr_lib', plugins_url( 'mzk-qr/includes/libs/EasyQRCodeJS/dist/easy.qrcode.min.js', dirname(__FILE__) ), [] , FVER);

  wp_deregister_script('html-screenshot');
  wp_enqueue_script('html-screenshot', plugins_url( 'mzk-qr/includes/libs/html2canvas/html2canvas.js', dirname(__FILE__) ), [] , FVER);
}
add_action('admin_enqueue_scripts', 'mzk_qr_admin_styles');