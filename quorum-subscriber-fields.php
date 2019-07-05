<?php
/**
 * Quorum Subscriber fields data.
 *
 * @package       GravityForms
 * @subpackage    GF_Quorum_AddOn
 */

// Complete list of every property a Subscriber can have in Quorum.
$subscriber_fields = [
	'unsubscribed_from_bulk_emails',
	'phone_number',
	'address_geocoded',
	'suffix',
	'unique_identifier',
	'utm_content',
	'valid_email',
	'fb_id',
	'state_name',
	'preferred_name',
	'prefix',
	'postal_code',
	'congressional_district',
	'last_user',
	'id',
	'secondary_phone',
	'postal_code_ext',
	'utm_term',
	'data_hash',
	'http_referer',
	'title',
	'state',
	'date_unsubscribed_from_bulk_emails',
	'waiting_to_be_created_by_user',
	'bulk_email_unsubscribe_key',
	'country_name',
	'utm_medium',
	'bulk_upload_file',
	'lastname',
	'input_address',
	'date_unsubscribed_from_texting',
	'email',
	'website',
	'updated',
	'secondary_extension',
	'supporter_type',
	'firstname',
	'utm_campaign',
	'middlename',
	'activated',
	'city',
	'archived',
	'utm_source',
	'source_type',
	'upper_ld',
	'user',
	'address',
	'active',
	'lower_ld',
	'county_name',
	'public_organization',
	'county',
	'name',
	'extension',
	'created',
	'secondary_email',
	'country',
	'region',
	'is_recently_active_email_recipient',
	'alternate_address',
	'unsubscribed_from_texting',
	'image_url',
	'organization',
	'municipality',
	'street_address',
];

// Hand-picked set of available fields, to be offered up for mapping.
$subscriber_fields_mappable = [
	'email',
	'prefix',
	'firstname',
	'middlename',
	'lastname',
	'preferred_name',
	'suffix',
	'phone_number',
	'street_address',
	'city',
	'state_name',
	'postal_code',
	'country_name',
	'congressional_district',
];

/**
 * Convert a field name to human-readable.
 *
 * This is here instead of using slug->human key/value assoc arrays in $subscriber_fields and
 * $subscriber_fields_mappable because it would be tedious to manually enter all those combos.
 *
 * @param string $field_slug The machine name to convert.
 *
 * @return string
 */
function gravityforms_quorum_addon_fieldname_humanize( $field_slug ) {
	// Replace underscores with spaces, then title case each word.
	return ucwords( str_replace( '_', ' ', $field_slug ) );
}
