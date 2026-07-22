<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cards_Tornado_3D_Widget extends Widget_Base {

	public function get_name() {
		return 'cards_tornado_3d';
	}

	public function get_title() {
		return __( '3D Cards Tornado', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ '3d', 'tornado', 'helix', 'cards', 'scroll', 'orbit', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-observer', 'gsap-scrolltrigger', 'elementor-3d-cards-tornado' ];
	}

	public function get_style_depends() {
		return [ 'elementor-3d-cards-tornado' ];
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
			'raw'     => __( '<strong>Tips:</strong> minimal 1 card sudah cukup — JS otomatis nge-duplicate untuk fill loop tornado seamless.', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Card Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		$rep->add_control( 'alt', [
			'label'       => __( 'Alt Text', 'elementor-gsap' ),
			'description' => __( 'Untuk aksesibilitas (screen reader). Kosongkan kalau purely decorative.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
		] );

		$this->add_control( 'cards', [
			'label'       => __( 'Cards', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ alt || "Card" }}}',
			'default'     => [
				[ 'alt' => 'Shadowed Silhouette', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c07aef8bb2296c518b0ec_Shadowed_Silhouette_on_Rugged_Terrain.avif' ] ],
				[ 'alt' => 'Elegant Reflection',  'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c07af2d72b691e62b852e_Elegant_Reflection_in_Soft_Natural_Light.avif' ] ],
				[ 'alt' => 'Focused Cyclist',     'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c07ae21453cfd94dcabd0_Focused_Cyclist_Portrait_in_Golden_Hour_Light.avif' ] ],
				[ 'alt' => 'Minimalist Tennis',   'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c08c8d011c9c2e761b40b_Minimalist_Tennis_Court_with_Yellow_Balls.avif' ] ],
				[ 'alt' => 'Fresh Fig',           'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c08c8ec2c5e98cd970711_Fresh_Fig_Still_Life_with_Halved_Fruit_on_Soft_Neutral_Background.avif' ] ],
				[ 'alt' => 'Green Stem',          'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c07af2b69b6a3c677c7ff_Delicate_Green_Stem_with_Buds.avif' ] ],
				[ 'alt' => 'Serene Portrait',     'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c07ae7f564388635a4c0e_Serene_Portrait_of_a_Woman_in_Warm_Tones.avif' ] ],
				[ 'alt' => 'Sailboat Ocean',      'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c07ae8a875732ce5ccecf_Sailboat_on_Tranquil_Ocean_Under_Clear_Sky.avif' ] ],
				[ 'alt' => 'Close-Up Eye',        'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a29211ed53fb474353e0c9f/6a2c07afd4e6fcd11cb42009_Close-Up_of_an_Eye_with_Freckles.avif' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                 CONTENT — TORNADO GEOMETRY                 */
		/* ========================================================= */
		$this->start_controls_section( 'content_geometry', [
			'label' => __( 'Tornado Geometry', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'rotation_angle', [
			'label'       => __( 'Rotation Angle (degrees)', 'elementor-gsap' ),
			'description' => __( 'Sudut spasi antar card di ring. Nilai kecil = card lebih rapat, besar = lebih renggang.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 5,
			'max'         => 90,
			'step'        => 1,
			'default'     => 30,
		] );

		$this->add_control( 'card_y_spacing', [
			'label'       => __( 'Vertical Card Offset', 'elementor-gsap' ),
			'description' => __( 'Gap vertikal antar card (multiplier terhadap tinggi card). 0 = flat ring, 1 = spiral tinggi.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 2,
			'step'        => 0.05,
			'default'     => 0.3,
		] );

		$this->add_control( 'orbit_depth', [
			'label'       => __( 'Orbit Depth', 'elementor-gsap' ),
			'description' => __( 'Radius orbit horizontal (em unit). Nilai besar = tornado lebih lebar & datar.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 10,
			'max'         => 100,
			'step'        => 1,
			'default'     => 35,
		] );

		$this->add_control( 'edge_offset', [
			'label'       => __( 'Edge Offset', 'elementor-gsap' ),
			'description' => __( 'Berapa jauh scaling boundary di-push keluar viewport (multiplier). Naikkan supaya card tetap full-size lebih lama sebelum fade.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 5,
			'step'        => 0.1,
			'default'     => 2,
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                  CONTENT — ANIMATION SPEED                 */
		/* ========================================================= */
		$this->start_controls_section( 'content_speed', [
			'label' => __( 'Animation & Input', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'auto_speed', [
			'label'       => __( 'Auto Rotation Speed', 'elementor-gsap' ),
			'description' => __( 'Kecepatan rotasi otomatis saat idle. Kecil = pelan.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 0.05,
			'step'        => 0.00025,
			'default'     => 0.00325,
		] );

		$this->add_control( 'scroll_speed', [
			'label'       => __( 'Scroll / Drag Speed', 'elementor-gsap' ),
			'description' => __( 'Faktor speed dari input scroll/drag terhadap rotasi.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.001,
			'max'         => 0.1,
			'step'        => 0.001,
			'default'     => 0.015,
		] );

		$this->add_control( 'drag_multiplier', [
			'label'       => __( 'Drag Sensitivity Multiplier', 'elementor-gsap' ),
			'description' => __( 'Extra multiplier untuk touch/pointer drag (bukan wheel).', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 1,
			'max'         => 20,
			'step'        => 0.5,
			'default'     => 5,
		] );

		$this->add_control( 'scroll_ease', [
			'label'       => __( 'Velocity Lerp Factor', 'elementor-gsap' ),
			'description' => __( 'Interpolation weight untuk velocity smoothing. 0 = kaku, 1 = instant, 0.1 = smooth.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.01,
			'max'         => 1,
			'step'        => 0.01,
			'default'     => 0.1,
		] );

		$this->add_control( 'max_speed', [
			'label'       => __( 'Maximum Speed', 'elementor-gsap' ),
			'description' => __( 'Cap kecepatan rotasi maksimum.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.05,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.2,
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                  CONTENT — VISUAL EFFECTS                  */
		/* ========================================================= */
		$this->start_controls_section( 'content_visual', [
			'label' => __( 'Visual Effects', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'edge_scale', [
			'label'       => __( 'Edge Fade Distance', 'elementor-gsap' ),
			'description' => __( 'Jarak transisi scale-out di edge (multiplier). Kecil = fade abrupt, besar = fade halus.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 2,
			'step'        => 0.1,
			'default'     => 0.5,
		] );

		$this->add_control( 'min_scale', [
			'label'       => __( 'Minimum Scale (Distant Cards)', 'elementor-gsap' ),
			'description' => __( 'Skala terkecil untuk card yang jauh dari center. 1 = uniform, kurang dari 1 = card jauh mengecil.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.3,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 1,
		] );

		$this->add_control( 'back_darkness', [
			'label'       => __( 'Back Card Darkness (0-1)', 'elementor-gsap' ),
			'description' => __( 'Seberapa gelap card yang menghadap belakang. 0 = tetap normal, 1 = pitch black.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.75,
		] );

		$this->add_control( 'back_blur', [
			'label'       => __( 'Back Card Blur (em)', 'elementor-gsap' ),
			'description' => __( 'Berapa em blur diterapkan ke card yang menghadap belakang.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 3,
			'step'        => 0.1,
			'default'     => 0.5,
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — CONTAINER                      */
		/* ========================================================= */
		$this->start_controls_section( 'style_container', [
			'label' => __( 'Container', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'container_height', [
			'label'       => __( 'Container Height', 'elementor-gsap' ),
			'description' => __( 'Tinggi widget. Default full viewport (100dvh).', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'dvh', 'vh', 'px', 'em' ],
			'range'       => [
				'dvh' => [ 'min' => 30, 'max' => 100 ],
				'vh'  => [ 'min' => 30, 'max' => 100 ],
				'px'  => [ 'min' => 300, 'max' => 1400 ],
				'em'  => [ 'min' => 15, 'max' => 80 ],
			],
			'default'     => [ 'unit' => 'dvh', 'size' => 100 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-tor' => '--tor-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'perspective', [
			'label'      => __( '3D Perspective', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 30, 'max' => 150 ], 'px' => [ 'min' => 400, 'max' => 2000 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-tor' => '--tor-perspective: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — CARD                         */
		/* ========================================================= */
		$this->start_controls_section( 'style_card', [
			'label' => __( 'Card', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'card_width', [
			'label'      => __( 'Card Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 8, 'max' => 30 ], 'px' => [ 'min' => 120, 'max' => 480 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 18 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-tor' => '--tor-card-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'card_radius', [
			'label'      => __( 'Card Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-tor' => '--tor-card-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'card_aspect', [
			'label'       => __( 'Card Aspect Ratio', 'elementor-gsap' ),
			'description' => __( 'Format <code>W / H</code>. Contoh: <code>4 / 5</code>, <code>1 / 1</code>, <code>16 / 9</code>, <code>3 / 4</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '4 / 5',
			'selectors'   => [ '{{WRAPPER}} .egsap-tor' => '--tor-card-aspect: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'card_aspect' => '--tor-card-aspect',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		$slider_map = [
			'container_height' => '--tor-height',
			'perspective'      => '--tor-perspective',
			'card_width'       => '--tor-card-width',
			'card_radius'      => '--tor-card-radius',
		];
		foreach ( $slider_map as $key => $var ) {
			if ( isset( $s[ $key ]['size'], $s[ $key ]['unit'] ) && '' !== $s[ $key ]['size'] ) {
				$m[] = $var . ': ' . $s[ $key ]['size'] . $s[ $key ]['unit'] . ';';
			}
		}
		if ( empty( $m ) ) return '';
		return ' style="' . esc_attr( implode( ' ', $m ) ) . '"';
	}

	private function build_data_attrs( $s ) {
		$attrs = [
			'egsap-tor-rotation-angle'  => $s['rotation_angle']  ?? 30,
			'egsap-tor-card-y-spacing'  => $s['card_y_spacing']  ?? 0.3,
			'egsap-tor-orbit-depth'     => $s['orbit_depth']     ?? 35,
			'egsap-tor-edge-offset'     => $s['edge_offset']     ?? 2,
			'egsap-tor-auto-speed'      => $s['auto_speed']      ?? 0.00325,
			'egsap-tor-scroll-speed'    => $s['scroll_speed']    ?? 0.015,
			'egsap-tor-drag-multiplier' => $s['drag_multiplier'] ?? 5,
			'egsap-tor-scroll-ease'     => $s['scroll_ease']     ?? 0.1,
			'egsap-tor-max-speed'       => $s['max_speed']       ?? 0.2,
			'egsap-tor-edge-scale'      => $s['edge_scale']      ?? 0.5,
			'egsap-tor-min-scale'       => $s['min_scale']       ?? 1,
			'egsap-tor-back-darkness'   => $s['back_darkness']   ?? 0.75,
			'egsap-tor-back-blur'       => $s['back_blur']       ?? 0.5,
		];
		$out = '';
		foreach ( $attrs as $k => $v ) {
			$out .= ' data-' . $k . '="' . esc_attr( $v ) . '"';
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

		/* Emit style + data attrs baik di frontend maupun editor supaya preview
		   Elementor reflect config user (JS baca dari data-* attributes). */
		$style_attr  = $this->build_style_attr( $s );
		$data_attrs  = $this->build_data_attrs( $s );
		$editor_flag = $is_edit ? ' data-egsap-tor-editor="1"' : '';
		?>
		<div
			class="egsap-tor"
			data-egsap-tor
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $data_attrs;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div class="egsap-tor__collection">
				<div data-egsap-tor-list class="egsap-tor__list">
					<?php if ( ! empty( $cards ) ) : foreach ( $cards as $card ) :
						$img = $card['image']['url'] ?? '';
						$alt = isset( $card['alt'] ) ? $card['alt'] : '';
						?>
						<div data-egsap-tor-item class="egsap-tor__item">
							<div draggable="false" class="egsap-tor__card">
								<?php if ( $img ) : ?>
									<img src="<?php echo esc_url( $img ); ?>" loading="lazy" alt="<?php echo esc_attr( $alt ); ?>" class="egsap-tor__cover-image" />
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
