<?php
/**
 * Template Name: home page
 * Description: home page.
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

global $paged;
if (!isset($paged) || !$paged){
		$paged = 1;
}

$context = Timber::get_context();
$args = array( 'post_type' => 'post', 'posts_per_page' => '6', 'no_found_rows' => 'true', 'paged' => $paged );
$context['posts'] = new Timber\PostQuery($args);
$context['home'] = true;
$templates = array( 'home.twig' );
/*
if ( is_home() ) {
	array_unshift( $templates, 'home.twig' );
}
*/
Timber::render( $templates, $context );
