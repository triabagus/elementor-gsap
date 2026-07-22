<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Dropping_Cards_Stack_Widget extends Widget_Base {

	public function get_name() {
		return 'dropping_cards_stack';
	}

	public function get_title() {
		return __( 'Dropping Cards Stack', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ 'slider', 'stack', 'cards', 'drag', 'tinder', 'osmo', 'gsap' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-draggable', 'gsap-customease', 'elementor-dropping-cards-stack' ];
	}

	public function get_style_depends() {
		return [ 'elementor-dropping-cards-stack' ];
	}

	public function chevron_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" class="egsap-dcs__control-svg" width="100%" viewBox="0 0 18 18" fill="none"><path d="M6.74976 14.25L11.9998 9L6.74976 3.75" stroke="currentColor" stroke-width="2.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                       CONTENT — CARDS                      */
		/* ========================================================= */
		$this->start_controls_section( 'content_cards', [
			'label' => __( 'Cards', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'cards_help', [
			'type'    => Controls_Manager::RAW_HTML,
			'raw'     => __( '<strong>Tips:</strong> minimal 3 cards. Kalau kurang dari 5, JS otomatis clone untuk cukup infinite loop.', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Card Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		$rep->add_control( 'title', [
			'label'   => __( 'Title', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Card Title.',
		] );
		$rep->add_control( 'tags', [
			'label'       => __( 'Tags (one per line)', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 4,
			'default'     => "Tag One\nTag Two\nTag Three",
		] );
		$rep->add_control( 'variant', [
			'label'   => __( 'Color Variant', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''        => __( 'Default (Yellow)', 'elementor-gsap' ),
				'light'   => __( 'Light', 'elementor-gsap' ),
				'purple'  => __( 'Purple', 'elementor-gsap' ),
				'pink'    => __( 'Pink', 'elementor-gsap' ),
				'dark'    => __( 'Dark', 'elementor-gsap' ),
				'custom'  => __( 'Custom Colors', 'elementor-gsap' ),
			],
			'default' => '',
		] );
		$rep->add_control( 'custom_bg', [
			'label'     => __( 'Custom Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffc664',
			'condition' => [ 'variant' => 'custom' ],
		] );
		$rep->add_control( 'custom_text', [
			'label'     => __( 'Custom Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#201d1d',
			'condition' => [ 'variant' => 'custom' ],
		] );

		$this->add_control( 'cards', [
			'label'       => __( 'Cards', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[
					'title'   => 'Branding & Identity.',
					'tags'    => "Brand Strategy\nLogo Design\nVisual Identity",
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/6969f795b8c9b9bba545e75b/6969fb1c152133800af9cd81_service-1.avif' ],
					'variant' => '',
				],
				[
					'title'   => 'Marketing.',
					'tags'    => "Ads Creation\nSEO Setup\nEmail Marketing\nFunnel Strategy\nAnalytics",
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/6969f795b8c9b9bba545e75b/696a00119a186f6eae03811f_service-2.avif' ],
					'variant' => 'light',
				],
				[
					'title'   => 'UX Strategy.',
					'tags'    => "UX audits\nWireframes & Prototypes\nUser Testing",
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/6969f795b8c9b9bba545e75b/696a000e473a6fb87e025764_service-3.avif' ],
					'variant' => 'purple',
				],
				[
					'title'   => 'Osmo Wizard.',
					'tags'    => "Magic Spells\nLegendary Status\nCreative Powerhouse\nEarly Adopter",
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/6969f795b8c9b9bba545e75b/696a0012a2bbbee1e9a7b23d_service-4.avif' ],
					'variant' => 'pink',
				],
				[
					'title'   => 'Websites.',
					'tags'    => "Web Design\nWebflow Development\nOsmo Supply",
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/6969f795b8c9b9bba545e75b/696a034ccd0b1a0c5b3af037_service-5.avif' ],
					'variant' => 'dark',
				],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     CONTENT — BEHAVIOR                     */
		/* ========================================================= */
		$this->start_controls_section( 'content_behavior', [
			'label' => __( 'Slider Behavior', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'nav_enable', [
			'label'        => __( 'Show Prev/Next Buttons', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'visible_count', [
			'label'       => __( 'Visible Stack Depth', 'elementor-gsap' ),
			'description' => __( 'Berapa cards yang visible di stack (termasuk yang paling depan). Default 4.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 2,
			'max'         => 6,
			'step'        => 1,
			'default'     => 4,
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Transition Duration (seconds)', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.2,
			'max'         => 2,
			'step'        => 0.05,
			'default'     => 0.75,
		] );

		$this->add_control( 'threshold', [
			'label'       => __( 'Drag Threshold (%)', 'elementor-gsap' ),
			'description' => __( 'Persentase drag distance untuk trigger next slide. Kecil = mudah cycle, besar = perlu drag jauh.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 5,
			'max'         => 60,
			'step'        => 1,
			'default'     => 20,
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — LAYOUT                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_layout', [
			'label' => __( 'Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'gap', [
			'label'      => __( 'Container Gap', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'pad_b', [
			'label'       => __( 'Stack Offset — Bottom (Depth)', 'elementor-gsap' ),
			'description' => __( 'Padding-bottom collection → total vertical stack depth. JS bagi ke visible cards.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [ 'em' => [ 'min' => 0, 'max' => 20, 'step' => 0.1 ], 'px' => [ 'min' => 0, 'max' => 200 ] ],
			'default'     => [ 'unit' => 'em', 'size' => 7.5 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-pad-b: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'pad_r', [
			'label'       => __( 'Stack Offset — Right (Depth)', 'elementor-gsap' ),
			'description' => __( 'Padding-right collection → total horizontal stack depth.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [ 'em' => [ 'min' => 0, 'max' => 20, 'step' => 0.1 ], 'px' => [ 'min' => 0, 'max' => 200 ] ],
			'default'     => [ 'unit' => 'em', 'size' => 7.5 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-pad-r: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'card_max', [
			'label'      => __( 'Card Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 20, 'max' => 80 ], 'px' => [ 'min' => 320, 'max' => 1200 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 50 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-card-max: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'card_radius', [
			'label'      => __( 'Card Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-card-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'card_pad', [
			'label'      => __( 'Card Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0.5, 'max' => 6, 'step' => 0.05 ], 'px' => [ 'min' => 8, 'max' => 100 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-card-pad: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — VISUAL                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_visual', [
			'label' => __( 'Card Image', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'visual_width', [
			'label'      => __( 'Image Column Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range'      => [ '%' => [ 'min' => 15, 'max' => 60 ] ],
			'default'    => [ 'unit' => '%', 'size' => 35 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-visual-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'visual_radius', [
			'label'      => __( 'Image Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 32 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-visual-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'visual_tint', [
			'label'   => __( 'Image Background Tint (Fallback)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.1)',
			'selectors' => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-visual-tint: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — TYPOGRAPHY                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Typography', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'title_size', [
			'label'      => __( 'Title Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 2, 'max' => 8, 'step' => 0.05 ],
				'px' => [ 'min' => 32, 'max' => 128 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 4.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-title-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => __( 'Title Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-dcs__card-h',
		] );

		$this->add_responsive_control( 'tag_size', [
			'label'      => __( 'Tag Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 10, 'max' => 32 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-p-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'tag_typography',
			'label'    => __( 'Tag Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-dcs__card-p',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — CONTROLS                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_controls', [
			'label' => __( 'Nav Buttons', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => [ 'nav_enable' => 'yes' ],
		] );

		$this->add_control( 'btn_bg', [
			'label'   => __( 'Next Button Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-btn-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'btn_color', [
			'label'   => __( 'Next Button Icon Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#201D1D',
			'selectors' => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-btn-color: {{VALUE}};' ],
		] );

		$this->add_control( 'btn_bg_prev', [
			'label'   => __( 'Prev Button Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(244,244,244,0.2)',
			'selectors' => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-btn-bg-prev: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->add_control( 'btn_color_prev', [
			'label'   => __( 'Prev Button Icon Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-btn-color-prev: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'btn_size', [
			'label'      => __( 'Button Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 2, 'max' => 6, 'step' => 0.1 ], 'px' => [ 'min' => 32, 'max' => 96 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-btn-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'btn_gap', [
			'label'      => __( 'Gap Between Buttons', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.375 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dcs' => '--dcs-btn-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	private function parse_tags( $text ) {
		if ( empty( $text ) ) return [];
		$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
		$out = [];
		foreach ( $lines as $ln ) {
			$t = trim( $ln );
			if ( '' !== $t ) $out[] = $t;
		}
		return $out;
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$cards = ! empty( $s['cards'] ) && is_array( $s['cards'] ) ? $s['cards'] : [];

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

		$editor_flag = $is_edit ? ' data-egsap-dcs-editor="1"' : '';

		$visible   = intval( $s['visible_count'] ?? 4 );
		$duration  = floatval( $s['duration'] ?? 0.75 );
		$threshold = floatval( $s['threshold'] ?? 20 );
		$show_nav  = 'yes' === ( $s['nav_enable'] ?? '' );

		$chevron = $this->chevron_svg();
		?>
		<div
			class="egsap-dcs"
			data-egsap-dcs-init
			data-egsap-dcs-visible="<?php echo esc_attr( $visible ); ?>"
			data-egsap-dcs-duration="<?php echo esc_attr( $duration ); ?>"
			data-egsap-dcs-threshold="<?php echo esc_attr( $threshold ); ?>"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div data-egsap-dcs-collection class="egsap-dcs__collection">
				<div class="egsap-dcs__list">
					<?php if ( ! empty( $cards ) ) : foreach ( $cards as $card ) :
						$title   = $card['title'] ?? '';
						$tags    = $this->parse_tags( $card['tags'] ?? '' );
						$img     = $card['image']['url'] ?? '';
						$variant = $card['variant'] ?? '';
						$cls     = 'egsap-dcs__card';
						$inline  = '';

						if ( 'custom' === $variant ) {
							$bg   = $card['custom_bg'] ?? '';
							$text = $card['custom_text'] ?? '';
							$style_parts = [];
							if ( $bg )   $style_parts[] = 'background-color:' . $bg;
							if ( $text ) $style_parts[] = 'color:' . $text;
							if ( ! empty( $style_parts ) ) $inline = ' style="' . esc_attr( implode( ';', $style_parts ) ) . '"';
						} elseif ( in_array( $variant, [ 'light', 'purple', 'pink', 'dark' ], true ) ) {
							$cls .= ' is--' . $variant;
						}
						?>
						<div data-egsap-dcs-item class="egsap-dcs__item">
							<div class="<?php echo esc_attr( $cls ); ?>"<?php echo $inline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<div class="egsap-dcs__card-before"></div>
								<div class="egsap-dcs__card-content">
									<div class="egsap-dcs__card-start">
										<?php if ( $img ) : ?>
											<div class="egsap-dcs__card-visual">
												<div class="egsap-dcs__card-visual-before"></div>
												<img src="<?php echo esc_url( $img ); ?>" loading="lazy" alt="" class="egsap-dcs__card-visual-img" />
											</div>
										<?php endif; ?>
										<?php if ( ! empty( $tags ) ) : ?>
											<div class="egsap-dcs__card-words">
												<?php foreach ( $tags as $tag ) : ?>
													<p class="egsap-dcs__card-p"><?php echo esc_html( $tag ); ?></p>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
									</div>
									<?php if ( '' !== $title ) : ?>
										<div class="egsap-dcs__card-end">
											<h3 class="egsap-dcs__card-h"><?php echo esc_html( $title ); ?></h3>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>

			<?php if ( $show_nav ) : ?>
				<div class="egsap-dcs__controls">
					<button type="button" data-egsap-dcs-prev aria-label="previous card" class="egsap-dcs__control is--prev">
						<span class="egsap-dcs__control-circle is--prev"><?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
					<button type="button" data-egsap-dcs-next aria-label="next card" class="egsap-dcs__control">
						<span class="egsap-dcs__control-circle"><?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
