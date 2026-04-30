<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bunny_HLS_Player_Widget extends Widget_Base {

	public function get_name() {
		return 'bunny_hls_player_basic';
	}

	public function get_title() {
		return __( 'Bunny HLS Player', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-video-camera';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'video', 'bunny', 'hls', 'player', 'm3u8', 'streaming' ];
	}

	public function get_script_depends() {
		return [ 'hls-js', 'elementor-bunny-hls-player' ];
	}

	public function get_style_depends() {
		return [ 'elementor-bunny-hls-player' ];
	}

	protected function register_controls() {

		/* === VIDEO === */
		$this->start_controls_section( 'video_section', [
			'label' => __( 'Video', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'video_src', [
			'label'       => __( 'HLS Source (.m3u8)', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://vz-xxx.b-cdn.net/.../playlist.m3u8',
			'default'     => 'https://vz-6ed806ff-5e5.b-cdn.net/b6a663de-07c1-4c37-8bb6-0e79fef7fb3c/playlist.m3u8',
			'description' => __( 'URL ke playlist HLS dari Bunny.net (atau provider HLS lain).', 'elementor-gsap' ),
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'placeholder_image', [
			'label'   => __( 'Placeholder Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => 'https://cdn.prod.website-files.com/68d1258667a36fff8e2a0887/68d12711fc7a7d993a8d46dc_player-placeholder.jpg' ],
		] );

		$this->end_controls_section();

		/* === SETTINGS === */
		$this->start_controls_section( 'settings_section', [
			'label' => __( 'Player Settings', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'autoplay', [
			'label'        => __( 'Autoplay', 'elementor-gsap' ),
			'description'  => __( 'Autoplay otomatis muted + loop sesuai browser policy. Play/pause dikontrol via IntersectionObserver.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'true',
			'default'      => '',
		] );

		$this->add_control( 'muted', [
			'label'        => __( 'Start Muted', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'true',
			'default'      => '',
			'condition'    => [ 'autoplay!' => 'true' ],
		] );

		$this->add_control( 'lazy', [
			'label'       => __( 'Lazy Loading', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'description' => __( 'meta = preload duration & ratio saja. true = tunggu user click play.', 'elementor-gsap' ),
			'options'     => [
				''     => __( 'Eager (load on init)', 'elementor-gsap' ),
				'meta' => __( 'Meta only (preload metadata)', 'elementor-gsap' ),
				'true' => __( 'Full lazy (load on play)', 'elementor-gsap' ),
			],
			'default'     => 'meta',
		] );

		$this->add_control( 'update_size', [
			'label'       => __( 'Aspect Ratio', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'description' => __( 'Auto = ambil rasio dari video. Cover = isi penuh container (butuh height eksplisit).', 'elementor-gsap' ),
			'options'     => [
				''      => __( 'Default (16:10 / 62.5% padding)', 'elementor-gsap' ),
				'true'  => __( 'Auto from video', 'elementor-gsap' ),
				'cover' => __( 'Cover container', 'elementor-gsap' ),
			],
			'default'     => '',
		] );

		$this->end_controls_section();

		/* === STYLE === */
		$this->start_controls_section( 'style_section', [
			'label' => __( 'Style', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
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
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .bunny-player' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_color', [
			'label'     => __( 'Icon / Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .bunny-player' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'overlay_color', [
			'label'     => __( 'Dark Overlay Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .bunny-player__dark' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'big_btn_bg', [
			'label'     => __( 'Big Button Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(100, 100, 100, 0.2)',
			'selectors' => [
				'{{WRAPPER}} .bunny-player__big-btn' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s           = $this->get_settings_for_display();
		$src         = ! empty( $s['video_src'] ) ? $s['video_src'] : '';
		$placeholder = ! empty( $s['placeholder_image']['url'] ) ? $s['placeholder_image']['url'] : '';
		$autoplay    = 'true' === $s['autoplay'] ? 'true' : 'false';
		$muted       = ( 'true' === $s['autoplay'] || 'true' === $s['muted'] ) ? 'true' : 'false';
		$lazy        = isset( $s['lazy'] ) ? $s['lazy'] : '';
		$update_size = isset( $s['update_size'] ) ? $s['update_size'] : '';
		?>
		<div class="bunny-player"
			data-bunny-player-init=""
			data-player-muted="<?php echo esc_attr( $muted ); ?>"
			data-player-activated="false"
			data-player-autoplay="<?php echo esc_attr( $autoplay ); ?>"
			data-player-hover="idle"
			data-player-src="<?php echo esc_url( $src ); ?>"
			data-player-status="idle"
			data-player-update-size="<?php echo esc_attr( $update_size ); ?>"
			data-player-lazy="<?php echo esc_attr( $lazy ); ?>">
			<div data-player-before="" class="bunny-player__before"></div>
			<video preload="auto" width="1920" height="1080" playsinline class="bunny-player__video"></video>
			<?php if ( $placeholder ) : ?>
				<img src="<?php echo esc_url( $placeholder ); ?>" alt="" class="bunny-player__placeholder">
			<?php endif; ?>
			<div class="bunny-player__dark"></div>
			<div data-player-control="playpause" class="bunny-player__playpause">
				<div class="bunny-player__big-btn">
					<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 24 24" fill="none" class="bunny-player__pause-svg"><path d="M16 5V19" stroke="currentColor" stroke-width="3" stroke-miterlimit="10"></path><path d="M8 5V19" stroke="currentColor" stroke-width="3" stroke-miterlimit="10"></path></svg>
					<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 24 24" fill="none" class="bunny-player__play-svg"><path d="M6 12V5.01109C6 4.05131 7.03685 3.4496 7.87017 3.92579L14 7.42855L20.1007 10.9147C20.9405 11.3945 20.9405 12.6054 20.1007 13.0853L14 16.5714L7.87017 20.0742C7.03685 20.5503 6 19.9486 6 18.9889V12Z" fill="currentColor"></path></svg>
				</div>
			</div>
			<!-- <div class="bunny-player__loading">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve" width="100%" class="bunny-player__loading-svg" fill="none"><path fill="currentColor" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"></path><animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform></svg>
			</div> -->
			<div class="bunny-player__loading" bis_skin_checked="1"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="L9" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve" width="100%" class="bunny-player__loading-svg vimeo-player__loading-svg" fill="none"><path fill="currentColor" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"><animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform></path></svg></div>
		</div>
		<?php
	}
}
