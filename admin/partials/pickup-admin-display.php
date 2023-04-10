<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://sonam.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Pickup
 * @subpackage Pickup/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

function my_form()
{

?> 
        <table class="form-table">
			<tbody>
				<tr>
					<th><label for="name"><?php _e('Store Name', 'pickup'); ?></label></th>
					<td><input type="text" id="name" name="store_name" value="<?php echo esc_attr($name); ?>"></td>
				</tr>
				<tr>
					<th><label for="address"><?php _e('Address', 'pickup'); ?></label></th>
					<td><textarea id="address" name="address"><?php echo esc_textarea($address); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="contact"><?php _e('Contact', 'pickup'); ?></label></th>
					<td><input type="text" id="contact" name="contact" value="<?php echo esc_attr($contact); ?>"></td>
				</tr>

			</tbody>
		</table>

    <?php
}
?>