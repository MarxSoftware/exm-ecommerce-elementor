<?php

namespace ExperienceManager\Ecommerce\Ajax;

/**
 * Description of class
 *
 * @author marx
 */
class Ecommerce_Ajax_EDD extends Ecommerce_Ajax {

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
			'post_type' => array( 'download' ),
			'meta_key' => '_edd_download_sales',
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
			'post_type' => 'download');

		$random_products = get_posts($args);

		return $random_products;
	}

	public function load_product($product_id) {
		$download = new \EDD_Download($product_id);

		$product = [
			"title" => $download->post_title,
			"price" => edd_currency_filter( edd_format_amount( edd_get_download_price( $product_id ) ) ), //$woo_product->get_price_html(),
			"is_sale" => false,
			"image" => the_post_thumbnail( 'post-thumbnail', ['class' => 'image']),
			"url" => get_the_permalink($product_id)
		];
		

		return $product;
	}

}
