<?php
/**
 * Plugin Name: Bowe Coupon URL
 * Description: Apply a WooCommerce coupon via URL.
 * Version: 1.0
 * Author: Andreas Pedersen
 * Author URI: https://bo-we.dk
 */

 //?apply_coupon=your-coupon-code

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Bowe_Coupon_URL {

    /**
     * The single instance of the class.
     *
     * @var Bowe_Coupon_URL
     */
    protected static $_instance = null;

    /**
     * Main Bowe_Coupon_URL Instance.
     *
     * Ensures only one instance of Bowe_Coupon_URL is loaded or can be loaded.
     *
     * @return Bowe_Coupon_URL - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Bowe_Coupon_URL Constructor.
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'apply_coupon_via_url' ) );
        add_filter( 'woocommerce_get_price_html',  array( $this, 'bowe_custom_price_html'), 100, 2 );
        add_action( 'add_meta_boxes', array( $this, 'add_coupon_url_meta_box' ) );
    }

    /**
     * Handle the coupon via URL.
     */
    public function apply_coupon_via_url() {
        if ( is_admin() || ! isset( $_GET['apply_coupon'] ) ) {
            return;
        }
    
        $coupon_code = sanitize_text_field( $_GET['apply_coupon'] );
    
        // Check if the coupon code is empty
        if ( empty( $coupon_code ) ) {
            return;
        }
    
        // Check if the coupon is valid before starting the session and cart
        $coupon = new WC_Coupon( $coupon_code );
        if ( ! $coupon->get_id() || ! $coupon->is_valid() ) {
            return; // Exit if the coupon is not valid
        }
    
        // Ensure a WC Cart session is started
        if ( ! WC()->cart ) {
            WC()->cart = new WC_Cart();
        }
    
        // Ensure session is started, this is important for empty carts
        if ( ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true );
        }
    
        if ( ! WC()->cart->has_discount( $coupon_code ) ) {
            WC()->cart->add_discount( $coupon_code );
            wc_add_notice( __( 'Coupon code applied successfully.', 'woocommerce' ), 'success' );
        }
    }
    


    public function bowe_custom_price_html( $price_html, $product ) {
        if ( WC()->cart && WC()->cart->has_discount() ) {
            $applied_coupons = WC()->cart->get_applied_coupons();
    
            foreach ($applied_coupons as $code) {
                $coupon = new WC_Coupon( $code );
                if ( ! $coupon->get_id() || ! $coupon->is_valid() ) {
                    continue; // Skip if the coupon is not valid
                }
    
                // Check if the product is eligible for the coupon
                if ( ! $coupon->is_valid_for_product( $product, array() ) ) {
                    continue; // Skip if the product is not eligible
                }
    
                // Assuming the coupon is a percentage discount
                if ( $coupon->is_type( 'percent' ) ) {
                    $discount_percentage = $coupon->get_amount();
                    $original_price = $product->get_regular_price();
                    $discounted_price = $original_price - ($original_price * ($discount_percentage / 100));
    
                    $price_html = sprintf(__(' <del>%s</del>', 'woocommerce'), wc_price($original_price));
                    $price_html .= sprintf(__(' <ins>%s</ins>', 'woocommerce'), wc_price($discounted_price));
                }
            }
        }
    
        return $price_html;
    }
    


    /**
     * Checks if a given coupon code is valid.
     *
     * @param string $coupon_code The coupon code to check.
     * @return array|bool Returns an array with coupon details if valid, false otherwise.
     */
    private function is_coupon_valid( $coupon_code ) {
        // Check if the coupon code exists
        if ( ! $coupon_code ) {
            return false;
        }

        // Create a new coupon object
        $coupon = new WC_Coupon( $coupon_code );

        // Check if the coupon is valid
        if ( ! $coupon->get_id() || ! $coupon->is_valid() ) {
            return false;
        }

        // Return relevant coupon details
        return array(
            'code' => $coupon_code,
            'type' => $coupon->get_discount_type(),
            'amount' => $coupon->get_amount(),
        );
    }




    /**
     * Add meta box to coupon edit page.
     */
    public function add_coupon_url_meta_box() {
        add_meta_box(
            'bowe_coupon_url',
            __( 'Coupon URL', 'woocommerce' ),
            array( $this, 'coupon_url_meta_box_content' ),
            'shop_coupon',
            'side',
            'high'
        );
    }

    /**
     * Content for the coupon URL meta box.
     */
    public function coupon_url_meta_box_content( $post ) {
        // Ensure global $post is available
        global $post;

        // Get the current coupon code
        $coupon_code = $post->post_title;

        // Create the URL
        $site_url = get_site_url();
        $coupon_url = add_query_arg( 'apply_coupon', urlencode( $coupon_code ), $site_url );

        // Display the URL
        echo '<p>' . __( 'Use the following URL to apply this coupon:', 'woocommerce' ) . '</p>';
        echo '<input type="text" readonly="readonly" class="large-text" value="' . esc_url( $coupon_url ) . '">';
        echo '<p>' . __( 'Copy and share this URL with your customers.', 'woocommerce' ) . '</p>';
    }


}

/**
 * Returns the main instance of Bowe_Coupon_URL.
 *
 * @return Bowe_Coupon_URL
 */
function BOWE_COUPON_URL() {
    return Bowe_Coupon_URL::instance();
}

// Global for backwards compatibility.
$GLOBALS['bowe_coupon_url'] = BOWE_COUPON_URL();
