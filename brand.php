<?php
/*
 * Plugin Name: Brand for woocommerce
 * Plugin URI: https://github.com/hannannexus
 * Description:  Add Extra Meta Data in woocommerce shop and single product page. 
 * Author: Hannan
 * Author URI: https://github.com/hannannexus/
 * License: License: GPLv2 or later
 * Version: 1.0
 * Text Domain: brand
 */

 
 /* add new field under product tab*/
function brand_add_field( $tab ){
    
     $tab['brand'] = [
        'label' => __('Brand','brand'),
        'target' => 'brand_option',
        'class' => ['hide_if_external'],
        'priority' => 25
    ];

    return $tab;
    
}
 add_action('woocommerce_product_data_tabs','brand_add_field');

 /* Add new field under brand tab*/
function brand_option_panel(){
    ?>
        <div id="brand_option" class="panel woocommerce_options_panel hidden">
            <?php
             woocommerce_wp_text_input(array(
               'id'=>'_brand',
               'label'=> __('Add Brand','brand'), 
               'wrapper_class'=> ['show_if_simple','show_if_variable'], 
             ));
 
            ?>
        </div>
    <?php

}
 add_action('woocommerce_product_data_panels','brand_option_panel');

 /* process brand data as meta*/
function brand_process( $post_id ){

    $product = wc_get_product($post_id);
    $brand = isset($_POST['_brand']) ? isset($_POST['_brand']): '';
    // update meta data
    $product->update_meta_data('_brand',sanitize_text_field($brand));

    $product->save();
}
add_action('woocommerce_process_product_meta','brand_process');

/* display  meta data in single product */
function brand_display_data(){
    global $post;
    $product = wc_get_product($post->ID);
    $brand_data =  $product->get_meta('_brand');
    
    if( !empty( $brand_data)){
        printf('<div class="brand"> %s: %s</div>',__('Brand','brand'), $brand_data);
    }
}
add_action('woocommerce_product_meta_start','brand_display_data');
add_action('woocommerce_after_shop_loop_item_title','brand_display_data');
