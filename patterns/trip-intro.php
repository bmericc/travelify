<?php
/**
 * Title: Trip introduction
 * Slug: travelify/trip-intro
 * Categories: travelify, banner
 * Keywords: travel, trip, intro, cover, destination
 * Viewport Width: 700
 * Description: An opening banner for a trip report: the destination, one line about it and a button. Add a photo as the background in the block settings.
 *
 * @package Travelify
 */

?>
<!-- wp:cover {"overlayColor":"primary","minHeight":320,"style":{"spacing":{"padding":{"top":"48px","bottom":"48px","left":"32px","right":"32px"},"margin":{"bottom":"24px"}}}} -->
<div class="wp-block-cover" style="margin-bottom:24px;padding-top:48px;padding-right:32px;padding-bottom:48px;padding-left:32px;min-height:320px"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"content-background"} -->
<h2 class="wp-block-heading has-text-align-center has-content-background-color has-text-color"><?php esc_html_e( 'Ten days along the coast', 'travelify' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"content-background"} -->
<p class="has-text-align-center has-content-background-color has-text-color"><?php esc_html_e( 'One sentence on where you went and why. Swap the colour for a photo from the trip in the block settings.', 'travelify' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"content-background","textColor":"body"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-body-color has-content-background-background-color has-text-color has-background wp-element-button"><?php esc_html_e( 'See the route', 'travelify' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
