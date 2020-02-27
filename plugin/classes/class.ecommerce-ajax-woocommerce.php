<?php

namespace ExperienceManager\Ecommerce\Ajax;

/**
 * Description of class
 *
 * @author marx
 */
class Ecommerce_Ajax_WooCommerce extends Ecommerce_Ajax {

	private static $_instance = null;

	public static function instance() {

		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		parent::__construct();
	}

	public function popular_products($count) {
		$args = array(
			'post_type' => array( 'product' ),
			'meta_key' => 'total_sales',
			'orderby' => 'meta_value_num',
			'order' => 'desc',
			'posts_per_page' => $count
		);

		$products = get_posts($args);

		return $products;
	}

	public function random_products($count) {
		$args = array(
			'posts_per_page' => $count,
			'orderby' => 'rand',
			'post_type' => 'product');

		$random_products = get_posts($args);

		return $random_products;
	}

	public function load_product($product_id) {
		$woo_product = wc_get_product($product_id);

		$product = [
			"title" => $woo_product->get_title(),
			"price" => $woo_product->get_price_html(),
			"is_sale" => $woo_product->is_on_sale(),
			"image" => $woo_product->get_image("woocommerce_thumbnail", ['class' => 'image']),
			"url" => $woo_product->get_permalink()
		];

		return $product;
	}

}
