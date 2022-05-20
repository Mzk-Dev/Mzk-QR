<?php
if (!defined('ABSPATH')) {
  exit;
}

$post_types_qr = get_option('qr_post_types');

if(isset($post_types_qr) && $post_types_qr != ''){
  add_action('admin_menu', function () {
    add_meta_box('qr_options', 'QR code options', 'qr_options', get_option('qr_post_types'), 'normal', 'high');
  });


function qr_options($post){
  if($post->post_type == 'pdf'):
  wp_nonce_field(basename(__FILE__), 'options_metabox_nonce'); 
  $qr_count = get_post_meta($post->ID , 'qr_count' , true);?>

  <div class="inside">
    <div>
      <label for="qr_count"><strong>QR Downloads: </strong></label><input id="qr_count" type="text" value="<?php if($qr_count > 0){ echo $qr_count;}else{echo '0';}?>" name="qr_count"/>
    </div>
  </div>
  
  <?php endif;?>
  

  <p>QR code :</p>
  <div id="qrcode" ></div>
  <a href="#" id="qr_download">Download QR</a>

  <script type="text/javascript">
    var width = <?php if((get_option('qr_width') != '')){ echo get_option('qr_width');}else{ echo '150';}?>;
    var height = <?php if((get_option('qr_height') != '')){ echo get_option('qr_height');}else{ echo '150';}?>;
    var logo = '<?php if((get_option('qr_logo') != '')){ echo get_site_url() . get_option('qr_logo');}else{ echo '';}?>';
    var logo_width = <?php if((get_option('logo_width') != '')){ echo get_option('logo_width');}else{ echo '50';}?>;
    var logo_height = <?php if((get_option('logo_height') != '')){ echo get_option('logo_height');}else{ echo '25';}?>;
    var logo_bg = '<?php if((get_option('logo_bg') != '')){ echo get_option('logo_bg');}else{ echo 'white';}?>';
    // width = (width != '') ? width : 100;

    let qrcode = new QRCode(document.getElementById("qrcode"),
     {
      text: '<?php echo get_post_permalink($post->ID);?>',
      width: width,
      height: height,
      logo: logo ,
      logoWidth: logo_width ,
      logoHeight: logo_height ,
      logoBackgroundColor: logo_bg ,
      useCORS: true
    }); 
    var qrlink = document.getElementById("qr_download");
    var dataUrl = document.querySelector('#qr_download');
    dataUrl.addEventListener('click' , function(e){
      e.preventDefault();
      html2canvas(document.querySelector('#qrcode canvas'))
      .then((canvas) => {
      var link = document.createElement("a");
      link.download = 'qrcode.png';
      link.href = canvas.toDataURL("image/jpg");
      link.click();
      })
    });
 
  </script>
  
 


<?php }

  add_action('save_post', function ($post_id) {
  if (
    !isset($_POST['options_metabox_nonce'])
    || !wp_verify_nonce($_POST['options_metabox_nonce'], basename(__FILE__))
  ) {
    return $post_id;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if($post->post_type == 'pdf'){
    $qr_count= $_POST['qr_count'] ?? '0';
    update_post_meta($post_id, 'qr_count', ($qr_count));
  }
  });
}