<?php if(!defined('ABSPATH'))exit;
global $wpdb;
$success = [];
$error = [];
$msgn = 0;
if(isset($_POST['sbmitredi'])){
  if(isset($_POST['sbmredi'])){$redir = 1;} else { $redir = 0;}
  $pth = $_POST['sbmpage'];
  $wpdb->query($wpdb->prepare("update ".$wpdb->prefix."twpLogin set h = %d, hpath = %s where id = 1",$redir, $pth));
  if($redir){
    array_push($success,[__("Redirect activated","twpeditor"),1]);
    array_push($success,[__("Are you sure that you have another login page or form!","twpeditor"),1]);
    array_push($success,[__("wp-admin and wp-login arent more available!","twpeditor"),1]);
  } else {
    array_push($success,[__("Redirect deactivated","twpeditor"),1]);
  }
}
if(isset($_POST['twpasubmit'])){
  $log = $_POST['logo'];
  $logo = explode(home_url(),$log);
  $bg = $_POST['bg'];
  $bgtextcolor = $_POST['bgtextcolor'];
  $formbg = $_POST['formbg'];
  $formtext = $_POST['formtext'];
  $btncolor = $_POST['btncolor'];
  $btntxtcolor = $_POST['btntxtcolor'];
  global $wpdb;
  $wpdb->query($wpdb->prepare("update ".$wpdb->prefix."twpLogin set logo = %s, bg = %s, bgtextcolor = %s, formbg = %s, formtext = %s, btncolor = %s, btntxtcolor = %s where id = 1",$logo[1],$bg,$bgtextcolor,$formbg,$formtext,$btncolor,$btntxtcolor));
  array_push($success,[__("Settings saved","twpeditor"),1]);
}
if(isset($_POST['dltdata'])){
  if(isset($_POST['datarmv'])){$wpdb->query($wpdb->prepare('update '.$wpdb->prefix.'twpLogin set d = %f',1));array_push($success,[__("At plugin deletion all twp login data will be removed","twpeditor"),1]);}
  else{$wpdb->query($wpdb->prepare('update '.$wpdb->prefix.'twpLogin set d = %f',0));array_push($success,[__("At plugin deletion all twp login data will be keeped","twpeditor"),1]);}
}
$result = $wpdb->get_results("select * from ".$wpdb->prefix."twpLogin where id = 1");
$logo = $result[0]->logo ? home_url().$result[0]->logo : ""; ?>
<div class="title">
  <h1><?php echo __("Admin login page","twpeditor") ?></h1> <?php
  foreach($success as $a){ ?>
    <div class="<?php if($a[1]){echo esc_attr('twpmessage');} ?> notice notice-success is-dismissible">
      <p><strong><?php echo esc_attr($a[0]) ?></strong></p><button type="button" class="notice-dismiss" onclick="twpmsgnone(<?php echo esc_attr($msgn) ?>)"></button>
    </div> <?php
    $msgn += 1;
  }
  foreach($error as $a){ ?>
    <div class="<?php if($a[1]){echo esc_attr('twpmessage');}?> notice notice-error is-dismissible">
      <p><strong><?php echo esc_attr($a[0]) ?></strong></p><button type="button" class="notice-dismiss" onclick="twpmsgnone(<?php echo esc_attr($msgn) ?>)"></button>
    </div> <?php
    $msgn += 1;
  } ?>
</div>
<form class="twpboxsetup" method="post" style="padding-bottom:20px;">
  <input type="checkbox" id="twpredit" name="sbmredi" <?php if($result[0]->h){ echo 'checked';} ?> onclick="twpredirect()">
  <label><?php echo __("Hide/Redirect wp-admin and wp-login.php","twpeditor") ?></label>
  <input type="submit" name="sbmitredi" class="twpbtnsave button-secondary" value="Save">
  <div id="twpredirect" <?php if(!$result[0]->h){echo 'style="display:none"';} ?>>
    <label><?php echo home_url() ?>/</label>
    <input type="text" name="sbmpage" value="<?php echo esc_attr($result[0]->hpath); ?>">
  </div>
</form>
<form class="twpboxsetup" id="twplogincss" method="post" <?php if($result[0]->h){echo 'style="display:none"';} ?>>
  <h2><?php echo __("login page css","twpeditor") ?></h2>
  <div class="twpmailsetup"> <?php
    wp_enqueue_script('jquery');
    wp_enqueue_media();  ?>
    <label class="twpallabel"><?php echo __("logo","twpeditor") ?></label>
    <input type="text" name="logo" id="twpimage_url" value="<?php echo esc_attr($logo) ?>" maxlength="160">
    <input type="button" name="upload-btn" id="twpupload_btn" class="button-secondary" value="<?php echo __("Image","twpeditor") ?>" style="margin-bottom:5px;">
    <script type="text/javascript">
      jQuery(document).ready(function($){
          $('#twpupload_btn').click(function(e) {
              e.preventDefault();
              var image = wp.media({
                  title: 'twp image upload',
                  multiple: false
              }).open()
              .on('select', function(e){
                  var uploaded_image = image.state().get('selection').first();
                  var image_url = uploaded_image.toJSON().url;
                  $('#twpimage_url').val(image_url);
              });
          });
      });
    </script>
  </div>
  <div class="twpmailsetup">
    <label class="twpalabel"><?php echo __("Background","twpeditor") ?></label>
    <input class="my-color-field" type="text" name="bg" value="<?php echo esc_attr($result[0]->bg) ?>" maxlength="10"/>
  </div>
  <div class="twpmailsetup">
    <label class="twpalabel"><?php echo __("background text color","twpeditor") ?></label>
    <input class="my-color-field" type="text" name="bgtextcolor" value="<?php echo esc_attr($result[0]->bgtextcolor) ?>" maxlength="10"/>
  </div>
  <div class="twpmailsetup">
    <label class="twpalabel"><?php echo __("Form background","twpeditor") ?></label>
    <input class="my-color-field" type="text" name="formbg" value="<?php echo esc_attr($result[0]->formbg) ?>" maxlength="10"/>
  </div>
  <div class="twpmailsetup">
    <label class="twpalabel"><?php echo __("Form text","twpeditor") ?></label>
    <input class="my-color-field" type="text" name="formtext" value="<?php echo esc_attr($result[0]->formtext) ?>" maxlength="10"/>
  </div>
  <div class="twpmailsetup">
    <label class="twpalabel"><?php echo __("button color","twpeditor") ?></label>
    <input class="my-color-field" type="text" name="btncolor" value="<?php echo esc_attr($result[0]->btncolor) ?>" maxlength="10"/>
  </div>
  <div class="twpmailsetup">
    <label class="twpalabel"><?php echo __("button text color","twpeditor") ?></label>
    <input class="my-color-field" type="text" name="btntxtcolor" value="<?php echo esc_attr($result[0]->btntxtcolor) ?>" maxlength="10"/>
  </div>
  <div class="twpmailsubmit">
    <input type="submit" class="button-secondary" name="twpasubmit" value="<?php echo __("Save","twpeditor") ?>">
  </div>
</form>
<form id="twpmailremovable" method="post">
  <input id="twpdltsmt" type="checkbox" name="datarmv" onchange="twpdltdata()" <?php if($result[0]->d){echo 'checked';}?>>
  <label><?php echo __("At plugin deletion remove ALL TWP login settings.","twpeditor") ?></label>
  <input id="twpdltdatasmt" type="submit" name="dltdata" style="display:none;">
</form>
<script type="text/javascript">
  var twpmsg = document.getElementsByClassName("twpmessage");
  if(twpmsg[0]){setTimeout(function(){for(var i = 0; i < twpmsg.length; i++){twpmsg[i].style.display = "none";}},8000);}
  function twpdltdata(){document.getElementById("twpdltdatasmt").click();}
  function twpredirect(){
    var check = document.getElementById('twpredit').checked;
    if(check){
      document.getElementById('twplogincss').style.display = "none";
      document.getElementById('twpredirect').style.display = "block";
    }
    else{
      document.getElementById('twplogincss').style.display = "block";
      document.getElementById('twpredirect').style.display = "none";
  }
  }
</script>
<style media="screen">
  #twpredirect input{width:170px;margin-top:20px;}
  .twpbtnsave{float:right;}
</style>
