<?php
/**
* Plugin Name: Huat Facebook Share Discount
* Plugin URI: http://on9commerce.com
* Description: This plugin allows customer to get discount when they share your website via Facebook on your WooCommerce store.
* Version: 1.2.0
* Author: AT & RL
* Author URI: http://on9commerce.com
* License: GPL2
*/

if(!defined('ABSPATH')){
  exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active( 'woocommerce/woocommerce.php')) {  
$huat_wc_fb_share_discount_options = get_option('huat_wc_fb_share_discount_settings', ' ');

require_once(plugin_dir_path(__FILE__).'/includes/huat-wc-fb-share-discount-scripts.php');
if(is_admin()){
  require_once(plugin_dir_path(__FILE__).'/includes/huat-wc-fb-share-discount-settings.php');
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'huat_wc_fb_share_discount_plugin_add_action_links' );
function huat_wc_fb_share_discount_plugin_add_action_links( $links ) {
	$links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=huat-wc-fb-share-discount-options') ) .'">Settings</a>';
	return $links;
}

add_action ('wp_head', 'huat_wc_fb_share_discount', 10);
function huat_wc_fb_share_discount(){

  global $huat_wc_fb_share_discount_options ;
  if (isset($huat_wc_fb_share_discount_options['fb_share_discount_app_id']))
  {
    ?>
    <?php
  }
}
} else {
	exit('This plugin requires WooCommerce plugin to be active.');
}