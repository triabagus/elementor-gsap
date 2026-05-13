<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Image_Scroll_Widget extends Widget_Base {

	public function get_name() {
		return 'image_scroll';
	}

	public function get_title() {
		return __( 'Image Scroll', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'image', 'scroll', 'hover', 'screenshot', 'mockup', 'long', 'tall' ];
	}

	public function get_script_depends() {
		return [ 'elementor-image-scroll' ];
	}

	public function get_style_depends() {
		return [ 'elementor-image-scroll' ];
	}

	protected function register_controls() {
		/* === CONTENT === */
		$this->start_controls_section( 'content_section', [
			'label' => __( 'Image', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'image', [
			'label'   => __( 'Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'    => 'image_size',
			'default' => 'full',
		] );

		$this->add_control( 'alt_text', [
			'label'       => __( 'Alt Text', 'elementor-gsap' ),
			'description' => __( 'Aksesibilitas — biarkan kosong untuk pakai alt yang tersimpan di Media Library.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
		] );

		$this->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '' ],
		] );

		$this->end_controls_section();

		/* === ANIMATION === */
		$this->start_controls_section( 'animation_section', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'direction', [
			'label'   => __( 'Direction', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'vertical'   => __( 'Vertical', 'elementor-gsap' ),
				'horizontal' => __( 'Horizontal', 'elementor-gsap' ),
			],
			'default' => 'vertical',
		] );

		$this->add_control( 'trigger', [
			'label'       => __( 'Trigger', 'elementor-gsap' ),
			'description' => __( 'Hover = scroll otomatis saat kursor masuk. Mouse Track = posisi image mengikuti posisi mouse di dalam frame.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'hover'       => __( 'Hover (auto)', 'elementor-gsap' ),
				'mouse-track' => __( 'Mouse Track', 'elementor-gsap' ),
			],
			'default'     => 'hover',
		] );

		$this->add_control( 'reverse', [
			'label'        => __( 'Reverse Direction', 'elementor-gsap' ),
			'description'  => __( 'Mulai dari ujung, scroll ke awal saat hover.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'elementor-gsap' ),
			'label_off'    => __( 'No', 'elementor-gsap' ),
			'return_value' => '1',
			'default'      => '',
		] );

		$this->add_control( 'duration', [
			'label'      => __( 'Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Berlaku untuk mode Hover.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 's' ],
			'range'      => [
				's' => [ 'min' => 0.2, 'max' => 15, 'step' => 0.1 ],
			],
			'default'    => [ 'unit' => 's', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .image-scroll' => '--is-duration: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'trigger' => 'hover',
			],
		] );

		$this->add_control( 'easing', [
			'label'   => __( 'Easing', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'cubic-bezier(0.4, 0, 0.2, 1)'   => 'Smooth (default)',
				'linear'                          => 'Linear',
				'ease'                            => 'Ease',
				'ease-in-out'                     => 'Ease In Out',
				'cubic-bezier(0.65, 0, 0.35, 1)'  => 'easeInOutCubic',
				'cubic-bezier(0.83, 0, 0.17, 1)'  => 'easeInOutQuint',
				'cubic-bezier(0.22, 1, 0.36, 1)'  => 'easeOutQuint',
			],
			'default' => 'cubic-bezier(0.4, 0, 0.2, 1)',
			'selectors' => [
				'{{WRAPPER}} .image-scroll' => '--is-easing: {{VALUE}};',
			],
			'condition' => [
				'trigger' => 'hover',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: FRAME === */
		$this->start_controls_section( 'style_frame_section', [
			'label' => __( 'Frame', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'frame_height', [
			'label'       => __( 'Frame Height', 'elementor-gsap' ),
			'description' => __( 'Tinggi frame yang menampung image. Image akan di-scroll secara otomatis sebanyak (image height − frame height).', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', 'em', 'vh', '%' ],
			'range'       => [
				'px' => [ 'min' => 100, 'max' => 1200 ],
				'em' => [ 'min' => 5, 'max' => 80 ],
				'vh' => [ 'min' => 10, 'max' => 100 ],
				'%'  => [ 'min' => 10, 'max' => 100 ],
			],
			'default'     => [ 'unit' => 'px', 'size' => 400 ],
			'selectors'   => [
				'{{WRAPPER}} .image-scroll' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'max_width', [
			'label'      => __( 'Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [ 'min' => 100, 'max' => 1600 ],
				'%'  => [ 'min' => 10, 'max' => 100 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .image-scroll' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'bg_color', [
			'label'     => __( 'Frame Background', 'elementor-gsap' ),
			'description' => __( 'Tampil kalau image lebih pendek dari frame.', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f5f5f5',
			'selectors' => [
				'{{WRAPPER}} .image-scroll' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'border_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 200 ],
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'%'  => [ 'min' => 0, 'max' => 50 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .image-scroll' => '--is-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'border',
			'selector' => '{{WRAPPER}} .image-scroll',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .image-scroll',
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => __( 'Alignment', 'elementor-gsap' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => __( 'Left', 'elementor-gsap' ), 'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => __( 'Center', 'elementor-gsap' ), 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => __( 'Right', 'elementor-gsap' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'center',
			'selectors' => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: OVERLAY === */
		$this->start_controls_section( 'style_overlay_section', [
			'label' => __( 'Overlay Icon', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'show_overlay', [
			'label'        => __( 'Show Overlay Icon', 'elementor-gsap' ),
			'description'  => __( 'Tampilkan ikon di tengah frame yang akan fade-out saat hover.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'elementor-gsap' ),
			'label_off'    => __( 'No', 'elementor-gsap' ),
			'return_value' => '1',
			'default'      => '',
		] );

		$this->add_control( 'overlay_icon', [
			'label'     => __( 'Icon', 'elementor-gsap' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [
				'value'   => 'eicon-mouse',
				'library' => 'eicons',
			],
			'condition' => [
				'show_overlay' => '1',
			],
		] );

		$this->add_control( 'overlay_icon_color', [
			'label'     => __( 'Icon Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .image-scroll__overlay-icon' => 'color: {{VALUE}};',
				'{{WRAPPER}} .image-scroll__overlay-icon svg' => 'fill: {{VALUE}};',
			],
			'condition' => [
				'show_overlay' => '1',
			],
		] );

		$this->add_control( 'overlay_bg_color', [
			'label'     => __( 'Icon Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.55)',
			'selectors' => [
				'{{WRAPPER}} .image-scroll__overlay-icon' => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'show_overlay' => '1',
			],
		] );

		$this->add_responsive_control( 'overlay_icon_size', [
			'label'      => __( 'Icon Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [
				'px' => [ 'min' => 20, 'max' => 200 ],
				'em' => [ 'min' => 1, 'max' => 8, 'step' => 0.1 ],
			],
			'default'    => [ 'unit' => 'px', 'size' => 56 ],
			'selectors'  => [
				'{{WRAPPER}} .image-scroll__overlay-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_overlay' => '1',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s         = $this->get_settings_for_display();
		$direction = ! empty( $s['direction'] ) ? $s['direction'] : 'vertical';
		$trigger   = ! empty( $s['trigger'] ) ? $s['trigger'] : 'hover';
		$reverse   = ! empty( $s['reverse'] ) ? '1' : '0';

		$image_url = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
		$image_id  = ! empty( $s['image']['id'] ) ? intval( $s['image']['id'] ) : 0;
		$alt       = '';
		if ( ! empty( $s['alt_text'] ) ) {
			$alt = $s['alt_text'];
		} elseif ( $image_id ) {
			$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		}

		// Use the chosen image size if an attachment ID is present.
		if ( $image_id ) {
			$sized = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $s );
			if ( $sized ) {
				$image_url = $sized;
			}
		}

		if ( empty( $image_url ) ) {
			$image_url = Utils::get_placeholder_image_src();
		}

		$has_link  = ! empty( $s['link']['url'] );
		$link_url  = $has_link ? $s['link']['url'] : '';
		$target    = ! empty( $s['link']['is_external'] ) ? '_blank' : '_self';
		$rel       = ! empty( $s['link']['nofollow'] ) ? 'nofollow' : '';

		$show_overlay = ! empty( $s['show_overlay'] );
		?>
		<div data-image-scroll
			class="image-scroll"
			data-direction="<?php echo esc_attr( $direction ); ?>"
			data-trigger="<?php echo esc_attr( $trigger ); ?>"
			data-reverse="<?php echo esc_attr( $reverse ); ?>"
			<?php if ( ! $has_link ) : ?>tabindex="0"<?php endif; ?>>
			<?php if ( $has_link ) : ?>
				<a class="image-scroll__link" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?>>
			<?php endif; ?>
				<img class="image-scroll__image"
					src="<?php echo esc_url( $image_url ); ?>"
					alt="<?php echo esc_attr( $alt ); ?>"
					loading="lazy"
					decoding="async">
				<?php if ( $show_overlay && ! empty( $s['overlay_icon']['value'] ) ) : ?>
					<div class="image-scroll__overlay" aria-hidden="true">
						<span class="image-scroll__overlay-icon">
							<?php \Elementor\Icons_Manager::render_icon( $s['overlay_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					</div>
				<?php endif; ?>
			<?php if ( $has_link ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
