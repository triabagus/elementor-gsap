<?php
namespace Elementor_GSAP;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Crisp_Loading_Animation_Template {

	const PREFIX = 'egsap_crisp_';

	const DEFAULT_IMAGES = [
		1 => 'https://cdn.prod.website-files.com/69158db916f2854de7fae735/69158e74238022f91976b241_green-headphone-close-up.avif',
		2 => 'https://cdn.prod.website-files.com/69158db916f2854de7fae735/69158e74238022f91976b278_orange-leather-case.avif',
		3 => 'https://cdn.prod.website-files.com/69158db916f2854de7fae735/69158e74238022f91976b258_modern-device-close-up.avif',
		4 => 'https://cdn.prod.website-files.com/69158db916f2854de7fae735/69158e74238022f91976b287_sleek-device-close-up.avif',
		5 => 'https://cdn.prod.website-files.com/69158db916f2854de7fae735/69158e74238022f91976b268_minimalist-teal-design.avif',
	];

	const LOADER_ORDER = [ 4, 5, 1, 2, 3 ];

	public static function key( $name ) {
		return self::PREFIX . $name;
	}

	public static function register_controls( Controls_Stack $element ) {
		$element->start_controls_section( self::key( 'section' ), [
			'label' => __( 'Loaders • Crisp', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$element->add_control( self::key( 'enable' ), [
			'label'        => __( 'Enable Crisp Loading', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$cond = [ self::key( 'enable' ) => 'yes' ];

		$element->add_control( self::key( 'text_heading' ), [
			'label'     => __( 'Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'heading_text' ), [
			'label'     => __( 'Heading', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'We just love pixels',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'paragraph_text' ), [
			'label'     => __( 'Bottom Paragraph', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Crisp Loading Animation',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'images_heading' ), [
			'label'       => __( 'Slideshow Images', 'elementor-gsap' ),
			'type'        => Controls_Manager::HEADING,
			'description' => __( '5 gambar dipakai bersama untuk slider, loader, dan thumbnail nav.', 'elementor-gsap' ),
			'separator'   => 'before',
			'condition'   => $cond,
		] );

		foreach ( self::DEFAULT_IMAGES as $i => $url ) {
			$element->add_control( self::key( "image_{$i}" ), [
				/* translators: %d: image number */
				'label'     => sprintf( __( 'Image %d', 'elementor-gsap' ), $i ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [ 'url' => $url ],
				'condition' => $cond,
			] );
		}

		$element->add_control( self::key( 'colors_heading' ), [
			'label'     => __( 'Colors', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'bg_color' ), [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#eaeaea',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'text_color' ), [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f4f4f4',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'fade_color' ), [
			'label'       => __( 'Fade Edge Color', 'elementor-gsap' ),
			'description' => __( 'Warna gradient pinggir loader. Biasanya sama dengan background color.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#eaeaea',
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'typography_heading' ), [
			'label'     => __( 'Typography', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$scope = '.crisp-header[data-egsap-id="' . esc_attr( $element->get_id() ) . '"]';

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => self::key( 'heading_typography' ),
			'label'     => __( 'Heading Typography', 'elementor-gsap' ),
			'selector'  => $scope . ' .crisp-header__h1',
			'condition' => $cond,
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => self::key( 'paragraph_typography' ),
			'label'     => __( 'Paragraph Typography', 'elementor-gsap' ),
			'selector'  => $scope . ' .crisp-header__p',
			'condition' => $cond,
		] );

		$element->end_controls_section();
	}

	public static function render( array $s, $element_id = '' ) {
		$heading   = ! empty( $s[ self::key( 'heading_text' ) ] ) ? $s[ self::key( 'heading_text' ) ] : 'We just love pixels';
		$paragraph = ! empty( $s[ self::key( 'paragraph_text' ) ] ) ? $s[ self::key( 'paragraph_text' ) ] : 'Crisp Loading Animation';

		$images = [];
		for ( $i = 1; $i <= 5; $i++ ) {
			$images[ $i ] = ! empty( $s[ self::key( "image_{$i}" ) ]['url'] ) ? $s[ self::key( "image_{$i}" ) ]['url'] : '';
		}

		$is_edit = class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();

		$classes = 'crisp-header' . ( $is_edit ? ' egsap-edit-mode' : ' is--loading is--hidden' );

		$style_attr        = self::build_style_attr( $s );
		$inline_style_html = self::render_inline_style_block( $s, $element_id );
		echo $inline_style_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<section data-slideshow="wrap" class="<?php echo esc_attr( $classes ); ?>" data-egsap-id="<?php echo esc_attr( $element_id ); ?>"<?php echo $style_attr; ?>>
			<div class="crisp-header__slider">
				<div class="crisp-header__slider-list">
					<?php $first = true; ?>
					<?php foreach ( range( 1, 5 ) as $i ) : ?>
						<?php if ( $images[ $i ] ) : ?>
							<div data-slideshow="slide" class="crisp-header__slider-slide<?php echo $first ? ' is--current' : ''; ?>">
								<img class="crisp-header__slider-slide-inner" src="<?php echo esc_url( $images[ $i ] ); ?>" alt="" data-slideshow="parallax" draggable="false">
							</div>
							<?php $first = false; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="crisp-loader">
				<div class="crisp-loader__wrap">
					<div class="crisp-loader__groups">
						<div class="crisp-loader__group is--duplicate">
							<?php foreach ( self::LOADER_ORDER as $i ) : ?>
								<?php if ( $images[ $i ] ) : ?>
									<div class="crisp-loader__single">
										<div class="crisp-loader__media">
											<img loading="eager" src="<?php echo esc_url( $images[ $i ] ); ?>" alt="" class="crisp-loader__cover-img">
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<div class="crisp-loader__group is--relative">
							<?php foreach ( self::LOADER_ORDER as $idx => $i ) :
								$is_focal    = ( 2 === $idx );
								$media_class = $is_focal ? 'crisp-loader__media is--scaling is--radius' : 'crisp-loader__media';
								$img_class   = $is_focal ? 'crisp-loader__cover-img' : 'crisp-loader__cover-img is--scale-down';
								?>
								<?php if ( $images[ $i ] ) : ?>
									<div class="crisp-loader__single">
										<div class="<?php echo esc_attr( $media_class ); ?>">
											<img loading="eager" src="<?php echo esc_url( $images[ $i ] ); ?>" alt="" class="<?php echo esc_attr( $img_class ); ?>">
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="crisp-loader__fade"></div>
					<div class="crisp-loader__fade is--duplicate"></div>
				</div>
			</div>
			<div class="crisp-header__content">
				<div class="crisp-header__center">
					<h1 class="crisp-header__h1"><?php echo esc_html( $heading ); ?></h1>
				</div>
				<div class="crisp-header__bottom">
					<div class="crisp-header__slider-nav">
						<?php $first = true; ?>
						<?php foreach ( range( 1, 5 ) as $i ) : ?>
							<?php if ( $images[ $i ] ) : ?>
								<div data-slideshow="thumb" class="crisp-header__slider-nav-btn<?php echo $first ? ' is--current' : ''; ?>">
									<img loading="eager" src="<?php echo esc_url( $images[ $i ] ); ?>" alt="" class="crisp-loader__cover-img">
								</div>
								<?php $first = false; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<p class="crisp-header__p"><?php echo esc_html( $paragraph ); ?></p>
				</div>
			</div>
		</section>
		<?php
	}

	private static function build_style_attr( array $s ) {
		$map = [
			'--egsap-crisp-bg'   => self::resolve_color_value( $s, self::key( 'bg_color' ) ),
			'--egsap-crisp-text' => self::resolve_color_value( $s, self::key( 'text_color' ) ),
			'--egsap-crisp-fade' => self::resolve_color_value( $s, self::key( 'fade_color' ) ),
		];

		$props = [];
		foreach ( $map as $var => $value ) {
			if ( '' !== $value ) {
				$props[] = $var . ': ' . $value;
			}
		}

		return $props ? ' style="' . esc_attr( implode( '; ', $props ) ) . '"' : '';
	}

	/**
	 * Resolve nilai color yang mendukung Elementor Global Colors.
	 *
	 * Global Colors disimpan di $s['__globals__'][$key] sebagai URL
	 * `globals/colors?id=<id>` — bukan di $s[$key]. Kita convert ke
	 * `var(--e-global-color-<id>)` yang di-define di :root oleh Elementor Kit.
	 * Kalau tidak ada global, fallback ke raw value dari $s[$key].
	 */
	private static function resolve_color_value( array $s, $key ) {
		$globals = isset( $s['__globals__'] ) && is_array( $s['__globals__'] ) ? $s['__globals__'] : [];
		if ( ! empty( $globals[ $key ] ) ) {
			$id = self::extract_global_id( $globals[ $key ] );
			if ( '' !== $id ) {
				return 'var(--e-global-color-' . $id . ')';
			}
		}
		return isset( $s[ $key ] ) ? (string) $s[ $key ] : '';
	}

	private static function extract_global_id( $global_url ) {
		if ( ! is_string( $global_url ) || false === strpos( $global_url, '?id=' ) ) {
			return '';
		}
		$parts = explode( '?id=', $global_url );
		return isset( $parts[1] ) ? sanitize_key( $parts[1] ) : '';
	}

	/**
	 * Emit inline <style> block untuk colors + typography.
	 *
	 * Sama seperti Willem, Elementor tidak mem-regenerate post-CSS file untuk
	 * perubahan page-settings (Page\Manager::get_css_file_for_update returning
	 * false). Group_Control_Typography yang di-scope via `selector` sering
	 * tidak menghasilkan CSS di frontend. Kita bangun rules-nya sendiri
	 * langsung dari $s supaya tidak bergantung pada CSS generator itu.
	 */
	public static function render_inline_style_block( array $s, $element_id ) {
		if ( empty( $element_id ) ) {
			return '';
		}

		$scope = '.crisp-header[data-egsap-id="' . esc_attr( $element_id ) . '"]';

		$rules = [];

		// COLORS — duplikasi dari inline style variable, sekaligus safety net
		// supaya rule pasti ada meski cascade lain menang atas var().
		$bg   = self::resolve_color_value( $s, self::key( 'bg_color' ) );
		$text = self::resolve_color_value( $s, self::key( 'text_color' ) );
		$fade = self::resolve_color_value( $s, self::key( 'fade_color' ) );

		if ( '' !== $bg ) {
			$rules[] = $scope . '{background-color:' . $bg . ';}';
		}
		if ( '' !== $text ) {
			$rules[] = $scope . '{color:' . $text . ';}';
		}
		if ( '' !== $fade ) {
			$rules[] = $scope . ' .crisp-loader__fade{background-image:linear-gradient(90deg,' . $fade . ' 20%,transparent);}';
		}

		// TYPOGRAPHY — 2 group control (heading + paragraph).
		$typo_groups = [
			'heading_typography'   => $scope . ' .crisp-header__h1',
			'paragraph_typography' => $scope . ' .crisp-header__p',
		];
		foreach ( $typo_groups as $name => $selector ) {
			$decl = self::build_typography_declarations( $s, self::key( $name ) );
			if ( '' !== $decl ) {
				$rules[] = $selector . '{' . $decl . '}';
			}
		}

		if ( empty( $rules ) ) {
			return '';
		}

		return '<style>' . implode( '', $rules ) . '</style>';
	}

	/**
	 * Bangun deklarasi CSS dari sub-values Group_Control_Typography.
	 * Sub-key mengikuti konvensi Elementor: {prefix}_font_family, {prefix}_font_size, dst.
	 *
	 * Global Typography disimpan sebagai referensi `globals/typography?id=<id>`
	 * di $s['__globals__'][$prefix.'_typography']. Kalau ada, kita resolve ke
	 * `var(--e-global-typography-<id>-<property>)`.
	 */
	private static function build_typography_declarations( array $s, $prefix ) {
		$props   = [];
		$globals = isset( $s['__globals__'] ) && is_array( $s['__globals__'] ) ? $s['__globals__'] : [];

		$global_ref = $globals[ $prefix . '_typography' ] ?? '';
		$global_id  = '' !== $global_ref ? self::extract_global_id( $global_ref ) : '';

		$all_props = [
			'font_family'     => 'font-family',
			'font_weight'     => 'font-weight',
			'text_transform'  => 'text-transform',
			'font_style'      => 'font-style',
			'text_decoration' => 'text-decoration',
			'font_size'       => 'font-size',
			'line_height'     => 'line-height',
			'letter_spacing'  => 'letter-spacing',
			'word_spacing'    => 'word-spacing',
		];

		$string_keys = [ 'font_family', 'font_weight', 'text_transform', 'font_style', 'text_decoration' ];
		$sized_keys  = [ 'font_size', 'line_height', 'letter_spacing', 'word_spacing' ];

		foreach ( $all_props as $key => $css_prop ) {
			if ( '' !== $global_id ) {
				$css_var_prop = str_replace( '_', '-', $key );
				$props[]      = $css_prop . ': var(--e-global-typography-' . $global_id . '-' . $css_var_prop . ')';
				continue;
			}

			if ( in_array( $key, $string_keys, true ) ) {
				$val = $s[ $prefix . '_' . $key ] ?? '';
				if ( '' === $val ) {
					continue;
				}
				$val = (string) $val;
				if ( 'font_family' === $key ) {
					$props[] = $css_prop . ': "' . $val . '"';
				} else {
					$props[] = $css_prop . ': ' . $val;
				}
			} elseif ( in_array( $key, $sized_keys, true ) ) {
				$val = $s[ $prefix . '_' . $key ] ?? null;
				if ( ! is_array( $val ) || ! isset( $val['size'] ) || '' === $val['size'] ) {
					continue;
				}
				$unit    = isset( $val['unit'] ) ? (string) $val['unit'] : 'px';
				$props[] = $css_prop . ': ' . floatval( $val['size'] ) . $unit;
			}
		}

		return implode( ';', $props );
	}
}
