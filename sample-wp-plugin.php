<?php
/*
Plugin Name: XXXXX
Description: XXXXX
Author: BrodNet - Aplikacje Internetowe
Version: 0.1
Author URI: http://brodnet.pl/
Text Domain: bn-xxxxx
S3J6eXN6dG9mIE1pY2hhbHNraQ==
*/

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


function bn_register_mysettings() { // whitelist options
    register_setting( 'bn-option-group', 'warning_text' );
  }

function bn_add_menu()
{
  add_menu_page(
    'Komunikaty',
    'Komunikaty',
    '',
    'bn_options',
    '',
    plugins_url('brodnet-logo.png', __FILE__ )
  );
  add_submenu_page(
		'bn_options',
		__('Opcje', 'bn-netto'),
		__('Opcje', 'bn-netto'),
		'manage_options',
		'bn_thank_you_options',
		'bn_thank_you_options_page_html'
	);
}

if ( is_admin() ){ // admin actions
    add_action( 'admin_menu', 'bn_add_menu' );
    add_action( 'admin_init', 'bn_register_mysettings' );
  }   