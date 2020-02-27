<?php

namespace ExperienceManager\Ecommerce\Elementor\Widgets;

/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Popular_Products_Widget extends Product_Widget {

	public function __construct($data = array(), $args = null) {
		parent::__construct($data, $args);
	}

	public function get_name() {
		return 'exm_widget_popular_products';
	}

	public function get_title() {
		return __('Popular products', 'plugin-name');
	}
	
	public function get_exm_type () {
		return "popular-products";
	}
}
