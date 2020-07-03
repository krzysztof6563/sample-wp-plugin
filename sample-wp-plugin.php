<?php
/*
Plugin Name: XXXXX
Description: XXXXX
Author: BrodNet - Aplikacje Internetowe
Version: 0.1
Author URI: http://brodnet.pl/
Text Domain: bn-project
S3J6eXN6dG9mIE1pY2hhbHNraQ==
*/

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


include __DIR__."/admin.php";

/**
 * POST HANDLING EXAMPLE
 * 
 * Make sure that your form contains following code:
 * <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
 * <input type="hidden" name="action" value="contact_form">
 * 
 */
function wp_post_example() {
    //POST and GET are available here
}
add_action( 'admin_post_nopriv_contact_form', 'wp_post_example');
add_action( 'admin_post_contact_form', 'wp_post_example' );

/**
 * SHORTCODE EXAMPLE
 */
function shortcode_example( $atts ) {
	$a = shortcode_atts( array(
		'foo' => 'something',
		'bar' => 'something else',
	), $atts );

	return "SOME HTML";
}
add_shortcode( 'example', 'shortcode_example' );