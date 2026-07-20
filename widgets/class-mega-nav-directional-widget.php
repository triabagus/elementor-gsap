<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mega_Nav_Directional_Widget extends Widget_Base {

	public function get_name() {
		return 'mega_nav_directional';
	}

	public function get_title() {
		return __( 'Mega Navigation', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'menu', 'mega', 'dropdown', 'directional', 'header', 'osmo', 'gsap' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'elementor-mega-nav-directional' ];
	}

	public function get_style_depends() {
		return [ 'elementor-mega-nav-directional' ];
	}

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
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 92 20" fill="none"><path d="M32.3282 19.9998C35.7836 19.9998 38.8319 18.2812 40.6512 15.6618C41.6963 18.3292 44.3996 19.9998 47.9789 19.9998C50.5576 19.9998 52.7327 19.1845 54.1302 17.8438L53.9287 19.5858H58.2951L59.3401 10.5658L61.7665 19.5858H66.1369L68.5633 10.5658L69.6077 19.5858H73.974L73.5064 15.5478C75.3156 18.2312 78.4024 19.9998 81.9075 19.9998C87.4813 19.9998 91.9999 15.5292 91.9999 10.0145C91.9999 4.49982 87.48 0.0291549 81.9062 0.0291549C77.5817 0.0291549 73.8939 2.72115 72.4573 6.50249L71.7518 0.413822H66.9266L63.9504 11.4785L60.9741 0.413822H56.1489L55.4711 6.26382C55.3889 4.56182 54.6827 3.01982 53.4645 1.90315C52.1067 0.658488 50.2099 0.000488281 47.9783 0.000488281C45.8874 0.000488281 44.0856 0.589822 42.7677 1.70515C41.796 2.52782 41.1599 3.58982 40.914 4.76782C39.1358 1.92382 35.9567 0.0291549 32.3282 0.0291549C26.7544 0.0291549 22.2358 4.49982 22.2358 10.0145C22.2358 15.5292 26.7544 19.9998 32.3282 19.9998ZM81.9062 4.52249C84.9721 4.52249 87.4571 6.98115 87.4571 10.0145C87.4571 13.0478 84.9721 15.5065 81.9062 15.5065C78.8403 15.5065 76.3553 13.0478 76.3553 10.0145C76.3553 6.98115 78.8403 4.52249 81.9062 4.52249ZM47.9789 4.18582C49.8737 4.18582 51.0367 5.05382 51.0893 6.50782L51.1054 6.94982H55.3923L54.9502 10.7685C54.7306 10.4185 54.4698 10.0952 54.1659 9.80116C53.166 8.83582 51.6997 8.17849 49.807 7.84782L47.2553 7.39582C45.5647 7.09449 45.2183 6.57182 45.2183 5.87982C45.2183 5.70649 45.2958 4.18515 47.9789 4.18515V4.18582ZM46.1468 11.5932L49.1534 12.1512C51.0947 12.5198 51.3561 13.2992 51.3561 14.0132C51.3561 15.1405 50.0617 15.8405 47.9776 15.8405C45.5027 15.8405 44.5674 14.4592 44.5182 13.2765L44.5 12.8372H42.0083C42.2744 11.9418 42.42 10.9952 42.42 10.0145C42.42 9.96782 42.4173 9.92115 42.4166 9.87449C43.3128 10.7165 44.564 11.3038 46.1462 11.5932H46.1468ZM32.3282 4.52249C35.3941 4.52249 37.8791 6.98115 37.8791 10.0145C37.8791 13.0478 35.3941 15.5065 32.3282 15.5065C29.2624 15.5065 26.7774 13.0478 26.7774 10.0145C26.7774 6.98115 29.2624 4.52249 32.3282 4.52249Z" fill="#151313"></path><path d="M13.6751 8.238L18.1479 3.81267L16.3609 2.04467L11.8881 6.47C11.6974 6.65933 11.3706 6.52533 11.3706 6.258V0H8.84382V7.55C8.84382 8.21267 8.30073 8.75 7.63095 8.75H0V11.25H6.3251C6.5953 11.25 6.73074 11.5733 6.53937 11.762L2.06726 16.1873L3.85422 17.9553L8.32701 13.53C8.51769 13.3413 8.84449 13.4747 8.84449 13.742V20H11.3713V12.45C11.3713 11.7873 11.9144 11.25 12.5842 11.25H20.2151V8.75H13.89C13.6198 8.75 13.4844 8.42667 13.6757 8.238H13.6751Z" fill="#6840FF"></path></svg>';
	}

	public function chevron_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 20 20" fill="none"><path d="M6.6665 8.3335L9.99984 11.6668L13.3332 8.3335" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
	}

	public function chevron_right_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 20 20" fill="none"><path d="M8.3335 13.3335L11.6668 10.0002L8.3335 6.66683" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
	}

	public function chevron_left_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 20 20" fill="none"><path d="M11.6665 6.6665L8.33317 9.99984L11.6665 13.3332" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
	}

	private function render_link_attrs( $link ) {
		if ( empty( $link ) || ! is_array( $link ) ) {
			return ' href="#"';
		}
		$url    = ! empty( $link['url'] ) ? $link['url'] : '#';
		$target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$rel    = ! empty( $link['nofollow'] ) ? ' rel="nofollow noopener"' : ( ! empty( $link['is_external'] ) ? ' rel="noopener"' : '' );
		return ' href="' . esc_url( $url ) . '"' . $target . $rel;
	}

	private function parse_link_lines( $text ) {
		if ( empty( $text ) ) return [];
		$out = [];
		$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
		foreach ( $lines as $ln ) {
			$ln = trim( $ln );
			if ( '' === $ln ) continue;
			$parts = array_map( 'trim', explode( '|', $ln ) );
			$out[] = [
				'label' => isset( $parts[0] ) ? $parts[0] : '',
				'desc'  => isset( $parts[1] ) ? $parts[1] : '',
				'url'   => isset( $parts[2] ) ? $parts[2] : '#',
			];
		}
		return $out;
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                       CONTENT — BAR                        */
		/* ========================================================= */
		$this->start_controls_section( 'content_bar', [
			'label' => __( 'Bar', 'elementor-gsap' ),
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
			'description' => __( 'Paste inline SVG. Kosongkan untuk pakai logo default.', 'elementor-gsap' ),
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

		$bar_rep = new Repeater();
		$bar_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Menu',
		] );
		$bar_rep->add_control( 'is_dropdown', [
			'label'        => __( 'Has Dropdown', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'elementor-gsap' ),
			'label_off'    => __( 'No', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$bar_rep->add_control( 'dropdown_id', [
			'label'       => __( 'Dropdown ID', 'elementor-gsap' ),
			'description' => __( 'Kunci unik untuk grouping panel di bawah. Pakai kebab-case (e.g. <code>products</code>, <code>solutions</code>).', 'elementor-gsap' ),
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
			'label'       => __( 'Bar Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $bar_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Products',  'is_dropdown' => 'yes', 'dropdown_id' => 'products' ],
				[ 'label' => 'Solutions', 'is_dropdown' => 'yes', 'dropdown_id' => 'solutions' ],
				[ 'label' => 'Company',   'is_dropdown' => 'yes', 'dropdown_id' => 'company' ],
				[ 'label' => 'Pricing',   'is_dropdown' => '',    'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    CONTENT — ACTIONS                       */
		/* ========================================================= */
		$this->start_controls_section( 'content_actions', [
			'label' => __( 'Actions (CTA)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'login_enable', [
			'label'        => __( 'Show Log In', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->add_control( 'login_label', [
			'label'     => __( 'Log In Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Log in',
			'condition' => [ 'login_enable' => 'yes' ],
		] );
		$this->add_control( 'login_link', [
			'label'     => __( 'Log In Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'login_enable' => 'yes' ],
		] );

		$this->add_control( 'cta_enable', [
			'label'        => __( 'Show Primary CTA', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
		] );
		$this->add_control( 'cta_label', [
			'label'     => __( 'CTA Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Get Started',
			'condition' => [ 'cta_enable' => 'yes' ],
		] );
		$this->add_control( 'cta_link', [
			'label'     => __( 'CTA Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'cta_enable' => 'yes' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    CONTENT — PANELS                        */
		/* ========================================================= */
		$this->start_controls_section( 'content_panels', [
			'label' => __( 'Dropdown Panels', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'panels_help', [
			'type'    => Controls_Manager::RAW_HTML,
			'raw'     => __( '<strong>Cara pakai:</strong> tiap row di sini = satu <em>kolom</em> dalam dropdown. Cocokkan <code>Dropdown ID</code> dengan yang di Bar Items. Boleh punya beberapa kolom untuk 1 dropdown (tinggal tambah row dengan ID yang sama).', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$panel_rep = new Repeater();
		$panel_rep->add_control( 'dropdown_id', [
			'label'   => __( 'Dropdown ID', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'products',
		] );
		$panel_rep->add_control( 'column_label', [
			'label'   => __( 'Column Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Platform',
		] );
		$panel_rep->add_control( 'column_style', [
			'label'   => __( 'Column Style', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default'      => __( 'Default', 'elementor-gsap' ),
				'colored'      => __( 'Colored Background', 'elementor-gsap' ),
				'colored_card' => __( 'Colored + Card', 'elementor-gsap' ),
			],
			'default' => 'default',
		] );
		$panel_rep->add_control( 'column_links', [
			'label'       => __( 'Links', 'elementor-gsap' ),
			'description' => __( 'Satu link per baris. Format: <code>Label | Description | URL</code>. Description & URL opsional.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 6,
			'default'     => "Overview | See the full platform | #\nAnalytics | Track and measure | #\nIntegrations | Connect your tools | #",
			'condition'   => [ 'column_style!' => 'colored_card' ],
		] );

		/* Card fields (only for colored_card) */
		$panel_rep->add_control( 'card_image', [
			'label'     => __( 'Card Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			'condition' => [ 'column_style' => 'colored_card' ],
		] );
		$panel_rep->add_control( 'card_title', [
			'label'     => __( 'Card Title', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Featured item',
			'condition' => [ 'column_style' => 'colored_card' ],
		] );
		$panel_rep->add_control( 'card_desc', [
			'label'     => __( 'Card Description', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Short supporting line',
			'condition' => [ 'column_style' => 'colored_card' ],
		] );
		$panel_rep->add_control( 'card_cta_label', [
			'label'     => __( 'Card CTA Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Learn more',
			'condition' => [ 'column_style' => 'colored_card' ],
		] );
		$panel_rep->add_control( 'card_cta_link', [
			'label'     => __( 'Card CTA Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'column_style' => 'colored_card' ],
		] );

		$this->add_control( 'panels', [
			'label'       => __( 'Panel Columns', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $panel_rep->get_controls(),
			'title_field' => '{{{ dropdown_id }}} → {{{ column_label }}}',
			'default'     => [
				/* products (3 columns) */
				[
					'dropdown_id'  => 'products',
					'column_label' => 'Platform',
					'column_style' => 'default',
					'column_links' => "Overview | See the full platform | #\nAnalytics | Track and measure | #\nIntegrations | Connect your tools | #",
				],
				[
					'dropdown_id'  => 'products',
					'column_label' => 'Features',
					'column_style' => 'default',
					'column_links' => "Automation | Streamline workflows | #\nReporting | Generate insights | #\nAI | Build custom integrations | #",
				],
				[
					'dropdown_id'  => 'products',
					'column_label' => 'Infrastructure',
					'column_style' => 'colored',
					'column_links' => "Cloud | Managed hosting | #\nSecurity | Enterprise grade | #",
				],
				/* solutions (4 columns) */
				[
					'dropdown_id'  => 'solutions',
					'column_label' => 'By use case',
					'column_style' => 'default',
					'column_links' => "SaaS | Sell online at scale | #\nE-commerce | Subscription businesses | #\nMarketplaces | Multi-vendor platforms | #\nPlatforms | Built on top of Osmo | #\nCreator economy | Monetize your audience | #",
				],
				[
					'dropdown_id'  => 'solutions',
					'column_label' => 'By industries',
					'column_style' => 'default',
					'column_links' => "Healthcare | HIPAA compliant | #\nFinance | Secure services | #\nEducation | Learning tools | #\nRetail | Omnichannel commerce | #",
				],
				[
					'dropdown_id'  => 'solutions',
					'column_label' => 'By size',
					'column_style' => 'default',
					'column_links' => "Startups | Launch faster | #\nEnterprise | Scale with confidence | #",
				],
				[
					'dropdown_id'  => 'solutions',
					'column_label' => 'Quick links',
					'column_style' => 'colored',
					'column_links' => "Customer stories | | #\nPartners | | #\nProfessional services | | #\nMigrations | | #\nCompare plans | | #",
				],
				/* company (3 cols, last one is card) */
				[
					'dropdown_id'  => 'company',
					'column_label' => 'company',
					'column_style' => 'default',
					'column_links' => "About us | Our mission and team | #\nCareers | Join the team | #\nBlog | News and updates | #",
				],
				[
					'dropdown_id'  => 'company',
					'column_label' => 'quick links',
					'column_style' => 'default',
					'column_links' => "Documentation | | #\nHelp center | | #\nContact | | #\nStatus | | #\nLegal | | #",
				],
				[
					'dropdown_id'    => 'company',
					'column_label'   => 'Featured',
					'column_style'   => 'colored_card',
					'card_image'     => [ 'url' => 'https://cdn.prod.website-files.com/69b13f28b9d6dbc2dfbf1cb1/69b15819d9e2e5fd40ddf716_osmo-mega-nav-card.avif' ],
					'card_title'     => "Sign up for the '26 conf",
					'card_desc'      => 'Tickets on sale now',
					'card_cta_label' => 'Learn more',
					'card_cta_link'  => [ 'url' => '#' ],
				],
			],
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
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-z: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'top_offset', [
			'label'      => __( 'Top Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'side_offset', [
			'label'      => __( 'Side Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-side: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'max_width', [
			'label'      => __( 'Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 30, 'max' => 120 ],
				'px' => [ 'min' => 480, 'max' => 1920 ],
				'%'  => [ 'min' => 50, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 80 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'bar_radius', [
			'label'      => __( 'Bar Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-bar-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'nav_height', [
			'label'      => __( 'Bar Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 3, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 40, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 4 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-nav-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                        STYLE — BAR                         */
		/* ========================================================= */
		$this->start_controls_section( 'style_bar', [
			'label' => __( 'Bar Colors', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'bar_bg', [
			'label'   => __( 'Bar Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-bar-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'bar_border', [
			'label'   => __( 'Bar Border', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.1)',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-bar-border: {{VALUE}};',
			],
		] );

		$this->add_control( 'text_color', [
			'label'   => __( 'Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#201D1D',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'link_hover_bg', [
			'label'   => __( 'Link Hover Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.04)',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-link-hover-bg: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'bar_typography',
			'label'    => __( 'Bar Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-mnd__bar-link-label, {{WRAPPER}} .egsap-mnd__bar-cta',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                        STYLE — CTA                         */
		/* ========================================================= */
		$this->start_controls_section( 'style_cta', [
			'label' => __( 'CTA Buttons', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'cta_bg_color', [
			'label'   => __( 'Primary CTA Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#6840FF',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-cta-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'cta_text_color', [
			'label'   => __( 'Primary CTA Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F2F2F2',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-cta-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'cta_border_color', [
			'label'       => __( 'Secondary CTA Border/Text', 'elementor-gsap' ),
			'description' => __( 'Warna outline + text tombol secondary (Log in).', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#6840FF',
			'selectors'   => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-cta-border: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    STYLE — DROPDOWN PANEL                  */
		/* ========================================================= */
		$this->start_controls_section( 'style_dropdown', [
			'label' => __( 'Dropdown Panel', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'panel_bg', [
			'label'   => __( 'Panel Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-panel-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'panel_col_bg', [
			'label'   => __( 'Colored Column Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F7F5FF',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-panel-col-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'panel_border', [
			'label'   => __( 'Column Divider Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.1)',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-panel-border: {{VALUE}};',
			],
		] );

		$this->add_control( 'text_muted_color', [
			'label'   => __( 'Description Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(32,29,29,0.6)',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-text-muted: {{VALUE}};',
			],
		] );

		$this->add_control( 'backdrop_color', [
			'label'   => __( 'Backdrop Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.25)',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-backdrop: {{VALUE}};',
			],
		] );

		$this->add_control( 'card_bg', [
			'label'   => __( 'Card Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-card-bg: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — BURGER                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_burger', [
			'label' => __( 'Burger Button (Mobile)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'burger_bg', [
			'label'   => __( 'Burger Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#E4E0F5',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-burger-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'burger_line', [
			'label'   => __( 'Burger Line Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#6840FF',
			'selectors' => [
				'{{WRAPPER}} .egsap-mnd-wrap' => '--mnd-burger-line: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Inline style attribute untuk CSS variables — bypass Elementor post-CSS
	 * cache supaya perubahan warna/sizing langsung tampil di frontend tanpa
	 * regenerate manual. Di edit mode dilewati (biar live-preview via <style>
	 * yang menang).
	 */
	private function build_style_attr( $s ) {
		$m = [];
		$map = [
			'z_index'          => [ '--mnd-z',           ''    ],
			'bar_bg'           => [ '--mnd-bar-bg',      ''    ],
			'bar_border'       => [ '--mnd-bar-border',  ''    ],
			'text_color'       => [ '--mnd-text',        ''    ],
			'link_hover_bg'    => [ '--mnd-link-hover-bg', ''  ],
			'cta_bg_color'     => [ '--mnd-cta-bg',      ''    ],
			'cta_text_color'   => [ '--mnd-cta-text',    ''    ],
			'cta_border_color' => [ '--mnd-cta-border',  ''    ],
			'panel_bg'         => [ '--mnd-panel-bg',    ''    ],
			'panel_col_bg'     => [ '--mnd-panel-col-bg', ''   ],
			'panel_border'     => [ '--mnd-panel-border', ''   ],
			'text_muted_color' => [ '--mnd-text-muted',   ''   ],
			'backdrop_color'   => [ '--mnd-backdrop',     ''   ],
			'card_bg'          => [ '--mnd-card-bg',      ''   ],
			'burger_bg'        => [ '--mnd-burger-bg',    ''   ],
			'burger_line'      => [ '--mnd-burger-line',  ''   ],
		];
		foreach ( $map as $key => $meta ) {
			if ( ! empty( $s[ $key ] ) ) {
				$m[] = $meta[0] . ': ' . $s[ $key ] . ';';
			}
		}
		// SLIDER-type values.
		$slider_map = [
			'top_offset'  => '--mnd-top',
			'side_offset' => '--mnd-side',
			'max_width'   => '--mnd-max-width',
			'bar_radius'  => '--mnd-bar-radius',
			'nav_height'  => '--mnd-nav-height',
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

		$bar_items = ! empty( $s['bar_items'] ) && is_array( $s['bar_items'] ) ? $s['bar_items'] : [];
		$panels    = ! empty( $s['panels'] )    && is_array( $s['panels'] )    ? $s['panels']    : [];

		/* Group panels by dropdown_id */
		$grouped = [];
		foreach ( $panels as $col ) {
			$id = isset( $col['dropdown_id'] ) ? sanitize_title( $col['dropdown_id'] ) : '';
			if ( '' === $id ) continue;
			if ( ! isset( $grouped[ $id ] ) ) $grouped[ $id ] = [];
			$grouped[ $id ][] = $col;
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

		$style_attr = $is_edit ? '' : $this->build_style_attr( $s );
		$editor_flag = $is_edit ? ' data-egsap-mnd-editor="1"' : '';

		$chevron       = $this->chevron_svg();
		$chevron_right = $this->chevron_right_svg();
		$chevron_left  = $this->chevron_left_svg();

		/* Logo output */
		$logo_html = '';
		if ( 'image' === ( $s['logo_type'] ?? 'svg' ) && ! empty( $s['logo_image']['url'] ) ) {
			$logo_html = '<img src="' . esc_url( $s['logo_image']['url'] ) . '" alt="" />';
		} else {
			$svg = ! empty( $s['logo_svg'] ) ? $this->sanitize_custom_svg( $s['logo_svg'] ) : $this->default_logo_svg();
			$logo_html = $svg;
		}
		?>
		<div class="egsap-mnd-wrap"<?php echo $style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<nav
				class="egsap-mnd"
				data-egsap-mnd
				data-egsap-mnd-menu-open="false"
				data-egsap-mnd-menu-wrap
				<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			>
				<div class="egsap-mnd__bar">
					<div class="egsap-mnd__container">
						<div class="egsap-mnd__bar-start">
							<a
								data-egsap-mnd-logo
								<?php echo $this->render_link_attrs( $s['logo_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								class="egsap-mnd__bar-logo"
							>
								<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>

							<div data-egsap-mnd-nav-list data-egsap-mnd-mobile-nav class="egsap-mnd__bar-inner">
								<ul class="egsap-mnd__bar-list">
									<?php foreach ( $bar_items as $item ) :
										$label       = isset( $item['label'] ) ? $item['label'] : '';
										$is_dd       = 'yes' === ( $item['is_dropdown'] ?? '' );
										$dropdown_id = isset( $item['dropdown_id'] ) ? sanitize_title( $item['dropdown_id'] ) : '';
										?>
										<li data-egsap-mnd-nav-list-item>
											<?php if ( $is_dd && $dropdown_id ) : ?>
												<button
													type="button"
													data-egsap-mnd-dropdown-toggle="<?php echo esc_attr( $dropdown_id ); ?>"
													aria-expanded="false"
													aria-haspopup="true"
													class="egsap-mnd__bar-link is--dropdown"
												>
													<span class="egsap-mnd__bar-link-label"><?php echo esc_html( $label ); ?></span>
													<span class="egsap-mnd__bar-link-icon is--dropdown">
														<?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</span>
												</button>
											<?php else :
												$link_attrs = $this->render_link_attrs( $item['link'] ?? [] );
												?>
												<a<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-mnd__bar-link">
													<span class="egsap-mnd__bar-link-label"><?php echo esc_html( $label ); ?></span>
												</a>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>

								<?php if ( 'yes' === ( $s['login_enable'] ?? '' ) || 'yes' === ( $s['cta_enable'] ?? '' ) ) : ?>
									<ul data-egsap-mnd-nav-list-item class="egsap-mnd__bar-list is--actions">
										<?php if ( 'yes' === ( $s['login_enable'] ?? '' ) ) : ?>
											<li class="egsap-mnd__bar-action">
												<a<?php echo $this->render_link_attrs( $s['login_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-mnd__bar-cta is--secondary">
													<span class="egsap-mnd__bar-link-label"><?php echo esc_html( $s['login_label'] ?? '' ); ?></span>
												</a>
											</li>
										<?php endif; ?>
										<?php if ( 'yes' === ( $s['cta_enable'] ?? '' ) ) : ?>
											<li class="egsap-mnd__bar-action">
												<a<?php echo $this->render_link_attrs( $s['cta_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-mnd__bar-cta">
													<span class="egsap-mnd__bar-link-label"><?php echo esc_html( $s['cta_label'] ?? '' ); ?></span>
													<span class="egsap-mnd__bar-link-icon">
														<?php echo $chevron_right; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</span>
												</a>
											</li>
										<?php endif; ?>
									</ul>
								<?php endif; ?>
							</div>

							<div class="egsap-mnd__bar-end">
								<button
									type="button"
									data-egsap-mnd-burger-toggle
									aria-label="toggle menu"
									aria-expanded="false"
									class="egsap-mnd__burger"
								>
									<span data-egsap-mnd-burger-line="top" class="egsap-mnd__burger-line"></span>
									<span data-egsap-mnd-burger-line="mid" class="egsap-mnd__burger-line"></span>
									<span data-egsap-mnd-burger-line="bot" class="egsap-mnd__burger-line"></span>
								</button>
							</div>

							<div data-egsap-mnd-mobile-back class="egsap-mnd__back">
								<button type="button" aria-label="back to menu" class="egsap-mnd__bar-link is--back">
									<span class="egsap-mnd__bar-link-icon">
										<?php echo $chevron_left; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
									<span class="egsap-mnd__bar-link-label"><?php esc_html_e( 'Back', 'elementor-gsap' ); ?></span>
								</button>
							</div>
						</div>
					</div>
				</div>

				<div data-egsap-mnd-dropdown-wrapper class="egsap-mnd__dropdown-wrapper">
					<div data-egsap-mnd-dropdown-container class="egsap-mnd__dropdown-container">
						<div data-egsap-mnd-dropdown-bg class="egsap-mnd__dropdown-bg"></div>

						<?php foreach ( $grouped as $dd_id => $cols ) : ?>
							<div
								data-egsap-mnd-panel-state
								data-egsap-mnd-nav-content="<?php echo esc_attr( $dd_id ); ?>"
								role="region"
								aria-label="<?php echo esc_attr( $dd_id ); ?> menu"
								class="egsap-mnd__dropdown-panel"
							>
								<div class="egsap-mnd__dropdown-inner">
									<?php foreach ( $cols as $col ) :
										$style = $col['column_style'] ?? 'default';
										$col_class = 'egsap-mnd__panel-col';
										if ( 'colored' === $style )      $col_class .= ' is--colored';
										if ( 'colored_card' === $style ) $col_class .= ' is--colored has--card';
										?>
										<div data-egsap-mnd-fade class="<?php echo esc_attr( $col_class ); ?>">
											<?php if ( ! empty( $col['column_label'] ) ) : ?>
												<span data-egsap-mnd-fade class="egsap-mnd__panel-label"><?php echo esc_html( $col['column_label'] ); ?></span>
											<?php endif; ?>

											<?php if ( 'colored_card' === $style ) :
												$img       = $col['card_image']['url'] ?? '';
												$title     = $col['card_title'] ?? '';
												$desc      = $col['card_desc'] ?? '';
												$cta_label = $col['card_cta_label'] ?? '';
												$cta_link  = $this->render_link_attrs( $col['card_cta_link'] ?? [] );
												?>
												<div class="egsap-mnd__card">
													<?php if ( $img ) : ?>
														<div class="egsap-mnd__card-visual">
															<img src="<?php echo esc_url( $img ); ?>" loading="lazy" alt="" class="egsap-mnd__card-img" />
														</div>
													<?php endif; ?>
													<div class="egsap-mnd__card-content">
														<div class="egsap-mnd__card-text">
															<?php if ( $title ) : ?>
																<span class="egsap-mnd__panel-link-text"><?php echo esc_html( $title ); ?></span>
															<?php endif; ?>
															<?php if ( $desc ) : ?>
																<span class="egsap-mnd__panel-link-desc"><?php echo esc_html( $desc ); ?></span>
															<?php endif; ?>
														</div>
														<?php if ( $cta_label ) : ?>
															<a<?php echo $cta_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-mnd__card-cta">
																<span class="egsap-mnd__card-cta-label"><?php echo esc_html( $cta_label ); ?></span>
																<span class="egsap-mnd__card-cta-icon">
																	<?php echo $chevron_right; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
																</span>
															</a>
														<?php endif; ?>
													</div>
												</div>
											<?php else :
												$links = $this->parse_link_lines( $col['column_links'] ?? '' );
												if ( ! empty( $links ) ) : ?>
													<ul class="egsap-mnd__panel-list">
														<?php foreach ( $links as $ln ) : ?>
															<li data-egsap-mnd-fade>
																<a href="<?php echo esc_url( $ln['url'] ); ?>" class="egsap-mnd__panel-link">
																	<span class="egsap-mnd__panel-link-text"><?php echo esc_html( $ln['label'] ); ?></span>
																	<?php if ( ! empty( $ln['desc'] ) ) : ?>
																		<span class="egsap-mnd__panel-link-desc"><?php echo esc_html( $ln['desc'] ); ?></span>
																	<?php endif; ?>
																</a>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif;
											endif; ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div data-egsap-mnd-backdrop class="egsap-mnd__backdrop"></div>
			</nav>
		</div>
		<?php
	}
}
