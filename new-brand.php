<?php
/*
 * Plugin Name: New Brand for WooCommerce 
 * Plugin URI: https://github.com/hannannexus
 * Description:  Add Extra Meta Data in WooCommerce shop and single product page. 
 * Author: Hannan
 * Author URI: https://github.com/hannannexus/
 * License: License: GPLv2 or later
 * Version: 1.0.0
 * Text Domain: new_brand
 */


 if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

 /* add new field under product tab*/
if( !function_exists('new_brand_add_field')){
    function new_brand_add_field( $tab ){
		
        $tab['brand'] = [
           'label' => __('Brand','new_brand'),
           'target' => 'brand_option',
           'class' => ['hide_if_external'],
           'priority' => 25
       ];
   
       return $tab;
       
   }
}
 add_action('woocommerce_product_data_tabs','new_brand_add_field');

 /* Add new field under brand tab*/
if( !function_exists('new_brand_option_panel')){
    function new_brand_option_panel(){
        ?>
            <div id="brand_option" class="panel woocommerce_options_panel hidden">
                <?php
                 woocommerce_wp_text_input(array(
                   'id'=>'_brand',
                   'label'=> __('Add Brand','new_brand'), 
                   'wrapper_class'=> ['show_if_simple','show_if_variable'], 
                 ));
     
                ?>
            </div>
        <?php
    
    }
}
 add_action('woocommerce_product_data_panels','new_brand_option_panel');

 /* process brand data as meta*/
if( !function_exists('new_brand_save_data')){
    function new_brand_save_data( $post_id ){

        $product = wc_get_product($post_id);
        $brand = isset($_POST['_brand']) ? isset($_POST['_brand']) : '';
        // update meta data
        $product->update_meta_data('_brand',sanitize_text_field($brand));
    
        $product->save();
    }
}
add_action('woocommerce_process_product_meta','new_brand_save_data');

/* display  meta data in single product */
if( !function_exists('new_brand_display_data')){
    function new_brand_display_data(){
        global $post;
        $product = wc_get_product($post->ID);
        $brand_data =  $product->get_meta('_brand');
        
        if( !empty( $brand_data)){
            printf('<div class="brand"> %s: %s</div>',__('Brand','new_brand'), $brand_data);
        }
    }
}
add_action('woocommerce_product_meta_start','new_brand_display_data');
add_action('woocommerce_after_shop_loop_item_title','new_brand_display_data');
