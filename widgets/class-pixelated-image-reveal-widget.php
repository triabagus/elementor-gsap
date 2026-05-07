<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pixelated_Image_Reveal_Widget extends Widget_Base {

	public function get_name() {
		return 'pixelated_image_reveal';
	}

	public function get_title() {
		return __( 'Pixelated Image Reveal', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'gsap', 'image', 'reveal', 'pixel', 'pixelated', 'hover', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'elementor-pixelated-image-reveal' ];
	}

	public function get_style_depends() {
		return [ 'elementor-pixelated-image-reveal' ];
	}

	protected function register_controls() {

		/* === CONTENT === */
		$this->start_controls_section( 'content_section', [
			'label' => __( 'Images', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'default_image', [
			'label'       => __( 'Default Image', 'elementor-gsap' ),
			'description' => __( 'Gambar yang tampil sebelum hover/klik.', 'elementor-gsap' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => [ 'url' => 'https://cdn.prod.website-files.com/6712ad33825977f9d2f1ba2c/6714d43a777a77da89a9b5ec_osmo-pixelated-image-1.jpg' ],
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'active_image', [
			'label'       => __( 'Active Image', 'elementor-gsap' ),
			'description' => __( 'Gambar yang muncul setelah animasi pixel reveal.', 'elementor-gsap' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => [ 'url' => 'https://cdn.prod.website-files.com/6712ad33825977f9d2f1ba2c/6714d43a4d1abab1b3c81caf_osmo-pixelated-image-2.jpg' ],
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'alt_text', [
			'label'   => __( 'Alt Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'link', [
			'label'       => __( 'Link (optional)', 'elementor-gsap' ),
			'description' => __( 'Kosongkan kalau cuma efek hover tanpa link.', 'elementor-gsap' ),
			'type'        => Controls_Manager::URL,
			'default'     => [ 'url' => '' ],
		] );

		$this->end_controls_section();

		/* === ANIMATION === */
		$this->start_controls_section( 'animation_section', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Step Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Lebih kecil = transisi lebih cepat. Default 0.3.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.05,
			'max'         => 3,
			'step'        => 0.01,
			'default'     => 0.3,
		] );

		$this->add_control( 'grid_size', [
			'label'       => __( 'Grid Size', 'elementor-gsap' ),
			'description' => __( 'Jumlah pixel per baris/kolom. Lebih besar = lebih halus tapi DOM nodes bertambah quadratically.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 3,
			'max'         => 20,
			'default'     => 7,
		] );

		$this->add_control( 'trigger_mode', [
			'label'       => __( 'Trigger Mode', 'elementor-gsap' ),
			'description' => __( 'Auto = hover di desktop, click di touch device.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'auto'  => __( 'Auto (hover/click)', 'elementor-gsap' ),
				'hover' => __( 'Hover only', 'elementor-gsap' ),
				'click' => __( 'Click / Tap only', 'elementor-gsap' ),
			],
			'default'     => 'auto',
		] );

		$this->end_controls_section();

		/* === STYLE: Card === */
		$this->start_controls_section( 'card_style', [
			'label' => __( 'Card', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'card_width', [
			'label'      => __( 'Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'vw', '%', 'px' ],
			'range'      => [
				'vw' => [ 'min' => 10, 'max' => 100 ],
				'%'  => [ 'min' => 10, 'max' => 100 ],
				'px' => [ 'min' => 100, 'max' => 1200 ],
			],
			'default'        => [ 'unit' => 'vw', 'size' => 30 ],
			'tablet_default' => [ 'unit' => '%',  'size' => 60 ],
			'mobile_default' => [ 'unit' => '%',  'size' => 100 ],
			'selectors'      => [
				'{{WRAPPER}} .pixelated-image-card' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'aspect_ratio', [
			'label'   => __( 'Aspect Ratio', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'1/1'  => '1:1 (square)',
				'4/3'  => '4:3',
				'3/2'  => '3:2',
				'16/9' => '16:9',
				'3/4'  => '3:4 (portrait)',
				'2/3'  => '2:3 (portrait)',
				'9/16' => '9:16 (portrait)',
			],
			'default'   => '1/1',
			'selectors' => [
				'{{WRAPPER}} .pixelated-image-card__before' => 'padding-top: 0; aspect-ratio: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'border_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
				'%'  => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .pixelated-image-card' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'card_bg', [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'description' => __( 'Warna latar yang terlihat di balik pixel grid saat animasi.', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1a1a1a',
			'selectors' => [
				'{{WRAPPER}} .pixelated-image-card' => '--egsap-pir-bg: {{VALUE}}; background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'pixel_color', [
			'label'       => __( 'Pixel Color', 'elementor-gsap' ),
			'description' => __( 'Warna kotak-kotak pixel saat transisi.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f4f4f4',
			'selectors'   => [
				'{{WRAPPER}} .pixelated-image-card' => '--egsap-pir-pixel: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$default_url = ! empty( $s['default_image']['url'] ) ? $s['default_image']['url'] : '';
		$active_url  = ! empty( $s['active_image']['url'] )  ? $s['active_image']['url']  : '';
		$alt         = ! empty( $s['alt_text'] ) ? $s['alt_text'] : '';

		$duration = isset( $s['duration'] ) && '' !== $s['duration'] ? floatval( $s['duration'] ) : 0.3;
		if ( $duration < 0.05 ) $duration = 0.05;
		if ( $duration > 3 )    $duration = 3;

		$grid_size = isset( $s['grid_size'] ) && '' !== $s['grid_size'] ? intval( $s['grid_size'] ) : 7;
		if ( $grid_size < 3 )  $grid_size = 3;
		if ( $grid_size > 20 ) $grid_size = 20;

		$trigger = in_array( ( $s['trigger_mode'] ?? 'auto' ), [ 'auto', 'hover', 'click' ], true )
			? $s['trigger_mode']
			: 'auto';

		$is_edit = class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();

		$classes = 'pixelated-image-card' . ( $is_edit ? ' egsap-edit-mode' : '' );

		// Wrapper: link kalau ada URL, kalau tidak div biasa.
		$has_link = ! empty( $s['link']['url'] );
		$tag      = $has_link ? 'a' : 'div';
		$attrs    = [
			'class'                              => $classes,
			'data-pixelated-image-reveal'        => '',
			'data-pir-duration'                  => (string) $duration,
			'data-pir-grid'                      => (string) $grid_size,
			'data-pir-trigger'                   => $trigger,
		];

		if ( $has_link ) {
			$attrs['href']   = $s['link']['url'];
			if ( ! empty( $s['link']['is_external'] ) ) {
				$attrs['target'] = '_blank';
			}
			if ( ! empty( $s['link']['nofollow'] ) ) {
				$attrs['rel'] = 'nofollow';
			}
		}

		$attr_str = '';
		foreach ( $attrs as $k => $v ) {
			$attr_str .= ' ' . $k . '="' . esc_attr( $v ) . '"';
		}
		?>
		<<?php echo tag_escape( $tag ); ?><?php echo $attr_str; ?>>
			<span class="pixelated-image-card__before"></span>
			<?php if ( $default_url ) : ?>
				<div class="pixelated-image-card__default">
					<img src="<?php echo esc_url( $default_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="pixelated-image-card__img" loading="lazy">
				</div>
			<?php endif; ?>
			<?php if ( $active_url ) : ?>
				<div data-pixelated-image-reveal-active class="pixelated-image-card__active">
					<img src="<?php echo esc_url( $active_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="pixelated-image-card__img" loading="lazy">
				</div>
			<?php endif; ?>
			<div data-pixelated-image-reveal-grid class="pixelated-image-card__pixels"></div>
		</<?php echo tag_escape( $tag ); ?>>
		<?php
	}
}
