<?php
/*
Plugin Name: StartUp Vendor Search
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-vendor-search
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

    // Shortcode to put all vendors in a list
    function startup_vendor_search_shortcode( $atts ) {
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && in_array( 'woocommerce-product-vendors/woocommerce-product-vendors.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            wp_enqueue_script( 'startup-vendor-search-select2', plugins_url() . '/startup-vendor-search/lib/select2/select2.min.js', array( ), '', false );
            wp_enqueue_style( 'startup-vendor-search-select2', plugins_url() . '/startup-vendor-search/lib/select2/select2.min.css' );
            $vendors = '';
            $terms = get_terms( 'wcpv_product_vendors' );
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term, 'wcpv_product_vendors' );
                if ( is_wp_error( $term_link ) )
                    continue;
                    $vendors .= '<option value="' . $term_link . '">' . $term->name . '</option>';
            }
            return '<select id="product_vendor_list" class="select2" style="width: 100%">
            <option value="">Recherche par nom</option>
            ' . $vendors . '
            </select>
            <script>
                jQuery(document).ready(function() {
                  jQuery("#product_vendor_list").select2({
                      placeholder: "Recherche par nom",
                      allowClear: false
                    });
                });

                // On fonce sur la page à la selection
                jQuery(function(){
                    // bind change event to select
                    jQuery("#product_vendor_list").on("change", function () {
                        var url = jQuery(this).val(); // get selected value
                        if (url) { // require a URL
                            window.location = url; // redirect
                        }
                        return false;
                    });
                });
            </script>';
        } else {
            return  __( 'WooCommerce and WooCommerce Product Vendors plugins must be activated to use Startup Vendor Search', 'startup-vendor-search' );
        }
    }

    add_shortcode( 'list_vendors', 'startup_vendor_search_shortcode' );