<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Localized data
 */

Dict::Add('EN US', 'English', 'English', [
	'Menu:DataFeatureRemovalMenu' => 'Features Removal',
	'combodo-data-feature-removal/Operation:Main/Title' => 'Features Removal',

	'DataFeatureRemoval:Main:Title' => 'Features Removal',
	'DataFeatureRemoval:Main:SubTitle' => 'Prepare features you want to enable/disable in a future setup',
	'DataFeatureRemoval:Failure:Title' => 'Feature dry removal errors',
	'DataFeatureRemoval:Helper:Title' => 'Enable or disable features that are installed in your iTop.',
	'DataFeatureRemoval:Helper:Desc1' => 'It will prepare the setup step that proceeds to feature enabling or disabling.',
	'DataFeatureRemoval:Helper:Desc2' => 'Analyze if there are any data or dependency preventing you from enabling/disabling a feature.',

	'DataFeatureRemoval:Features:Title' => 'Features',
	'DataFeatureRemoval:Analysis:Title' => 'Analysis result',
	'DataFeatureRemoval:Analysis:SubTitle' => '%1$s element(s) to clean before continuing',

	'DataFeatureRemoval:DeletionPlan:Title' => 'Deletion plan',
	'DataFeatureRemoval:DeletionPlan:SubTitle' => 'Database tables to clean before continuing',
	'DataFeatureRemoval:DoDeletion:Title' => 'Do deletion',
	'DataFeatureRemoval:DoDeletion:SubTitle' => 'Remove all the entries from the database',

	'DataFeatureRemoval:Table:Analysis:ClassName' => 'Element to remove',
	'DataFeatureRemoval:Table:Analysis:FeatureName' => 'Feature name',
	'DataFeatureRemoval:Table:Analysis:Occurrence' => 'Occurrence',

	'UI:Button:Analyze' => 'Analyze',
	'UI:Button:ModifyChoices' => 'Modify Choices',
	'UI:Button:AnalyzeAndSetup' => 'Analyze and go to setup',
	'UI:Button:PlanDeletion' => 'Prepare deletion plan',
	'UI:Button:DoDeletion' => 'Delete data',
	'UI:Button:BackToMain' => 'Back to Feature Removal',
	'UI:Button:Setup' => 'Back to setup',

	'UI:Action:ForceUninstall' => 'Force uninstall',
	'UI:Action:MoreInfo' => 'More information',

	'DataFeatureRemoval:Table:Empty' => 'No data to remove',

	'DataFeatureRemoval:Column:Class' => 'Class',
	'DataFeatureRemoval:Column:DeleteCount' => 'Entries to delete',
	'DataFeatureRemoval:Column:UpdateCount' => 'Entries to update',
	'DataFeatureRemoval:Column:Issue' => 'Issue',

	'DataFeatureRemoval:Column:DeletedCount' => 'Deleted entries',
	'DataFeatureRemoval:Column:UpdatedCount' => 'Updated entries',
]);
