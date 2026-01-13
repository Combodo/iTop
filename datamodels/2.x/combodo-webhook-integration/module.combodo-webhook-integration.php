<?php
//
// iTop module definition file
//
//
//
SetupWebPage::AddModule(__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-webhook-integration/1.4.4',
	array(
		//
		'label' => 'Webhook integrations',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-structure/3.2.0',
			'itop-attribute-encrypted-password/1.0.0',
			'combodo-oauth2-client/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'WebhookIntegrationInstaller',

		// Components
		//
		'datamodel' => array(
			// Extension autoloader
			'vendor/autoload.php',
			// Explicitly load DM classes
			'SendWebRequest.php',
			'model.combodo-webhook-integration.php',
		),
		'webservice' => array(),
		'data.struct' => array(
			'data.struct.remote_application_type.xml',
		),
		'data.sample' => array(),

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => array(
		// Configuración de proxy para las llamadas salientes de los webhooks
			'proxy' => array(
				'host'     => 'ip.roxy:3128', // IP:PUERTO de tu proxy
				'user'     => '',                 // si no requiere auth, dejalo vacío
				'password' => '',
			)
		)
	)
);

if (!class_exists('WebhookIntegrationInstaller'))
{
	// Module installation handler
	//
	class WebhookIntegrationInstaller extends ModuleInstallerAPI
	{
		/**
		 * @inheritDoc
		 * @since 1.4.0
		 */
		public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			if (strlen($sPreviousVersion) === 0) {
				return;
			}

			// Search for existing ActionWebhook where the language attribute was defined on its child
			if (version_compare($sPreviousVersion, '1.4.0', '<')) {
				SetupLog::Info("|  Migrate ActionWebhook language attribute values to its parent.");
				$sTableToRead = MetaModel::DBGetTable('ActionWebhook');
				$sTableToSet = MetaModel::DBGetTable('ActionNotification');
				self::MoveColumnInDB($sTableToRead, 'language', $sTableToSet, 'language', true);
				SetupLog::Info("|  ActionWebhook migration done.");
			}
		}
	}
}
