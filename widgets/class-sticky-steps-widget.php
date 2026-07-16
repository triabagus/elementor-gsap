<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sticky_Steps_Widget extends Widget_Base {

	public function get_name() {
		return 'sticky_steps';
	}

	public function get_title() {
		return __( 'Sticky Steps (Basic)', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-scroll';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sections' ];
	}

	public function get_keywords() {
		return [ 'sticky', 'steps', 'scroll', 'scrolltrigger', 'section', 'gsap', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-scrolltrigger', 'elementor-sticky-steps' ];
	}

	public function get_style_depends() {
		return [ 'elementor-sticky-steps' ];
	}

	protected function register_controls() {

		/* === CONTENT: STEPS === */
		$this->start_controls_section( 'content_steps', [
			'label' => __( 'Steps', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'eyebrow', [
			'label'   => __( 'Eyebrow', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Feature A',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'heading', [
			'label'   => __( 'Heading', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Sticky Steps',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'paragraph', [
			'label'   => __( 'Description', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'In CSS, position: sticky is a hybrid positioning method that combines the behaviors of relative and fixed positioning.',
			'rows'    => 4,
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'image', [
			'label'   => __( 'Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'steps', [
			'label'       => __( 'Steps', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ heading }}}',
			'default'     => [
				[
					'eyebrow'   => 'Feature A',
					'heading'   => 'Sticky Steps',
					'paragraph' => 'In CSS, position: sticky is a hybrid positioning method that combines the behaviors of relative and fixed positioning.',
					'image'     => [ 'url' => 'https://cdn.prod.website-files.com/69ae9e6ddf70dcdf27a5f726/69aeb2dec0b5fa47975b9542_placeholder-4.avif' ],
				],
				[
					'eyebrow'   => 'Feature B',
					'heading'   => 'Hybrid positioning',
					'paragraph' => 'In CSS, position: sticky is a hybrid positioning method that combines the behaviors of relative and fixed positioning.',
					'image'     => [ 'url' => 'https://cdn.prod.website-files.com/69ae9e6ddf70dcdf27a5f726/69aeb2deb2fd62dc067748f0_placeholder-3.avif' ],
				],
				[
					'eyebrow'   => 'Feature C',
					'heading'   => 'CSS Position',
					'paragraph' => 'In CSS, position: sticky is a hybrid positioning method that combines the behaviors of relative and fixed positioning.',
					'image'     => [ 'url' => 'https://cdn.prod.website-files.com/69ae9e6ddf70dcdf27a5f726/69aeb2de97fa626a31cfa6d6_placeholder-2.avif' ],
				],
				[
					'eyebrow'   => 'Feature D',
					'heading'   => 'The last step',
					'paragraph' => 'In CSS, position: sticky is a hybrid positioning method that combines the behaviors of relative and fixed positioning.',
					'image'     => [ 'url' => 'https://cdn.prod.website-files.com/69ae9e6ddf70dcdf27a5f726/69aeb2dece70d4c848502473_placeholder-1.avif' ],
				],
			],
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
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 20, 'max' => 120, 'step' => 0.5 ],
				'px' => [ 'min' => 320, 'max' => 1920 ],
				'%'  => [ 'min' => 30, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 74 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-steps' => '--sts-container-max: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'container_pad_x', [
			'label'      => __( 'Container Padding X', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 96 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.5 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-steps' => '--sts-container-pad-x: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'step_gap', [
			'label'       => __( 'Step Gap', 'elementor-gsap' ),
			'description' => __( 'Jarak antar step. Default 30dvh; kecil = step berdekatan, besar = scroll lebih lama per step.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'dvh', 'vh', 'em', 'px' ],
			'range'       => [
				'dvh' => [ 'min' => 0, 'max' => 100 ],
				'vh'  => [ 'min' => 0, 'max' => 100 ],
				'em'  => [ 'min' => 0, 'max' => 40, 'step' => 0.5 ],
				'px'  => [ 'min' => 0, 'max' => 500 ],
			],
			'default'     => [ 'unit' => 'dvh', 'size' => 30 ],
			'selectors'   => [
				'{{WRAPPER}} .sticky-steps' => '--sts-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_width', [
			'label'      => __( 'Text Column Width', 'elementor-gsap' ),
			'description' => __( 'Sisa lebar dipakai oleh media/image column. Hanya aktif di desktop (≥992px).', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range'      => [ '%' => [ 'min' => 20, 'max' => 80 ] ],
			'default'    => [ 'unit' => '%', 'size' => 50 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-steps' => '--sts-text-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_pad_right', [
			'label'      => __( 'Text Right Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 12, 'step' => 0.25 ],
				'px' => [ 'min' => 0, 'max' => 200 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 6 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-steps' => '--sts-text-pad-right: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'media_pad_left', [
			'label'      => __( 'Media Left Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 12, 'step' => 0.25 ],
				'px' => [ 'min' => 0, 'max' => 200 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-steps' => '--sts-media-pad-left: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'sticky_top', [
			'label'       => __( 'Sticky Top Offset', 'elementor-gsap' ),
			'description' => __( 'Jarak dari atas viewport saat media di-stick. Naikkan kalau ada header fixed di atasnya.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 200 ],
			],
			'default'     => [ 'unit' => 'px', 'size' => 0 ],
			'selectors'   => [
				'{{WRAPPER}} .sticky-steps' => '--sts-sticky-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: EYEBROW === */
		$this->start_controls_section( 'style_eyebrow', [
			'label' => __( 'Eyebrow', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'eyebrow_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sticky-steps' => '--sts-eyebrow-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'eyebrow_opacity', [
			'label'   => __( 'Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default' => [ 'unit' => 'px', 'size' => 0.5 ],
			'selectors' => [
				'{{WRAPPER}} .sticky-steps' => '--sts-eyebrow-opacity: {{SIZE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'eyebrow_typography',
			'selector' => '{{WRAPPER}} .sticky-steps__eyebrow',
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
			'selectors' => [
				'{{WRAPPER}} .sticky-steps' => '--sts-heading-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .sticky-steps__h2',
		] );

		$this->end_controls_section();

		/* === STYLE: PARAGRAPH === */
		$this->start_controls_section( 'style_paragraph', [
			'label' => __( 'Description', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'p_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sticky-steps' => '--sts-p-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'p_opacity', [
			'label'   => __( 'Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default' => [ 'unit' => 'px', 'size' => 0.6 ],
			'selectors' => [
				'{{WRAPPER}} .sticky-steps' => '--sts-p-opacity: {{SIZE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'p_typography',
			'selector' => '{{WRAPPER}} .sticky-steps__p',
		] );

		$this->end_controls_section();

		/* === STYLE: VISUAL / IMAGE === */
		$this->start_controls_section( 'style_visual', [
			'label' => __( 'Visual / Image', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'visual_aspect', [
			'label'       => __( 'Aspect Ratio', 'elementor-gsap' ),
			'description' => __( 'Format: width / height. Contoh: <code>3 / 4</code>, <code>1 / 1</code>, <code>4 / 3</code>, <code>16 / 9</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '3 / 4',
			'selectors'   => [
				'{{WRAPPER}} .sticky-steps' => '--sts-visual-aspect: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'visual_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'description' => __( 'Nilai besar (misal 500em) = full pill/oval. Turunkan untuk sudut lebih tegas.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 500, 'step' => 0.5 ],
				'px' => [ 'min' => 0, 'max' => 500 ],
				'%'  => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 500 ],
			'selectors'  => [
				'{{WRAPPER}} .sticky-steps' => '--sts-visual-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: STATES === */
		$this->start_controls_section( 'style_states', [
			'label' => __( 'States (Before / Active / After)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'inactive_text_opacity', [
			'label'       => __( 'Inactive Text Opacity', 'elementor-gsap' ),
			'description' => __( 'Opacity teks step yang tidak sedang active (before + after).', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'range'       => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default'     => [ 'unit' => 'px', 'size' => 0.25 ],
			'selectors'   => [
				'{{WRAPPER}} .sticky-steps' => '--sts-inactive-text-opacity: {{SIZE}};',
			],
		] );

		$this->add_control( 'state_duration', [
			'label'       => __( 'Transition Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi CSS fade antar state. Set kecil untuk swap cepat.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.05,
			'max'         => 2,
			'step'        => 0.05,
			'default'     => 0.5,
			'selectors'   => [
				'{{WRAPPER}} .sticky-steps' => '--sts-state-duration: {{VALUE}}s;',
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
		$s     = $this->get_settings_for_display();
		$steps = ! empty( $s['steps'] ) ? $s['steps'] : [];

		$root_classes = 'sticky-steps' . ( $this->is_edit_mode() ? ' egsap-edit-mode' : '' );
		?>
		<section class="<?php echo esc_attr( $root_classes ); ?>">
			<div class="sticky-steps__container">
				<div data-sticky-steps-init class="sticky-steps__collection">
					<div class="sticky-steps__list">
						<?php foreach ( $steps as $i => $step ) :
							$status    = 0 === $i ? 'active' : 'after';
							$eyebrow   = ! empty( $step['eyebrow'] )   ? $step['eyebrow']   : '';
							$heading   = ! empty( $step['heading'] )   ? $step['heading']   : '';
							$paragraph = ! empty( $step['paragraph'] ) ? $step['paragraph'] : '';
							$img_url   = ! empty( $step['image']['url'] ) ? $step['image']['url'] : '';
							?>
							<div data-sticky-steps-item data-sticky-steps-item-status="<?php echo esc_attr( $status ); ?>" class="sticky-steps__item">
								<div data-sticky-steps-anchor class="sticky-steps__text">
									<?php if ( '' !== $eyebrow ) : ?>
										<span class="sticky-steps__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
									<?php endif; ?>
									<?php if ( '' !== $heading ) : ?>
										<h2 class="sticky-steps__h2"><?php echo esc_html( $heading ); ?></h2>
									<?php endif; ?>
									<?php if ( '' !== $paragraph ) : ?>
										<p class="sticky-steps__p"><?php echo esc_html( $paragraph ); ?></p>
									<?php endif; ?>
								</div>
								<div class="sticky-steps__media">
									<div class="sticky-steps__sticky">
										<div class="sticky-steps__visual">
											<?php if ( '' !== $img_url ) : ?>
												<img src="<?php echo esc_url( $img_url ); ?>" loading="lazy" alt="" class="sticky-steps__cover-image">
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
