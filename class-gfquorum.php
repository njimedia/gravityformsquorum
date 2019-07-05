<?php
/**
 * Gravity_Forms Quorum Add-On Main Class
 *
 * @package       GravityForms
 * @subpackage    GravityForms_Quorum_AddOn
 */

// Deny direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Main Quorum add-on functionality.
 */
class GFQuorum extends GFFeedAddOn {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0
	 * @access private
	 * @var    GFQuorum $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Defines the version of the Quorum Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_version Contains the version, defined from gravityformsquorum.php
	 */
	protected $_version = GF_QUORUM_VERSION;

	/**
	 * Defines the minimum Gravity Forms version required.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_min_gravityforms_version The minimum version required.
	 */
	protected $_min_gravityforms_version = '2.2.3';

	/**
	 * Defines the plugin slug.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_slug The slug used for this plugin.
	 */
	protected $_slug = 'gravityformsquorum';

	/**
	 * Defines the main plugin file.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_path The path to the main plugin file, relative to the plugins folder.
	 */
	protected $_path = 'gravityformsquorum/gravityformsquorum.php';

	/**
	 * Defines the full path to this class file.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_full_path The full path.
	 */
	protected $_full_path = __FILE__;

	/**
	 * Defines the URL where this Add-On can be found.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string The URL of the Add-On.
	 */
	protected $_url = '';

	/**
	 * Defines the title of this Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_title The title of the Add-On.
	 */
	protected $_title = 'Gravity Forms Quorum Add-On';

	/**
	 * Defines the short title of the Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_short_title The short title.
	 */
	protected $_short_title = 'Quorum';

	/**
	 * Contains an instance of the Quorum API library, if available.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    QuorumAPI $api If available, contains an instance of the Quorum API library.
	 */
	public $api = null;

	/**
	 * Create or return existing instance.
	 */
	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new GFQuorum();
		}
		return self::$_instance;
	}

	/**
	 * Autoload the required libraries.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses GFAddOn::is_gravityforms_supported()
	 */
	public function pre_init() {

		parent::pre_init();

		if ( $this->is_gravityforms_supported() ) {
			// Load the Quorum API library.
			if ( ! class_exists( 'NJIMedia\Quorum\Client' ) ) {
				require_once __DIR__ . '/vendor/autoload.php';
			}
		}
	}

	/**
	 * Define custom settings fields for the Quorum add-on.
	 */
	public function plugin_settings_fields() {
		return [
			[
				'title'  => esc_html__( 'Quorum Add-On Settings', 'gravityformsquorum' ),
				'fields' => [
					[
						'name'              => 'quorum_production_api_key',
						'tooltip'           => esc_html__( 'Enter the API Key provided by Quorum', 'gravityformsquorum' ),
						'label'             => esc_html__( 'Quorum API Key', 'gravityformsquorum' ),
						'type'              => 'text',
						'class'             => 'medium',
						'feedback_callback' => [ $this, 'initialize_api' ],
					],
					[
						'name'              => 'quorum_production_api_username',
						'tooltip'           => esc_html__( 'Enter the API Username provided by Quorum', 'gravityformsquorum' ),
						'label'             => esc_html__( 'Quorum API Username', 'gravityformsquorum' ),
						'type'              => 'text',
						'class'             => 'small',
						'feedback_callback' => [ $this, 'initialize_api' ],
					],
				],
			],
		];
	}

	/**
	 * Configures which columns should be displayed on the feed list page.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function feed_list_columns() {

		return [
			'feedName' => esc_html__( 'Name', 'gravityformsquorum' ),
		];

	}

	/**
	 * Configures the settings which should be rendered on the feed edit page.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function feed_settings_fields() {
		return [
			[
				'title'  => esc_html__( 'Quorum Form Integration Settings', 'gravityformsquorum' ),
				'fields' => [
					[
						'name'     => 'feedName',
						'label'    => esc_html__( 'Name', 'gravityformsquorum' ),
						'type'     => 'text',
						'required' => true,
						'class'    => 'medium',
						'tooltip'  => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Name', 'gravityformsquorum' ),
							esc_html__( 'Give this integration configuration a name by which to identify it.', 'gravityformsquorum' )
						),
					],
				],
			],
			[
				'title'  => esc_html__( 'Quorum Contacts Field Mapping', 'gravityformsquorum' ),
				'fields' => [
					[
						'name'      => 'quorumContactFieldsStandard',
						'label'     => esc_html__( 'Field Map', 'gravityformsquorum' ),
						'type'      => 'field_map',
						'field_map' => $this->get_field_map(),
						'tooltip'   => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Field Map', 'gravityformsquorum' ),
							esc_html__( 'Connect your Gravity Forms web form fields to the corresponding Quorum Contact field', 'gravityformsquorum' )
						),
					],
				],
			],
			[
				'title'  => esc_html__( 'Quorum Contact Options', 'gravityformsquorum' ),
				'fields' => [
					[
						'name'    => 'quorumContactCustomFields',
						'label'   => esc_html__( 'Custom Fields', 'gravityformsquorum' ),
						'type'    => 'checkbox',
						'choices' => $this->get_custom_fields(),
					],
				],
			],
		];
	}

	/**
	 * Initializes Quorum API.
	 *
	 * @since  1.0
	 *
	 * @return bool|null
	 */
	public function initialize_api() {

		// If API is already initialized, return true.
		if ( ! is_null( $this->api ) ) {
			return true;
		}

		// Get the credentials to use with the API.
		$api_key      = $this->get_plugin_setting( 'quorum_production_api_key' );
		$api_username = $this->get_plugin_setting( 'quorum_production_api_username' );

		// Log validation step.
		$this->log_debug( __METHOD__ . '(): Validating Quorum API Info.' );

		// Try/catch client init, because if key/username aren't set yet, it throws error.
		try {
			// Setup a new Quorum API object with the credentials.
			$quorum = new NJIMedia\QuorumAPI\Client( $api_username, $api_key );
		} catch ( Exception $e ) {
			$this->log_debug( __METHOD__ . '(): Quorum client could not initialize:' );
			$this->log_debug( $e->getMessage() );
			return false;
		}

		if ( $quorum->validate() ) {

			// Assign API library to class.
			$this->api = $quorum;

			// Log that authentication test passed.
			$this->log_debug( __METHOD__ . '(): Quorum API is working.' );

			return true;

		} else {

			// Log that authentication test failed.
			$this->log_error( __METHOD__ . '(): The Quorum API is not working; ' );

			return false;
		}
	}

	/**
	 * Retreive from Quorum API any custom fields that have been defined.
	 *
	 * @since  1.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_custom_fields() {

		// Hold list of custom  fields.
		$custom_fields = [];

		// If unable to initialize API, return field map.
		if ( ! $this->initialize_api() ) {
			return $custom_fields;
		}

		// Call custom fields from the API.
		$response = json_decode( $this->api->getCustomTags()->getBody() );

		$custom_tags = $response->objects;

		// Loop thru the set of all custom tags from Quorum and convert them to the
		// appropriate GravityForms field definition.
		foreach ( $custom_tags as $tag ) {
			switch ( $tag->tag_type ) {
				case 'boolean':
					// Treat bools as checkboxes.
					array_push( $custom_fields, $this->create_field_checkbox( $tag ) );
					break;
				case 'single_option_list':
					// Todo: create a select or radio for option_lists.
					break;
			}
		}
		return $custom_fields;
	}

	/**
	 * Retreive from Quorum Contacts all the fields available to be mapped to Gravity Forms fields.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_field_map() {

		// Initialize field map array.
		$field_map = [];

		// Get mappable fields.
		// The service API doesn't reliably return just the subscriber fields: hardcoding it.
		// Requiring the file below creates the variable $subscriber_fields_mappable.
		require __DIR__ . '/quorum-subscriber-fields.php';
		// If no fields to map, return field map.
		if ( empty( $subscriber_fields_mappable ) ) {
			return $field_map;
		}

		foreach ( $subscriber_fields_mappable as $field_slug ) {
			// Define required field type.
			$field_type = null;

			// If this is an email merge field, set field types to "email" or "hidden".
			if ( 'email' === $field_slug ) {
				$field_type = [ 'email', 'hidden' ];
			}

			// If this is an address field, set field type to "address".
			if ( in_array( $field_slug, [ 'address', 'street_address' ], true ) ) {
				$field_type = [ 'address' ];
			}

			// Add to field map.
			$field_map[ $field_slug ] = [
				'name'       => $field_slug,
				'label'      => gravityforms_quorum_addon_fieldname_humanize( $field_slug ),
				'field_type' => $field_type,
			];
		}

		return $field_map;
	}

	/**
	 * Process the feed, add the user to the contacts.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $feed  The feed object to be processed.
	 * @param array $entry The entry object currently being processed.
	 * @param array $form  The form object currently being processed.
	 *
	 * @return array
	 */
	public function process_feed( $feed, $entry, $form ) {

		// Log that we are processing feed.
		$this->log_debug( __METHOD__ . '(): Processing feed.' );

		// If unable to initialize API, log error and return.
		if ( ! $this->initialize_api() ) {
			$this->add_feed_error( esc_html__( 'Unable to process feed because API could not be initialized.', 'gravityformsquorum' ), $feed, $entry, $form );
			return $entry;
		}

		// Establish API payload data array.
		$data = [];

		// Its a little complex to search through the submitted form object for which field or sub-field
		// (conditionally in form->field->inputs) might have been the one holding the passed value. More
		// efficient to check for a specific value by field name, iterating names of each field
		// with a mapped config, regardless of if this form actually implemented those fields.
		// Get all possible fields (by looking at all fields mapped in configuration).
		$quorum_contact_fields = $this->get_field_map_fields( $feed, 'quorumContactFieldsStandard' );
		foreach ( $quorum_contact_fields as $api_field_key => $mapped_field_value ) {
			// See if a value is in the submitted form, matching this mapped field name.
			$gravity_field_key = 'quorumContactFieldsStandard_' . $api_field_key;
			$submitted_value   = $this->get_field_value( $form, $entry, $feed['meta'][ $gravity_field_key ] );
			if ( ! empty( $submitted_value ) ) {
				// If theres a value, add it to the API data payload.
				$data[ $api_field_key ] = $submitted_value;
			}
		}

		// Add any custom fields set on the form options to the data payload.
		// The custom options fields are just mixed in randomly with the mapped fields.
		foreach ( $feed['meta'] as $key => $value ) {
			// Skip the feedName meta field.
			if ( 'feedName' === $key ) {
				continue;
			}
			// Skip all the mapped fields. They all start with the prefix 'quorumContact...'.
			if ( 0 === strpos( $key, 'quorumContactFieldsStandard_' ) ) {
				continue;
			}
			// Whatever else is in meta, try to save it as a custom tag by putting it into
			// tag_dict.
			// Quorum wants real bools: true/false, but GF has made them strings 1/0.
			if ( '1' === $value ) {
				$value = true;
			} elseif ( '0' === $value ) {
				$value = false;
			}
			$data['tag_dict'][ $key ] = $value;
		}

		// Now the data payload is ready, do the API call to create the supporter.
		$response = $this->api->createSupporter( $data );

		// Debug response.
		$this->log_debug( 'API Response: ' . $response->getStatusCode() . ' : ' . $response->getBody() );
		return $entry;
	}

	/**
	 * Create a checkbox field
	 *
	 * @param object $tag A Custom Tag object from Quorum.
	 *
	 * @return array A GravityForms field definition.
	 */
	protected function create_field_checkbox( $tag ) {
		return [
			'label'         => esc_html( $tag->name ),
			'name'          => $tag->slug,
			'default_value' => $tag->default_boolean_value,
		];
	}
}
