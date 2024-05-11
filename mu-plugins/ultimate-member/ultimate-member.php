<?php
/**
 * Plugin Name: Ultimate Member
 * Plugin URI: http://ultimatemember.com/
 * Description: The easiest way to create powerful online communities and beautiful user profiles with WordPress
 * Version: 2.7.0
 * Author: Ultimate Member
 * Author URI: http://ultimatemember.com/
 * Text Domain: ultimate-member
 * Domain Path: /languages
 * Requires at least: 5.5
 * Requires PHP: 5.6
 *
 * @package UM
 */

defined( 'ABSPATH' ) || exit;

/* Disable updates notification */
add_filter( 'site_transient_update_plugins', function( $value ) {
	unset( $value->response['ultimate-member/ultimate-member.php'] );
	return $value;
} );


$license = new stdClass();
$license->success = true;
$license->license = 'valid';
$license->expires = 'lifetime';
$licenses = [
	'um_bbpress_license_key',
	'um_followers_license_key',
	'um_friends_license_key',
	'um_groups_license_key',
	'um_instagram_license_key',
	'um_mailchimp_license_key',
	'um_messaging_license_key',
	'um_mycred_license_key',
	'um_notices_license_key',
	'um_notifications_license_key',
	'um_private_content_license_key',
	'um_profile_completeness_license_key',
	'um_reviews_license_key',
	'um_activity_license_key',
	'um_social_login_license_key',
	'um_unsplash_license_key',
	'um_user_bookmarks_license_key',
	'um_user_photos_license_key',
	'um_user_tags_license_key',
	'um_verified_license_key',
	'um_woocommerce_license_key',
	'um_profile_tabs_license_key',
	'um_user_notes_license_key',
	'um_user_locations_license_key',
];
foreach ( $licenses as $id ) {
	update_option( $id . '_edd_answer', $license );
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
$plugin_data = get_plugin_data( __FILE__ );

// phpcs:disable Generic.NamingConventions.UpperCaseConstantName
define( 'um_url', plugin_dir_url( __FILE__ ) );
define( 'um_path', plugin_dir_path( __FILE__ ) );
define( 'um_plugin', plugin_basename( __FILE__ ) );
define( 'ultimatemember_version', $plugin_data['Version'] );
define( 'ultimatemember_plugin_name', $plugin_data['Name'] );
// phpcs:enable Generic.NamingConventions.UpperCaseConstantName

define( 'UM_URL', plugin_dir_url( __FILE__ ) );
define( 'UM_PATH', plugin_dir_path( __FILE__ ) );
define( 'UM_PLUGIN', plugin_basename( __FILE__ ) );
define( 'UM_VERSION', $plugin_data['Version'] );
define( 'UM_PLUGIN_NAME', $plugin_data['Name'] );

require_once 'includes/class-functions.php';
require_once 'includes/class-init.php';
