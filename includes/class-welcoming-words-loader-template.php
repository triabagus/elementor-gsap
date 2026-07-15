<?php
namespace Elementor_GSAP;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Welcoming_Words_Loader_Template {

	const PREFIX = 'egsap_wwl_';

	public static function key( $name ) {
		return self::PREFIX . $name;
	}

	public static function register_controls( Controls_Stack $element ) {
		$element->start_controls_section( self::key( 'section' ), [
			'label' => __( 'Loaders • Welcoming Words', 'elementor-gsap' ),
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

		$element->add_control( self::key( 'words_heading' ), [
			'label'     => __( 'Words', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'words' ), [
			'label'       => __( 'Loading Words', 'elementor-gsap' ),
			'description' => __( 'Daftar kata / frasa dipisah koma. Contoh: <code>Hello, Bonjour, Hola, Ciao</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 3,
			'default'     => 'Hello, Bonjour, स्वागत हे, Ciao, Olá, おい, Hallå, Guten tag, Hallo',
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'timing_heading' ), [
			'label'     => __( 'Timing', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'step_delay' ), [
			'label'       => __( 'Step Delay (s)', 'elementor-gsap' ),
			'description' => __( 'Jeda antar-kata saat sequence berjalan.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.05,
			'max'         => 2,
			'step'        => 0.05,
			'default'     => 0.15,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'reveal_duration' ), [
			'label'     => __( 'Reveal Duration (s)', 'elementor-gsap' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0.2,
			'max'       => 3,
			'step'      => 0.1,
			'default'   => 1,
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'exit_duration' ), [
			'label'     => __( 'Exit Duration (s)', 'elementor-gsap' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0.2,
			'max'       => 3,
			'step'      => 0.1,
			'default'   => 0.8,
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'fade_duration' ), [
			'label'     => __( 'Fade Out Duration (s)', 'elementor-gsap' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0.2,
			'max'       => 3,
			'step'      => 0.1,
			'default'   => 0.6,
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'colors_heading' ), [
			'label'     => __( 'Colors', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'bg_color' ), [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'text_color' ), [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'dot_color' ), [
			'label'     => __( 'Dot Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'condition' => $cond,
		] );

		$element->add_responsive_control( self::key( 'dot_size' ), [
			'label'      => __( 'Dot Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.1, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 4, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'condition'  => $cond,
		] );

		$element->add_control( self::key( 'typography_heading' ), [
			'label'     => __( 'Typography', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$scope = '.egsap-wwl-container[data-egsap-id="' . esc_attr( $element->get_id() ) . '"]';

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => self::key( 'word_typography' ),
			'label'     => __( 'Word Typography', 'elementor-gsap' ),
			'selector'  => $scope . ' .egsap-wwl-word',
			'condition' => $cond,
		] );

		$element->end_controls_section();
	}

	public static function render( array $s, $element_id = '' ) {
		$words   = ! empty( $s[ self::key( 'words' ) ] ) ? $s[ self::key( 'words' ) ] : 'Hello';
		$first   = trim( strtok( $words, ',' ) );
		$first   = '' !== $first ? $first : 'Hello';

		$step_delay      = isset( $s[ self::key( 'step_delay' ) ] )      && '' !== $s[ self::key( 'step_delay' ) ]      ? floatval( $s[ self::key( 'step_delay' ) ] )      : 0.15;
		$reveal_duration = isset( $s[ self::key( 'reveal_duration' ) ] ) && '' !== $s[ self::key( 'reveal_duration' ) ] ? floatval( $s[ self::key( 'reveal_duration' ) ] ) : 1;
		$exit_duration   = isset( $s[ self::key( 'exit_duration' ) ] )   && '' !== $s[ self::key( 'exit_duration' ) ]   ? floatval( $s[ self::key( 'exit_duration' ) ] )   : 0.8;
		$fade_duration   = isset( $s[ self::key( 'fade_duration' ) ] )   && '' !== $s[ self::key( 'fade_duration' ) ]   ? floatval( $s[ self::key( 'fade_duration' ) ] )   : 0.6;

		$id_attr           = $element_id ? ' data-egsap-id="' . esc_attr( $element_id ) . '"' : '';
		$style_attr        = self::build_style_attr( $s );
		$inline_style_html = self::render_inline_style_block( $s, $element_id );

		echo $inline_style_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<div
			data-loading-container
			data-egsap-wwl-step="<?php echo esc_attr( $step_delay ); ?>"
			data-egsap-wwl-reveal="<?php echo esc_attr( $reveal_duration ); ?>"
			data-egsap-wwl-exit="<?php echo esc_attr( $exit_duration ); ?>"
			data-egsap-wwl-fade="<?php echo esc_attr( $fade_duration ); ?>"
			class="egsap-wwl-container"
			<?php echo $id_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div class="egsap-wwl-screen">
				<div data-loading-words="<?php echo esc_attr( $words ); ?>" class="egsap-wwl-words">
					<div class="egsap-wwl-dot"></div>
					<p data-loading-words-target class="egsap-wwl-word"><?php echo esc_html( $first ); ?></p>
				</div>
			</div>
		</div>
		<?php
	}

	private static function build_style_attr( array $s ) {
		$map = [
			'--egsap-wwl-bg'       => self::resolve_color_value( $s, self::key( 'bg_color' ) ),
			'--egsap-wwl-text'     => self::resolve_color_value( $s, self::key( 'text_color' ) ),
			'--egsap-wwl-dot'      => self::resolve_color_value( $s, self::key( 'dot_color' ) ),
		];

		$dot_size = $s[ self::key( 'dot_size' ) ] ?? null;
		if ( is_array( $dot_size ) && isset( $dot_size['size'] ) && '' !== $dot_size['size'] ) {
			$unit                    = isset( $dot_size['unit'] ) ? (string) $dot_size['unit'] : 'em';
			$map['--egsap-wwl-dot-size'] = floatval( $dot_size['size'] ) . $unit;
		}

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
	 * Global Colors disimpan di $s['__globals__'][$key] sebagai URL
	 * `globals/colors?id=<id>` → convert ke var(--e-global-color-<id>).
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
	 * Safety net <style> block: emit colors + typography rules langsung dari $s.
	 * Diperlukan karena Elementor tidak me-regenerate post-CSS untuk perubahan
	 * page-settings (Page\Manager::get_css_file_for_update returning false),
	 * jadi Group_Control_Typography::selector kadang tidak menghasilkan CSS.
	 */
	public static function render_inline_style_block( array $s, $element_id ) {
		if ( empty( $element_id ) ) {
			return '';
		}

		$scope = '.egsap-wwl-container[data-egsap-id="' . esc_attr( $element_id ) . '"]';

		$rules = [];

		$bg   = self::resolve_color_value( $s, self::key( 'bg_color' ) );
		$text = self::resolve_color_value( $s, self::key( 'text_color' ) );
		$dot  = self::resolve_color_value( $s, self::key( 'dot_color' ) );

		if ( '' !== $bg ) {
			$rules[] = $scope . ' .egsap-wwl-screen{background-color:' . $bg . ';}';
		}
		if ( '' !== $text ) {
			$rules[] = $scope . ' .egsap-wwl-screen{color:' . $text . ';}';
		}
		if ( '' !== $dot ) {
			$rules[] = $scope . ' .egsap-wwl-dot{background-color:' . $dot . ';}';
		}

		$decl = self::build_typography_declarations( $s, self::key( 'word_typography' ) );
		if ( '' !== $decl ) {
			$rules[] = $scope . ' .egsap-wwl-word{' . $decl . '}';
		}

		if ( empty( $rules ) ) {
			return '';
		}

		return '<style>' . implode( '', $rules ) . '</style>';
	}

	/**
	 * Bangun deklarasi CSS dari sub-values Group_Control_Typography, termasuk
	 * dukungan Global Typography (`var(--e-global-typography-<id>-<prop>)`).
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
