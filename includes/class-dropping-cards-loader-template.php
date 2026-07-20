<?php
namespace Elementor_GSAP;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Dropping_Cards_Loader_Template {

	const PREFIX = 'egsap_dcl_';

	public static function key( $name ) {
		return self::PREFIX . $name;
	}

	public static function default_osmo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 86 13" fill="none"><path d="M81.7014 3.9848V1.3192H82.7758C83.0387 1.3192 83.2473 1.39627 83.4014 1.5504C83.5555 1.69547 83.6326 1.89493 83.6326 2.1488C83.6326 2.29387 83.5963 2.42987 83.5238 2.5568C83.4513 2.67467 83.3606 2.76533 83.2518 2.8288L83.9454 3.9848H83.1974L82.6262 2.9648H82.327V3.9848H81.7014ZM82.327 2.4344H82.667C82.7667 2.4344 82.8438 2.41173 82.8982 2.3664C82.9617 2.312 82.9934 2.23947 82.9934 2.1488C82.9934 2.0672 82.9617 1.9992 82.8982 1.9448C82.8438 1.8904 82.7667 1.8632 82.667 1.8632H82.327V2.4344ZM82.6534 5.3176C82.1366 5.3176 81.6787 5.20427 81.2798 4.9776C80.8809 4.74187 80.5635 4.42453 80.3278 4.0256C80.1011 3.6176 79.9878 3.15973 79.9878 2.652C79.9878 2.14427 80.1011 1.69093 80.3278 1.292C80.5635 0.893067 80.8809 0.580267 81.2798 0.3536C81.6787 0.117867 82.1366 0 82.6534 0C83.1611 0 83.6099 0.117867 83.9998 0.3536C84.3987 0.580267 84.7115 0.893067 84.9382 1.292C85.1739 1.69093 85.2918 2.14427 85.2918 2.652C85.2918 3.15973 85.1739 3.61307 84.9382 4.012C84.7115 4.41093 84.3987 4.72827 83.9998 4.964C83.6099 5.19973 83.1611 5.3176 82.6534 5.3176ZM82.6398 4.7056C83.0297 4.7056 83.3742 4.61947 83.6734 4.4472C83.9817 4.26587 84.2219 4.02107 84.3942 3.7128C84.5755 3.39547 84.6662 3.04187 84.6662 2.652C84.6662 2.26213 84.5755 1.91307 84.3942 1.6048C84.2219 1.29653 83.9862 1.05627 83.687 0.884001C83.3878 0.702667 83.0433 0.612 82.6534 0.612C82.2726 0.612 81.9281 0.702667 81.6198 0.884001C81.3206 1.05627 81.0803 1.29653 80.899 1.6048C80.7267 1.91307 80.6406 2.2576 80.6406 2.6384C80.6406 3.03733 80.7267 3.39547 80.899 3.7128C81.0713 4.02107 81.307 4.26587 81.6062 4.4472C81.9145 4.61947 82.259 4.7056 82.6398 4.7056Z" fill="currentColor"></path><path d="M72.5918 12.8476V4.27956H74.0368V5.79255C74.2068 5.42989 74.4278 5.12389 74.6998 4.87456C74.9718 4.62522 75.2835 4.44389 75.6348 4.33055C75.9861 4.20589 76.3431 4.14355 76.7058 4.14355C76.8871 4.14355 77.0571 4.16055 77.2158 4.19456C77.3858 4.22855 77.5445 4.27955 77.6918 4.34756V5.72455C77.4765 5.63389 77.2781 5.57722 77.0968 5.55455C76.9155 5.53189 76.7341 5.52055 76.5528 5.52055C76.2015 5.52055 75.8671 5.60555 75.5498 5.77556C75.2438 5.94555 74.9775 6.19489 74.7508 6.52355C74.5241 6.85222 74.3485 7.26022 74.2238 7.74755C74.0991 8.23489 74.0368 8.79022 74.0368 9.41356V12.8476H72.5918Z" fill="currentColor"></path><path d="M69.3494 12.8477V4.27965H70.8114V12.8477H69.3494ZM69.3494 12.8477V11.6407H70.8114V12.8477H69.3494ZM69.3494 5.48665V4.27965H70.8114V5.48665H69.3494ZM70.0804 2.78365C69.7857 2.78365 69.5364 2.68732 69.3324 2.49465C69.1284 2.30199 69.0264 2.05832 69.0264 1.76365C69.0264 1.55965 69.0717 1.38399 69.1624 1.23665C69.2644 1.08932 69.389 0.970318 69.5364 0.879652C69.695 0.788985 69.8764 0.743652 70.0804 0.743652C70.3864 0.743652 70.6357 0.839985 70.8284 1.03265C71.0324 1.22532 71.1344 1.46899 71.1344 1.76365C71.1344 1.95632 71.089 2.13199 70.9984 2.29065C70.9077 2.43799 70.783 2.55699 70.6244 2.64765C70.4657 2.73832 70.2844 2.78365 70.0804 2.78365Z" fill="currentColor"></path><path d="M57.3833 12.8478L62.1263 0.947754H63.9113L68.6373 12.8478H66.9713L65.7473 9.78775H60.2223L58.9983 12.8478H57.3833ZM60.7323 8.44475H65.2543L62.9933 2.73275L60.7323 8.44475Z" fill="currentColor"></path><path d="M50.563 12.9836C49.7243 12.9836 48.982 12.7966 48.336 12.4226C47.7013 12.0372 47.2027 11.5159 46.84 10.8586C46.4887 10.1899 46.313 9.42489 46.313 8.56356C46.313 7.91756 46.415 7.32822 46.619 6.79556C46.823 6.25156 47.112 5.78122 47.486 5.38455C47.86 4.98789 48.302 4.68189 48.812 4.46655C49.3333 4.25122 49.9 4.14355 50.512 4.14355C51.1693 4.14355 51.753 4.26255 52.263 4.50055C52.7843 4.72722 53.2207 5.05589 53.572 5.48655C53.9347 5.90589 54.1897 6.39889 54.337 6.96555C54.4957 7.52089 54.5297 8.11589 54.439 8.75056H47.146V7.59455H52.96C52.96 7.12989 52.8523 6.73322 52.637 6.40455C52.4217 6.07589 52.1327 5.82656 51.77 5.65656C51.4073 5.47522 50.988 5.38455 50.512 5.38455C50.104 5.38455 49.73 5.45822 49.39 5.60555C49.05 5.74155 48.761 5.94555 48.523 6.21756C48.285 6.47822 48.1037 6.80689 47.979 7.20356C47.8543 7.58889 47.792 8.03089 47.792 8.52955C47.792 9.19822 47.911 9.77056 48.149 10.2466C48.387 10.7226 48.71 11.0909 49.118 11.3516C49.5373 11.6009 50.019 11.7256 50.563 11.7256C51.0277 11.7256 51.4243 11.6519 51.753 11.5046C52.093 11.3572 52.365 11.1646 52.569 10.9266C52.773 10.6772 52.9033 10.4052 52.96 10.1106H54.422C54.32 10.6432 54.116 11.1306 53.81 11.5726C53.504 12.0032 53.0847 12.3489 52.552 12.6096C52.0193 12.8589 51.3563 12.9836 50.563 12.9836Z" fill="currentColor"></path><path d="M37.4893 12.8475V0.811523H38.9343V5.75852C39.1043 5.46386 39.3366 5.19186 39.6313 4.94252C39.9259 4.69319 40.2716 4.50052 40.6683 4.36452C41.0649 4.21719 41.5013 4.14352 41.9773 4.14352C42.5779 4.14352 43.1163 4.26819 43.5923 4.51752C44.0796 4.75552 44.4593 5.10686 44.7313 5.57152C45.0146 6.02486 45.1563 6.58019 45.1563 7.23752V12.8475H43.6943V7.56052C43.6943 7.09586 43.6093 6.70486 43.4393 6.38752C43.2693 6.07019 43.0369 5.83219 42.7423 5.67352C42.4476 5.50352 42.0963 5.41852 41.6883 5.41852C41.1329 5.41852 40.6456 5.57152 40.2263 5.87752C39.8183 6.18352 39.5009 6.61986 39.2743 7.18652C39.0476 7.74186 38.9343 8.40486 38.9343 9.17552V12.8475H37.4893Z" fill="currentColor"></path><path d="M35.3629 12.9835C34.8643 12.9835 34.4223 12.8815 34.0369 12.6775C33.6629 12.4735 33.3739 12.1732 33.1699 11.7765C32.9659 11.3685 32.8639 10.8812 32.8639 10.3145V2.10352H34.3259V10.1955C34.3259 10.6942 34.4336 11.0682 34.6489 11.3175C34.8756 11.5668 35.1816 11.6915 35.5669 11.6915C35.7369 11.6915 35.9013 11.6745 36.0599 11.6405C36.2299 11.6065 36.3829 11.5668 36.5189 11.5215V12.7625C36.3829 12.8305 36.2129 12.8815 36.0089 12.9155C35.8049 12.9608 35.5896 12.9835 35.3629 12.9835ZM31.4189 5.48652V4.27952H36.5019V5.48652H31.4189Z" fill="currentColor"></path><path d="M26.6505 12.9836C26.0272 12.9836 25.4888 12.8816 25.0355 12.6776C24.5822 12.4622 24.2308 12.1676 23.9815 11.7936C23.7435 11.4196 23.6245 11.0002 23.6245 10.5356C23.6245 9.67422 23.9305 9.01689 24.5425 8.56356C25.1658 8.11022 26.1632 7.81555 27.5345 7.67955L29.6425 7.45856V8.58055L27.6195 8.81855C27.0302 8.88656 26.5485 8.98855 26.1745 9.12455C25.8005 9.24922 25.5228 9.41922 25.3415 9.63455C25.1715 9.84989 25.0865 10.1332 25.0865 10.4846C25.0865 10.9039 25.2395 11.2326 25.5455 11.4706C25.8515 11.6972 26.2595 11.8106 26.7695 11.8106C27.2795 11.8106 27.7272 11.6859 28.1125 11.4366C28.5092 11.1759 28.8152 10.8359 29.0305 10.4166C29.2572 9.98589 29.3705 9.52122 29.3705 9.02256V7.18655C29.3705 6.80122 29.2798 6.47822 29.0985 6.21756C28.9285 5.95689 28.6905 5.75289 28.3845 5.60555C28.0785 5.45822 27.7158 5.38455 27.2965 5.38455C26.7072 5.38455 26.2142 5.52622 25.8175 5.80955C25.4208 6.09289 25.1772 6.52922 25.0865 7.11856H23.6585C23.7492 6.52922 23.9588 6.01355 24.2875 5.57155C24.6162 5.12955 25.0412 4.78389 25.5625 4.53455C26.0838 4.27389 26.6618 4.14355 27.2965 4.14355C27.9765 4.14355 28.5828 4.27389 29.1155 4.53455C29.6482 4.78389 30.0675 5.15222 30.3735 5.63955C30.6795 6.11555 30.8325 6.68222 30.8325 7.33956V12.8476H29.3705V11.4196C29.2572 11.6916 29.0645 11.9466 28.7925 12.1846C28.5205 12.4226 28.1975 12.6152 27.8235 12.7626C27.4495 12.9099 27.0585 12.9836 26.6505 12.9836Z" fill="currentColor"></path><path d="M18.8687 12.9836C18.03 12.9836 17.2877 12.7966 16.6417 12.4226C16.007 12.0372 15.5083 11.5159 15.1457 10.8586C14.7943 10.1899 14.6187 9.42489 14.6187 8.56356C14.6187 7.91756 14.7207 7.32822 14.9247 6.79556C15.1287 6.25156 15.4177 5.78122 15.7917 5.38455C16.1657 4.98789 16.6077 4.68189 17.1177 4.46655C17.639 4.25122 18.2057 4.14355 18.8177 4.14355C19.475 4.14355 20.0587 4.26255 20.5687 4.50055C21.09 4.72722 21.5263 5.05589 21.8777 5.48655C22.2403 5.90589 22.4953 6.39889 22.6427 6.96555C22.8013 7.52089 22.8353 8.11589 22.7447 8.75056H15.4517V7.59455H21.2657C21.2657 7.12989 21.158 6.73322 20.9427 6.40455C20.7273 6.07589 20.4383 5.82656 20.0757 5.65656C19.713 5.47522 19.2937 5.38455 18.8177 5.38455C18.4097 5.38455 18.0357 5.45822 17.6957 5.60555C17.3557 5.74155 17.0667 5.94555 16.8287 6.21756C16.5907 6.47822 16.4093 6.80689 16.2847 7.20356C16.16 7.58889 16.0977 8.03089 16.0977 8.52955C16.0977 9.19822 16.2167 9.77056 16.4547 10.2466C16.6927 10.7226 17.0157 11.0909 17.4237 11.3516C17.843 11.6009 18.3247 11.7256 18.8687 11.7256C19.3333 11.7256 19.73 11.6519 20.0587 11.5046C20.3987 11.3572 20.6707 11.1646 20.8747 10.9266C21.0787 10.6772 21.209 10.4052 21.2657 10.1106H22.7277C22.6257 10.6432 22.4217 11.1306 22.1157 11.5726C21.8097 12.0032 21.3903 12.3489 20.8577 12.6096C20.325 12.8589 19.662 12.9836 18.8687 12.9836Z" fill="currentColor"></path><path d="M9.7627 12.8476V4.27956H11.2077V5.79255C11.3777 5.42989 11.5987 5.12389 11.8707 4.87456C12.1427 4.62522 12.4544 4.44389 12.8057 4.33055C13.157 4.20589 13.514 4.14355 13.8767 4.14355C14.058 4.14355 14.228 4.16055 14.3867 4.19456C14.5567 4.22855 14.7154 4.27955 14.8627 4.34756V5.72455C14.6474 5.63389 14.449 5.57722 14.2677 5.55455C14.0864 5.53189 13.905 5.52055 13.7237 5.52055C13.3724 5.52055 13.038 5.60555 12.7207 5.77556C12.4147 5.94555 12.1484 6.19489 11.9217 6.52355C11.695 6.85222 11.5194 7.26022 11.3947 7.74755C11.27 8.23489 11.2077 8.79022 11.2077 9.41356V12.8476H9.7627Z" fill="currentColor"></path><path d="M0 12.8478V0.947754H4.607C5.27567 0.947754 5.85933 1.07242 6.358 1.32175C6.868 1.55975 7.26467 1.89975 7.548 2.34175C7.84267 2.78375 7.99 3.29375 7.99 3.87175C7.99 4.29109 7.89933 4.67642 7.718 5.02775C7.53667 5.37909 7.30433 5.67942 7.021 5.92875C6.749 6.16675 6.45433 6.33676 6.137 6.43876C6.56767 6.51809 6.96433 6.69942 7.327 6.98275C7.68967 7.26609 7.97867 7.61742 8.194 8.03675C8.42067 8.45609 8.534 8.91509 8.534 9.41375C8.534 10.0938 8.364 10.6944 8.024 11.2158C7.69533 11.7258 7.23633 12.1281 6.647 12.4228C6.05767 12.7061 5.38333 12.8478 4.624 12.8478H0ZM1.564 11.5048H4.505C4.981 11.5048 5.406 11.4141 5.78 11.2328C6.154 11.0401 6.44867 10.7851 6.664 10.4678C6.87933 10.1391 6.987 9.76509 6.987 9.34575C6.987 8.92642 6.87933 8.55242 6.664 8.22375C6.44867 7.89509 6.154 7.64009 5.78 7.45875C5.406 7.27742 4.981 7.18675 4.505 7.18675H1.564V11.5048ZM1.564 5.86075H4.403C4.79967 5.86075 5.151 5.78709 5.457 5.63975C5.77433 5.48109 6.02367 5.27142 6.205 5.01075C6.38633 4.73875 6.477 4.42709 6.477 4.07575C6.477 3.71309 6.38633 3.40142 6.205 3.14075C6.02367 2.88009 5.77433 2.67609 5.457 2.52875C5.151 2.37009 4.79967 2.29075 4.403 2.29075H1.564V5.86075Z" fill="currentColor"></path></svg>';
	}

	public static function sanitize_custom_svg( $svg ) {
		if ( empty( $svg ) ) return '';
		$svg = trim( (string) $svg );
		$svg = preg_replace( '#<\s*script[^>]*>.*?<\s*/\s*script\s*>#is', '', $svg );
		$svg = preg_replace( '#<\s*script[^>]*/?>#i', '', $svg );
		$svg = preg_replace( '#<\s*foreignObject[^>]*>.*?<\s*/\s*foreignObject\s*>#is', '', $svg );
		$svg = preg_replace( '#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $svg );
		$svg = preg_replace( '#\s+(xlink:href|href)\s*=\s*("|\')?\s*javascript:[^"\'>\s]*("|\')?#i', '', $svg );
		return $svg;
	}

	private static function default_cards() {
		$base = 'https://cdn.prod.website-files.com/6a423981a98fdcf4095b10ff/';
		return [
			[ 'image' => [ 'url' => $base . '6a423dbf2b6b7cd5bbd13f57_Striped_Fabric_Flowing_Against_Clear_Blue_Sky.avif' ],   'alt' => 'Striped Fabric' ],
			[ 'image' => [ 'url' => $base . '6a423dbff54dad74005c11e5_Majestic_Rock_Formations_Under_Soft_Sky.avif' ],       'alt' => 'Rock Formations' ],
			[ 'image' => [ 'url' => $base . '6a423dbf4721a5f823f14da4_Serene_Senior_Woman_in_Warm_Sunlight_Against_Clear_Blue_Sky.avif' ], 'alt' => 'Serene Portrait' ],
			[ 'image' => [ 'url' => $base . '6a42689a25fe47bc5049a99b_Tranquil_Rowing_at_Sunrise_with_Birds_in_Flight.avif' ], 'alt' => 'Rowing Sunrise' ],
			[ 'image' => [ 'url' => $base . '6a423dbf69f858fcfa5ea9af_Hands_Holding_Striped_Towel_Against_Clear_Summer_Sky.avif' ], 'alt' => 'Striped Towel' ],
		];
	}

	public static function register_controls( Controls_Stack $element ) {
		$element->start_controls_section( self::key( 'section' ), [
			'label' => __( 'Loaders • Dropping Cards', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$element->add_control( self::key( 'enable' ), [
			'label'        => __( 'Enable Loader', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$cond = [ self::key( 'enable' ) => 'yes' ];

		/* === LOGO === */
		$element->add_control( self::key( 'logo_heading' ), [
			'label'     => __( 'Logo', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'logo_type' ), [
			'label'   => __( 'Logo Type', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default SVG (Osmo)', 'elementor-gsap' ),
				'image'   => __( 'Image Upload', 'elementor-gsap' ),
				'svg'     => __( 'Custom SVG', 'elementor-gsap' ),
				'none'    => __( 'None', 'elementor-gsap' ),
			],
			'default'   => 'default',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'logo_image' ), [
			'label'     => __( 'Logo Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => '' ],
			'condition' => array_merge( $cond, [ self::key( 'logo_type' ) => 'image' ] ),
		] );

		$element->add_control( self::key( 'logo_svg' ), [
			'label'       => __( 'Custom SVG Code', 'elementor-gsap' ),
			'description' => __( 'Paste kode <code>&lt;svg&gt;…&lt;/svg&gt;</code>. Gunakan <code>fill="currentColor"</code> agar warna ikut Logo Color.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 8,
			'default'     => '',
			'condition'   => array_merge( $cond, [ self::key( 'logo_type' ) => 'svg' ] ),
		] );

		$element->add_control( self::key( 'logo_color' ), [
			'label'     => __( 'Logo Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-logo-color: {{VALUE}};',
			],
			'condition' => array_merge( $cond, [ self::key( 'logo_type' ) . '!' => 'none' ] ),
		] );

		$element->add_control( self::key( 'logo_width' ), [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 3, 'max' => 20, 'step' => 0.1 ],
				'px' => [ 'min' => 40, 'max' => 300 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 8 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-logo-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => array_merge( $cond, [ self::key( 'logo_type' ) . '!' => 'none' ] ),
		] );

		$element->add_control( self::key( 'logo_bottom' ), [
			'label'      => __( 'Logo Bottom Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 150 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-logo-bottom: {{SIZE}}{{UNIT}};',
			],
			'condition'  => array_merge( $cond, [ self::key( 'logo_type' ) . '!' => 'none' ] ),
		] );

		/* === CARDS === */
		$element->add_control( self::key( 'cards_heading' ), [
			'label'     => __( 'Cards', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'alt', [
			'label'   => __( 'Alt Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		] );

		$element->add_control( self::key( 'cards' ), [
			'label'       => __( 'Cards', 'elementor-gsap' ),
			'description' => __( 'Minimal 2 kartu untuk animasi drop yang natural. Order = urutan jatuh (dari atas stack ke bawah).', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ alt }}}',
			'default'     => self::default_cards(),
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'card_width' ), [
			'label'      => __( 'Card Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 5, 'max' => 25, 'step' => 0.25 ],
				'px' => [ 'min' => 80, 'max' => 400 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 10 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-card-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		$element->add_control( self::key( 'card_aspect' ), [
			'label'       => __( 'Card Aspect Ratio', 'elementor-gsap' ),
			'description' => __( 'Format <code>width / height</code>. Contoh: <code>3 / 4</code>, <code>1 / 1</code>, <code>4 / 5</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '3 / 4',
			'selectors'   => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-card-aspect: {{VALUE}};',
			],
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'card_radius' ), [
			'label'      => __( 'Card Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-card-radius: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		$element->add_control( self::key( 'card_gap' ), [
			'label'       => __( 'Card Gap (Stack Spacing)', 'elementor-gsap' ),
			'description' => __( 'Gap horizontal antar card (untuk display flex sebelum di-stack).', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 30 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'   => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-card-gap: {{SIZE}}{{UNIT}};',
			],
			'condition'   => $cond,
		] );

		/* === COLORS === */
		$element->add_control( self::key( 'colors_heading' ), [
			'label'     => __( 'Colors', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'bg_color' ), [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#14273a',
			'selectors' => [
				'{{WRAPPER}} .egsap-dcl-container' => '--dcl-bg: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		/* === ANIMATION === */
		$element->add_control( self::key( 'anim_heading' ), [
			'label'     => __( 'Animation', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'scale_decrease' ), [
			'label'       => __( 'Scale Decrease per Stack', 'elementor-gsap' ),
			'description' => __( 'Kartu belakang mengecil sebanyak nilai ini. Default 0.1.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 0.5,
			'step'        => 0.01,
			'default'     => 0.1,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'y_offset' ), [
			'label'       => __( 'Y Offset per Stack (%)', 'elementor-gsap' ),
			'description' => __( 'Kartu belakang ke atas sebanyak nilai ini (yPercent). Default -7.5.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => -30,
			'max'         => 30,
			'step'        => 0.5,
			'default'     => -7.5,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'fall_stagger' ), [
			'label'       => __( 'Total Fall Stagger (s)', 'elementor-gsap' ),
			'description' => __( 'Total waktu semua kartu jatuh. Kecil = jatuh cepat berurutan.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 5,
			'step'        => 0.05,
			'default'     => 0.75,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'deck_duration' ), [
			'label'       => __( 'Deck Move Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi re-stacking sisa kartu tiap kali satu kartu jatuh.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 1,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'rotation_pattern' ), [
			'label'       => __( 'Rotation Pattern (deg)', 'elementor-gsap' ),
			'description' => __( 'Comma-separated. Rotasi tiap kartu saat jatuh, dipakai looping urut. Default: <code>-10, 10, -15, 10, 20</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '-10, 10, -15, 10, 20',
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'x_pattern' ), [
			'label'       => __( 'X Offset Pattern (%)', 'elementor-gsap' ),
			'description' => __( 'Comma-separated. Offset horizontal (xPercent) tiap kartu saat jatuh. Default: <code>-5, 7.5, 10, 5, -10</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '-5, 7.5, 10, 5, -10',
			'condition'   => $cond,
		] );

		$element->end_controls_section();
	}

	public static function render( array $s, $element_id = '' ) {
		$cards = ! empty( $s[ self::key( 'cards' ) ] ) ? $s[ self::key( 'cards' ) ] : [];
		if ( empty( $cards ) ) {
			return;
		}

		$logo_type       = ! empty( $s[ self::key( 'logo_type' ) ] ) ? $s[ self::key( 'logo_type' ) ] : 'default';
		$scale_decrease  = isset( $s[ self::key( 'scale_decrease' ) ] ) && '' !== $s[ self::key( 'scale_decrease' ) ] ? floatval( $s[ self::key( 'scale_decrease' ) ] ) : 0.1;
		$y_offset        = isset( $s[ self::key( 'y_offset' ) ] )        && '' !== $s[ self::key( 'y_offset' ) ]        ? floatval( $s[ self::key( 'y_offset' ) ] )        : -7.5;
		$fall_stagger    = isset( $s[ self::key( 'fall_stagger' ) ] )    && '' !== $s[ self::key( 'fall_stagger' ) ]    ? floatval( $s[ self::key( 'fall_stagger' ) ] )    : 0.75;
		$deck_duration   = isset( $s[ self::key( 'deck_duration' ) ] )   && '' !== $s[ self::key( 'deck_duration' ) ]   ? floatval( $s[ self::key( 'deck_duration' ) ] )   : 1;
		$rotation_patt   = isset( $s[ self::key( 'rotation_pattern' ) ] ) ? $s[ self::key( 'rotation_pattern' ) ] : '-10, 10, -15, 10, 20';
		$x_patt          = isset( $s[ self::key( 'x_pattern' ) ] )        ? $s[ self::key( 'x_pattern' ) ]        : '-5, 7.5, 10, 5, -10';

		$id_attr = $element_id ? ' data-egsap-id="' . esc_attr( $element_id ) . '"' : '';
		?>
		<div
			class="egsap-dcl-container"
			data-loading-container
			data-egsap-dcl
			<?php echo $id_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			data-egsap-dcl-scale-decrease="<?php echo esc_attr( $scale_decrease ); ?>"
			data-egsap-dcl-y-offset="<?php echo esc_attr( $y_offset ); ?>"
			data-egsap-dcl-fall-stagger="<?php echo esc_attr( $fall_stagger ); ?>"
			data-egsap-dcl-deck-duration="<?php echo esc_attr( $deck_duration ); ?>"
			data-egsap-dcl-rotation-pattern="<?php echo esc_attr( $rotation_patt ); ?>"
			data-egsap-dcl-x-pattern="<?php echo esc_attr( $x_patt ); ?>"
		>
			<div class="egsap-dcl-screen">
				<div data-loading-background class="egsap-dcl-background"></div>

				<?php if ( 'none' !== $logo_type ) : ?>
					<div data-loading-logo class="egsap-dcl-logo">
						<?php
						if ( 'image' === $logo_type && ! empty( $s[ self::key( 'logo_image' ) ]['url'] ) ) {
							echo '<img src="' . esc_url( $s[ self::key( 'logo_image' ) ]['url'] ) . '" alt="" />';
						} elseif ( 'svg' === $logo_type ) {
							$svg = isset( $s[ self::key( 'logo_svg' ) ] ) ? self::sanitize_custom_svg( $s[ self::key( 'logo_svg' ) ] ) : '';
							if ( '' !== $svg ) {
								echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
						} else {
							echo self::default_osmo_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
				<?php endif; ?>

				<div data-loading-cards-list class="egsap-dcl-list">
					<?php foreach ( $cards as $card ) :
						$img = ! empty( $card['image']['url'] ) ? $card['image']['url'] : '';
						if ( '' === $img ) continue;
						$alt = isset( $card['alt'] ) ? $card['alt'] : '';
						?>
						<div data-loading-card class="egsap-dcl-card">
							<img src="<?php echo esc_url( $img ); ?>" loading="lazy" alt="<?php echo esc_attr( $alt ); ?>">
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
