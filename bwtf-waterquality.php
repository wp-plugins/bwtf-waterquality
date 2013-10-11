<?php
/**
 * Plugin Name: BWTF Water Quality
 * Description: Provides a Widget to display water quality test resuts from Surfrider Foundation's Blue Water Task Force program
 * Version: 0.96
 * Author: Christopher Wilson (cwilson@surfrider.org)
 * License: GPL2
 */

class WQWidget extends WP_Widget {

	function WQWidget() {
		// Instantiate the parent object
		parent::__construct( false, 'Local Water Quality' );
	}

	// widget form creation
	function form($instance) {

	// Check values
	if( $instance) {
	     $siteid = esc_attr($instance['siteid']);
	} else {
     	$siteid = '';
	}
	?>

	<p>
	<label for="<?php echo $this->get_field_id('siteid'); ?>"><?php _e('Site ID to display quality from:', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('siteid'); ?>" name="<?php echo $this->get_field_name('siteid'); ?>" type="text" value="<?php echo $siteid; ?>" />
	</p>
	<p>
	To find your testing site ID, ask your local chapter or find a local beach at the <a href="http://www.surfrider.org/blue-water-task-force" target="_blank">Blue Water Task Force Website</a>. The site ID will be the last section of the URL for the beach. For example, if the URL for the beach is http://www.surfrider.org/blue-water-task-force/beach/359, the Site ID is '359'.
	</p>
	<?php
	}

	function update($new_instance, $old_instance) {
	      $instance = $old_instance;
	      // Fields
	      $instance['siteid'] = strip_tags($new_instance['siteid']);
     	return $instance;
	}


	function widget( $args, $instance ) {
		$contenturl = 'wq.php?site=' . $instance['siteid'];
		// Widget output
		echo '<div class="widget"><h3>Latest Local Water Quality</h3>
		<a href="http://www.surfrider.org/blue-water-task-force"><img src="' . plugins_url( 'bwtf.jpg' , __FILE__  ) . '" style="padding:5px;" align="left" /></a>
		<SCRIPT LANGUAGE= "JavaScript">
		function httpGet(theUrl)
    		{
    			var xmlHttp = null;
    			xmlHttp = new XMLHttpRequest();
    			xmlHttp.open( "GET", theUrl, false );
    			xmlHttp.send( null );
    			return xmlHttp.responseText;
    		}
		theText = httpGet("' . plugins_url( $contenturl , __FILE__ ) . '");
		document.write(theText);
		</SCRIPT>
		</div>';

	}
}

function waterquality_register_widgets() {
	register_widget( 'WQWidget' );
}

add_action( 'widgets_init', 'waterquality_register_widgets' );

