<?php
/*
Plugin Name: Facebook Like Lock for Wordpress
Plugin URI: http://wordpress.org/plugins/facebook-like-lock/
Description: Facebook Like Lock for Wordpress. Locks your content and user can unlock by clicking on like button.
Version: 1.0
Author: Zohaib Hassan Shaikh
Author URI: http://pph.me/zhs
*/
require("settings.php");
class sociallock_main {
	public static $dir, $url;
	public $DLINK;
	private $options,$social_secret;
	
	function __construct() {
	$this->init();
	}
	
	private function init(){
	 	if (is_admin()) {
			add_action('wp_ajax_wplikelocker', array(&$this, "wplikelocker_callback"));
			add_action('wp_ajax_nopriv_wplikelocker', array(&$this, "wplikelocker_callback"));
			add_action( 'admin_head', array(&$this,'sl_add_tinymce') );
		
		} else {
		   
			add_shortcode('wp-like-lock', array(&$this, "fblock_handler"));
		}	
		 $this->options = get_option( 'sl_option_name' );
		 $this->social_secret =substr(md5(NONCE_SALT), 0, 11);
	}
	
	
	function sl_add_tinymce() {
    global $typenow;

    add_filter('mce_external_plugins', array(&$this, "generate_mce_plugin"));
	add_filter('mce_buttons', array(&$this, "render_sl_mce_button"), 0);
	$icn = plugins_url( '/img/wplike_lock.png', __FILE__ );
	?>
	<style>
	#content_wplikelockplugin{
	 background-image: url('<?php //echo $icn; ?>') !important;
     background-repeat: no-repeat;
	}
	</style>
	<?php
}
	
function generate_mce_plugin($plugin_array){
	
		$plugin_array['wplikelockplugin'] = plugins_url( 'js/button.js', __FILE__ );
		return $plugin_array;
	}
	
function render_sl_mce_button($buttons) {
		array_push($buttons, "|", "wplikelockplugin");
		return $buttons;
	}	

	function print_sl_dialog(){
		require_once('button.tpl.html');
	}
	


	

	function wplikelocker_callback() {
	global $wpdb;
		$post_id = $_POST['post'];
		$post_url = get_permalink($post_id);
		switch ($_POST['network']) {
			case "facebook":
				break;
			default:
				break;
		}
		if(setcookie("phoenix_".$post_id, $this->social_secret, time()+3600*24*180, "/")){
		echo "success";
		die;
		}
	}	

	function inject() { 
	?>
	<script type="text/javascript">
			var wplikelocker_use = false;
			var sl_post_id = '<?php echo get_the_ID() ?>';
			var sl_ajax_url = '<?php echo admin_url('admin-ajax.php') ?>';
			<?php if(isset($this->options['use_sl_timer']) =='on') { ?>
			var use_timer = true;
			var timer_time = <?php echo isset( $this->options['timer_time'] ) ? esc_attr( $this->options['timer_time']) : 100 ?>;
			<?php } ?>
	</script>
	<div id="fb-root"></div>
	<?php

	
	
 	}	
	function fblock_handler($_atts, $content=null) {
					global	$post,$wpdb;
					
					if(!has_shortcode($post->post_content,"wp-like-lock")){
						return "";
					}
			if(!isset($_COOKIE["phoenix_".get_the_ID()]) ||
			$_COOKIE["phoenix_".get_the_ID()] != $this->social_secret)
			{
			$tweet = substr($post->post_content,0,20);
			
			$my_url = isset($_atts["url"]) ? $_atts["url"] :get_permalink(get_the_ID());
			$content = '<div id="wp_like_mwrap">
			<div id="wp_lock_msg" class="sl red" >
			<b>'.$this->options['sl_default_msg'].'</b>
			</div>';
			
			if(isset($this->options['use_sl_timer']) =='on'){
			$content .= '<div id="sl_countdown"></div>';
			}
			
			//if($this->options['use_sl_button'] =='on'){
			$content .= '<div id="wp_like_wrap">';
			
			//if($this->options['like_button'] =='on' ){
			
			if($this->options['fb_app_id'] =="")
			{
			$content .='<script src="//connect.facebook.net/en_US/all.js#xfbml=1"></script>';
			}
			else{
			
			$content .='<script src="//connect.facebook.net/en_US/all.js#xfbml=1&appId='.$this->options['fb_app_id'].'"></script>';
			}
			
			
			if($this->options['sl_post_or_fbpage']==0){
			
			$content .='<div class="sl_button sl_facebook" >
			<fb:like id="fbLikeButton" href="'.$this->options['fbpage_id'].'" layout="button_count" width="200"></fb:like></div>';
			}
			else{
			$content .='<div class="dd_button" style="float:left">
			<fb:like id="fbLikeButton" href="'.$my_url.'" layout="button_count" width="200"></fb:like></div>';
			}
			
			//}
			$content .= '</div></div><p style="font-size:10px; text-align:center">Powered by <a target="_blank" href="http://wordpress.org/plugins/facebook-like-lock">FB Like Lock</a></p>';
			//}
			}		
			
     add_action("wp_footer", array(&$this, "inject"),100);
		
	 if(isset($this->options['use_sl_timer']) =='on'){
		wp_register_script('timeTO', plugins_url( 'js/jquery.timeTo.min.js', __FILE__ ),false,NULL,true);
		wp_enqueue_script("timeTO");
	}
	
		wp_register_script('sociallock_js', plugins_url( 'js/main.js', __FILE__ ),false,NULL,true);
		wp_enqueue_script("sociallock_js");
	
	 wp_register_style( 'sociallock_css', plugins_url('css/main.css', __FILE__));
	 wp_enqueue_style( 'sociallock_css' );
		
		
		
	return $content;
	}
}

new sociallock_main();
?>