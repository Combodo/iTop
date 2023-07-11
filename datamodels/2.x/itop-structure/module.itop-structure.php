<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-structure/3.2.0',
	array(
		// Identification
		//
		'label' => 'Core iTop Structure',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => false,
		'installer' => 'StructureInstaller',

		// Components
		//
		'datamodel' => array(
			'main.itop-structure.php',
		),
		'data.struct' => array(
		),
		'data.sample' => array(
			'data.sample.organizations.xml',
			'data.sample.locations.xml',
			'data.sample.persons.xml',
			'data.sample.teams.xml',
			'data.sample.contactteam.xml',
			'data.sample.contacttype.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

if (!class_exists('StructureInstaller'))
{
	// Module installation handler
	//
	class StructureInstaller extends ModuleInstallerAPI
	{
		public static function BeforeWritingConfig(Config $oConfiguration)
		{
			// If you want to override/force some configuration values, do it here
			return $oConfiguration;
		}

		/**
		 * Handler called before creating or upgrading the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			if (strlen($sPreviousVersion) > 0)
			{
				// If you want to migrate data from one format to another, do it here
				self::RenameEnumValueInDB('Software', 'type', 'DBserver', 'DBServer');
				self::RenameEnumValueInDB('Software', 'type', 'Webserver', 'WebServer');
				self::RenameEnumValueInDB('Model', 'type', 'SANswitch', 'SANSwitch');
				self::RenameEnumValueInDB('Model', 'type', 'IpPhone', 'IPPhone');
				self::RenameEnumValueInDB('Model', 'type', 'Telephone', 'Phone');
				self::RenameClassInDB('DBserver', 'DBServer');
				self::RenameClassInDB('OSfamily', 'OSFamily');
				self::RenameClassInDB('OSversion', 'OSVersion');
				self::RenameClassInDB('Webserver', 'WebServer');
				self::RenameClassInDB('OSpatch', 'OSPatch');
				self::RenameClassInDB('lnkFunctionalCIToOSpatch', 'lnkFunctionalCIToOSPatch');
				self::RenameClassInDB('OsLicence', 'OSLicence');
				self::RenameClassInDB('IOSversion', 'IOSVersion');
				self::RenameClassInDB('IPinterface', 'IPInterface');
			}
		}
	
		/**
		 * Handler called after the creation/update of the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			// Search for existing TriggerOnObject where the Trigger string complement is empty and fed it with target_class field value
			if (version_compare($sPreviousVersion, '3.1.0', '<')) {
				SetupLog::Info("|  Feed computed field triggering_class on existing Triggers.");

				$sTableToSet = MetaModel::DBGetTable('Trigger', 'complement');
				$sTableToRead = MetaModel::DBGetTable('TriggerOnObject', 'target_class');
				$oAttDefToSet = MetaModel::GetAttributeDef('Trigger', 'complement');
				$oAttDefToRead = MetaModel::GetAttributeDef('TriggerOnObject', 'target_class');

				$aColumnsToSets = array_keys($oAttDefToSet->GetSQLColumns());
				$sColumnToSet = $aColumnsToSets[0]; // We know that a string has only one column
				$aColumnsToReads = array_keys($oAttDefToRead->GetSQLColumns());
				$sColumnToRead = $aColumnsToReads[0]; // We know that a string has only one column

				$sRepair = "UPDATE $sTableToSet JOIN $sTableToRead ON $sTableToSet.id = $sTableToRead.id SET $sTableToSet.$sColumnToSet = CONCAT('class restriction: ',$sTableToRead.$sColumnToRead) WHERE $sTableToSet.$sColumnToSet = ''";
				SetupLog::Debug(" |  | Query: ".$sRepair);
				CMDBSource::Query($sRepair);
				$iNbProcessed = CMDBSource::AffectedRows();
				SetupLog::Info("|  | ".$iNbProcessed." triggers processed.");
			}

			// Add default configuration, so Persons are notified if mentioned on any Caselog
			if (version_compare($sPreviousVersion, '3.0.0', '<')) {
				SetupLog::Info("Adding default triggers/action for Person objects mentions. All DM classes with at least 1 log attribute will be concerned...");

				$sPersonClass = 'Person';
				$sPersonStateAttCode = MetaModel::GetStateAttributeCode($sPersonClass);
				$sPersonOwnerOrgAttCode = UserRightsProfile::GetOwnerOrganizationAttCode($sPersonClass);

				$iClassesWithLogCount = 0;
				$aCreatedTriggerIds = [];
				foreach (MetaModel::EnumRootClasses() as $sRootClass) {
					foreach (MetaModel::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_ALL, true) as $sClass) {
						$aLogAttCodes = MetaModel::GetAttributesList($sClass, ['AttributeCaseLog']);

						// Skip class with log attribute
						if (count($aLogAttCodes) === 0) {
							continue;
						}

						// Prepare the mentioned_filter OQL
						$oPersonSearch = DBObjectSearch::FromOQL("SELECT $sPersonClass");

						// - Add status condition if attribute present
						if (empty($sPersonStateAttCode) === false) {
							$oPersonSearch->AddConditionExpression(new BinaryExpression(
								new FieldExpression($sPersonStateAttCode),
								'=',
								new ScalarExpression('active')
							));
						}

						// - Check if the classes have a silo attribute so we can use them in the mentioned_filter
						if (empty($sPersonOwnerOrgAttCode) === false) {
							// Filter on current contact org.
							$oCurrentContactExpr = new BinaryExpression(
								new FieldExpression($sPersonOwnerOrgAttCode),
								'=',
								new VariableExpression("current_contact->org_id")
							);

							// Filter on class owner org. if any
							$sClassOwnerOrgAttCode = UserRightsProfile::GetOwnerOrganizationAttCode($sClass);
							$oOwnerOrgExpr = empty($sClassOwnerOrgAttCode) ? null : new BinaryExpression(
								new FieldExpression($sPersonOwnerOrgAttCode),
								'=',
								new VariableExpression("this->$sClassOwnerOrgAttCode")
							);

							// No owner org, simple condition
							if ($oOwnerOrgExpr === null) {
								$oPersonSearch->AddConditionExpression($oCurrentContactExpr);
							}
							// Owner org, condition is either from owner org or current contact's
							else {
								$oOrExpr = new BinaryExpression($oCurrentContactExpr, 'OR', $oOwnerOrgExpr);
								$oPersonSearch->AddConditionExpression($oOrExpr);
							}
						}

						// Build the trigger
						$oTrigger = MetaModel::NewObject('TriggerOnObjectMention');
						$oTrigger->Set('description', 'Person mentioned on '.$sClass);
						$oTrigger->Set('target_class', $sClass);
						$oTrigger->Set('mentioned_filter', $oPersonSearch->ToOQL());
						$oTrigger->DBInsert();

						SetupLog::Info("|- Created trigger \"{$oTrigger->Get('description')}\" for class $sClass.");
						$aCreatedTriggerIds[] = $oTrigger->GetKey();
						$iClassesWithLogCount++;
						// Note: We break because we only have to create one trigger/action for the class hierarchy as it will be for all their log attributes
						break;
					}
				}

				// Build the corresponding action and link it to the triggers
				if (count($aCreatedTriggerIds) > 0) {
					$oAction = MetaModel::NewObject('ActionEmail');
					$oAction->Set('name', 'Notification to persons mentioned in logs');
					$oAction->Set('status', 'enabled');
					$oAction->Set('from', '$current_contact->email$');
					$oAction->Set('to', 'SELECT Person WHERE id = :mentioned->id');
					$oAction->Set('subject', 'You have been mentioned in "$this->friendlyname$"');
					$oAction->Set('body', '<p>Hello $mentioned->first_name$,</p>
								<p>You have been mentioned by $current_contact->friendlyname$ in $this->hyperlink()$</p>'
					);

					/** @var \ormLinkSet $oOrm */
					$oOrm = $oAction->Get('trigger_list');
					foreach ($aCreatedTriggerIds as $sTriggerId) {
						$oLink = new lnkTriggerAction();
						$oLink->Set('trigger_id', $sTriggerId);
						$oOrm->AddItem($oLink);
					}
					$oAction->Set('trigger_list', $oOrm);
					$oAction->DBInsert();

					SetupLog::Info("|- Created action \"{$oAction->Get('name')}\" and linked it to the previously created triggers.");
				}

				if ($iClassesWithLogCount === 0) {
					SetupLog::Info("... no trigger/action created as there is no DM class with a log attribute.");
				} else {
					SetupLog::Info("... default triggers/action successfully created for $iClassesWithLogCount classes.");
				}
			}
		}
	}
}
