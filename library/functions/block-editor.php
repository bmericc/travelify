<?php
/**
 * Block editor integration: the theme.json palette, editor sizing and the
 * styles core withdraws once a theme ships theme.json.
 *
 * theme.json only carries settings -- the palette and the layout sizes. It
 * deliberately has no "styles" section: style.css stays the source of the
 * front end's look, and the palette exists so the editor offers the theme's
 * own colours and so has-*-color classes follow the Customizer.
 *
 * @package Travelify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'travelify_get_palette_mods' ) ) :
	/**
	 * Which Customizer colour feeds which theme.json palette slug.
	 *
	 * The header chrome colours (site title, menu bar, social icons) are not
	 * in the palette: they are not colours anyone would pick for a paragraph.
	 *
	 * Slugs must not collide with core's generic classes. A slug of "text",
	 * "link" or "border" generates a .has-text-color, .has-link-color or
	 * .has-border-color preset rule with !important, and core adds those
	 * classes to every block given any text, link or border colour -- so every
	 * coloured block would be forced to that palette colour. Hence "body",
	 * "links" and "divider".
	 *
	 * @return array Theme mod name => palette slug.
	 */
	function travelify_get_palette_mods() {
		return array(
			'travelify_element_color'       => 'primary',
			'travelify_element_hover_color' => 'primary-hover',
			'travelify_link_color'          => 'links',
			'travelify_link_hover_color'    => 'links-hover',
			'travelify_header_color'        => 'heading',
			'travelify_entry_color'         => 'body',
			'travelify_content_bg_color'    => 'content-background',
			'travelify_wrapper_color'       => 'page-background',
		);
	}
endif;

if ( ! function_exists( 'travelify_theme_json_customizer_colors' ) ) :
	/**
	 * Write the Customizer colours into the theme.json palette.
	 *
	 * So the editor's colour picker, the editor canvas (editor-style.css reads
	 * the palette variables) and the has-*-color classes in published content
	 * all use the colours the site is actually set to, not theme.json's
	 * defaults. Colours are re-validated on the way in, as they are for the
	 * front end's stylesheet.
	 *
	 * @param WP_Theme_JSON_Data $theme_json The theme's theme.json data.
	 * @return WP_Theme_JSON_Data
	 */
	function travelify_theme_json_customizer_colors( $theme_json ) {
		$overrides = array();

		foreach ( travelify_get_palette_mods() as $mod => $slug ) {
			$value = travelify_color_mod( $mod );

			if ( '' !== $value ) {
				$overrides[ $slug ] = $value;
			}
		}

		if ( empty( $overrides ) ) {
			return $theme_json;
		}

		$data    = $theme_json->get_data();
		$palette = isset( $data['settings']['color']['palette'] ) ? $data['settings']['color']['palette'] : array();

		// The resolver keys presets by origin; a bare list is the file's own shape.
		if ( isset( $palette['theme'] ) ) {
			$palette = $palette['theme'];
		}

		foreach ( $palette as $i => $entry ) {
			if ( isset( $entry['slug'], $overrides[ $entry['slug'] ] ) ) {
				$palette[ $i ]['color'] = $overrides[ $entry['slug'] ];
			}
		}

		return $theme_json->update_with(
			array(
				'version'  => 2,
				'settings' => array(
					'color' => array(
						'palette' => array_values( $palette ),
					),
				),
			)
		);
	}
endif;
add_filter( 'wp_theme_json_data_theme', 'travelify_theme_json_customizer_colors' );

if ( ! function_exists( 'travelify_editor_layout_sizes' ) ) :
	/**
	 * Size the block editor like the column the post will render in.
	 *
	 * theme.json says 919px, the No Sidebar, Full Width column, so that groups
	 * on the front end are never narrower than the column they sit in. Posts
	 * default to a sidebar layout, where the text column is 603px, so the
	 * editor would show lines half as long again as the published post. In the
	 * post editor the sizes follow the post's own layout instead; the front
	 * end is untouched.
	 *
	 * Measured against the rendered .entry-content at 1280px.
	 *
	 * @param WP_Theme_JSON_Data $theme_json The theme's theme.json data.
	 * @return WP_Theme_JSON_Data
	 */
	function travelify_editor_layout_sizes( $theme_json ) {
		global $pagenow;

		if ( ! is_admin() || ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return $theme_json;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only: which post the editor is open on.
		$post_id = ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) ? absint( $_GET['post'] ) : 0;
		$layout  = $post_id ? get_post_meta( $post_id, 'travelify_sidebarlayout', true ) : '';

		if ( empty( $layout ) || 'default' === $layout ) {
			global $travelify_theme_options_settings;
			$layout = isset( $travelify_theme_options_settings['default_layout'] ) ? $travelify_theme_options_settings['default_layout'] : 'right-sidebar';
		}

		$sizes = array(
			'left-sidebar'          => '603px',
			'right-sidebar'         => '603px',
			'no-sidebar'            => '635px',
			'no-sidebar-one-column' => '635px',
			'no-sidebar-full-width' => '919px',
		);

		if ( ! isset( $sizes[ $layout ] ) ) {
			return $theme_json;
		}

		/*
		 * Wide and full blocks do not break out of the column on the front end
		 * (the theme is boxed), so they are the content width here too.
		 */
		return $theme_json->update_with(
			array(
				'version'  => 2,
				'settings' => array(
					'layout' => array(
						'contentSize' => $sizes[ $layout ],
						'wideSize'    => $sizes[ $layout ],
					),
				),
			)
		);
	}
endif;
add_filter( 'wp_theme_json_data_theme', 'travelify_editor_layout_sizes' );

if ( ! function_exists( 'travelify_enqueue_classic_theme_styles' ) ) :
	/**
	 * Keep core's classic-theme button and file block styles.
	 *
	 * Core only loads classic-themes.css for themes without a theme.json.
	 * Adding one (for the palette) would otherwise square off every Button
	 * block and change the File block's download button on existing sites.
	 */
	function travelify_enqueue_classic_theme_styles() {
		wp_enqueue_style( 'classic-theme-styles' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'travelify_enqueue_classic_theme_styles' );
add_action( 'enqueue_block_assets', 'travelify_enqueue_classic_theme_styles' );

if ( ! function_exists( 'travelify_register_block_pattern_category' ) ) :
	/**
	 * The category the patterns in /patterns are filed under.
	 *
	 * WordPress registers the pattern files themselves, classic theme or not.
	 */
	function travelify_register_block_pattern_category() {
		register_block_pattern_category(
			'travelify',
			array( 'label' => esc_html__( 'Travelify', 'travelify' ) )
		);
	}
endif;
add_action( 'init', 'travelify_register_block_pattern_category' );
