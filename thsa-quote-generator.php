<?php
 /** 
 * 
 * Plugin Name: THSA Quotation Generator for WooCommerce
 * Plugin URI: https://thsaapps.com/subscription-switcher/
 * Description: A plugin that will allow you to create quotations with advanced computation features. 
 * Author:      THSAapps
 * Plugin Type: Extension
 * Author URI: https://thsaapps.com
 * Version: 1.2.3
 * Text Domain: thsa-quote-generator
 * 
 * 
*/



defined( 'ABSPATH' ) or die( 'No access area' );
define('THSA_QG_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('THSA_QG_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('THSA_QG_FOLDER','thsa-quotation-generator-for-woocommerce');
define('THSA_QG_PREFIX','thsa_qg');

/**
 * 
 * 
 * Load text domain from languages
 * @since 1.2.0
 * 
 * 
 */
add_action( 'init', 'thsa_qg_load_textdomain' );
function thsa_qg_load_textdomain() {
	load_plugin_textdomain( 'thsa-quote-generator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/**
 * 
 * 
 * Plugin Installation
 * @since 1.2.0
 * 
 * 
 */
function thsa_qg_install(){
	if(class_exists('WooCommerce'))
		return;

	echo '<strong>'.__('This plugin requires woocommerce installation', 'thsa-quote-generator').'</strong>';
    @trigger_error(__('Woocommerce plugin is missing', 'thsa-quote-generator'), E_USER_ERROR);
}
register_activation_hook( __FILE__, 'thsa_qg_install' );

//autoload classes
/**
 * 
 * @since 1.2.0
 * restructure folder added public, common and public
 * 
 */
spl_autoload_register(function ($class) {
	if(strpos($class,THSA_QG_PREFIX) !== false){
		$class = preg_replace('/\\\\/', '{'.THSA_QG_PREFIX.'}', $class);
        $fullclass = $class;
		$class = explode('{'.THSA_QG_PREFIX.'}', $class);
		if(!empty(end($class))){
			$filename = str_replace("_", "-", end($class));
          
            if(strpos($fullclass,'admin') !== false){
                $folder = 'admin/includes/';
            }elseif(strpos($fullclass,'public') !== false){
                $folder = 'public/includes/';
            }else{
                $folder = 'common/';
            }
			include $folder.$filename.'.php';
		}
	}
  
});

/**
 * 
 * 
 * init
 * @since 1.2.0
 * 
 * 
 */
add_action('plugins_loaded','thsa_qg_init');
function thsa_qg_init(){
    if(is_admin()){
        //load admin
        new thsa\qg\admin\thsa_qg_admin_class();
    }
    //load public
    new thsa\qg\front\thsa_qg_public_class();
}
?>