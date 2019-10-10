<?php
/**
 * Adds WS_Widget widget.
 */
class WS_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'ws_widget', // Base ID
			__( 'WooSocio - Facebook Like Box', 'woosocio' ), // Name
			array( 'description' => __( 'A WooSocio Widget to show Facebook Like Box!', 'woosocio' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$WsFBURL = ! empty( $instance['WsFBURL'] ) ? $instance['WsFBURL'] : '';
		$datawidth = ! empty( $instance['datawidth'] ) ? $instance['datawidth'] : '';
		$datahight = ! empty( $instance['datahight'] ) ? $instance['datahight'] : '';
		$datatimeline = ! empty( $instance['datatimeline'] ) ? $instance['datatimeline'] : '';
		$dataevents = ! empty( $instance['dataevents'] ) ? $instance['dataevents'] : '';
		$datamessages = ! empty( $instance['datamessages'] ) ? $instance['datamessages'] : '';
		$datahidecover = ! empty( $instance['datahidecover'] ) ? $instance['datahidecover'] : '';
		$datashowfacepile = ! empty( $instance['datashowfacepile'] ) ? $instance['datashowfacepile'] : 'false';
		$datahidecta = ! empty( $instance['datahidecta'] ) ? $instance['datahidecta'] : '';
		$datasmallheader = ! empty( $instance['datasmallheader'] ) ? $instance['datasmallheader'] : '';
		$dataadaptcontainerwidth = ! empty( $instance['dataadaptcontainerwidth'] ) ? $instance['dataadaptcontainerwidth'] : '';
		?>	
	
    	<div 
			class="fb-page" 
			data-href="<?php echo $WsFBURL; ?>" 
			data-width="<?php echo $datawidth; ?>"
			data-height="<?php echo $datahight; ?>"
			data-tabs="<?php echo $datatimeline.','.$dataevents.','.$datamessages; ?>"
			data-hide-cover="<?php echo $datahidecover; ?>" 
			data-show-facepile="<?php echo $datashowfacepile; ?>"
			data-hide-cta="<?php echo $datahidecta; ?>"
			data-small-header="<?php echo $datasmallheader; ?>" 
			data-adapt-container-width="<?php echo $dataadaptcontainerwidth; ?>" 
		>
    	</div>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

        <?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Facebook Like Box', 'woosocio' );
		$WsFBURL = ! empty( $instance['WsFBURL'] ) ? $instance['WsFBURL'] : '';
		$datawidth = ! empty( $instance['datawidth'] ) ? $instance['datawidth'] : '';
		$datahight = ! empty( $instance['datahight'] ) ? $instance['datahight'] : '';
		$datatimeline = ! empty( $instance['datatimeline'] ) ? $instance['datatimeline'] : '';
		$dataevents = ! empty( $instance['dataevents'] ) ? $instance['dataevents'] : '';
		$datamessages = ! empty( $instance['datamessages'] ) ? $instance['datamessages'] : '';
		$datahidecover = ! empty( $instance['datahidecover'] ) ? $instance['datahidecover'] : '';
		$datashowfacepile = ! empty( $instance['datashowfacepile'] ) ? $instance['datashowfacepile'] : '';
		$datahidecta = ! empty( $instance['datahidecta'] ) ? $instance['datahidecta'] : '';
		$datasmallheader = ! empty( $instance['datasmallheader'] ) ? $instance['datasmallheader'] : '';
		$dataadaptcontainerwidth = ! empty( $instance['dataadaptcontainerwidth'] ) ? $instance['dataadaptcontainerwidth'] : '';
		?>
		
        <p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ), 'woosocio' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        
		<p>
		<label for="<?php echo $this->get_field_id( 'WsFBURL' ); ?>"><?php _e( 'Facebook Page URL', 'woosocio' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'WsFBURL' ); ?>" name="<?php echo $this->get_field_name( 'WsFBURL' ); ?>" type="text" value="<?php echo esc_attr( $WsFBURL ); ?>" placeholder="https://www.facebook.com/WooSocio">
		</p>
		
		<p>
        <?php _e( 'Show tabs:', 'woosocio' ); ?>
        </br>
		
		<input 
			id="<?php echo $this->get_field_id('datatimeline'); ?>" 
			name="<?php echo $this->get_field_name('datatimeline'); ?>" 
			type="checkbox" <?php checked(isset($instance['datatimeline'])&&$instance['datatimeline']=='timeline' ? 1 : 0); ?> 
			value="timeline"/>
		<label for="<?php echo $this->get_field_id( 'datatimeline' ); ?>"><?php _e( 'Timeline', 'woosocio' ); ?></label>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input 
			id="<?php echo $this->get_field_id( 'dataevents' ); ?>"
			name="<?php echo $this->get_field_name('dataevents'); ?>" 
			type="checkbox" <?php checked(isset($instance['dataevents'])&&$instance['dataevents']=='events' ? 1 : 0); ?> 
			value="events" 
		/>
		<label for="<?php echo $this->get_field_id( 'dataevents' ); ?>"><?php _e( 'Events', 'woosocio' ); ?></label>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input 
			id="<?php echo $this->get_field_id( 'datamessages' ); ?>"
			name="<?php echo $this->get_field_name('datamessages'); ?>" 
			type="checkbox" <?php checked(isset($instance['datamessages'])&&$instance['datamessages']=='messages' ? 1 : 0); ?> 
			value="messages" 
		/>
		<label for="<?php echo $this->get_field_id( 'datamessages' ); ?>"><?php _e( 'Messages', 'woosocio' ); ?></label>
        </p>

		<p>
		<label for="<?php echo $this->get_field_id( 'datawidth' ); ?>"><?php _e( 'Width', 'woosocio' ); ?></label>
		<input title="<?php _e( 'The pixel width of the embed (Min. 180 to Max. 500)', 'woosocio' ); ?>" id="<?php echo $this->get_field_id( 'datawidth' ); ?>" name="<?php echo $this->get_field_name( 'datawidth' ); ?>" 
        type="number" min="180" max="500" style="width:30%;" value="<?php echo esc_attr( $datawidth ); ?>">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="<?php echo $this->get_field_id( 'datahight' ); ?>"><?php _e( 'Height', 'woosocio' ); ?></label>
		<input title="<?php _e( 'The pixel height of the embed (Min. 70)', 'woosocio' ); ?>" id="<?php echo $this->get_field_id( 'datahight' ); ?>" name="<?php echo $this->get_field_name( 'datahight' ); ?>" 
        type="number" min="70" style="width:30%;" value="<?php echo esc_attr( $datahight ); ?>">
		</p>

		<p>
		<input 
			id="<?php echo $this->get_field_id( 'datasmallheader' ); ?>"
			name="<?php echo $this->get_field_name('datasmallheader'); ?>" 
			type="checkbox" <?php checked(isset($instance['datasmallheader'])&&$instance['datasmallheader']=='true' ? 1 : 0); ?> 
			value="true" 
		/>
		<label 
			title="<?php _e( 'Uses a smaller version of the page header', 'woosocio' ); ?>" 
			for="<?php echo $this->get_field_id( 'datasmallheader' ); ?>">
			<?php _e( 'Use Small Header', 'woosocio' ); ?>
		</label>
		</br>
		<input 
			id="<?php echo $this->get_field_id( 'dataadaptcontainerwidth' ); ?>"
			name="<?php echo $this->get_field_name('dataadaptcontainerwidth'); ?>" 
			type="checkbox" <?php checked(isset($instance['dataadaptcontainerwidth'])&&$instance['dataadaptcontainerwidth']=='true' ? 1 : 0); ?> 
			value="true" 
		/>
		<label 
			title="<?php _e( 'Plugin will try to fit inside the container', 'woosocio' ); ?>" 
			for="<?php echo $this->get_field_id( 'dataadaptcontainerwidth' ); ?>">
			<?php _e( 'Adapt to plugin container width', 'woosocio' ); ?>
		</label>
		</br>
		<input 
			id="<?php echo $this->get_field_id( 'datahidecover' ); ?>"
			name="<?php echo $this->get_field_name('datahidecover'); ?>" 
			type="checkbox" <?php checked(isset($instance['datahidecover'])&&$instance['datahidecover']=='true' ? 1 : 0); ?> 
			value="true" 
		/>
		<label 
			title="<?php _e( 'Hide the cover photo in the header', 'woosocio' ); ?>" 
			for="<?php echo $this->get_field_id( 'datahidecover' ); ?>">
			<?php _e( 'Hide Cover Photo', 'woosocio' ); ?>
		</label>
		</br>
		<input 
			id="<?php echo $this->get_field_id( 'datashowfacepile' ); ?>"
			name="<?php echo $this->get_field_name('datashowfacepile'); ?>" 
			type="checkbox" <?php checked(isset($instance['datashowfacepile'])&&$instance['datashowfacepile']=='true' ? 1 : 0); ?> 
			value="true" 
		/>
		<label 
			title="<?php _e( 'Show profile photos when friends like this', 'woosocio' ); ?>" 
			for="<?php echo $this->get_field_id( 'datashowfacepile' ); ?>">
			<?php _e( "Show Friend's Faces", 'woosocio' ); ?>
		</label>
        </p>

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['WsFBURL'] = ( ! empty( $new_instance['WsFBURL'] ) ) ? strip_tags( $new_instance['WsFBURL'] ) : '';
		$instance['datawidth'] = ( ! empty( $new_instance['datawidth'] ) ) ? $new_instance['datawidth']  : '';
		$instance['datahight'] = ( ! empty( $new_instance['datahight'] ) ) ? strip_tags( $new_instance['datahight'] ) : '';
		//$instance['datatimeline'] = ( ! empty( $new_instance['datatimeline'] ) ) ? strip_tags( $new_instance['datatimeline'] ) : '';
		$instance['datatimeline'] = ( ! empty( $new_instance['datatimeline'] ) ) ? 'timeline' : 'false';
		$instance['dataevents'] = ( ! empty( $new_instance['dataevents'] ) ) ? strip_tags( $new_instance['dataevents'] ) : '';
		$instance['datamessages'] = ( ! empty( $new_instance['datamessages'] ) ) ? strip_tags( $new_instance['datamessages'] ) : '';
		$instance['datahidecover'] = ( ! empty( $new_instance['datahidecover'] ) ) ? strip_tags( $new_instance['datahidecover'] ) : '';
		$instance['datashowfacepile'] = ( ! empty( $new_instance['datashowfacepile'] ) ) ? strip_tags( $new_instance['datashowfacepile'] ) : '';
		$instance['datahidecta'] = ( ! empty( $new_instance['datahidecta'] ) ) ? strip_tags( $new_instance['datahidecta'] ) : '';
		$instance['datasmallheader'] = ( ! empty( $new_instance['datasmallheader'] ) ) ? strip_tags( $new_instance['datasmallheader'] ) : '';
		$instance['dataadaptcontainerwidth'] = ( ! empty( $new_instance['dataadaptcontainerwidth'] ) ) ? strip_tags( $new_instance['dataadaptcontainerwidth'] ) : '';

		return $instance;
	}
} // class Foo_Widget
?>