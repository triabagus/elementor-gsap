<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Line_Reveal_Testimonials_Widget extends Widget_Base {

	public function get_name() {
		return 'line_reveal_testimonials';
	}

	public function get_title() {
		return __( 'Line Reveal Testimonials', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ 'testimonial', 'slider', 'line', 'reveal', 'splittext', 'osmo', 'gsap' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-splittext', 'gsap-scrolltrigger', 'elementor-line-reveal-testimonials' ];
	}

	public function get_style_depends() {
		return [ 'elementor-line-reveal-testimonials' ];
	}

	public function arrow_prev_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" fill="none"><path d="M5.26512 12L6.43721 10.7746L1.48837 5.28169V6.71831L6.45581 1.22535L5.28372 0L-2.21369e-07 6L5.26512 12ZM12 6.97183V5.02817H1.30232V6.97183H12Z" fill="currentColor"/></svg>';
	}

	public function arrow_next_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" fill="none"><path d="M6.73488 12L5.56279 10.7746L10.5116 5.28169V6.71831L5.54419 1.22535L6.71628 0L12 6L6.73488 12ZM0 6.97183V5.02817H10.6977V6.97183H0Z" fill="currentColor"/></svg>';
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                    CONTENT — TESTIMONIALS                  */
		/* ========================================================= */
		$this->start_controls_section( 'content_items', [
			'label' => __( 'Testimonials', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'quote', [
			'label'   => __( 'Quote', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => 'Their team jumped in with clear pricing and flexible coverage.',
		] );
		$rep->add_control( 'name', [
			'label'   => __( 'Name', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'John Doe',
		] );
		$rep->add_control( 'company', [
			'label'   => __( 'Company / Role', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Acme Inc.',
		] );
		$rep->add_control( 'image', [
			'label'   => __( 'Avatar', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );

		$this->add_control( 'items', [
			'label'       => __( 'Testimonials', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ name }}} — {{{ company }}}',
			'default'     => [
				[
					'quote'   => '“After a rough quarter, we needed hands fast. Their team jumped in with clear pricing and flexible coverage for weekend rushes and supplier delays. They’ve become our first call when operations get tight.”',
					'name'    => 'Mara Kline',
					'company' => 'Northbay Produce Co.',
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/697946f9c74d6e83502491c6/6979fcf493ed513c80fb67b0_img-1.avif' ],
				],
				[
					'quote'   => '“We were referred by a partner and liked the straight answers. They helped us stabilize scheduling, fill last-minute gaps, and keep deliveries on time during peak season. Now we reach out before problems snowball.”',
					'name'    => 'Devon Reyes',
					'company' => 'Kestrel Courier Group',
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/697946f9c74d6e83502491c6/6979fcf4340be6c44df0fa86_img-2.avif' ],
				],
				[
					'quote'   => '“During our expansion, training and onboarding fell behind. They stepped in with consistent staffing, fair rates, and quick turnaround for urgent shifts.”',
					'name'    => 'Priya Menon',
					'company' => 'Harborview Senior Living',
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/697946f9c74d6e83502491c6/6979fcf41e2c7bc67c213c31_img-3.avif' ],
				],
				[
					'quote'   => '“We had a sudden equipment outage and couldn’t afford downtime. They coordinated extra coverage, kept communication simple, and helped us meet our production commitments without surprises.”',
					'name'    => 'Cole Hart',
					'company' => 'Redstone Bottling Works',
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/697946f9c74d6e83502491c6/6979fcf45f6f900cd99e3701_img-4.avif' ],
				],
				[
					'quote'   => '“Our busiest months are unpredictable, and hiring temp help is usually a headache. They made it easy—clear terms, flexible availability, and people who actually showed up prepared. They’re our go-to when demand spikes.”',
					'name'    => 'Lina Okafor',
					'company' => 'Juniper Street Catering',
					'image'   => [ 'url' => 'https://cdn.prod.website-files.com/697946f9c74d6e83502491c6/6979fcf4cb1e0ab479d5809a_img-5.avif' ],
				],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    CONTENT — HEADER LABEL                  */
		/* ========================================================= */
		$this->start_controls_section( 'content_header', [
			'label' => __( 'Header Label', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'header_label', [
			'label'       => __( 'Label Text', 'elementor-gsap' ),
			'description' => __( 'Text di samping counter "1 / 5". Kosongkan untuk hide.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'What our clients say:',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     CONTENT — BEHAVIOR                     */
		/* ========================================================= */
		$this->start_controls_section( 'content_behavior', [
			'label' => __( 'Slider Behavior', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'nav_enable', [
			'label'        => __( 'Show Prev/Next Buttons', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'autoplay', [
			'label'        => __( 'Autoplay', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'autoplay_duration', [
			'label'       => __( 'Autoplay Duration (ms)', 'elementor-gsap' ),
			'description' => __( 'Waktu antar auto-advance dalam milidetik. Default 5000 (5 detik).', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 1000,
			'max'         => 20000,
			'step'        => 250,
			'default'     => 5000,
			'condition'   => [ 'autoplay' => 'yes' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — LAYOUT                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_layout', [
			'label' => __( 'Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'controls_width', [
			'label'      => __( 'Controls Column Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px', 'em' ],
			'range'      => [
				'%'  => [ 'min' => 15, 'max' => 60 ],
				'px' => [ 'min' => 100, 'max' => 500 ],
				'em' => [ 'min' => 8, 'max' => 30 ],
			],
			'default'    => [ 'unit' => '%', 'size' => 33.3333 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-controls-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'gap', [
			'label'      => __( 'Top-Level Gap', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'main_gap', [
			'label'       => __( 'Main Column Gap (Header ↔ Testimonial)', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [ 'em' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ], 'px' => [ 'min' => 0, 'max' => 160 ] ],
			'default'     => [ 'unit' => 'em', 'size' => 5 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-main-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_gap', [
			'label'       => __( 'Testimonial Item Gap (Quote ↔ Author)', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [ 'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.1 ], 'px' => [ 'min' => 0, 'max' => 128 ] ],
			'default'     => [ 'unit' => 'em', 'size' => 4 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-item-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — TYPOGRAPHY                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Typography', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'text_color', [
			'label'   => __( 'Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [ '{{WRAPPER}} .egsap-lrt' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'faded_opacity', [
			'label'       => __( 'Faded Text Opacity', 'elementor-gsap' ),
			'description' => __( 'Opacity untuk text kecil dengan class <code>.is--faded</code> (counter, company/role).', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.5,
			'selectors'   => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-p-opacity: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'quote_size', [
			'label'      => __( 'Quote Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 6, 'step' => 0.05 ],
				'px' => [ 'min' => 16, 'max' => 96 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-h-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'quote_typography',
			'label'    => __( 'Quote Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-lrt__h',
		] );

		$this->add_responsive_control( 'p_size', [
			'label'      => __( 'Author / Label Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 0.75, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 12, 'max' => 32 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-p-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'p_typography',
			'label'    => __( 'Author / Label Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-lrt__p',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — AVATAR                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_avatar', [
			'label' => __( 'Avatar', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'avatar_size', [
			'label'      => __( 'Avatar Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 2, 'max' => 12, 'step' => 0.1 ], 'px' => [ 'min' => 32, 'max' => 200 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-avatar-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                        STYLE — BUTTONS                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_buttons', [
			'label' => __( 'Prev/Next Buttons', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => [ 'nav_enable' => 'yes' ],
		] );

		$this->add_control( 'btn_bg', [
			'label'   => __( 'Button Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-btn-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'btn_border', [
			'label'   => __( 'Button Border Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.2)',
			'selectors' => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-btn-border: {{VALUE}};' ],
		] );

		$this->add_control( 'btn_color', [
			'label'   => __( 'Button Icon Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#201D1D',
			'selectors' => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-btn-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'btn_size', [
			'label'      => __( 'Button Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 2, 'max' => 5, 'step' => 0.05 ], 'px' => [ 'min' => 32, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 2.5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-btn-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'btn_radius', [
			'label'      => __( 'Button Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ], '%' => [ 'min' => 0, 'max' => 50 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-btn-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'btn_arrow_size', [
			'label'      => __( 'Arrow Icon Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0.3, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 6, 'max' => 32 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-lrt' => '--lrt-btn-arrow-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$items = ! empty( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : [];
		$header_label = isset( $s['header_label'] ) ? $s['header_label'] : '';

		$is_edit = false;
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$plugin = \Elementor\Plugin::$instance;
			if ( isset( $plugin->editor ) && method_exists( $plugin->editor, 'is_edit_mode' ) && $plugin->editor->is_edit_mode() ) {
				$is_edit = true;
			}
			if ( isset( $plugin->preview ) && method_exists( $plugin->preview, 'is_preview_mode' ) && $plugin->preview->is_preview_mode() ) {
				$is_edit = true;
			}
		}

		$editor_flag = $is_edit ? ' data-egsap-lrt-editor="1"' : '';

		$autoplay = 'yes' === ( $s['autoplay'] ?? '' ) ? 'true' : 'false';
		$autoplay_dur = isset( $s['autoplay_duration'] ) ? intval( $s['autoplay_duration'] ) : 5000;
		$show_nav = 'yes' === ( $s['nav_enable'] ?? '' );

		$arrow_prev = $this->arrow_prev_svg();
		$arrow_next = $this->arrow_next_svg();
		?>
		<div
			class="egsap-lrt"
			data-egsap-lrt-wrap
			data-egsap-lrt-autoplay="<?php echo esc_attr( $autoplay ); ?>"
			data-egsap-lrt-autoplay-duration="<?php echo esc_attr( $autoplay_dur ); ?>"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<?php if ( $show_nav ) : ?>
				<div class="egsap-lrt__controls">
					<button type="button" data-egsap-lrt-prev aria-label="previous testimonial" class="egsap-lrt__button">
						<span class="egsap-lrt__arrow"><?php echo $arrow_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
					<button type="button" data-egsap-lrt-next aria-label="next testimonial" class="egsap-lrt__button">
						<span class="egsap-lrt__arrow"><?php echo $arrow_next; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
				</div>
			<?php endif; ?>

			<div class="egsap-lrt__main">
				<div class="egsap-lrt__main-details">
					<p class="egsap-lrt__p is--faded">
						<span data-egsap-lrt-current class="egsap-lrt__count">1</span>
						/
						<span data-egsap-lrt-total><?php echo count( $items ); ?></span>
					</p>
					<?php if ( '' !== $header_label ) : ?>
						<p class="egsap-lrt__p"><?php echo esc_html( $header_label ); ?></p>
					<?php endif; ?>
				</div>

				<div class="egsap-lrt__collection">
					<div role="list" data-egsap-lrt-list class="egsap-lrt__list">
						<?php if ( ! empty( $items ) ) : foreach ( $items as $i => $it ) :
							$quote   = $it['quote'] ?? '';
							$name    = $it['name']  ?? '';
							$company = $it['company'] ?? '';
							$img     = $it['image']['url'] ?? '';
							$active  = 0 === $i;
							$cls     = 'egsap-lrt__item' . ( $active ? ' is--active' : '' );
							?>
							<div
								aria-hidden="<?php echo $active ? 'false' : 'true'; ?>"
								data-egsap-lrt-item
								role="listitem"
								class="<?php echo esc_attr( $cls ); ?>"
							>
								<?php if ( '' !== $quote ) : ?>
									<h3 data-egsap-lrt-text class="egsap-lrt__h"><?php echo esc_html( $quote ); ?></h3>
								<?php endif; ?>
								<div class="egsap-lrt__item-details">
									<?php if ( $img ) : ?>
										<div data-egsap-lrt-img class="egsap-lrt__item-visual">
											<img src="<?php echo esc_url( $img ); ?>" alt="" class="egsap-lrt__item-img" />
										</div>
									<?php endif; ?>
									<div>
										<?php if ( '' !== $name ) : ?>
											<p data-egsap-lrt-split class="egsap-lrt__p"><?php echo esc_html( $name ); ?></p>
										<?php endif; ?>
										<?php if ( '' !== $company ) : ?>
											<p data-egsap-lrt-split class="egsap-lrt__p is--faded"><?php echo esc_html( $company ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
