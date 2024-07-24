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
				// Search for existing ActionEmail where the language attribute was defined on its child
				if (version_compare($sPreviousVersion, '3.2.0', '<')) {
					SetupLog::Info("|  Migrate ActionEmail language attribute values to its parent.");
					$sTableToRead = MetaModel::DBGetTable('ActionEmail');
					$sTableToSet = MetaModel::DBGetTable('ActionNotification');
					self::MoveColumnInDB($sTableToRead, 'language',  $sTableToSet, 'language', true);
					SetupLog::Info("|  ActionEmail migration done.");
				}
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
			// Default language will be used for actions
			// Note: There is a issue when upgrading, default language cannot be retrieved from the passed configuration, we have to read it from the disk
			if (utils::IsNullOrEmptyString($sPreviousVersion)) {
				// Fresh install
				$sDefaultLanguage = $oConfiguration->GetDefaultLanguage();
			} else {
				// Upgrade
				$sDefaultLanguage = utils::GetConfig(true)->GetDefaultLanguage();
			}
			// Fallback language on english if not french
			$sDefaultLanguage = $sDefaultLanguage === 'FR FR' ? 'FR FR' : 'EN US';
			SetupLog::Info("Default app language used for actions: $sDefaultLanguage");

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

			// Add notifications by email to Persons if mentioned on any log
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

						// Skip class with no log attribute
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
						$oTrigger = MetaModel::NewObject(TriggerOnObjectMention::class);
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
					// Actions data for english and french
					$aActionsData = [
						'EN US' => [
							'name' => 'Notification to persons mentioned in logs',
							'subject' => 'You have been mentioned in "$this->friendlyname$"',
							'body' => '<p>Hello $mentioned->first_name$,</p>
								<p>You have been mentioned by $current_contact->friendlyname$ in $this->hyperlink()$</p>',
						],
						'FR FR' => [
							'name' => 'Notification aux personnes mentionnées dans les journaux',
							'subject' => 'Vous avez été mentionné dans "$this->friendlyname$"',
							'body' => '<p>Bonjour $mentioned->first_name$,</p>
								<p>Vous avez été mentionné par $current_contact->friendlyname$ dans $this->hyperlink()$</p>',
						]
					];

					// Create action in app. default language and link it to the triggers
					$aData = $aActionsData[$sDefaultLanguage];
					$oAction = MetaModel::NewObject(ActionEmail::class);
					$oAction->Set('name', $aData['name']);
					$oAction->Set('status', 'enabled');
					$oAction->Set('language', $sDefaultLanguage);
					$oAction->Set('from', '$current_contact->email$');
					$oAction->Set('to', 'SELECT Person WHERE id = :mentioned->id');
					$oAction->Set('subject', $aData['subject']);
					$oAction->Set('body', $aData['body']);

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

			// Add notifications by newsroom to Persons if mentioned on any log
			if (version_compare($sPreviousVersion, '3.2.0', '<')) {
				SetupLog::Info("Adding default newsroom actions for Person objects mentions. All existing TriggerOnObjectMention mentioning the Person class will be concerned...");

				$sPersonClass = Person::class;
				$iExistingTriggersCount = 0;

				// Actions data for english and french
				$aActionsData = [
					'EN US' => [
						'name' => 'Notification to persons mentioned in logs',
						'message' => 'You have been mentioned by $current_contact->friendlyname$',
					],
					'FR FR' => [
						'name' => 'Notification aux personnes mentionnées dans les journaux',
						'message' => 'Vous avez été mentionné par $current_contact->friendlyname$',
					]
				];

				// Start by creating the default action no matter what (even if there is no relevant trigger, it will be there for future use)
				$aData = $aActionsData[$sDefaultLanguage];
				$oAction = MetaModel::NewObject(ActionNewsroom::class);
				$oAction->Set('name', $aData['name']);
				$oAction->Set('status', 'enabled');
				$oAction->Set('language', $sDefaultLanguage);
				$oAction->Set('priority', 3); // Important priority as a mention is probably more important than a simple notification
				$oAction->Set('recipients', 'SELECT Person WHERE id = :mentioned->id');
				$oAction->Set('title', '$this->friendlyname$');
				$oAction->Set('message', $aData['message']);
				$oAction->DBWrite();

				SetupLog::Info("|- Created newsroom action \"{$oAction->Get('name')}\".");

				// Retrieve all triggers and find those with a mentioned_filter on the Person class
				$oTriggersSearch = DBObjectSearch::FromOQL("SELECT " . TriggerOnObjectMention::class);
				$oTriggersSearch->AllowAllData();

				$oTriggersSet = new DBObjectSet($oTriggersSearch);
				while ($oTrigger = $oTriggersSet->Fetch()) {
					$oMentionedFilter = DBSearch::FromOQL($oTrigger->Get('mentioned_filter'));
					$sMentionedClass = $oMentionedFilter->GetClass();

					// If mentioned class is not a Person, ignore
					if (is_a($sMentionedClass, $sPersonClass, true) === false) {
						continue;
					}

					// Link the trigger to the action
					/** @var \ormLinkSet $oOrm */
					$oOrm = $oTrigger->Get('action_list');
					$oLink = new lnkTriggerAction();
					$oLink->Set('action_id', $oAction->GetKey());
					$oOrm->AddItem($oLink);

					$oTrigger->Set('action_list', $oOrm);
					$oTrigger->DBUpdate();
					$iExistingTriggersCount++;

					SetupLog::Info("|- Linked newsroom action \"{$oAction->GetName()}\" to existing trigger \"{$oTrigger->GetName()}\".");
				}

				if ($iExistingTriggersCount === 0) {
					SetupLog::Info("... no action created as there is no existing trigger on mention for the $sPersonClass class.");
				} else {
					SetupLog::Info("... default newsroom action successfully created and linked to $iExistingTriggersCount triggers on mention.");
				}
			}

			// Force subscription policy to ForceAtLeastOneChannel for all existing TriggerOnObjectMention
			if (version_compare($sPreviousVersion, '3.2.0', '<')) {
				SetupLog::Info("Forcing subscription policy to ForceAtLeastOneChannel for all existing TriggerOnObjectMention...");

				$oTriggersSearch = DBObjectSearch::FromOQL("SELECT " . TriggerOnObjectMention::class);
				$oTriggersSearch->AllowAllData();

				$oTriggersSet = new DBObjectSet($oTriggersSearch);
				while ($oTrigger = $oTriggersSet->Fetch()) {
					$oTrigger->Set('subscription_policy', \Combodo\iTop\Core\Trigger\Enum\SubscriptionPolicy::ForceAtLeastOneChannel->value);
					$oTrigger->DBUpdate();

					SetupLog::Info("|- Trigger \"{$oTrigger->GetName()}\" updated.");
				}

				SetupLog::Info("... all existing TriggerOnObjectMention updated.");
			}

			// Add notifications by newsroom (not linked to any trigger yet) for TriggerOnPortalUpdate and TriggerOnReachingState
			if (version_compare($sPreviousVersion, '3.2.0', '<')) {
				// TriggerOnPortalUpdate
				SetupLog::Info("Adding default newsroom action for TriggerOnPortalUpdate (not linked to any trigger yet)...");

				//  - Actions data for english and french
				$aActionsData = [
					'EN US' => [
						'name' => 'Notification on public log update through the portal',
						'message' => 'New message from $current_contact->friendlyname$',
					],
					'FR FR' => [
						'name' => 'Notification sur MAJ du journal public via le portail',
						'message' => 'Nouveau message de $current_contact->friendlyname$',
					]
				];

				// - Create action in app. default language and link it to the triggers
				$aData = $aActionsData[$sDefaultLanguage];
				$oAction = MetaModel::NewObject(ActionNewsroom::class);
				$oAction->Set('name', $aData['name']);
				$oAction->Set('status', 'enabled');
				$oAction->Set('language', $sDefaultLanguage);
				$oAction->Set('priority', 4); // Standard priority
				$oAction->Set('recipients', 'SELECT Person WHERE id = :this->agent_id');
				$oAction->Set('title', '$this->friendlyname$');
				$oAction->Set('message', $aData['message']);
				$oAction->DBWrite();

				// TriggerOnReachingState
				SetupLog::Info("Adding default newsroom action for TriggerOnReachingState (not linked to any trigger yet)...");

				// Actions data for english and french
				$aActionsData = [
					'EN US' => [
						'name' => 'Notification to agent when ticket assigned',
						'message' => 'Ticket has been assigned to you',
					],
					'FR FR' => [
						'name' => 'Notification à l\'agent à l\'assignation du ticket',
						'message' => 'Le ticket vous a été assigné',
					]
				];

				// Create action in app. default language and link it to the triggers
				$aData = $aActionsData[$sDefaultLanguage];
				$oAction = MetaModel::NewObject(ActionNewsroom::class);
				$oAction->Set('name', $aData['name']);
				$oAction->Set('status', 'enabled');
				$oAction->Set('language', $sDefaultLanguage);
				$oAction->Set('priority', 3); // Important priority
				$oAction->Set('recipients', 'SELECT Person WHERE id = :this->agent_id');
				$oAction->Set('title', '$this->friendlyname$');
				$oAction->Set('message', $aData['message']);
				$oAction->DBWrite();
			}
		}
	}
}
