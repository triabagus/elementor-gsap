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
			'label' => __( 'Crisp Loading Animation', 'elementor-gsap' ),
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

		$element->add_control( self::key( 'logo_url' ), [
			'label'     => __( 'Logo URL', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'logo_image' ), [
			'label'       => __( 'Logo Image', 'elementor-gsap' ),
			'description' => __( 'Upload custom logo (PNG / JPG / SVG). Kosongkan untuk pakai OSMO SVG default.', 'elementor-gsap' ),
			'type'        => Controls_Manager::MEDIA,
			'media_types' => [ 'image', 'svg' ],
			'default'     => [ 'url' => '' ],
			'condition'   => $cond,
		] );

		$element->add_responsive_control( self::key( 'logo_width' ), [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 20, 'step' => 0.1 ],
				'px' => [ 'min' => 16, 'max' => 400 ],
				'%'  => [ 'min' => 1, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 8 ],
			'selectors'  => [
				'.crisp-header[data-egsap-id="' . esc_attr( $element->get_id() ) . '"] .osmo-logo' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
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
		$logo_url    = ! empty( $s[ self::key( 'logo_url' ) ]['url'] ) ? $s[ self::key( 'logo_url' ) ]['url'] : '#';
		$logo_target = ! empty( $s[ self::key( 'logo_url' ) ]['is_external'] ) ? '_blank' : '_self';
		$logo_rel    = ! empty( $s[ self::key( 'logo_url' ) ]['nofollow'] ) ? 'nofollow' : '';
		$logo_image  = ! empty( $s[ self::key( 'logo_image' ) ]['url'] ) ? $s[ self::key( 'logo_image' ) ]['url'] : '';

		$images = [];
		for ( $i = 1; $i <= 5; $i++ ) {
			$images[ $i ] = ! empty( $s[ self::key( "image_{$i}" ) ]['url'] ) ? $s[ self::key( "image_{$i}" ) ]['url'] : '';
		}

		$is_edit = class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();

		$classes = 'crisp-header' . ( $is_edit ? ' egsap-edit-mode' : ' is--loading is--hidden' );

		$style_attr = self::build_style_attr( $s );
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
				<div class="crisp-header__top">
					<a href="<?php echo esc_url( $logo_url ); ?>" target="<?php echo esc_attr( $logo_target ); ?>"<?php echo $logo_rel ? ' rel="' . esc_attr( $logo_rel ) . '"' : ''; ?> class="osmo-logo">
						<?php if ( $logo_image ) : ?>
							<img src="<?php echo esc_url( $logo_image ); ?>" alt="" class="osmo-logo__img">
						<?php else : ?>
							<?php echo self::osmo_logo_svg(); ?>
						<?php endif; ?>
					</a>
					<div class="crisp-header__hamburger">
						<div class="crisp-header__hamburger-bar"></div>
						<div class="crisp-header__hamburger-bar"></div>
						<div class="crisp-header__hamburger-bar"></div>
					</div>
				</div>
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
			'--egsap-crisp-bg'   => $s[ self::key( 'bg_color' ) ] ?? '',
			'--egsap-crisp-text' => $s[ self::key( 'text_color' ) ] ?? '',
			'--egsap-crisp-fade' => $s[ self::key( 'fade_color' ) ] ?? '',
		];

		$props = [];
		foreach ( $map as $var => $value ) {
			if ( '' !== $value ) {
				$props[] = $var . ': ' . $value;
			}
		}

		return $props ? ' style="' . esc_attr( implode( '; ', $props ) ) . '"' : '';
	}

	private static function osmo_logo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 110 25" fill="none" class="osmo-logo__svg"><path d="M38.6535 24.1686C42.7849 24.1686 46.4296 22.0917 48.6049 18.9263C49.8544 22.1497 53.0867 24.1686 57.3663 24.1686C60.4495 24.1686 63.0501 23.1833 64.721 21.5632L64.4802 23.6683H69.7007L70.9503 12.7679L73.8514 23.6683H79.0769L81.978 12.7679L83.2268 23.6683H88.4473L87.8882 18.7885C90.0514 22.0313 93.7421 24.1686 97.933 24.1686C104.597 24.1686 110 18.766 110 12.1016C110 5.43732 104.596 0.0346429 97.9314 0.0346429C92.7608 0.0346429 88.3515 3.28785 86.6338 7.85749L85.7903 0.499502H80.0211L76.4625 13.8708L72.904 0.499502H67.1348L66.3243 7.56906C66.226 5.51224 65.3817 3.64878 63.9251 2.29932C62.3017 0.795175 60.0338 0 57.3655 0C54.8656 0 52.7113 0.712193 51.1354 2.06004C49.9737 3.05421 49.2131 4.33761 48.9191 5.76119C46.793 2.32429 42.9919 0.0346429 38.6535 0.0346429C31.9892 0.0346429 26.5865 5.43732 26.5865 12.1016C26.5865 18.766 31.9892 24.1686 38.6535 24.1686ZM97.9314 5.46471C101.597 5.46471 104.568 8.43594 104.568 12.1016C104.568 15.7673 101.597 18.7386 97.9314 18.7386C94.2657 18.7386 91.2945 15.7673 91.2945 12.1016C91.2945 8.43594 94.2657 5.46471 97.9314 5.46471ZM57.3663 5.05786C59.6318 5.05786 61.0223 6.10681 61.0852 7.86393L61.1045 8.39808H66.23L65.7015 13.0128C65.4389 12.5899 65.1271 12.1991 64.7637 11.8438C63.5682 10.6773 61.8151 9.88289 59.552 9.48328L56.501 8.93706C54.4797 8.5729 54.0656 7.94127 54.0656 7.10501C54.0656 6.89554 54.1582 5.05705 57.3663 5.05705V5.05786ZM55.1757 14.0094L58.7705 14.6837C61.0916 15.1293 61.4042 16.0711 61.4042 16.9339C61.4042 18.2963 59.8565 19.1422 57.3647 19.1422C54.4055 19.1422 53.2873 17.4729 53.2285 16.0437L53.2067 15.5128H50.2275C50.5457 14.4308 50.7197 13.2868 50.7197 12.1016C50.7197 12.0452 50.7165 11.9889 50.7157 11.9325C51.7872 12.95 53.2833 13.6598 55.1749 14.0094H55.1757ZM38.6535 5.46471C42.3192 5.46471 45.2904 8.43594 45.2904 12.1016C45.2904 15.7673 42.3192 18.7386 38.6535 18.7386C34.9878 18.7386 32.0166 15.7673 32.0166 12.1016C32.0166 8.43594 34.9878 5.46471 38.6535 5.46471Z" fill="currentColor"></path><path d="M16.3506 9.9554L21.6985 4.6075L19.5619 2.47092L14.214 7.81882C13.986 8.04762 13.5953 7.88569 13.5953 7.56262V0H10.5741V9.12397C10.5741 9.92478 9.92476 10.5741 9.12395 10.5741H0V13.5953H7.56261C7.88567 13.5953 8.04761 13.9861 7.8188 14.2141L2.47172 19.5619L4.6083 21.6985L9.95618 16.3506C10.1842 16.1226 10.5749 16.2838 10.5749 16.6068V24.1694H13.5961V15.0455C13.5961 14.2447 14.2454 13.5953 15.0463 13.5953H24.1702V10.5741H16.6076C16.2845 10.5741 16.1226 10.1834 16.3514 9.9554H16.3506Z" fill="currentColor" fill-opacity="0.75"></path></svg>';
	}
}
