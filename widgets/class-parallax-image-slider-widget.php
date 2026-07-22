<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Parallax_Image_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'parallax_image_slider';
	}

	public function get_title() {
		return __( 'Parallax Image Slider (Smooothy)', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ 'slider', 'parallax', 'smooothy', 'drag', 'infinite', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'smooothy-js', 'elementor-parallax-image-slider' ];
	}

	public function get_style_depends() {
		return [ 'elementor-parallax-image-slider' ];
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                       CONTENT — SLIDES                     */
		/* ========================================================= */
		$this->start_controls_section( 'content_slides', [
			'label' => __( 'Slides', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'slides_help', [
			'type'    => Controls_Manager::RAW_HTML,
			'raw'     => __( '<strong>Tips:</strong> image di setiap slide sebaiknya lebih lebar dari container (default 160% dengan offset -30%) supaya ada extra area untuk parallax shift.', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Slide Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		$rep->add_control( 'alt', [
			'label'       => __( 'Alt Text', 'elementor-gsap' ),
			'description' => __( 'Untuk aksesibilitas. Kosongkan kalau purely decorative.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
		] );

		$this->add_control( 'slides', [
			'label'       => __( 'Slides', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ alt || "Slide" }}}',
			'default'     => [
				[ 'alt' => 'Sleek Black Sports Car', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a22d30444bf3a9f911e68b9/6a22d94b60b1ba0e3492cc90_Sleek%20Black%20Sports%20Car.avif' ] ],
				[ 'alt' => 'Astronaut Amidst Crowd', 'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a22d30444bf3a9f911e68b9/6a22d94bccbaad3404e09921_Astronaut%20Amidst%20Crowd.avif' ] ],
				[ 'alt' => 'Textured Stone Block',   'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a22d30444bf3a9f911e68b9/6a22d94b7b950434ca8c1076_Textured%20Stone%20Block.avif' ] ],
				[ 'alt' => 'Portrait of a Woman',    'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a22d30444bf3a9f911e68b9/6a22d94ce1355dd6de4b06ae_Portrait%20of%20a%20Woman.avif' ] ],
				[ 'alt' => 'Digital Urban Fusion',   'image' => [ 'url' => 'https://cdn.prod.website-files.com/6a22d30444bf3a9f911e68b9/6a22d94b6422798654ba5b42_Digital%20Urban%20Fusion.avif' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     CONTENT — BEHAVIOR                     */
		/* ========================================================= */
		$this->start_controls_section( 'content_behavior', [
			'label' => __( 'Slider Behavior', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'infinite', [
			'label'        => __( 'Infinite Loop', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'snap', [
			'label'        => __( 'Snap to Slide', 'elementor-gsap' ),
			'description'  => __( 'Kalau on, drag akan snap ke slide terdekat.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'amount', [
			'label'       => __( 'Parallax Amount', 'elementor-gsap' ),
			'description' => __( 'Seberapa kuat inner image shift horizontal saat slide bergerak.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 50,
			'step'        => 0.5,
			'default'     => 10,
		] );

		$this->add_control( 'lerp', [
			'label'       => __( 'Lerp Smoothing', 'elementor-gsap' ),
			'description' => __( 'Kecil = smoother/gentle, besar = responsive/kaku. Default <code>0.3</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.05,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.3,
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — ITEM                         */
		/* ========================================================= */
		$this->start_controls_section( 'style_item', [
			'label' => __( 'Slide Item', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'item_width', [
			'label'      => __( 'Item Width (Desktop)', 'elementor-gsap' ),
			'description' => __( 'Osmo reference default: <code>32em</code>.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'vw' ],
			'range'      => [
				'em' => [ 'min' => 10, 'max' => 60 ],
				'px' => [ 'min' => 160, 'max' => 900 ],
				'vw' => [ 'min' => 20, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 32 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-pis__item-inner' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_width_mobile', [
			'label'      => __( 'Item Width (Mobile ≤767px)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'vw', 'em', 'px' ],
			'range'      => [
				'vw' => [ 'min' => 20, 'max' => 90 ],
				'em' => [ 'min' => 10, 'max' => 30 ],
				'px' => [ 'min' => 160, 'max' => 480 ],
			],
			'default'    => [ 'unit' => 'vw', 'size' => 45 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-pis__item-inner' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'item_aspect', [
			'label'       => __( 'Aspect Ratio', 'elementor-gsap' ),
			'description' => __( 'Format <code>W / H</code>. Contoh <code>3 / 2</code>, <code>16 / 9</code>, <code>1 / 1</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '3 / 2',
			'selectors'   => [ '{{WRAPPER}} .egsap-pis__item-inner' => 'aspect-ratio: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'item_pad_x', [
			'label'       => __( 'Slide Spacing / Half-Gap (Desktop)', 'elementor-gsap' ),
			'description' => __( 'Padding kiri-kanan tiap slide. <strong>Visible gap antar 2 slide = 2× nilai ini.</strong> Osmo reference default: <code>1em</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.025 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'     => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-pis__item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_pad_x_mobile', [
			'label'       => __( 'Slide Spacing / Half-Gap (Mobile ≤767px)', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [ 'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.025 ], 'px' => [ 'min' => 0, 'max' => 32 ] ],
			'default'     => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-pis__item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-pis__item-inner' => 'border-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — IMAGE                        */
		/* ========================================================= */
		$this->start_controls_section( 'style_image', [
			'label' => __( 'Image (Parallax Layer)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'img_scale', [
			'label'       => __( 'Image Scale (%)', 'elementor-gsap' ),
			'description' => __( 'Lebar image sebagai % dari container. Naikkan supaya ada extra area untuk parallax shift.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%' ],
			'range'       => [ '%' => [ 'min' => 100, 'max' => 200, 'step' => 5 ] ],
			'default'     => [ 'unit' => '%', 'size' => 160 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-pis__item-img' => 'width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'img_offset', [
			'label'       => __( 'Image Horizontal Offset (%)', 'elementor-gsap' ),
			'description' => __( 'Center-kan image extra. Kalau scale 160%%, offset ideal -30%%.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%' ],
			'range'       => [ '%' => [ 'min' => -50, 'max' => 0, 'step' => 1 ] ],
			'default'     => [ 'unit' => '%', 'size' => -30 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-pis__item-img' => 'left: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$slides = ! empty( $s['slides'] ) && is_array( $s['slides'] ) ? $s['slides'] : [];

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

		$editor_flag = $is_edit ? ' data-egsap-pis-editor="1"' : '';

		$infinite = 'yes' === ( $s['infinite'] ?? '' ) ? 'true' : 'false';
		$snap     = 'yes' === ( $s['snap'] ?? '' ) ? 'true' : 'false';
		$amount   = isset( $s['amount'] ) ? floatval( $s['amount'] ) : 10;
		$lerp     = isset( $s['lerp'] ) ? floatval( $s['lerp'] ) : 0.3;
		?>
		<div
			class="egsap-pis"
			data-egsap-pis-init
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div class="egsap-pis__collection">
				<div
					data-egsap-pis-slider
					data-egsap-pis-infinite="<?php echo esc_attr( $infinite ); ?>"
					data-egsap-pis-snap="<?php echo esc_attr( $snap ); ?>"
					data-egsap-pis-amount="<?php echo esc_attr( $amount ); ?>"
					data-egsap-pis-lerp="<?php echo esc_attr( $lerp ); ?>"
					class="egsap-pis__list"
				>
					<?php if ( ! empty( $slides ) ) : foreach ( $slides as $slide ) :
						$img = $slide['image']['url'] ?? '';
						$alt = isset( $slide['alt'] ) ? $slide['alt'] : '';
						?>
						<div class="egsap-pis__item">
							<div class="egsap-pis__item-inner">
								<div data-egsap-pis-inner class="egsap-pis__item-visual">
									<?php if ( $img ) : ?>
										<img
											src="<?php echo esc_url( $img ); ?>"
											loading="lazy"
											draggable="false"
											alt="<?php echo esc_attr( $alt ); ?>"
											class="egsap-pis__item-img"
										/>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
