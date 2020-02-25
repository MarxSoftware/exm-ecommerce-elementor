<?php

namespace ExperienceManager\Ecommerce\Ajax;

/**
 * Description of class
 *
 * @author marx
 */
abstract class Ecommerce_Ajax {
	
	public function __construct() {		
		add_action("wp_ajax_nopriv_exm_ecom_load_products", [$this, "load_products"]);
		add_action("wp_ajax_exm_ecom_load_products", [$this, "load_products"]);
	}

	public abstract function load_product($product_id);
	
	public function load_products () {
		$type = filter_input(INPUT_GET, 'type', FILTER_DEFAULT);
		
		$parameters = [
			"userid" => exm_ecom_get_userid(),
			"site" => tma_exm_get_site()
		];
		//https://exp.wp-digitalexperience.com/rest/module/module-ecommerce/range?name=order_conversion_rate&site=b8ff2cf4-aee7-49eb-9a08-085d9ba20788&end=1577836800000&start=1546732800000

		$request = new \TMA\ExperienceManager\TMA_Request();
		$response = [];
		$values = $request->module("module-ecommerce", "/userprofile", $parameters);
		if (!$values) {
			$response["error"] = true;
			wp_send_json($response);
		} else {
			$recentlyViewedProducts = [];
			$frequentlyPurchasedProducts = [];
			if (property_exists($values, "recentlyViewedProducts")) {
				foreach ($values->recentlyViewedProducts as $product) {
					$recentlyViewedProducts[] = $this->load_product($product->id);
				}
			}
			if (property_exists($values, "frequentlyPurchasedProducts")) {
				foreach ($values->frequentlyPurchasedProducts as $product) {
					$frequentlyPurchasedProducts[] = $this->load_product($product->id);
				}
			}
			$response["recentlyViewedProducts"] = $recentlyViewedProducts;
			$response["frequentlyPurchasedProducts"] = $frequentlyPurchasedProducts;
			$response["error"] = false;
			wp_send_json($response);
		}
		
	}
}
