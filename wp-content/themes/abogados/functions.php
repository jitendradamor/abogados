<?php
/**
 * abogados functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package abogados
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
function abogados_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on abogados, use a find and replace
		* to change 'abogados' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'abogados', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'abogados' ),
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
			'abogados_custom_background_args',
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
add_action( 'after_setup_theme', 'abogados_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function abogados_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'abogados_content_width', 640 );
}
add_action( 'after_setup_theme', 'abogados_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function abogados_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'abogados' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'abogados' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer left', 'abogados' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Add widgets here.', 'abogados' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer copy right', 'abogados' ),
			'id'            => 'sidebar-3',
			'description'   => esc_html__( 'Add widgets here.', 'abogados' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'abogados_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function abogados_scripts() {
	$version = time();

	wp_enqueue_style( 'mmenu-style', get_template_directory_uri() . '/assets/css/mmenu.css', array(), $version );
    wp_enqueue_style( 'mburger-style', get_template_directory_uri() . '/assets/css/mburger.css', array(), $version );
	wp_enqueue_style( 'abogados-style', get_stylesheet_uri(), array(), $version );
	wp_style_add_data( 'abogados-style', 'rtl', 'replace' );

	wp_enqueue_script( 'abogados-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'mmenu-js', get_template_directory_uri() . '/assets/js/mmenu.js', array(), $version, true );
	wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/assets/js/custom.js', array(), $version, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'abogados_scripts' );

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


function my_custom_gutenberg_block() {
    // Define the path to the blocks directory for easier access
    $block_path = get_template_directory() . '/blocks/';
    
    // Register the block editor script
    wp_register_script(
        'my-custom-block', // Unique handle for the script
        get_template_directory_uri() . '/blocks/my-block.js', // URL to the script file
        array( 'wp-blocks', 'wp-element', 'wp-editor' ), // Dependencies required for the block to function
        filemtime( $block_path . 'my-block.js' ), // Cache busting using the file modification time
        true // Load the script in the footer for better performance
    );
    // Register the custom block with WordPress
    register_block_type('my-theme/my-block', array(
        'editor_script' => 'my-custom-block', // Script to be used in the block editor
        'editor_style'  => 'my-custom-block-editor-style', // Editor-specific styles
        'style'         => 'my-custom-block-style', // Frontend styles
    ));
}

// Hook the function into WordPress' init action to ensure it runs at the right time
add_action('init', 'my_custom_gutenberg_block');

function abogados_post_listing_shortcode($atts) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 6, // Change as needed
        'post_status' => 'publish', // Only show publish posts
    );
    $query = new WP_Query($args);
	
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="post-listing">';
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="post-item">
                <div class="post-thumb">
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="post-image"><?php the_post_thumbnail('full'); ?></div>
                    <?php } ?>
                </div>

                <div class="post-details">
                    <div class="post-meta">
                        <span class="post-category"><?php the_category(', '); ?></span>
                        <?php
							$month = get_the_date('F'); 

							$months_in_spanish = [
								'January' => ['Enero', 'Ene'],
								'February' => ['Febrero', 'Feb'],
								'March' => ['Marzo', 'Mar'],
								'April' => ['Abril', 'Abr'],
								'May' => ['Mayo', 'May'],
								'June' => ['Junio', 'Jun'],
								'July' => ['Julio', 'Jul'],
								'August' => ['Agosto', 'Ago'],
								'September' => ['Septiembre', 'Sep'],
								'October' => ['Octubre', 'Oct'],
								'November' => ['Noviembre', 'Nov'],
								'December' => ['Diciembre', 'Dic'],
							];
							$translated_month_full = isset($months_in_spanish[$month]) ? $months_in_spanish[$month][0] : $month;
							$translated_month_short = isset($months_in_spanish[$month]) ? $months_in_spanish[$month][1] : '';

							echo "<span class='post-date'>" . get_the_date('d')." ".$translated_month_short." " . get_the_date('Y') . "</span>";
						?> 
						
                    </div>
                    <a href="<?php echo get_the_permalink(); ?>"><h3 class="post-title"><?php echo get_the_title(); ?></h3></a>
                    <a href="<?php echo get_the_permalink(); ?>" class="read-more">Seguir Leyendo</a>
                </div>
            </div>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode('abogados_post_listing', 'abogados_post_listing_shortcode');
