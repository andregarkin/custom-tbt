<?php

/**
 * The settings of the plugin.
 *
 */


class Custom_Tbt_Admin_Settings {

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

	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'Custom TBT' menu.
	 */
	public function setup_plugin_options_menu() {

		//Add the menu to the Plugins set of menu items
		add_plugins_page(
			'Custom TBT Options',                  // The title to be displayed in the browser window for this page.
			'Custom TBT Options',                  // The text to be displayed for this menu item
			'manage_options',                      // Which type of users can see this menu item
			$this->plugin_name . '_options',      // The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content')   // The name of the function to call when rendering this menu's page
		);

	}


	public function default_contacts_options() {

	    $defaults = array(
	        'link_email'      => '<a target="_blank" href="mailto:info@gms-worldwide.com">info@gms-worldwide.com</a>',
	        'link_messenger'  => '<a target="_blank" href="viber://add?number=%2B41415446200">Viber us</a>',
	        'link_form'       => '<a target="_blank" href="#">Contact us</a>',
	        'link_phone'      => '+41 41 544 62 00',
        );

		/*$defaults = array(
			'link_email'      => '<a href="https://somedomain.com/">default with \'Single Quotes\'</a>',
			'link_messenger'  => '',
			'link_form'       => '',
			'link_phone'      => '+41 41 544 62 00',
		);*/

	    return $defaults;
    }


	/**
	 * Provides default values for the Input Options.
	 *
	 * @return array
	 */
	public function default_input_options() {

		$defaults = array(
			'input_example'		=>	'default input example',
			'textarea_example'	=>	'',
			'checkbox_example'	=>	'',
			'radio_example'		=>	'2',
			'time_options'		=>	'default'
		);

		return $defaults;

	}

	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'Custom TBT Options', $this->plugin_name ); ?></h2>
			<?php settings_errors(); ?>

			<?php if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = $_GET[ 'tab' ];
			} else if( $active_tab == 'input_examples' ) {
				$active_tab = 'input_examples';
			} else {
				$active_tab = 'contacts_options';
			} // end if/else ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php echo $this->plugin_name . '_options'; // was: wppb_demo_options ?>&tab=contacts_options" class="nav-tab <?php echo $active_tab == 'contacts_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Contacts Data', ''. $this->plugin_name ); ?></a>
				<a href="?page=<?php echo $this->plugin_name . '_options'; // was: wppb_demo_options ?>&tab=input_examples" class="nav-tab <?php echo $active_tab == 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Input Examples', ''. $this->plugin_name ); ?></a>
			</h2>

			<form method="post" action="options.php">
				<?php

				if( $active_tab == 'contacts_options' ) {

					settings_fields( $this->plugin_name . '_contacts_options' );
					do_settings_sections( $this->plugin_name . '_contacts_options' );

				} else {

					settings_fields( $this->plugin_name . '_input_examples' );
					do_settings_sections( $this->plugin_name . '_input_examples' );

				} // end if/else

				submit_button();

				?>
			</form>

		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * This function provides a simple description for the Input Examples page.
	 *
	 * It's called from the 'initialize_input_examples' function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function input_examples_callback() {
		$options = get_option($this->plugin_name . '_input_examples');
		echo '<pre>'; print_r($options); echo '</pre>';
		echo '<p>' . __( 'Provides examples of the five basic element types.', $this->plugin_name ) . '</p>';
	} // end general_options_callback

    public function contacts_options_callback() {
	    $options = get_option($this->plugin_name . '_contacts_options');
	    //echo '<pre>'; var_dump($options); echo '</pre>';
	    echo '<pre>'; print_r($options); echo '</pre>';
	    echo '<p>' . __( 'Provide the URL to the contacts you\'d like to display.', $this->plugin_name ) . '</p>';
    }

	/**
	 * Initializes the theme's input example by registering the Sections,
	 * Fields, and Settings. This particular group of options is used to demonstration
	 * validation and sanitization.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_input_examples() {
		//delete_option($this->plugin_name . '_input_examples');
		if( false == get_option( $this->plugin_name . '_input_examples' ) ) {
			$default_array = $this->default_input_options();
			update_option( $this->plugin_name . '_input_examples', $default_array );
		} // end if

		add_settings_section(
			'input_examples_section',
			__( 'Input Examples', $this->plugin_name ),
			array( $this, 'input_examples_callback'),
			$this->plugin_name . '_input_examples'
		);

		add_settings_field(
			'Input Element',
			__( 'Input Element', $this->plugin_name ),
			array( $this, 'input_element_callback'),
			$this->plugin_name . '_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Textarea Element',
			__( 'Textarea Element', $this->plugin_name ),
			array( $this, 'textarea_element_callback'),
			$this->plugin_name . '_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Checkbox Element',
			__( 'Checkbox Element', $this->plugin_name ),
			array( $this, 'checkbox_element_callback'),
			$this->plugin_name . '_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Radio Button Elements',
			__( 'Radio Button Elements', $this->plugin_name ),
			array( $this, 'radio_element_callback'),
			$this->plugin_name . '_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Select Element',
			__( 'Select Element', $this->plugin_name ),
			array( $this, 'select_element_callback'),
			$this->plugin_name . '_input_examples',
			'input_examples_section'
		);

		register_setting(
			$this->plugin_name . '_input_examples',
			$this->plugin_name . '_input_examples',
			array( $this, 'validate_input_examples')
		);

	}


	// This function is registered with the 'admin_init' hook.
	public function initialize_contacts_options() {
		delete_option($this->plugin_name . '_contacts_options');

		if( false == get_option( $this->plugin_name . '_contacts_options' ) ) {
			$default_array = $this->default_contacts_options();
			update_option( $this->plugin_name . '_contacts_options', $this->validate_contacts_options($default_array) );
		}

		add_settings_section(
			'contacts_options_section',                   // id
			__('Contacts Data', $this->plugin_name),         // title
			array( $this, 'contacts_options_callback' ),     // cb
			$this->plugin_name . '_contacts_options'   // page
		);

		add_settings_field(
			'link_email',                                    // id
			__( 'Link to email', $this->plugin_name ),          // title
			array( $this, 'link_email_callback'),               // cb
			$this->plugin_name . '_contacts_options',     // page
			'contacts_options_section'                   // section
		);

		add_settings_field(
			'link_messenger',
			__( 'Link to messenger', $this->plugin_name ),
			array( $this, 'link_messenger_callback'),
			$this->plugin_name . '_contacts_options',
			'contacts_options_section'
		);

		add_settings_field(
			'link_form',
			__( 'Link to form', $this->plugin_name ),
			array( $this, 'link_form_callback'),
			$this->plugin_name . '_contacts_options',
			'contacts_options_section'
		);

		add_settings_field(
			'link_phone',
			__( 'Link to phone', $this->plugin_name ),
			array( $this, 'link_phone_callback'),
			$this->plugin_name . '_contacts_options',
			'contacts_options_section'
		);

		register_setting(
			$this->plugin_name . '_contacts_options',
			$this->plugin_name . '_contacts_options',
		    array( $this, 'validate_contacts_options')
		);

	}


	public function input_element_callback() {

		$options = get_option( $this->plugin_name . '_input_examples' );

		// Render the output
		echo '<input type="text" id="input_example" name="' . $this->plugin_name . '_input_examples[input_example]" value="' . $options['input_example'] . '" >';

	} // end input_element_callback

	public function textarea_element_callback() {

		$options = get_option( $this->plugin_name . '_input_examples' );

		// Render the output
		echo '<textarea id="textarea_example" name="' . $this->plugin_name . '_input_examples[textarea_example]" rows="5" cols="50">' . $options['textarea_example'] . '</textarea>';

	} // end textarea_element_callback

	public function checkbox_element_callback() {

		$options = get_option( $this->plugin_name . '_input_examples' );

		$html = '<input type="hidden"    id="checkbox_example" name="' . $this->plugin_name . '_input_examples[checkbox_example]" value="0" >';
		$html .= '<input type="checkbox" id="checkbox_example" name="' . $this->plugin_name . '_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '>';
		$html .= '&nbsp;';
		$html .= '<label for="checkbox_example">This is an example of a checkbox</label>';

		echo $html;

	} // end checkbox_element_callback

	public function radio_element_callback() {

		$options = get_option( $this->plugin_name . '_input_examples' );

		$html = '<input type="radio" id="radio_example_one" name="' . $this->plugin_name . '_input_examples[radio_example]" value="1"' . checked( 1, $options['radio_example'], false ) . '>';
		$html .= '&nbsp;';
		$html .= '<label for="radio_example_one">Option One</label>';
		$html .= '&nbsp;';
		$html .= '<input type="radio" id="radio_example_two" name="' . $this->plugin_name . '_input_examples[radio_example]" value="2"' . checked( 2, $options['radio_example'], false ) . '>';
		$html .= '&nbsp;';
		$html .= '<label for="radio_example_two">Option Two</label>';

		echo $html;

	} // end radio_element_callback

	public function select_element_callback() {

		$options = get_option( $this->plugin_name . '_input_examples' );

		$html = '<select id="time_options" name="' . $this->plugin_name . '_input_examples[time_options]">';
		$html .= '<option value="default">' . __( 'Select a time option...', $this->plugin_name ) . '</option>';
		$html .= '<option value="never"' . selected( $options['time_options'], 'never', false) . '>' . __( 'Never', $this->plugin_name ) . '</option>';
		$html .= '<option value="sometimes"' . selected( $options['time_options'], 'sometimes', false) . '>' . __( 'Sometimes', $this->plugin_name ) . '</option>';
		$html .= '<option value="always"' . selected( $options['time_options'], 'always', false) . '>' . __( 'Always', $this->plugin_name ) . '</option>';
		$html .= '</select>';

		echo $html;

	} // end select_element_callback


	public function link_email_callback() {

		$options = get_option( $this->plugin_name . '_contacts_options' );
		// Render the output
		echo '<input type="text" id="link_email" name="' . $this->plugin_name . '_contacts_options[link_email]" value="' . $options['link_email'] . '" size="100" >';
	} // end input_element_callback

    public function link_messenger_callback() {

		$options = get_option( $this->plugin_name . '_contacts_options' );
		// Render the output
		echo '<input type="text" id="link_messenger" name="' . $this->plugin_name . '_contacts_options[link_messenger]" value="' . $options['link_messenger'] . '" size="100" >';
	} // end input_element_callback

	public function link_form_callback() {

		$options = get_option( $this->plugin_name . '_contacts_options' );
		// Render the output
		echo '<input type="text" id="link_form" name="' . $this->plugin_name . '_contacts_options[link_form]" value="' . $options['link_form'] . '" size="100" >';
	} // end input_element_callback

	public function link_phone_callback() {

		$options = get_option( $this->plugin_name . '_contacts_options' );
		// Render the output
		echo '<input type="text" id="link_phone" name="' . $this->plugin_name . '_contacts_options[link_phone]" value="' . $options['link_phone'] . '" size="100" >';
	} // end input_element_callback


	public function validate_input_examples( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );

			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'validate_input_examples', $output, $input );

	} // end validate_input_examples

    public function validate_contacts_options( $input ) {
	    // Create our array for storing the validated options
	    $output = array();

	    // Loop through each of the incoming options
	    foreach( $input as $key => $value ) {

		    // Check to see if the current option has a value. If so, process it.
		    if( isset( $input[$key] ) ) {

			    // convert all HTML
			    $output[$key] = htmlentities( $input[ $key ], ENT_QUOTES | ENT_XML1, 'UTF-8');

		    } // end if

	    } // end foreach

	    // Return the array processing any additional functions filtered by this action
	    return apply_filters( 'validate_input_examples', $output, $input );

    } // end validate_input_examples



}