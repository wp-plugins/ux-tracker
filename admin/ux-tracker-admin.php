<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">

	<h2>UX Tracker Settings</h2>

	<form method="post" action="options.php">

	    <?php settings_fields( 'ux-tracker-settings-group' ); ?>
	    <?php do_settings_sections( 'ux-tracker-settings-group' ); ?>

	    <table class="form-table">
	        <tr valign="top">
		        <th scope="row">Google Analytics Tracking ID:</th>
		        <td>
			        <input type="text" name="ux_tracker_analytics_id" placeholder="Analytics tracking ID" value="<?php echo esc_attr( get_option('ux_tracker_analytics_id') ); ?>" />
					<br/><br/>
					<p>You must create these two custom dimensions in your <a href="https://www.google.com/analytics" target="_blank">Google Analytics</a> Profile:</p>
					<ul>
						<li><strong>Custom Dimension 1:</strong> User ID, user level</li>
						<li><strong>Custom Dimension 2:</strong> Unique Identifier, user level</li>
					</ul>
					<img src="<?php echo plugins_url( 'img/custom-dimensions.png', __FILE__ ); ?>" alt="">
					<br/>
					<br>
					<p>If you are already utilising custom dimensions you can manually set the indexes below.</p>
					<p>Leave these blank if you wish to use default values.</p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">User ID Index</th>
							<td><input type="text" id="ux_tracker_analytics_custom_dim_1" name="ux_tracker_analytics_custom_dim_1" value="<?php echo esc_attr( get_option('ux_tracker_analytics_custom_dim_1') ); ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row">Unique Identifier Index</th>
							<td><input type="text" id="ux_tracker_analytics_custom_dim_2" name="ux_tracker_analytics_custom_dim_2" value="<?php echo esc_attr( get_option('ux_tracker_analytics_custom_dim_2') ); ?>" /></td>
						</tr>
					</table>
					<br/>
					<p>You must also enabled '<strong>Enhanced Link Attribution</strong>' in your <a href="https://www.google.com/analytics" target="_blank">Google Analytics</a> profile for the best experience.</p>
		        </td>
	        </tr>
	        <tr valign="top">
		        <th scope="row">Inspectlet Tracking ID:</th>
		        <td>
		        	<input type="text" name="ux_tracker_inspectlet_id" placeholder="Inspectlet tracking ID" value="<?php echo esc_attr( get_option('ux_tracker_inspectlet_id') ); ?>" />
					<br><br>
					<img src="<?php echo plugins_url( 'img/inspectlet-tracking-code.jpg', __FILE__ ); ?>" alt="">
					<p><a href="http://www.inspectlet.com/?u=ux-tracker" target="_blank">Inspectlet</a> records videos of your visitors as they use your site, allowing you to see everything they do. See every mouse movement, scroll, click, and keypress on your site.</p>
		        	<p>To use Inspectlet you must first <a href="http://www.inspectlet.com/?u=ux-tracker" target="_blank">create an account</a></p>
		        </td>
	        </tr>
	        <tr valign="top">
		        <th scope="row">Scroll Depth Tracking:</th>
		        <td>
		        	<label for="ux_tracker_scroll_depth"><input name="ux_tracker_scroll_depth" id="ux_tracker_scroll_depth" type="checkbox" value="1" <?php checked( '1', get_option( 'ux_tracker_scroll_depth' ) ); ?> /> Enable</label>
					<br><br>
					<p>This will track a users scroll depth using google analytics events. You must have a Google Analytics tracking ID set.</p>
					<p>These are reported as Google Analytics events. </p>
		        </td>
	        </tr>
	        <tr valign="top">
		        <th scope="row">TrackEverything Events:</th>
		        <td>
		        	<label for="ux_tracker_track_everything"></label><input name="ux_tracker_track_everything" id="ux_tracker_track_everything" type="checkbox" value="1" <?php checked( '1', get_option( 'ux_tracker_track_everything' ) ); ?> /> Enable</label>
					<br><br>
					<p>This will track a users interactions with most of your sites content including forms, external links, phone numbers, emails and anchor links.</p>
					<p>These are reported as Google Analytics events. </p>
		        </td>
	        </tr>
	    </table>
	    
	    <?php submit_button(); ?>

	</form>

</div>