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

		try {
			if ($type == "popular-products") {
				$this->shop_profile($count);
			} else {
				$this->user_profile($count);
			}
		} catch (Exception $ex) {
			$reponse = [
				"error" => true,
				"message" => $ex->getMessage()
			];
			wp_send_json($response);
		}
	}

	private function shop_profile($count) {
		$parameters = [
			"query" => [
				"userid" => exm_ecom_get_userid(),
				"site" => tma_exm_get_site()
			]
		];

//		wp_send_json($parameters);
		$request = new \TMA\ExperienceManager\TMA_Request();
		$response = [];
		$values = $request->get("json/profiles/shop", $parameters);
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
			"query" => [
				"userid" => exm_ecom_get_userid(),
				"site" => tma_exm_get_site()
			]
		];
		//https://exp.wp-digitalexperience.com/rest/module/module-ecommerce/range?name=order_conversion_rate&site=b8ff2cf4-aee7-49eb-9a08-085d9ba20788&end=1577836800000&start=1546732800000

		$request = new \TMA\ExperienceManager\TMA_Request();
		$response = [];
		$values = $request->get("json/profiles/user", $parameters);

		if ($values !== FALSE && (is_object($values) || is_array($values)) && !is_wp_error($values)) {
			$values = $values['body']; // use the content
		}
		
		if (!$values) {
			$response["error"] = true;
			wp_send_json($response);
		} else {
			$recentlyViewedProducts = [];
			$frequentlyPurchasedProducts = [];

			if (property_exists($values, "recentlyViewedProducts")) {
				tma_exm_log("recentlyViewedProducts");
			} else {
				tma_exm_log("no recentlyViewedProducts");
			}

			if (property_exists($values, "recentlyViewedProducts")) {
				foreach ($values->recentlyViewedProducts as $product) {
					$prod = $this->load_product($product->id);
					if ($prod !== FALSE) {
						$recentlyViewedProducts[] = $prod;
					}
				}
			}
			if (sizeof($recentlyViewedProducts) < $count) {
				$random = $this->random_products($count - sizeof($recentlyViewedProducts));
				foreach ($random as $product) {
					$prod = $this->load_product($product->ID);
					if ($prod !== FALSE) {
						$recentlyViewedProducts[] = $prod;
					}
				}
			} else if (sizeof($recentlyViewedProducts) > $count) {
				$recentlyViewedProducts = array_slice($recentlyViewedProducts, 0, $count);
			}
			if (property_exists($values, "frequentlyPurchasedProducts")) {
				foreach ($values->frequentlyPurchasedProducts as $product) {
					$prod = $this->load_product($product->id);
					if ($prod !== FALSE) {
						$frequentlyPurchasedProducts[] = $prod;
					}
				}
			}
			if (sizeof($frequentlyPurchasedProducts) < $count) {
				$random = $this->random_products($count - sizeof($frequentlyPurchasedProducts));
				foreach ($random as $product) {
					$prod = $this->load_product($product->ID);
					if ($prod !== FALSE) {
						$frequentlyPurchasedProducts[] = $prod;
					}
				}
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
