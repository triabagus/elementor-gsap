<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sticky_Features_Widget extends Widget_Base {

	public function get_name() {
		return 'sticky_features';
	}

	public function get_title() {
		return __( 'Sticky Features', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	public function get_categories() {
		return [ 'elementor-gsap-scroll' ];
	}

	public function get_keywords() {
		return [ 'sticky', 'features', 'scroll', 'scrolltrigger', 'pin', 'clip-path', 'gsap', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-scrolltrigger', 'elementor-sticky-features' ];
	}

	public function get_style_depends() {
		return [ 'elementor-sticky-features' ];
	}

	private function default_features() {
		$base = 'https://cdn.prod.website-files.com/68b83c9a431270d8deb1e6b1/';
		return [
			[
				'image'     => [ 'url' => $base . '68b847956497fe87b81b7025_Iced%20Matcha%20Latte.avif' ],
				'tag'       => '01',
				'heading'   => 'Fresh Iced Matcha Latte',
				'paragraph' => 'A glass of iced matcha latte with a metal straw, sitting on a red surface against a dark background, showcasing its vibrant green color.',
				'link_text' => 'Learn more',
				'link_url'  => [ 'url' => '#' ],
			],
			[
				'image'     => [ 'url' => $base . '68b847956468e74ee70e259e_Matcha%20Whisking%20Art.avif' ],
				'tag'       => '02',
				'heading'   => 'Matcha Whisking Art',
				'paragraph' => 'A hand sprinkles green powder using a bamboo whisk into another hand, set against a dark fabric background, creating a dramatic visual.',
				'link_text' => 'Learn more',
				'link_url'  => [ 'url' => '#' ],
			],
			[
				'image'     => [ 'url' => $base . '68b84795dd49cb5a5f2a2640_Steaming%20Orange%20Beverage.avif' ],
				'tag'       => '03',
				'heading'   => 'Steaming Orange Fizz',
				'paragraph' => 'A glass of orange beverage with a sugared rim and a floating leaf, emitting steam, set against a warm orange background.',
				'link_text' => 'Learn more',
				'link_url'  => [ 'url' => '#' ],
			],
			[
				'image'     => [ 'url' => $base . '68b8479522525b321756af2e_Hands%20Holding%20Matcha%20Cup.avif' ],
				'tag'       => '04',
				'heading'   => 'Home Away From Home',
				'paragraph' => 'Hands holding a copper cup of green matcha tea on a brown surface, adorned with simple bracelets, creating a warm and serene scene.',
				'link_text' => 'Learn more',
				'link_url'  => [ 'url' => '#' ],
			],
		];
	}

	protected function register_controls() {

		/* === CONTENT: FEATURES === */
		$this->start_controls_section( 'content_features', [
			'label' => __( 'Features', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Visual', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'tag', [
			'label'   => __( 'Tag', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '01',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'heading', [
			'label'   => __( 'Heading', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Feature Title',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'paragraph', [
			'label'   => __( 'Description', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => 'Short description that explains this feature.',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'link_text', [
			'label'       => __( 'Link Text', 'elementor-gsap' ),
			'description' => __( 'Kosongkan kalau tidak ingin ada teks link.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'Learn more',
			'dynamic'     => [ 'active' => true ],
		] );
		$rep->add_control( 'link_url', [
			'label'       => __( 'Link URL', 'elementor-gsap' ),
			'description' => __( 'Kalau kosong tapi Link Text ada, tampilkan sebagai teks non-clickable.', 'elementor-gsap' ),
			'type'        => Controls_Manager::URL,
			'default'     => [ 'url' => '' ],
		] );

		$this->add_control( 'features', [
			'label'       => __( 'Features', 'elementor-gsap' ),
			'description' => __( 'Jumlah image & text harus sama (1 image = 1 text card).', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ tag }}} — {{{ heading }}}',
			'default'     => $this->default_features(),
		] );

		$this->end_controls_section();

		/* === CONTENT: ANIMATION === */
		$this->start_controls_section( 'content_anim', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Transition Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi swap image + text per step.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.75,
		] );

		$this->add_control( 'scroll_amount', [
			'label'       => __( 'Scroll Amount', 'elementor-gsap' ),
			'description' => __( 'Persentase scroll yang dipakai untuk transisi (0.1–1). Default 0.9 = 90% scroll dipakai untuk swap, 10% sisanya untuk item terakhir tetap terlihat sebelum unpin.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.9,
		] );

		$this->add_control( 'respect_reduced_motion', [
			'label'        => __( 'Respect Reduced Motion', 'elementor-gsap' ),
			'description'  => __( 'Pakai durasi instant (0.01s) untuk user dengan setting <code>prefers-reduced-motion: reduce</code>.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->end_controls_section();

		/* === STYLE: LAYOUT === */
		$this->start_controls_section( 'style_layout', [
			'label' => __( 'Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'container_max', [
			'label'      => __( 'Container Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 30, 'max' => 120, 'step' => 0.5 ],
				'px' => [ 'min' => 480, 'max' => 1920 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 70 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-container-max: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'container_pad_x', [
			'label'      => __( 'Container Padding X', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-container-pad-x: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'col_gap', [
			'label'      => __( 'Column Gap', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-col-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'img_aspect', [
			'label'       => __( 'Image Aspect Ratio', 'elementor-gsap' ),
			'description' => __( 'Format <code>width / height</code>. Contoh: <code>1 / 1.3</code>, <code>1 / 1</code>, <code>4 / 5</code>, <code>16 / 9</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '1 / 1.3',
			'selectors'   => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-img-aspect: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_max', [
			'label'      => __( 'Text Card Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 15, 'max' => 60, 'step' => 0.5 ],
				'px' => [ 'min' => 240, 'max' => 900 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 27.5 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-text-max: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_gap', [
			'label'      => __( 'Text Vertical Gap', 'elementor-gsap' ),
			'description' => __( 'Jarak antar Tag / Heading / Description / Link.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.5 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-text-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: TAG === */
		$this->start_controls_section( 'style_tag', [
			'label' => __( 'Tag', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'tag_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-tag-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'tag_bg', [
			'label'     => __( 'Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.08)',
			'selectors' => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-tag-bg: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'tag_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-tag-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'tag_typography',
			'selector' => '{{WRAPPER}} .sticky-features__tag',
		] );

		$this->end_controls_section();

		/* === STYLE: HEADING === */
		$this->start_controls_section( 'style_heading', [
			'label' => __( 'Heading', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'heading_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-heading-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .sticky-features__heading',
		] );

		$this->end_controls_section();

		/* === STYLE: DESCRIPTION === */
		$this->start_controls_section( 'style_paragraph', [
			'label' => __( 'Description', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'p_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.7)',
			'selectors' => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-p-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'p_typography',
			'selector' => '{{WRAPPER}} .sticky-features__p:not(.is--link)',
		] );

		$this->end_controls_section();

		/* === STYLE: LINK === */
		$this->start_controls_section( 'style_link', [
			'label' => __( 'Link', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'link_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-link-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'link_typography',
			'selector' => '{{WRAPPER}} .sticky-features__p.is--link',
		] );

		$this->end_controls_section();

		/* === STYLE: PROGRESS BAR === */
		$this->start_controls_section( 'style_progress', [
			'label' => __( 'Progress Bar', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'progress_track', [
			'label'     => __( 'Track Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.15)',
			'selectors' => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-progress-track: {{VALUE}};',
			],
		] );

		$this->add_control( 'progress_bar', [
			'label'     => __( 'Bar Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-progress-bar: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'progress_height', [
			'label'      => __( 'Bar Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.05, 'max' => 1, 'step' => 0.01 ],
				'px' => [ 'min' => 1, 'max' => 16 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-features__wrap' => '--sf-progress-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function is_edit_mode() {
		return class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$features = ! empty( $s['features'] ) ? $s['features'] : [];

		if ( empty( $features ) ) {
			return;
		}

		$duration        = isset( $s['duration'] )      && '' !== $s['duration']      ? floatval( $s['duration'] )      : 0.75;
		$scroll_amount   = isset( $s['scroll_amount'] ) && '' !== $s['scroll_amount'] ? floatval( $s['scroll_amount'] ) : 0.9;
		$respect_rm      = ! empty( $s['respect_reduced_motion'] ) && 'yes' === $s['respect_reduced_motion'];
		$radius          = isset( $s['radius']['size'], $s['radius']['unit'] ) && '' !== $s['radius']['size']
			? floatval( $s['radius']['size'] ) . $s['radius']['unit']
			: '0.75em';

		$edit_class = $this->is_edit_mode() ? ' egsap-edit-mode' : '';
		?>
		<div
			data-sticky-feature-wrap
			data-sticky-feature-duration="<?php echo esc_attr( $duration ); ?>"
			data-sticky-feature-scroll-amount="<?php echo esc_attr( $scroll_amount ); ?>"
			data-sticky-feature-respect-rm="<?php echo $respect_rm ? 'true' : 'false'; ?>"
			data-sticky-feature-radius="<?php echo esc_attr( $radius ); ?>"
			class="sticky-features__wrap<?php echo esc_attr( $edit_class ); ?>"
		>
			<div class="sticky-features__scroll">
				<div class="sticky-features__container">
					<div class="sticky-feaures__col is--img">
						<div class="sticky-features__img-collection">
							<div class="sticky-features__img-list">
								<?php foreach ( $features as $f ) :
									$img_url = ! empty( $f['image']['url'] ) ? $f['image']['url'] : '';
									if ( '' === $img_url ) {
										continue;
									}
									?>
									<div data-sticky-feature-visual-wrap class="sticky-features__img-item">
										<img src="<?php echo esc_url( $img_url ); ?>" loading="lazy" alt="" class="sticky-features__img">
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="sticky-features__progress-w">
							<div class="sticky-features__progress-bar" data-sticky-feature-progress></div>
						</div>
					</div>
					<div class="sticky-feaures__col">
						<div class="sticky-features__text-collection">
							<div class="sticky-features__text-list">
								<?php foreach ( $features as $f ) :
									$tag       = isset( $f['tag'] )         ? $f['tag']         : '';
									$heading   = isset( $f['heading'] )     ? $f['heading']     : '';
									$paragraph = isset( $f['paragraph'] )   ? $f['paragraph']   : '';
									$link_text = isset( $f['link_text'] )   ? $f['link_text']   : '';
									$link_url  = ! empty( $f['link_url']['url'] ) ? $f['link_url']['url'] : '';
									$link_ext  = ! empty( $f['link_url']['is_external'] );
									$link_nof  = ! empty( $f['link_url']['nofollow'] );
									$rel       = trim( ( $link_ext ? 'noopener' : '' ) . ( $link_nof ? ' nofollow' : '' ) );
									?>
									<div data-sticky-feature-item class="sticky-features__text-item">
										<?php if ( '' !== $tag ) : ?>
											<span data-sticky-feature-text class="sticky-features__tag"><?php echo esc_html( $tag ); ?></span>
										<?php endif; ?>
										<?php if ( '' !== $heading ) : ?>
											<h2 data-sticky-feature-text class="sticky-features__heading"><?php echo esc_html( $heading ); ?></h2>
										<?php endif; ?>
										<?php if ( '' !== $paragraph ) : ?>
											<p data-sticky-feature-text class="sticky-features__p"><?php echo esc_html( $paragraph ); ?></p>
										<?php endif; ?>
										<?php if ( '' !== $link_text ) : ?>
											<p data-sticky-feature-text class="sticky-features__p is--link">
												<?php if ( '' !== $link_url ) : ?>
													<a
														href="<?php echo esc_url( $link_url ); ?>"
														<?php echo $link_ext ? ' target="_blank"' : ''; ?>
														<?php echo '' !== $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?>
													><?php echo esc_html( $link_text ); ?></a>
												<?php else : ?>
													<?php echo esc_html( $link_text ); ?>
												<?php endif; ?>
											</p>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
