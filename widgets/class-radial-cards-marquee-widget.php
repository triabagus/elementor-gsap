<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Radial_Cards_Marquee_Widget extends Widget_Base {

	public function get_name() {
		return 'radial_cards_marquee';
	}

	public function get_title() {
		return __( 'Radial Cards Marquee', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ 'radial', 'marquee', 'cards', 'rotate', 'ring', 'circle', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-radial-cards-marquee' ];
	}

	public function get_style_depends() {
		return [ 'elementor-radial-cards-marquee' ];
	}

	private function render_link_attrs( $link ) {
		if ( empty( $link ) || ! is_array( $link ) ) return '';
		if ( empty( $link['url'] ) ) return '';
		$url    = $link['url'];
		$target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$rel    = ! empty( $link['nofollow'] ) ? ' rel="nofollow noopener"' : ( ! empty( $link['is_external'] ) ? ' rel="noopener"' : '' );
		return ' href="' . esc_url( $url ) . '"' . $target . $rel;
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
			'raw'     => __( '<strong>Tips:</strong> jumlah unique cards di sini boleh berapa saja — JS otomatis nge-clone/remove supaya total di ring pas dengan <em>Total Cards</em> di section Marquee Settings. Misal: 4 cards + total 12 → tiap card di-duplicate 3x.', 'elementor-gsap' ),
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
			'default' => 'Card Title',
		] );
		$rep->add_control( 'link', [
			'label'       => __( 'Link (Optional)', 'elementor-gsap' ),
			'description' => __( 'Kosongkan kalau card nggak clickable.', 'elementor-gsap' ),
			'type'        => Controls_Manager::URL,
			'default'     => [ 'url' => '' ],
		] );

		$this->add_control( 'cards', [
			'label'       => __( 'Cards', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[ 'title' => 'Pixelate Image Render Effect', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a37c705a4a90c92c1e3dd2f/6a37c9bbc7ceaf29c174a45b_pixelate-image-render-effect-1440x900.avif' ] ],
				[ 'title' => 'Directional List Hover',      'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a37c705a4a90c92c1e3dd2f/6a37c9bb0d7234217629ac90_directional-list-hover-1440x900.avif' ] ],
				[ 'title' => 'Flick Cards Slider',          'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a37c705a4a90c92c1e3dd2f/6a37c9bbf139279efda387fc_flick-cards-slider-1440x900.avif' ] ],
				[ 'title' => 'Face Follow Cursor (Mascot)', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a37c705a4a90c92c1e3dd2f/6a37c9bbc6bd247aa8b009dc_face-follow-cursor-1440x900.avif' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                CONTENT — MARQUEE SETTINGS                  */
		/* ========================================================= */
		$this->start_controls_section( 'content_marquee', [
			'label' => __( 'Marquee Settings', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'total_cards', [
			'label'       => __( 'Total Cards (Ring)', 'elementor-gsap' ),
			'description' => __( 'Jumlah cards yang di-distribute di ring. JS akan clone unique cards di atas kalau kurang, atau remove kalau lebih.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 3,
			'max'         => 24,
			'step'        => 1,
			'default'     => 12,
			'selectors'   => [ '{{WRAPPER}} .egsap-rcm__list' => '--total: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'radius', [
			'label'      => __( 'Ring Radius', 'elementor-gsap' ),
			'description' => __( 'Jarak dari center ke tiap card — makin besar makin flat ring-nya.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em'  => [ 'min' => 20, 'max' => 120 ],
				'px'  => [ 'min' => 320, 'max' => 2000 ],
				'rem' => [ 'min' => 20, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 52 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-rcm__list' => '--rcm-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Rotation Duration (seconds)', 'elementor-gsap' ),
			'description' => __( 'Durasi 1 full loop rotasi. Nilai lebih kecil = lebih cepat.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 10,
			'max'         => 300,
			'step'        => 1,
			'default'     => 60,
			'selectors'   => [ '{{WRAPPER}} .egsap-rcm__list' => '--duration: {{VALUE}}s;' ],
		] );

		$this->add_control( 'direction', [
			'label'   => __( 'Rotation Direction', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''        => __( 'Default (Left)', 'elementor-gsap' ),
				'reverse' => __( 'Reverse (Right)', 'elementor-gsap' ),
			],
			'default' => '',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    STYLE — CONTAINER                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_container', [
			'label' => __( 'Container', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'min_height', [
			'label'       => __( 'Min Height', 'elementor-gsap' ),
			'description' => __( 'Height container widget. Set cukup tinggi untuk nunjukin arc atas ring.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px', 'vh', 'dvh' ],
			'range'       => [
				'em'  => [ 'min' => 10, 'max' => 60 ],
				'px'  => [ 'min' => 160, 'max' => 1200 ],
				'vh'  => [ 'min' => 20, 'max' => 100 ],
				'dvh' => [ 'min' => 20, 'max' => 100 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 20 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-rcm' => 'min-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — CARD                         */
		/* ========================================================= */
		$this->start_controls_section( 'style_card', [
			'label' => __( 'Card', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'card_bg', [
			'label'   => __( 'Card Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#201D1D',
			'selectors' => [ '{{WRAPPER}} .egsap-rcm' => '--rcm-card-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'card_text', [
			'label'   => __( 'Card Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [ '{{WRAPPER}} .egsap-rcm' => '--rcm-card-text: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'card_width', [
			'label'      => __( 'Card Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 10, 'max' => 30 ], 'px' => [ 'min' => 160, 'max' => 480 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 18.125 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-rcm' => '--rcm-card-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'card_radius', [
			'label'      => __( 'Card Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 32 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.375 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-rcm' => '--rcm-card-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'media_aspect', [
			'label'       => __( 'Image Aspect Ratio (%)', 'elementor-gsap' ),
			'description' => __( 'Padding-top percentage untuk aspect ratio. 62.5%% = 16:10, 56.25%% = 16:9, 100%% = 1:1.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 30,
			'max'         => 150,
			'step'        => 0.05,
			'default'     => 62.5,
			'selectors'   => [ '{{WRAPPER}} .egsap-rcm' => '--rcm-media-aspect: {{VALUE}}%;' ],
		] );

		$this->add_responsive_control( 'title_size', [
			'label'      => __( 'Title Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [ 'em' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 10, 'max' => 32 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.8125 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-rcm' => '--rcm-title-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => __( 'Title Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-rcm__card-h',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — TYPOGRAPHY                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Base Font', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'font_family', [
			'label'       => __( 'Base Font Family', 'elementor-gsap' ),
			'description' => __( 'Default <code>PP Neue Montreal, Arial, sans-serif</code>. Title typography di atas override elemen spesifik.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => "'PP Neue Montreal', Arial, sans-serif",
			'selectors'   => [ '{{WRAPPER}} .egsap-rcm' => '--rcm-font: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'font_family' => '--rcm-font',
			'card_bg'     => '--rcm-card-bg',
			'card_text'   => '--rcm-card-text',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		if ( isset( $s['media_aspect'] ) && '' !== $s['media_aspect'] ) {
			$m[] = '--rcm-media-aspect: ' . floatval( $s['media_aspect'] ) . '%;';
		}
		$slider_map = [
			'card_width'  => '--rcm-card-width',
			'card_radius' => '--rcm-card-radius',
			'title_size'  => '--rcm-title-size',
		];
		foreach ( $slider_map as $key => $var ) {
			if ( isset( $s[ $key ]['size'], $s[ $key ]['unit'] ) && '' !== $s[ $key ]['size'] ) {
				$m[] = $var . ': ' . $s[ $key ]['size'] . $s[ $key ]['unit'] . ';';
			}
		}
		if ( empty( $m ) ) return '';
		return ' style="' . esc_attr( implode( ' ', $m ) ) . '"';
	}

	private function build_list_style_attr( $s ) {
		$m = [];
		if ( isset( $s['total_cards'] ) && '' !== $s['total_cards'] ) {
			$m[] = '--total: ' . intval( $s['total_cards'] ) . ';';
		}
		if ( isset( $s['duration'] ) && '' !== $s['duration'] ) {
			$m[] = '--duration: ' . floatval( $s['duration'] ) . 's;';
		}
		if ( isset( $s['radius']['size'], $s['radius']['unit'] ) && '' !== $s['radius']['size'] ) {
			$m[] = '--rcm-radius: ' . $s['radius']['size'] . $s['radius']['unit'] . ';';
		}
		if ( empty( $m ) ) return '';
		return ' style="' . esc_attr( implode( ' ', $m ) ) . '"';
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

		$style_attr      = $is_edit ? '' : $this->build_style_attr( $s );
		$list_style_attr = $is_edit ? '' : $this->build_list_style_attr( $s );
		$editor_flag     = $is_edit ? ' data-egsap-rcm-editor="1"' : '';
		$direction       = ! empty( $s['direction'] ) ? ' data-egsap-rcm-direction="' . esc_attr( $s['direction'] ) . '"' : '';
		?>
		<div
			class="egsap-rcm"
			data-egsap-rcm
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $direction;   // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div class="egsap-rcm__collection">
				<div data-egsap-rcm-list class="egsap-rcm__list"<?php echo $list_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php if ( ! empty( $cards ) ) : foreach ( $cards as $card ) :
						$img   = $card['image']['url'] ?? '';
						$title = $card['title'] ?? '';
						$link  = $this->render_link_attrs( $card['link'] ?? [] );
						$tag   = '' !== $link ? 'a' : 'div';
						?>
						<div class="egsap-rcm__item">
							<<?php echo esc_html( $tag ); ?><?php echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-rcm__card">
								<div class="egsap-rcm__card-visual">
									<div class="egsap-rcm__card-media">
										<div class="egsap-rcm__card-media-before"></div>
										<?php if ( $img ) : ?>
											<img src="<?php echo esc_url( $img ); ?>" loading="lazy" alt="" class="egsap-rcm__cover-image" />
										<?php endif; ?>
									</div>
								</div>
								<?php if ( '' !== $title ) : ?>
									<div class="egsap-rcm__card-info">
										<h3 class="egsap-rcm__card-h"><?php echo esc_html( $title ); ?></h3>
									</div>
								<?php endif; ?>
							</<?php echo esc_html( $tag ); ?>>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
