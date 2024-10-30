<?php
/*
Plugin Name: CS Disable Emojis
Plugin URI: https://chetansatasiya.com
Description: This plugin will disables the WordPress emoji functionality.
Version: 1.0
Author: Chetan Satasiya
Author URI: https://chetansatasiya.com/blog
License: GPL3
============================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited to, the
implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
consequential damages(including, but not limited to, procurement of substitute goods or services; loss of
use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
contract, strict liability, or tort(including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.

For full license details see license.txt
============================================================================================================

*/

/**
 * Function for remove all the default actions of emoji's.
 *
 * @author  Chetan Satasiya
 * @since   1.0
 *
 */
function cs_disable_emojis() {

	// Remove action hook for print the inline Emoji detection script if it is not already printed.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

	// Remove action hook for print the inline Emoji detection script if it is not already printed.
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

	// Remove action for Print the important emoji-related styles.
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	// Remove action for Print the important emoji-related styles.
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// Remove filter for Convert emoji to a static img element.
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );

	// Remove filter for Convert emoji to a static img element.
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	// Remove filter for Convert emoji in emails into static images.
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'tiny_mce_plugins', 'cs_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'cs_disable_emojis_remove_dns_prefetch', 10, 2 );
}

add_action( 'init', 'cs_disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array $plugins
 *
 * @author Chetan Saasiya
 * @since  1.0
 *
 * @return   array             Difference between the two arrays
 */
function cs_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param  array  $urls          URLs to print for resource hints.
 * @param  string $relation_type The relation type the URLs are printed for.
 *
 * @author Chetan Satasiya
 * @since  1.0
 * @return array                 Difference betwen the two arrays.
 */
function cs_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {

	if ( 'dns-prefetch' === $relation_type ) {

		// Strip out any URLs referencing the WordPress.org emoji location.
		$cs_emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ( $urls as $key => $url ) {
			if ( strpos( $url, $cs_emoji_svg_url_bit ) !== false ) {
				unset( $urls[ $key ] );
			}
		}
	}

	return $urls;
}
