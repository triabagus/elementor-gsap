<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Multilevel_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'multilevel_nav';
	}

	public function get_title() {
		return __( 'Multilevel Navigation', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'menu', 'multilevel', 'dropdown', 'mega', 'header', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-multilevel-nav' ];
	}

	public function get_style_depends() {
		return [ 'elementor-multilevel-nav' ];
	}

	public function sanitize_custom_svg( $svg ) {
		if ( empty( $svg ) ) return '';
		$svg = trim( (string) $svg );
		$svg = preg_replace( '#<\s*script[^>]*>.*?<\s*/\s*script\s*>#is', '', $svg );
		$svg = preg_replace( '#<\s*script[^>]*/?>#i', '', $svg );
		$svg = preg_replace( '#<\s*foreignObject[^>]*>.*?<\s*/\s*foreignObject\s*>#is', '', $svg );
		$svg = preg_replace( '#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $svg );
		$svg = preg_replace( '#\s+(xlink:href|href)\s*=\s*("|\')?\s*javascript:[^"\'>\s]*("|\')?#i', '', $svg );
		return $svg;
	}

	public function default_logo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 108 24" fill="none"><path d="M98.1531 8.07867C97.7723 8.45665 97.1213 8.18895 97.1213 7.65441V0H94.7037V8.99999C94.7037 9.9941 93.8918 10.8 92.8904 10.8H83.8242V13.2H91.5349C92.0734 13.2 92.3431 13.8463 91.9623 14.2242L86.5959 19.5514L88.3055 21.2485L93.6719 15.9213C94.0515 15.5445 94.6998 15.8095 94.7037 16.3408V24L97.1213 24L97.1213 15C97.1213 14.0059 97.9331 13.2 98.9345 13.2H108.001V10.8H100.285C99.7515 10.7962 99.4846 10.1564 99.8593 9.77907L99.8626 9.77574L105.229 4.44852L103.519 2.75149L98.1531 8.07867Z" fill="currentColor"></path><path d="M9.75885 22.3068C3.80901 22.3068 0 17.9736 0 12.0396C0 6.10564 3.80901 1.80005 9.75885 1.80005C15.7087 1.80005 19.5177 6.10564 19.5177 12.0396C19.5177 17.9736 15.7087 22.3068 9.75885 22.3068ZM3.19735 12.0396C3.19735 16.0968 5.06015 19.6848 9.75885 19.6848C14.4576 19.6848 16.3204 16.0968 16.3204 12.0396C16.3204 7.98244 14.4576 4.42205 9.75885 4.42205C5.06015 4.42205 3.19735 7.98244 3.19735 12.0396Z" fill="currentColor"></path><path d="M27.5934 22.3068C23.2839 22.3068 21.1431 20.0988 21.0597 17.2284H23.8122C23.9234 18.8568 24.9243 20.1264 27.5656 20.1264C29.9566 20.1264 30.5961 19.0776 30.5961 18.0564C30.5961 16.29 28.7055 16.0968 26.8705 15.7104C24.396 15.1308 21.5601 14.4132 21.5601 11.4876C21.5601 9.05884 23.5342 7.43044 26.9539 7.43044C30.8463 7.43044 32.7091 9.50044 32.9038 11.9292H30.1513C29.9566 10.8528 29.3728 9.61084 27.0095 9.61084C25.1745 9.61084 24.396 10.3284 24.396 11.3772C24.396 12.84 25.9808 12.978 27.9826 13.4196C30.5961 14.0268 33.432 14.772 33.432 17.9184C33.432 20.6508 31.319 22.3068 27.5934 22.3068Z" fill="currentColor"></path><path d="M44.0884 12.8952C44.0884 11.0184 43.6992 9.74884 41.6139 9.74884C39.5843 9.74884 38.3054 11.1564 38.3054 13.2816V21.9204H35.5807V7.84444H38.3054V9.61084H38.361C39.1117 8.53444 40.4184 7.43044 42.5592 7.43044C44.5332 7.43044 45.7566 8.31364 46.3126 9.88684H46.3682C47.3969 8.53444 48.8427 7.43044 51.0113 7.43044C53.875 7.43044 55.3208 9.14164 55.3208 12.15V21.9204H52.5961V12.8952C52.5961 11.0184 52.2069 9.74884 50.1216 9.74884C48.092 9.74884 46.8131 11.1564 46.8131 13.2816V21.9204H44.0884V12.8952Z" fill="currentColor"></path><path d="M64.6455 22.3344C60.2248 22.3344 57.5557 19.2984 57.5557 14.8824C57.5557 10.494 60.2248 7.40284 64.6733 7.40284C69.0661 7.40284 71.7352 10.4664 71.7352 14.8548C71.7352 19.2708 69.0661 22.3344 64.6455 22.3344ZM60.3916 14.8824C60.3916 17.808 61.7261 20.0988 64.6733 20.0988C67.5648 20.0988 68.8993 17.808 68.8993 14.8824C68.8993 11.9292 67.5648 9.66604 64.6733 9.66604C61.7261 9.66604 60.3916 11.9292 60.3916 14.8824Z" fill="currentColor"></path></svg>';
	}

	public function chevron_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 17 10" fill="none"><path d="M1.5 1.5L8.5 8.5L15.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}

	public function arrow_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 16 16" fill="none" class="egsap-mln__icon"><path d="M9.33398 12.6666L14.0007 7.99992L9.33398 3.33325" stroke="currentColor" stroke-miterlimit="10"/><path d="M14.0007 8H1.33398" stroke="currentColor" stroke-miterlimit="10"/></svg>';
	}

	private function render_link_attrs( $link ) {
		if ( empty( $link ) || ! is_array( $link ) ) return ' href="#"';
		$url    = ! empty( $link['url'] ) ? $link['url'] : '#';
		$target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$rel    = ! empty( $link['nofollow'] ) ? ' rel="nofollow noopener"' : ( ! empty( $link['is_external'] ) ? ' rel="noopener"' : '' );
		return ' href="' . esc_url( $url ) . '"' . $target . $rel;
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                       CONTENT — BAR                        */
		/* ========================================================= */
		$this->start_controls_section( 'content_bar', [
			'label' => __( 'Bar (Logo)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'logo_type', [
			'label'   => __( 'Logo Type', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'image' => __( 'Image', 'elementor-gsap' ),
				'svg'   => __( 'Custom SVG', 'elementor-gsap' ),
			],
			'default' => 'svg',
		] );

		$this->add_control( 'logo_image', [
			'label'     => __( 'Logo Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			'condition' => [ 'logo_type' => 'image' ],
		] );

		$this->add_control( 'logo_svg', [
			'label'       => __( 'Logo SVG', 'elementor-gsap' ),
			'description' => __( 'Paste inline SVG. Kosongkan untuk pakai default.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 4,
			'default'     => '',
			'condition'   => [ 'logo_type' => 'svg' ],
		] );

		$this->add_control( 'logo_link', [
			'label'   => __( 'Logo Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    CONTENT — MENU ITEMS                    */
		/* ========================================================= */
		$this->start_controls_section( 'content_menu', [
			'label' => __( 'Menu Items (Top Bar)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$bar_rep = new Repeater();
		$bar_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Menu',
		] );
		$bar_rep->add_control( 'is_dropdown', [
			'label'        => __( 'Has Dropdown', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$bar_rep->add_control( 'dropdown_id', [
			'label'       => __( 'Dropdown ID', 'elementor-gsap' ),
			'description' => __( 'Kunci unik untuk grouping. Cocokkan dengan yang di section "Dropdown Items".', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'products',
			'condition'   => [ 'is_dropdown' => 'yes' ],
		] );
		$bar_rep->add_control( 'link', [
			'label'     => __( 'Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'is_dropdown!' => 'yes' ],
		] );

		$this->add_control( 'bar_items', [
			'label'       => __( 'Menu Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $bar_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Products', 'is_dropdown' => 'yes', 'dropdown_id' => 'products' ],
				[ 'label' => 'Services', 'is_dropdown' => 'yes', 'dropdown_id' => 'services' ],
				[ 'label' => 'About',    'is_dropdown' => '',    'link' => [ 'url' => '#' ] ],
				[ 'label' => 'News',     'is_dropdown' => '',    'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    CONTENT — DROPDOWN                      */
		/* ========================================================= */
		$this->start_controls_section( 'content_dropdown', [
			'label' => __( 'Dropdown Items', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'dropdown_help', [
			'type'    => Controls_Manager::RAW_HTML,
			'raw'     => __( '<strong>Cara pakai:</strong> tiap row = satu <em>item</em> di dropdown. Cocokkan <code>Dropdown ID</code> dengan yang di Menu Items. Ada 2 style: <code>Image Card</code> (dengan gambar besar) atau <code>Static</code> (text saja, background bervariasi saat hover).', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$dd_rep = new Repeater();
		$dd_rep->add_control( 'dropdown_id', [
			'label'   => __( 'Dropdown ID', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'products',
		] );
		$dd_rep->add_control( 'item_type', [
			'label'   => __( 'Item Style', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'image_card' => __( 'Image Card', 'elementor-gsap' ),
				'static'     => __( 'Static (text-only)', 'elementor-gsap' ),
			],
			'default' => 'image_card',
		] );
		$dd_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Item',
		] );
		$dd_rep->add_control( 'image', [
			'label'     => __( 'Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			'condition' => [ 'item_type' => 'image_card' ],
		] );
		$dd_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'dropdown_items', [
			'label'       => __( 'Dropdown Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $dd_rep->get_controls(),
			'title_field' => '{{{ dropdown_id }}} → {{{ label }}}',
			'default'     => [
				[ 'dropdown_id' => 'products', 'item_type' => 'image_card', 'label' => 'Olaf',  'image' => [ 'url' => 'https://cdn.prod.website-files.com/686e38e4cff3784e230d2c25/686e567e67f6622d88a2e1c6_nav-img-1.avif' ], 'link' => [ 'url' => '#' ] ],
				[ 'dropdown_id' => 'products', 'item_type' => 'image_card', 'label' => 'Stijn', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/686e38e4cff3784e230d2c25/686e567fc4fcdd19e150e190_nav-img-2.avif' ], 'link' => [ 'url' => '#' ] ],
				[ 'dropdown_id' => 'products', 'item_type' => 'image_card', 'label' => 'Kees',  'image' => [ 'url' => 'https://cdn.prod.website-files.com/686e38e4cff3784e230d2c25/686e567e2fca1779de2a029c_nav-img-3.avif' ], 'link' => [ 'url' => '#' ] ],
				[ 'dropdown_id' => 'products', 'item_type' => 'image_card', 'label' => 'Nelis', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/686e38e4cff3784e230d2c25/686e567fc4fcdd19e150e18b_nav-img-4.avif' ], 'link' => [ 'url' => '#' ] ],
				[ 'dropdown_id' => 'services', 'item_type' => 'static', 'label' => 'Custom Pieces', 'link' => [ 'url' => '#' ] ],
				[ 'dropdown_id' => 'services', 'item_type' => 'static', 'label' => 'Renovation',    'link' => [ 'url' => '#' ] ],
				[ 'dropdown_id' => 'services', 'item_type' => 'static', 'label' => 'Wholesale',     'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     CONTENT — ACTIONS                      */
		/* ========================================================= */
		$this->start_controls_section( 'content_actions', [
			'label' => __( 'Actions (CTA)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'secondary_enable', [
			'label'        => __( 'Show Secondary Button', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->add_control( 'secondary_label', [
			'label'     => __( 'Secondary Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Contact Us',
			'condition' => [ 'secondary_enable' => 'yes' ],
		] );
		$this->add_control( 'secondary_link', [
			'label'     => __( 'Secondary Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'secondary_enable' => 'yes' ],
		] );
		$this->add_control( 'secondary_hide_mobile', [
			'label'        => __( 'Hide on Tablet & Mobile', 'elementor-gsap' ),
			'description'  => __( 'Sembunyikan tombol secondary di viewport ≤991px.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [ 'secondary_enable' => 'yes' ],
		] );

		$this->add_control( 'primary_enable', [
			'label'        => __( 'Show Primary Button', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );
		$this->add_control( 'primary_label', [
			'label'     => __( 'Primary Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Enquire Now',
			'condition' => [ 'primary_enable' => 'yes' ],
		] );
		$this->add_control( 'primary_link', [
			'label'     => __( 'Primary Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'primary_enable' => 'yes' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — LAYOUT                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_layout', [
			'label' => __( 'Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'z_index', [
			'label'   => __( 'z-index', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 100,
			'min'     => 0,
			'max'     => 100000,
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-z: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'container_pad_x', [
			'label'      => __( 'Container Horizontal Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-container-pad-x: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'top_pad', [
			'label'      => __( 'Top Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 64 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-top-pad: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'bg_height', [
			'label'       => __( 'Bar BG Expanded Height', 'elementor-gsap' ),
			'description' => __( 'Tinggi bar background saat dropdown terbuka. Boleh pakai <code>calc()</code>. Default menyesuaikan tinggi image card + padding.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'calc(20em + calc(2em + 3em + 2.5em + 3em))',
			'selectors'   => [ '{{WRAPPER}} .egsap-mln' => '--mln-bg-height: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'bar_radius', [
			'label'      => __( 'Bar BG Bottom Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-bar-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'logo_width', [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 3, 'max' => 15, 'step' => 0.05 ], 'px' => [ 'min' => 40, 'max' => 240 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 6.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-logo-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'card_height', [
			'label'      => __( 'Image Card Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 10, 'max' => 30 ], 'px' => [ 'min' => 160, 'max' => 480 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 20 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-card-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — COLORS                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_colors', [
			'label' => __( 'Colors', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'text_color', [
			'label'   => __( 'Default Text Color', 'elementor-gsap' ),
			'description' => __( 'Warna text saat dropdown belum terbuka (biasanya white untuk over-image / hero).', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-text: {{VALUE}};' ],
		] );

		$this->add_control( 'text_open_color', [
			'label'   => __( 'Open State Text Color', 'elementor-gsap' ),
			'description' => __( 'Warna text saat bar background muncul (dropdown open).', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#2B1D15',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-text-open: {{VALUE}};' ],
		] );

		$this->add_control( 'bar_bg', [
			'label'   => __( 'Bar Background Fill', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-bar-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'page_bg', [
			'label'   => __( 'Page Backdrop (Behind Nav)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.3)',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-page-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'card_bg', [
			'label'   => __( 'Dropdown Card Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#EBE7E4',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-card-bg: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->add_control( 'card_static_hover', [
			'label'   => __( 'Static Item Hover Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#D7D1CD',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-card-static-hover: {{VALUE}};' ],
		] );

		$this->add_control( 'card_label_color', [
			'label'       => __( 'Image Card Label Color', 'elementor-gsap' ),
			'description' => __( 'Warna text label di image card (default white — kontras dengan overlay gelap di bawah).', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#FFFFFF',
			'selectors'   => [ '{{WRAPPER}} .egsap-mln' => '--mln-card-label: {{VALUE}};' ],
		] );

		$this->add_control( 'card_static_label_color', [
			'label'       => __( 'Static Item Label Color', 'elementor-gsap' ),
			'description' => __( 'Warna text label untuk item Static (text-only).', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#2B1D15',
			'selectors'   => [ '{{WRAPPER}} .egsap-mln' => '--mln-card-static-label: {{VALUE}};' ],
		] );

		$this->add_control( 'hover_bg', [
			'label'       => __( 'Dropdown Toggle Hover BG', 'elementor-gsap' ),
			'description' => __( 'Background trigger button (Products/Services dll) saat hover / open.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#EBE7E4',
			'selectors'   => [ '{{WRAPPER}} .egsap-mln' => '--mln-hover-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'bubble_bg', [
			'label'   => __( 'Arrow Bubble Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#2B1D15',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-bubble-bg: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->add_control( 'bubble_fg', [
			'label'   => __( 'Arrow Bubble Icon Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-bubble-fg: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — BUTTONS                      */
		/* ========================================================= */
		$this->start_controls_section( 'style_buttons', [
			'label' => __( 'CTA Buttons', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'btn_radius', [
			'label'      => __( 'Button Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-btn-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'btn_height', [
			'label'      => __( 'Button Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 2, 'max' => 5, 'step' => 0.05 ], 'px' => [ 'min' => 32, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-btn-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'btn_pad_x', [
			'label'      => __( 'Button Horizontal Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-mln' => '--mln-btn-pad-x: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'primary_bg', [
			'label'   => __( 'Primary BG (Default State)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-btn-primary-bg: {{VALUE}}; --mln-btn-primary-border: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->add_control( 'primary_text', [
			'label'   => __( 'Primary Text (Default State)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#2B1D15',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-btn-primary-text: {{VALUE}};' ],
		] );

		$this->add_control( 'primary_bg_open', [
			'label'   => __( 'Primary BG (Open State)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#2B1D15',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-btn-primary-bg-open: {{VALUE}};' ],
		] );

		$this->add_control( 'primary_text_open', [
			'label'   => __( 'Primary Text (Open State)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-mln' => '--mln-btn-primary-text-open: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'btn_typography',
			'label'    => __( 'Button Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-mln__button',
			'separator' => 'before',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — TYPOGRAPHY                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Typography', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'font_family', [
			'label'       => __( 'Base Font Family', 'elementor-gsap' ),
			'description' => __( 'Pakai default <code>PP Neue Montreal, Arial, sans-serif</code> atau override di sini. Group Typography di bawah override berdasarkan elemen spesifik.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => "'PP Neue Montreal', Arial, sans-serif",
			'selectors'   => [ '{{WRAPPER}} .egsap-mln' => '--mln-font: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'menu_link_typography',
			'label'     => __( 'Menu Link Typography', 'elementor-gsap' ),
			'selector'  => '{{WRAPPER}} .egsap-mln__link-label',
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'dropdown_label_typography',
			'label'    => __( 'Dropdown Label Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-mln__dropdown-link-label',
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'z_index'           => '--mln-z',
			'bg_height'         => '--mln-bg-height',
			'font_family'       => '--mln-font',
			'text_color'        => '--mln-text',
			'text_open_color'   => '--mln-text-open',
			'bar_bg'            => '--mln-bar-bg',
			'page_bg'           => '--mln-page-bg',
			'card_bg'                 => '--mln-card-bg',
			'card_static_hover'       => '--mln-card-static-hover',
			'card_label_color'        => '--mln-card-label',
			'card_static_label_color' => '--mln-card-static-label',
			'hover_bg'                => '--mln-hover-bg',
			'bubble_bg'         => '--mln-bubble-bg',
			'bubble_fg'         => '--mln-bubble-fg',
			'primary_text'      => '--mln-btn-primary-text',
			'primary_bg_open'   => '--mln-btn-primary-bg-open',
			'primary_text_open' => '--mln-btn-primary-text-open',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		// primary_bg drives both bg + border via same selector; still emit both here.
		if ( ! empty( $s['primary_bg'] ) ) {
			$m[] = '--mln-btn-primary-bg: ' . $s['primary_bg'] . ';';
			$m[] = '--mln-btn-primary-border: ' . $s['primary_bg'] . ';';
		}
		$slider_map = [
			'container_pad_x' => '--mln-container-pad-x',
			'top_pad'         => '--mln-top-pad',
			'bar_radius'      => '--mln-bar-radius',
			'logo_width'      => '--mln-logo-width',
			'card_height'     => '--mln-card-height',
			'btn_radius'      => '--mln-btn-radius',
			'btn_height'      => '--mln-btn-height',
			'btn_pad_x'       => '--mln-btn-pad-x',
		];
		foreach ( $slider_map as $key => $var ) {
			if ( isset( $s[ $key ]['size'], $s[ $key ]['unit'] ) && '' !== $s[ $key ]['size'] ) {
				$m[] = $var . ': ' . $s[ $key ]['size'] . $s[ $key ]['unit'] . ';';
			}
		}
		if ( empty( $m ) ) return '';
		return ' style="' . esc_attr( implode( ' ', $m ) ) . '"';
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$bar_items      = ! empty( $s['bar_items'] )      && is_array( $s['bar_items'] )      ? $s['bar_items']      : [];
		$dropdown_items = ! empty( $s['dropdown_items'] ) && is_array( $s['dropdown_items'] ) ? $s['dropdown_items'] : [];

		/* Group dropdown items by dropdown_id */
		$grouped = [];
		foreach ( $dropdown_items as $it ) {
			$id = isset( $it['dropdown_id'] ) ? sanitize_title( $it['dropdown_id'] ) : '';
			if ( '' === $id ) continue;
			if ( ! isset( $grouped[ $id ] ) ) $grouped[ $id ] = [];
			$grouped[ $id ][] = $it;
		}

		$is_edit = false;
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$plugin = \Elementor\Plugin::$instance;
			if ( isset( $plugin->editor ) && method_exists( $plugin->editor, 'is_edit_mode' ) && $plugin->editor->is_edit_mode() ) {
				$is_edit = true;
			}
			if ( isset( $plugin->preview ) && method_exists( $plugin->preview, 'is_preview_mode' ) && $plugin->preview->is_preview_mode() ) {
				$is_edit = true;
			}
		}

		$style_attr  = $is_edit ? '' : $this->build_style_attr( $s );
		$editor_flag = $is_edit ? ' data-egsap-mln-editor="1"' : '';

		/* Logo HTML */
		$logo_html = '';
		if ( 'image' === ( $s['logo_type'] ?? 'svg' ) && ! empty( $s['logo_image']['url'] ) ) {
			$logo_html = '<img src="' . esc_url( $s['logo_image']['url'] ) . '" alt="" />';
		} else {
			$svg = ! empty( $s['logo_svg'] ) ? $this->sanitize_custom_svg( $s['logo_svg'] ) : $this->default_logo_svg();
			$logo_html = $svg;
		}

		$chevron = $this->chevron_svg();
		$arrow   = $this->arrow_svg();
		?>
		<nav
			class="egsap-mln"
			data-egsap-mln
			data-egsap-mln-status="closed"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div class="egsap-mln__container">
				<div class="egsap-mln__bg"></div>
				<div class="egsap-mln__inner">
					<a<?php echo $this->render_link_attrs( $s['logo_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-mln__logo">
						<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>

					<div class="egsap-mln__center">
						<ul class="egsap-mln__center-list">
							<?php foreach ( $bar_items as $item ) :
								$label = isset( $item['label'] ) ? $item['label'] : '';
								$is_dd = 'yes' === ( $item['is_dropdown'] ?? '' );
								$dd_id = isset( $item['dropdown_id'] ) ? sanitize_title( $item['dropdown_id'] ) : '';
								?>
								<li>
									<?php if ( $is_dd && $dd_id ) : ?>
										<button type="button" data-egsap-mln-dropdown-toggle="closed" class="egsap-mln__link">
											<span class="egsap-mln__link-label"><?php echo esc_html( $label ); ?></span>
											<span class="egsap-mln__link-dropdown-icon"><?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
										</button>
										<?php if ( ! empty( $grouped[ $dd_id ] ) ) : ?>
											<div class="egsap-mln__dropdown">
												<div class="egsap-mln__dropdown-overflow">
													<div class="egsap-mln__dropdown-overflow-inner">
														<div class="egsap-mln__container">
															<ul class="egsap-mln__dropdown-content">
																<?php foreach ( $grouped[ $dd_id ] as $di ) :
																	$type      = $di['item_type'] ?? 'image_card';
																	$di_label  = $di['label'] ?? '';
																	$di_link   = $this->render_link_attrs( $di['link'] ?? [] );
																	$img_url   = $di['image']['url'] ?? '';
																	$is_static = 'static' === $type;
																	$link_class = 'egsap-mln__dropdown-link' . ( $is_static ? ' is--static' : '' );
																	?>
																	<li class="egsap-mln__dropdown-content-li">
																		<a<?php echo $di_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $link_class ); ?>">
																			<?php if ( ! $is_static && $img_url ) : ?>
																				<div class="egsap-mln__dropdown-link-bg">
																					<img src="<?php echo esc_url( $img_url ); ?>" alt="" class="egsap-mln__dropdown-img" />
																					<div class="egsap-mln__dropdown-img-overlay"></div>
																				</div>
																			<?php endif; ?>
																			<div class="egsap-mln__dropdown-link-inner">
																				<span class="egsap-mln__dropdown-link-label"><?php echo esc_html( $di_label ); ?></span>
																				<div class="egsap-mln__dropdown-link-bubble">
																					<?php echo $arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
																				</div>
																			</div>
																		</a>
																	</li>
																<?php endforeach; ?>
															</ul>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>
									<?php else :
										$link_attrs = $this->render_link_attrs( $item['link'] ?? [] );
										?>
										<a<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-mln__link">
											<span class="egsap-mln__link-label"><?php echo esc_html( $label ); ?></span>
										</a>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="egsap-mln__end">
						<?php if ( 'yes' === ( $s['secondary_enable'] ?? '' ) ) :
							$sec_class = 'egsap-mln__button';
							if ( 'yes' === ( $s['secondary_hide_mobile'] ?? '' ) ) $sec_class .= ' is--md-hide';
							?>
							<a<?php echo $this->render_link_attrs( $s['secondary_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $sec_class ); ?>">
								<span><?php echo esc_html( $s['secondary_label'] ?? '' ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( 'yes' === ( $s['primary_enable'] ?? '' ) ) : ?>
							<a<?php echo $this->render_link_attrs( $s['primary_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-mln__button is--primary">
								<span><?php echo esc_html( $s['primary_label'] ?? '' ); ?></span>
							</a>
						<?php endif; ?>
						<button type="button" data-egsap-mln-menu-btn aria-label="toggle menu" class="egsap-mln__menu-btn">
							<span class="egsap-mln__menu-btn-line"></span>
							<span class="egsap-mln__menu-btn-line"></span>
						</button>
					</div>
				</div>
			</div>
			<div class="egsap-mln__page-bg"></div>
		</nav>
		<?php
	}
}
