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

	public abstract function random_products($count);

	public abstract function popular_products($count);

	public function load_products() {
		$type = filter_input(INPUT_POST, 'type', FILTER_DEFAULT);
		$count = filter_input(INPUT_POST, 'count', FILTER_DEFAULT);

		if ($count === FALSE || $count === NULL) {
			$count = 3;
		} else {
			$count = intval($count);
		}

		if ($type == "popular-products") {
			$this->shop_profile($count);
		} else {
			$this->user_profile($count);
		}
	}

	private function shop_profile($count) {
		$parameters = [
			"userid" => exm_ecom_get_userid(),
			"site" => tma_exm_get_site()
		];

		$request = new \TMA\ExperienceManager\TMA_Request();
		$response = [];
		$values = $request->module("module-ecommerce", "/shopprofile", $parameters);
		if (!$values) {
			$response["error"] = true;
			wp_send_json($response);
		} else {
			$popularProducts = [];

			if (property_exists($values, "popularProducts")) {
				foreach ($values->popularProducts as $product) {
					$popularProducts[] = $this->load_product($product->id);
				}
			}
			if (sizeof($popularProducts) < $count) {
//				$popularProducts = $this->extend_products($popularProducts, $this->popular_products, $count);
				$popular = $this->popular_products($count - sizeof($popularProducts));
				foreach ($popular as $product) {
					$popularProducts[] = $this->load_product($product->ID);
				}
				if (sizeof($popularProducts) < $count) {
//					$popularProducts = $this->extend_products($popularProducts, $this->random_products, $count);
					$random = $this->random_products($count - sizeof($popularProducts));
					foreach ($random as $product) {
						$popularProducts[] = $this->load_product($product->ID);
					}
				}
			} else if (sizeof($popularProducts) > $count) {
				$popularProducts = array_slice($popularProducts, 0, $count);
			}
			$response["popularProducts"] = $popularProducts;
			$response["error"] = false;
			wp_send_json($response);
		}
	}

	private function user_profile($count) {
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
			if (sizeof($recentlyViewedProducts) < $count) {
				$random = $this->random_products($count - sizeof($recentlyViewedProducts));
				foreach ($random as $product) {
					$recentlyViewedProducts[] = $this->load_product($product->ID);
				}
//				$recentlyViewedProducts = $this->extend_products($recentlyViewedProducts, $this->random_products, $count);
			} else if (sizeof($recentlyViewedProducts) > $count) {
				$recentlyViewedProducts = array_slice($recentlyViewedProducts, 0, $count);
			}
			if (property_exists($values, "frequentlyPurchasedProducts")) {
				foreach ($values->frequentlyPurchasedProducts as $product) {
					$frequentlyPurchasedProducts[] = $this->load_product($product->id);
				}
			}
			if (sizeof($frequentlyPurchasedProducts) < $count) {
				$random = $this->random_products($count - sizeof($frequentlyPurchasedProducts));
				foreach ($random as $product) {
					$frequentlyPurchasedProducts[] = $this->load_product($product->ID);
				}
//				$frequentlyPurchasedProducts = $this->extend_products($frequentlyPurchasedProducts, $this->random_products, $count);
			} else if (sizeof($frequentlyPurchasxedProducts) > $count) {
				$frequentlyPurchasedProducts = array_slice($frequentlyPurchasedProducts, 0, $count);
			}
			$response["recentlyViewedProducts"] = $recentlyViewedProducts;
			$response["frequentlyPurchasedProducts"] = $frequentlyPurchasedProducts;
			$response["error"] = false;
			wp_send_json($response);
		}
	}

}
