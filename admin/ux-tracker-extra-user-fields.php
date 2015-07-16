<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<h3>UX Tracker User Information</h3>

<table class="form-table">
	<tr>
		<th><label for="ux_tracker_user_identifier">Unique Identifier</label></th>
		<td>
		<?php if (isset($user->ID)) { ?>
			<input type="text" name="ux_tracker_user_identifier" id="ux_tracker_user_identifier" value="<?php echo esc_attr( the_author_meta( 'ux_tracker_user_identifier' , $user->ID) ); ?>" class="regular-text" /><br />
		<?php } else { ?>
			<input type="text" name="ux_tracker_user_identifier" id="ux_tracker_user_identifier" value="" class="regular-text" /><br />
		<?php } ?>
			<span class="description">Please enter Users Unique Identifier.</span>
		</td>
	</tr>
</table>