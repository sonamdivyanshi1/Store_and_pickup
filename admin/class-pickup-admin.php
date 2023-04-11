<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://sonam.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Pickup
 * @subpackage Pickup/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pickup
 * @subpackage Pickup/admin
 * @author     Sonam Divyanshi <sonam.divyanshi@wisdmlabs.com>
 */
class Pickup_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		//require_once(plugin_dir_url(__FILE__).'partials/pickup-admin-display.php');
		//require_once("C:\Users\wwwso\Local Sites\wordpress3\app\public\wp-content\plugins\pickup\admin\partials\pickup-admin-display.php");
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pickup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pickup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pickup-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pickup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pickup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pickup-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_store_custom_post_type(){
		register_post_type(
			'store',
			array(
				'labels' => array(
					'name' => __('Stores'),
					'singular_name' => __('Store')
				),
				'public' => true,
				'has_archive' => true,
				'supports' => array('author'),
				'menu_icon' => 'dashicons-store',
			)
		);
	}

	public function add_store_meta_box(){
		add_meta_box(
			'store_information',
			__('Store Information', 'pickup'),
			array($this,'store_information'),
			'store',
			'normal',
			'high'
		);
	}

	public function store_information($post)
	{
		//my_form();
		$name = get_post_meta($post->ID, '_name', true);
		$address = get_post_meta($post->ID, '_address', true);
		$contact = get_post_meta($post->ID, '_contact', true);

		wp_nonce_field('store_information', 'store_information_nonce');
?>
		<table class="form-table">
		<tbody>
				<tr>
					<th><label for="name"><?php _e('Store Name', 'pickup'); ?></label></th>
					<td><input type="text" id="name" name="name" value="<?php echo esc_attr($name); ?>"></td>
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

	public function save_meta_boxes($post_id)
	{
		if (!isset($_POST['store_information_nonce']) ||
		 !wp_verify_nonce($_POST['store_information_nonce'], 'store_information')) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (isset($_POST['post_type']) && 'store' == $_POST['post_type']) {
			if (current_user_can('edit_post', $post_id)) {
				if (isset($_POST['store_name'])) {
					update_post_meta($post_id, '_name', sanitize_text_field($_POST['name']));
				}
				if (isset($_POST['store_address'])) {
					update_post_meta($post_id, '_address', sanitize_text_field($_POST['address']));
				}
				if (isset($_POST['contact_info'])) {
					update_post_meta($post_id, '_contact', sanitize_textarea_field($_POST['contact']));
				}
			}
		}
	}

	public function add_store_list_columns($columns)
	{
		$columns['name'] = __('Store Name', 'pickup');
		$columns['address'] = __('Address', 'pickup');
		$columns['contact'] = __('Contact', 'pickup');
		return $columns;
	}

	public function display_store_list_columns($column, $post_id)
	{
		switch ($column) {
			case 'store_name':
				echo get_post_meta($post_id, '_name', true);
				break;
			case 'store_address':
				echo get_post_meta($post_id, '_address', true);
				break;
			case 'contact_info':
				echo get_post_meta($post_id, '_contact', true);
				break;
		}
	}

	public function send_confirmation_mail(){
		if(isset($_POST['pickup_date']) && !empty($_POST['pickup_date'])){
			$pickup_date = sanitize_text_field($_POST['pickup_date']);
		}

		if(isset($_POST['store_options']) && !empty($_POST['store_options'])) {
			$selected_store = sanitize_text_field($_POST['store_options']);
		}

		$mail = '<h3>Store Pickup Details</h3>';
		$date = date('d-m-y', strtotime($pickup_date));
		$mail .= "Pickup Date: $date".'<br>';
		$mail .= "selected store : $selected_store".'<br>';
		return $mail;
	}

	public function save_order($order){
		if (isset($_POST['pickup_date']) && isset($_POST['store_options'])) {
			
			$pickup_date = sanitize_text_field($_POST['pickup_date']);
			$selected_store = sanitize_text_field($_POST['store_options']);
			$order->update_meta_data( 'pickup_id', $pickup_date );
			$order->update_meta_data( 'store_id', $selected_store );
	
		}	
	}

	public function send_pickup_reminder_emails() {
		$args = array(
			'post_type' => 'shop_order',
			'posts_per_page' => '-1',
			'post_status' => 'any'
		  );
	  
		$query = new WP_Query($args);
		$posts = $query->posts;

		// Calculate next day
		$next_day = strtotime( '+1 day', current_time( 'timestamp' ) );
		//$next_day_date = date( 'Y-m-d', $next_day );
	
		foreach ( $posts as $post ) {
			$order_id = $post->ID;
			$this->send_pickup_reminder_email($order_id);
		}
	}

	//To send remainder mail
	public function send_pickup_reminder_email($order_id) {
		$order = wc_get_order( $order_id );
		$pickup_date = $order->get_meta('pickup_id');
		$selected_store = $order->get_meta('store_id');
		$customer_email = $order->get_billing_email();
		
		// Check if pickup date is exactly one day away from today
		$pickup_timestamp = strtotime($pickup_date);
		$one_day_before_pickup_timestamp = strtotime('-1 day', $pickup_timestamp);
		if (date('Y-m-d', $one_day_before_pickup_timestamp) === date('Y-m-d')) {
			// Send reminder email
			$subject = 'Reminder: Store Pickup Tomorrow';
			$message = "<p>Dear customer,</p>";
			$message .= "<p>This is a reminder that your order is ready for pickup and 
			you have a store pickup scheduled for tomorrow at the following location:</p>";
			$message .= "<p>Selected Store: $selected_store</p>";
			$date = date('d-m-Y', strtotime($pickup_date));
			$message .= "<p>Pickup Date: $date</p>";
			$message .= "<p>Thank you for choosing our store. We look forward to seeing you again.</p>";
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail($customer_email, $subject, $message, $headers);
		}

	}

}
