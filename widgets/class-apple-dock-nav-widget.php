<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Apple_Dock_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'apple_dock_nav';
	}

	public function get_title() {
		return __( 'Apple Dock Navigation Bar', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'dock', 'apple', 'macos', 'magnify', 'hover', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-apple-dock-nav' ];
	}

	public function get_style_depends() {
		return [ 'elementor-apple-dock-nav' ];
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
		/*                    CONTENT — DOCK ITEMS                    */
		/* ========================================================= */
		$this->start_controls_section( 'content_items', [
			'label' => __( 'Dock Items', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Icon Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		$rep->add_control( 'label', [
			'label'       => __( 'Label (Tooltip)', 'elementor-gsap' ),
			'description' => __( 'Muncul di atas icon saat di-hover.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'App',
		] );
		$rep->add_control( 'alt', [
			'label'       => __( 'Alt Text', 'elementor-gsap' ),
			'description' => __( 'Untuk aksesibilitas (screen reader). Kosongkan kalau icon purely decorative.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
		] );
		$rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'items', [
			'label'       => __( 'Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Notion',            'alt' => 'Notion app icon',            'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6be92ee5ddf0080fb90_notion.png' ],            'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Asana',             'alt' => 'Asana app icon',             'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6bef9d004f8a9cf3b29_asana.png' ],             'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Slack',             'alt' => 'Slack app icon',             'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6be8c099d4e1ed55770_slack.png' ],             'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Loom',              'alt' => 'Loom app icon',              'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6be5b31ba243e4da377_loom.png' ],              'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Spotify',           'alt' => 'Spotify app icon',           'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6bea97e140677496dae_spotify.png' ],           'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Webflow',           'alt' => 'Webflow app icon',           'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6bea73fcc6ee568f6f0_webflow.png' ],           'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Osmo',              'alt' => '',                           'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728b10be6bc07649a51369e_Osmo.png' ],              'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Adobe Illustrator', 'alt' => 'Adobe Illustrator app icon', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6bdf9d004f8a9cf3b09_adobe-illustrator.png' ], 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Figma',             'alt' => 'Figma app icon',             'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6be1de916069b5e1aaa_figma.png' ],             'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Photoshop',         'alt' => 'Photoshop app icon',         'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6be1de916069b5e1a86_adobe-photoshop.png' ],  'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Premiere Pro',      'alt' => 'Premiere Pro app icon',      'image' => [ 'url' => 'https://cdn.prod.website-files.com/6728a3e6f4f4161c235bc519/6728a6be051d32942a7aa31e_adobe-premierepro.png' ], 'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — SIZING                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_sizing', [
			'label' => __( 'Sizing (Dock Scale)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'dock_scale', [
			'label'       => __( 'Dock Base Scale', 'elementor-gsap' ),
			'description' => __( 'Font-size dasar list yang menentukan skala keseluruhan dock. Item widths pakai <code>em</code> relatif ke ini. Default <code>1.4vw</code> (responsive) — boleh <code>calc()</code>, <code>vw</code>, <code>px</code>, <code>rem</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '1.4vw',
			'selectors'   => [ '{{WRAPPER}} .egsap-adn' => '--adn-scale: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'item_width_base', [
			'label'      => __( 'Item Width — Base', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 3, 'max' => 10, 'step' => 0.1 ], 'px' => [ 'min' => 40, 'max' => 200 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-adn' => '--adn-item-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_width_close', [
			'label'      => __( 'Item Width — Close Sibling (±1)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 4, 'max' => 12, 'step' => 0.1 ], 'px' => [ 'min' => 50, 'max' => 240 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 7 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-adn' => '--adn-item-close: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_width_far', [
			'label'      => __( 'Item Width — Far Sibling (±2)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 4, 'max' => 12, 'step' => 0.1 ], 'px' => [ 'min' => 50, 'max' => 240 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 6 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-adn' => '--adn-item-far: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_width_hover', [
			'label'      => __( 'Item Width — Hover (Focus)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 5, 'max' => 14, 'step' => 0.1 ], 'px' => [ 'min' => 60, 'max' => 280 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 8 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-adn' => '--adn-item-hover: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_pad_x', [
			'label'      => __( 'Icon Padding (Horizontal)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-adn' => '--adn-item-pad-x: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'transition_dur', [
			'label'       => __( 'Transition Duration (seconds)', 'elementor-gsap' ),
			'description' => __( 'Durasi animasi scale.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 2,
			'step'        => 0.05,
			'default'     => 0.5,
			'selectors'   => [ '{{WRAPPER}} .egsap-adn' => '--adn-transition-dur: {{VALUE}}s;' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                      STYLE — TOOLTIP                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_tooltip', [
			'label' => __( 'Tooltip', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'tooltip_bg', [
			'label'   => __( 'Background Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [ '{{WRAPPER}} .egsap-adn' => '--adn-tooltip-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'tooltip_text', [
			'label'   => __( 'Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#131313',
			'selectors' => [ '{{WRAPPER}} .egsap-adn' => '--adn-tooltip-text: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'tooltip_size', [
			'label'      => __( 'Tooltip Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [ 'em' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 10, 'max' => 24 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-adn' => '--adn-tooltip-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'tooltip_radius', [
			'label'      => __( 'Tooltip Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 32 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-adn' => '--adn-tooltip-radius: {{SIZE}}{{UNIT}};' ],
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
			'description' => __( 'Default <code>PP Neue Montreal, Arial, sans-serif</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => "'PP Neue Montreal', Arial, sans-serif",
			'selectors'   => [ '{{WRAPPER}} .egsap-adn' => '--adn-font: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'tooltip_typography',
			'label'    => __( 'Tooltip Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-adn__tooltip',
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'dock_scale'   => '--adn-scale',
			'font_family'  => '--adn-font',
			'tooltip_bg'   => '--adn-tooltip-bg',
			'tooltip_text' => '--adn-tooltip-text',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		if ( isset( $s['transition_dur'] ) && '' !== $s['transition_dur'] ) {
			$m[] = '--adn-transition-dur: ' . floatval( $s['transition_dur'] ) . 's;';
		}
		$slider_map = [
			'item_width_base'  => '--adn-item-width',
			'item_width_close' => '--adn-item-close',
			'item_width_far'   => '--adn-item-far',
			'item_width_hover' => '--adn-item-hover',
			'item_pad_x'       => '--adn-item-pad-x',
			'tooltip_size'     => '--adn-tooltip-size',
			'tooltip_radius'   => '--adn-tooltip-radius',
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

		$items = ! empty( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : [];

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
		$editor_flag = $is_edit ? ' data-egsap-adn-editor="1"' : '';
		?>
		<nav
			class="egsap-adn"
			data-egsap-adn
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<?php if ( ! empty( $items ) ) : ?>
				<ul class="egsap-adn__list">
					<?php foreach ( $items as $item ) :
						$img_url = $item['image']['url'] ?? '';
						$label   = isset( $item['label'] ) ? $item['label'] : '';
						$alt     = isset( $item['alt'] ) ? $item['alt'] : $label;
						$link    = $this->render_link_attrs( $item['link'] ?? [] );
						?>
						<li class="egsap-adn__item">
							<a<?php echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-adn__link">
								<?php if ( $img_url ) : ?>
									<img src="<?php echo esc_url( $img_url ); ?>" loading="eager" alt="<?php echo esc_attr( $alt ); ?>" class="egsap-adn__img" />
								<?php endif; ?>
							</a>
							<?php if ( '' !== $label ) : ?>
								<div class="egsap-adn__tooltip">
									<div><?php echo esc_html( $label ); ?></div>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</nav>
		<?php
	}
}
