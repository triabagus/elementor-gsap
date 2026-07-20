<?php
namespace Elementor_GSAP;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Logo_Reveal_Loader_Template {

	const PREFIX = 'egsap_lrl_';

	public static function key( $name ) {
		return self::PREFIX . $name;
	}

	public static function default_osmo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 178 40" fill="none" class="loader__logo-img"><path d="M161.77 13.4645C161.143 14.0944 160.07 13.6483 160.07 12.7574V0H156.085V15C156.085 16.6569 154.747 18 153.097 18H138.154V22H150.863C151.75 22 152.195 23.0771 151.567 23.7071L142.722 32.5858L145.54 35.4142L154.385 26.5356C155.01 25.9075 156.079 26.3491 156.085 27.2347V40L160.07 40L160.07 25C160.07 23.3431 161.408 22 163.058 22H178.001V18H165.284C164.405 17.9936 163.965 16.9273 164.583 16.2985L164.588 16.2929L173.433 7.41421L170.615 4.58582L161.77 13.4645Z" fill="currentColor"></path><path d="M16.084 37.178C6.27782 37.178 0 29.956 0 20.066C0 10.176 6.27782 3 16.084 3C25.8903 3 32.1681 10.176 32.1681 20.066C32.1681 29.956 25.8903 37.178 16.084 37.178ZM5.2697 20.066C5.2697 26.828 8.33987 32.808 16.084 32.808C23.8282 32.808 26.8984 26.828 26.8984 20.066C26.8984 13.304 23.8282 7.37 16.084 7.37C8.33987 7.37 5.2697 13.304 5.2697 20.066Z" fill="currentColor"></path><path d="M45.478 37.178C38.3754 37.178 34.847 33.498 34.7095 28.714H39.246C39.4293 31.428 41.0789 33.544 45.4322 33.544C49.373 33.544 50.4269 31.796 50.4269 30.094C50.4269 27.15 47.3109 26.828 44.2866 26.184C40.2083 25.218 35.5343 24.022 35.5343 19.146C35.5343 15.098 38.7878 12.384 44.4241 12.384C50.8393 12.384 53.9095 15.834 54.2303 19.882H49.6938C49.373 18.088 48.4107 16.018 44.5157 16.018C41.4914 16.018 40.2083 17.214 40.2083 18.962C40.2083 21.4 42.8202 21.63 46.1195 22.366C50.4269 23.378 55.1009 24.62 55.1009 29.864C55.1009 34.418 51.6183 37.178 45.478 37.178Z" fill="currentColor"></path><path d="M72.6642 21.492C72.6642 18.364 72.0227 16.248 68.5859 16.248C65.2408 16.248 63.1329 18.594 63.1329 22.136V36.534H58.6422V13.074H63.1329V16.018H63.2246C64.4618 14.224 66.6155 12.384 70.1439 12.384C73.3974 12.384 75.4136 13.856 76.3301 16.478H76.4217C78.1172 14.224 80.5 12.384 84.0742 12.384C88.7941 12.384 91.1769 15.236 91.1769 20.25V36.534H86.6862V21.492C86.6862 18.364 86.0447 16.248 82.6079 16.248C79.2628 16.248 77.1549 18.594 77.1549 22.136V36.534H72.6642V21.492Z" fill="currentColor"></path><path d="M106.545 37.224C99.2594 37.224 94.8603 32.164 94.8603 24.804C94.8603 17.49 99.2594 12.338 106.591 12.338C113.831 12.338 118.23 17.444 118.23 24.758C118.23 32.118 113.831 37.224 106.545 37.224ZM99.5343 24.804C99.5343 29.68 101.734 33.498 106.591 33.498C111.357 33.498 113.556 29.68 113.556 24.804C113.556 19.882 111.357 16.11 106.591 16.11C101.734 16.11 99.5343 19.882 99.5343 24.804Z" fill="currentColor"></path></svg>';
	}

	public static function sanitize_custom_svg( $svg ) {
		if ( empty( $svg ) ) return '';
		$svg = trim( (string) $svg );
		$svg = preg_replace( '#<\s*script[^>]*>.*?<\s*/\s*script\s*>#is', '', $svg );
		$svg = preg_replace( '#<\s*script[^>]*/?>#i', '', $svg );
		$svg = preg_replace( '#<\s*foreignObject[^>]*>.*?<\s*/\s*foreignObject\s*>#is', '', $svg );
		$svg = preg_replace( '#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $svg );
		$svg = preg_replace( '#\s+(xlink:href|href)\s*=\s*("|\')?\s*javascript:[^"\'>\s]*("|\')?#i', '', $svg );
		return $svg;
	}

	public static function register_controls( Controls_Stack $element ) {
		$element->start_controls_section( self::key( 'section' ), [
			'label' => __( 'Loaders • Logo Reveal', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$element->add_control( self::key( 'enable' ), [
			'label'        => __( 'Enable Loader', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$cond = [ self::key( 'enable' ) => 'yes' ];

		/* === LOGO === */
		$element->add_control( self::key( 'logo_heading' ), [
			'label'     => __( 'Logo', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'logo_type' ), [
			'label'     => __( 'Logo Type', 'elementor-gsap' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'default' => __( 'Default SVG (Osmo)', 'elementor-gsap' ),
				'image'   => __( 'Image Upload', 'elementor-gsap' ),
				'svg'     => __( 'Custom SVG', 'elementor-gsap' ),
			],
			'default'   => 'default',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'logo_image' ), [
			'label'     => __( 'Logo Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => '' ],
			'condition' => array_merge( $cond, [ self::key( 'logo_type' ) => 'image' ] ),
		] );

		$element->add_control( self::key( 'logo_svg' ), [
			'label'       => __( 'Custom SVG Code', 'elementor-gsap' ),
			'description' => __( 'Paste <code>&lt;svg&gt;…&lt;/svg&gt;</code>. Gunakan <code>fill="currentColor"</code> agar warna ikut Logo Color.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 8,
			'default'     => '',
			'condition'   => array_merge( $cond, [ self::key( 'logo_type' ) => 'svg' ] ),
		] );

		$element->add_control( self::key( 'logo_color' ), [
			'label'     => __( 'Logo Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-logo-color: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'logo_base_opacity' ), [
			'label'       => __( 'Base Logo Opacity', 'elementor-gsap' ),
			'description' => __( 'Opacity untuk versi "base" (yang di belakang) sebagai backdrop. Versi "top" (yang reveal clip-path) selalu 100%.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'range'       => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default'     => [ 'unit' => 'px', 'size' => 0.2 ],
			'selectors'   => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-logo-base-opacity: {{SIZE}};',
			],
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'logo_width' ), [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 4, 'max' => 30, 'step' => 0.1 ],
				'px' => [ 'min' => 60, 'max' => 500 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 12 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-logo-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		$element->add_control( self::key( 'logo_height' ), [
			'label'      => __( 'Logo Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 10, 'step' => 0.1 ],
				'px' => [ 'min' => 20, 'max' => 150 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-logo-height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		/* === TEXT === */
		$element->add_control( self::key( 'text_heading' ), [
			'label'     => __( 'Text (2 words swap)', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'show_text' ), [
			'label'        => __( 'Show Text', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => $cond,
		] );

		$element->add_control( self::key( 'text_1' ), [
			'label'     => __( 'First Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Hold tight',
			'condition' => array_merge( $cond, [ self::key( 'show_text' ) => 'yes' ] ),
		] );

		$element->add_control( self::key( 'text_2' ), [
			'label'     => __( 'Second Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Hi there!',
			'condition' => array_merge( $cond, [ self::key( 'show_text' ) => 'yes' ] ),
		] );

		$element->add_control( self::key( 'text_color' ), [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-text-color: {{VALUE}};',
			],
			'condition' => array_merge( $cond, [ self::key( 'show_text' ) => 'yes' ] ),
		] );

		$element->add_control( self::key( 'text_bottom' ), [
			'label'      => __( 'Text Bottom Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 150 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3.5 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-text-bottom: {{SIZE}}{{UNIT}};',
			],
			'condition'  => array_merge( $cond, [ self::key( 'show_text' ) => 'yes' ] ),
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => self::key( 'text_typography' ),
			'label'     => __( 'Text Typography', 'elementor-gsap' ),
			'selector'  => '{{WRAPPER}} .egsap-lrl-text',
			'condition' => array_merge( $cond, [ self::key( 'show_text' ) => 'yes' ] ),
		] );

		/* === COLORS === */
		$element->add_control( self::key( 'colors_heading' ), [
			'label'     => __( 'Colors', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'bg_color' ), [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#0a0a0a',
			'selectors' => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-bg: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'progress_color' ), [
			'label'     => __( 'Progress Bar Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-progress-color: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'progress_height' ), [
			'label'      => __( 'Progress Bar Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.05, 'max' => 2, 'step' => 0.025 ],
				'px' => [ 'min' => 1, 'max' => 30 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-lrl-loader' => '--lrl-progress-height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		/* === TIMING === */
		$element->add_control( self::key( 'timing_heading' ), [
			'label'     => __( 'Timing', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'main_duration' ), [
			'label'       => __( 'Main Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi progress bar fill + logo reveal + text swap. Default 3s.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.5,
			'max'         => 10,
			'step'        => 0.1,
			'default'     => 3,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'exit_duration' ), [
			'label'       => __( 'Exit Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi background slide-up saat loader keluar. Default 1s.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.2,
			'max'         => 3,
			'step'        => 0.1,
			'default'     => 1,
			'condition'   => $cond,
		] );

		$element->end_controls_section();
	}

	public static function render( array $s, $element_id = '' ) {
		$logo_type     = ! empty( $s[ self::key( 'logo_type' ) ] ) ? $s[ self::key( 'logo_type' ) ] : 'default';
		$show_text     = ! empty( $s[ self::key( 'show_text' ) ] ) && 'yes' === $s[ self::key( 'show_text' ) ];
		$text_1        = ! empty( $s[ self::key( 'text_1' ) ] ) ? $s[ self::key( 'text_1' ) ] : 'Hold tight';
		$text_2        = ! empty( $s[ self::key( 'text_2' ) ] ) ? $s[ self::key( 'text_2' ) ] : 'Hi there!';
		$main_duration = isset( $s[ self::key( 'main_duration' ) ] ) && '' !== $s[ self::key( 'main_duration' ) ] ? floatval( $s[ self::key( 'main_duration' ) ] ) : 3;
		$exit_duration = isset( $s[ self::key( 'exit_duration' ) ] ) && '' !== $s[ self::key( 'exit_duration' ) ] ? floatval( $s[ self::key( 'exit_duration' ) ] ) : 1;

		$id_attr = $element_id ? ' data-egsap-id="' . esc_attr( $element_id ) . '"' : '';

		// Bangun 2 versi logo (base + top overlay yang di-clip-path reveal)
		ob_start();
		if ( 'image' === $logo_type && ! empty( $s[ self::key( 'logo_image' ) ]['url'] ) ) {
			echo '<img src="' . esc_url( $s[ self::key( 'logo_image' ) ]['url'] ) . '" alt="" />';
		} elseif ( 'svg' === $logo_type ) {
			$svg = isset( $s[ self::key( 'logo_svg' ) ] ) ? self::sanitize_custom_svg( $s[ self::key( 'logo_svg' ) ] ) : '';
			if ( '' !== $svg ) {
				echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		} else {
			echo self::default_osmo_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		$logo_html = ob_get_clean();
		?>
		<div
			class="egsap-lrl-loader"
			data-load-wrap
			data-egsap-lrl
			<?php echo $id_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			data-egsap-lrl-main-duration="<?php echo esc_attr( $main_duration ); ?>"
			data-egsap-lrl-exit-duration="<?php echo esc_attr( $exit_duration ); ?>"
		>
			<div data-load-bg class="egsap-lrl-bg">
				<div data-load-progress class="egsap-lrl-progress"></div>
			</div>
			<div data-load-container class="egsap-lrl-container">
				<div class="egsap-lrl-logo-wrap">
					<div class="egsap-lrl-logo-item is--base">
						<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div data-load-logo class="egsap-lrl-logo-item is--top">
						<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<?php if ( $show_text ) : ?>
					<div class="egsap-lrl-text-wrap">
						<span data-load-text data-load-reset class="egsap-lrl-text"><?php echo esc_html( $text_1 ); ?></span>
						<span data-load-text data-load-reset class="egsap-lrl-text"><?php echo esc_html( $text_2 ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
