<?php
/**
 * Travelify defining constants, adding files and WordPress core functionality.
 *
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 700;
}


if ( ! function_exists( 'travelify_setup' ) ):

	add_filter( 'widget_text', 'do_shortcode' );

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	add_action( 'after_setup_theme', 'travelify_setup' );

	/**
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 */

	function travelify_setup() {
		/**
		 * travelify_add_files hook
		 *
		 * Adding other addtional files if needed.
		 */
		do_action( 'travelify_add_files' );

		/* Travelify is now available for translation. */
		require( get_template_directory() . '/library/functions/i18n.php' );

		/** Load functions */
		require( get_template_directory() . '/library/functions/functions.php' );

		/** Load WP backend related functions */
		require( get_template_directory() . '/library/panel/themeoptions-defaults.php' );
		require( get_template_directory() . '/library/panel/metaboxes.php' );
		require( get_template_directory() . '/library/panel/show-post-id.php' );

		/** Load Shortcodes */
		require( get_template_directory() . '/library/functions/shortcodes.php' );

		/** Load WP Customizer */
		require( get_template_directory() . '/library/functions/customizer.php' );

		/** Load Structure */
		require( get_template_directory() . '/library/structure/header-extensions.php' );
		require( get_template_directory() . '/library/structure/sidebar-extensions.php' );
		require( get_template_directory() . '/library/structure/footer-extensions.php' );
		require( get_template_directory() . '/library/structure/content-extensions.php' );

		// /** TGMPA */
		// require( get_template_directory() . '/library/tgmpa/tgm-plugin-activation.php' );

		/**
		 * travelify_add_functionality hook
		 *
		 * Adding other addtional functionality if needed.
		 */
		do_action( 'travelify_add_functionality' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page.
		add_theme_support( 'post-thumbnails' );

		// WordPress core logo upload (Appearance > Customize > Site Identity)
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		// This theme uses wp_nav_menu() in header menu location.
		register_nav_menu( 'primary', __( 'Primary Menu', 'travelify' ) );

		// Add Travelify custom image sizes
		add_image_size( 'travelify-featured', 670, 300, true );
		add_image_size( 'travelify-featured-medium', 230, 230, true );
		add_image_size( 'travelify-slider', 1018, 460, true );        // used on Featured Slider on Homepage Header
		add_image_size( 'travelify-gallery', 474, 342, true );                // used to show gallery all images

		// This feature enables WooCommerce support for a theme.
		add_theme_support( 'woocommerce' );

		/**
		 * This theme supports custom background color and image
		 */
		$args = array(
			'default-color' => '#d3d3d3',
			'default-image' => get_template_directory_uri() . '/images/background.png',
		);
		add_theme_support( 'custom-background', $args );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * This theme supports add_editor_style
		 */
		add_editor_style();

        /**
         * Update WP default Addition CSS with Travelify's Custom CSS
         */
        // Get our own Custom CSS
        $travelify_options = get_option('travelify_theme_options');

        if (isset($travelify_options['custom_css'])) {
            // Get Additional CSS for Travelify
            $posts = get_posts(array(
                'post_type'   => 'custom_css',
                'name'        => 'travelify',
                'orderby'     => 'date',
                'order'       => 'DESC',
                'numberposts' => 1
            ));

            // We get only 1 post
            if ($posts && !empty($posts)) {
                $travelify_wp_css = $posts[0];

                // Create the new content
                $travelify_wp_css->post_content = $travelify_wp_css->post_content . $travelify_options['custom_css'];
                // Update post with new content
                wp_update_post($travelify_wp_css);
                // Unset custom_css option, previous set bye theme
                unset($travelify_options['custom_css']);
                // Delete transient
                delete_transient('travelify_internal_css');
                // Update option with new values ( no custom_css )
                update_option('travelify_theme_options', $travelify_options);
            }
        }

	}
endif; // travelify_setup

/**
 * Twitter Card görsel etiketi.
 * Not (2026-07-12): Yoast SEO bu sitelerde og:title / og:description / og:url /
 * og:image ve twitter:card / twitter:label / twitter:data etiketlerini kendisi
 * üretiyor — sadece twitter:image'ı üretmiyor (Twitter Card özel görseli),
 * bu eksik burada tamamlanıyor (öne çıkan görsel üzerinden).
 * (Önceden og:image de burada basılıyordu, ama Yoast bunu zaten kendisi
 * ürettiği için sayfada mükerrer og:image etiketine yol açıyordu — kaldırıldı.)
 */
add_action( 'wp_head', 'travelify_social_image_meta', 5 );
function travelify_social_image_meta() {
	if ( ! is_singular() || ! has_post_thumbnail() ) {
		return;
	}

	$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
	if ( ! $image ) {
		return;
	}

	list( $url, $width, $height ) = $image;

	printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $url ) );
	printf( '<meta name="twitter:image:width" content="%d" />' . "\n", (int) $width );
	printf( '<meta name="twitter:image:height" content="%d" />' . "\n", (int) $height );
}

// Emoji script/style'larını kaldır (wp-emoji-release.min.js 404 önlemi)
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
} );

?>