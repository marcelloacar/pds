<?php
session_start();
global $woosocio, $is_IE;
if(isset($_GET['action']) && $_GET['action'] === 'logout'){
    $_SESSION['facebook_access_token']='';
}

if (isset($_GET['user_state'])){
  	update_option( '_ws_state', $_GET['user_state'] );
	echo '<table class="wp-list-table widefat fixed ws-table-css">';
	echo '<tbody>';
	echo '<tr>';
	echo '<td style="width: 40%;">';
		
		echo '<span style="color:blue; font-size: 175%">'.__('Your site has been verified by WooSocio!', 'woosocio').'</span>';
	
	echo '</td>';
	echo '<td>';

	  	echo '<span class="dashicons dashicons-yes" title="Yes" style="color:green;font-size: 300%"></span>';

	echo '</td>';
	echo '</tr>';

	echo '</tbody>';
	echo '</table>';
}

if (isset($_GET['ac'])){
  	$accessToken = $_GET['ac'];
    $_SESSION['facebook_access_token'] = (string) $accessToken;
} else {

	$facebook = $woosocio->ws_get_inst();

	try {
	  $helper = $facebook->getRedirectLoginHelper();
	  if (isset($_GET['code'])) {
	  	$accessToken = $woosocio->ws_getAccessToken($_GET['code']);
	  }
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo $e->getMessage();
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo $e->getMessage();
	}
}

if (isset($accessToken)) {
  $_SESSION['facebook_access_token'] = (string) $accessToken;
}

if (isset($_SESSION['facebook_access_token']) && $_SESSION['facebook_access_token'] != '')
	$ws_fb_connection = true;
else
	$ws_fb_connection = false;

if ( $ws_fb_connection ) {
  	$logoutUrl = admin_url().'admin.php?page=woosocio&logout=yes&action=logout';

	$user_profile = $woosocio->ws_get_request('/me', $_SESSION['facebook_access_token'] );

	$user_pages = $woosocio->ws_get_request('/me/accounts', $_SESSION['facebook_access_token']);

	$user_groups = $woosocio->ws_get_request('/me/groups', $_SESSION['facebook_access_token'] );

} else {

	$facebook = $woosocio->ws_get_inst();
  	$helper = $facebook->getRedirectLoginHelper();
	$permissions = ['manage_pages', 'publish_pages', 'publish_to_groups']; // optional
  	$loginUrl = $helper->getLoginUrl( admin_url().'admin.php?page=woosocio', $permissions);
  	$gsloginUrl = $woosocio->ws_get_gs_login_url();
  	
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title><?php _e( 'WooSocio Login', 'woosocio' ) ?></title>
</head>
<body>
<div class="woosocio_wrap">
 
  <?php 
	if ( isset( $_GET['oauth_resp'] ) ) {
  		echo "<p style='font-size:18px; color:#F00;'>" . $_GET['oauth_resp'] . "</p>";
	}

	if ($is_IE){
	  echo "<p style='font-size:18px; color:#F00;'>" . __( 'Important Notice:', 'woosocio') . "</p>";
	  echo "<p style='font-size:16px; color:#F00;'>" . 
	  		__( 'You are using Internet Explorer. This plugin may not work properly with IE. Please use any other browser.', 'woosocio') . "</p>";
	  echo "<p style='font-size:16px; color:#F00;'>" . __( 'Recommended: Google Chrome.', 'woosocio') . "</p>";
	}
  ?>
  <div id="woosocio-services-block">
	<img src="<?php echo $woosocio->assets_url.'/facebook-logo.png' ?>" alt="Facebook Logo">
	<div class="woosocio-service-entry" >

        <?php if ( !$ws_fb_connection ) :?>
        <div class="login-box">
			<h2>Facebook Login</h2>
				<a href="<?php echo esc_url( $gsloginUrl ); ?>" class="social-button" id="facebook-connect"> <span>Connect with WooSocio (Trial) (BETA)</span></a>
				<a href="<?php echo esc_url( $loginUrl ); ?>" class="social-button" id="facebook-connect"> <span>Connect with my App</span></a>
		</div>
		<?php else: ?>
		<div class="woosocio-service-right">
			
            <?php _e( 'Connected as:', 'woosocio') ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="woosocio-profile-link" href="https://www.facebook.com" target="_top">
			<img src="http://graph.facebook.com/<?php echo $user_profile['id'] ?>/picture" alt="No Image">
			<?php echo $user_profile['name'] ?></a><br>
            <a id="pub-disconnect-button1" class="woosocio-add-connection button" href="<?php echo $logoutUrl; ?>" target="_top"><?php _e('Disconnect', 'woosocio')?></a><br>
            <span id="working_app_info" class="spinner is-active" style="display: none;"></span><br>
        </div>
        <?php endif; ?>
		
		<div class="woosocio-service-right">
            <?php 
			if (get_option( 'fb_app_id' ) && get_option( 'fb_app_secret' )): 
			    echo '<a id="app-details" href="javascript:">' . __('Show App Details', 'woosocio') . '</a>';
				echo '<div id="app-info" style="display: none;">';
			else:            
            	echo '<div id="app-info">';
			endif;
			?>
            <table class="form-table">
            <tr valign="top">
	  			<th scope="row"><label><?php _e('Your App ID:', 'woosocio') ?></label></th>
	  			<td>
	  				<input type="text" name="app_id" id="fb-app-id" placeholder="<?php _e('App ID', 'woosocio') ?>" value="<?php echo get_option( 'fb_app_id' ); ?>"><br>
                    <p style="font-size:10px"><?php _e("Don't have an app? You can get from ", 'woosocio') ?>
                    <a href="https://developers.facebook.com/apps" target="_new" style="font-size:10px">developers.facebook.com/apps</a>
                    <!--<p><a href="https://www.youtube.com/watch?v=hfFkOZ9USeA"><?php //_e("Video: How to create Facebook app for WooSocio", 'woosocio') ?></a>-->
	  			</td>
	  		</tr>
            <tr valign="top">
	  			<th scope="row"><label><?php _e('Your App Secret:', 'woosocio') ?></label></th>
	  			<td>
	  				<input type="text" name="app_secret" id="fb-app-secret" placeholder="<?php _e('App Secret', 'woosocio') ?>" value="<?php echo get_option( 'fb_app_secret' ); ?>"><br>
                    <p style="font-size:11px"><?php _e('Need more help? ', 'woosocio') ?>
                    <a href="https://developers.facebook.com/docs/opengraph/getting-started/#create-app" target="_new" style="font-size:11px"><?php _e('Click here', 'woosocio') ?></a>
	  			</td>
	  		</tr>
            <tr valign="top">
     	  		<th scope="row"></th>
	  			<td>
                	<a id="btn-save" class="button-primary button" href="javascript:"><?php _e('Save', 'woosocio') ?></a>
	  			</td>
	  		</tr>
            </table>
			<iframe 
            	src="https://www.youtube.com/embed/tNZqddIUUtU"
                width="560" 
            	height="315" 
                frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>
            </iframe> 
			<p>
            	<a href="https://www.youtube.com/watch?v=tNZqddIUUtU" target="_new"><?php _e('How to create Facebook App v2.11 for WooSocio', 'woosocio') ?></a>
                <?php _e('from', 'woosocio') ?> <a href="http://genialsouls.com/" target="_new">GenialSouls</a>
                <?php _e('on', 'woosocio') ?> <a href="https://www.youtube.com/channel/UC1z3dGUdG5JJEIsEpaDnG5w" target="_new">YouTube</a>.
            </p>            
            
            </div>
		</div>
	</div>

	<?php
    if ( $ws_fb_connection ) {
    	echo '<div class="woosocio-service-entry" >';
			$me_ac = $_SESSION['facebook_access_token'];
			$user_sign = $user_profile['id'].'_fb_page_id';
			$fb_page_value = get_option( $user_sign, array('id' => '') );
			$ws_shared_con = get_option( 'ws_shared_connection', array( 'id' => '' ) );
			$checked = $fb_page_value['id'] == $ws_shared_con['id'] ? 'checked' : '';
			$fb_page_value['id'] == $user_profile['id']?
			$update_page_value = array('id' => $user_profile['id'],
									   'type' => 'me',
									   'ac' => $me_ac)
			:$update_page_value = array();
			$fb_page_value['id'] == $ws_shared_con['id'] ?
			$update_sc_value = array('id' => $user_profile['id'],
									 'ac' => $me_ac)
			:$update_sc_value = array('id'=>'');
        	?>  
	        <!-- shared connection table start -->
			
			<h3 class="ws-table-title"> <?php _e( 'Shared connection?', 'woosocio' )?> </h3>
			<table class="wp-list-table widefat fixed ws-table-css">
        	<tbody>
			<tr>
				<td>
					<span id="shared_con_ico" style="font-size: 250%; color:darkblue" class="dashicons dashicons-groups"></span>
        		</td>
        		<td>
					<input type="checkbox" class="ios8-switch" name="ws_shared_con" id="ws_shared_con" value="<?php echo $ws_shared_con['id'] ?>" <?php echo $checked ?> >        		
	        		<label for="ws_shared_con"><?php _e('Available this connection for all WooSocio users?', 'woosocio') ?></label>
	        	</td>
				<td>
					<span id="working_con" class="spinner is-active" style="display: none;"></span>
				</td>
			</tr>
        	</tbody>
       		</table>
	        <!-- shared connection table end -->

	        <!-- personal table start -->
			<h3 class="ws-table-title"> <?php _e( 'Pages', 'woosocio' )?> </h3>
			<table class="wp-list-table widefat fixed ws-table-css">
        	<tbody>
			<tr>
				<td>
	        		<label for="<?php echo $user_profile['id'] ?>">
    		        <img src="http://graph.facebook.com/<?php echo $user_profile['id'] ?>/picture" alt="No Image"></label>
				</td>
				<td class="ws-table-td">
		            <input type="radio" name="pages" id="<?php echo $user_profile['id'] ?>" class="ios8-switch" value="<?php echo $user_profile['id'] ?>" <?php echo ($fb_page_value['id'] == $user_profile['id'])?'checked':''?>>
					<label for="<?php echo $user_profile['id'] ?>"><?php _e('Personal Page (Wall)', 'woosocio') ?></label> <br>
        		    <input type="hidden" id="<?php echo 'fb_type'.$user_profile['id'] ?>" value="me">
        		    <input type="hidden" id="fb-user-profile-id" value="<?php echo $user_profile['id'] ?>">
            		<input type="hidden" id="<?php echo 'fb_ac_'.$user_profile['id'] ?>" value="<?php echo $me_ac ?>">
				</td>
				<td class="ws-table-td">
					<span id="<?php echo 'sp_'.$user_profile['id'] ?>" class="spinner is-active" style="display: none;"></span>
				</td>
			</tr>

        	<?php
	        $page_names = $user_pages['data'];
	        foreach($page_names as $key => $page)
	        {
	        	$page = (array)$page;
				$page_ac = $page['access_token'];
				$fb_page_value['id'] == $page['id']?
				$update_page_value = array('id' => $page['id'],
										   'type' => 'page',
										   'ac' => $page_ac)
				:$update_page_value;
				
				$page['id'] == $ws_shared_con['id'] ?
				$update_sc_value = array('id' => $page['id'],
										 'ac' => $page_ac)
				:$update_sc_value;

	        ?>
			<tr>
				<td>
		        	<label for="<?php echo $page['id'] ?>">
		            <img src="http://graph.facebook.com/<?php echo $page['id'] ?>/picture" alt="No Image"></label>
				</td>
				<td class="ws-table-td">
		            <input type="radio" name="pages" id="<?php echo $page['id'] ?>" class="ios8-switch" value="<?php echo $page['id'] ?>" <?php echo ($fb_page_value['id'] == $page['id']) ? 'checked':''?>>
					<label for="<?php echo $page['id'] ?>"><?php echo $page['name'] ?></label><br>
	    	        <input type="hidden" id="<?php echo 'fb_type'.$page['id'] ?>" value="page">
	        	    <input type="hidden" id="<?php echo 'fb_ac_'.$page['id'] ?>" value="<?php echo $page_ac ?>">
				<td class="ws-table-td">
					<span id="<?php echo 'sp_'.$page['id'] ?>" class="spinner is-active" style="display: none;"></span>
				</td>
			</tr>
	        <?php
	        }	//$woosocio->pa($user_profile);		 
		?>
        	<!-- </div> -->
        	</tbody>
       		</table>
			
	        <!-- pages table end -->

	        <!-- groups table start -->
			<table class="wp-list-table widefat fixed ws-table-css">
        	<tbody>
		<?php
		
	        $group_names = $user_groups['data'];
	        if ( is_array( $group_names ) && array_filter($group_names) ){
		        echo '<h3 class="ws-table-title">' . __( 'Groups', 'woosocio' ) . '</h3>';	
		        // $woosocio->pa($group_names);
		        foreach($group_names as $key => $group)
		        {
		        	$group = (array)$group;
		        	$me_ac = $_SESSION['facebook_access_token'];
					$fb_page_value['id'] == $group['id']?
					$update_page_value = array('id' => $group['id'],
											   'type' => 'group',
											   'ac' => $me_ac)
					:$update_page_value;
					
					$group['id'] == $ws_shared_con['id'] ?
					$update_sc_value = array('id' => $group['id'],
											 'ac' => $me_ac)
					:$update_sc_value;

				?>
				<tr>
					<td>
			        	<label for="<?php echo $group['id'] ?>">
			            <img id="<?php echo 'fi_'.$group['id'] ?>" src="http://graph.facebook.com/<?php echo $group['id'] ?>/picture" alt="No Image" onerror="load_standby_img('<?php echo 'fi_'.$group['id'] ?>','<?php echo $woosocio->assets_url.'/fb_group.png'?>')"></label>
			        </td>
		            <td class="ws-table-td">
		            	<input type="radio" name="pages" id="<?php echo $group['id'] ?>" class="ios8-switch" value="<?php echo $group['id'] ?>" <?php echo ($fb_page_value['id'] == $group['id']) ? 'checked':''?>>
						<label for="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></label><br>
		            	<input type="hidden" id="<?php echo 'fb_type'.$group['id'] ?>" value="group">
		            	<input type="hidden" id="<?php echo 'fb_ac_'.$group['id'] ?>" value="<?php echo $me_ac ?>">
		            </td>
					<td class="ws-table-td">
						<span id="<?php echo 'sp_'.$group['id'] ?>" class="spinner is-active" style="display: none;"></span>
					</td>
				</tr>

		        <?php
		        }
			}
	        ?>
	        	</tbody>
	       		</table>
		        <!-- groups table end -->
	        <?php
			
			$other_groups = get_option( 'woosocio_settings' );
			if ( isset ( $other_groups['woosocio_groups_txtarea'] ) && $other_groups['woosocio_groups_txtarea'] != '' ){
				$arr_other_groups = explode( ',', $other_groups['woosocio_groups_txtarea'] );
				$arr_other_groups = array_unique($arr_other_groups);
	        ?>
		        <!-- other groups table start -->
				<table class="wp-list-table widefat fixed ws-table-css">
	        	<tbody>
	        	<h3 class="ws-table-title"><?php _e( 'Other Groups', 'woosocio' )?></h3>
	        <?php
	
	    	    foreach($arr_other_groups as $group)
		        {
					$me_ac = $_SESSION['facebook_access_token'];
					$response = wp_remote_get("https://graph.facebook.com/".$group."?fields=name&access_token=".$me_ac);
					$group_info = json_decode(wp_remote_retrieve_body( $response ));
					$group_array = (array)$group_info;
					// $this->pa($group_array);
					if (!array_key_exists('error', $group_array)){

				?>
				<tr>
					<td>
		            	<label for="<?php echo $group ?>">
						<img id="<?php echo 'ofi_'.$group ?>" src="http://graph.facebook.com/<?php echo $group ?>/picture" alt="No Image" onerror="load_standby_img('<?php echo 'ofi_'.$group ?>','<?php echo $woosocio->assets_url.'/fb_group.png'?>')"></label>
					</td>
		            <td class="ws-table-td">
		            	<input type="radio" name="pages" id="<?php echo $group ?>" class="ios8-switch" value="<?php echo $group ?>" <?php echo ($fb_page_value['id'] == $group) ? 'checked':''?>>
						<label for="<?php echo $group ?>"><?php echo $group_info->name ?></label><br>
	    	        	<input type="hidden" id="<?php echo 'fb_type'.$group ?>" value="group">
	    	        	<input type="hidden" id="<?php echo 'fb_ac_'.$group ?>" value="<?php echo $me_ac ?>">
					</td>
					<td class="ws-table-td">
						<span id="<?php echo 'sp_'.$group ?>" class="spinner is-active" style="display: none;"></span>
					</td>
				</tr>
				<?php
				}
				}
	        ?>
        	</tbody>
       		</table>
	        <!-- groups table end -->
	        <?php
			}
		echo '</div>';
		update_option( $user_sign, $update_page_value );
		update_option( get_current_user_id().'_fb_page_id', $update_page_value );
		update_option( 'ws_shared_connection', $update_sc_value );
	}//$woosocio->pa($update_sc_value);	
	?>

    
    <?php
	$fb_saved_con = get_option( get_current_user_id().'_fb_page_id' ) == '' ? array() : get_option( get_current_user_id().'_fb_page_id' );
	$ws_shared_con = get_option( 'ws_shared_connection' ) == '' ? array() : get_option( 'ws_shared_connection' );
	$fb_both_cons = $fb_saved_con + $ws_shared_con;

	if (!$ws_fb_connection && array_filter($fb_both_cons)){
		echo '<table class="wp-list-table widefat fixed ws-table-css">';
       	echo '<tbody>';
       	echo '<h3 class="ws-table-title">' . __( 'Available Connection', 'woosocio' ) . '</h3>';

		echo '<tr>';
			
		echo '<td>';
		?><img id="<?php echo 'ofi_'.$fb_both_cons['id'] ?>" src="http://graph.facebook.com/<?php echo $fb_both_cons['id'] ?>/picture" alt="No Image" onerror="load_standby_img('<?php echo 'ofi_'.$fb_both_cons['id'] ?>','<?php echo $woosocio->assets_url.'/fb_group.png'?>')"><?php
		echo '</td>';
			
		echo '<td class="ws-table-td">';
		$response = wp_remote_get("https://graph.facebook.com/".$fb_both_cons['id']."?fields=name&access_token=".$fb_both_cons['ac']);
		$page_info = json_decode(wp_remote_retrieve_body( $response ));
		echo $page_info->name;
		echo '</td>';
		
		echo '<td class="ws-table-td">';
		echo '<img src="'.$woosocio->assets_url.'/connected.gif" alt="Connected " title="'.__( 'Connected!', 'woosocio').'" style="border:0px">';
		echo '</td>';

		if( array_filter($ws_shared_con) ){
			echo '<td class="ws-table-td">';
			echo '<span id="shared_con_ico" style="color:darkblue" class="dashicons dashicons-groups" title="'.__( 'Shared!', 'woosocio').'"></span>';
			echo '</td>';
		}
		
		if( array_filter($fb_saved_con) ){
			echo '<td class="ws-table-td">';
			echo '<a title="'.__( 'Delete connection!', 'woosocio').'" id="ws_delete_connection" href="javascript:;"><span class="dashicons dashicons-trash" style="color: #d01000;"></span></a>';
			echo '</td>';

			echo '<td class="ws-table-td">';
			echo '<span id="working_del_con" class="spinner is-active" style="display: none;"></span>';
			echo '</td>';
		}

		echo '</tr>';

        echo '</tbody>';
       	echo '</table>';

		// $woosocio->pa($fb_both_cons);
	}

	if ( $ws_fb_connection ) {
    	echo '<div class="woosocio-service-entry">';
		/*
			Check permissions
		*/
		$arr_permissions = $woosocio->ws_get_request('/me/permissions', $_SESSION['facebook_access_token'] );
		
		echo '<table class="wp-list-table widefat fixed ws-table-css">';
   		echo '<tbody>';
   		echo '<h3 class="ws-table-title">' . __( 'App Permissions', 'woosocio' ) . '</h3>';

		foreach ( $arr_permissions['data'] as $permission ) {
			$permission = (array)$permission;
			echo '<tr>';
			echo '<td>' . $permission['permission'] . '</td>';
			echo '<td>' . $permission['status']     . '</td>';
			echo '</tr>';
		}
   		echo '</tbody>';
		echo '</table>';
	    echo '</div>';
	}
	echo '<h3 class="ws-table-title">' . __( 'You may also like!', 'woosocio' ) . '</h3>';
	?>

    <div class="woosocio-service-entry" style="font-size:18px; color:#03D">
        <div class="woosocio-service-left">
            <a href="https://wordpress.org/plugins/gs-facebook-comments/" target="_top">
            <img src="<?php echo $woosocio->assets_url.'/wpfc_icon.jpg' ?>" alt="WP Facebook Comments/" height="128">
            </a>
        </div>
        <div class="woosocio-service-right">
            <div align="left">
            <?php
				echo '<a href="https://wordpress.org/plugins/gs-facebook-comments/" target="_top">'.__('* WP Facebook Comments *', 'woosocio').'</a></br>';
				_e('* Add Facebook comments at your site.', 'woosocio'); echo "</br>";
				_e('* Share comments on Facebook.', 'woosocio'); echo "</br>";
				_e('* Add Facebook comments on all types of posts.', 'woosocio'); echo "</br>";
				_e('* Customize comments box.', 'woosocio'); echo "</br>";
				_e('* Ability to moderation of comments.', 'woosocio'); echo "</br>";
				_e('* And many more...', 'woosocio'); echo "</br>";
            ?>
            </div>
        </div>
    </div>
    <div class="woosocio-service-entry" style="font-size:18px; color:#03D">
        <div class="woosocio-service-left">
            <a href="https://wordpress.org/plugins/wp-instagram-post/" target="_top">
            <img src="<?php echo $woosocio->assets_url.'/wpigp_icon.jpg' ?>" alt="WP Instagram Post">
            </a>
        </div>
        <div class="woosocio-service-right">
            <div align="left">
            <?php
				echo '<a href="https://wordpress.org/plugins/wp-instagram-post/" target="_top">'.__('* WP Instagram Post *', 'woosocio').'</a></br>';
				_e('* Post any type of post to Instagram.', 'woosocio'); echo "</br>";
				_e('* Post product price, currency symbol, link, details.', 'woosocio'); echo "</br>";
				_e('* Post products multiple times. (on every update)', 'woosocio'); echo "</br>";
				_e('* Simple to use with Instagram user and password.', 'woosocio'); echo "</br>";
				_e('* And many more...', 'woosocio'); echo "</br>";
            ?>
            </div>
        </div>
    </div>
    <div class="woosocio-service-entry" style="font-size:18px; color:#03C">
        <div class="woosocio-service-left">
            <a href="https://wordpress.org/plugins/wootweet/" target="_top">
            <img src="<?php echo $woosocio->assets_url.'/wootweet_icon.jpg' ?>" alt="WooTweet">
            </a>
        </div>
        <div class="woosocio-service-right">
            <div align="left">
            <?php
                echo '<a href="https://wordpress.org/plugins/wootweet/" target="_top">'.__('* WooTweet Free *', 'woosocio').'</a>';
                echo "</br></br>";
                _e('* Post product to Twitter', 'woosocio'); echo "</br>";
                _e('* Post products multiple times (on every update)', 'woosocio'); echo "</br>";
                _e('* And many more to come...', 'woosocio'); echo "</br>";
            ?>
            </div>
        </div>
    </div>
  </div>
    <!-- Right Area Widgets -->  
    <?php 
		include_once 'right_area.php';
	 ?>
    <!-- Right Area Widgets -->  
</div>
  </body>
</html>
<script type="text/javascript">
jQuery(document).ready(function($){
		//$("#app-info").hide();
		
	$("#ws_shared_con").click(function(){
		$("#working_con").show();
		
		var data = {
			action: 'save_shared_connection',
			fb_shared_con: $("#ws_shared_con").is(':checked'),
			fb_shared_page_id: $('input[name=pages]:checked').val(),
			fb_shared_page_ac: $("#fb_ac_"+$('input[name=pages]:checked').val()).val()
		};
		
		$.post(ajaxurl, data, function(response) {
			console.log('Got this from the server: ' + response);
			$("#working_con").hide();
		//location.reload();
		});	
		
		$("#app-info").hide(2000);
	});

	$("#btn-save").click(function(){
		$("#working_app_info").show();
		
		var data = {
			action: 'save_app_info',
			fb_app_id: $("#fb-app-id").val(),
			fb_app_secret: $("#fb-app-secret").val()
		};
		
		$.post(ajaxurl, data, function(response) {
			console.log('Got this from the server: ' + response);
		location.reload();
		});	
		
		$("#app-info").hide(2000);
	});

	$("input:radio[name=pages]").click(function() {
		//$("#working-page").show();
		var current_pg_id = "sp_"+$(this).val();
		$("#"+current_pg_id).show();
		//alert($("#fb-user-profile-id").val());

		var data = {
			action: 'update_page_info',
			fb_page_id: $(this).val(),
			fb_page_ac: $("#fb_ac_"+$(this).val()).val(),
			fb_user_profile_id: $("#fb-user-profile-id").val(),
			fb_type: $("#fb_type"+$(this).val()).val()
		};

		$.post(ajaxurl, data, function(response) {
			//console.log('Got this from the server: ' + response);
			$("#"+current_pg_id).hide();
			$('input[name=ws_shared_con]').prop('checked', false);
			alert(response);
			//location.reload();
		});
	});
	
	$("#app-details").click(function(){
		$("#app-info").toggle(1000);
	});

	$("#ws_delete_connection").click(function(){
		$("#working_del_con").show();
		var data = {
			action: 'ws_delete_connection',
		};

		$.post(ajaxurl, data, function(response) {
			console.log('Got this from the server: ' + response);
			if (response=='Deleted') {
				location.reload();
			} else {
				alert(response);
			};
		});		
	});

});

//======= javascript =======//

function load_standby_img(gid, sip){
	document.getElementById(gid).src = sip;
}
</script>