<?php

/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_oEmbed_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve oEmbed widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'oembed';
    }

    /**
     * Get widget title.
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Recently Viewed Products', 'plugin-name');
    }

    /**
     * Get widget icon.
     *
     * Retrieve oEmbed widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'fa fa-shopping-basket';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the oEmbed widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['digital-experience'];
    }

    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter your title', 'plugin-name'),

            ]
        );

        $this->add_control(
			'products_filter',
			[
				'label' => __( 'Products', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'popular',
				'options' => [
					'popular' => __( 'Popular in shop', 'plugin-domain' ),
					'recently_views'  => __( 'Recently viewed by user', 'plugin-domain' ),
					'frequently_purchased' => __( 'Frequently purchased by user', 'plugin-domain' ),
				],
			]
        );
        $this->add_control(
			'product_count',
			[
				'label' => __( 'Number of products', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 3,
				'step' => 1,
				'default' => 3,
			]
		);

        $this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row' => __( 'Horizontal', 'plugin-domain' ),
					'column'  => __( 'Vertical', 'plugin-domain' ),
				],
			]
        );

        $this->end_controls_section();



        

        $this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Title', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'plugin-domain' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'plugin-domain' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'plugin-domain' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Color', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f00',
				'selectors' => [
					'{{WRAPPER}} h3' => 'color: {{VALUE}}',
				],
			]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'plugin-domain' ),
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .title',
			]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Product', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        $this->add_control(
			'show_image',
			[
				'label' => __( 'Show Image', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'show_button',
			[
				'label' => __( 'Show button', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        $this->add_control(
			'show_price',
			[
				'label' => __( 'Show price', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
            'button_text',
            [
                'label' => __('Button text', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('Enter your button text', 'plugin-name'),
				'default' => __('Buy now', 'plugin-name')
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'label' => __( 'Title', 'plugin-domain' ),
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .product .product_title',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'label' => __( 'Price', 'plugin-domain' ),
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .product .product_price',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'product_background',
				'label' => __( 'Product background', 'plugin-domain' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .product',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'section_sale',
			[
				'label' => __( 'Sale', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'show_sale',
			[
                'label' => __( 'Show sale', 'plugin-domain' ),
                'description' => __( 'Sales label will only be viewed in products in sale!', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        $this->add_control(
			'sale_align',
			[
				'label' => __( 'Alignment', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'plugin-domain' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'plugin-domain' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'right',
				'toggle' => true,
			]
		);
        $this->add_control(
			'sale_color',
			[
				'label' => __( 'Color', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .sale' => 'color: {{VALUE}}',
				],
			]
        );
        $this->add_control(
			'sale_backgroundcolor',
			[
				'label' => __( 'Background color', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .sale' => 'background-color: {{VALUE}}',
				],
			]
        );
        $this->add_control(
            'sale_text',
            [
                'label' => __('Text', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Sale!', 'plugin-name'),

            ]
		);
		$this->add_control(
			'sale_position',
			[
				'label' => __( 'Position', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .product .sale' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();
    }

    /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();

        echo '<div class="oembed-elementor-widget">';
        echo '<h3>' . $settings['title'] . '</h3>';

        echo '</div>';
    }

    protected function _content_template()
    {
		$currency_sympol = get_woocommerce_currency_symbol();
?>      <div>
            <#
                var count = settings.product_count;
                var width = Math.round(100 / count);
                if (settings.direction === "column") {
                    width = 100;
                }
				var currency_symbol = "<?php echo $currency_sympol;?>";
            #>
            <h3 class="title" style="text-align: {{ settings.text_align }}">{{{ settings.title }}}</h3>
            <div style="display: flex; flex-direction: {{ settings.direction }};  justify-content: space-between;">
                <#
                    for (var i = 0; i < count; i++) {
                        #>
                        <div class="product" style="width: {{width}}%; min-height: 250px; padding:10px; text-align:center; position:relative;">
                            <# if ( 'yes' === settings.show_title ) { #>
			                    <h4 class="product_title" style=" width:100%;">Product title</h4>
		                    <# } #>
                            <# if ( 'yes' === settings.show_image ) { #>
			                    <div style="background-color: darkgrey; width:50%; height:50%; margin: 0 auto;" ></div>
		                    <# } #>
                            <# if ( 'yes' === settings.show_price ) { #>
			                    <h4 class="product_price" style=" margin: 0 auto; margin-top: 10px;">{{{ currency_symbol }}}24.99</h4>
                            <# } #>
                            <# if ( 'yes' === settings.show_sale ) { #>
			                    <div class="sale" style="padding: 2px; font-weight: bold; position: absolute; {{ settings.sale_align }}: 0;">{{{ settings.sale_text }}}</div>
							<# } #>
							<# if ( 'yes' === settings.show_button ) { #>
			                    <button class="button" style="margin: 0 auto;">{{{ settings.button_text }}}</button>
		                    <# } #>
                        </div>
                        <#
                    }
                #>
            </div>
        </div>
<?php
    }
}
