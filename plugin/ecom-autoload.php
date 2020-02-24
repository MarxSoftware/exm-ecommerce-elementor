<?php

define("EXM_ECOM_DIR", plugin_dir_path(__FILE__));

define("EXM_ECOM_CLASSES", array(
	"ExperienceManager\Ecommerce\Ajax\Ecommerce_Ajax" => "classes/class.ecommerce-ajax.php",
	"ExperienceManager\Ecommerce\Ajax\Ecommerce_Ajax_WooCommerce" => "classes/class.ecommerce-ajax-woocommerce.php",
	"ExperienceManager\Ecommerce\Elementor\Widgets\Product_Widget" => "classes/widgets/recently-viewed-products.php"
));

function exm_ecom_autoload($class_name) {
	if (array_key_exists($class_name, EXM_ECOM_CLASSES)) {
		require_once EXM_ECOM_DIR . "/" . EXM_ECOM_CLASSES[$class_name];
	}
}

spl_autoload_register('exm_ecom_autoload');
