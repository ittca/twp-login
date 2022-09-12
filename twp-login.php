<?php /*
  Plugin Name: twp login
  Plugin URI:
  Description: simple editor for wordpress admin page
  Author: Tiago Anastácio
  Author URI: ittca.eu
  Version: 1.0.1
*/
if(!defined('ABSPATH'))exit;
define('TWPloginV',"1.0.1");
if(!function_exists('twpLoginFiles')){
  function twpLoginFiles() {wp_enqueue_style('twpl_style', plugins_url('style.css',__FILE__ ));}
  add_action( 'admin_init','twpLoginFiles');
}
if(!function_exists('twpl_js')){
  function twpl_js() {
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_script( 'my-script-handle', plugins_url('adm/twp.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
  }
  add_action( 'admin_enqueue_scripts', 'twpl_js' );
}
if(!function_exists('twped_csm')){
  function twped_csm($slug){global $submenu;if($submenu['twp_editor']){foreach($submenu['twp_editor'] as $subm){if($subm[2]==$slug)return 1;}}}
}
if (!function_exists('twplogin')){
  function twplogin(){
    if(empty($GLOBALS['admin_page_hooks']['twp_editor'])){
      add_menu_page('twp editor','twp','manage_options','twp_editor','twpMain','dashicons-welcome-widgets-menus',99);
    }
    if(empty(twped_csm('twp_about'))){add_submenu_page('twp_editor', 'twp about', __('About'), 'manage_options','twp_about','twpl_About');}
    if(empty(twped_csm('twp_login'))){add_submenu_page('twp_editor', 'twp login', __('Login'), 'manage_options','twp_login','twpl_Login');}
    remove_submenu_page('twp_editor', 'twp_editor');
  }
  function twpl_About(){require_once plugin_dir_path(__FILE__).'adm/about.php';}
  function twpl_Login(){require_once plugin_dir_path(__FILE__).'adm/login.php';}
  add_action('admin_menu', 'twplogin');
}
if(! function_exists('twpl_settings')){
  function twpl_settings( $links ) {
  	$links[] = '<a href="'.admin_url('admin.php?page=twp_about').'">'.__('Settings').'</a>';
  	return $links;
  }
  add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'twpl_settings');
}
if(!function_exists('twpl_install')){
  function twpl_install(){
    global $wpdb;
    if(!$wpdb->get_results("select * from ".$wpdb->prefix."twpLogin where id = 1")){
      $wpdb->query("create table if not exists ".$wpdb->prefix."twpLogin(id int, logo varchar(150), bg varchar(10), formbg varchar(10), formtext varchar(10),  btncolor varchar(10), bgtextcolor varchar(10), btntxtcolor varchar(10), d int, h int, hpath varchar(50))");
      $wpdb->query("insert into ".$wpdb->prefix."twpLogin values(1,'','','','','','','',0,0,'')");
    }
  }
  register_activation_hook(__FILE__, 'twpl_install');
}
if(!function_exists('twpl_uninstall')){
  function twpl_uninstall(){
    global $wpdb;
    $r=$wpdb->get_results('select d from '.$wpdb->prefix.'twpLogin where id=1');
    if($r[0]->d){$wpdb->query('drop table '.$wpdb->prefix.'twpLogin');}
  }
  register_uninstall_hook( __FILE__, 'twpl_uninstall' );
}
if(!function_exists('twpl_wplogin')){
  function twpl_wplogin() {
    global $wpdb;
    $data = $wpdb->get_results("select * from ".$wpdb->prefix."twpLogin where id = 1");
    $logo = $data[0]->logo ? home_url().$data[0]->logo : ""; ?>
    <style type="text/css"> <?php
      if($data[0]->logo){ ?>
        body.login div#login h1 a{
          background-image: url(<?php echo $logo; ?>);
        } <?php
      }
      if($data[0]->bg){ ?> body{background-color:<?php echo $data[0]->bg?> !important;} <?php } ?>
      .login #login_error, .login .message, .login .success, #loginform, #lostpasswordform{ <?php
        if($data[0]->formbg){  ?> background: <?php echo $data[0]->formbg ?> !important; <?php }
        if($data[0]->formtext){ ?> color:<?php echo $data[0]->formtext ?> !important; <?php } ?>
      }
      #loginform, .login #login_error, .login .message, .login .success, #lostpasswordform{border-radius:8px;}
      .login #backtoblog a, .login #nav a, .language-switcher label .dashicons{color:<?php echo $data[0]->bgtextcolor ?> !important;}
      .wp-core-ui .button.button-large{border-radius:5px;background:<?php echo $data[0]->btncolor ?>;color:<?php echo $data[0]->btntxtcolor ?>;border: none!important;}
      .wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus{background:<?php echo $data[0]->btncolor ?>; color:<?php echo $data[0]->btntxtcolor ?>;opacity:85%;border-color:<?php echo $data[0]->btntxtcolor ?>;} <?php
      if($data[0]->btncolor){ ?>
        .login .button.wp-hide-pw .dashicons{color:lightgrey;}
        #language-switcher input{
          color:<?php echo $data[0]->btncolor ?> !important;
          border-color:<?php echo $data[0]->btncolor ?> !important;
        }
        #language-switcher input:focus{box-shadow: none!important;}
        input[type="password"]:focus, input[type="text"]:focus, #rememberme:focus,.wp-core-ui .button.button-large:focus{
          outline: none!important;
          border: 1px solid <?php echo $data[0]->btncolor ?> !important;
          box-shadow: none!important;
        }
        ::selection{background:<?php echo $data[0]->btncolor ?>;}
        .wp-core-ui select:hover{
          color:<?php echo $data[0]->btncolor ?> !important;
        }
        .wp-core-ui select:focus{
          border:1px solid <?php echo $data[0]->btncolor ?> !important;
          box-shadow: none!important;
        }
        .login #backtoblog a:focus, .login #nav a:focus{
          border-bottom: 1px solid <?php echo $data[0]->btncolor ?> ;
          box-shadow: none!important;
        } <?php
      } ?>
      .login .button.wp-hide-pw:focus{
         outline: none!important;
         border: none!important;
         box-shadow: none!important;
      }
    </style><?php
  }
  add_action('login_enqueue_scripts','twpl_wplogin');
}
if(!function_exists('twpl_redirect')){
  function twpl_redirect(){
    global $wpdb;
    $data = $wpdb->get_results("select * from ".$wpdb->prefix."twpLogin where id = 1");
    if($data[0]->h){
      global $pagenow;
      if('wp-login.php'==$pagenow){wp_redirect(home_url($data[0]->hpath));exit();}
    }
  }
  add_action('init','twpl_redirect');
}
if(!function_exists('twpl_about_title')){
  function twpl_about_title(){echo __("Login").'&emsp;'; }
  add_action('twp_about_title', 'twpl_about_title');
}
if(!function_exists('twpl_about_body')){
  function twpl_about_body(){ require 'adm/abouttwp.php';}
  add_action('twp_about_body', 'twpl_about_body');
}
