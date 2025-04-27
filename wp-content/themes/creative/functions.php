<?php
/**
 * creative functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package creative
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function creative_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on creative, use a find and replace
		* to change 'creative' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'creative', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'creative' ),
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
			'creative_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

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
add_action( 'after_setup_theme', 'creative_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function creative_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'creative_content_width', 640 );
}
add_action( 'after_setup_theme', 'creative_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function creative_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'creative' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'creative' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'creative_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function creative_scripts() {
	wp_enqueue_style( 'creative-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'creative-style', 'rtl', 'replace' );

	// Fonts
	// Preconnect to Google Fonts
    echo '<link href="https://fonts.googleapis.com" rel="preconnect">' . "\n";
    echo '<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>' . "\n";

    // Enqueue the Google Fonts stylesheet
    wp_enqueue_style(
        'google-fonts', // Handle
        'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
        array(), // Dependencies
        null // Version
    );

	// Vendor CSS Files
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/css/bootstrap.min.css' );
   	wp_enqueue_style( 'bootstrap-icons', get_template_directory_uri() . '/assets/vendor/bootstrap-icons/bootstrap-icons.css' );
   	wp_enqueue_style( 'aos', get_template_directory_uri() . '/assets/vendor/aos/aos.css' );
   	wp_enqueue_style( 'glightbox', get_template_directory_uri() . '/assets/vendor/glightbox/css/glightbox.min.css' );
   	wp_enqueue_style( 'swiper', get_template_directory_uri() . '/assets/vendor/swiper/swiper-bundle.min.css' );

   	// Homepage CSS Files
	wp_enqueue_style( 'homepage', get_template_directory_uri() . '/assets/css/main.css' );

	wp_enqueue_script( 'creative-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

    // Vendor JS Files
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', array(), '1.0', true );
    wp_enqueue_script( 'php-email-form', get_template_directory_uri() . '/assets/vendor/php-email-form/validate.js', array(), '1.0', true );
    wp_enqueue_script( 'aos', get_template_directory_uri() . '/assets/vendor/aos/aos.js', array(), '1.0', true );
    wp_enqueue_script( 'glightbox', get_template_directory_uri() . '/assets/vendor/glightbox/js/glightbox.min.js', array(), '1.0', true );
    wp_enqueue_script( 'swiper', get_template_directory_uri() . '/assets/vendor/swiper/swiper-bundle.min.js', array(), '1.0', true );

    // Main JS File
    wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'bootstrap', 'aos', 'glightbox', 'swiper'), '1.0', true );

    if (is_user_logged_in()) { // Restrict script to logged-in users
		wp_enqueue_script('ajax-testimonial-search', get_template_directory_uri() . '/assets/js/ajax-testimonial-search.js', array('jquery'), null, true);
		wp_localize_script('ajax-testimonial-search', 'ajax_search_params', array(
			'ajax_url' => admin_url('admin-ajax.php'), // AJAX URL
		));
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'creative_scripts' );

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
 * Load custom post type
 */
require get_template_directory() . '/inc/custom-cpt.php';

/**
 * Add meta box in post type
 */
require get_template_directory() . '/inc/custom-metabox.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Add custom class to contact form 1
function creative_add_class_to_cf7_form($class) {
    $form_id = WPCF7_ContactForm::get_current()->id();
    
    if ($form_id === 6) { 
        $class .= ' php-email-form';
    }
    
    return $class;
}
add_filter('wpcf7_form_class_attr', 'creative_add_class_to_cf7_form');

// Custom testimonial search functionality
function handle_testimonial_ajax_search() {
    // Check if the user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('You must be logged in to search testimonials.');
        wp_die();
    }

    $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';

    if (empty($title)) {
        wp_send_json_success('<p>No testimonials found.</p>');
    }

    // Build WP_Query arguments
    $args = array(
        'post_type' => 'testimonials',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    // Add search filters
    $meta_query = array('relation' => 'AND');
    if (!empty($title)) {
        $args['s'] = $title;
    }

    // Add meta query if applicable
    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) : $query->the_post();
            ?>
            <div class="testimonial-item">
                <h3><?php the_title(); ?></h3>
                <p><?php the_content(); ?></p>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
        $content = ob_get_clean();
        wp_send_json_success($content);
    } else {
        wp_send_json_success('<p>No testimonials found.</p>');
    }

    wp_die();
}
add_action('wp_ajax_testimonial_ajax_search', 'handle_testimonial_ajax_search');
add_action('wp_ajax_nopriv_testimonial_ajax_search', '__return_false'); // Deny non-logged-in users
