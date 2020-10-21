<?php

/**
 * clean functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package clean
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'clean_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function clean_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on clean, use a find and replace
		 * to change 'clean' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'clean', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'clean' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'clean_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'post-formats', array(
			'standard',
			'aside',
			'gallery',
			'link',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat'
		) );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'clean_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function clean_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'clean_content_width', 640 );
}

add_action( 'after_setup_theme', 'clean_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function clean_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'clean' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'clean' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_action( 'widgets_init', 'clean_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function clean_scripts() {
	wp_enqueue_style( 'clean-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'clean-google-font1', 'https://fonts.googleapis.com/css?family=Roboto:400,300,100,500' );
	wp_enqueue_style( 'clean-google-font2', 'https://fonts.googleapis.com/css?family=Roboto+Slab:400,300,100,500' );
	wp_enqueue_style( 'clean-animate-style', get_template_directory_uri() . '/assets/css/animate.css' );
	wp_enqueue_style( 'clean-icomoon-style', get_template_directory_uri() . '/assets/css/icomoon.css' );
	wp_enqueue_style( 'clean-simple-line-icons-style', get_template_directory_uri() . '/assets/css/simple-line-icons.css' );
	wp_enqueue_style( 'clean-style2', get_template_directory_uri() . '/assets/css/style.css' );
	wp_enqueue_script( 'clean-modernizr', get_template_directory_uri() . '/assets/js/modernizr-2.6.2.min.js' );
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery.min.js' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'clean-jquery-easing', get_template_directory_uri() . '/assets/js/jquery.easing.1.3.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'clean-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js' );
	wp_enqueue_script( 'clean-waypoints', get_template_directory_uri() . '/assets/js/jquery.waypoints.min.js' );
	wp_enqueue_script( 'clean-main-js', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), true );
	wp_enqueue_script( 'clean-respond-js', get_template_directory_uri() . '/assets/js/respond.min.js', array( 'jquery' ), true );

//	wp_style_add_data( 'clean-style', 'rtl', 'replace' );
//	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
//		wp_enqueue_script( 'comment-reply' );
//	}
}

add_action( 'wp_enqueue_scripts', 'clean_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

