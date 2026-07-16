<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Fixed_Underlay_Navigation_Widget extends Widget_Base {

	public function get_name() {
		return 'fixed_underlay_navigation';
	}

	public function get_title() {
		return __( 'Fixed Underlay Navigation', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-menu-bar';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'fixed', 'underlay', 'navigation', 'menu', 'reveal', 'slide-page', 'offcanvas', 'gsap' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-customease', 'elementor-fixed-underlay-navigation' ];
	}

	public function get_style_depends() {
		return [ 'elementor-fixed-underlay-navigation' ];
	}

	/**
	 * Sanitize user-provided SVG. Strips <script>, <foreignObject>, on* event
	 * handlers, dan javascript: URLs. Case attribut (viewBox, dll.) tetap
	 * dipertahankan supaya render SVG tidak break.
	 */
	public function sanitize_custom_svg( $svg ) {
		if ( empty( $svg ) ) {
			return '';
		}
		$svg = trim( (string) $svg );
		$svg = preg_replace( '#<\s*script[^>]*>.*?<\s*/\s*script\s*>#is', '', $svg );
		$svg = preg_replace( '#<\s*script[^>]*/?>#i', '', $svg );
		$svg = preg_replace( '#<\s*foreignObject[^>]*>.*?<\s*/\s*foreignObject\s*>#is', '', $svg );
		$svg = preg_replace( '#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $svg );
		$svg = preg_replace( '#\s+(xlink:href|href)\s*=\s*("|\')?\s*javascript:[^"\'>\s]*("|\')?#i', '', $svg );
		return $svg;
	}

	public function default_logo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 110 25" fill="none" class="underlay-nav__logo-svg"><path class="underlay-nav__logo-main" d="M38.6539 24.1686C42.7853 24.1686 46.43 22.0917 48.6052 18.9263C49.8548 22.1497 53.0871 24.1686 57.3667 24.1686C60.4499 24.1686 63.0505 23.1833 64.7214 21.5632L64.4805 23.6683H69.7011L70.9507 12.7679L73.8518 23.6683H79.0772L81.9784 12.7679L83.2271 23.6683H88.4477L87.8886 18.7885C90.0518 22.0313 93.7424 24.1686 97.9334 24.1686C104.598 24.1686 110 18.766 110 12.1016C110 5.43732 104.596 0.0346429 97.9318 0.0346429C92.7612 0.0346429 88.3518 3.28785 86.6342 7.85749L85.7907 0.499502H80.0215L76.4629 13.8708L72.9044 0.499502H67.1351L66.3246 7.56906C66.2264 5.51224 65.382 3.64878 63.9254 2.29932C62.3021 0.795175 60.0342 0 57.3659 0C54.8659 0 52.7116 0.712193 51.1358 2.06004C49.974 3.05421 49.2135 4.33761 48.9194 5.76119C46.7933 2.32429 42.9923 0.0346429 38.6539 0.0346429C31.9896 0.0346429 26.5869 5.43732 26.5869 12.1016C26.5869 18.766 31.9896 24.1686 38.6539 24.1686ZM97.9318 5.46471C101.597 5.46471 104.569 8.43594 104.569 12.1016C104.569 15.7673 101.597 18.7386 97.9318 18.7386C94.2661 18.7386 91.2949 15.7673 91.2949 12.1016C91.2949 8.43594 94.2661 5.46471 97.9318 5.46471ZM57.3667 5.05786C59.6321 5.05786 61.0227 6.10681 61.0855 7.86393L61.1049 8.39808H66.2304L65.7019 13.0128C65.4392 12.5899 65.1275 12.1991 64.7641 11.8438C63.5685 10.6773 61.8154 9.88289 59.5524 9.48328L56.5014 8.93706C54.48 8.5729 54.0659 7.94127 54.0659 7.10501C54.0659 6.89554 54.1586 5.05705 57.3667 5.05705V5.05786ZM55.1761 14.0094L58.7709 14.6837C61.092 15.1293 61.4046 16.0711 61.4046 16.9339C61.4046 18.2963 59.8569 19.1422 57.365 19.1422C54.4059 19.1422 53.2877 17.4729 53.2289 16.0437L53.2071 15.5128H50.2278C50.5461 14.4308 50.7201 13.2868 50.7201 12.1016C50.7201 12.0452 50.7168 11.9889 50.716 11.9325C51.7876 12.95 53.2836 13.6598 55.1753 14.0094H55.1761ZM38.6539 5.46471C42.3196 5.46471 45.2908 8.43594 45.2908 12.1016C45.2908 15.7673 42.3196 18.7386 38.6539 18.7386C34.9882 18.7386 32.017 15.7673 32.017 12.1016C32.017 8.43594 34.9882 5.46471 38.6539 5.46471Z"></path><path class="underlay-nav__logo-accent" d="M16.3506 9.9554L21.6985 4.6075L19.5619 2.47092L14.214 7.81882C13.986 8.04762 13.5953 7.88569 13.5953 7.56262V0H10.5741V9.12397C10.5741 9.92478 9.92476 10.5741 9.12395 10.5741H0V13.5953H7.56261C7.88567 13.5953 8.04761 13.9861 7.8188 14.2141L2.47172 19.5619L4.6083 21.6985L9.95618 16.3506C10.1842 16.1226 10.5749 16.2838 10.5749 16.6068V24.1694H13.5961V15.0455C13.5961 14.2447 14.2454 13.5953 15.0463 13.5953H24.1702V10.5741H16.6076C16.2845 10.5741 16.1226 10.1834 16.3514 9.9554H16.3506Z"></path></svg>';
	}

	protected function register_controls() {

		/* === CONTENT: LOGO === */
		$this->start_controls_section( 'content_logo', [
			'label' => __( 'Logo', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'logo_type', [
			'label'   => __( 'Logo Type', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default SVG', 'elementor-gsap' ),
				'image'   => __( 'Image Upload', 'elementor-gsap' ),
				'svg'     => __( 'Custom SVG', 'elementor-gsap' ),
				'text'    => __( 'Text', 'elementor-gsap' ),
				'none'    => __( 'None', 'elementor-gsap' ),
			],
			'default' => 'default',
		] );

		$this->add_control( 'logo_image', [
			'label'     => __( 'Logo Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => '' ],
			'condition' => [ 'logo_type' => 'image' ],
		] );

		$this->add_control( 'logo_alt', [
			'label'     => __( 'Logo Alt Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Logo',
			'condition' => [ 'logo_type' => 'image' ],
		] );

		$this->add_control( 'logo_text', [
			'label'     => __( 'Logo Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Studio',
			'condition' => [ 'logo_type' => 'text' ],
		] );

		$this->add_control( 'logo_svg', [
			'label'       => __( 'Custom SVG Code', 'elementor-gsap' ),
			'description' => __( 'Paste kode <code>&lt;svg&gt;…&lt;/svg&gt;</code>. Gunakan <code>fill="currentColor"</code> pada path yang ingin ikut warna dari <strong>Logo Main Color</strong>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 10,
			'default'     => '',
			'condition'   => [ 'logo_type' => 'svg' ],
		] );

		$this->add_control( 'logo_svg_alt', [
			'label'     => __( 'SVG Aria Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Logo',
			'condition' => [ 'logo_type' => 'svg' ],
		] );

		$this->add_control( 'logo_link', [
			'label'     => __( 'Logo Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'logo_type!' => 'none' ],
		] );

		$this->end_controls_section();

		/* === CONTENT: TOGGLE === */
		$this->start_controls_section( 'content_toggle', [
			'label' => __( 'Toggle Button', 'elementor-gsap' ),
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
		$item_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );
		$item_rep->add_control( 'is_current', [
			'label'        => __( 'Mark as Current', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'elementor-gsap' ),
			'label_off'    => __( 'No', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'menu_items', [
			'label'       => __( 'Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $item_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Home',     'link' => [ 'url' => '#' ], 'is_current' => 'yes' ],
				[ 'label' => 'Projects', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'About',    'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Services', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'News',     'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Contact',  'link' => [ 'url' => '#' ] ],
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

		$soc_rep = new Repeater();
		$soc_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Instagram',
		] );
		$soc_rep->add_control( 'icon', [
			'label'   => __( 'Icon', 'elementor-gsap' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => '', 'library' => '' ],
		] );
		$soc_rep->add_control( 'icon_position', [
			'label'   => __( 'Icon Position', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'before' => __( 'Before Text', 'elementor-gsap' ),
				'after'  => __( 'After Text', 'elementor-gsap' ),
			],
			'default'   => 'before',
			'condition' => [ 'icon[value]!' => '' ],
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
				[ 'label' => 'Instagram', 'link' => [ 'url' => '#' ], 'icon' => [ 'value' => 'fab fa-instagram',   'library' => 'fa-brands' ], 'icon_position' => 'before' ],
				[ 'label' => 'LinkedIn',  'link' => [ 'url' => '#' ], 'icon' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ], 'icon_position' => 'before' ],
				[ 'label' => 'X/Twitter', 'link' => [ 'url' => '#' ], 'icon' => [ 'value' => 'fab fa-x-twitter',   'library' => 'fa-brands' ], 'icon_position' => 'before' ],
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: QUICK LINKS === */
		$this->start_controls_section( 'content_quick', [
			'label' => __( 'Quick Links', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_quick', [
			'label'        => __( 'Show Quick Links', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'elementor-gsap' ),
			'label_off'    => __( 'No', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'quick_label', [
			'label'     => __( 'Section Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Quick Links',
			'condition' => [ 'show_quick' => 'yes' ],
		] );

		$quick_rep = new Repeater();
		$quick_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Privacy Policy',
		] );
		$quick_rep->add_control( 'icon', [
			'label'   => __( 'Icon', 'elementor-gsap' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => '', 'library' => '' ],
		] );
		$quick_rep->add_control( 'icon_position', [
			'label'   => __( 'Icon Position', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'before' => __( 'Before Text', 'elementor-gsap' ),
				'after'  => __( 'After Text', 'elementor-gsap' ),
			],
			'default'   => 'after',
			'condition' => [ 'icon[value]!' => '' ],
		] );
		$quick_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'quick_items', [
			'label'       => __( 'Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $quick_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'condition'   => [ 'show_quick' => 'yes' ],
			'default'     => [
				[ 'label' => 'Privacy Policy',     'link' => [ 'url' => '#' ], 'icon' => [ 'value' => 'eicon-editor-external-link', 'library' => 'eicons' ], 'icon_position' => 'after' ],
				[ 'label' => 'Terms & Conditions', 'link' => [ 'url' => '#' ], 'icon' => [ 'value' => 'eicon-editor-external-link', 'library' => 'eicons' ], 'icon_position' => 'after' ],
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: BEHAVIOR === */
		$this->start_controls_section( 'content_behavior', [
			'label' => __( 'Behavior', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'behavior_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( '<strong>Cara pakai:</strong> drop widget ini sekali di template (Header / Page). Saat halaman dibuka, JS akan otomatis memindahkan widget ke <code>&lt;body&gt;</code> dan membungkus konten lain di dalam <code>[data-fun-main]</code>. Tidak perlu setup manual. Power user bisa override target slide dengan <em>Main Wrapper Selector</em> di bawah.', 'elementor-gsap' ),
			'content_classes' => 'elementor-control-field-description',
		] );

		$this->add_control( 'open_duration', [
			'label'       => __( 'Open Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi default GSAP timeline untuk tween utama. Default 0.7s sesuai demo Osmo.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.2,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.7,
		] );

		$this->add_control( 'main_selector', [
			'label'       => __( 'Main Wrapper Selector (optional)', 'elementor-gsap' ),
			'description' => __( 'Override target geser. Kosongkan untuk auto-wrap. Contoh: <code>main</code>, <code>#page</code>, <code>.site-content</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'placeholder' => 'main',
		] );

		$this->end_controls_section();

		/* === STYLE: HEADER === */
		$this->start_controls_section( 'style_header', [
			'label' => __( 'Header', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'header_padding_y', [
			'label'      => __( 'Vertical Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.5 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-header-padding-y: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'header_padding_x', [
			'label'      => __( 'Horizontal Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.5 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-header-padding-x: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: LOGO === */
		$this->start_controls_section( 'style_logo', [
			'label' => __( 'Logo', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'logo_width', [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 2, 'max' => 20, 'step' => 0.1 ],
				'px' => [ 'min' => 30, 'max' => 300 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 6.875 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-logo-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'logo_type!' => 'text' ],
		] );

		$this->add_control( 'logo_color', [
			'label'       => __( 'Logo Main Color', 'elementor-gsap' ),
			'description' => __( 'Untuk <strong>Custom SVG</strong>, warna ini hanya berlaku pada path yang menggunakan <code>fill="currentColor"</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f4f4f4',
			'selectors'   => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-logo-color: {{VALUE}};',
			],
			'condition'   => [ 'logo_type' => [ 'default', 'text', 'svg' ] ],
		] );

		$this->add_control( 'logo_accent', [
			'label'     => __( 'Logo Accent Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f85931',
			'selectors' => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-logo-accent: {{VALUE}};',
			],
			'condition' => [ 'logo_type' => 'default' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'logo_text_typography',
			'label'     => __( 'Text Typography', 'elementor-gsap' ),
			'selector'  => '{{WRAPPER}} .fun-underlay-nav .underlay-nav__logo-text',
			'condition' => [ 'logo_type' => 'text' ],
		] );

		$this->end_controls_section();

		/* === STYLE: TOGGLE BUTTON === */
		$this->start_controls_section( 'style_toggle', [
			'label' => __( 'Toggle Button', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'toggle_color_closed', [
			'label'       => __( 'Color (Open)', 'elementor-gsap' ),
			'description' => __( 'Warna tombol saat label "Open" tampil (menu tertutup). Default putih cocok untuk hero gelap.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#ffffff',
			'selectors'   => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-toggle-color-closed: {{VALUE}};',
			],
		] );

		$this->add_control( 'toggle_color_open', [
			'label'       => __( 'Color (Close)', 'elementor-gsap' ),
			'description' => __( 'Warna tombol saat label "Close" tampil (menu terbuka). Cocokkan dengan warna teks menu.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#201d1d',
			'selectors'   => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-toggle-color-open: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'toggle_label_size', [
			'label'      => __( 'Label Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 8, 'max' => 48 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-toggle-label-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'toggle_icon_size', [
			'label'      => __( 'Icon Box Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 8, 'max' => 64 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.5 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-toggle-icon-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'toggle_bar_thickness', [
			'label'      => __( 'Icon Bar Thickness', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.05, 'max' => 0.4, 'step' => 0.005 ],
				'px' => [ 'min' => 1, 'max' => 8 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.125 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-toggle-bar-thickness: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'toggle_typography',
			'selector' => '{{WRAPPER}} .fun-underlay-nav .underlay-nav__toggle-label',
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
			'size_units' => [ 'em', 'px', 'vw' ],
			'range'      => [
				'em' => [ 'min' => 18, 'max' => 60, 'step' => 0.5 ],
				'px' => [ 'min' => 280, 'max' => 900 ],
				'vw' => [ 'min' => 30, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 30 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-menu-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'menu_bg', [
			'label'       => __( 'Background', 'elementor-gsap' ),
			'description' => __( 'Warna panel menu. Cocokkan dengan background halaman supaya menu menyatu (default Osmo Webflow).', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f4f4f4',
			'selectors'   => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-menu-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'menu_text', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#201d1d',
			'selectors' => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-menu-text: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'menu_padding_top', [
			'label'      => __( 'Top Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 2, 'max' => 14, 'step' => 0.1 ],
				'px' => [ 'min' => 40, 'max' => 240 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 7.5 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-menu-padding-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'menu_padding_x', [
			'label'      => __( 'Horizontal Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-menu-padding-x: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'menu_padding_bottom', [
			'label'      => __( 'Bottom Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 160 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-menu-padding-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: MENU LINKS (LARGE) === */
		$this->start_controls_section( 'style_links_large', [
			'label' => __( 'Menu Links (Large)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'link_large_size', [
			'label'      => __( 'Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 6, 'step' => 0.05 ],
				'px' => [ 'min' => 16, 'max' => 96 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3.25 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-large-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'link_large_line_height', [
			'label'      => __( 'Line Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em' ],
			'range'      => [
				'em' => [ 'min' => 0.7, 'max' => 1.6, 'step' => 0.01 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.9 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-large-line-height: {{SIZE}};',
			],
		] );

		$this->add_responsive_control( 'link_large_letter_spacing', [
			'label'      => __( 'Letter Spacing', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => -0.1, 'max' => 0.1, 'step' => 0.005 ],
				'px' => [ 'min' => -4, 'max' => 4 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => -0.04 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-large-letter-spacing: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'link_large_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-large-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'link_large_padding_y', [
			'label'      => __( 'Padding Y', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-large-padding-y: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'link_large_padding_x', [
			'label'      => __( 'Padding X', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-large-padding-x: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'current_bg', [
			'label'     => __( 'Current Item Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f85931',
			'selectors' => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-current-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'current_color', [
			'label'     => __( 'Current Item Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ededed',
			'selectors' => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-current-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'link_large_typography',
			'selector' => '{{WRAPPER}} .fun-underlay-nav .underlay-nav__link-label',
		] );

		$this->end_controls_section();

		/* === STYLE: BOTTOM (SOCIALS + QUICK LINKS) === */
		$this->start_controls_section( 'style_bottom', [
			'label' => __( 'Bottom Section', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'link_small_size', [
			'label'      => __( 'Small Link Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 8, 'max' => 32 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-small-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'link_small_faded_opacity', [
			'label'      => __( 'Faded Label Opacity', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ],
			],
			'default'    => [ 'unit' => 'px', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-link-small-faded-opacity: {{SIZE}};',
			],
		] );

		$this->add_control( 'bottom_border_color', [
			'label'     => __( 'Top Border Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-bottom-border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'link_small_typography',
			'selector' => '{{WRAPPER}} .fun-underlay-nav .underlay-nav__link-small',
		] );

		$this->end_controls_section();

		/* === STYLE: OVERLAY === */
		$this->start_controls_section( 'style_overlay', [
			'label' => __( 'Overlay', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'overlay_dark', [
			'label'     => __( 'Dark Layer Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.3)',
			'selectors' => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-overlay-dark-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'overlay_border_height', [
			'label'      => __( 'Border Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-overlay-border-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'overlay_corner_size', [
			'label'      => __( 'Corner Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .fun-underlay-nav' => '--fun-overlay-corner-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render_link_attrs( $link ) {
		$url    = ! empty( $link['url'] ) ? $link['url'] : '#';
		$target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$rel    = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';
		return ' href="' . esc_url( $url ) . '"' . $target . $rel;
	}

	protected function is_edit_mode() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return false;
		}
		$plugin = \Elementor\Plugin::$instance;
		if ( $plugin && isset( $plugin->editor ) && method_exists( $plugin->editor, 'is_edit_mode' ) ) {
			return (bool) $plugin->editor->is_edit_mode();
		}
		return false;
	}

	protected function render() {
		$s             = $this->get_settings_for_display();
		$logo_type     = ! empty( $s['logo_type'] ) ? $s['logo_type'] : 'default';
		$open_label    = ! empty( $s['open_label'] ) ? $s['open_label'] : 'Menu';
		$close_label   = ! empty( $s['close_label'] ) ? $s['close_label'] : 'Close';
		$items         = ! empty( $s['menu_items'] ) ? $s['menu_items'] : [];
		$show_socials  = ! empty( $s['show_socials'] ) && 'yes' === $s['show_socials'];
		$socials_label = ! empty( $s['socials_label'] ) ? $s['socials_label'] : 'Socials';
		$socials       = ! empty( $s['socials_items'] ) ? $s['socials_items'] : [];
		$show_quick    = ! empty( $s['show_quick'] ) && 'yes' === $s['show_quick'];
		$quick_label   = ! empty( $s['quick_label'] ) ? $s['quick_label'] : 'Quick Links';
		$quick         = ! empty( $s['quick_items'] ) ? $s['quick_items'] : [];
		$duration      = isset( $s['open_duration'] ) && '' !== $s['open_duration'] ? floatval( $s['open_duration'] ) : 0.7;
		$main_sel      = isset( $s['main_selector'] ) ? trim( $s['main_selector'] ) : '';
		$root_classes  = 'fun-underlay-nav' . ( $this->is_edit_mode() ? ' fun-underlay-nav--editor' : '' );
		?>
		<div
			class="<?php echo esc_attr( $root_classes ); ?>"
			data-fun-root
			data-fun-duration="<?php echo esc_attr( $duration ); ?>"
			data-fun-main-selector="<?php echo esc_attr( $main_sel ); ?>"
			data-fun-skip-wrap
		>
			<header class="underlay-nav__header">
				<div class="underlay-nav__bar">
					<div class="underlay-nav__container">
						<?php if ( 'none' !== $logo_type ) :
							$logo_attrs   = $this->render_link_attrs( isset( $s['logo_link'] ) ? $s['logo_link'] : [] );
							$logo_modifier = '';
							if ( 'text' === $logo_type ) {
								$logo_modifier = ' underlay-nav__logo--text';
							} elseif ( 'svg' === $logo_type ) {
								$logo_modifier = ' underlay-nav__logo--svg';
							}
							$logo_classes = 'underlay-nav__logo' . $logo_modifier;
							?>
							<a<?php echo $logo_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $logo_classes ); ?>">
								<?php if ( 'image' === $logo_type && ! empty( $s['logo_image']['url'] ) ) :
									$alt = ! empty( $s['logo_alt'] ) ? $s['logo_alt'] : '';
									?>
									<img src="<?php echo esc_url( $s['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
								<?php elseif ( 'text' === $logo_type ) :
									$logo_text = isset( $s['logo_text'] ) ? $s['logo_text'] : '';
									?>
									<span class="underlay-nav__logo-text"><?php echo esc_html( $logo_text ); ?></span>
								<?php elseif ( 'svg' === $logo_type ) :
									$svg_code  = isset( $s['logo_svg'] ) ? $this->sanitize_custom_svg( $s['logo_svg'] ) : '';
									$svg_label = isset( $s['logo_svg_alt'] ) ? $s['logo_svg_alt'] : '';
									if ( '' !== $svg_code ) :
										?>
										<span class="underlay-nav__logo-svg-custom" role="img"<?php echo '' !== $svg_label ? ' aria-label="' . esc_attr( $svg_label ) . '"' : ' aria-hidden="true"'; ?>><?php
											echo $svg_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?></span>
									<?php endif;
								else :
									echo $this->default_logo_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								endif; ?>
							</a>
						<?php else : ?>
							<span class="underlay-nav__logo" aria-hidden="true"></span>
						<?php endif; ?>

						<button type="button" data-underlay-nav-toggle aria-expanded="false" aria-label="open menu" class="underlay-nav__toggle">
							<span class="underlay-nav__toggle-text">
								<span class="underlay-nav__toggle-label"><?php echo esc_html( $open_label ); ?></span>
								<span class="underlay-nav__toggle-label"><?php echo esc_html( $close_label ); ?></span>
							</span>
							<span class="underlay-nav__toggle-icon" aria-hidden="true">
								<span class="underlay-nav__toggle-bar"></span>
								<span class="underlay-nav__toggle-bar"></span>
							</span>
						</button>
					</div>
				</div>
			</header>

			<nav data-underlay-nav-menu class="underlay-nav__menu" aria-label="<?php echo esc_attr__( 'Main menu', 'elementor-gsap' ); ?>">
				<div class="underlay-nav__inner">
					<ul class="underlay-nav__list">
						<?php foreach ( $items as $item ) :
							$label     = ! empty( $item['label'] ) ? $item['label'] : '';
							$attrs     = $this->render_link_attrs( isset( $item['link'] ) ? $item['link'] : [] );
							$current   = ! empty( $item['is_current'] ) && 'yes' === $item['is_current'];
							$cls       = 'underlay-nav__link-large' . ( $current ? ' is--current' : '' );
							$aria_curr = $current ? ' aria-current="page"' : '';
							?>
							<li data-reveal-l>
								<a<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $aria_curr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $cls ); ?>">
									<span class="underlay-nav__link-label"><?php echo esc_html( $label ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php if ( $show_socials || $show_quick ) : ?>
						<div class="underlay-nav__bottom">
							<?php if ( $show_socials ) : ?>
								<div class="underlay-nav__bottom-col">
									<div data-reveal-s>
										<span class="underlay-nav__link-small is--faded"><?php echo esc_html( $socials_label ); ?></span>
									</div>
									<ul class="underlay-nav__list is--small">
										<?php foreach ( $socials as $soc ) :
											$label    = ! empty( $soc['label'] ) ? $soc['label'] : '';
											$attrs    = $this->render_link_attrs( isset( $soc['link'] ) ? $soc['link'] : [] );
											$icon     = ! empty( $soc['icon'] ) && is_array( $soc['icon'] ) ? $soc['icon'] : [];
											$has_icon = ! empty( $icon['value'] );
											$icon_pos = ! empty( $soc['icon_position'] ) ? $soc['icon_position'] : 'before';
											$a_cls    = 'underlay-nav__link-small' . ( $has_icon ? ' has--icon is--icon-' . $icon_pos : '' );
											?>
											<li data-reveal-s>
												<a<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $a_cls ); ?>">
													<?php if ( $has_icon ) : ?>
														<span class="underlay-nav__link-small-icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
													<?php endif; ?>
													<span class="underlay-nav__link-small-text"><?php echo esc_html( $label ); ?></span>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<?php if ( $show_quick ) : ?>
								<div class="underlay-nav__bottom-col">
									<div data-reveal-s>
										<span class="underlay-nav__link-small is--faded"><?php echo esc_html( $quick_label ); ?></span>
									</div>
									<ul class="underlay-nav__list is--small">
										<?php foreach ( $quick as $q ) :
											$label    = ! empty( $q['label'] ) ? $q['label'] : '';
											$attrs    = $this->render_link_attrs( isset( $q['link'] ) ? $q['link'] : [] );
											$icon     = ! empty( $q['icon'] ) && is_array( $q['icon'] ) ? $q['icon'] : [];
											$has_icon = ! empty( $icon['value'] );
											$icon_pos = ! empty( $q['icon_position'] ) ? $q['icon_position'] : 'after';
											$a_cls    = 'underlay-nav__link-small' . ( $has_icon ? ' has--icon is--icon-' . $icon_pos : '' );
											?>
											<li data-reveal-s>
												<a<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $a_cls ); ?>">
													<?php if ( $has_icon ) : ?>
														<span class="underlay-nav__link-small-icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
													<?php endif; ?>
													<span class="underlay-nav__link-small-text"><?php echo esc_html( $label ); ?></span>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<div class="underlay-nav__bottom-border"></div>
						</div>
					<?php endif; ?>
				</div>
			</nav>

			<div data-underlay-nav-overlay class="underlay-nav__overlay" aria-hidden="true">
				<div class="underlay-nav__dark"></div>
				<div class="underlay-nav__borders">
					<div class="underlay-nav__border-row">
						<div class="underlay-nav__border"></div>
						<div class="underlay-nav__corner"></div>
					</div>
					<div class="underlay-nav__border-row">
						<div class="underlay-nav__corner is--bottom"></div>
						<div class="underlay-nav__border"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
