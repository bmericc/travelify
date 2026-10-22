<?php
/**
 * Title: Trip at a glance
 * Slug: travelify/trip-facts
 * Categories: travelify, text
 * Keywords: travel, facts, budget, season, getting there, columns
 * Viewport Width: 700
 * Description: Three short columns for the practical details of a trip: when to go, how to get there and what it costs.
 *
 * @package Travelify
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"24px","bottom":"8px","left":"24px","right":"24px"},"margin":{"bottom":"24px"}}},"backgroundColor":"page-background","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-page-background-background-color has-background" style="margin-bottom:24px;padding-top:24px;padding-right:24px;padding-bottom:8px;padding-left:24px"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'At a glance', 'travelify' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading"><?php esc_html_e( 'When to go', 'travelify' ); ?></h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'The months with the best weather, and the ones to avoid.', 'travelify' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading"><?php esc_html_e( 'Getting there', 'travelify' ); ?></h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'The nearest airport or station, and how long the last leg takes.', 'travelify' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading"><?php esc_html_e( 'Budget', 'travelify' ); ?></h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Roughly what a day costs for a bed, food and getting around.', 'travelify' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
