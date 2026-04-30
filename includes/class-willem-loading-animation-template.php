<?php
namespace Elementor_GSAP;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Willem_Loading_Animation_Template {

	const PREFIX = 'egsap_';

	public static function key( $name ) {
		return self::PREFIX . $name;
	}

	public static function register_controls( Controls_Stack $element ) {
		$element->start_controls_section( self::key( 'section' ), [
			'label' => __( 'Willem Loading Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$element->add_control( self::key( 'enable' ), [
			'label'        => __( 'Enable Loading Animation', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$cond = [ self::key( 'enable' ) => 'yes' ];

		$element->add_control( self::key( 'logo_heading' ), [
			'label'     => __( 'Logo', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'logo_text' ), [
			'label'       => __( 'Logo Text', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'Willem',
			'description' => __( 'Akan dipecah dua di tengah; gambar muncul di antara separuh awal & akhir.', 'elementor-gsap' ),
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'logo_suffix' ), [
			'label'     => __( 'Bottom Logo Suffix', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '©',
			'condition' => $cond,
		] );

		$h1_scope = '.willem-header[data-egsap-id="' . esc_attr( $element->get_id() ) . '"]';

		$element->add_responsive_control( self::key( 'logo_start_width' ), [
			'label'      => __( 'Logo Start Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 10, 'step' => 0.001 ],
				'px' => [ 'min' => 0, 'max' => 500 ],
				'%'  => [ 'min' => 0, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.5256 ],
			'selectors'  => [
				$h1_scope . ' .willem__h1-start' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		$element->add_responsive_control( self::key( 'logo_end_width' ), [
			'label'      => __( 'Logo End Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 10, 'step' => 0.001 ],
				'px' => [ 'min' => 0, 'max' => 500 ],
				'%'  => [ 'min' => 0, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.525 ],
			'selectors'  => [
				$h1_scope . ' .willem__h1-end' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		$element->add_control( self::key( 'images_heading' ), [
			'label'     => __( 'Images', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'cover_image' ), [
			'label'     => __( 'Final Cover Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => 'https://cdn.prod.website-files.com/6915bbf51d482439010ee790/6915bc3ac9fe346a924724b0_minimalist-architecture-1.avif' ],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'extra_image_1' ), [
			'label'     => __( 'Flash Image 1 (top)', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => 'https://cdn.prod.website-files.com/6915bbf51d482439010ee790/6915bc3ac9fe346a924724bc_minimalist-architecture-2.avif' ],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'extra_image_2' ), [
			'label'     => __( 'Flash Image 2', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => 'https://cdn.prod.website-files.com/6915bbf51d482439010ee790/6915bc3ac9fe346a924724cf_minimalist-architecture-4.avif' ],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'extra_image_3' ), [
			'label'     => __( 'Flash Image 3', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => 'https://cdn.prod.website-files.com/6915bbf51d482439010ee790/6915bc3ac9fe346a924724c5_minimalist-architecture-3.avif' ],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'nav_heading' ), [
			'label'     => __( 'Navigation', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'brand_text' ), [
			'label'     => __( 'Brand (top-left)', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Osmo ©',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'brand_url' ), [
			'label'     => __( 'Brand URL', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => $cond,
		] );

		$repeater = new Repeater();
		$repeater->add_control( 'link_text', [
			'label'   => __( 'Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Link,',
		] );
		$repeater->add_control( 'link_url', [
			'label'   => __( 'URL', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$element->add_control( self::key( 'nav_links' ), [
			'label'       => __( 'Nav Links', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[ 'link_text' => 'Projects,' ],
				[ 'link_text' => 'Services,' ],
				[ 'link_text' => 'Blog (13)' ],
			],
			'title_field' => '{{{ link_text }}}',
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'cta_text' ), [
			'label'     => __( 'CTA Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Get in touch',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'cta_url' ), [
			'label'     => __( 'CTA URL', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'colors_heading' ), [
			'label'     => __( 'Colors', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'header_color' ), [
			'label'     => __( 'Header Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f4f4f4',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'loader_color' ), [
			'label'     => __( 'Loader Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#201d1d',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'header_bg' ), [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => $cond,
		] );

		self::register_typography_controls( $element, $cond );

		$element->end_controls_section();
	}

	private static function register_typography_controls( Controls_Stack $element, array $cond ) {
		$element->add_control( self::key( 'typography_heading' ), [
			'label'     => __( 'Typography', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$scope = '.willem-header[data-egsap-id="' . esc_attr( $element->get_id() ) . '"]';

		$groups = [
			'logo_top_typography'    => [
				'label'    => __( 'Logo (Top)', 'elementor-gsap' ),
				'selector' => $scope . ' .willem__letter',
			],
			'logo_bottom_typography' => [
				'label'    => __( 'Logo (Bottom)', 'elementor-gsap' ),
				'selector' => $scope . ' .willem__letter-white',
			],
			'brand_typography'       => [
				'label'    => __( 'Brand', 'elementor-gsap' ),
				'selector' => $scope . ' .willem-nav__start .willem-nav__link',
			],
			'nav_typography'         => [
				'label'    => __( 'Nav Links', 'elementor-gsap' ),
				'selector' => $scope . ' .willem-nav__links .willem-nav__link',
			],
			'cta_typography'         => [
				'label'    => __( 'CTA', 'elementor-gsap' ),
				'selector' => $scope . ' .willem-nav__cta .willem-nav__link',
			],
		];

		foreach ( $groups as $name => $args ) {
			$element->add_group_control( Group_Control_Typography::get_type(), [
				'name'      => self::key( $name ),
				'label'     => $args['label'],
				'selector'  => $args['selector'],
				'condition' => $cond,
			] );
		}
	}

	public static function render( array $s, $element_id = '' ) {
		$logo  = ! empty( $s[ self::key( 'logo_text' ) ] ) ? $s[ self::key( 'logo_text' ) ] : 'Willem';
		$chars = preg_split( '//u', $logo, -1, PREG_SPLIT_NO_EMPTY );
		$count = count( $chars );
		$half  = (int) floor( $count / 2 );
		$start = implode( '', array_slice( $chars, 0, $half ) );
		$end   = implode( '', array_slice( $chars, $half ) );

		$suffix      = ! empty( $s[ self::key( 'logo_suffix' ) ] ) ? $s[ self::key( 'logo_suffix' ) ] : '©';
		$bottom_text = $logo . ' ' . $suffix;

		$cover  = ! empty( $s[ self::key( 'cover_image' ) ]['url'] ) ? $s[ self::key( 'cover_image' ) ]['url'] : '';
		$extra1 = ! empty( $s[ self::key( 'extra_image_1' ) ]['url'] ) ? $s[ self::key( 'extra_image_1' ) ]['url'] : '';
		$extra2 = ! empty( $s[ self::key( 'extra_image_2' ) ]['url'] ) ? $s[ self::key( 'extra_image_2' ) ]['url'] : '';
		$extra3 = ! empty( $s[ self::key( 'extra_image_3' ) ]['url'] ) ? $s[ self::key( 'extra_image_3' ) ]['url'] : '';

		$brand_url  = ! empty( $s[ self::key( 'brand_url' ) ]['url'] ) ? $s[ self::key( 'brand_url' ) ]['url'] : '#';
		$cta_url    = ! empty( $s[ self::key( 'cta_url' ) ]['url'] ) ? $s[ self::key( 'cta_url' ) ]['url'] : '#';
		$brand_text = ! empty( $s[ self::key( 'brand_text' ) ] ) ? $s[ self::key( 'brand_text' ) ] : '';
		$cta_text   = ! empty( $s[ self::key( 'cta_text' ) ] ) ? $s[ self::key( 'cta_text' ) ] : '';
		$nav_links  = ! empty( $s[ self::key( 'nav_links' ) ] ) ? $s[ self::key( 'nav_links' ) ] : [];

		$style_attr = self::build_style_attr( $s );
		$id_attr    = $element_id ? ' data-egsap-id="' . esc_attr( $element_id ) . '"' : '';

		$is_edit = class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();

		$classes = 'willem-header' . ( $is_edit ? ' egsap-edit-mode' : ' is--loading is--hidden' );
		?>
		<section class="<?php echo esc_attr( $classes ); ?>"<?php echo $id_attr; ?><?php echo $style_attr; ?>>
			<div class="willem-loader">
				<div class="willem__h1">
					<div class="willem__h1-start"><?php echo self::render_letters( $start ); ?></div>
					<div class="willem-loader__box">
						<div class="willem-loader__box-inner">
							<div class="willem__growing-image">
								<div class="willem__growing-image-wrap">
									<?php if ( $extra1 ) : ?><img class="willem__cover-image-extra is--1" src="<?php echo esc_url( $extra1 ); ?>" loading="lazy" alt=""><?php endif; ?>
									<?php if ( $extra2 ) : ?><img class="willem__cover-image-extra is--2" src="<?php echo esc_url( $extra2 ); ?>" loading="lazy" alt=""><?php endif; ?>
									<?php if ( $extra3 ) : ?><img class="willem__cover-image-extra is--3" src="<?php echo esc_url( $extra3 ); ?>" loading="lazy" alt=""><?php endif; ?>
									<?php if ( $cover ) : ?><img class="willem__cover-image" src="<?php echo esc_url( $cover ); ?>" loading="lazy" alt=""><?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="willem__h1-end"><?php echo self::render_letters( $end ); ?></div>
				</div>
			</div>
			<div class="willem-header__content">
				<div class="willem-header__top">
					<nav class="willen-nav">
						<div class="willem-nav__start">
							<a href="<?php echo esc_url( $brand_url ); ?>" class="willem-nav__link"><?php echo esc_html( $brand_text ); ?></a>
						</div>
						<div class="willem-nav__end">
							<div class="willem-nav__links">
								<?php foreach ( $nav_links as $link ) :
									$url = ! empty( $link['link_url']['url'] ) ? $link['link_url']['url'] : '#';
									?>
									<a href="<?php echo esc_url( $url ); ?>" class="willem-nav__link"><?php echo esc_html( $link['link_text'] ); ?></a>
								<?php endforeach; ?>
							</div>
							<div class="willem-nav__cta">
								<a href="<?php echo esc_url( $cta_url ); ?>" class="willem-nav__link"><?php echo esc_html( $cta_text ); ?></a>
							</div>
						</div>
					</nav>
				</div>
				<div class="willem-header__bottom">
					<div class="willem__h1">
						<?php echo self::render_letters( $bottom_text, 'willem__letter-white' ); ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}

	private static function build_style_attr( array $s ) {
		$map = [
			'--egsap-header-color' => $s[ self::key( 'header_color' ) ] ?? '',
			'--egsap-loader-color' => $s[ self::key( 'loader_color' ) ] ?? '',
			'--egsap-bg-color'     => $s[ self::key( 'header_bg' ) ] ?? '',
		];

		$props = [];
		foreach ( $map as $var => $value ) {
			if ( '' !== $value ) {
				$props[] = $var . ': ' . $value;
			}
		}

		return $props ? ' style="' . esc_attr( implode( '; ', $props ) ) . '"' : '';
	}

	private static function render_letters( $text, $class = 'willem__letter' ) {
		$out   = '';
		$chars = preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY );
		foreach ( $chars as $ch ) {
			$display = ( ' ' === $ch ) ? '&nbsp;' : esc_html( $ch );
			$extra   = ( '©' === $ch && 'willem__letter-white' === $class ) ? ' is--space' : '';
			$out    .= '<span class="' . esc_attr( $class . $extra ) . '">' . $display . '</span>';
		}
		return $out;
	}
}
