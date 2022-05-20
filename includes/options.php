<?php
/*
 * File: options.php
 * Project: 88

 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
  exit;
}

add_action('admin_menu', 'create_qr_plugin_options');
function create_qr_plugin_options()
{
  //create new top-level menu
  add_menu_page('QR plugin options', 'QR plugin options', 'administrator', __FILE__, 'qr_plugin_settings_page');

  //call register settings function
  add_action('admin_init', 'register_my_qr_plugin_settings');
}

function register_my_qr_plugin_settings()
{
  register_setting('qr-tmpl-settings-group', 'qr_width');
  register_setting('qr-tmpl-settings-group', 'qr_height');

  register_setting('qr-tmpl-settings-group', 'logo_width');
  register_setting('qr-tmpl-settings-group', 'logo_height');
  register_setting('qr-tmpl-settings-group', 'logo_bg');
  register_setting('qr-tmpl-settings-group', 'qr_post_types');
  // register_setting('qr-tmpl-settings-group', 'logo_bg_transparent');


  if (!empty($_FILES['qr_logo'])) {
    if (!function_exists('wp_handle_upload'))
      require_once(ABSPATH . 'wp-admin/includes/file.php');

    $file = &$_FILES['qr_logo'];
    $overrides = ['test_form' => false];

    // For uploading SVG. Add to wp-config.php
    // define('ALLOW_UNFILTERED_UPLOADS', true);
    $movefile = wp_handle_upload($file, $overrides);
    if (!empty($movefile['url']) && empty($movefile['error'])) {
      $url = str_replace(get_site_url(), '', $movefile['url']);
      $url = '/' . ltrim($url, '/');

      if (!empty(get_option('qr_logo'))) {
        $cur_url = rtrim(ABSPATH, '/') . '/' . ltrim(get_option('qr_logo'), '/');
        unlink($cur_url);
      }

      update_option('qr_logo', $url);
    }
  }

  function qr_plugin_settings_page()
{ ?>
  <style type="text/css">
    .pvr-settings-group {
      margin: 0 0 20px;
      padding: 0 30% 0 0;
    }
    .bg_grey{
      background-color: #f6f6f6;
    }

    .pvr-settings-group input,
    .pvr-settings-group textarea {
      width: 100%;
      box-sizing: border-box;
    }

    @media (max-width: 1439.98px) {
      .pvr-settings-group {
        padding: 0 20% 0 0;
      }
    }

    @media (max-width: 1199.98px) {
      .pvr-settings-group {
        padding: 0 10% 0 0;
      }
    }

    @media (max-width: 991.98px) {
      .pvr-settings-group {
        padding: 0;
      }
    }
  </style>

  <div class="wrap">
    <h2>QR plugin options</h2>

    <form method="post" action="options.php" enctype="multipart/form-data">
      <?php settings_fields('qr-tmpl-settings-group'); ?>
      <?php do_settings_sections('qr-tmpl-settings-group'); ?>

      <div class="pvr-settings-group">
        <div class="postbox">
          <div class="inside">
            <strong>QR Code Setting</strong>
          </div>

          <div class="inside">
            <div>
              <label for="qr_width"><strong>QR width:</strong></label>
            </div>

            <div>
              <input id="qr_width" type="text" name="qr_width" maxlength="128" value="<?php echo get_option('qr_width'); ?>" />
            </div>
          </div>


          <div class="inside">
            <div>
              <label for="qr_height"><strong>QR height:</strong></label>
            </div>

            <div>
              <input id="qr_height" type="text" name="qr_height" maxlength="128" value="<?php echo get_option('qr_height'); ?>" />
            </div>
          </div>



        </div>
      </div>



      <div class="pvr-settings-group">
        <div class="postbox bg_grey">
          <div class="inside">
            <strong>Logo Setting</strong>
          </div>

          <div class="inside">
            <div>
              <strong>Logo:</strong>
            </div>

            <div>
              <input name="qr_logo" type="file" /><br />
              <?php if (!empty(get_option('qr_logo'))) { ?>
                <img src="<?php echo get_site_url() . get_option('qr_logo'); ?>" style="height: 80px" /><br />
                <button id="remove_qr_logo">Remove image</button>
                <script>
                  jQuery(function($) {
                    $('#remove_qr_logo').on('click', function() {
                      $.ajax({
                        type: 'POST',
                        url: '<?php echo get_admin_url('admin-ajax.php') . 'admin-ajax.php' ?>',
                        data: {
                          action: 'remove_qr_logo',
                          nonce_code: '<?php echo wp_create_nonce('pvr_nonce') ?>',
                          qr_logo: '<?php echo get_option('qr_logo'); ?>'
                        },
                        dataType: 'json',
                        success: function() {
                          document.location.reload(true);
                        }
                      });

                      return false;
                    });
                  });
                </script>
              <?php } ?>
            </div>
          </div>



          <div class="inside">
            <div>
              <label for="logo_width"><strong>Logo width:</strong></label>
            </div>

            <div>
              <input id="logo_width" type="text" name="logo_width" maxlength="128" value="<?php echo get_option('logo_width'); ?>" />
            </div>
          </div>

          <div class="inside">
            <div>
              <label for="logo_height"><strong>Logo height:</strong></label>
            </div>

            <div>
              <input id="logo_height" type="text" name="logo_height" maxlength="128" value="<?php echo get_option('logo_height'); ?>" />
            </div>
          </div>


          <div class="inside">
            <div>
              <label for="logo_bg"><strong>Logo bg:</strong></label>
            </div>

            <div>
              <input id="logo_bg" type="text" name="logo_bg" maxlength="128" value="<?php echo get_option('logo_bg'); ?>" />
            </div>
          </div>

          <!-- <div class="inside">
            <div>
              <label for="logo_height"><strong>Logo height:</strong></label>
            </div>

            <div>
              <input id="logo_height" type="text" name="logo_height" maxlength="128" value="<?php echo get_option('logo_height'); ?>" />
            </div>
          </div> -->


          </div>
        </div>

        <div class="pvr-settings-group">
            <div class="postbox bg_grey">
              <div class="inside">
                <strong>Post Types Setting</strong>
              </div>
              <div class="inside">
          <?php
            $args = array(
               'public'   => true,
            );
              
            $output = 'names'; // 'names' or 'objects' (default: 'names')
            $operator = 'and'; // 'and' or 'or' (default: 'and')
           
            $post_types = get_post_types( $args, $output, $operator );
            $exclude = array( 'attachment' , 'elementor_library' );
            if ( $post_types ) {?> 
              
              <p>Please select the types of posts in which the QR code will be displayed :</p>
                    <div>

                <?php $i = 0 ; foreach ( $post_types  as $post_type ) {
                      if( !in_array( $post_type , $exclude )) {
                        ?>
                   
                      <input type="checkbox" id="contactChoice<?php echo $i;?>"
                       name="qr_post_types[]" value="<?php echo $post_type ;?>" <?php if(is_array(get_option('qr_post_types'))){if(in_array($post_type , get_option('qr_post_types'))) echo 'checked';}?> <?php if(get_option('qr_post_types') == $post_type) echo 'checked';?>>
                      <label for="contactChoice<?php echo $i;?>"><?php echo $post_type ;?></label>
                      <?php $i++;?>
                <?php };};?>
              
                  </div>
              
            <?php } ?>
                </div>
               </div>
            </div>
       <?php submit_button(); ?>
    </form>
  </div>

<?php };


}

add_action('wp_ajax_remove_qr_logo', 'remove_qr_logo_callback');
function remove_qr_logo_callback()
{
  check_ajax_referer('pvr_nonce', 'nonce_code');

  if (empty($_POST['qr_logo']) || !is_string($_POST['qr_logo'])) {
    header400();
    header_json();
    echo json_encode(['code' => 'wrong_removing', 'message' => 'Wrong post']);
    die();
  }

  $filepath = rtrim(ABSPATH, '/') . '/' . ltrim($_POST['qr_logo'], '/');
  if (is_file($filepath)) {
    unlink($filepath);
  }

  unlink($filepath);
  delete_option('qr_logo');

  header200();
  header_json();
  echo json_encode(['status' => 'success']);
  die();
}