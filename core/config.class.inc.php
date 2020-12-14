<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 *
 *
 */


define('ITOP_APPLICATION', 'iTop');
define('ITOP_APPLICATION_SHORT', 'iTop');
define('ITOP_VERSION', '3.0.0-dev');
define('ITOP_REVISION', 'svn');
define('ITOP_BUILD_DATE', '$WCNOW$');
define('ITOP_VERSION_FULL', ITOP_VERSION.'-'.ITOP_REVISION);

define('ACCESS_USER_WRITE', 1);
define('ACCESS_ADMIN_WRITE', 2);
define('ACCESS_FULL', ACCESS_USER_WRITE | ACCESS_ADMIN_WRITE);
define('ACCESS_READONLY', 0);

/**
 * Configuration read/write
 *
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('coreexception.class.inc.php');
require_once('attributedef.class.inc.php'); // For the defines
require_once('simplecrypt.class.inc.php');

class ConfigException extends CoreException
{
}

// was utf8 but it only supports BMP chars (https://dev.mysql.com/doc/refman/5.5/en/charset-unicode-utf8mb4.html)
// so we switched to utf8mb4 in iTop 2.5, adding dependency to MySQL 5.5.3
// The config params db_character_set and db_collation were introduced as a temporary workaround and removed in iTop 2.5
// now everything uses those fixed value !
define('DEFAULT_CHARACTER_SET', 'utf8mb4');
define('DEFAULT_COLLATION', 'utf8mb4_unicode_ci');

define('DEFAULT_LOG_GLOBAL', true);
define('DEFAULT_LOG_NOTIFICATION', true);
define('DEFAULT_LOG_ISSUE', true);
define('DEFAULT_LOG_WEB_SERVICE', true);

define('DEFAULT_QUERY_CACHE_ENABLED', true);


define('DEFAULT_MIN_DISPLAY_LIMIT', 10);
define('DEFAULT_MAX_DISPLAY_LIMIT', 15);
define('DEFAULT_STANDARD_RELOAD_INTERVAL', 5 * 60);
define('DEFAULT_FAST_RELOAD_INTERVAL', 1 * 60);
define('DEFAULT_SECURE_CONNECTION_REQUIRED', false);
define('DEFAULT_ALLOWED_LOGIN_TYPES', 'form|external|basic');
define('DEFAULT_EXT_AUTH_VARIABLE', '$_SERVER[\'REMOTE_USER\']');
define('DEFAULT_ENCRYPTION_KEY', '@iT0pEncr1pti0n!'); // We'll use a random generated key later (if possible)
define('DEFAULT_ENCRYPTION_LIB', 'Mcrypt'); // We'll define the best encryption available later

/**
 * Config
 * configuration data (this class cannot not be localized, because it is responsible for loading the dictionaries)
 *
 * @see \MetaModel::GetConfig() to get the config, if the metamodel was already loaded
 * @see utils::GetConfig() to load config from the current env, if metamodel is not loaded
 * @package     iTopORM
 */
class Config
{
	//protected $m_bIsLoaded = false;
	protected $m_sFile = '';

	protected $m_aAppModules;
	protected $m_aDataModels;
	protected $m_aWebServiceCategories;
	protected $m_aAddons;

	/** @var ConfigPlaceholdersResolver */
	private $oConfigPlaceholdersResolver;

	protected $m_aModuleSettings;
	/**
	 * @var \iTopConfigParser|null
	 */
	private $oItopConfigParser;
	//for each conf entry, whether the non interpreted value can be kept in case is is written back to the disk.
	private $m_aCanOverrideSettings;

	/**
	 * New way to store the settings !
	 *
	 * @var array
	 * @since 2.5.0 db* variables
	 * @since 2.7.0 export_pdf_font param
	 */
	protected $m_aSettings = [
		'log_level_min' => [
			'type' => 'array',
			'description' => 'Optional min log level per channel',
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'app_env_label' => [
			'type' => 'string',
			'description' => 'Label displayed to describe the current application environment, defaults to the environment name (e.g. "production")',
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'app_root_url' => [
			'type' => 'string',
			'description' => 'Root URL used for navigating within the application, or from an email to the application (you can put $SERVER_NAME$ as a placeholder for the server\'s name)',
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'app_icon_url' => [
			'type' => 'string',
			'description' => 'Hyperlink to redirect the user when clicking on the application icon (in the main window, or login/logoff pages)',
			'default' => 'http://www.combodo.com/itop',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'db_host' => [
			'type' => 'string',
			'default' => null,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'db_user' => [
			'type' => 'string',
			'default' => null,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'db_pwd' => [
			'type' => 'string',
			'default' => null,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'db_name' => [
			'type' => 'string',
			'default' => null,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'db_subname' => [
			'type' => 'string',
			'default' => null,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'db_tls.enabled' => [
			'type' => 'bool',
			'description' => 'If true then the connection to the DB will be encrypted',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'db_tls.ca' => [
			'type' => 'string',
			'description' => 'Path to certificate authority file for SSL',
			'default' => null,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'db_core_transactions_enabled' => [
			'type' => 'bool',
			'description' => 'If true, CRUD transactions in iTop core will be enabled',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'db_core_transactions_retry_count' => [
			'type' => 'integer',
			'description' => 'Number of times the current transaction is tried',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'db_core_transactions_retry_delay_ms' => [
			'type' => 'integer',
			'description' => 'Base delay in milliseconds between transaction tries',
			'default' => 500,
			'value' => 500,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'skip_check_to_write' => [
			'type' => 'bool',
			'description' => 'Disable data format and integrity checks to boost up data load (insert or update)',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'skip_check_ext_keys' => [
			'type' => 'bool',
			'description' => 'Disable external key check when checking the value of attributes',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'skip_strong_security' => [
			'type' => 'bool',
			'description' => 'Disable strong security - TEMPORARY: this flag should be removed when we are more confident in the recent change in security',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'query_optimization_enabled' => [
			'type' => 'bool',
			'description' => 'The queries are optimized based on the assumption that the DB integrity has been preserved. By disabling the optimization one can ensure that the fetched data is clean... but this can be really slower or not usable at all (some queries will exceed the allowed number of joins in MySQL: 61!)',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'query_indentation_enabled' => [
			'type' => 'bool',
			'description' => 'For developers: format the SQL queries for human analysis',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'disable_mandatory_ext_keys' => [
			'type' => 'bool',
			'description' => 'For developers: allow every external keys to be undefined',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'graphviz_path' => [
			'type' => 'string',
			'description' => 'Path to the Graphviz "dot" executable for graphing objects lifecycle',
			'default' => '/usr/bin/dot',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'php_path' => [
			'type' => 'string',
			'description' => 'Path to the php executable in CLI mode',
			'default' => 'php',
			'value' => 'php',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'session_name' => [
			'type' => 'string',
			'description' => 'The name of the cookie used to store the PHP session id',
			'default' => 'iTop',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'max_combo_length' => [
			'type' => 'integer',
			'description' => 'The maximum number of elements in a drop-down list. If more then an autocomplete will be used',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'min_autocomplete_chars' => [
			'type' => 'integer',
			'description' => 'The minimum number of characters to type in order to trigger the "autocomplete" behavior',
			'default' => 2,
			'value' => 2,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'allow_menu_on_linkset' => [
			'type' => 'bool',
			'description' => 'Display Action menus in view mode on any LinkedSet with edit_mode != none',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'allow_target_creation' => [
			'type' => 'bool',
			'description' => 'Displays the + button on external keys to create target objects',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		// Levels that trigger a confirmation in the CSV import/synchro wizard
		'csv_import_min_object_confirmation' => [
			'type' => 'integer',
			'description' => 'Minimum number of objects to check for the confirmation percentages',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'csv_import_errors_percentage' => [
			'type' => 'integer',
			'description' => 'Percentage of errors that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'csv_import_modifications_percentage' => [
			'type' => 'integer',
			'description' => 'Percentage of modifications that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'csv_import_creations_percentage' => [
			'type' => 'integer',
			'description' => 'Percentage of creations that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'csv_import_history_display' => [
			'type' => 'bool',
			'description' => 'Display the history tab in the import wizard',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'export_pdf_font' => [ // @since 2.7.0 PR #49 / N°1947
			'type' => 'string',
			'description' => 'Font used when generating a PDF file',
			'default' => 'DejaVuSans', // DejaVuSans is a UTF-8 Unicode font, embedded in the TCPPDF lib we're using
			// Standard PDF fonts like helvetica or times newroman are NOT Unicode
			// A new DroidSansFallback can be used to improve CJK support (se PR #49)
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'access_mode' => [
			'type' => 'integer',
			'description' => 'Access mode: ACCESS_READONLY = 0, ACCESS_ADMIN_WRITE = 2, ACCESS_FULL = 3',
			'default' => ACCESS_FULL,
			'value' => ACCESS_FULL,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'access_message' => [
			'type' => 'string',
			'description' => 'Message displayed to the users when there is any access restriction',
			'default' => 'iTop is temporarily frozen, please wait... (the admin team)',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'online_help' => [
			'type' => 'string',
			'description' => 'Hyperlink to the online-help web page',
			'default' => 'http://www.combodo.com/itop-help',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'log_usage' => [
			'type' => 'bool',
			'description' => 'Log the usage of the application (i.e. the date/time and the user name of each login)',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_filename_builder_impl' => [
			'type' => 'string',
			'description' => 'Name of the iLogFileNameBuilder to use',
			'default' => 'MonthlyRotatingLogFileNameBuilder',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_rest_service' => [
			'type' => 'bool',
			'description' => 'Log the usage of the REST/JSON service',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'synchro_trace' => [
			'type' => 'string',
			'description' => 'Synchronization details: none, display, save (includes \'display\')',
			'default' => 'none',
			'value' => 'none',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'link_set_item_separator' => [
			'type' => 'string',
			'description' => 'Link set from string: line separator',
			'default' => '|',
			'value' => '|',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'link_set_attribute_separator' => [
			'type' => 'string',
			'description' => 'Link set from string: attribute separator',
			'default' => ';',
			'value' => ';',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'link_set_value_separator' => [
			'type' => 'string',
			'description' => 'Link set from string: value separator (between the attcode and the value itself',
			'default' => ':',
			'value' => ':',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'link_set_attribute_qualifier' => [
			'type' => 'string',
			'description' => 'Link set from string: attribute qualifier (encloses both the attcode and the value)',
			'default' => "'",
			'value' => "'",
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'tag_set_item_separator' => [
			'type' => 'string',
			'description' => 'Tag set from string: tag label separator',
			'default' => '|',
			'value' => '|',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'cron_max_execution_time' => [
			'type' => 'integer',
			'description' => 'Duration (seconds) of the page cron.php, must be shorter than php setting max_execution_time and shorter than the web server response timeout',
			'default' => 600,
			'value' => 600,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'cron_sleep' => [
			'type' => 'integer',
			'description' => 'Duration (seconds) before cron.php checks again if something must be done',
			'default' => 2,
			'value' => 2,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'async_task_retries' => [
			'type' => 'array',
			'description' => 'Automatic retries of asynchronous tasks in case of failure (per class)',
			'default' => ['AsyncSendEmail' => ['max_retries' => 0, 'retry_delay' => 600]],
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'email_asynchronous' => [
			'type' => 'bool',
			'description' => 'If set, the emails are sent off line, which requires cron.php to be activated. Exception: some features like the email test utility will force the serialized mode',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'email_transport' => [
			'type' => 'string',
			'description' => 'Mean to send emails: PHPMail (uses the function mail()) or SMTP (implements the client protocol)',
			'default' => "PHPMail",
			'value' => "PHPMail",
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'email_transport_smtp.host' => [
			'type' => 'string',
			'description' => 'host name or IP address (optional)',
			'default' => "localhost",
			'value' => "localhost",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'email_transport_smtp.port' => [
			'type' => 'integer',
			'description' => 'port number (optional)',
			'default' => 25,
			'value' => 25,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'email_transport_smtp.encryption' => [
			'type' => 'string',
			'description' => 'tls or ssl (optional)',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'email_transport_smtp.username' => [
			'type' => 'string',
			'description' => 'Authentication user (optional)',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'email_transport_smtp.password' => [
			'type' => 'string',
			'description' => 'Authentication password (optional)',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'email_css' => [
			'type' => 'string',
			'description' => 'CSS that will override the standard stylesheet used for the notifications',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'email_default_sender_address' => [
			'type' => 'string',
			'description' => 'Default address provided in the email from header field.',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'email_default_sender_label' => [
			'type' => 'string',
			'description' => 'Default label provided in the email from header field.',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'apc_cache.enabled' => [
			'type' => 'bool',
			'description' => 'If set, the APC cache is allowed (the PHP extension must also be active)',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'apc_cache.query_ttl' => [
			'type' => 'integer',
			'description' => 'Time to live set in APC for the prepared queries (seconds - 0 means no timeout)',
			'default' => 3600,
			'value' => 3600,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'apc_cache_emulation.max_entries' => [
			'type' => 'integer',
			'description' => 'Maximum number of cache entries (0 means no limit)',
			'default' => 1000,
			'value' => 1000,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'timezone' => [
			'type' => 'string',
			'description' => 'Timezone (reference: http://php.net/manual/en/timezones.php). If empty, it will be left unchanged and MUST be explicitly configured in PHP',
			// examples... not used (nor 'description')
			'examples' => [
				'America/Sao_Paulo',
				'America/New_York (standing for EDT)',
				'America/Los_Angeles (standing for PDT)',
				'Asia/Istanbul',
				'Asia/Singapore',
				'Africa/Casablanca',
				'Australia/Sydney',
			],
			'default' => 'Europe/Paris',
			'value' => 'Europe/Paris',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'cas_include_path' => [
			'type' => 'string',
			'description' => 'The path where to find the phpCAS library',
			// examples... not used (nor 'description')
			'default' => '/usr/share/php',
			'value' => '/usr/share/php',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'cas_version' => [
			'type' => 'string',
			'description' => 'The CAS protocol version to use: "1.0" (CAS v1), "2.0" (CAS v2) or "S1" (SAML V1) )',
			// examples... not used (nor 'description')
			'default' => '2.0',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_host' => [
			'type' => 'string',
			'description' => 'The name of the CAS host',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_port' => [
			'type' => 'integer',
			'description' => 'The port used by the CAS server',
			// examples... not used (nor 'description')
			'default' => 443,
			'value' => 443,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_context' => [
			'type' => 'string',
			'description' => 'The CAS context',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_server_ca_cert_path' => [
			'type' => 'string',
			'description' => 'The path where to find the certificate of the CA for validating the certificate of the CAS server',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_logout_redirect_service' => [
			'type' => 'string',
			'description' => 'The redirect service (URL) to use when logging-out with CAS',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_memberof' => [
			'type' => 'string',
			'description' => 'A semicolon separated list of group names that the user must be member of (works only with SAML - e.g. cas_version=> "S1")',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_user_synchro' => [
			'type' => 'bool',
			'description' => 'Whether or not to synchronize users with CAS/LDAP',
			// examples... not used (nor 'description')
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_update_profiles' => [
			'type' => 'bool',
			'description' => 'Whether or not to update the profiles of an existing user from the CAS information',
			// examples... not used (nor 'description')
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_profile_pattern' => [
			'type' => 'string',
			'description' => 'A regular expression pattern to extract the name of the iTop profile from the name of an LDAP/CAS group',
			// examples... not used (nor 'description')
			'default' => '/^cn=([^,]+),/',
			'value' => '/^cn=([^,]+),/',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_default_profiles' => [
			'type' => 'string',
			'description' => 'A semi-colon separated list of iTop Profiles to use when creating a new user if no profile is retrieved from CAS',
			// examples... not used (nor 'description')
			'default' => 'Portal user',
			'value' => 'Portal user',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'cas_debug' => [
			'type' => 'bool',
			'description' => 'Activate the CAS debug',
			// examples... not used (nor 'description')
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'login_debug' => [
			'type' => 'bool',
			'description' => 'Activate the login FSM debug',
			// examples... not used (nor 'description')
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'forgot_password' => [
			'type' => 'bool',
			'description' => 'Enable the "Forgot password" feature',
			// examples... not used (nor 'description')
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'forgot_password_from' => [
			'type' => 'string',
			'description' => 'Sender email address for the "forgot password" feature. If empty, defaults to the recipient\'s  email address.',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'deadline_format' => [
			'type' => 'string',
			'description' => 'The format used for displaying "deadline" attributes: any string with the following placeholders: $date$, $difference$',
			// examples... $date$ ($deadline$)
			'default' => '$difference$',
			'value' => '$difference$',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'buttons_position' => [
			'type' => 'string',
			'description' => 'Position of the forms buttons: bottom | top | both',
			// examples... not used
			'default' => 'both',
			'value' => 'both',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'shortcut_actions' => [
			'type' => 'string',
			'description' => 'Actions that are available as direct buttons next to the "Actions" menu',
			// examples... not used
			'default' => 'UI:Menu:Modify,UI:Menu:New',
			'value' => 'UI:Menu:Modify',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'complex_actions_limit' => [
			'type' => 'integer',
			'description' => 'Display the "actions" menu items that require long computation only if the list of objects is contains less objects than this number (0 means no limit)',
			// examples... not used
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'synchro_prevent_delete_all' => [
			'type' => 'bool',
			'description' => 'Stop the synchro if all the replicas of a data source become obsolete at the same time.',
			// examples... not used
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'source_dir' => [
			'type' => 'string',
			'description' => 'Source directory for the datamodel files. (which gets compiled to env-production).',
			// examples... not used
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'csv_file_default_charset' => [
			'type' => 'string',
			'description' => 'Character set used by default for downloading and uploading data as a CSV file. Warning: it is case sensitive (uppercase is preferable).',
			// examples... not used
			'default' => 'ISO-8859-1',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'debug_report_spurious_chars' => [
			'type' => 'bool',
			'description' => 'Report, in the error log, the characters found in the output buffer, echoed by mistake in the loaded modules, and potentially corrupting the output',
			// examples... not used
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'impact_analysis_first_tab' => [
			'type' => 'string',
			'description' => 'Which tab to display first in the impact analysis view: list or graphics. Graphics are nicer but slower to display when there are many objects',
			// examples... not used
			'default' => 'graphics',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'url_validation_pattern' => [
			'type' => 'string',
			'description' => 'Regular expression to validate/detect the format of an URL (URL attributes and Wiki formatting for Text attributes)',
			'default' => '(https?|ftp)\://([a-zA-Z0-9+!*(),;?&=\$_.-]+(\:[a-zA-Z0-9+!*(),;?&=\$_.-]+)?@)?([a-zA-Z0-9-.]{3,})(\:[0-9]{2,5})?(/([a-zA-Z0-9%+\$_-]\.?)+)*/?(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:[\]@&%=+/\$_.-]*)?(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?',
			//            SHEME.......... USER....................... PASSWORD.......................... HOST/IP........... PORT.......... PATH........................ GET............................................ ANCHOR............................
			// Example: http://User:passWord@127.0.0.1:8888/patH/Page.php?arrayArgument[2]=something:blah20#myAnchor
			// Origin of this regexp: http://www.php.net/manual/fr/function.preg-match.php#93824
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'email_validation_pattern' => [
			'type' => 'string',
			'description' => 'Regular expression to validate/detect the format of an eMail address',
			'default' => "[a-zA-Z0-9._&'-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]{2,}",
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'email_decoration_class' => [
			'type' => 'string',
			'description' => 'CSS class(es) to use as decoration for the HTML rendering of the attribute. eg. "fas fa-envelope" will put a mail icon.',
			'default' => 'fas fa-envelope',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'phone_number_validation_pattern' => [
			'type' => 'string',
			'description' => 'Regular expression to validate/detect the format of a phone number',
			'default' => "[0-9.\-\ \+\(\)]+",
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'phone_number_url_pattern' => [
			'type' => 'string',
			'description' => 'Format for phone number url, use %1$s as a placeholder for the value. eg. "tel:%1$s" for regular phone applications or "callto:%1$s" for Skype. Default is "tel:%1$s".',
			'default' => 'tel:%1$s',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'phone_number_decoration_class' => [
			'type' => 'string',
			'description' => 'CSS class(es) to use as decoration for the HTML rendering of the attribute. eg. "fas fa-phone" will put a phone icon.',
			'default' => 'fas fa-phone',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_kpi_duration' => [
			'type' => 'integer',
			'description' => 'Level of logging for troubleshooting performance issues (1 to enable, 2 +blame callers) new: add "log_kpi_slow_queries" to limit the stats',
			// examples... not used
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_kpi_slow_queries' => [
			'type' => 'float',
			'description' => 'Log only KPI duration stats lasting more than this value in seconds (0 for all)',
			'default' => 1,
			'value' => 1,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_kpi_memory' => [
			'type' => 'integer',
			'description' => 'Level of logging for troubleshooting memory limit issues',
			// examples... not used
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_kpi_user_id' => [
			'type' => 'string',
			'description' => 'Limit the scope of users to the given user id (* means no limit)',
			// examples... not used
			'default' => '*',
			'value' => '*',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'max_linkset_output' => [
			'type' => 'integer',
			'description' => 'Maximum number of items shown when getting a list of related items in an email, using the form $this->some_list$. 0 means no limit.',
			'default' => 100,
			'value' => 100,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'demo_mode' => [
			'type' => 'bool',
			'description' => 'Set to true to prevent users from changing passwords/languages',
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'portal_dispatch_urls' => [
			'type' => 'array',
			'description' => 'Associative array of sPortalId => Home page URL (relatively to the application root)',
			// examples... not used
			'default' => [],
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'max_execution_time_per_loop' => [
			'type' => 'integer',
			'description' => 'Maximum execution time requested, per loop, during bulk operations. Zero means no limit.',
			// examples... not used
			'default' => 30,
			'value' => 30,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'max_history_length' => [
			'type' => 'integer',
			'description' => 'Maximum length of the history table (in the "History" tab on each object) before it gets truncated. Latest modifications are displayed first.',
			// examples... not used
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'full_text_chunk_duration' => [
			'type' => 'integer',
			'description' => 'Delay after which the results are displayed.',
			// examples... not used
			'default' => 2,
			'value' => 2,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'full_text_accelerators' => [
			'type' => 'array',
			'description' => 'Specifies classes to be searched at first (and the subset of data) when running the full text search.',
			'default' => [],
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'full_text_needle_min' => [
			'type' => 'integer',
			'description' => 'Minimum size of the full text needle.',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'tracking_level_linked_set_default' => [
			'type' => 'integer',
			'description' => 'Default tracking level if not explicitly set at the attribute level, for AttributeLinkedSet (defaults to NONE in case of a fresh install, LIST otherwise - this to preserve backward compatibility while upgrading from a version older than 2.0.3 - see TRAC #936)',
			'default' => LINKSET_TRACKING_LIST,
			'value' => LINKSET_TRACKING_LIST,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'tracking_level_linked_set_indirect_default' => [
			'type' => 'integer',
			'description' => 'Default tracking level if not explicitly set at the attribute level, for AttributeLinkedSetIndirect',
			'default' => LINKSET_TRACKING_ALL,
			'value' => LINKSET_TRACKING_ALL,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'user_rights_legacy' => [
			'type' => 'bool',
			'description' => 'Set to true to restore the buggy algorithm for the computation of user rights (within the same profile, ALLOW on the class itself has precedence on DENY of a parent class)',
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'xlsx_exporter_memory_limit' => [
			'type' => 'string',
			'description' => 'Memory limit to use when (interactively) exporting data to Excel',
			'default' => '2048M', // Huuuuuuge 2GB!
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'min_reload_interval' => [
			'type' => 'integer',
			'description' => 'Minimum refresh interval (seconds) for dashboards, shortcuts, etc. Even if the interval is set programmatically, it is forced to that minimum',
			'default' => 5, // In iTop 2.0.3, this was the hardcoded value
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'relations_max_depth' => [
			'type' => 'integer',
			'description' => 'Maximum number of successive levels (depth) to explore when displaying the impact/depends on relations.',
			'default' => 20, // In iTop 2.0.3, this was the hardcoded value
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'transaction_storage' => [
			'type' => 'string',
			'description' => 'The type of mechanism to use for storing the unique identifiers for transactions (Session|File).',
			'default' => 'File',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'transactions_enabled' => [
			'type' => 'bool',
			'description' => 'Whether or not the whole mechanism to prevent multiple submissions of a page is enabled.',
			'default' => true,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_transactions' => [
			'type' => 'bool',
			'description' => 'Whether or not to enable the debug log for the transactions.',
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'concurrent_lock_enabled' => [
			'type' => 'bool',
			'description' => 'Whether or not to activate the locking mechanism in order to prevent concurrent edition of the same object.',
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'concurrent_lock_expiration_delay' => [
			'type' => 'integer',
			'description' => 'Delay (in seconds) for a concurrent lock to expire',
			'default' => 120,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'concurrent_lock_override_profiles' => [
			'type' => 'array',
			'description' => 'The list of profiles allowed to "kill" a lock',
			'default' => ['Administrator'],
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'html_sanitizer' => [
			'type' => 'string',
			'description' => 'The class to use for HTML sanitization: HTMLDOMSanitizer, HTMLPurifierSanitizer or HTMLNullSanitizer',
			'default' => 'HTMLDOMSanitizer',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'inline_image_max_display_width' => [
			'type' => 'integer',
			'description' => 'The maximum width (in pixels) when displaying images inside an HTML formatted attribute. Images will be displayed using this this maximum width.',
			'default' => '250',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'inline_image_max_storage_width' => [
			'type' => 'integer',
			'description' => 'The maximum width (in pixels) when uploading images to be used inside an HTML formatted attribute. Images larger than the given size will be downsampled before storing them in the database.',
			'default' => '1600',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'draft_attachments_lifetime' => [
			'type' => 'integer',
			'description' => 'Lifetime (in seconds) of drafts\' attachments and inline images: after this duration, the garbage collector will delete them.',
			'default' => 86400,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'date_and_time_format' => [
			'type' => 'array',
			'description' => 'Format for date and time display (per language)',
			'default' => ['default' => ['date' => 'Y-m-d', 'time' => 'H:i:s', 'date_time' => '$date $time']],
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'quick_create.enabled' => [
			'type' => 'bool',
			'description' => 'Whether or not the global search is enabled',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'quick_create.max_autocomplete_results' => [
			'type' => 'integer',
			'description' => 'Max. number of elements returned by the autocomplete.',
			'default' => 10,
			'value' => 10,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'quick_create.max_history_results' => [
			'type' => 'integer',
			'description' => 'Max. number of elements in the history.',
			'default' => 10,
			'value' => 10,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'mentions.allowed_classes' => [
			'type' => 'array',
			'description' => 'Classes which can be mentioned through the autocomplete in the caselogs. Key of the array must be a single character that will trigger the autocomplete (eg. "@" => "Person")',
			'default' => [
				'@' => 'Person',
			],
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'global_search.enabled' => [
			'type' => 'bool',
			'description' => 'Whether or not the global search is enabled',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'global_search.max_history_results' => [
			'type' => 'integer',
			'description' => 'Max. number of elements in the history.',
			'default' => 10,
			'value' => 10,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'breadcrumb.enabled' => [
			'type' => 'bool',
			'description' => 'Whether or not the breadcrumbs is enabled',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'breadcrumb.max_count' => [
			'type' => 'integer',
			'description' => 'Maximum number of items kept in the history breadcrumb. Set it to 0 to entirely disable the breadcrumb.',
			'default' => 8,
			'value' => 8,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'obsolescence.show_obsolete_data' => [
			'type' => 'bool',
			'description' => 'Default value for the user preference "show obsolete data"',
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'obsolescence.date_update_interval' => [
			'type' => 'integer',
			'description' => 'Delay in seconds between two refreshes of the obsolescence dates.',
			'default' => 600,
			'value' => 600,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'secure_rest_services' => [
			'type' => 'bool',
			'description' => 'When set to true, only the users with the profile "REST Services User" are allowed to use the REST web services.',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'search_manual_submit' => [
			'type' => 'array',
			'description' => 'Force manual submit of search all requests',
			'default' => false,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'optimize_requests_for_join_count' => [
			'type' => 'bool',
			'description' => 'Optimize request joins to minimize the count (default is true, try to set it to false in case of performance issues)',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'high_cardinality_classes' => [
			'type' => 'array',
			'description' => 'List of classes with high cardinality (Force manual submit of search)',
			'default' => [],
			'value' => [],
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		],
		'newsroom_enabled' => [
			'type' => 'bool',
			'description' => 'Whether or not the whole newsroom is enabled',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'regenerate_session_id_enabled' => [
			'type' => 'bool',
			'description' => 'If true then session id will be regenerated on each login, to prevent session fixation.',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'use_legacy_dbsearch' => [
			'type' => 'bool',
			'description' => 'If set, DBSearch will use legacy SQL query generation',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'query_cache_enabled' => [
			'type' => 'bool',
			'description' => 'If set, DBSearch will use cache for query generation',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'expression_cache_enabled' => [
			'type' => 'bool',
			'description' => 'If set, DBSearch will use cache for query expression generation',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'log_kpi_record_oql' => [
			'type' => 'integer',
			'description' => '1 => Record OQL requests and parameters',
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'backoffice_default_theme' => [
			'type' => 'string',
			'description' => 'Default theme used for '.ITOP_APPLICATION_SHORT.'\'s console',
			'default' => 'light-grey',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'display_menus_count' => [
			'type' => 'bool',
			'description' => 'Display count badges for OQL menu entries',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
		'security_header_xframe' => [
			'type' => 'string',
			'description' => 'Value of the X-Frame-Options HTTP header sent by iTop. Possible values : DENY, SAMEORIGIN, or empty string.',
			'default' => 'SAMEORIGIN',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		],
	];


	public function IsProperty($sPropCode)
	{
		return (array_key_exists($sPropCode, $this->m_aSettings));
	}

	/**
	 * @return string identifier that can be used for example to name WebStorage/SessionStorage keys (they
	 *     are related to a whole domain, and a domain can host multiple itop)
	 *     Beware: do not expose server side information to the client !
	 * @throws \Exception
	 */
	public function GetItopInstanceid()
	{
		return md5(utils::GetAbsoluteUrlAppRoot()
			.'==='.$this->Get('db_host')
			.'/'.$this->Get('db_name')
			.'/'.$this->Get('db_subname'));
	}

	public function GetDescription($sPropCode)
	{
		return $this->m_aSettings[$sPropCode];
	}

	/**
	 * @param string $sPropCode
	 * @param mixed $value
	 * @param string $sSourceDesc mandatory for variables with show_in_conf_sample=false
	 * @param bool $bCanOverride whether the written to file value can still be the non evaluated version on must be the literal
	 *
	 * @throws \CoreException
	 */
	public function Set($sPropCode, $value, $sSourceDesc = 'unknown', $bCanOverride = false)
	{
		$sType = $this->m_aSettings[$sPropCode]['type'];

		$value = $this->oConfigPlaceholdersResolver->Resolve($value);

		switch ($sType)
		{
			case 'bool':
				$value = (bool)$value;
				break;
			case 'string':
				$value = (string)$value;
				break;
			case 'integer':
				$value = (integer)$value;
				break;
			case 'float':
				$value = (float)$value;
				break;
			case 'array':
				break;
			default:
				throw new CoreException('Unknown type for setting', array('property' => $sPropCode, 'type' => $sType));
		}

		if ($this->m_aSettings[$sPropCode]['value'] == $value)
		{
			//when you set the exact same value than the previous one, then, you still can preserve the non evaluated version and so on preserve vars/jokers.
			$bCanOverride = true;
		}

		$this->m_aSettings[$sPropCode]['value'] = $value;
		$this->m_aSettings[$sPropCode]['source_of_value'] = $sSourceDesc;
		$this->m_aCanOverrideSettings[$sPropCode] = $bCanOverride;
	}

	/**
	 * @param string $sPropCode
	 *
	 * @return mixed
	 */
	public function Get($sPropCode)
	{
		return $this->m_aSettings[$sPropCode]['value'];
	}

	/**
	 * Event log options (see LOG_... definition)
	 */
	// Those variables will be deprecated later, when the transition to ...Get('my_setting') will be done
	protected $m_bLogGlobal;
	protected $m_bLogNotification;
	protected $m_bLogIssue;
	protected $m_bLogWebService;
	protected $m_bQueryCacheEnabled; // private setting

	/**
	 * @var integer Number of elements to be displayed when there are more than m_iMaxDisplayLimit elements
	 */
	protected $m_iMinDisplayLimit;
	/**
	 * @var integer Max number of elements before truncating the display
	 */
	protected $m_iMaxDisplayLimit;

	/**
	 * @var integer Number of seconds between two reloads of the display (standard)
	 */
	protected $m_iStandardReloadInterval;
	/**
	 * @var integer Number of seconds between two reloads of the display (fast)
	 */
	protected $m_iFastReloadInterval;

	/**
	 * @var boolean Whether or not a secure connection is required for using the application.
	 *              If set, any attempt to connect to an iTop page with http:// will be redirected
	 *              to https://
	 */
	protected $m_bSecureConnectionRequired;

	/**
	 * @var string Langage code, default if the user language is undefined
	 */
	protected $m_sDefaultLanguage;

	/**
	 * @var string Type of login process allowed: form|basic|url|external
	 */
	protected $m_sAllowedLoginTypes;

	/**
	 * @var string Name of the PHP variable in which external authentication information is passed by the web server
	 */
	protected $m_sExtAuthVariable;

	/**
	 * @var string Encryption key used for all attributes of type "encrypted string". Can be set to a random value
	 *             unless you want to import a database from another iTop instance, in which case you must use
	 *             the same encryption key in order to properly decode the encrypted fields
	 */
	protected $m_sEncryptionKey;

	/**
	 * @var string Encryption key used for all attributes of type "encrypted string". Can be set to a random value
	 *             unless you want to import a database from another iTop instance, in which case you must use
	 *             the same encryption key in order to properly decode the encrypted fields
	 */
	protected $m_sEncryptionLibrary;

	/**
	 * @var array Additional character sets to be supported by the interactive CSV import
	 *            'iconv_code' => 'display name'
	 */
	protected $m_aCharsets;

	/**
	 * Config constructor.
	 *
	 * @param string|null $sConfigFile
	 * @param bool $bLoadConfig
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function __construct($sConfigFile = null, $bLoadConfig = true)
	{
		$this->oConfigPlaceholdersResolver = new ConfigPlaceholdersResolver();

		$this->m_sFile = $sConfigFile;
		if (is_null($sConfigFile))
		{
			$bLoadConfig = false;
		}

		$this->m_aAddons = array(
			// Default AddOn, always present can be moved to an official iTop Module later if needed
			'user rights' => 'addons/userrights/userrightsprofile.class.inc.php',
		);

		foreach ($this->m_aSettings as $sPropCode => $aSettingInfo)
		{
			$this->m_aSettings[$sPropCode]['value'] = $aSettingInfo['default'];
		}

		$this->m_bLogGlobal = DEFAULT_LOG_GLOBAL;
		$this->m_bLogNotification = DEFAULT_LOG_NOTIFICATION;
		$this->m_bLogIssue = DEFAULT_LOG_ISSUE;
		$this->m_bLogWebService = DEFAULT_LOG_WEB_SERVICE;
		$this->m_iMinDisplayLimit = DEFAULT_MIN_DISPLAY_LIMIT;
		$this->m_iMaxDisplayLimit = DEFAULT_MAX_DISPLAY_LIMIT;
		$this->m_iStandardReloadInterval = DEFAULT_STANDARD_RELOAD_INTERVAL;
		$this->m_iFastReloadInterval = DEFAULT_FAST_RELOAD_INTERVAL;
		$this->m_bSecureConnectionRequired = DEFAULT_SECURE_CONNECTION_REQUIRED;
		$this->m_sDefaultLanguage = 'EN US';
		$this->m_sAllowedLoginTypes = DEFAULT_ALLOWED_LOGIN_TYPES;
		$this->m_sExtAuthVariable = DEFAULT_EXT_AUTH_VARIABLE;
		$this->m_aCharsets = array();
		$this->m_bQueryCacheEnabled = DEFAULT_QUERY_CACHE_ENABLED;

		//define default encryption params according to php install
		$aEncryptParams = SimpleCrypt::GetNewDefaultParams();
		$this->m_sEncryptionLibrary = isset($aEncryptParams['lib']) ? $aEncryptParams['lib'] : DEFAULT_ENCRYPTION_LIB;
		$this->m_sEncryptionKey = isset($aEncryptParams['key']) ? $aEncryptParams['key'] : DEFAULT_ENCRYPTION_KEY;

		$this->m_aModuleSettings = array();

		if ($bLoadConfig)
		{
			$this->Load($sConfigFile);
			$this->Verify();
		}

		// Application root url: set a default value, then normalize it
		/*
		 * Does not work in CLI/unattended mode
				$sAppRootUrl = trim($this->Get('app_root_url'));
				if (strlen($sAppRootUrl) == 0)
				{
					$sAppRootUrl = utils::GetDefaultUrlAppRoot();
				}
				if (substr($sAppRootUrl, -1, 1) != '/')
				{
					$sAppRootUrl .= '/';
				}
				$this->Set('app_root_url', $sAppRootUrl);
		 */
	}

	/**
	 * @param string $sPurpose
	 * @param string $sFileName
	 *
	 * @throws \ConfigException
	 */
	protected function CheckFile($sPurpose, $sFileName)
	{
		if (!file_exists($sFileName))
		{
			throw new ConfigException("Could not find $sPurpose file", array('file' => $sFileName));
		}
		if (!is_readable($sFileName))
		{
			throw new ConfigException("Could not read $sPurpose file (the file exists but cannot be read). Do you have the rights to access this file?",
				array('file' => $sFileName));
		}
	}

	/**
	 * @param string $sConfigFile
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	protected function Load($sConfigFile)
	{
		$this->CheckFile('configuration', $sConfigFile);

		$sConfigCode = trim(file_get_contents($sConfigFile));

		// Variables created when doing an eval() on the config file
		/** @var array $MySettings */
		$MySettings = null;
		/** @var array $MyModuleSettings */
		$MyModuleSettings = null;
		/** @var array $MyModules */
		$MyModules = null;

		// This does not work on several lines
		// preg_match('/^<\\?php(.*)\\?'.'>$/', $sConfigCode, $aMatches)...
		// So, I've implemented a solution suggested in the PHP doc (search for phpWrapper)
		try
		{
			ob_start();
			eval('?'.'>'.trim($sConfigCode));
			$sNoise = trim(ob_get_contents());
			ob_end_clean();
		}
		catch (Error $e)
		{
			// PHP 7
			throw new ConfigException('Error in configuration file',
				array('file' => $sConfigFile, 'error' => $e->getMessage().' at line '.$e->getLine()));
		}
		catch (Exception $e)
		{
			// well, never reach in case of parsing error :-(
			// will be improved in PHP 6 ?
			throw new ConfigException('Error in configuration file',
				array('file' => $sConfigFile, 'error' => $e->getMessage()));
		}
		if (strlen($sNoise) > 0)
		{
			// Note: sNoise is an html output, but so far it was ok for me (e.g. showing the entire call stack) 
			throw new ConfigException('Syntax error in configuration file',
				array('file' => $sConfigFile, 'error' => '<tt>'.htmlentities($sNoise, ENT_QUOTES, 'UTF-8').'</tt>'));
		}

		if (!isset($MySettings) || !is_array($MySettings))
		{
			throw new ConfigException('Missing array in configuration file',
				array('file' => $sConfigFile, 'expected' => '$MySettings'));
		}

		if (!array_key_exists('addons', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file',
				array('file' => $sConfigFile, 'expected' => '$MyModules[\'addons\']'));
		}
		if (!array_key_exists('user rights', $MyModules['addons']))
		{
			// Add one, by default
			$MyModules['addons']['user rights'] = '/addons/userrights/userrightsnull.class.inc.php';
		}

		$this->m_aAddons = $MyModules['addons'];

		foreach ($MySettings as $sPropCode => $rawvalue)
		{
			if ($this->IsProperty($sPropCode))
			{
				if (is_string($rawvalue))
				{
					$value = trim($rawvalue);
				}
				else
				{
					$value = $rawvalue;
				}
				$this->Set($sPropCode, $value, $sConfigFile, true);
			}
		}

		if (file_exists(READONLY_MODE_FILE))
		{
			$this->Set('access_mode', ACCESS_READONLY, READONLY_MODE_FILE);
		}

		$this->m_bLogGlobal = isset($MySettings['log_global']) ? (bool)trim($MySettings['log_global']) : DEFAULT_LOG_GLOBAL;
		$this->m_bLogNotification = isset($MySettings['log_notification']) ? (bool)trim($MySettings['log_notification']) : DEFAULT_LOG_NOTIFICATION;
		$this->m_bLogIssue = isset($MySettings['log_issue']) ? (bool)trim($MySettings['log_issue']) : DEFAULT_LOG_ISSUE;
		$this->m_bLogWebService = isset($MySettings['log_web_service']) ? (bool)trim($MySettings['log_web_service']) : DEFAULT_LOG_WEB_SERVICE;
		$this->m_bQueryCacheEnabled = isset($MySettings['query_cache_enabled']) ? (bool)trim($MySettings['query_cache_enabled']) : DEFAULT_QUERY_CACHE_ENABLED;

		$this->m_iMinDisplayLimit = isset($MySettings['min_display_limit']) ? trim($MySettings['min_display_limit']) : DEFAULT_MIN_DISPLAY_LIMIT;
		$this->m_iMaxDisplayLimit = isset($MySettings['max_display_limit']) ? trim($MySettings['max_display_limit']) : DEFAULT_MAX_DISPLAY_LIMIT;
		$this->m_iStandardReloadInterval = isset($MySettings['standard_reload_interval']) ? trim($MySettings['standard_reload_interval']) : DEFAULT_STANDARD_RELOAD_INTERVAL;
		$this->m_iFastReloadInterval = isset($MySettings['fast_reload_interval']) ? trim($MySettings['fast_reload_interval']) : DEFAULT_FAST_RELOAD_INTERVAL;
		$this->m_bSecureConnectionRequired = isset($MySettings['secure_connection_required']) ? (bool)trim($MySettings['secure_connection_required']) : DEFAULT_SECURE_CONNECTION_REQUIRED;

		$this->m_aModuleSettings = isset($MyModuleSettings) ? $MyModuleSettings : array();

		$this->m_sDefaultLanguage = isset($MySettings['default_language']) ? trim($MySettings['default_language']) : 'EN US';
		$this->m_sAllowedLoginTypes = isset($MySettings['allowed_login_types']) ? trim($MySettings['allowed_login_types']) : DEFAULT_ALLOWED_LOGIN_TYPES;
		$this->m_sExtAuthVariable = isset($MySettings['ext_auth_variable']) ? trim($MySettings['ext_auth_variable']) : DEFAULT_EXT_AUTH_VARIABLE;
		$this->m_sEncryptionKey = isset($MySettings['encryption_key']) ? trim($MySettings['encryption_key']) : $this->m_sEncryptionKey;
		$this->m_sEncryptionLibrary = isset($MySettings['encryption_library']) ? trim($MySettings['encryption_library']) : $this->m_sEncryptionLibrary;
		$this->m_aCharsets = isset($MySettings['csv_import_charsets']) ? $MySettings['csv_import_charsets'] : array();
	}

	protected function Verify()
	{
		// Files are verified later on, just before using them -see MetaModel::Plugin()
		// (we have their final path at that point)
	}

	/**
	 * @see \MetaModel::GetModuleParameter()
	 *
	 * @param string $sProperty
	 * @param mixed $defaultvalue
	 *
	 * @param string $sModule
	 *
	 * @return mixed|null if present, value defined in the configuration file, if not module parameter from XML
	 */
	public function GetModuleSetting($sModule, $sProperty, $defaultvalue = null)
	{
		if (isset($this->m_aModuleSettings[$sModule][$sProperty]))
		{
			return $this->m_aModuleSettings[$sModule][$sProperty];
		}

		// Fall back to the predefined XML parameter, if any
		return $this->GetModuleParameter($sModule, $sProperty, $defaultvalue);
	}

	/**
	 * @see \MetaModel::GetModuleSetting() to get from the configuration file first
	 *
	 * @param string $sProperty
	 * @param mixed $defaultvalue
	 *
	 * @param string $sModule
	 *
	 * @return mixed|null parameter value defined in the XML
	 * @link https://www.itophub.io/wiki/page?id=latest%3Acustomization%3Axml_reference#modules_parameters
	 */
	public function GetModuleParameter($sModule, $sProperty, $defaultvalue = null)
	{
		$ret = $defaultvalue;
		if (class_exists('ModulesXMLParameters'))
		{
			$aAllParams = ModulesXMLParameters::GetData($sModule);
			if (array_key_exists($sProperty, $aAllParams))
			{
				$ret = $aAllParams[$sProperty];
			}
		}

		return $ret;
	}

	public function SetModuleSetting($sModule, $sProperty, $value)
	{
		$this->m_aModuleSettings[$sModule][$sProperty] = $value;
	}

	public function GetAddons()
	{
		return $this->m_aAddons;
	}

	public function SetAddons($aAddons)
	{
		$this->m_aAddons = $aAddons;
	}

	public function GetLogGlobal()
	{
		return $this->m_bLogGlobal;
	}

	public function GetLogNotification()
	{
		return $this->m_bLogNotification;
	}

	public function GetLogIssue()
	{
		return $this->m_bLogIssue;
	}

	public function GetLogWebService()
	{
		return $this->m_bLogWebService;
	}

	public function GetLogQueries()
	{
		return false;
	}

	public function GetQueryCacheEnabled()
	{
		return $this->m_bQueryCacheEnabled;
	}

	public function GetMinDisplayLimit()
	{
		return $this->m_iMinDisplayLimit;
	}

	public function GetMaxDisplayLimit()
	{
		return $this->m_iMaxDisplayLimit;
	}

	public function GetStandardReloadInterval()
	{
		return $this->m_iStandardReloadInterval;
	}

	public function GetFastReloadInterval()
	{
		return $this->m_iFastReloadInterval;
	}

	public function GetSecureConnectionRequired()
	{
		return $this->m_bSecureConnectionRequired;
	}

	public function GetDefaultLanguage()
	{
		return $this->m_sDefaultLanguage;
	}

	public function GetEncryptionKey()
	{
		return $this->m_sEncryptionKey;
	}

	public function GetEncryptionLibrary()
	{
		return $this->m_sEncryptionLibrary;
	}

	public function GetAllowedLoginTypes()
	{
		return explode('|', $this->m_sAllowedLoginTypes);
	}

	public function GetExternalAuthenticationVariable()
	{
		return $this->m_sExtAuthVariable;
	}

	public function GetCSVImportCharsets()
	{
		return $this->m_aCharsets;
	}

	public function SetLogGlobal($iLogGlobal)
	{
		$this->m_iLogGlobal = $iLogGlobal;
	}

	public function SetLogNotification($iLogNotification)
	{
		$this->m_iLogNotification = $iLogNotification;
	}

	public function SetLogIssue($iLogIssue)
	{
		$this->m_iLogIssue = $iLogIssue;
	}

	public function SetLogWebService($iLogWebService)
	{
		$this->m_iLogWebService = $iLogWebService;
	}

	public function SetMinDisplayLimit($iMinDisplayLimit)
	{
		$this->m_iMinDisplayLimit = $iMinDisplayLimit;
	}

	public function SetMaxDisplayLimit($iMaxDisplayLimit)
	{
		$this->m_iMaxDisplayLimit = $iMaxDisplayLimit;
	}

	public function SetStandardReloadInterval($iStandardReloadInterval)
	{
		$this->m_iStandardReloadInterval = $iStandardReloadInterval;
	}

	public function SetFastReloadInterval($iFastReloadInterval)
	{
		$this->m_iFastReloadInterval = $iFastReloadInterval;
	}

	public function SetSecureConnectionRequired($bSecureConnectionRequired)
	{
		$this->m_bSecureConnectionRequired = $bSecureConnectionRequired;
	}

	public function SetDefaultLanguage($sLanguageCode)
	{
		$this->m_sDefaultLanguage = $sLanguageCode;
	}

	public function SetAllowedLoginTypes($aAllowedLoginTypes)
	{
		$this->m_sAllowedLoginTypes = implode('|', $aAllowedLoginTypes);
	}

	public function SetExternalAuthenticationVariable($sExtAuthVariable)
	{
		$this->m_sExtAuthVariable = $sExtAuthVariable;
	}

	public function SetEncryptionKey($sKey)
	{
		$this->m_sEncryptionKey = $sKey;
	}

	public function SetCSVImportCharsets($aCharsets)
	{
		$this->m_aCharsets = $aCharsets;
	}

	public function AddCSVImportCharset($sIconvCode, $sDisplayName)
	{
		$this->m_aCharsets[$sIconvCode] = $sDisplayName;
	}

	public function GetLoadedFile()
	{
		if (is_null($this->m_sFile))
		{
			return '';
		}
		else
		{
			return $this->m_sFile;
		}
	}

	/**
	 * Render the configuration as an associative array
	 *
	 * @return array
	 */
	public function ToArray()
	{
		$aSettings = array();
		foreach ($this->m_aSettings as $sPropCode => $aSettingInfo)
		{
			$aSettings[$sPropCode] = $aSettingInfo['value'];
		}
		$aSettings['log_global'] = $this->m_bLogGlobal;
		$aSettings['log_notification'] = $this->m_bLogNotification;
		$aSettings['log_issue'] = $this->m_bLogIssue;
		$aSettings['log_web_service'] = $this->m_bLogWebService;
		$aSettings['query_cache_enabled'] = $this->m_bQueryCacheEnabled;
		$aSettings['min_display_limit'] = $this->m_iMinDisplayLimit;
		$aSettings['max_display_limit'] = $this->m_iMaxDisplayLimit;
		$aSettings['standard_reload_interval'] = $this->m_iStandardReloadInterval;
		$aSettings['fast_reload_interval'] = $this->m_iFastReloadInterval;
		$aSettings['secure_connection_required'] = $this->m_bSecureConnectionRequired;
		$aSettings['default_language'] = $this->m_sDefaultLanguage;
		$aSettings['allowed_login_types'] = $this->m_sAllowedLoginTypes;
		$aSettings['ext_auth_variable'] = $this->m_sExtAuthVariable;
		$aSettings['encryption_key'] = $this->m_sEncryptionKey;
		$aSettings['encryption_library'] = $this->m_sEncryptionLibrary;
		$aSettings['csv_import_charsets'] = $this->m_aCharsets;

		foreach ($this->m_aModuleSettings as $sModule => $aProperties)
		{
			foreach ($aProperties as $sProperty => $value)
			{
				$aSettings['module_settings'][$sModule][$sProperty] = $value;
			}
		}
		foreach ($this->m_aAddons as $sKey => $sFile)
		{
			$aSettings['addon_list'][] = $sFile;
		}

		return $aSettings;
	}

	/**
	 * Write the configuration to a file (php format) that can be reloaded later
	 * By default write to the same file that was specified when constructing the object
	 *
	 * @param string $sFileName string Name of the file to write to (emtpy to write to the same file)
	 *
	 * @return boolean True otherwise throws an Exception
	 *
	 * @throws \ConfigException
	 */
	public function WriteToFile($sFileName = '')
	{
		if (empty($sFileName))
		{
			$sFileName = $this->m_sFile;
		}
		$oHandle = null;
		$sConfig = null;

		if (is_file($this->m_sFile))
		{
			$oHandle = fopen($this->m_sFile, 'r');
			$index = 0;
			while (!flock($oHandle, LOCK_SH))
			{
				if ($index > 50)
				{
					throw new ConfigException("Could not read to configuration file", array('file' => $this->m_sFile));
				}
				usleep(100000);
				$index++;
			}
			$sConfig = file_get_contents($this->m_sFile);
		}
		$this->oItopConfigParser = new iTopConfigParser($sConfig);
		if ($oHandle !==null)
		{
			flock($oHandle, LOCK_UN);
		}

		$hFile = @fopen($sFileName, 'w');
		if ($hFile !== false)
		{
			fwrite($hFile, "<?php\n");
			fwrite($hFile, "\n/**\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * Configuration file, generated by the ".ITOP_APPLICATION." configuration wizard\n");
			fwrite($hFile, " *\n");
			fwrite($hFile,
				" * The file is used in MetaModel::LoadConfig() which does all the necessary initialization job\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " */\n");

			$aConfigSettings = $this->m_aSettings;

			// Old fashioned boolean settings
			$aBoolValues = array(
				'log_global' => $this->m_bLogGlobal,
				'log_notification' => $this->m_bLogNotification,
				'log_issue' => $this->m_bLogIssue,
				'log_web_service' => $this->m_bLogWebService,
				'secure_connection_required' => $this->m_bSecureConnectionRequired,
			);
			foreach ($aBoolValues as $sKey => $bValue)
			{
				$aConfigSettings[$sKey] = array(
					'show_in_conf_sample' => true,
					'type' => 'bool',
					'value' => $bValue,
				);
			}

			// Old fashioned integer settings
			$aIntValues = array(
				'fast_reload_interval' => $this->m_iFastReloadInterval,
				'max_display_limit' => $this->m_iMaxDisplayLimit,
				'min_display_limit' => $this->m_iMinDisplayLimit,
				'standard_reload_interval' => $this->m_iStandardReloadInterval,
			);
			foreach ($aIntValues as $sKey => $iValue)
			{
				$aConfigSettings[$sKey] = array(
					'show_in_conf_sample' => true,
					'type' => 'integer',
					'value' => $iValue,
				);
			}

			// Old fashioned remaining values
			$aOtherValues = array(
				'default_language' => $this->m_sDefaultLanguage,
				'allowed_login_types' => $this->m_sAllowedLoginTypes,
				'ext_auth_variable' => $this->m_sExtAuthVariable,
				'encryption_key' => $this->m_sEncryptionKey,
				'encryption_library' => $this->m_sEncryptionLibrary,
				'csv_import_charsets' => $this->m_aCharsets,
			);
			foreach ($aOtherValues as $sKey => $value)
			{
				$aConfigSettings[$sKey] = array(
					'show_in_conf_sample' => true,
					'type' => is_string($value) ? 'string' : 'mixed',
					'value' => $value,
				);
			}

			ksort($aConfigSettings);
			fwrite($hFile, "\$MySettings = array(\n");
			foreach ($aConfigSettings as $sPropCode => $aSettingInfo)
			{
				// Write all values that are either always visible or present in the cloned config file
				if ($aSettingInfo['show_in_conf_sample'] || (!empty($aSettingInfo['source_of_value']) && ($aSettingInfo['source_of_value'] != 'unknown')))
				{
					fwrite($hFile, "\n");

					if (isset($aSettingInfo['description']))
					{
						fwrite($hFile, "\t// $sPropCode: {$aSettingInfo['description']}\n");
					}

					if (isset($aSettingInfo['default']))
					{
						$sComment = self::PrettyVarExport(null,$aSettingInfo['default'], "\t//\t\t", true);
						fwrite($hFile,"\t//\tdefault: {$sComment}\n");
					}

					if (isset($this->m_aCanOverrideSettings[$sPropCode]) && $this->m_aCanOverrideSettings[$sPropCode])
					{
						$aParserValue = $this->oItopConfigParser->GetVarValue('MySettings', $sPropCode);
					}
					else
					{
						$aParserValue = null;
					}
					$sSeenAs = self::PrettyVarExport($aParserValue,$aSettingInfo['value'], "\t");
					fwrite($hFile, "\t'$sPropCode' => $sSeenAs,\n");
				}
			}
			fwrite($hFile, ");\n");

			fwrite($hFile, "\n");
			fwrite($hFile, "/**\n *\n * Modules specific settings\n *\n */\n");
			fwrite($hFile, "\$MyModuleSettings = array(\n");
			foreach ($this->m_aModuleSettings as $sModule => $aProperties)
			{
				fwrite($hFile, "\t'$sModule' => array (\n");
				foreach ($aProperties as $sProperty => $value)
				{
					$sNiceExport = self::PrettyVarExport($this->oItopConfigParser->GetVarValue('MyModuleSettings', $sProperty), $value, "\t\t");
					fwrite($hFile, "\t\t'$sProperty' => $sNiceExport,\n");
				}
				fwrite($hFile, "\t),\n");
			}
			fwrite($hFile, ");\n");

			fwrite($hFile, "\n/**\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * Data model modules to be loaded. Names are specified as relative paths\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " */\n");
			fwrite($hFile, "\$MyModules = array(\n");
			$aParserValue = $this->oItopConfigParser->GetVarValue('MyModules', 'addons');
			if ($aParserValue['found'])
			{
				fwrite($hFile, "\t'addons' => {$aParserValue['value']},\n");
			}
			else
			{
				fwrite($hFile, "\t'addons' => array (\n");
				foreach ($this->m_aAddons as $sKey => $sFile)
				{
					fwrite($hFile, "\t\t'$sKey' => '$sFile',\n");
				}
				fwrite($hFile, "\t),\n");
			}
			fwrite($hFile, ");\n");
			fwrite($hFile, '?'.'>'); // Avoid perturbing the syntax highlighting !

			$bReturn = fclose($hFile);

			utils::SetConfig($this);

			return $bReturn;
		}
		else
		{
			throw new ConfigException("Could not write to configuration file", array('file' => $sFileName));
		}
	}

	/**
	 * Helper function to initialize a configuration from the page arguments
	 *
	 * @param array $aParamValues
	 * @param string|null $sModulesDir
	 * @param bool $bPreserveModuleSettings
	 *
	 * @throws \Exception
	 * @throws \CoreException
	 */
	public function UpdateFromParams($aParamValues, $sModulesDir = null, $bPreserveModuleSettings = false)
	{
		if (isset($aParamValues['application_path']))
		{
			$this->Set('app_root_url', $aParamValues['application_path']);
		}
		if (isset($aParamValues['graphviz_path']))
		{
			$this->Set('graphviz_path', $aParamValues['graphviz_path']);
		}
		if (isset($aParamValues['mode']) && isset($aParamValues['language']))
		{
			if (($aParamValues['mode'] == 'install') || $this->GetDefaultLanguage() == '')
			{
				$this->SetDefaultLanguage($aParamValues['language']);
			}
		}
		if (isset($aParamValues['db_server']))
		{
			$this->Set('db_host', $aParamValues['db_server']);
			$this->Set('db_user', $aParamValues['db_user']);
			$this->Set('db_pwd', $aParamValues['db_pwd']);
			$sDBName = $aParamValues['db_name'];
			if ($sDBName == '')
			{
				// Todo - obsolete after the transition to the new setup (2.0) is complete (WARNING: used by the designer)
				if (isset($aParamValues['new_db_name']))
				{
					$sDBName = $aParamValues['new_db_name'];
				}
			}
			$this->Set('db_name', $sDBName);
			$this->Set('db_subname', $aParamValues['db_prefix']);

			$bDbTlsEnabled = (bool)$aParamValues['db_tls_enabled'];
			if ($bDbTlsEnabled)
			{
				$this->Set('db_tls.enabled', $bDbTlsEnabled, 'UpdateFromParams');
			}
			else
			{
				// disabled : we don't want parameter in the file
				$this->Set('db_tls.enabled', $bDbTlsEnabled, null);
			}
			$sDbTlsCa = $bDbTlsEnabled ? $aParamValues['db_tls_ca'] : null;
			if (isset($sDbTlsCa) && !empty($sDbTlsCa))
			{
				$this->Set('db_tls.ca', $sDbTlsCa, 'UpdateFromParams');
			}
			else
			{
				// empty parameter : we don't want it in the file
				$this->Set('db_tls.ca', null, null);
			}
		}

		if (isset($aParamValues['selected_modules']))
		{
			$aSelectedModules = explode(',', $aParamValues['selected_modules']);
		}
		else
		{
			$aSelectedModules = null;
		}
		$this->UpdateIncludes($sModulesDir, $aSelectedModules);

		if (isset($aParamValues['source_dir']))
		{
			$this->Set('source_dir', $aParamValues['source_dir']);
		}
	}

	/**
	 * Helper function to rebuild the default configuration and the list of includes from a directory and a list of
	 * selected modules
	 *
	 * @param string $sModulesDir The relative path to the directory to scan for modules (typically the 'env-xxx'
	 *     directory resulting from the compilation). If null nothing will be done.
	 * @param array $aSelectedModules An array of selected modules' identifiers. If null all modules found will be
	 *     considered as installed
	 *
	 * @throws Exception
	 */
	public function UpdateIncludes($sModulesDir, $aSelectedModules = null)
	{
		if ($sModulesDir === null)
		{
			return;
		}

		// Initialize the arrays below with default values for the application...
		$oEmptyConfig = new Config('dummy_file', false); // Do NOT load any config file, just set the default values
		$aAddOns = $oEmptyConfig->GetAddOns();

		$aModules = ModuleDiscovery::GetAvailableModules(array(APPROOT.$sModulesDir));
		foreach ($aModules as $sModuleId => $aModuleInfo)
		{
			list ($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
			if (is_null($aSelectedModules) || in_array($sModuleName, $aSelectedModules))
			{
				if (isset($aModuleInfo['settings']))
				{
					list ($sName, $sVersion) = ModuleDiscovery::GetModuleName($sModuleId);
					foreach ($aModuleInfo['settings'] as $sProperty => $value)
					{
						if (isset($this->m_aModuleSettings[$sName][$sProperty]))
						{
							// Do nothing keep the original value
						}
						else
						{
							$this->SetModuleSetting($sName, $sProperty, $value);
						}
					}
				}
				if (isset($aModuleInfo['installer']))
				{
					$sModuleInstallerClass = $aModuleInfo['installer'];
					if (!class_exists($sModuleInstallerClass))
					{
						throw new Exception("Wrong installer class: '$sModuleInstallerClass' is not a PHP class - Module: ".$aModuleInfo['label']);
					}
					if (!is_subclass_of($sModuleInstallerClass, 'ModuleInstallerAPI'))
					{
						throw new Exception("Wrong installer class: '$sModuleInstallerClass' is not derived from 'ModuleInstallerAPI' - Module: ".$aModuleInfo['label']);
					}
					$aCallSpec = array($sModuleInstallerClass, 'BeforeWritingConfig');
					call_user_func_array($aCallSpec, array($this));
				}
			}
		}
		$this->SetAddOns($aAddOns);
	}

	/**
	 * Helper: for an array of string, change the prefix when found
	 *
	 * @param array $aStrings
	 * @param string $sSearchPrefix
	 * @param string $sNewPrefix
	 */
	protected static function ChangePrefix(&$aStrings, $sSearchPrefix, $sNewPrefix)
	{
		foreach ($aStrings as &$sFile)
		{
			if (substr($sFile, 0, strlen($sSearchPrefix)) == $sSearchPrefix)
			{
				$sFile = $sNewPrefix.substr($sFile, strlen($sSearchPrefix));
			}
		}
	}

	/**
	 * Obsolete: kept only for backward compatibility of the Toolkit
	 * Quick and dirty way to clone a config file into another environment
	 *
	 * @param string $sSourceEnv
	 * @param string $sTargetEnv
	 */
	public function ChangeModulesPath($sSourceEnv, $sTargetEnv)
	{
		// Now does nothing since the includes are built into the environment itself
	}

	/**
	 * Pretty format a var_export'ed value so that (if possible) the identation is preserved on every line
	 *
	 * @param array $aParserValue
	 * @param mixed $value The value to export
	 * @param string $sIndentation The string to use to indent the text
	 * @param bool $bForceIndentation Forces the identation (enven if it breaks/changes an eval, for example to ouput a
	 *     value inside a comment)
	 *
	 * @return string The indented export string
	 */
	protected static function PrettyVarExport($aParserValue, $value, $sIndentation, $bForceIndentation = false)
	{
		if (is_array($aParserValue) && $aParserValue['found'])
		{
			return $aParserValue['value'];
		}

		$sExport = var_export($value, true);
		$sNiceExport = str_replace(array("\r\n", "\n", "\r"), "\n".$sIndentation, trim($sExport));
		if (!$bForceIndentation)
		{
			/** @var array $aImported */
			$aImported = null;
			eval('$aImported='.$sNiceExport.';');
			// Check if adding the identations at the beginning of each line
			// did not modify the values (in case of a string containing a line break)
			if ($aImported != $value)
			{
				$sNiceExport = $sExport;
			}
		}

		return $sNiceExport;
	}

}

class ConfigPlaceholdersResolver
{
	/**
	 * @var null|array
	 */
	private $aEnv;
	/**
	 * @var null|array
	 */
	private $aServer;

	public function __construct($aEnv = null, $aServer = null)
	{
		$this->aEnv = $aEnv ?: $_ENV;
		$this->aServer = $aServer ?: $_SERVER;
	}

	public function Resolve($rawValue)
	{
		if (empty($this->aEnv['ITOP_CONFIG_PLACEHOLDERS']) && empty($this->aServer['ITOP_CONFIG_PLACEHOLDERS']))
		{
			return $rawValue;
		}

		if (is_array($rawValue))
		{
			$aResolvedRawValue = array();
			foreach ($rawValue as $key => $value)
			{
				$aResolvedRawValue[$key] = $this->Resolve($value);
			}

			return $aResolvedRawValue;
		}

		if (!is_string($rawValue))
		{
			return $rawValue;
		}

		$sPattern = '/\%(env|server)\((\w+)\)(?:\?:(\w*))?\%/'; //3 capturing groups, ie `%env(HTTP_PORT)?:8080%` produce: `env` `HTTP_PORT` and `8080`.
		
		if (! preg_match_all($sPattern, $rawValue, $aMatchesCollection, PREG_SET_ORDER))
		{
			return $rawValue;
		}

		$sValue = $rawValue;
		foreach ($aMatchesCollection as $aMatches)
		{
			$sWholeMask = $aMatches[0];
			$sSource = $aMatches[1];
			$sKey = $aMatches[2];
			$sDefault = isset($aMatches[3]) ? $aMatches[3] : null;

			$sReplacement = $this->Get($sSource, $sKey, $sDefault, $sWholeMask);

			$sValue = str_replace($sWholeMask, $sReplacement, $sValue);
		}

		return $sValue;
	}

	private function Get($sSourceName, $sKey, $sDefault, $sWholeMask)
	{
		if ('env' == $sSourceName)
		{
			$aSource = $this->aEnv;
		}
		else if ('server' == $sSourceName)
		{
			$aSource = $this->aServer;
		}
		else
		{
			$sErrorMessage = sprintf('unsupported source name "%s" into "%s"', $sSourceName, $sWholeMask);
			IssueLog::Error($sErrorMessage, self::class, array($sSourceName, $sKey, $sDefault, $sWholeMask));
			throw new ConfigException($sErrorMessage);
		}

		if (array_key_exists($sKey, $aSource))
		{
			return $aSource[$sKey];
		}

		if (null !== $sDefault)
		{
			return $sDefault;
		}

		$sErrorMessage = sprintf('key "%s" not found into "%s" while expanding', $sSourceName, $sWholeMask);
		IssueLog::Error($sErrorMessage, self::class, array($sSourceName, $sKey, $sDefault, $sWholeMask));
		throw new ConfigException($sErrorMessage);
	}
}