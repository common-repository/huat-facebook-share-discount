<?php

function huat_wc_fb_share_discount_my_init() {
	if (!is_admin()) {
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'huat_wc_fb_share_discount_my_init');

 function huat_wc_fb_share_discount_add_scripts(){
 	wp_enqueue_script('huat-wc-fb-share-discount-main-script', plugins_url( 'js/main.js' , dirname(__FILE__) ));

	global $huat_wc_fb_share_discount_options ;
	if (isset($huat_wc_fb_share_discount_options['fb_share_discount_app_id']))
	{
		$params = array(
			'fb_app_id' => $huat_wc_fb_share_discount_options['fb_share_discount_app_id'],
			'fb_api_version' => $huat_wc_fb_share_discount_options['fb_share_discount_api_version'],
			'fb_share_link' => get_home_url(),
		);

		wp_localize_script( 'huat-wc-fb-share-discount-main-script', 'MyScriptParams', $params);

		wp_localize_script( 'huat-wc-fb-share-discount-main-script', 'ajax_object',
		array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );

	}
}

add_action('wp_enqueue_scripts', 'huat_wc_fb_share_discount_add_scripts');

function huat_wc_fb_share_discount_action_callback() {
	global $woocommerce;
	global $huat_wc_fb_share_discount_options ;

	$amount = $huat_wc_fb_share_discount_options['fb_share_discount_coupon_amount'];
	$discount_type = 'percent';

	if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
		$my_coupon = $woocommerce->cart->get_coupons() ;
		foreach($my_coupon as $coupon){

			if ($post = get_post( $coupon->id )  ) {
				if ($post->coupon_amount == $amount && $post->discount_type == $discount_type && strlen($coupon->code) == 20 && substr($coupon->code, 0, 5) == "fbsd-") {
					return;
				}
			}
		}
	}

	$coupon_code = strtolower(uniqid('fbsd-').mt_rand(10,99));
	$coupon = array(
		'post_title' => $coupon_code,
		'post_content' => '',
		'post_excerpt' => 'FB Share Discount',
		'post_status' => 'publish',
		'post_author' => 1,
		'post_type'		=> 'shop_coupon'
	);

	$new_coupon_id = wp_insert_post( $coupon );

	update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
	update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
	update_post_meta( $new_coupon_id, 'individual_use', 'no' );
	update_post_meta( $new_coupon_id, 'product_ids', '' );
	update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
	update_post_meta( $new_coupon_id, 'usage_limit', '1' );
	update_post_meta( $new_coupon_id, 'expiry_date', '' );
	update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
	update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

	if ( $woocommerce->cart->has_discount( $coupon_code ) ) return;

	$woocommerce->cart->add_discount( $coupon_code );
	wp_die();
}

if ( is_admin() ) {
	add_action( 'wp_ajax_huat_wc_fb_share_discount_action', 'huat_wc_fb_share_discount_action_callback' ); //for administration side
	add_action( 'wp_ajax_nopriv_huat_wc_fb_share_discount_action', 'huat_wc_fb_share_discount_action_callback' ); //for viewer side

} else {
}

add_filter( 'woocommerce_coupon_is_valid', 'huat_wc_fb_share_discount_woocommerce_coupon_is_valid', 1, 2 );

function huat_wc_fb_share_discount_woocommerce_coupon_is_valid( $valid, $coupon ) {
	global $message;
	global $woocommerce;
	global $huat_wc_fb_share_discount_options ;
	$count = 0;

	$amount = $huat_wc_fb_share_discount_options['fb_share_discount_coupon_amount'];
	$discount_type = 'percent';

	if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
		$my_coupon = $woocommerce->cart->get_coupons() ;
		foreach($my_coupon as $coupon){

			if ($post = get_post( $coupon->id )  ) {
				if ($post->coupon_amount == $amount && $post->discount_type == $discount_type && strlen($coupon->code) == 20 && substr($coupon->code, 0, 5) == "fbsd-") {
					$valid = false;
					$count += 1;
				}
			}
		}
	} else {
		$valid = true;
	}

	if ($count > 1) {
		$valid = false;
	} else {
		$valid = true;
	}
	return $valid;
}

add_action ('woocommerce_cart_contents', 'huat_wc_fb_share_discount_cart_content_html');
function huat_wc_fb_share_discount_cart_content_html()
{
	global $woocommerce;
	global $huat_wc_fb_share_discount_options ;

	$count = 0;
	$amount = $huat_wc_fb_share_discount_options['fb_share_discount_coupon_amount'];
	$discount_type = 'percent';

	if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
		$my_coupon = $woocommerce->cart->get_coupons() ;
		foreach($my_coupon as $coupon){

			if ($post = get_post( $coupon->id )  ) {
				if ($post->coupon_amount == $amount && $post->discount_type == $discount_type && strlen($coupon->code) == 20 && substr($coupon->code, 0, 5) == "fbsd-") {
					$valid = false;
					$count += 1;
				}
			}
		}
	} else {
		$valid = true;
	}

	if ($count > 1) {
		$valid = false;
	} else {
		$valid = true;
	}


	if ($woocommerce->cart->cart_contents_total > 0 && $count < 1)
	{
$message = <<<HTML
<div style="border:0.1px solid grey; text-align:center; padding:10px;">
<h2>Facebook Share Discount</h2>
<button id="fbsdbutton" type="button">Get $amount% Discount by Sharing via Facebook</button>
</div>
HTML;

	echo $message."<br />";

	}
}