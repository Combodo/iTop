<?php
// Copyright (C) 2010-2013 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


define('ITOP_APPLICATION', 'iTop');
define('ITOP_VERSION', '$ITOP_VERSION$');
define('ITOP_REVISION', '$WCREV$');
define('ITOP_BUILD_DATE', '$WCNOW$');

define('ACCESS_USER_WRITE', 1);
define('ACCESS_ADMIN_WRITE', 2);
define('ACCESS_FULL', ACCESS_USER_WRITE | ACCESS_ADMIN_WRITE);
define('ACCESS_READONLY', 0);

/**
 * Configuration read/write
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('coreexception.class.inc.php');
require_once('attributedef.class.inc.php'); // For the defines

class ConfigException extends CoreException
{
}

define ('DEFAULT_CHARACTER_SET', 'utf8');
define ('DEFAULT_COLLATION', 'utf8_unicode_ci');

define ('DEFAULT_LOG_GLOBAL', true);
define ('DEFAULT_LOG_NOTIFICATION', true);
define ('DEFAULT_LOG_ISSUE', true);
define ('DEFAULT_LOG_WEB_SERVICE', true);
define ('DEFAULT_LOG_QUERIES', false);

define ('DEFAULT_QUERY_CACHE_ENABLED', true);


define ('DEFAULT_MIN_DISPLAY_LIMIT', 10);
define ('DEFAULT_MAX_DISPLAY_LIMIT', 15);
define ('DEFAULT_STANDARD_RELOAD_INTERVAL', 5*60);
define ('DEFAULT_FAST_RELOAD_INTERVAL', 1*60);
define ('DEFAULT_SECURE_CONNECTION_REQUIRED', false);
define ('DEFAULT_ALLOWED_LOGIN_TYPES', 'form|basic|external');
define ('DEFAULT_EXT_AUTH_VARIABLE', '$_SERVER[\'REMOTE_USER\']');
define ('DEFAULT_ENCRYPTION_KEY', '@iT0pEncr1pti0n!'); // We'll use a random value, later...

/**
 * Config
 * configuration data (this class cannot not be localized, because it is responsible for loading the dictionaries)
 *
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
	protected $m_aDictionaries;

	protected $m_aModuleSettings;

	// New way to store the settings !
	//
	protected $m_aSettings = array(
		'app_env_label' => array(
			'type' => 'string',
			'description' => 'Label displayed to describe the current application environnment, defaults to the environment name (e.g. "production")',
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'app_root_url' => array(
			'type' => 'string',
			'description' => 'Root URL used for navigating within the application, or from an email to the application (you can put $SERVER_NAME$ as a placeholder for the server\'s name)',
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'app_icon_url' => array(
			'type' => 'string',
			'description' => 'Hyperlink to redirect the user when clicking on the application icon (in the main window, or login/logoff pages)',
			'default' => 'http://www.combodo.com/itop',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'skip_check_to_write' => array(
			'type' => 'bool',
			'description' => 'Disable data format and integrity checks to boost up data load (insert or update)',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'skip_check_ext_keys' => array(
			'type' => 'bool',
			'description' => 'Disable external key check when checking the value of attributes',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'skip_strong_security' => array(
			'type' => 'bool',
			'description' => 'Disable strong security - TEMPORY: this flag should be removed when we are more confident in the recent change in security',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'query_optimization_enabled' => array(
			'type' => 'bool',
			'description' => 'The queries are optimized based on the assumption that the DB integrity has been preserved. By disabling the optimization one can ensure that the fetched data is clean... but this can be really slower or not usable at all (some queries will exceed the allowed number of joins in MySQL: 61!)',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'query_indentation_enabled' => array(
			'type' => 'bool',
			'description' => 'For developpers: format the SQL queries for human analysis',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'disable_mandatory_ext_keys' => array(
			'type' => 'bool',
			'description' => 'For developpers: allow every external keys to be undefined',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'graphviz_path' => array(
			'type' => 'string',
			'description' => 'Path to the Graphviz "dot" executable for graphing objects lifecycle',
			'default' => '/usr/bin/dot',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'php_path' => array(
			'type' => 'string',
			'description' => 'Path to the php executable in CLI mode',
			'default' => 'php',
			'value' => 'php',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'session_name' => array(
			'type' => 'string',
			'description' => 'The name of the cookie used to store the PHP session id',
			'default' => 'iTop',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'max_combo_length' => array(
			'type' => 'integer',
			'description' => 'The maximum number of elements in a drop-down list. If more then an autocomplete will be used',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'min_autocomplete_chars' => array(
			'type' => 'integer',
			'description' => 'The minimum number of characters to type in order to trigger the "autocomplete" behavior',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'allow_target_creation' => array(
			'type' => 'bool',
			'description' => 'Displays the + button on external keys to create target objects',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		// Levels that trigger a confirmation in the CSV import/synchro wizard
		'csv_import_min_object_confirmation' => array(
			'type' => 'integer',
			'description' => 'Minimum number of objects to check for the confirmation percentages',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'csv_import_errors_percentage' => array(
			'type' => 'integer',
			'description' => 'Percentage of errors that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'csv_import_modifications_percentage' => array(
			'type' => 'integer',
			'description' => 'Percentage of modifications that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'csv_import_creations_percentage' => array(
			'type' => 'integer',
			'description' => 'Percentage of creations that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'csv_import_history_display' => array(
			'type' => 'bool',
			'description' => 'Display the history tab in the import wizard',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'access_mode' => array(
			'type' => 'integer',
			'description' => 'Combination of flags (ACCESS_USER_WRITE | ACCESS_ADMIN_WRITE, or ACCESS_FULL)',
			'default' => ACCESS_FULL,
			'value' => ACCESS_FULL,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'access_message' => array(
			'type' => 'string',
			'description' => 'Message displayed to the users when there is any access restriction',
			'default' => 'iTop is temporarily frozen, please wait... (the admin team)',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'online_help' => array(
			'type' => 'string',
			'description' => 'Hyperlink to the online-help web page',
			'default' => 'http://www.combodo.com/itop-help',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'log_usage' => array(
			'type' => 'bool',
			'description' => 'Log the usage of the application (i.e. the date/time and the user name of each login)',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'synchro_trace' => array(
			'type' => 'string',
			'description' => 'Synchronization details: none, display, save (includes \'display\')',
			'default' => 'none',
			'value' => 'none',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'link_set_item_separator' => array(
			'type' => 'string',
			'description' => 'Link set from string: line separator',
			'default' => '|',
			'value' => '|',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'link_set_attribute_separator' => array(
			'type' => 'string',
			'description' => 'Link set from string: attribute separator',
			'default' => ';',
			'value' => ';',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'link_set_value_separator' => array(
			'type' => 'string',
			'description' => 'Link set from string: value separator (between the attcode and the value itself',
			'default' => ':',
			'value' => ':',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'link_set_attribute_qualifier' => array(
			'type' => 'string',
			'description' => 'Link set from string: attribute qualifier (encloses both the attcode and the value)',
			'default' => "'",
			'value' => "'",
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'cron_max_execution_time' => array(
			'type' => 'integer',
			'description' => 'Duration (seconds) of the page cron.php, must be shorter than php setting max_execution_time and shorter than the web server response timeout',
			'default' => 600,
			'value' => 600,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'cron_sleep' => array(
			'type' => 'integer',
			'description' => 'Duration (seconds) before cron.php checks again if something must be done',
			'default' => 2,
			'value' => 2,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'async_task_retries' => array(
			'type' => 'array',
			'description' => 'Automatic retries of asynchronous tasks in case of failure (per class)',
			'default' => array('AsyncSendEmail' => array('max_retries' => 0, 'retry_delay' => 600)),
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'email_asynchronous' => array(
			'type' => 'bool',
			'description' => 'If set, the emails are sent off line, which requires cron.php to be activated. Exception: some features like the email test utility will force the serialized mode',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'email_transport' => array(
			'type' => 'string',
			'description' => 'Mean to send emails: PHPMail (uses the function mail()) or SMTP (implements the client protocole)',
			'default' => "PHPMail",
			'value' => "PHPMail",
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'email_transport_smtp.host' => array(
			'type' => 'string',
			'description' => 'host name or IP address (optional)',
			'default' => "localhost",
			'value' => "localhost",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'email_transport_smtp.port' => array(
			'type' => 'integer',
			'description' => 'port number (optional)',
			'default' => 25,
			'value' => 25,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'email_transport_smtp.encryption' => array(
			'type' => 'string',
			'description' => 'tls or ssl (optional)',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'email_transport_smtp.username' => array(
			'type' => 'string',
			'description' => 'Authentication user (optional)',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'email_transport_smtp.password' => array(
			'type' => 'string',
			'description' => 'Authentication password (optional)',
			'default' => "",
			'value' => "",
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'apc_cache.enabled' => array(
			'type' => 'bool',
			'description' => 'If set, the APC cache is allowed (the PHP extension must also be active)',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'apc_cache.query_ttl' => array(
			'type' => 'integer',
			'description' => 'Time to live set in APC for the prepared queries (seconds - 0 means no timeout)',
			'default' => 3600,
			'value' => 3600,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'timezone' => array(
			'type' => 'string',
			'description' => 'Timezone (reference: http://php.net/manual/en/timezones.php). If empty, it will be left unchanged and MUST be explicitely configured in PHP',
			// examples... not used (nor 'description')
			'examples' => array('America/Sao_Paulo', 'America/New_York (standing for EDT)', 'America/Los_Angeles (standing for PDT)', 'Asia/Istanbul', 'Asia/Singapore', 'Africa/Casablanca', 'Australia/Sydney'),
			'default' => 'Europe/Paris',
			'value' => 'Europe/Paris',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'cas_include_path' => array(
			'type' => 'string',
			'description' => 'The path where to find the phpCAS library',
			// examples... not used (nor 'description')
			'default' => '/usr/share/php',
			'value' => '/usr/share/php',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'cas_version' => array(
			'type' => 'string',
			'description' => 'The CAS protocol version to use: "1.0" (CAS v1), "2.0" (CAS v2) or "S1" (SAML V1) )',
			// examples... not used (nor 'description')
			'default' => '2.0',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_host' => array(
			'type' => 'string',
			'description' => 'The name of the CAS host',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_port' => array(
			'type' => 'integer',
			'description' => 'The port used by the CAS server',
			// examples... not used (nor 'description')
			'default' => 443,
			'value' => 443,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_context' => array(
			'type' => 'string',
			'description' => 'The CAS context',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_server_ca_cert_path' => array(
			'type' => 'string',
			'description' => 'The path where to find the certificate of the CA for validating the certificate of the CAS server',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_logout_redirect_service' => array(
			'type' => 'string',
			'description' => 'The redirect service (URL) to use when logging-out with CAS',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_memberof' => array(
			'type' => 'string',
			'description' => 'A semicolon separated list of group names that the user must be member of (works only with SAML - e.g. cas_version=> "S1")',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_user_synchro' => array(
			'type' => 'bool',
			'description' => 'Whether or not to synchronize users with CAS/LDAP',
			// examples... not used (nor 'description')
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_update_profiles' => array(
			'type' => 'bool',
			'description' => 'Whether or not to update the profiles of an existing user from the CAS information',
			// examples... not used (nor 'description')
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_profile_pattern' => array(
			'type' => 'string',
			'description' => 'A regular expression pattern to extract the name of the iTop profile from the name of an LDAP/CAS group',
			// examples... not used (nor 'description')
			'default' => '/^cn=([^,]+),/',
			'value' => '/^cn=([^,]+),/',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_default_profiles' => array(
			'type' => 'string',
			'description' => 'A semi-colon separated list of iTop Profiles to use when creating a new user if no profile is retrieved from CAS',
			// examples... not used (nor 'description')
			'default' => 'Portal user',
			'value' => 'Portal user',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'cas_debug' => array(
			'type' => 'bool',
			'description' => 'Activate the CAS debug',
			// examples... not used (nor 'description')
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'forgot_password' => array(
			'type' => 'bool',
			'description' => 'Enable the "Forgot password" feature',
			// examples... not used (nor 'description')
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'forgot_password_from' => array(
			'type' => 'string',
			'description' => 'Sender email address for the "forgot password" feature. If empty, defaults to the recipient\'s  email address.',
			// examples... not used (nor 'description')
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'deadline_format' => array(
			'type' => 'string',
			'description' => 'The format used for displaying "deadline" attributes: any string with the following placeholders: $date$, $difference$',
			// examples... $date$ ($deadline$)
			'default' => '$difference$',
			'value' => '$difference$',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'buttons_position' => array(
			'type' => 'string',
			'description' => 'Position of the forms buttons: bottom | top | both',
			// examples... not used
			'default' => 'both',
			'value' => 'both',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'shortcut_actions' => array(
			'type' => 'string',
			'description' => 'Actions that are available as direct buttons next to the "Actions" menu',
			// examples... not used
			'default' => 'UI:Menu:Modify,UI:Menu:New',
			'value' => 'UI:Menu:Modify',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'complex_actions_limit' => array(
			'type' => 'integer',
			'description' => 'Display the "actions" menu items that require long computation only if the list of objects is contains less objects than this number (0 means no limit)',
			// examples... not used
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'synchro_prevent_delete_all' => array(
			'type' => 'bool',
			'description' => 'Stop the synchro if all the replicas of a data source become obsolete at the same time.',
			// examples... not used
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'source_dir' => array(
			'type' => 'string',
			'description' => 'Source directory for the datamodel files. (which gets compiled to env-production).',
			// examples... not used
			'default' => '',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'csv_file_default_charset' => array(
			'type' => 'string',
			'description' => 'Character set used by default for downloading and uploading data as a CSV file. Warning: it is case sensitive (uppercase is preferable).',
			// examples... not used
			'default' => 'ISO-8859-1',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'debug_report_spurious_chars' => array(
			'type' => 'bool',
			'description' => 'Report, in the error log, the characters found in the output buffer, echoed by mistake in the loaded modules, and potentially corrupting the output',
			// examples... not used
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'impact_analysis_first_tab' => array(
			'type' => 'string',
			'description' => 'Which tab to display first in the impact analysis view: list or graphics. Graphics are nicer but slower to display when there are many objects',
			// examples... not used
			'default' => 'graphics',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'url_validation_pattern' => array(
			'type' => 'string',
			'description' => 'Regular expression to validate/detect the format of an URL (URL attributes and Wiki formatting for Text attributes)',
			'default' => '(https?|ftp)\://([a-zA-Z0-9+!*(),;?&=\$_.-]+(\:[a-zA-Z0-9+!*(),;?&=\$_.-]+)?@)?([a-zA-Z0-9-.]{3,})(\:[0-9]{2,5})?(/([a-zA-Z0-9%+\$_-]\.?)+)*/?(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:[\]@&%=+/\$_.-]*)?(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?',
			//            SHEME.......... USER....................... PASSWORD.......................... HOST/IP........... PORT.......... PATH........................ GET............................................ ANCHOR............................
			// Example: http://User:passWord@127.0.0.1:8888/patH/Page.php?arrayArgument[2]=something:blah20#myAnchor
			// Origin of this regexp: http://www.php.net/manual/fr/function.preg-match.php#93824
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'email_validation_pattern' => array(
			'type' => 'string',
			'description' => 'Regular expression to validate/detect the format of an eMail address',
			'default' => "[a-zA-Z0-9._&'-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]{2,}",
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'log_kpi_duration' => array(
			'type' => 'integer',
			'description' => 'Level of logging for troubleshooting performance issues (1 to enable, 2 +blame callers)',
			// examples... not used
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'log_kpi_memory' => array(
			'type' => 'integer',
			'description' => 'Level of logging for troubleshooting memory limit issues',
			// examples... not used
			'default' => 0,
			'value' => 0,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'log_kpi_user_id' => array(
			'type' => 'string',
			'description' => 'Limit the scope of users to the given user id (* means no limit)',
			// examples... not used
			'default' => '*',
			'value' => '*',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'max_linkset_output' => array(
			'type' => 'integer',
			'description' => 'Maximum number of items shown when getting a list of related items in an email, using the form $this->some_list$. 0 means no limit.',
			'default' => 100,
			'value' => 100,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'demo_mode' => array(
			'type' => 'bool',
			'description' => 'Set to true to prevent users from changing passwords/languages',
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'portal_tickets' => array(
			'type' => 'string',
			'description' => 'CSV list of classes supported in the portal',
			// examples... not used
			'default' => 'UserRequest',
			'value' => 'UserRequest',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'max_execution_time_per_loop' => array(
			'type' => 'integer',
			'description' => 'Maximum execution time requested, per loop, during bulk operations. Zero means no limit.',
			// examples... not used
			'default' => 30,
			'value' => 30,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'max_history_length' => array(
			'type' => 'integer',
			'description' => 'Maximum length of the history table (in the "History" tab on each object) before it gets truncated. Latest modifications are displayed first.',
			// examples... not used
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'full_text_chunk_duration' => array(
			'type' => 'integer',
			'description' => 'Delay after which the results are displayed.',
			// examples... not used
			'default' => 2,
			'value' => 2,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'full_text_accelerators' => array(
			'type' => 'array',
			'description' => 'Specifies classes to be searched at first (and the subset of data) when running the full text search.',
			'default' => array(),
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'full_text_needle_min' => array(
			'type' => 'integer',
			'description' => 'Minimum size of the full text needle.',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'tracking_level_linked_set_default' => array(
			'type' => 'integer',
			'description' => 'Default tracking level if not explicitely set at the attribute level, for AttributeLinkedSet (defaults to NONE in case of a fresh install, LIST otherwise - this to preserve backward compatibility while upgrading from a version older than 2.0.3 - see TRAC #936)',
			'default' => LINKSET_TRACKING_LIST,
			'value' => LINKSET_TRACKING_LIST,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'tracking_level_linked_set_indirect_default' => array(
			'type' => 'integer',
			'description' => 'Default tracking level if not explicitely set at the attribute level, for AttributeLinkedSetIndirect',
			'default' => LINKSET_TRACKING_ALL,
			'value' => LINKSET_TRACKING_ALL,
			'source_of_value' => '',
			'show_in_conf_sample' => false, 
		),
		'user_rights_legacy' => array(
			'type' => 'bool',
			'description' => 'Set to true to restore the buggy algorithm for the computation of user rights (within the same profile, ALLOW on the class itself has precedence on DENY of a parent class)',
			'default' => false,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'xlsx_exporter_cleanup_old_files_delay' => array(
			'type' => 'int',
			'description' => 'Delay (in seconds) for which to let the exported XLSX files on the server so that the user who initiated the export can download the result',
			'default' => 86400,
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'xlsx_exporter_memory_limit' => array(
			'type' => 'string',
			'description' => 'Memory limit to use when (interactively) exporting data to Excel',
			'default' => '2048M', // Huuuuuuge 2GB!
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'min_reload_interval' => array(
			'type' => 'integer',
			'description' => 'Minimum refresh interval (seconds) for dashboards, shortcuts, etc. Even if the interval is set programmatically, it is forced to that minimum',
			'default' => 5, // In iTop 2.0.3, this was the hardcoded value
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'relations_max_depth' => array(
			'type' => 'integer',
			'description' => 'Maximum number of successive levels (depth) to explore when displaying the impact/depends on relations.',
			'default' => 20, // In iTop 2.0.3, this was the hardcoded value
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'transaction_storage' => array(
			'type' => 'string',
			'description' => 'The type of mechanism to use for storing the unique identifiers for transactions (Session|File).',
			'default' => 'Session',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
	);

	public function IsProperty($sPropCode)
	{
		return (array_key_exists($sPropCode, $this->m_aSettings));
	}
	public function GetDescription($sPropCode)
	{
		return $this->m_aSettings[$sPropCode];
	}

	public function Set($sPropCode, $value, $sSourceDesc = 'unknown')
	{
		$sType = $this->m_aSettings[$sPropCode]['type'];
		switch($sType)
		{
		case 'bool':
			$value = (bool) $value;
			break;
		case 'string':
			$value = (string) $value;
			break;
		case 'integer':
			$value = (integer) $value;
			break;
		case 'float':
			$value = (float) $value;
			break;
		case 'array':
			break;
		default:
			throw new CoreException('Unknown type for setting', array('property' => $sPropCode, 'type' => $sType));
		}
		$this->m_aSettings[$sPropCode]['value'] = $value;
		$this->m_aSettings[$sPropCode]['source_of_value'] = $sSourceDesc;

	}

	public function Get($sPropCode)
	{
		return $this->m_aSettings[$sPropCode]['value'];
	}

	// Those variables will be deprecated later, when the transition to ...Get('my_setting') will be done
	protected $m_sDBHost;
	protected $m_sDBUser;
	protected $m_sDBPwd;
	protected $m_sDBName;
	protected $m_sDBSubname;
	protected $m_sDBCharacterSet;
	protected $m_sDBCollation;

	/**
	 * Event log options (see LOG_... definition)
	 */	 	
	// Those variables will be deprecated later, when the transition to ...Get('my_setting') will be done
	protected $m_bLogGlobal;
	protected $m_bLogNotification;
	protected $m_bLogIssue;
	protected $m_bLogWebService;
	protected $m_bLogQueries; // private setting
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
	 * @var array Additional character sets to be supported by the interactive CSV import
	 *            'iconv_code' => 'display name'
	 */
	 protected $m_aCharsets;

	public function __construct($sConfigFile = null, $bLoadConfig = true)
	{
		$this->m_sFile = $sConfigFile;
		if (is_null($sConfigFile))
		{
			$bLoadConfig = false;
		}

		$this->m_aAppModules = array(
			// Some default modules, always present can be move to an official iTop Module later if needed
			'application/transaction.class.inc.php',
			'application/menunode.class.inc.php',
			'application/user.preferences.class.inc.php',
			'application/user.dashboard.class.inc.php',
			'application/audit.rule.class.inc.php',
			'application/query.class.inc.php',
// Romain - That's dirty, because those classes are in fact part of the core
//          but I needed those classes to be derived from cmdbAbstractObject
//          (to be managed via the GUI) and this class in not really known from
//          the core, PLUS I needed the includes to be there also for the setup
//          to create the tables.
			'core/event.class.inc.php',
			'core/action.class.inc.php',
			'core/trigger.class.inc.php',
			'synchro/synchrodatasource.class.inc.php',
			'core/backgroundtask.class.inc.php',
		);
		$this->m_aDataModels = array();
		$this->m_aWebServiceCategories = array(
			'webservices/webservices.basic.php',
		);
		$this->m_aAddons = array(
			// Default AddOn, always present can be moved to an official iTop Module later if needed
			'user rights' => 'addons/userrights/userrightsprofile.class.inc.php',
		);
		$this->m_aDictionaries = self::ScanDictionariesDir();
		
		foreach($this->m_aSettings as $sPropCode => $aSettingInfo)
		{
			$this->m_aSettings[$sPropCode]['value'] = $aSettingInfo['default'];
		}

		$this->m_sDBHost = '';
		$this->m_sDBUser = '';
		$this->m_sDBPwd = '';
		$this->m_sDBName = '';
		$this->m_sDBSubname = '';
		$this->m_sDBCharacterSet = DEFAULT_CHARACTER_SET;
		$this->m_sDBCollation = DEFAULT_COLLATION;
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
		$this->m_sEncryptionKey = DEFAULT_ENCRYPTION_KEY;
		$this->m_aCharsets = array();
		
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

	protected function CheckFile($sPurpose, $sFileName)
	{
		if (!file_exists($sFileName))
		{
			throw new ConfigException("Could not find $sPurpose file", array('file' => $sFileName));
		}
		if (!is_readable($sFileName))
		{
			throw new ConfigException("Could not read $sPurpose file (the file exists but cannot be read). Do you have the rights to access this file?", array('file' => $sFileName));			
		}
	}

	protected function Load($sConfigFile)
	{
		$this->CheckFile('configuration', $sConfigFile);

		$sConfigCode = trim(file_get_contents($sConfigFile));

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
		catch (Exception $e)
		{
			// well, never reach in case of parsing error :-(
			// will be improved in PHP 6 ?
			throw new ConfigException('Error in configuration file', array('file' => $sConfigFile, 'error' => $e->getMessage()));
		}
		if (strlen($sNoise) > 0)
		{
			// Note: sNoise is an html output, but so far it was ok for me (e.g. showing the entire call stack) 
			throw new ConfigException('Syntax error in configuration file', array('file' => $sConfigFile, 'error' => '<tt>'.htmlentities($sNoise, ENT_QUOTES, 'UTF-8').'</tt>'));
		}

		if (!isset($MySettings) || !is_array($MySettings))
		{
			throw new ConfigException('Missing array in configuration file', array('file' => $sConfigFile, 'expected' => '$MySettings'));
		}
		if (!isset($MyModules) || !is_array($MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules'));
		}
		if (!array_key_exists('application', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'application\']'));
		}
		if (!array_key_exists('business', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'business\']'));
		}
		if (!array_key_exists('addons', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'addons\']'));
		}
		if (!array_key_exists('user rights', $MyModules['addons']))
		{
			// Add one, by default
			$MyModules['addons']['user rights'] = '/addons/userrights/userrightsnull.class.inc.php';
		}
		if (!array_key_exists('dictionaries', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'dictionaries\']'));
		}
		$this->m_aAppModules = $MyModules['application'];
		$this->m_aDataModels = $MyModules['business'];
		if (isset($MyModules['webservices']))
		{
			$this->m_aWebServiceCategories = $MyModules['webservices'];
		}
		$this->m_aAddons = $MyModules['addons'];
		$this->m_aDictionaries = $MyModules['dictionaries'];

		foreach($MySettings as $sPropCode => $rawvalue)
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
				$this->Set($sPropCode, $value, $sConfigFile);
			}
		}

		$this->m_sDBHost = trim($MySettings['db_host']);
		$this->m_sDBUser = trim($MySettings['db_user']);
		$this->m_sDBPwd = trim($MySettings['db_pwd']);
		$this->m_sDBName = trim($MySettings['db_name']);
		$this->m_sDBSubname = trim($MySettings['db_subname']);

		$this->m_sDBCharacterSet = isset($MySettings['db_character_set']) ? trim($MySettings['db_character_set']) : DEFAULT_CHARACTER_SET;
		$this->m_sDBCollation = isset($MySettings['db_collation']) ? trim($MySettings['db_collation']) : DEFAULT_COLLATION;

		$this->m_bLogGlobal = isset($MySettings['log_global']) ? (bool) trim($MySettings['log_global']) : DEFAULT_LOG_GLOBAL;
		$this->m_bLogNotification = isset($MySettings['log_notification']) ? (bool) trim($MySettings['log_notification']) : DEFAULT_LOG_NOTIFICATION;
		$this->m_bLogIssue = isset($MySettings['log_issue']) ? (bool) trim($MySettings['log_issue']) : DEFAULT_LOG_ISSUE;
		$this->m_bLogWebService = isset($MySettings['log_web_service']) ? (bool) trim($MySettings['log_web_service']) : DEFAULT_LOG_WEB_SERVICE;
		$this->m_bLogQueries = isset($MySettings['log_queries']) ? (bool) trim($MySettings['log_queries']) : DEFAULT_LOG_QUERIES;
		$this->m_bQueryCacheEnabled = isset($MySettings['query_cache_enabled']) ? (bool) trim($MySettings['query_cache_enabled']) : DEFAULT_QUERY_CACHE_ENABLED;

		$this->m_iMinDisplayLimit = isset($MySettings['min_display_limit']) ? trim($MySettings['min_display_limit']) : DEFAULT_MIN_DISPLAY_LIMIT;
		$this->m_iMaxDisplayLimit = isset($MySettings['max_display_limit']) ? trim($MySettings['max_display_limit']) : DEFAULT_MAX_DISPLAY_LIMIT;
		$this->m_iStandardReloadInterval = isset($MySettings['standard_reload_interval']) ? trim($MySettings['standard_reload_interval']) : DEFAULT_STANDARD_RELOAD_INTERVAL;
		$this->m_iFastReloadInterval = isset($MySettings['fast_reload_interval']) ? trim($MySettings['fast_reload_interval']) : DEFAULT_FAST_RELOAD_INTERVAL;
		$this->m_bSecureConnectionRequired = isset($MySettings['secure_connection_required']) ? (bool) trim($MySettings['secure_connection_required']) : DEFAULT_SECURE_CONNECTION_REQUIRED;

		$this->m_aModuleSettings = isset($MyModuleSettings) ?  $MyModuleSettings : array();

		$this->m_sDefaultLanguage = isset($MySettings['default_language']) ? trim($MySettings['default_language']) : 'EN US';
		$this->m_sAllowedLoginTypes = isset($MySettings['allowed_login_types']) ? trim($MySettings['allowed_login_types']) : DEFAULT_ALLOWED_LOGIN_TYPES;
		$this->m_sExtAuthVariable = isset($MySettings['ext_auth_variable']) ? trim($MySettings['ext_auth_variable']) : DEFAULT_EXT_AUTH_VARIABLE;
		$this->m_sEncryptionKey = isset($MySettings['encryption_key']) ? trim($MySettings['encryption_key']) : DEFAULT_ENCRYPTION_KEY;
		$this->m_aCharsets = isset($MySettings['csv_import_charsets']) ? $MySettings['csv_import_charsets'] : array();
	}

	protected function Verify()
	{
		// Files are verified later on, just before using them -see MetaModel::Plugin()
		// (we have their final path at that point)
	}

	public function GetModuleSetting($sModule, $sProperty, $defaultvalue = null)
	{
		if (isset($this->m_aModuleSettings[$sModule][$sProperty]))
		{
			return $this->m_aModuleSettings[$sModule][$sProperty];
		}
		return $defaultvalue;
	}

	public function SetModuleSetting($sModule, $sProperty, $value)
	{
		$this->m_aModuleSettings[$sModule][$sProperty] = $value;
	}

	public function GetAppModules()
	{
		return $this->m_aAppModules;
	}
	public function SetAppModules($aAppModules)
	{
		$this->m_aAppModules = $aAppModules;
	}

	public function GetDataModels()
	{
		return $this->m_aDataModels;
	}
	public function SetDataModels($aDataModels)
	{
		$this->m_aDataModels = $aDataModels;
	}

	public function GetWebServiceCategories()
	{
		return $this->m_aWebServiceCategories;
	}
	public function SetWebServiceCategories($aWebServiceCategories)
	{
		$this->m_aWebServiceCategories = $aWebServiceCategories;
	}

	public function GetAddons()
	{
		return $this->m_aAddons;
	}
	public function SetAddons($aAddons)
	{
		$this->m_aAddons = $aAddons;
	}

	public function GetDictionaries()
	{
		return $this->m_aDictionaries;
	}
	public function SetDictionaries($aDictionaries)
	{
		$this->m_aDictionaries = $aDictionaries;
	}

	public function GetDBHost()
	{
		return $this->m_sDBHost;
	}
	
	public function GetDBName()
	{
		return $this->m_sDBName;
	}

	public function GetDBSubname()
	{
		return $this->m_sDBSubname;
	}

	public function GetDBCharacterSet()
	{
		return $this->m_sDBCharacterSet;
	}

	public function GetDBCollation()
	{
		return $this->m_sDBCollation;
	}

	public function GetDBUser()
	{
		return $this->m_sDBUser;
	}

	public function GetDBPwd()
	{
		return $this->m_sDBPwd;
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
		return $this->m_bLogQueries;
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
	
	public function SetDBHost($sDBHost)
	{
		$this->m_sDBHost = $sDBHost;
	}
	
	public function SetDBName($sDBName)
	{
		$this->m_sDBName = $sDBName;
	}

	public function SetDBSubname($sDBSubName)
	{
		$this->m_sDBSubname = $sDBSubName;
	}

	public function SetDBCharacterSet($sDBCharacterSet)
	{
		$this->m_sDBCharacterSet = $sDBCharacterSet;
	}

	public function SetDBCollation($sDBCollation)
	{
		$this->m_sDBCollation = $sDBCollation;
	}

	public function SetDBUser($sUser)
	{
		$this->m_sDBUser = $sUser;
	}

	public function SetDBPwd($sPwd)
	{
		$this->m_sDBPwd = $sPwd;
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
	 * @return boolean True otherwise throws an Exception
	 */	 	 	 	 	
	public function ToArray()
	{
		$aSettings = array();
		foreach($this->m_aSettings as $sPropCode => $aSettingInfo)
		{
			$aSettings[$sPropCode] = $aSettingInfo['value'];
		}
		$aSettings['db_host'] = $this->m_sDBHost;
		$aSettings['db_user'] = $this->m_sDBUser;
		$aSettings['db_pwd'] = $this->m_sDBPwd;
		$aSettings['db_name'] = $this->m_sDBName;
		$aSettings['db_subname'] = $this->m_sDBSubname;
		$aSettings['db_character_set'] = $this->m_sDBCharacterSet;
		$aSettings['db_collation'] = $this->m_sDBCollation;
		$aSettings['log_global'] = $this->m_bLogGlobal;
		$aSettings['log_notification'] = $this->m_bLogNotification;
		$aSettings['log_issue'] = $this->m_bLogIssue;
		$aSettings['log_web_service'] = $this->m_bLogWebService;
		$aSettings['min_display_limit'] = $this->m_iMinDisplayLimit;
		$aSettings['max_display_limit'] = $this->m_iMaxDisplayLimit;
		$aSettings['standard_reload_interval'] = $this->m_iStandardReloadInterval;
		$aSettings['fast_reload_interval'] = $this->m_iFastReloadInterval;
		$aSettings['secure_connection_required'] = $this->m_bSecureConnectionRequired;
		$aSettings['default_language'] = $this->m_sDefaultLanguage;
		$aSettings['allowed_login_types'] = $this->m_sAllowedLoginTypes;
		$aSettings['encryption_key'] = $this->m_sEncryptionKey;
		$aSettings['csv_import_charsets'] = $this->m_aCharsets;

		foreach ($this->m_aModuleSettings as $sModule => $aProperties)
		{
			foreach ($aProperties as $sProperty => $value)
			{
				$aSettings['module_settings'][$sModule][$sProperty] = $value;
			}
		}
		foreach($this->m_aAppModules as $sFile)
		{
			$aSettings['application_list'][] = $sFile;
		}
		foreach($this->m_aDataModels as $sFile)
		{
			$aSettings['datamodel_list'][] = $sFile;
		}
		foreach($this->m_aWebServiceCategories as $sFile)
		{
			$aSettings['webservice_list'][] = $sFile;
		}
		foreach($this->m_aAddons as $sKey => $sFile)
		{
			$aSettings['addon_list'][] = $sFile;
		}
		foreach($this->m_aDictionaries as $sFile)
		{
			$aSettings['dictionary_list'][] = $sFile;
		}
		return $aSettings;
	}

	/**
	 * Write the configuration to a file (php format) that can be reloaded later
	 * By default write to the same file that was specified when constructing the object
	 * @param $sFileName string Name of the file to write to (emtpy to write to the same file)
	 * @return boolean True otherwise throws an Exception
	 */	 	 	 	 	
	public function WriteToFile($sFileName = '')
	{
		if (empty($sFileName))
		{
			$sFileName = $this->m_sFile;
		}
		$hFile = @fopen($sFileName, 'w');
		if ($hFile !== false)
		{
			fwrite($hFile, "<?php\n");
			fwrite($hFile, "\n/**\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * Configuration file, generated by the ".ITOP_APPLICATION." configuration wizard\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * The file is used in MetaModel::LoadConfig() which does all the necessary initialization job\n");
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
			foreach($aBoolValues as $sKey => $bValue)
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
			foreach($aIntValues as $sKey => $iValue)
			{
				$aConfigSettings[$sKey] = array(
					'show_in_conf_sample' => true,
					'type' => 'integer',
					'value' => $iValue,
				);
			}

			// Old fashioned remaining values
			$aOtherValues = array(
				'db_host' => $this->m_sDBHost,
				'db_user' => $this->m_sDBUser,
				'db_pwd' => $this->m_sDBPwd,
				'db_name' => $this->m_sDBName,
				'db_subname' => $this->m_sDBSubname,
				'db_character_set' => $this->m_sDBCharacterSet,
				'db_collation' => $this->m_sDBCollation,
				'default_language' => $this->m_sDefaultLanguage,
				'allowed_login_types' => $this->m_sAllowedLoginTypes,
				'encryption_key' => $this->m_sEncryptionKey,
				'csv_import_charsets' => $this->m_aCharsets,
			);
			foreach($aOtherValues as $sKey => $value)
			{
				$aConfigSettings[$sKey] = array(
					'show_in_conf_sample' => true,
					'type' => is_string($value) ? 'string' : 'mixed',
					'value' => $value,
				);
			}
			
			ksort($aConfigSettings);
			fwrite($hFile, "\$MySettings = array(\n");
			foreach($aConfigSettings as $sPropCode => $aSettingInfo)
			{
				// Write all values that are either always visible or present in the cloned config file
				if ($aSettingInfo['show_in_conf_sample'] || (!empty($aSettingInfo['source_of_value']) && ($aSettingInfo['source_of_value'] != 'unknown')) )
				{
					$sType = $aSettingInfo['type'];
					switch($sType)
					{
					case 'bool':
						$sSeenAs = $aSettingInfo['value'] ? 'true' : 'false';
						break;
					default:
						$sSeenAs = self::PrettyVarExport($aSettingInfo['value'], "\t");
					}
					fwrite($hFile, "\n");
					if (isset($aSettingInfo['description']))
					{
						fwrite($hFile, "\t// $sPropCode: {$aSettingInfo['description']}\n");
					}
					if (isset($aSettingInfo['default']))
					{
						$default = $aSettingInfo['default'];
						if ($aSettingInfo['type'] == 'bool')
						{
							$default = $default ? 'true' : 'false';
						}
						fwrite($hFile, "\t//\tdefault: ".self::PrettyVarExport($aSettingInfo['default'],"\t//\t\t", true)."\n");
					}
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
					$sNiceExport = self::PrettyVarExport($value, "\t\t");
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
			fwrite($hFile, "\t'application' => array (\n");
			foreach($this->m_aAppModules as $sFile)
			{
				fwrite($hFile, "\t\t'$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'business' => array (\n");
			foreach($this->m_aDataModels as $sFile)
			{
				fwrite($hFile, "\t\t'$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'webservices' => array (\n");
			foreach($this->m_aWebServiceCategories as $sFile)
			{
				fwrite($hFile, "\t\t'$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'addons' => array (\n");
			foreach($this->m_aAddons as $sKey => $sFile)
			{
				fwrite($hFile, "\t\t'$sKey' => '$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'dictionaries' => array (\n");
			foreach($this->m_aDictionaries as $sFile)
			{
				fwrite($hFile, "\t\t'$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, ");\n");
			fwrite($hFile, '?'.'>'); // Avoid perturbing the syntax highlighting !
			return fclose($hFile);
		}
		else
		{
			throw new ConfigException("Could not write to configuration file", array('file' => $sFileName));
		}
	}
	
	protected static function ScanDictionariesDir()
	{
		$aResult = array();
		// Populate automatically the list of dictionary files
		$sDir = APPROOT.'/dictionaries';
		if ($hDir = @opendir($sDir))
		{
			while (($sFile = readdir($hDir)) !== false)
			{
				$aMatches = array();
				if (preg_match("/^([^\.]+\.)?dictionary\.itop\.(ui|core)\.php$/i", $sFile, $aMatches)) // Dictionary files named like [<Lang>.]dictionary.[core|ui].php are loaded automatically
				{
					$aResult[] = 'dictionaries/'.$sFile;
				}
			}
			closedir($hDir);
		}
		return $aResult;
	}

	/**
	 * Helper function to initialize a configuration from the page arguments
	 */
	public function UpdateFromParams($aParamValues, $sModulesDir = null, $bPreserveModuleSettings = false)
	{
		if (isset($aParamValues['application_path']))
		{
			$this->Set('app_root_url', $aParamValues['application_path']);
		}
		if (isset($aParamValues['mode']) && isset($aParamValues['language']))
		{
			if (($aParamValues['mode'] == 'install') ||  $this->GetDefaultLanguage() == '')
			{
				$this->SetDefaultLanguage($aParamValues['language']);
			}
		}
		if (isset($aParamValues['db_server']))
		{
			$this->SetDBHost($aParamValues['db_server']);
			$this->SetDBUser($aParamValues['db_user']);
			$this->SetDBPwd($aParamValues['db_pwd']);
			$sDBName = $aParamValues['db_name'];
			if ($sDBName == '')
			{
				// Todo - obsolete after the transition to the new setup (2.0) is complete (WARNING: used by the designer)
				$sDBName = $aParamValues['new_db_name'];
			}
			$this->SetDBName($sDBName);
			$this->SetDBSubname($aParamValues['db_prefix']);
		}
	
		if (!is_null($sModulesDir))
		{
			if (isset($aParamValues['selected_modules']))
			{
				$aSelectedModules = explode(',', $aParamValues['selected_modules']);
			}
			else
			{
				$aSelectedModules = null;
			}
	
			// Initialize the arrays below with default values for the application...
			$oEmptyConfig = new Config('dummy_file', false); // Do NOT load any config file, just set the default values
			$aAddOns = $oEmptyConfig->GetAddOns();
			$aAppModules = $oEmptyConfig->GetAppModules();
			$aDataModels = $oEmptyConfig->GetDataModels();
			$aWebServiceCategories = $oEmptyConfig->GetWebServiceCategories();
			$aDictionaries = $oEmptyConfig->GetDictionaries();
			// Merge the values with the ones provided by the modules
			// Make sure when don't load the same file twice...
	
			$aModules = ModuleDiscovery::GetAvailableModules(array(APPROOT.$sModulesDir));
			foreach($aModules as $sModuleId => $aModuleInfo)
			{
				list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
				if (is_null($aSelectedModules) || in_array($sModuleName, $aSelectedModules))
				{
					if (isset($aModuleInfo['datamodel']))
					{
						$aDataModels = array_unique(array_merge($aDataModels, $aModuleInfo['datamodel']));
					}
					if (isset($aModuleInfo['webservice']))
					{
						$aWebServiceCategories = array_unique(array_merge($aWebServiceCategories, $aModuleInfo['webservice']));
					}
					if (isset($aModuleInfo['settings']))
					{
						list($sName, $sVersion) = ModuleDiscovery::GetModuleName($sModuleId);
						foreach($aModuleInfo['settings'] as $sProperty => $value)
						{
							if ($bPreserveModuleSettings && isset($this->m_aModuleSettings[$sName][$sProperty]))
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
			$this->SetAppModules($aAppModules);
			$this->SetDataModels($aDataModels);
			$this->SetWebServiceCategories($aWebServiceCategories);

			// Scan dictionaries
			//
			if (!is_null($sModulesDir))
			{
				foreach(glob(APPROOT.$sModulesDir.'/dictionaries/*.dict.php') as $sFilePath)
				{
					$sFile = basename($sFilePath);
					$aDictionaries[] = $sModulesDir.'/dictionaries/'.$sFile;
				}
			}
			$this->SetDictionaries($aDictionaries);
		}
	}

	/**
	 * Helper: for an array of string, change the prefix when found
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
	 * Quick an dirty way to clone a config file into another environment	
	 */	
	public function ChangeModulesPath($sSourceEnv, $sTargetEnv)
	{
		$sSearchPrefix = 'env-'.$sSourceEnv.'/';
		$sNewPrefix = 'env-'.$sTargetEnv.'/';
		self::ChangePrefix($this->m_aDataModels, $sSearchPrefix, $sNewPrefix);
		self::ChangePrefix($this->m_aWebServiceCategories, $sSearchPrefix, $sNewPrefix);
		self::ChangePrefix($this->m_aDictionaries, $sSearchPrefix, $sNewPrefix);
	}
	
	/**
	 * Pretty format a var_export'ed value so that (if possible) the identation is preserved on every line
	 * @param mixed $value The value to export
	 * @param string $sIndentation The string to use to indent the text
	 * @param bool $bForceIndentation Forces the identation (enven if it breaks/changes an eval, for example to ouput a value inside a comment)
	 * @return string The indented export string
	 */
	protected static function PrettyVarExport($value, $sIndentation, $bForceIndentation = false)
	{
		$sExport = var_export($value, true);
		$sNiceExport = str_replace(array("\r\n", "\n", "\r"), "\n".$sIndentation, trim($sExport));
		if (!$bForceIndentation)
		{
			eval('$aImported='.$sNiceExport.';');
			// Check if adding the identations at the beginning of each line
			// did not modify the values (in case of a string containing a line break)
			if($aImported != $value)
			{
				$sNiceExport = $sExport;
			}
		}
		return $sNiceExport;	
	}

}
?>
