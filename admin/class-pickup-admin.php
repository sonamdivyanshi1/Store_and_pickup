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

}
