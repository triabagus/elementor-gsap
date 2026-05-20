<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sidenav_Wipe_Widget extends Widget_Base {

	public function get_name() {
		return 'sidenav_wipe';
	}

	public function get_title() {
		return __( 'Side Navigation Wipe', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'side', 'navigation', 'menu', 'wipe', 'drawer', 'gsap', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-customease', 'elementor-sidenav-wipe' ];
	}

	public function get_style_depends() {
		return [ 'elementor-sidenav-wipe' ];
	}

	protected function register_controls() {
		/* === CONTENT: TRIGGER === */
		$this->start_controls_section( 'content_trigger', [
			'label' => __( 'Trigger Button', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'open_label', [
			'label'   => __( 'Open Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Menu',
		] );

		$this->add_control( 'close_label', [
			'label'   => __( 'Close Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Close',
		] );

		$this->end_controls_section();

		/* === CONTENT: MENU ITEMS === */
		$this->start_controls_section( 'content_menu', [
			'label' => __( 'Menu Items', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$item_rep = new Repeater();
		$item_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Menu Item',
			'dynamic' => [ 'active' => true ],
		] );
		$item_rep->add_control( 'eyebrow', [
			'label'       => __( 'Eyebrow', 'elementor-gsap' ),
			'description' => __( 'Kode kecil (01, 02, dst). Biarkan kosong untuk auto-number.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
		] );
		$item_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'menu_items', [
			'label'       => __( 'Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $item_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'About us',   'eyebrow' => '01', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Our work',   'eyebrow' => '02', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Services',   'eyebrow' => '03', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Blog',       'eyebrow' => '04', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Contact us', 'eyebrow' => '05', 'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: SOCIALS === */
		$this->start_controls_section( 'content_socials', [
			'label' => __( 'Socials', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_socials', [
			'label'        => __( 'Show Socials', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'elementor-gsap' ),
			'label_off'    => __( 'No', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'socials_label', [
			'label'     => __( 'Section Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Socials',
			'condition' => [ 'show_socials' => 'yes' ],
		] );

		$this->add_control( 'socials_display', [
			'label'       => __( 'Display Mode', 'elementor-gsap' ),
			'description' => __( 'Pilih bagaimana setiap social link ditampilkan.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'text'      => __( 'Text Only', 'elementor-gsap' ),
				'icon'      => __( 'Icon Only', 'elementor-gsap' ),
				'icon_text' => __( 'Icon + Text', 'elementor-gsap' ),
			],
			'default'     => 'text',
			'condition'   => [ 'show_socials' => 'yes' ],
		] );

		$soc_rep = new Repeater();
		$soc_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Instagram',
		] );
		$soc_rep->add_control( 'social_icon', [
			'label'   => __( 'Icon', 'elementor-gsap' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [
				'value'   => 'fab fa-instagram',
				'library' => 'fa-brands',
			],
		] );
		$soc_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'socials_items', [
			'label'       => __( 'Links', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $soc_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'condition'   => [ 'show_socials' => 'yes' ],
			'default'     => [
				[ 'label' => 'Instagram', 'link' => [ 'url' => '#' ], 'social_icon' => [ 'value' => 'fab fa-instagram',   'library' => 'fa-brands' ] ],
				[ 'label' => 'LinkedIn',  'link' => [ 'url' => '#' ], 'social_icon' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ] ],
				[ 'label' => 'X/Twitter', 'link' => [ 'url' => '#' ], 'social_icon' => [ 'value' => 'fab fa-x-twitter',   'library' => 'fa-brands' ] ],
				[ 'label' => 'Dribbble',  'link' => [ 'url' => '#' ], 'social_icon' => [ 'value' => 'fab fa-dribbble',    'library' => 'fa-brands' ] ],
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: ANIMATION === */
		$this->start_controls_section( 'content_anim', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'open_duration', [
			'label'       => __( 'Open Duration (s)', 'elementor-gsap' ),
			'description' => __( 'GSAP default duration. Tween individual mungkin punya durasi override sendiri.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.2,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.7,
		] );

		$this->end_controls_section();

		/* === STYLE: TRIGGER BUTTON === */
		$this->start_controls_section( 'style_button', [
			'label' => __( 'Trigger Button', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'btn_top', [
			'label'      => __( 'Top Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .sidenav' => '--sn-btn-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'btn_side', [
			'label'      => __( 'Side Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .sidenav' => '--sn-btn-side: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'btn_color', [
			'label'     => __( 'Button Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#131313',
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-btn-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'btn_icon_color', [
			'label'     => __( 'Button Icon Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#131313',
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-btn-icon-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'btn_typography',
			'selector' => '{{WRAPPER}} .sidenav__button-label',
		] );

		$this->end_controls_section();

		/* === STYLE: MENU PANEL === */
		$this->start_controls_section( 'style_menu', [
			'label' => __( 'Menu Panel', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'menu_width', [
			'label'      => __( 'Menu Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 20, 'max' => 60, 'step' => 0.5 ],
				'px' => [ 'min' => 300, 'max' => 900 ],
				'%'  => [ 'min' => 30, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 35 ],
			'selectors'  => [
				'{{WRAPPER}} .sidenav' => '--sn-menu-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'menu_radius', [
			'label'      => __( 'Corner Radius (top-left & bottom-left)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .sidenav' => '--sn-menu-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'overlay_color', [
			'label'     => __( 'Overlay Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(19, 19, 19, 0.4)',
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-overlay-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'panel_1_color', [
			'label'       => __( 'Panel 1 (first wipe)', 'elementor-gsap' ),
			'description' => __( 'Warna panel paling depan saat wipe masuk.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#e04645',
			'selectors'   => [
				'{{WRAPPER}} .sidenav' => '--sn-panel-1: {{VALUE}};',
			],
		] );

		$this->add_control( 'panel_2_color', [
			'label'       => __( 'Panel 2 (middle wipe)', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#131313',
			'selectors'   => [
				'{{WRAPPER}} .sidenav' => '--sn-panel-2: {{VALUE}};',
			],
		] );

		$this->add_control( 'panel_3_color', [
			'label'       => __( 'Panel 3 (final background)', 'elementor-gsap' ),
			'description' => __( 'Warna terakhir yang muncul — jadi background final menu.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#ebdcc5',
			'selectors'   => [
				'{{WRAPPER}} .sidenav' => '--sn-panel-3: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: MENU ITEMS === */
		$this->start_controls_section( 'style_items', [
			'label' => __( 'Menu Items', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->start_controls_tabs( 'items_state_tabs' );

		/* Normal state */
		$this->start_controls_tab( 'items_state_normal', [
			'label' => __( 'Normal', 'elementor-gsap' ),
		] );

		$this->add_control( 'heading_color', [
			'label'     => __( 'Heading Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#131313',
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-heading-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'eyebrow_color', [
			'label'     => __( 'Eyebrow Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e04645',
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-eyebrow-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		/* Hover state */
		$this->start_controls_tab( 'items_state_hover', [
			'label' => __( 'Hover', 'elementor-gsap' ),
		] );

		$this->add_control( 'heading_hover_color', [
			'label'     => __( 'Heading Hover Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-heading-hover-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'eyebrow_hover_color', [
			'label'     => __( 'Eyebrow Hover Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-eyebrow-hover-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'heading_hover_shift', [
			'label'       => __( 'Heading Hover Shift (X)', 'elementor-gsap' ),
			'description' => __( 'Pergeseran horizontal heading saat hover. Set 0 untuk tanpa shift.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => -2, 'max' => 2, 'step' => 0.025 ],
				'px' => [ 'min' => -40, 'max' => 40 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 0.3 ],
			'selectors'   => [
				'{{WRAPPER}} .sidenav' => '--sn-heading-hover-shift: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'underline_heading', [
			'label'     => __( 'Hover Underline', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'show_underline', [
			'label'        => __( 'Show Underline on Hover', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'elementor-gsap' ),
			'label_off'    => __( 'No', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'underline_color', [
			'label'     => __( 'Underline Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-underline-color: {{VALUE}};',
			],
			'condition' => [ 'show_underline' => 'yes' ],
		] );

		$this->add_responsive_control( 'underline_thickness', [
			'label'      => __( 'Underline Thickness', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.01, 'max' => 0.3, 'step' => 0.005 ],
				'px' => [ 'min' => 1, 'max' => 20 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.05 ],
			'selectors'  => [
				'{{WRAPPER}} .sidenav' => '--sn-underline-thickness: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'show_underline' => 'yes' ],
		] );

		$this->add_responsive_control( 'underline_offset', [
			'label'       => __( 'Underline Offset', 'elementor-gsap' ),
			'description' => __( 'Jarak underline dari baseline teks. Naikkan untuk gap kecil di bawah.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => -0.5, 'max' => 0.5, 'step' => 0.01 ],
				'px' => [ 'min' => -20, 'max' => 20 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 0 ],
			'selectors'   => [
				'{{WRAPPER}} .sidenav' => '--sn-underline-offset: {{SIZE}}{{UNIT}};',
			],
			'condition'   => [ 'show_underline' => 'yes' ],
		] );

		$this->add_control( 'underline_origin', [
			'label'       => __( 'Reveal Direction', 'elementor-gsap' ),
			'description' => __( 'Sisi tempat underline mulai muncul.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'left'   => __( 'Left → Right', 'elementor-gsap' ),
				'center' => __( 'Center → Expand', 'elementor-gsap' ),
				'right'  => __( 'Right → Left', 'elementor-gsap' ),
			],
			'default'     => 'left',
			'selectors'   => [
				'{{WRAPPER}} .sidenav' => '--sn-underline-origin: {{VALUE}};',
			],
			'condition'   => [ 'show_underline' => 'yes' ],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .sidenav__menu-link-heading',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'eyebrow_typography',
			'selector' => '{{WRAPPER}} .sidenav__menu-link-eyebrow',
		] );

		$this->end_controls_section();

		/* === STYLE: SOCIALS === */
		$this->start_controls_section( 'style_socials', [
			'label'     => __( 'Socials', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_socials' => 'yes' ],
		] );

		$this->add_responsive_control( 'social_icon_size', [
			'label'      => __( 'Icon Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 8, 'max' => 64 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .sidenav' => '--sn-social-icon-size: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'socials_display' => [ 'icon', 'icon_text' ] ],
		] );

		$this->start_controls_tabs( 'socials_state_tabs' );

		/* Normal state */
		$this->start_controls_tab( 'socials_state_normal', [
			'label' => __( 'Normal', 'elementor-gsap' ),
		] );

		$this->add_control( 'socials_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#131313',
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-socials-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'social_icon_color', [
			'label'     => __( 'Icon Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-social-icon-color: {{VALUE}};',
			],
			'condition' => [ 'socials_display' => [ 'icon', 'icon_text' ] ],
		] );

		$this->end_controls_tab();

		/* Hover state */
		$this->start_controls_tab( 'socials_state_hover', [
			'label' => __( 'Hover', 'elementor-gsap' ),
		] );

		$this->add_control( 'socials_hover_color', [
			'label'     => __( 'Text Hover Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-socials-hover-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'social_icon_hover_color', [
			'label'     => __( 'Icon Hover Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sidenav' => '--sn-social-icon-hover-color: {{VALUE}};',
			],
			'condition' => [ 'socials_display' => [ 'icon', 'icon_text' ] ],
		] );

		$this->add_responsive_control( 'socials_hover_shift', [
			'label'       => __( 'Hover Shift (Y)', 'elementor-gsap' ),
			'description' => __( 'Pergeseran vertikal saat hover. Nilai negatif = naik. Set 0 untuk tanpa shift.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => -1, 'max' => 1, 'step' => 0.025 ],
				'px' => [ 'min' => -20, 'max' => 20 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => -0.25 ],
			'selectors'   => [
				'{{WRAPPER}} .sidenav' => '--sn-socials-hover-shift: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'socials_typography',
			'selector'  => '{{WRAPPER}} .sidenav__menu-details .sidenav__button-label, {{WRAPPER}} .sidenav__menu-socials a',
		] );

		$this->end_controls_section();
	}

	protected function render_link_attrs( $link ) {
		$url = ! empty( $link['url'] ) ? $link['url'] : '#';
		$target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$rel = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';
		return ' href="' . esc_url( $url ) . '"' . $target . $rel;
	}

	protected function render() {
		$s             = $this->get_settings_for_display();
		$open_label    = ! empty( $s['open_label'] ) ? $s['open_label'] : 'Menu';
		$close_label   = ! empty( $s['close_label'] ) ? $s['close_label'] : 'Close';
		$items         = ! empty( $s['menu_items'] ) ? $s['menu_items'] : [];
		$show_socials  = ! empty( $s['show_socials'] ) && 'yes' === $s['show_socials'];
		$socials_label = ! empty( $s['socials_label'] ) ? $s['socials_label'] : 'Socials';
		$socials       = ! empty( $s['socials_items'] ) ? $s['socials_items'] : [];
		$soc_display    = ! empty( $s['socials_display'] ) ? $s['socials_display'] : 'text';
		$show_underline = ! empty( $s['show_underline'] ) && 'yes' === $s['show_underline'];
		$duration       = isset( $s['open_duration'] ) && '' !== $s['open_duration'] ? floatval( $s['open_duration'] ) : 0.7;
		?>
		<div data-sidenav-root class="sidenav" data-sn-duration="<?php echo esc_attr( $duration ); ?>" data-sn-underline="<?php echo $show_underline ? '1' : '0'; ?>">
			<header class="sidenav__header">
				<button type="button" data-sidenav-toggle data-sidenav-button class="sidenav__button" aria-label="<?php echo esc_attr( $open_label ); ?>" aria-expanded="false">
					<div class="sidenav__button-text">
						<p data-sidenav-label class="sidenav__button-label"><?php echo esc_html( $open_label ); ?></p>
						<p data-sidenav-label class="sidenav__button-label"><?php echo esc_html( $close_label ); ?></p>
					</div>
					<div data-sidenav-icon class="sidenav__button-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 16 16" fill="none" class="sidenav__button-icon-svg" aria-hidden="true">
							<path d="M7.33333 16L7.33333 -3.2055e-07L8.66667 -3.78832e-07L8.66667 16L7.33333 16Z" fill="currentColor"></path>
							<path d="M16 8.66667L-2.62269e-07 8.66667L-3.78832e-07 7.33333L16 7.33333L16 8.66667Z" fill="currentColor"></path>
							<path d="M6 7.33333L7.33333 7.33333L7.33333 6C7.33333 6.73637 6.73638 7.33333 6 7.33333Z" fill="currentColor"></path>
							<path d="M10 7.33333L8.66667 7.33333L8.66667 6C8.66667 6.73638 9.26362 7.33333 10 7.33333Z" fill="currentColor"></path>
							<path d="M6 8.66667L7.33333 8.66667L7.33333 10C7.33333 9.26362 6.73638 8.66667 6 8.66667Z" fill="currentColor"></path>
							<path d="M10 8.66667L8.66667 8.66667L8.66667 10C8.66667 9.26362 9.26362 8.66667 10 8.66667Z" fill="currentColor"></path>
						</svg>
					</div>
				</button>
			</header>
			<div data-sidenav-wrap data-nav-state="closed" class="sidenav__nav">
				<div data-sidenav-overlay data-sidenav-toggle class="sidenav__overlay"></div>
				<nav data-sidenav-menu class="sidenav__menu" aria-label="<?php echo esc_attr__( 'Main menu', 'elementor-gsap' ); ?>">
					<div class="sidenav__menu-bg" aria-hidden="true">
						<div data-sidenav-panel class="sidenav__menu-bg-panel is--first"></div>
						<div data-sidenav-panel class="sidenav__menu-bg-panel is--second"></div>
						<div data-sidenav-panel class="sidenav__menu-bg-panel"></div>
					</div>
					<div class="sidenav__menu-inner">
						<ul class="sidenav__menu-list">
							<?php foreach ( $items as $i => $item ) :
								$label   = ! empty( $item['label'] ) ? $item['label'] : '';
								$eyebrow = ! empty( $item['eyebrow'] ) ? $item['eyebrow'] : sprintf( '%02d', $i + 1 );
								$attrs   = $this->render_link_attrs( isset( $item['link'] ) ? $item['link'] : [] );
								?>
								<li class="sidenav__menu-list-item">
									<a data-sidenav-link<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="sidenav__menu-link">
										<p class="sidenav__menu-link-heading"><?php echo esc_html( $label ); ?></p>
										<p class="sidenav__menu-link-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
						<?php if ( $show_socials ) : ?>
							<div class="sidenav__menu-details">
								<p data-sidenav-fade class="sidenav__button-label"><?php echo esc_html( $socials_label ); ?></p>
								<div class="sidenav__menu-socials">
									<?php foreach ( $socials as $soc ) :
										$label    = ! empty( $soc['label'] ) ? $soc['label'] : '';
										$attrs    = $this->render_link_attrs( isset( $soc['link'] ) ? $soc['link'] : [] );
										$icon     = ! empty( $soc['social_icon'] ) && is_array( $soc['social_icon'] ) ? $soc['social_icon'] : [];
										$icon_set = ! empty( $icon['value'] );
										$show_icon = $icon_set && ( 'icon' === $soc_display || 'icon_text' === $soc_display );
										$show_text = ( 'text' === $soc_display || 'icon_text' === $soc_display );
										// Icon-only needs aria-label so screen readers know the link's purpose.
										$aria = ( 'icon' === $soc_display && $label ) ? ' aria-label="' . esc_attr( $label ) . '"' : '';
										?>
										<a data-sidenav-fade<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $aria; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="sidenav__button-label">
											<?php if ( $show_icon ) : ?>
												<span class="sidenav__social-icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
											<?php endif; ?>
											<?php if ( $show_text ) : ?>
												<span class="sidenav__social-text"><?php echo esc_html( $label ); ?></span>
											<?php endif; ?>
										</a>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</nav>
			</div>
		</div>
		<?php
	}
}
