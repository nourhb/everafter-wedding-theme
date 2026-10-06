<?php
/**
 * Ever After theme setup.
 *
 * @package EverAfter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup: supports, menus and widget areas.
 */
function everafter_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'everafter' ),
			'footer'  => __( 'Footer Menu', 'everafter' ),
		)
	);
}
add_action( 'after_setup_theme', 'everafter_setup' );

/**
 * Register footer widget area.
 */
function everafter_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Widgets', 'everafter' ),
			'id'            => 'footer-widgets',
			'description'   => __( 'Widgets shown in the footer area.', 'everafter' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'everafter_widgets_init' );

/**
 * Enqueue Google Fonts, stylesheet and front-end script.
 */
function everafter_enqueue_assets() {
	wp_enqueue_style(
		'everafter-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap',
		array(),
		'1.0.0'
	);

	wp_enqueue_style(
		'everafter-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script(
		'everafter-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'everafter_enqueue_assets' );

/**
 * Enqueue the editor stylesheet.
 */
function everafter_editor_assets() {
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'everafter_editor_assets' );

/**
 * Custom block styles.
 */
function everafter_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'outline-gold',
			'label' => __( 'Outline Gold', 'everafter' ),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'soft-card',
			'label' => __( 'Soft Card', 'everafter' ),
		)
	);

	register_block_style(
		'core/image',
		array(
			'name'  => 'arched',
			'label' => __( 'Arched Frame', 'everafter' ),
		)
	);

	register_block_style(
		'core/heading',
		array(
			'name'  => 'script-accent',
			'label' => __( 'Script Accent', 'everafter' ),
		)
	);
}
add_action( 'init', 'everafter_block_styles' );

/**
 * Register the Ever After pattern category.
 */
function everafter_pattern_category() {
	register_block_pattern_category(
		'everafter',
		array( 'label' => __( 'Ever After', 'everafter' ) )
	);
}
add_action( 'init', 'everafter_pattern_category' );

/**
 * Shorten excerpts to a readable length.
 *
 * @param int $length Default length.
 * @return int
 */
function everafter_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'everafter_excerpt_length' );

/**
 * Render a small inline SVG icon.
 *
 * @param string $name Icon name: ring, heart, flower.
 * @return string
 */
function everafter_icon( $name ) {
	$paths = array(
		'ring'   => '<circle cx="12" cy="14" r="6"/><path d="M9 9l1.5-4 3 3 3-3L18 9"/>',
		'heart'  => '<path d="M12 20s-7-4.5-9-9c-1.5-3.5 1-7 4.5-7 2 0 3.5 1.5 4.5 3 1-1.5 2.5-3 4.5-3 3.5 0 6 3.5 4.5 7-2 4.5-9 9-9 9z"/>',
		'flower' => '<circle cx="12" cy="12" r="2.5"/><path d="M12 2c1.5 2.5 1.5 5.5 0 8-1.5-2.5-1.5-5.5 0-8zm0 20c-1.5-2.5-1.5-5.5 0-8 1.5 2.5 1.5 5.5 0 8zM2 12c2.5-1.5 5.5-1.5 8 0-2.5 1.5-5.5 1.5-8 0zm20 0c-2.5 1.5-5.5 1.5-8 0 2.5-1.5 5.5-1.5 8 0z"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">' . $paths[ $name ] . '</svg>';
}
