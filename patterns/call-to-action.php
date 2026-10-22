<?php
/**
 * Title: Follow the journey
 * Slug: travelify/call-to-action
 * Categories: travelify, call-to-action
 * Keywords: subscribe, follow, newsletter, call to action
 * Viewport Width: 700
 * Description: A closing panel that asks readers to follow along, with a heading, a line of text and a button.
 *
 * @package Travelify
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"32px","bottom":"32px","left":"24px","right":"24px"},"margin":{"top":"24px","bottom":"24px"}}},"backgroundColor":"page-background","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-page-background-background-color has-background" style="margin-top:24px;margin-bottom:24px;padding-top:32px;padding-right:24px;padding-bottom:32px;padding-left:24px"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Where to next?', 'travelify' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'Get the next trip report as soon as it is published.', 'travelify' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","textColor":"content-background"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-content-background-color has-primary-background-color has-text-color has-background wp-element-button"><?php esc_html_e( 'Follow along', 'travelify' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
