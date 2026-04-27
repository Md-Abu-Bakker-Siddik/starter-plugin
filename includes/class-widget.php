<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class DWL_Pricing_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'dwl_pricing';
    }

    public function get_title() {
        return __( 'DWL Pricing Table', 'dwl-pricing' );
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'dwl-pricing' ),
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__( 'Show Table Title', 'dwl-pricing' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'dwl-pricing' ),
                'label_off'    => esc_html__( 'Hide', 'dwl-pricing' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'table_title',
            [
                'label'     => esc_html__( 'Table Title', 'dwl-pricing' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => esc_html__( 'Choose Your Plan', 'dwl-pricing' ),
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'json_url',
            [
                'label'       => esc_html__( 'JSON Endpoint URL', 'dwl-pricing' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => 'https://example.com/pricing.json',
                'default'     => 'https://api.jsonbin.io/v3/b/662d083be41b4d34e4aa23a3?meta=false',
            ]
        );

        $this->add_control(
            'currency_symbol_global',
            [
                'label'   => esc_html__( 'Currency Symbol', 'dwl-pricing' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => '$',
            ]
        );

        $this->add_control(
            'popular_plan_index',
            [
                'label'       => esc_html__( 'Popular Plan Index', 'dwl-pricing' ),
                'description' => esc_html__( '1 = first plan, 2 = second plan.', 'dwl-pricing' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'default'     => 2,
                'min'         => 1,
                'step'        => 1,
            ]
        );

        $this->add_control(
            'cache_ttl',
            [
                'label'       => esc_html__( 'Cache TTL (seconds)', 'dwl-pricing' ),
                'description' => esc_html__( 'Minimum 60 seconds.', 'dwl-pricing' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'default'     => 300,
                'min'         => 60,
                'step'        => 30,
            ]
        );

        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => esc_html__( 'Columns', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1 Column',
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dwl-pricing-container' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__( 'Grid Gap', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dwl-pricing-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'show_popular_badge',
            [
                'label' => esc_html__( 'Show Popular Badge', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'popular_badge_text',
            [
                'label' => esc_html__( 'Popular Badge Text', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Most Popular', 'dwl-pricing' ),
                'condition' => [
                    'show_popular_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __( 'Container', 'dwl-pricing' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__( 'Padding', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .dwl-pricing-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_card_style',
            [
                'label' => __( 'Card', 'dwl-pricing' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => esc_html__( 'Background Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-pricing-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .dwl-pricing-card',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .dwl-pricing-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dwl-pricing-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__( 'Padding', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .dwl-pricing-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'dwl-pricing' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plan-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .plan-name',
            ]
        );

        $this->add_control(
            'widget_title_color',
            [
                'label' => esc_html__( 'Widget Title Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-table-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'widget_title_typography',
                'selector' => '{{WRAPPER}} .dwl-table-title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_price_style',
            [
                'label' => __( 'Price', 'dwl-pricing' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plan-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .plan-price',
            ]
        );

        $this->add_control(
            'currency_color',
            [
                'label' => esc_html__( 'Currency Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plan-price span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_features_style',
            [
                'label' => __( 'Features', 'dwl-pricing' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'features_text_color',
            [
                'label' => esc_html__( 'Text Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plan-features li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'features_typography',
                'selector' => '{{WRAPPER}} .plan-features li',
            ]
        );

        $this->add_control(
            'features_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plan-features li::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_badge_style',
            [
                'label' => __( 'Popular Badge', 'dwl-pricing' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => esc_html__( 'Text Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .dwl-badge',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Button', 'dwl-pricing' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab(
            'button_normal',
            [
                'label' => __( 'Normal', 'dwl-pricing' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [
                'label' => __( 'Hover', 'dwl-pricing' ),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dwl-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .dwl-btn',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .dwl-btn',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dwl-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'dwl-pricing' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .dwl-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $json_url = isset( $settings['json_url'] ) ? $settings['json_url'] : '';
        $cache_ttl = isset( $settings['cache_ttl'] ) ? absint( $settings['cache_ttl'] ) : 300;
        $plans = DWL_Pricing_Data::get_plans( $json_url, $cache_ttl );
        $show_badge = isset( $settings['show_popular_badge'] ) && 'yes' === $settings['show_popular_badge'];
        $badge_text = ! empty( $settings['popular_badge_text'] ) ? $settings['popular_badge_text'] : 'Most Popular';
        $show_title = isset( $settings['show_title'] ) && 'yes' === $settings['show_title'];
        $table_title = isset( $settings['table_title'] ) ? $settings['table_title'] : '';
        $currency_symbol_global = isset( $settings['currency_symbol_global'] ) ? $settings['currency_symbol_global'] : '$';
        $popular_index = isset( $settings['popular_plan_index'] ) ? absint( $settings['popular_plan_index'] ) : 2;

        if ( empty( $plans ) ) {
            return;
        }
        ?>
        <?php if ( $show_title && '' !== $table_title ) : ?>
            <h2 class="dwl-table-title"><?php echo esc_html( $table_title ); ?></h2>
        <?php endif; ?>
        <div class="dwl-pricing-container">
            <?php foreach ( $plans as $index => $plan ) : ?>
                <?php
                if ( ! is_array( $plan ) ) {
                    continue;
                }

                $plan_name = ! empty( $plan['name'] ) ? $plan['name'] : '';
                $plan_price = ! empty( $plan['price'] ) ? $plan['price'] : '';
                $currency_symbol = $currency_symbol_global;
                $button_text = esc_html__( 'Choose Plan', 'dwl-pricing' );
                $is_popular = ( (int) $index + 1 ) === (int) $popular_index;
                $features = isset( $plan['features'] ) && is_array( $plan['features'] ) ? $plan['features'] : [];
                ?>
                <article class="dwl-pricing-card<?php echo $is_popular ? ' is-highlighted' : ''; ?>">
                    <?php if ( $show_badge && $is_popular ) : ?>
                        <div class="dwl-badge"><?php echo esc_html( $badge_text ); ?></div>
                    <?php endif; ?>

                    <h3 class="plan-name"><?php echo esc_html( $plan_name ); ?></h3>
                    <div class="plan-price">
                        <span><?php echo esc_html( $currency_symbol ); ?></span><?php echo esc_html( $plan_price ); ?>
                    </div>

                    <ul class="plan-features">
                        <?php foreach ( $features as $feature ) : ?>
                            <li><?php echo esc_html( $feature ); ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <button type="button" class="dwl-btn" aria-label="<?php echo esc_attr( sprintf( __( 'Select %s plan', 'dwl-pricing' ), $plan_name ) ); ?>">
                        <?php echo esc_html( $button_text ); ?>
                    </button>
                </article>
            <?php endforeach; ?>
        </div>
        <?php
    }
}