<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Draggable_Marquee_Widget extends Widget_Base {

	public function get_name() {
		return 'draggable_marquee';
	}

	public function get_title() {
		return __( 'Draggable Marquee', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ 'marquee', 'draggable', 'infinite', 'scroll', 'observer', 'osmo', 'gsap' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-observer', 'gsap-scrolltrigger', 'elementor-draggable-marquee' ];
	}

	public function get_style_depends() {
		return [ 'elementor-draggable-marquee' ];
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                       CONTENT — ITEMS                      */
		/* ========================================================= */
		$this->start_controls_section( 'content_items', [
			'label' => __( 'Marquee Items', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'items_help', [
			'type'    => Controls_Manager::RAW_HTML,
			'raw'     => __( '<strong>Tips:</strong> JS otomatis clone list sampai lebar total menutup viewport untuk seamless infinite loop.', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		$rep->add_control( 'is_round', [
			'label'        => __( 'Round Shape', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );
		$rep->add_control( 'alt', [
			'label'   => __( 'Alt Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$this->add_control( 'items', [
			'label'       => __( 'Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ alt || "Item" }}}{{ is_round === "yes" ? " (round)" : "" }}',
			'default'     => [
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/694b0fb876617b13bea76eb8/694bc0b8b19fd3d316656d36_marquee-fruit-1.avif' ], 'is_round' => 'yes' ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/694b0fb876617b13bea76eb8/694bc0b8d50d60981d906f71_marquee-fruit-2.avif' ] ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/694b0fb876617b13bea76eb8/694bc0b7383ea5688964f10b_marquee-fruit-3.avif' ] ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/694b0fb876617b13bea76eb8/694bc0b7c398823122b56766_marquee-fruit-4.avif' ], 'is_round' => 'yes' ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/694b0fb876617b13bea76eb8/694bc0b7e249f3def94a048c_marquee-fruit-5.avif' ] ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/694b0fb876617b13bea76eb8/694bc0b7b75b2b06a7e51ec3_marquee-fruit-6.avif' ], 'is_round' => 'yes' ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/694b0fb876617b13bea76eb8/694bc0b866f40e1da7eb53ba_marquee-fruit-7.avif' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     CONTENT — BEHAVIOR                     */
		/* ========================================================= */
		$this->start_controls_section( 'content_behavior', [
			'label' => __( 'Marquee Behavior', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'direction', [
			'label'   => __( 'Direction', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'left'  => __( 'Left', 'elementor-gsap' ),
				'right' => __( 'Right', 'elementor-gsap' ),
			],
			'default' => 'left',
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Duration (seconds)', 'elementor-gsap' ),
			'description' => __( 'Durasi 1 cycle marquee. Kecil = cepat, besar = pelan.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 5,
			'max'         => 120,
			'step'        => 1,
			'default'     => 20,
		] );

		$this->add_control( 'multiplier', [
			'label'       => __( 'Drag Max Time Scale', 'elementor-gsap' ),
			'description' => __( 'Cap kecepatan boost saat di-drag. Semakin besar = lebih responsif ke drag cepat.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 5,
			'max'         => 100,
			'step'        => 1,
			'default'     => 35,
		] );

		$this->add_control( 'sensitivity', [
			'label'       => __( 'Drag Sensitivity', 'elementor-gsap' ),
			'description' => __( 'Multiplier terhadap drag velocity. Kecil = perlu drag jauh untuk boost, besar = sensitif.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.001,
			'max'         => 0.1,
			'step'        => 0.001,
			'default'     => 0.01,
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                        STYLE — ITEM                        */
		/* ========================================================= */
		$this->start_controls_section( 'style_item', [
			'label' => __( 'Item', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'item_width', [
			'label'      => __( 'Item Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'vw' ],
			'range'      => [
				'em' => [ 'min' => 5, 'max' => 30 ],
				'px' => [ 'min' => 80, 'max' => 500 ],
				'vw' => [ 'min' => 10, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 15 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dmq' => '--dmq-item-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'item_aspect', [
			'label'       => __( 'Aspect Ratio', 'elementor-gsap' ),
			'description' => __( 'Format <code>W / H</code>. Contoh <code>1</code>, <code>4 / 5</code>, <code>16 / 9</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '1',
			'selectors'   => [ '{{WRAPPER}} .egsap-dmq' => '--dmq-item-aspect: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'item_gap', [
			'label'      => __( 'Gap Between Items', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dmq' => '--dmq-item-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_radius', [
			'label'      => __( 'Border Radius (Non-Round)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-dmq' => '--dmq-item-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$items = ! empty( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : [];

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

		$editor_flag = $is_edit ? ' data-egsap-dmq-editor="1"' : '';

		$direction   = $s['direction']   ?? 'left';
		$duration    = floatval( $s['duration']    ?? 20 );
		$multiplier  = floatval( $s['multiplier']  ?? 35 );
		$sensitivity = floatval( $s['sensitivity'] ?? 0.01 );
		?>
		<div
			class="egsap-dmq"
			data-egsap-dmq-init
			data-egsap-dmq-direction="<?php echo esc_attr( $direction ); ?>"
			data-egsap-dmq-duration="<?php echo esc_attr( $duration ); ?>"
			data-egsap-dmq-multiplier="<?php echo esc_attr( $multiplier ); ?>"
			data-egsap-dmq-sensitivity="<?php echo esc_attr( $sensitivity ); ?>"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div data-egsap-dmq-collection class="egsap-dmq__collection">
				<div data-egsap-dmq-list class="egsap-dmq__list">
					<?php if ( ! empty( $items ) ) : foreach ( $items as $item ) :
						$img   = $item['image']['url'] ?? '';
						$round = 'yes' === ( $item['is_round'] ?? '' );
						$alt   = $item['alt'] ?? '';
						$cls   = 'egsap-dmq__item' . ( $round ? ' is--round' : '' );
						?>
						<div class="<?php echo esc_attr( $cls ); ?>">
							<?php if ( $img ) : ?>
								<img
									src="<?php echo esc_url( $img ); ?>"
									draggable="false"
									loading="eager"
									alt="<?php echo esc_attr( $alt ); ?>"
									class="egsap-dmq__item-img"
								/>
							<?php endif; ?>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
