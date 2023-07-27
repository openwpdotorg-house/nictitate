<?php
/**
 * Implement Custom Header functionality for Nictitate
 *
 * @package WordPress
 * @subpackage Nictitate
 * @since Nictitate 1.0.4
 */

/**
 * Load site title font family
 */
function kopa_admin_custom_header_fonts() {
	wp_enqueue_style( 'kopa-nictitate-rokkit', 'http://fonts.googleapis.com/css?family=Rokkitt', array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'kopa_admin_custom_header_fonts' );

/**
 * Set up the WordPress core custom header settings.
 *
 * @since Nictitate 1.0.4
 *
 * @uses kopa_header_style()
 * @uses kopa_admin_header_style()
 * @uses kopa_admin_header_image()
 */
function kopa_custom_header_setup() {
	/**
	 * Filter Kopa custom-header support arguments.
	 *
	 * @since Nictitate 1.0.4
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type bool   $header_text            Whether to display custom header text. Default false.
	 *     @type int    $width                  Width in pixels of the custom header image. Default 1160.
	 *     @type int    $height                 Height in pixels of the custom header image. Default 101.
	 *     @type bool   $flex_height            Whether to allow flexible-height header images. Default true.
	 *     @type string $admin_head_callback    Callback function used to style the image displayed in
	 *                                          the Appearance > Header screen.
	 *     @type string $admin_preview_callback Callback function used to create the custom header markup in
	 *                                          the Appearance > Header screen.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'kopa_custom_header_args', array(
		'default-text-color'     => '444',
		'width'                  => 1160,
		'height'                 => 101,
		'flex-height'            => true,
		'wp-head-callback'       => 'kopa_header_style',
		'admin-head-callback'    => 'kopa_admin_header_style',
		'admin-preview-callback' => 'kopa_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'kopa_custom_header_setup' );

if ( ! function_exists( 'kopa_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see kopa_custom_header_setup().
 *
 */
function kopa_header_style() {
	$text_color = get_header_textcolor();

	// If no custom color for text is set, let's bail.
	if ( display_header_text() && $text_color === get_theme_support( 'custom-header', 'default-text-color' ) )
		return;

	// If we get this far, we have custom styles.
	?>
	<style type="text/css" id="kopa-header-css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.site-title {
			clip: rect(1px 1px 1px 1px); /* IE7 */
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
	<?php
		// If the user has set a custom color for the text, use that.
		elseif ( $text_color != get_theme_support( 'custom-header', 'default-text-color' ) ) :
	?>
		.site-title a {
			color: #<?php echo esc_attr( $text_color ); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // twentyfourteen_header_style


if ( ! function_exists( 'kopa_admin_header_style' ) ) :
/**
 * Style the header image displayed on the Appearance > Header screen.
 *
 * @see kopa_custom_header_setup()
 *
 * @since Nictitate 1.0.4
 */
function kopa_admin_header_style() {
?>
	<style type="text/css" id="kopa-admin-header-css">
	.appearance_page_custom-header #headimg {
		background-color: #f9f9f9;
		border: none;
		max-width: 1160px;
		min-height: 48px;
	}
	#headimg h1 {
		font-family: Rokkitt, serif;
		font-size: 18px;
		margin: 0 0 0 30px;
	}
	#headimg h1 a {
		color: #444;
		text-decoration: none;
		font-weight: 400;
	}
	#headimg h1 a:hover {
		color: #33bee5;
	}
	#headimg img {
		vertical-align: middle;
		margin: 0 0 0 30px;
		padding-top: 10px;
	}
	</style>
<?php
}
endif; // twentyfourteen_admin_header_style

if ( ! function_exists( 'kopa_admin_header_image' ) ) :
/**
 * Create the custom header image markup displayed on the Appearance > Header screen.
 *
 * @see kopa_custom_header_setup()
 *
 * @since Nictitate 1.0.4
 */
function kopa_admin_header_image() {
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
		<h1 class="displaying-header-text"><a id="name"<?php echo sprintf( ' style="color:#%s;"', get_header_textcolor() ); ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
	</div>
<?php
}
endif; // twentyfourteen_admin_header_image
