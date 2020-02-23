<?php

namespace ExperienceManager\Ecommerce\Ajax;

/**
 * Description of class
 *
 * @author marx
 */
class Ecommerce_Ajax_WooCommerce extends Ecommerce_Ajax {

	private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Elementor_Test_Extension An instance of the class.
     */
    public static function instance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	public function __construct() {		
		parent::__construct();
	}

	public function load_product($product_id) {
		$woo_product = wc_get_product($product_id);
		
		$product = [
			"title" => $woo_product->get_title(),
			"price" => $woo_product->get_price_html(),
			"sale" => $woo_product->is_on_sale(),
			"url" => $woo_product->get_permalink()
		];
		
		return $product;
	}
}
