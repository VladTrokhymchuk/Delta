<?php
defined('ABSPATH') || exit;
/**
 * Filter URL entry before it gets added to the sitemap.
 *
 * @param array  $url  Array of URL parts.
 * @param string $type URL type. Can be user, post or term.
 * @param object $object Data object for the URL.
 */
add_filter( 'rank_math/sitemap/entry', function( $url, $type, $object ){

	$url = str_replace('/golovna', '', $url);
	return $url;
}, 10, 3 );

/**
 * Filter the URL Rank Math SEO uses in the XML sitemap for this post type archive.
 *
 * @param string $archive_url The URL of this archive
 * @param string $post_type   The post type this archive is for.
 */
add_filter( 'rank_math/sitemap/post_type_archive_link', function( $archive_url, $post_type ){
	return 0;
}, 10, 2 );