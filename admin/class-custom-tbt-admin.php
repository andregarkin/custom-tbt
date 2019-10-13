<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wppb.me/
 * @since      1.0.0
 *
 * @package    Custom_Tbt
 * @subpackage Custom_Tbt/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Tbt
 * @subpackage Custom_Tbt/admin
 * @author     Andriy <andregarkin@gmail.com>
 */
class Custom_Tbt_Admin {

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

	private $my_plugin_options;

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

		$this->load_dependencies();

		$this->my_plugin_options = get_option($this->plugin_name);

	}

	/**
	 * Load the required dependencies for the Admin facing functionality.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Custom_Tbt_Admin_Settings. Registers the admin settings and page.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-custom-tbt-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/widgets/class-custom-tbt-widget-contact.php';

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
		 * defined in Custom_Tbt_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Tbt_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-tbt-admin.css', array(), $this->version, 'all' );

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
		 * defined in Custom_Tbt_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Tbt_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-tbt-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * @param $wp_admin_bar
	 */
	public function add_plugins_link_to_admin_toolbar( $wp_admin_bar ) {

		$args = array(
			'id'    => 'plugins',
			'title' => 'Plugins',
			'href'  => admin_url('plugins.php'),
			'parent'=> 'appearance',
		);
		$wp_admin_bar->add_node( $args );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		*/
		add_options_page( 'Custom TBT plugin settings',
			'Custom TBT',
			'manage_options',
			''. $this->plugin_name,
			array($this, 'display_plugin_setup_page')
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function add_action_links( $links ) {

		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function display_plugin_setup_page() {

		include_once( 'partials/custom-tbt-admin-display.php' );

	}

	/**
	 * Validate options
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public function validate($input) {
		$valid = array();
		$valid['footer_text'] = (isset($input['footer_text']) && !empty($input['footer_text'])) ? $input['footer_text'] : '';
		return $valid;
	}

	/**
	 * Update all options
	 */
	public function options_update() {
		register_setting(
			''. $this->plugin_name,
			''. $this->plugin_name,
			array($this, 'validate')
		);
	}

	/*public function register_custom_tbt_widget_contact() {
		register_widget('Custom_Tbt_Widget_Contact');
	}*/
}
