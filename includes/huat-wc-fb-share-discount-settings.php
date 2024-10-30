<?php

function huat_wc_fb_share_discount_options_menu_link(){
  add_options_page(
  'Facebook Share Discount Options',
  'Facebook Share Discount',
  'manage_options',
  'huat-wc-fb-share-discount-options',
  'huat_wc_fb_share_discount_options_content'
);
}

function huat_wc_fb_share_discount_options_content(){
  global $huat_wc_fb_share_discount_options ;

  ob_start(); ?>
  <div class="wrap">
    <h2><?php _e('Facebook Share Discount Settings', 'huat_wc_fb_share_discount_domain'); ?></h2>
    <p><?php _e('Settings for the Facebook Share Discount plugin', 'huat_wc_fb_share_discount_domain'); ?></p>
    <form method="post" action="options.php">
      <?php settings_fields('huat_wc_fb_share_discount_settings_group'); ?>
      <table class="form-table">
        <tbody>

          <tr>
            <th scope="row"><label for="huat_wc_fb_share_discount_settings[fb_share_discount_app_id]"><?php _e('Facebook App ID','huat_wc_fb_share_discount_domain'); ?></label></th>
            <td><input name="huat_wc_fb_share_discount_settings[fb_share_discount_app_id]" type="text" id="huat_wc_fb_share_discount_settings[fb_share_discount_app_id]" value="<?php if (isset($huat_wc_fb_share_discount_options ['fb_share_discount_app_id'])) { echo sanitize_text_field($huat_wc_fb_share_discount_options ['fb_share_discount_app_id']); } ?>" class="regular-text">
              <p class="description"><?php _e('Enter your Facebook App ID', 'huat_wc_fb_share_discount_domain'); ?></p></td>
            </tr>

            <tr>
              <th scope="row"><label for="huat_wc_fb_share_discount_settings[fb_share_discount_api_version]"><?php _e('Facebook API Version','huat_wc_fb_share_discount_domain'); ?></label></th>
              <td><input name="huat_wc_fb_share_discount_settings[fb_share_discount_api_version]" type="text" id="huat_wc_fb_share_discount_settings[fb_share_discount_api_version]" value="<?php if (isset($huat_wc_fb_share_discount_options ['fb_share_discount_api_version'])) { echo sanitize_text_field($huat_wc_fb_share_discount_options ['fb_share_discount_api_version']); } ?>" class="regular-text">
                <p class="description"><?php _e('Enter your Facebook API Version, e.g. v2.8', 'huat_wc_fb_share_discount_domain'); ?></p></td>
              </tr>

              <tr>
                <th scope="row">Share Link</th>
                <td><input type="text" value="<?php echo get_home_url(); ?>" class="regular-text" disabled>
                  <p class="description">Pro version allows you to customize the link for customer to share</p>
                </td>
                  </tr>

                  <tr>
                    <th scope="row">Cart Promo Message</th>
                    <td><input type="text" value="<?php echo 'Get '; if (isset($huat_wc_fb_share_discount_options ['fb_share_discount_coupon_amount'])) { echo sanitize_text_field($huat_wc_fb_share_discount_options ['fb_share_discount_coupon_amount']); } echo '% Discount by Sharing via Facebook'; ?>" class="regular-text" disabled>
                      <p class="description">Pro version allows you to customize the message to be displayed in the cart promo message button</p>
                    </td>
                    </tr>

                    <tr>
                      <th scope="row">Share Link Caption</th>
                      <td><input type="text" value="" class="regular-text" disabled>
                        <p class="description">Pro version allows you to add caption to the Facebook share link (appears beneath the link name)</p></td>
                      </tr>

                      <tr>
                        <th scope="row"><label for="huat_wc_fb_share_discount_settings[fb_share_discount_coupon_amount]"><?php _e('Coupon Amount','huat_wc_fb_share_discount_domain'); ?></label></th>
                        <td><input name="huat_wc_fb_share_discount_settings[fb_share_discount_coupon_amount]" type="number" id="huat_wc_fb_share_discount_settings[fb_share_discount_coupon_amount]" value="<?php if (isset($huat_wc_fb_share_discount_options ['fb_share_discount_coupon_amount'])) { echo sanitize_text_field($huat_wc_fb_share_discount_options ['fb_share_discount_coupon_amount']); } ?>" class="regular-text">
                          <p class="description"><?php _e('Enter value of the Coupon', 'huat_wc_fb_share_discount_domain'); ?></p></td>
                        </tr>

                        <tr>

                          <th scope="row">Coupon Type</th>
                          <td>
                            <input type="radio" disabled>Cart Discount
                            <input type="radio" value="percent" checked>Cart % Discount<br />
                            <p class="description">Pro version allows you to choose more than 1 Coupon Discount Type</p></td>
                          </tr>

                          <tr>
                            <th scope="row">Allow Free Shipping</th>
                            <td>
                              <input type="checkbox" disabled>
                              <p class="description">Pro version allows you to choose Free Shipping</p></td>
                            </td>
                            </tr>  

                        </tbody>
                      </table>
                      <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'huat_huat_wc_fb_share_discount_domain'); ?>"</p>
                    </form>
                  </div>
                  <?php
                  echo ob_get_clean();
                }

                add_action('admin_menu', 'huat_wc_fb_share_discount_options_menu_link');

                function huat_wc_fb_share_discount_register_settings(){
                  register_setting('huat_wc_fb_share_discount_settings_group', 'huat_wc_fb_share_discount_settings', 'huat_wc_fb_share_discount_validate_input');
                }

                add_action('admin_init', 'huat_wc_fb_share_discount_register_settings');

                function huat_wc_fb_share_discount_validate_input( $input ) {
                     $output = array();
                      
                     foreach( $input as $key => $value ) {                          
                         if( isset( $input[$key] ) ) {                          
                             $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );                     
                         }        
                     }                      
                     return apply_filters( 'huat_wc_fb_share_discount_validate_input', $output, $input );                  
                } 