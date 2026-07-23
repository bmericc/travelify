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
			'default-image' => get_template_directory_uri() . '/images/background.webp',
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
 * X/LinkedIn AVIF sorunu: botlar AVIF formatını desteklemiyor.
 * Bu helper AVIF URL'ini orijinal JPEG/PNG URL'e çevirir.
 * wp_get_original_image_url() yüklenen orijinal dosyayı döner (format dönüşümü öncesi).
 */
function travelify_social_image_url( int $attachment_id ): ?string {
	// content-manager'ın sosyal medya için yüklediği küçük JPEG
	global $post;
	if ( $post ) {
		$cm_url = get_post_meta( $post->ID, '_cm_social_image_url', true );
		if ( $cm_url ) {
			return $cm_url;
		}
	}

	$orig = wp_get_original_image_url( $attachment_id );
	if ( $orig && ! preg_match( '/\.avif$/i', $orig ) ) {
		return $orig;
	}

	$src = wp_get_attachment_image_src( $attachment_id, 'full' );
	return $src ? $src[0] : null;
}

// Yoast og:image override — doğru filter adı: wpseo_opengraph_image
add_filter( 'wpseo_opengraph_image', function( $url ) {
	global $post;
	if ( $post ) {
		$cm_url = get_post_meta( $post->ID, '_cm_social_image_url', true );
		if ( $cm_url ) return $cm_url;
	}
	return $url;
}, 20 );

// Jetpack og:image override — _cm_social_image_url varsa JPEG kullan
add_filter( 'jetpack_open_graph_tags', function( $tags ) {
	global $post;
	if ( ! $post ) return $tags;
	$cm_url = get_post_meta( $post->ID, '_cm_social_image_url', true );
	if ( ! $cm_url ) return $tags;
	$tags['og:image']            = $cm_url;
	$tags['og:image:secure_url'] = $cm_url;
	unset( $tags['og:image:type'] );
	return $tags;
}, 20 );

/**
 * Twitter Card görsel etiketi — AVIF yerine orijinal JPEG/PNG kullanır.
 * Not (2026-07-12): Yoast SEO bu sitelerde og:title / og:description / og:url /
 * og:image ve twitter:card / twitter:label / twitter:data etiketlerini kendisi
 * üretiyor — sadece twitter:image'ı üretmiyor (Twitter Card özel görseli),
 * bu eksik burada tamamlanıyor (öne çıkan görsel üzerinden).
 */
add_action( 'wp_head', 'travelify_social_image_meta', 99 );
function travelify_social_image_meta() {
	if ( ! is_singular() || ! has_post_thumbnail() ) {
		return;
	}

	$thumb_id = get_post_thumbnail_id();
	$url      = travelify_social_image_url( $thumb_id );
	if ( ! $url ) {
		return;
	}

	$image = wp_get_attachment_image_src( $thumb_id, 'full' );
	[ , $width, $height ] = $image ?: [ null, 0, 0 ];

	// og:image için de JPEG URL kullan (başka plugin AVIF koyuyorsa override et)
	global $post;
	$cm_url = $post ? get_post_meta( $post->ID, '_cm_social_image_url', true ) : '';
	if ( $cm_url ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $cm_url ) );
		if ( $width )  printf( '<meta property="og:image:width" content="%d" />' . "\n", (int) $width );
		if ( $height ) printf( '<meta property="og:image:height" content="%d" />' . "\n", (int) $height );
	}

	printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $url ) );
	if ( $width )  printf( '<meta name="twitter:image:width" content="%d" />' . "\n", (int) $width );
	if ( $height ) printf( '<meta name="twitter:image:height" content="%d" />' . "\n", (int) $height );
}

/**
 * og:logo — sitenin özel logosu varsa (WP site icon / custom_logo) basılır.
 * Resmi OG standardı olmasa da bazı SEO araçları bu etiketi kontrol ediyor.
 */
add_action( 'wp_head', 'travelify_og_logo', 4 );
function travelify_og_logo() {
    $logo_id = get_theme_mod( 'custom_logo' );
    if ( $logo_id ) {
        $logo = wp_get_attachment_image_src( $logo_id, 'full' );
        if ( $logo ) {
            printf( '<meta property="og:logo" content="%s" />' . "\n", esc_url( $logo[0] ) );
            return;
        }
    }

    // Fallback: WP site icon
    $icon_url = get_site_icon_url( 512 );
    if ( $icon_url ) {
        printf( '<meta property="og:logo" content="%s" />' . "\n", esc_url( $icon_url ) );
    }
}

// Emoji script/style'larını kaldır (wp-emoji-release.min.js 404 önlemi)
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
} );

// Sosyal medya blok ikonları düzeltmesi — WP core inline style'ı (line-height:0) geç priority ile override eder
add_action( 'wp_head', function () {
	echo '<style>
.wp-block-social-links .wp-social-link a {
    line-height: 1 !important;
    width: auto !important;
    height: auto !important;
    display: flex !important;
    align-items: center !important;
    padding: .25em !important;
    margin: 0 !important;
    gap: .4em;
}
.wp-block-social-links .wp-social-link::before,
.wp-block-social-links .wp-social-link::after {
    content: none !important;
}
.wp-block-social-links .wp-social-link {
    list-style: none !important;
    background-image: none !important;
}
.wp-block-social-links .wp-social-link a {
    font-size: large !important;
}
.wp-block-social-links .wp-social-link a svg {
    width: 1.5em !important;
    height: 1.5em !important;
    flex-shrink: 0;
    display: block !important;
}
</style>' . "\n";
}, 9999 );

?>