<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Logo_Wall_Cycle_Widget extends Widget_Base {

	public function get_name() {
		return 'logo_wall_cycle';
	}

	public function get_title() {
		return __( 'Logo Wall Cycle', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sections' ];
	}

	public function get_keywords() {
		return [ 'logo', 'wall', 'cycle', 'grid', 'brands', 'gsap', 'scrolltrigger', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-scrolltrigger', 'elementor-logo-wall-cycle' ];
	}

	public function get_style_depends() {
		return [ 'elementor-logo-wall-cycle' ];
	}

	private function osmo_default_logos() {
		$base = 'https://cdn.prod.website-files.com/68836e3f51ac98fec14ceed2/';
		$slugs = [
			[ '688370ea9d37fbceb3be49cb_logo-webflow.svg',    'Webflow' ],
			[ '688370ea48d4fb0c708dd1dc_logo-microsoft.svg',  'Microsoft' ],
			[ '688370ea9ba384ff47fa5d51_logo-asana.svg',      'Asana' ],
			[ '688370eaec918fbd4a0acc12_logo-snapchat.svg',   'Snapchat' ],
			[ '688370ea155a551c08692a03_logo-google.svg',     'Google' ],
			[ '688370eafdf2b295d65f9450_logo-bluesky.svg',    'Bluesky' ],
			[ '688370ea68a433ee5808ed90_logo-codepen.svg',    'CodePen' ],
			[ '688370ea2ebc0415055d04f3_logo-linkedin.svg',   'LinkedIn' ],
			[ '688370ea7699561e6f9f008f_logo-android.svg',    'Android' ],
			[ '688370ea753f2afe2f6b036f_logo-apple.svg',      'Apple' ],
			[ '688370eabec1e0c00348b5ed_logo-twitter.svg',    'Twitter' ],
			[ '688370ea0e0e1dc81a9b5799_logo-osmo.svg',       'Osmo' ],
			[ '688370ea36c91584afe43e2d_logo-medium.svg',     'Medium' ],
			[ '688370ea87b05cdce0387084_logo-eventbrite.svg', 'Eventbrite' ],
			[ '688370eaf4465d763c2f9b2a_logo-behance.svg',    'Behance' ],
			[ '688370eaec1d445957d7e3a1_logo-chatgpt.svg',    'ChatGPT' ],
		];
		$out = [];
		foreach ( $slugs as $s ) {
			$out[] = [
				'image' => [ 'url' => $base . $s[0] ],
				'alt'   => $s[1],
				'link'  => [ 'url' => '' ],
			];
		}
		return $out;
	}

	protected function register_controls() {

		/* === CONTENT: LOGOS === */
		$this->start_controls_section( 'content_logos', [
			'label' => __( 'Logos', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Logo', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'alt', [
			'label'   => __( 'Alt Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'link', [
			'label'       => __( 'Link (opsional)', 'elementor-gsap' ),
			'description' => __( 'Kosongkan untuk logo tanpa link.', 'elementor-gsap' ),
			'type'        => Controls_Manager::URL,
			'default'     => [ 'url' => '' ],
		] );

		$this->add_control( 'logos', [
			'label'       => __( 'Logos', 'elementor-gsap' ),
			'description' => __( 'Isi lebih banyak logo daripada jumlah visible per breakpoint supaya ada pool untuk cycling swap.', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ alt }}}',
			'default'     => $this->osmo_default_logos(),
		] );

		$this->end_controls_section();

		/* === CONTENT: ANIMATION === */
		$this->start_controls_section( 'content_anim', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'loop_delay', [
			'label'       => __( 'Loop Delay (s)', 'elementor-gsap' ),
			'description' => __( 'Jeda antar swap. Kecil = swap cepat/lebih sering.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 10,
			'step'        => 0.1,
			'default'     => 1.5,
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Swap Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi animasi in/out per swap.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.9,
		] );

		$this->add_control( 'shuffle_front', [
			'label'        => __( 'Shuffle Initial Order', 'elementor-gsap' ),
			'description'  => __( 'On: 8 logo pertama yang tampil di-random dari seluruh pool. Off: pakai urutan repeater apa adanya.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->end_controls_section();

		/* === STYLE: GRID === */
		$this->start_controls_section( 'style_grid', [
			'label' => __( 'Grid', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'cols', [
			'label'       => __( 'Columns per Row', 'elementor-gsap' ),
			'description' => __( 'Kolom per baris. Default: 4 desktop / 3 tablet / 2 mobile.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 1,
			'max'         => 8,
			'step'        => 1,
			'default'     => 4,
			'tablet_default' => 3,
			'mobile_default' => 2,
			'selectors'   => [
				'{{WRAPPER}} .logo-wall' => '--lw-cols: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'visible', [
			'label'       => __( 'Visible Items', 'elementor-gsap' ),
			'description' => __( 'Jumlah logo yang tampil bersamaan. Sisanya jadi pool untuk cycling. Default: 8 desktop / 6 tablet / 4 mobile.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 1,
			'max'         => 32,
			'step'        => 1,
			'default'     => 8,
			'tablet_default' => 6,
			'mobile_default' => 4,
		] );

		$this->add_responsive_control( 'item_aspect', [
			'label'      => __( 'Item Aspect (padding-top %)', 'elementor-gsap' ),
			'description' => __( '66.66% ≈ ratio 3:2. Naikkan untuk kotak lebih tinggi, turunkan untuk lebih pipih.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range'      => [ '%' => [ 'min' => 20, 'max' => 150, 'step' => 0.5 ] ],
			'default'    => [ 'unit' => '%', 'size' => 66.66 ],
			'selectors'  => [
				'{{WRAPPER}} .logo-wall' => '--lw-item-aspect: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'wall_bg', [
			'label'     => __( 'Wall Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .logo-wall' => '--lw-wall-bg: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: LOGO === */
		$this->start_controls_section( 'style_logo', [
			'label' => __( 'Logo', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'target_w', [
			'label'      => __( 'Logo Target Width', 'elementor-gsap' ),
			'description' => __( 'Lebar area logo relatif ke item. Default 66.66%.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range'      => [ '%' => [ 'min' => 20, 'max' => 100, 'step' => 0.5 ] ],
			'default'    => [ 'unit' => '%', 'size' => 66.66 ],
			'selectors'  => [
				'{{WRAPPER}} .logo-wall' => '--lw-target-w: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'target_h', [
			'label'      => __( 'Logo Target Height', 'elementor-gsap' ),
			'description' => __( 'Tinggi area logo relatif ke item. Default 40%.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range'      => [ '%' => [ 'min' => 10, 'max' => 100, 'step' => 0.5 ] ],
			'default'    => [ 'unit' => '%', 'size' => 40 ],
			'selectors'  => [
				'{{WRAPPER}} .logo-wall' => '--lw-target-h: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'img_filter', [
			'label'       => __( 'Image Filter', 'elementor-gsap' ),
			'description' => __( 'Contoh: <code>grayscale(1)</code>, <code>brightness(0.5)</code>, <code>none</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'none',
			'selectors'   => [
				'{{WRAPPER}} .logo-wall' => '--lw-img-filter: {{VALUE}};',
			],
		] );

		$this->add_control( 'img_filter_hover', [
			'label'       => __( 'Image Filter (Hover)', 'elementor-gsap' ),
			'description' => __( 'Filter saat hover logo. Kosong = ikuti filter normal.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'none',
			'selectors'   => [
				'{{WRAPPER}} .logo-wall' => '--lw-img-filter-hover: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Emit inline <style> block per-instance untuk aturan visibility
	 * (:nth-child) — pseudo-class butuh literal number, tidak bisa pakai
	 * CSS var. Scope pakai widget ID Elementor supaya tidak bocor ke
	 * instance lain.
	 */
	private function build_visibility_style( $widget_id, $vis_desktop, $vis_tablet, $vis_mobile ) {
		$sel = '.elementor-element-' . esc_attr( $widget_id ) . ' .logo-wall .logo-wall__list .logo-wall__item';
		$vd  = max( 1, intval( $vis_desktop ) );
		$vt  = max( 1, intval( $vis_tablet ) );
		$vm  = max( 1, intval( $vis_mobile ) );

		$css  = '';
		$css .= $sel . '{display:none;}';
		$css .= $sel . ':nth-child(-n+' . $vd . '){display:block;}';
		$css .= '@media (max-width:1024px){' . $sel . ':nth-child(n+' . ( $vt + 1 ) . '){display:none;}}';
		$css .= '@media (max-width:767px){'  . $sel . ':nth-child(n+' . ( $vm + 1 ) . '){display:none;}}';

		return '<style>' . $css . '</style>';
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$logos = ! empty( $s['logos'] ) ? $s['logos'] : [];

		if ( empty( $logos ) ) {
			return;
		}

		$loop_delay    = isset( $s['loop_delay'] ) && '' !== $s['loop_delay'] ? floatval( $s['loop_delay'] ) : 1.5;
		$duration      = isset( $s['duration'] )   && '' !== $s['duration']   ? floatval( $s['duration'] )   : 0.9;
		$shuffle_front = ! empty( $s['shuffle_front'] ) && 'yes' === $s['shuffle_front'];

		$vis_desktop = isset( $s['visible'] )        && '' !== $s['visible']        ? intval( $s['visible'] )        : 8;
		$vis_tablet  = isset( $s['visible_tablet'] ) && '' !== $s['visible_tablet'] ? intval( $s['visible_tablet'] ) : 6;
		$vis_mobile  = isset( $s['visible_mobile'] ) && '' !== $s['visible_mobile'] ? intval( $s['visible_mobile'] ) : 4;

		echo $this->build_visibility_style( $this->get_id(), $vis_desktop, $vis_tablet, $vis_mobile ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<div
			data-logo-wall-cycle-init
			data-logo-wall-shuffle="<?php echo $shuffle_front ? 'true' : 'false'; ?>"
			data-logo-wall-loop-delay="<?php echo esc_attr( $loop_delay ); ?>"
			data-logo-wall-duration="<?php echo esc_attr( $duration ); ?>"
			class="logo-wall"
		>
			<div class="logo-wall__collection">
				<div data-logo-wall-list class="logo-wall__list">
					<?php foreach ( $logos as $logo ) :
						$img_url = ! empty( $logo['image']['url'] ) ? $logo['image']['url'] : '';
						if ( '' === $img_url ) {
							continue;
						}
						$alt      = isset( $logo['alt'] )         ? $logo['alt']            : '';
						$link_url = ! empty( $logo['link']['url'] ) ? $logo['link']['url'] : '';
						$link_ext = ! empty( $logo['link']['is_external'] );
						$link_nof = ! empty( $logo['link']['nofollow'] );
						$rel      = trim( ( $link_ext ? 'noopener' : '' ) . ( $link_nof ? ' nofollow' : '' ) );
						?>
						<div data-logo-wall-item class="logo-wall__item">
							<div data-logo-wall-target-parent class="logo-wall__logo">
								<div class="logo-wall__logo-before"></div>
								<div data-logo-wall-target class="logo-wall__logo-target">
									<?php if ( '' !== $link_url ) : ?>
										<a
											href="<?php echo esc_url( $link_url ); ?>"
											class="logo-wall__logo-link"
											<?php echo $link_ext ? ' target="_blank"' : ''; ?>
											<?php echo '' !== $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?>
										>
											<img src="<?php echo esc_url( $img_url ); ?>" loading="lazy" alt="<?php echo esc_attr( $alt ); ?>" class="logo-wall__logo-img">
										</a>
									<?php else : ?>
										<img src="<?php echo esc_url( $img_url ); ?>" loading="lazy" alt="<?php echo esc_attr( $alt ); ?>" class="logo-wall__logo-img">
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
