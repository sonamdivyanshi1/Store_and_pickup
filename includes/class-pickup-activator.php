<?php

/**
 * Fired during plugin activation
 *
 * @link       https://sonam.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Pickup
 * @subpackage Pickup/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pickup
 * @subpackage Pickup/includes
 * @author     Sonam Divyanshi <sonam.divyanshi@wisdmlabs.com>
 */
class Pickup_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$now = time();
		$scheduled_time = strtotime('23:59:00', $now); 
		if (!wp_next_scheduled('my_daily_remainder')) {
			wp_schedule_event($scheduled_time, 'daily', 'my_daily_remainder');
		}
	}

}
