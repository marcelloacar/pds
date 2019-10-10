<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include_once( 'facebook.php' );
/**
 * WooSocio Base Class
 *
 * All functionality pertaining to core functionality of the WooSocio plugin.
 *
 * @package WordPress
 * @subpackage WooSocio
 * @author qsheeraz
 * @since 0.0.1
 *
 * TABLE OF CONTENTS
 *
 * public $version
 * private $file
 *
 * private $token
 * private $prefix
 *
 * private $plugin_url
 * private $assets_url
 * private $plugin_path
 *
 * public $facebook
 * private $fb_user_profile
 * private $app_id
 * private $secret
 *
 * - __construct()
 * - init()
 * - woosocio_meta_box()
 * - woosocio_ajax_action()
 * - woosocio_admin_init()
 * - socialize_post()
 * - woosocio_admin_menu()
 * - woosocio_admin_styles()
 * - socio_settings()
 * - products_list()
 * - check_connection()
 * - save_app_info()
 * - update_page_info()
 *
 * - load_localisation()
 * - activation()
 * - register_plugin_version()
 */

class Woo_Socio {
	public $version;
	private $file;

	private $token;
	private $prefix;

	private $plugin_url;
	private $assets_url;
	private $plugin_path;
	
	public $facebook;
	public $fb_user_profile = array();
	public $fb_user_pages = array();
	
	private $fb_app_id;
	private $fb_secret;

    /**
     * @const string Default GS APP ID.
     */
	const GS_APP = '1735631509883906';

    /**
     * @const string Default GS APP Secret.
     */
	const GS_SECRET = 'GS_SECRET';

    /**
     * @const string Default Graph API version for requests.
     */
    const DEFAULT_GRAPH_VERSION = 'v3.1';

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct ( $file ) {
		$this->version = '';
		$this->file = $file;
		$this->prefix = 'woo_socio_';

		/* Plugin URL/path settings. */
		$this->plugin_url = str_replace( '/classes', '', plugins_url( plugin_basename( dirname( __FILE__ ) ) ) );
		$this->plugin_path = str_replace( 'classes', '', plugin_dir_path( __FILE__ ));
		$this->assets_url = $this->plugin_url . '/assets';
		
	} // End __construct()

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init () {
		add_action( 'init', array( $this, 'load_localisation' ) );

		add_action( 'admin_init', array( $this, 'woosocio_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'woosocio_admin_menu' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'woosocio_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_woosocio_meta_boxes' ), 1, 2 );
		add_action( 'save_post', array( $this, 'socialize_post' ));
		add_action( 'wp_ajax_my_action', array( $this, 'woosocio_ajax_action' ));
		add_action( 'wp_ajax_save_app_info', array( $this, 'save_app_info' ));
		add_action( 'wp_ajax_update_page_info', array( $this, 'update_page_info' ));
		add_action( 'wp_ajax_save_shared_connection', array( $this, 'save_shared_connection' ));
		add_action( 'wp_ajax_ws_delete_connection', array( $this, 'ws_delete_connection' ));
		add_action( 'woocommerce_single_product_summary', array( $this, 'show_sharing_buttons'), 50, 2  );
		#add_filter( 'the_content', array( $this, 'ws_show_social_buttons') );
		add_filter( 'manage_edit-product_columns', array($this, 'woosocio_columns'), 998);
		add_action( 'manage_product_posts_custom_column', array($this, 'woosocio_custom_product_columns') );
		add_action( 'admin_footer', array($this, 'jquery_change_url') );
		add_action( 'restrict_manage_posts', array($this, 'add_list_woosocio') );
		add_action( 'widgets_init', array( $this, 'register_ws_widget' ) );
		add_action( 'ws_schedule_action', array($this, 'ws_fb_post' ), 1, 2);
		

		// Run this on activation.
		register_activation_hook( $this->file, array( $this, 'activation' ) );
	} // End init()
	
	function pa($arr){

		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}


	/**
	 * register widget
	 *
	 * @return void
	 * @author 
	 **/
	function register_ws_widget() {
		register_widget( 'WS_Widget' );
	}

	/**
	 * register WooSocio meta box
	 *
	 * @return void
	 * @author 
	 **/
	function add_woosocio_meta_boxes( $post_type, $post ) {
		$options = get_option( 'woosocio_settings' );
		if( isset($options['woosocio_checkbox_post_types'][$post_type]) ){
		    add_meta_box( 
		        'woosocio-meta-box',
		        __( 'WP Facebook Post (WooSocio)' ),
		        array( $this, 'woosocio_meta_box' ),
		        'post',
		        'side',
		        'default'
		    );
		}
	}

	/**
	 * woosocio_columns function.
	 *
	 * @access public
	 * @return columns
	 */
	function woosocio_columns($columns) {
		if ( isset( $_REQUEST['list'] ) && $_REQUEST['list'] == 'woosocio' ) {
		    $columns = array();
			$columns["cb"] = "<input type=\"checkbox\" />";
			$columns['thumb'] = '<span class="wc-image tips" data-tip="' . __( 'Image', 'woosocio' ) . '">' . __( 'Image', 'woosocio' ) . '</span>';
			$columns["name"] = __( 'Name', 'woosocio' );
			$columns["like_btn"] = __('Like/ Share Button?', 'woosocio');
			$columns["fb_post"] = __('Posted to Facebook?', 'woosocio');
			$columns["custom_msg"] = __('Custom Message', 'woosocio');

			return $columns;
		}
		else
			return $columns;
	}
		
	/**
	 * woosocio_custom_product_columns function.
	 *
	 * @access public
	 * @return void
	 */
	function woosocio_custom_product_columns( $column ) {
	global $post;

	if ( empty( $the_product ) || $the_product->id != $post->ID )
		$the_product = wc_get_product( $post );

	switch ($column) {
		case "like_btn" :
			$woo_like_fb = metadata_exists('post', $post -> ID, '_woosocio_like_facebook') ? get_post_meta( $post -> ID, '_woosocio_like_facebook', true ) : 'No';
			echo $woo_like_fb == 'checked' ? '<img src="'.$this->assets_url.'/yes.png" alt="Yes" width="25">' : '<img src="'.$this->assets_url.'/no.png" alt="No" width="25">';
		break;
		case "fb_post" :
			$woo_post_fb = metadata_exists('post', $post -> ID, '_woosocio_facebook') ? get_post_meta( $post -> ID, '_woosocio_facebook', true ) : 'No';
			echo $woo_post_fb == 'checked' ? '<img src="'.$this->assets_url.'/yes.png" alt="Yes" width="25">' : '<img src="'.$this->assets_url.'/no.png" alt="No" width="25">';			
		break;
		case "custom_msg" :
			echo get_post_meta( $post -> ID, '_woosocio_msg', true );
		break;
	}
}

	/**
	 * show_sharing_buttons function.
	 *
	 * @access public
	 * @return void
	 */
	public function show_sharing_buttons() {
		$post_id = get_the_ID();
		$socio_link = get_permalink( $post_id );
		$fb_like = metadata_exists('post', $post_id, '_woosocio_like_facebook') ? get_post_meta( $post_id, '_woosocio_like_facebook', true ) : 'checked';

		if ($fb_like) {
			if($this->fb_app_id)
				$fb_appid_option = '&appId='.$this->fb_app_id;
		  ?>
		  <div class="fb-like" data-href="<?php echo $socio_link; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
		  <div id="fb-root"></div>
		  <script><!--
		  (function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?php echo $fb_appid_option; ?>";
			fjs.parentNode.insertBefore(js, fjs);
		  }(document, 'script', 'facebook-jssdk'));//-->
          </script> 
		  <?php
		}
	}

	/**
	 * show_social_buttons function.
	 *
	 * @access public
	 * @return void
	 */
	public function ws_show_social_buttons() {
		$post_id = get_the_ID();
		$socio_link = get_permalink( $post_id );
		$fb_like = metadata_exists('post', $post_id, '_woosocio_like_facebook') ? get_post_meta( $post_id, '_woosocio_like_facebook', true ) : 'checked';

		$options = get_option( 'woosocio_settings' );
		if ($fb_like and isset($options['woosocio_checkbox_post_types'][get_post_type( $post_id )]) != '' and get_post_type( $post_id )!= 'product' ) {
			if ($fb_like) {
				if($this->fb_app_id)
					$fb_appid_option = '&appId='.$this->fb_app_id;
			  ?>
			  <div class="fb-like" data-href="<?php echo $socio_link; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
			  <div id="fb-root"></div>
			  <script><!--
			  (function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?php echo $fb_appid_option; ?>";
				fjs.parentNode.insertBefore(js, fjs);
			  }(document, 'script', 'facebook-jssdk'));//-->
	          </script> 
			  <?php
			}
		}
	}	

	/**
	 * woosocio_meta_box function.
	 *
	 * @access public
	 * @return void
	 */
	public function woosocio_meta_box() {
		global $post;
		$post_id = get_the_ID();
		?>
		<div id="woosocio" class="misc-pub-section misc-pub-section-last">
			<?php
			$content = '';

			_e( 'Facebook:', 'woosocio' );
			$like_chkbox_val = metadata_exists('post', $post_id, '_woosocio_like_facebook') ? get_post_meta( $post_id, '_woosocio_like_facebook', true ) : 'checked';
			$chkbox_val = metadata_exists('post', $post_id, '_woosocio_facebook') ? get_post_meta( $post_id, '_woosocio_facebook', true ) : 'checked';
			$saved_msg = ( get_post_meta( $post_id, '_woosocio_msg', true ) ? get_post_meta( $post_id, '_woosocio_msg', true ) : $post->title );
			$connections = $this->wsGetConnectionNames();

			if ( $connections ): 
				echo '&nbsp;<img src="'.$this->assets_url.'/connected.gif" alt="Connected "> as: '."<b>".$connections."</b>";
			else:
				echo "&nbsp;<b>" . __( 'Not Connected', 'woosocio' )."</b>";
				?>&nbsp;<a href="<?php echo admin_url( 'options-general.php?page=woosocio' ); ?>" target="_blank"><?php _e( 'Connect', 'woosocio' ); ?></a>
				
			<?php endif; ?>
			<div id="woosocio-form" style="display: none;">
            	<br />
                <input type="checkbox" name="like_facebook" id="like-facebook" <?php echo $like_chkbox_val; ?> />
                <label for="like-facebook"><b><?php _e( 'Show Like/Share buttons?', 'woosocio' ); ?></b></label><br />
                <input type="checkbox" name="chk_facebook" id="chk-facebook" <?php echo $chkbox_val; ?> />
                <label for="chk-facebook"><b><?php _e( 'Post to Facebook?', 'woosocio' ); ?></b></label><br />
				<label for="woosocio-custom-msg"><?php _e( 'Custom Message: (No html tags)', 'woosocio' ); ?></label>
				<textarea name="woosocio_custom_msg" id="woosocio-custom-msg"><?php echo $saved_msg; ?></textarea>
				<a href="#" id="woosocio-form-ok" class="button"><?php _e( 'Save', 'woosocio' ); ?></a>
				<a href="#" id="woosocio-form-hide"><?php _e( 'Cancel', 'woosocio' ); ?></a>
                <input type="hidden" name="postid" id="postid" value="<?php echo get_the_ID()?>" />
			</div>
             &nbsp; <a href="#" id="woosocio-form-edit"><?php _e( 'Edit', 'woosocio' ); ?></a>
		</div> 
        
		<script type="text/javascript"><!--
        jQuery(document).ready(function($){
                $("#woosocio-form").hide();
                
            $("#woosocio-form-edit").click(function(){
				$("#woosocio-form-edit").hide();
                $("#woosocio-form").show(1000);
            });
            
            $("#woosocio-form-hide").click(function(){
                $("#woosocio-form").hide(1000);
				$("#woosocio-form-edit").show();
            });
           
		    $("#woosocio-form-ok").click(function(){
				var custom_msg;
       			custom_msg = $("#woosocio-custom-msg").val();
				var data = {
					action: 'my_action',
					text1: custom_msg,
					postid: $("#postid").val(),
					chk_facebook: $("#chk-facebook").attr("checked"),
					like_facebook: $("#like-facebook").attr("checked")
				};
				$.post(ajaxurl, data, function(response) {
					console.log('Got this from the server: ' + response);
				});
                $("#woosocio-form").hide(1000);
				$("#woosocio-form-edit").show();
            });

        });
		//-->
        </script>
		<?php 
	}

	/**
	 * woosocio_ajax_action function.
	 *
	 * @access public
	 * @return void
	 */	
	public function woosocio_ajax_action($post) {
		if ( ! update_post_meta ($_POST['postid'], '_woosocio_msg', 
								 $_POST['text1'] ) ) 
			   add_post_meta(    $_POST['postid'], '_woosocio_msg', 
			   				     $_POST['text1'], true );
		if ( ! update_post_meta ($_POST['postid'], '_woosocio_facebook', 
								 $_POST['chk_facebook'] ) ) 
			   add_post_meta(    $_POST['postid'], '_woosocio_facebook', 
			   				     $_POST['chk_facebook'], true );
	    if ( ! update_post_meta ($_POST['postid'], '_woosocio_like_facebook', 
								 $_POST['like_facebook'] ) ) 
			   add_post_meta(    $_POST['postid'], '_woosocio_like_facebook', 
			   				     $_POST['like_facebook'], true );

		die(0);
		//die(); // this is required to return a proper result
	}
	
	/**
	 * woosocio_admin_init function.
	 *
	 * @access public
	 * @return void
	 */		
	public function woosocio_admin_init() {
       /* Register stylesheet. */
        wp_register_style( 'woosocioStylesheet', $this->assets_url.'/woosocio.css' );
		
		register_setting( 'woosocio_options', 'woosocio_settings' );
		
		// woosocio_options_section
		add_settings_section(
			'woosocio_options_section', 
			__( 'WooSocio Settings', 'woosocio' ), 
			array($this, 'woosocio_settings_section_callback'), 
			'woosocio_options'
		);

		// woosocio_post_format_section
		add_settings_section(
			'woosocio_post_format_section', 
			'', 
			array($this, 'woosocio_post_format_section_callback'), 
			'woosocio_options'
		);

		// woosocio_posts_options_section
		add_settings_section(
			'woosocio_posts_options_section', 
			'', 
			array($this, 'woosocio_posts_section_callback'), 
			'woosocio_options'
		);

		add_settings_field( 
			'woosocio_checkbox_post_update', 
			__( 'Post to Facebook every time on post update?', 'woosocio' ), 
			array($this, 'woosocio_checkbox_post_update'), 
			'woosocio_options', 
			'woosocio_options_section' 
		);
	
		add_settings_field( 
			'woosocio_checkbox_notifications', 
			__( 'Get error notifications by email?', 'woosocio' ), 
			array($this, 'woosocio_checkbox_notifications'), 
			'woosocio_options', 
			'woosocio_options_section' 
		);

		add_settings_field( 
			'woosocio_option_posting_type', 
			__( 'Post to Facebook as ', 'woosocio' ), 
			array($this, 'woosocio_option_posting_type'), 
			'woosocio_options', 
			'woosocio_post_format_section' 
		);

		if ( class_exists( 'WooCommerce' ) ) {
			add_settings_field( 
				'woosocio_checkbox_show_price', 
				__( 'Show Product Price?', 'woosocio' ), 
				array($this, 'woosocio_checkbox_show_price'), 
				'woosocio_options', 
				'woosocio_post_format_section' 
			);
		}

		add_settings_field( 
			'woosocio_checkbox_show_link', 
			__( 'Show Post Link?', 'woosocio' ), 
			array($this, 'woosocio_checkbox_show_link'), 
			'woosocio_options', 
			'woosocio_post_format_section' 
		);
/*
		add_settings_field( 
			'woosocio_groups_txtarea', 
			__( 'Enter group IDs, separated by comma!', 'woosocio' ), 
			array($this, 'woosocio_groups_txtarea'), 
			'woosocio_options', 
			'woosocio_options_section' 
		);
*/
		add_settings_field( 
			'woosocio_checkbox_post_types', 
			__( 'Select post types to post to Facebook!', 'woosocio' ), 
			array($this, 'woosocio_checkbox_post_types'), 
			'woosocio_options', 
			'woosocio_posts_options_section' 
		);
    }

	/**
	 * woosocio_options function.
	 *
	 * @access public
	 * @return void
	 */		
	public function woosocio_options () {
		
	?>
	<form action='options.php' method='post'>
	<div class="woosocio_wrap">
	<div id="woosocio-services-block">
		
		<?php
		settings_fields( 'woosocio_options' );
		do_settings_sections( 'woosocio_options' );
		submit_button();

		echo '</div>';
		
		$filepath = $this->plugin_path.'right_area.php';
		if (file_exists($filepath))
			include_once($filepath);
		else
			die('Could not load file '.$filepath);

		?>
	</div>	
	</form>	

	<?php

	}

	function woosocio_checkbox_post_update(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_post_update'] ) )
			$options['woosocio_checkbox_post_update'] = 0;
		?>
		<input type='checkbox' 
			   class="ios8-switch" 
			   name='woosocio_settings[woosocio_checkbox_post_update]' <?php checked( $options['woosocio_checkbox_post_update'], 1 ); ?> 
   			   id = 'woosocio_checkbox_post_update'
			   value='1'>
		<label for="woosocio_checkbox_post_update"><b><?php //echo ucwords($post_type); ?></b></label>
		<span class='description'><?php _e( 'If not selected, only new posts will be posted to Facebook.', 'woosocio' ) ?></span>
		<?php
	}
	
	function woosocio_checkbox_notifications(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_notifications'] ) )
			$options['woosocio_checkbox_notifications'] = 0;
		?>
		<input type='checkbox' 
			   class="ios8-switch" 
			   name='woosocio_settings[woosocio_checkbox_notifications]' <?php checked( $options['woosocio_checkbox_notifications'], 1 ); ?> 
			   id = 'woosocio_checkbox_notifications'
			   value='1'>
		<label for="woosocio_checkbox_notifications"></label>
		<span class='description'><?php _e( 'Get error notifications in site admin inbox.', 'woosocio' ) ?></span>
		<?php
	}

	function woosocio_option_posting_type(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_option_posting_type'] ) )
			$options['woosocio_option_posting_type'] = 'link';
		?>
		<input type="radio" name='woosocio_settings[woosocio_option_posting_type]' value='link' 
				<?php echo ($options['woosocio_option_posting_type'] == 'link') ? 'checked' : '' ?> > 
				<?php echo __( 'Link', 'woosocio' ) ?><br/>
		<input type="radio" name='woosocio_settings[woosocio_option_posting_type]' value='pic'
        		<?php echo ($options['woosocio_option_posting_type'] == 'pic') ? 'checked' : '' ?> > 
		        <?php echo __( 'Picture', 'woosocio' ) ?>
		<?php
	
	}

	function woosocio_checkbox_show_price(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_show_price'] ) )
			$options['woosocio_checkbox_show_price'] = 0;
		?>
		<input type='checkbox' 
			   class="ios8-switch" 
			   name='woosocio_settings[woosocio_checkbox_show_price]' <?php checked( $options['woosocio_checkbox_show_price'], 1 ); ?> 
			   id = 'woosocio_checkbox_show_price'
			   value='1'>
		<label for="woosocio_checkbox_show_price"></label>
		<span class='description'><?php _e( 'Select if you want to show product price to Facebook.', 'woosocio' ) ?></span>
		<?php
	}

	function woosocio_checkbox_show_link(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_show_link'] ) )
			$options['woosocio_checkbox_show_link'] = 0;
		?>
		<input type='checkbox' 
			   class="ios8-switch" 
			   name='woosocio_settings[woosocio_checkbox_show_link]' <?php checked( $options['woosocio_checkbox_show_link'], 1 ); ?> 
			   id = 'woosocio_checkbox_show_link'
			   value='1'>
		<label for="woosocio_checkbox_show_link"></label>
		<span class='description'><?php _e( 'Select if you want to show post URL to Facebook.', 'woosocio' ) ?></span>
		<?php
	}

	function woosocio_checkbox_post_tags(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_post_tags'] ) )
			$options['woosocio_checkbox_post_tags'] = 0;
		?>
		<input type='checkbox' 
			   class="ios8-switch" 
			   name='woosocio_settings[woosocio_checkbox_post_tags]' <?php checked( $options['woosocio_checkbox_post_tags'], 1 ); ?> 
			   id = 'woosocio_checkbox_post_tags'
			   value='1'>
		<label for="woosocio_checkbox_post_tags"></label>
		<span class='description'><?php _e( 'Include post tags as hashtags for Facebook.', 'woosocio' ) ?></span>
		<?php
	}

	function woosocio_number_default_postid(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_number_default_postid'] ) )
			$options['woosocio_number_default_postid'] = '';
		?>
		<input type='number' 
			   min = '0'
			   class="ios8-switch" 
			   name='woosocio_settings[woosocio_number_default_postid]'
			   id = 'woosocio_number_default_postid'
			   value = <?php echo $options['woosocio_number_default_postid'] ?> >
		<label for="woosocio_number_show_words"></label>
		<span class='description'><?php _e( 'Use this post as default image if no image attached.', 'woosocio' ) ?></span>
		<?php
	}

	function woosocio_checkbox_post_types(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_post_types'] ) )
			$options['woosocio_checkbox_post_types'] = array();
		
		foreach ( get_post_types( '', 'names' ) as $post_type ) {
		?>
		<input type='checkbox'
			   class="ios8-switch"
			   name='woosocio_settings[woosocio_checkbox_post_types][<?php echo $post_type ?>]' 
			   id = '<?php echo $post_type ?>'
			   <?php checked( isset($options['woosocio_checkbox_post_types'][$post_type]) ); ?> 
			   value='<?php echo $post_type ?> '> 
		<label for="<?php echo $post_type ?>"><b><?php echo ucwords($post_type) ?></b></label><br />
		<?php
		}
	}

	function woosocio_groups_txtarea(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_groups_txtarea'] ) )
			$options['woosocio_groups_txtarea'] = '';

		echo "<textarea rows='5' cols='52' placeholder='Enter group IDs here. Like 1111111111111111,2222222222222222' name='woosocio_settings[woosocio_groups_txtarea]'>";
		echo $options['woosocio_groups_txtarea'];
		echo "</textarea>";
		
		echo "<div class='woosocio-infobox'>";
		_e( 'This option provide you the ability to post on the groups you don\'t manage. ', 'woosocio' );
		_e( 'But you should be the member of the group to post! ', 'woosocio' );
		_e( 'Need to find group ID? ', 'woosocio' );
		echo "<a href='http://genialsouls.com/find-facebook-group-id/' target='_new' >".__('Click here!', 'woosocio')."</a>";
		echo "</div>";
	}

	/**
	 * woosocio_posts_section_callback function.
	 *
	 * @access public
	 * @return void
	 */		
	function woosocio_settings_section_callback(  ) { 

		echo '<h3 class="ws-table-title">' . __( 'Settings!', 'woosocio' ) . '</h3>';
	}

	/**
	 * woosocio_posts_section_callback function.
	 *
	 * @access public
	 * @return void
	 */		
	function woosocio_posts_section_callback(  ) { 
	
		echo '<h3 class="ws-table-title">' . __( 'Post Types for Facebook!', 'woosocio' ) . '</h3>';

	}

	/**
	 * woosocio_post_format_section_callback
	 *
	 * @access public
	 * @return void
	 */		
	function woosocio_post_format_section_callback(  ) { 
	
		echo '<h3 class="ws-table-title">' . __( 'Post Format for Facebook!', 'woosocio' ) . '</h3>';

	}

	/**
	 * socialize_post function.
	 *
	 * @access public
	 * @return void
	 */		
	public function socialize_post($post_id){
		
		$options = get_option( 'woosocio_settings' );
		if( get_post_status($post_id) == "publish" && isset($options['woosocio_checkbox_post_types'][get_post_type( $post_id )]) != '' ){

		// if( get_post_type( $post_id ) == "product" and get_post_status($post_id) == "publish" ){
			
			$fb_post = metadata_exists('post', $post_id, '_woosocio_facebook') ? get_post_meta( $post_id, '_woosocio_facebook', true ) : 'checked';
			$fb_posted = metadata_exists('post', $post_id, '_woosocio_fb_posted') ? get_post_meta( $post_id, '_woosocio_fb_posted', true ) : '';
			$options = get_option( 'woosocio_settings' );
			$repost = !$fb_posted ? true : $options['woosocio_checkbox_post_update'];

			if ( $fb_post && $repost) {

				$time_delay = time() + 5;
				$args = array($post_id, get_current_user_id());
				wp_schedule_single_event( $time_delay, 'ws_schedule_action', $args );

		
	      	} else return;
		} else return;
	}

    /**
     * Instantiates a new FacebookRequest entity.
     *
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return FacebookRequest
     *
     * @throws FacebookSDKException
     */
    public function ws_post_request( $fb_page_value, $endpoint, array $params = [], $accessToken = null, $eTag = null, $graphVersion = null)
    {

  		foreach($params as $key => $value) {
		  $param_arr.= "&".$key."=".$value;
		}
		echo $param_arr;

		$response = wp_remote_post("https://graph.facebook.com/".$fb_page_value.$endpoint."?&access_token=".$accessToken.$param_arr);
		return $response;
		
    }

    /**
     * Instantiates a new FacebookRequest entity.
     *
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return FacebookRequest
     *
     * @throws FacebookSDKException
     */
    public function ws_get_request($endpoint, $accessToken = null, $eTag = null, $graphVersion = null)
    {
		$response = wp_remote_get("https://graph.facebook.com".$endpoint."?access_token=".$accessToken);
		return (array)json_decode(wp_remote_retrieve_body( $response ));
    }

	/**
	 * socialize_post function.
	 *
	 * @access public
	 * @return void
	 */		
	public function ws_fb_post($post_id, $user_id){

		$fb_page_value = get_option( $user_id.'_fb_page_id', array('id' => '', 'ac' => '' ));
				
		if ($fb_page_value['id'] == ''){
			$fb_page_value = get_option( 'ws_shared_connection', array('id' => '', 'ac' => '' ));
		}
				
		if ($fb_page_value['id'] != ''){

			$post_excerpt = strip_tags( get_the_excerpt( $post_id ) );
		
			$message = get_the_title($post_id);
			$message.= metadata_exists('post', $post_id, '_woosocio_msg') ? " - ".get_post_meta( $post_id, '_woosocio_msg', true ) : '';
			
			$options = get_option( 'woosocio_settings' );
			if ( !isset ( $options['woosocio_option_posting_type'] ) )
				$options['woosocio_option_posting_type'] = 'pic';
				
			if ( $options['woosocio_option_posting_type'] == 'link' ){

				$socio_link = get_permalink( $post_id );
				$message.= "\n" . $post_excerpt;
				$post_arr = array(  'access_token'  => $fb_page_value['ac'],
									'link' 			=> $socio_link,
	                                'message'		=> html_entity_decode($message, ENT_COMPAT, "UTF-8"),
								 );
				$node = '/feed';
				wp_remote_post("https://graph.facebook.com?id=".get_permalink($post_id)."&scrape=true");
		
			} else {

				if( get_post_type( $post_id ) == "product" ){
					$_pf = new WC_Product_Factory();
					$_product = $_pf->get_product($post_id);

					$curr_symb = get_woocommerce_currency_symbol();
					
					if ( $options['woosocio_checkbox_show_price'] ) {
						$message.= "\n" . __( 'Price: ', 'woosocio') 
								. html_entity_decode($curr_symb, ENT_COMPAT, "UTF-8") 
								. $_product->get_price() ;
					}		
				}

				$post_desc = strip_tags( get_post_field( 'post_content', $post_id ) );
				$message.=  "\n" . $post_excerpt . "\n" . $post_desc;

				if ( $options['woosocio_checkbox_show_link'] ) {
					$message.= "\n" . __( 'See here: ', 'wpigp') 
							. get_permalink( $post_id );
				}
				
				$patt = '/#\S+[ \t]*/';
 				$message = preg_replace($patt, '', $message);
				$message = strip_shortcodes(html_entity_decode($message, ENT_COMPAT, "UTF-8"));
				$post_arr = array(	'message'		=> $message,
									'url'			=> wp_get_attachment_url(get_post_thumbnail_id( $post_id ) ),
								 );

				$node = '/photos';
			}

			try {

				$ret_obj = $this->ws_post_request( $fb_page_value['id'], $node, $post_arr, $fb_page_value['ac']);

				if ( $ret_obj['response']['code'] !== 200 ) {
					$response = (array)json_decode(wp_remote_retrieve_body( $ret_obj ));
					$exce_msg = $response['error']->message;
					throw new Exception($exce_msg);

				} else {
					
					$woosocio_fb_posted = update_post_meta ( $post_id, '_woosocio_fb_posted', 'checked' );

				} 
				
				$woosocio_facebook = update_post_meta ( $post_id, '_woosocio_facebook', 'checked' );

				if ( $node == '/feed' ){
					wp_remote_post("https://graph.facebook.com?id=".get_permalink($post_id)."&scrape=true");
				}

      		} 
			catch(Exception $e) {
				if ( $options['woosocio_checkbox_notifications'] ){
					$admin_email = get_option( 'admin_email' );
					if ( empty( $admin_email ) ) {
						$current_user = wp_get_current_user();
						$admin_email = $current_user->user_email;
					}
					
					$msg = __('Dear user,', 'woosocio') . "\r\n";
					$msg.= __('Your product ID ', 'woosocio') . $post_id . __(' not posted to Facebook due to following reason.', 'woosocio') . "\r\n";
					$msg.= $e->getMessage();
					
					wp_mail($admin_email, __('WooSocio - Notification', 'woosocio'), $msg, $this->woosocio_headers());
				}
				return false;
      		}
      	} else return;
	}

	/**
	 * clear cache function.
	 *
	 * @access public
	 * @return void
	 */		
	function clear_cache( $postid ){
		if(get_post_type( $postid ) == "product" and get_post_status($postid) == "publish"){
			wp_remote_post("https://graph.facebook.com?id=".get_permalink($postid)."&scrape=true");
		}
	}

	/**
	 * woosocio_admin_menu function.
	 *
	 * @access public
	 * @return void
	 */		
	public function woosocio_admin_menu () {
		add_menu_page( 'WooSocio', 'WooSocio', 'manage_options', 'woosocio', '', $this->assets_url.'/menu_icon_wc.png', 51 );
		$page_logins   = add_submenu_page( 'woosocio', 'WooSocio Logins', 'WooSocio Logins', 'manage_options', 'woosocio', array( $this, 'socio_settings' ) );
		$page_products = add_submenu_page( 'woosocio', 'WooSocio Products', 'WooSocio Products', 'manage_woocommerce', 'products_list', array( $this, 'products_list' ) );
		$page_options  = add_submenu_page( 'woosocio', 'WooSocio Options', 'WooSocio Options', 'manage_options', 'woosocio_options', array( $this, 'woosocio_options' ) );
		/*$page = add_options_page( 'Socio Logins', 'WooSocio Options', 'manage_options', 'woosocio', array( $this, 'socio_settings' ) );*/
		add_action( 'admin_print_styles-' . $page_logins, array( $this, 'woosocio_admin_styles' ) );
		add_action( 'admin_print_styles-' . $page_products, array( $this, 'woosocio_admin_styles' ) );
		add_action( 'admin_print_styles-' . $page_options, array( $this, 'woosocio_admin_styles' ) );
	}

	/**
	 * woosocio_admin_styles function.
	 *
	 * @access public
	 * @return void
	 */			
	public function woosocio_admin_styles() {
       /*
        * It will be called only on plugin admin page, enqueue stylesheet here
        */
       wp_enqueue_style( 'woosocioStylesheet' );
   }

	/**
	 * add list function.
	 *
	 * @access public
	 */	
	function add_list_woosocio(){
		if ( isset($_REQUEST['list']) && $_REQUEST['list'] == 'woosocio') 
		{
			echo '<input type="hidden" name="list" value="woosocio">';
		}
	}

	/**
	 * change url function.
	 *
	 * @access public
	 * @return columns
	 */
	function jquery_change_url(){
		if ( isset($_REQUEST['list']) && $_REQUEST['list'] == 'woosocio'){
			?>
			<script type="text/javascript"><!--
			jQuery(function(){
				jQuery(".all a").attr('href', function() {
					return this.href + '&list=woosocio';
				});
				
				jQuery(".publish a").attr('href', function() {
					return this.href + '&list=woosocio';
				});
				
				jQuery(".trash a").attr('href', function() {
					return this.href + '&list=woosocio';
				});
			});
			//-->
			</script>
			<?php
		}
	}

	/**
	 * socio_settings function.
	 *
	 * @access public
	 * @return void
	 */		
	public function socio_settings () {
		
		$filepath = $this->plugin_path.'woosocio.logins.php';
		if (file_exists($filepath))
			include_once($filepath);
		else
			die('Could not load file '.$filepath);
	}

	/**
	 * products_list function.
	 *
	 * @access public
	 * @return void
	 */		
	public function products_list () {
		
		?>
		<script type="text/javascript"><!--
			url = '<?php echo add_query_arg( array('post_type' => 'product',
											   	   'list'	   => 'woosocio'), admin_url('edit.php')) ?>';
			window.location.replace(url);//-->
		</script>
        <?php
	}


	/**
	 * creating email headers.
	 *
	 * @access public
	 */
	public function woosocio_headers(){
		$admin_email = get_option( 'admin_email' );
		if ( empty( $admin_email ) ) {
			$admin_email = 'support@' . $_SERVER['SERVER_NAME'];
		}

		$from_name = get_option( 'blogname' );

		$header = "From: \"{$from_name}\" <{$admin_email}>\n";
		$header.= "MIME-Version: 1.0\r\n"; 
		$header.= "Content-Type: text/plain; charset=\"" . get_option( 'blog_charset' ) . "\"\n";
		$header.= "X-Priority: 1\r\n"; 

		return $header;
	}

	/**
	 * Get Facebook Connection function.
	 *
	 * @access public
	 */
	public function wsGetConnection() {

		$fb_page_values = get_option( get_current_user_id().'_fb_page_id' );
			
		if (!is_array($fb_page_values)){
			$fb_page_values = get_option( 'ws_shared_connection' );
		}

		if (is_array($fb_page_values)){
			return $fb_page_values;
		}
		else
			return false;
	}

	/**
	 * Get Facebook Connection Names
	 *
	 * @access public
	 */
	public function wsGetConnectionNames() {

		$fb_page_values = $this->wsGetConnection();

		if ( $fb_page_values ){
			
			// foreach ( $fb_page_values as $fb_page_value => $access_token){

				$user_profile = $this->ws_get_request('/'.$fb_page_values['id'], $fb_page_values['ac']);
				$user_profiles = $user_profile['name'];

			// }
			
			return $user_profiles;
		}

		else 

			return false;
	}

	/**
	 * check connection function.
	 *
	 * @access public
	 */
	public function check_connection() {

		$this->fb_app_id = get_option( 'fb_app_id', '111');
		$this->fb_secret = get_option( 'fb_app_secret', 'abc');

 		if ( $this->fb_app_id != '' && $this->fb_secret != '' ) {
	 		try {
		  	  $fb = $this->ws_get_inst();
		  	  $this->ws_get_post_inst();
			  if ( isset( $_SESSION['facebook_access_token'] ) ) {
			  	$user_profile = $fb->get('/me', $_SESSION['facebook_access_token'] );
			    $this->fb_user_profile = $user_profile->getDecodedBody();
              }
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  return false;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  return false;
			}
		 	return $this->fb_user_profile;
		}
		else
			return false;
	}

	/**
	 * get new facebook instance
	 *
	 * @access public
	 */
	function ws_get_gs_login_url(){
		
		$get_state = get_option('_ws_state', '0' );
		
		if ( $get_state == '0') {
			// goto gs site for registration.
			return 'https://apps.genialsouls.com/register/';
		} else {
  			$fbgs = $this->ws_get_gs_inst();
			$gshelper = $fbgs->getRedirectLoginHelper();
			$permissions = ['manage_pages', 'publish_pages']; // optional
			return $gshelper->getLoginUrl( 'https://apps.genialsouls.com/authentication/', $permissions, '&state='.$get_state.'&');
		}
		
	}

	/**
	 * get new facebook instance
	 *
	 * @access public
	 */
	function ws_get_inst(){

		$this->fb_app_id = get_option( 'fb_app_id') == '' ? '111' : get_option( 'fb_app_id');
		$this->fb_secret = get_option( 'fb_app_secret') == '' ? 'abc' : get_option( 'fb_app_secret');

		try {
			$fb = new Facebook\Facebook([
			'app_id' => $this->fb_app_id,
	  		'app_secret' => $this->fb_secret,
	  		'default_graph_version' => 'v2.9',
			]);
			return $fb;
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  return false;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  return false;
		}
	}

	/**
	 * get new facebook instance
	 *
	 * @access public
	 */
	function ws_get_gs_inst(){

		try {
			$fb = new Facebook\Facebook([
			'app_id' => static::GS_APP,
	  		'app_secret' => static::GS_SECRET,
	  		'default_graph_version' => static::DEFAULT_GRAPH_VERSION,
			]);
			return $fb;
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  return false;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  return false;
		}
	}


	/**
	 * get new facebook instance
	 *
	 * @access public
	 */
	function ws_get_post_inst(){

		$this->fb_app_id = get_option( 'fb_app_id', '111');
		$this->fb_secret = get_option( 'fb_app_secret', 'abc');

		try {
			$this->facebook = new Facebook(array('appId'  	  => $this->fb_app_id,
												 'secret' 	  => $this->fb_secret,
												 'status' 	  => true,
												 'cookie' 	  => true,
												 'xfbml' 	  => true,
												 'fileUpload' => true ));
			return $this->facebook;
		} catch(FacebookApiException $e) {
		  return false;
		}
	}

    /**
     * Get access token.
     *
     * @param string $code
     * @param string $redirectUri
     *
     * @return AccessToken
     *
     * @throws FacebookSDKException
     */
    public function ws_getAccessToken($code, $redirectUri = '')
    {
	  	$response = wp_remote_post("https://graph.facebook.com/v2.12/oauth/access_token?client_id="
	  								.get_option( 'fb_app_id')
	  								."&redirect_uri=".admin_url()
	  								."admin.php?page=woosocio&client_secret="
	  								.get_option( 'fb_app_secret')
	  								."&code=".$code
	  							  );
	  
	  	$ac = json_decode($response['body']);
	  	$accessToken = $ac->access_token;

        return $accessToken;
    }

	/**
	 * save facebook app id and secret function.
	 *
	 * @access public
	 */
	public function save_app_info() {
		update_option( 'fb_app_id', $_POST['fb_app_id'] );
		update_option( 'fb_app_secret', $_POST['fb_app_secret'] );
 	}

	/**
	 * update facebook shared connection function.
	 *
	 * @access public
	 */
	public function save_shared_connection() {
		
		if( $_POST['fb_shared_con'] == 'false' ){
			if( delete_option( 'ws_shared_connection' ) )
				_e( 'Connection Updated!', 'woosocio');
			else
				_e( 'Unable to update shared connection! Please try again.', 'woosocio');

		}
		else{
			if (update_option( 'ws_shared_connection', array( 'id'	=> $_POST['fb_shared_page_id'],
															  'ac'	=> $_POST['fb_shared_page_ac']) ) )
				_e( 'Connection Updated!', 'woosocio');
			else
				_e( 'Unable to update shared connection! Please try again.', 'woosocio');
		}
		die(0);
 	}

	/**
	 * update facebook page id function.
	 *
	 * @access public
	 */
	public function update_page_info() {

		$fb_user_sign = $_POST['fb_user_profile_id'].'_fb_page_id';
		$wp_user_sign = get_current_user_id().'_fb_page_id';

		try{
			delete_option( 'ws_shared_connection' );
		}catch(FacebookApiException $e) {}
		update_option( $fb_user_sign, array('id'		=> $_POST['fb_page_id'],
											'type'		=> $_POST['fb_type'],
											'ac'		=> $_POST['fb_page_ac']) );
		if(update_option( $wp_user_sign, array('id'		=> $_POST['fb_page_id'],
											   'type'	=> $_POST['fb_type'],
											   'ac'		=> $_POST['fb_page_ac']) ))
			_e( 'Page Info Updated!', 'woosocio');
		else
			_e( 'Unable to update page info! Please try again.', 'woosocio');
		die(0);
 	}

	/**
	 * delete saved connection
	 *
	 * @access public
	 */
	public function ws_delete_connection() {
		
		$user_sign = get_current_user_id().'_fb_page_id';
		if( delete_option( $user_sign ) ){
			delete_option( 'ws_shared_connection' );
			_e( 'Deleted', 'woosocio');
		}
		else
			_e( 'Error deleting connection! Please try later!', 'woosocio');

		die(0);
 	}

	/**
	 * load_localisation function.
	 *
	 * @access public
	 * @return void
	 */
	public function load_localisation () {
		$lang_dir = trailingslashit( str_replace( 'classes', 'lang', plugin_basename( dirname(__FILE__) ) ) );
		load_plugin_textdomain( 'woosocio', false, $lang_dir );
	} // End load_localisation()

	/**
	 * activation function.
	 *
	 * @access public
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * register_plugin_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( 'woosocio' . '-version', $this->version );
		}
	} // End register_plugin_version()
} // End Class
?>