<?php


// Until we develop a mean to adress this within the setup, let's check that this instance
// of PHP has the php_ldap extension
//
if (function_exists('ldap_connect'))
{

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'authent-ldap/3.3.0',
	array(
		// Identification
		//
		'label' => 'User authentication based on LDAP',
		'category' => 'authentication',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'AuthentLDAPInstaller',

		// Components
		//
		'datamodel' => array(
		),
		'data.struct' => array(
			//'data.struct.authent-ldap.xml',
		),
		'data.sample' => array(
			//'data.sample.authent-ldap.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
			'uri' => 'ldap://localhost', // URI with host or IP address of your LDAP server
			'default_user' => '', // User and password used for initial "Anonymous" bind to LDAP
			'default_pwd' => '',  // Leave both blank, if anonymous (read-only) bind is allowed
			'base_dn' => 'dc=yourcompany,dc=com', // Base DN for User queries, adjust it to your LDAP schema
			'user_query' => '(&(uid=%1$s)(inetuserstatus=ACTIVE))', // Query used to retrieve each user %1$s => iTop login
																	// For Windows AD use (samaccountname=%1$s) or (userprincipalname=%1$s)
																	
			// Some extra LDAP options, refer to: http://www.php.net/manual/en/function.ldap-set-option.php for more info
			'options' => array(
				LDAP_OPT_PROTOCOL_VERSION => 3,
				LDAP_OPT_REFERRALS => 0,
			),
			'start_tls' => false,
			'debug' => false,
			'servers' => array(),
		),
	)
);

// Module installation handler
//
class AuthentLDAPInstaller extends ModuleInstallerAPI
{
	public static function AfterDataLoad(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
	{
		// Create missing table entries
		$sUserLDAPTable = MetaModel::DBGetTable('UserLDAP');
		$sUserTable = MetaModel::DBGetTable('User');
		$sSQL = "insert into $sUserLDAPTable (id) select U.id from $sUserTable as U left join $sUserLDAPTable as L on U.id = L.id where U.finalclass='UserLDAP' and isnull(L.id);";
		CMDBSource::Query($sSQL);
	}

	public static function BeforeWritingConfig(Config $oConfiguration)
	{
		$sURI = $oConfiguration->GetModuleSetting('authent-ldap', 'uri');
		if (empty($sURI)) {
			$sLDAPHost = MetaModel::GetModuleSetting('authent-ldap', 'host', 'localhost');
			$iLDAPPort = MetaModel::GetModuleSetting('authent-ldap', 'port', 389);
			$sURI = preg_match('#^ldaps?://#i', $sLDAPHost) ? $sLDAPHost : 'ldap://'.$sLDAPHost.':'.$iLDAPPort;
			$oConfiguration->SetModuleSetting('authent-ldap', 'uri', $sURI);
		}

		$aServers = $oConfiguration->GetModuleSetting('authent-ldap', 'servers', []);
		foreach ($aServers as &$aServer) {
			if (!array_key_exists($aServer, 'uri')) {
				$sLDAPHost = $aServerParams['host'] ?? 'localhost';
				$iLDAPPort = $aServerParams['port'] ?? 389;
				$aServer['uri'] = preg_match('#^ldaps?://#i', $sLDAPHost) ? $sLDAPHost : 'ldap://'.$sLDAPHost.':'.$iLDAPPort;
			}
		}
		$oConfiguration->SetModuleSetting('authent-ldap', 'servers', $aServers);
	}
}

} // if (function_exists('ldap_connect'))
