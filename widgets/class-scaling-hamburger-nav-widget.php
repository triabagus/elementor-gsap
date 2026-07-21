<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Scaling_Hamburger_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'scaling_hamburger_nav';
	}

	public function get_title() {
		return __( 'Scaling Hamburger Navigation', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'menu', 'hamburger', 'scaling', 'floating', 'pill', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-scaling-hamburger-nav' ];
	}

	public function get_style_depends() {
		return [ 'elementor-scaling-hamburger-nav' ];
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
		/*                    CONTENT — MENU                          */
		/* ========================================================= */
		$this->start_controls_section( 'content_menu', [
			'label' => __( 'Menu', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'menu_label', [
			'label'       => __( 'Menu Eyebrow Label', 'elementor-gsap' ),
			'description' => __( 'Text kecil uppercase di atas list menu (mis. "Menu"). Kosongkan untuk hide.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'Menu',
		] );

		$menu_rep = new Repeater();
		$menu_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Home',
		] );
		$menu_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );
		$menu_rep->add_control( 'is_current', [
			'label'        => __( 'Mark as Current', 'elementor-gsap' ),
			'description'  => __( 'Halaman aktif — text dim + dot muncul di kanan.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'menu_items', [
			'label'       => __( 'Menu Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $menu_rep->get_controls(),
			'title_field' => '{{{ label }}}{{ is_current === "yes" ? " • current" : "" }}',
			'default'     => [
				[ 'label' => 'Home',           'link' => [ 'url' => '#' ], 'is_current' => 'yes' ],
				[ 'label' => 'Portfolio',      'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Our Expertises', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Services',       'link' => [ 'url' => '#' ] ],
				[ 'label' => 'About',          'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Contact',        'link' => [ 'url' => '#' ] ],
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
			'default' => 500,
			'min'     => 0,
			'max'     => 100000,
			'selectors' => [ '{{WRAPPER}} .egsap-shn' => '--shn-z: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'top_offset', [
			'label'      => __( 'Top Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-shn' => '--shn-top: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'right_offset', [
			'label'      => __( 'Right Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-shn' => '--shn-right: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'toggle_size', [
			'label'      => __( 'Toggle Size (Collapsed Bar)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 2, 'max' => 6, 'step' => 0.05 ], 'px' => [ 'min' => 32, 'max' => 100 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 3.5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-shn' => '--shn-toggle-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'bar_radius', [
			'label'      => __( 'Bar Radius (Expanded)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-shn' => '--shn-bar-bg-radius: {{SIZE}}{{UNIT}}; --shn-bar-radius: calc({{SIZE}}{{UNIT}} - 0.25em);' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — COLORS                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_colors', [
			'label' => __( 'Colors', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'bar_bg', [
			'label'   => __( 'Bar Background (Expanded)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#E2E1DF',
			'selectors' => [ '{{WRAPPER}} .egsap-shn' => '--shn-bar-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'text_color', [
			'label'   => __( 'Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#131313',
			'selectors' => [ '{{WRAPPER}} .egsap-shn' => '--shn-text: {{VALUE}};' ],
		] );

		$this->add_control( 'toggle_color', [
			'label'   => __( 'Toggle Bar Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#131313',
			'selectors' => [ '{{WRAPPER}} .egsap-shn' => '--shn-toggle-color: {{VALUE}};' ],
		] );

		$this->add_control( 'backdrop_color', [
			'label'   => __( 'Backdrop Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#000000',
			'selectors' => [ '{{WRAPPER}} .egsap-shn' => '--shn-backdrop: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->add_control( 'backdrop_opacity', [
			'label'   => __( 'Backdrop Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 1,
			'step'    => 0.05,
			'default' => 0.33,
			'selectors' => [ '{{WRAPPER}} .egsap-shn' => '--shn-backdrop-opacity: {{VALUE}};' ],
		] );

		$this->add_control( 'menu_label_color', [
			'label'       => __( 'Menu Eyebrow Color', 'elementor-gsap' ),
			'description' => __( 'Warna text "Menu" kecil di atas list. Default mengikuti text color.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '',
			'selectors'   => [ '{{WRAPPER}} .egsap-shn' => '--shn-menu-label-color: {{VALUE}};' ],
			'separator'   => 'before',
		] );

		$this->add_control( 'menu_label_opacity', [
			'label'   => __( 'Menu Eyebrow Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 1,
			'step'    => 0.05,
			'default' => 0.5,
			'selectors' => [ '{{WRAPPER}} .egsap-shn' => '--shn-menu-label-opacity: {{VALUE}};' ],
		] );

		$this->add_control( 'dot_color', [
			'label'       => __( 'Current Item Dot Color', 'elementor-gsap' ),
			'description' => __( 'Warna dot indicator. Kosongkan = mengikuti text color.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '',
			'selectors'   => [ '{{WRAPPER}} .egsap-shn' => '--shn-dot-color: {{VALUE}};' ],
			'separator'   => 'before',
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
			'description' => __( 'Default <code>PP Neue Montreal, Arial, sans-serif</code>. Ganti kalau perlu.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => "'PP Neue Montreal', Arial, sans-serif",
			'selectors'   => [ '{{WRAPPER}} .egsap-shn' => '--shn-font: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'menu_label_size', [
			'label'      => __( 'Menu Eyebrow Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 10, 'max' => 24 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-shn' => '--shn-menu-label-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'menu_label_typography',
			'label'    => __( 'Menu Eyebrow Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-shn__menu-label',
		] );

		$this->add_responsive_control( 'link_size', [
			'label'      => __( 'Link Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 16, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-shn' => '--shn-link-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'link_typography',
			'label'    => __( 'Link Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-shn__link-text',
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'z_index'            => '--shn-z',
			'font_family'        => '--shn-font',
			'bar_bg'             => '--shn-bar-bg',
			'text_color'         => '--shn-text',
			'toggle_color'       => '--shn-toggle-color',
			'backdrop_color'     => '--shn-backdrop',
			'backdrop_opacity'   => '--shn-backdrop-opacity',
			'menu_label_color'   => '--shn-menu-label-color',
			'menu_label_opacity' => '--shn-menu-label-opacity',
			'dot_color'          => '--shn-dot-color',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		$slider_map = [
			'top_offset'      => '--shn-top',
			'right_offset'    => '--shn-right',
			'toggle_size'     => '--shn-toggle-size',
			'menu_label_size' => '--shn-menu-label-size',
			'link_size'       => '--shn-link-size',
		];
		foreach ( $slider_map as $key => $var ) {
			if ( isset( $s[ $key ]['size'], $s[ $key ]['unit'] ) && '' !== $s[ $key ]['size'] ) {
				$m[] = $var . ': ' . $s[ $key ]['size'] . $s[ $key ]['unit'] . ';';
			}
		}
		if ( isset( $s['bar_radius']['size'], $s['bar_radius']['unit'] ) && '' !== $s['bar_radius']['size'] ) {
			$size = $s['bar_radius']['size'];
			$unit = $s['bar_radius']['unit'];
			$m[] = '--shn-bar-bg-radius: ' . $size . $unit . ';';
			$m[] = '--shn-bar-radius: calc(' . $size . $unit . ' - 0.25em);';
		}
		if ( empty( $m ) ) return '';
		return ' style="' . esc_attr( implode( ' ', $m ) ) . '"';
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$menu_items = ! empty( $s['menu_items'] ) && is_array( $s['menu_items'] ) ? $s['menu_items'] : [];
		$menu_label = isset( $s['menu_label'] ) ? $s['menu_label'] : '';

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
		$editor_flag = $is_edit ? ' data-egsap-shn-editor="1"' : '';
		?>
		<nav
			class="egsap-shn"
			data-egsap-shn
			data-egsap-shn-status="not-active"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<button type="button" data-egsap-shn-toggle="close" aria-label="close menu" class="egsap-shn__backdrop"></button>
			<div class="egsap-shn__bar">
				<div class="egsap-shn__bg"></div>
				<div class="egsap-shn__group">
					<?php if ( '' !== $menu_label ) : ?>
						<p class="egsap-shn__menu-label"><?php echo esc_html( $menu_label ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $menu_items ) ) : ?>
						<ul class="egsap-shn__ul">
							<?php foreach ( $menu_items as $item ) :
								$label   = isset( $item['label'] ) ? $item['label'] : '';
								$link    = $this->render_link_attrs( $item['link'] ?? [] );
								$current = ( 'yes' === ( $item['is_current'] ?? '' ) ) ? ' aria-current="page"' : '';
								?>
								<li class="egsap-shn__li">
									<a<?php echo $link;    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $current; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-shn__link">
										<p class="egsap-shn__link-text"><?php echo esc_html( $label ); ?></p>
										<span class="egsap-shn__dot"></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div
					data-egsap-shn-toggle="toggle"
					role="button"
					tabindex="0"
					aria-label="toggle menu"
					class="egsap-shn__toggle"
				>
					<div class="egsap-shn__toggle-bar"></div>
					<div class="egsap-shn__toggle-bar"></div>
				</div>
			</div>
		</nav>
		<?php
	}
}
