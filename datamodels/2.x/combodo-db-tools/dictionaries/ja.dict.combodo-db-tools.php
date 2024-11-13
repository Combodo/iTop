<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('JA JP', 'Japanese', '日本語', [
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted~~',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value as `%3$s`.`%1$s`~~',
	'DBAnalyzer-Integrity-HKInvalid' => 'Broken hierarchical key `%1$s`~~',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Invalid external key %1$s (column: `%2$s.%3$s`)~~',
	'DBAnalyzer-Integrity-InvalidValue' => 'Invalid value for %1$s (column: `%2$s.%3$s`)~~',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Missing external key %1$s (column: `%2$s.%3$s`)~~',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Orphan record in `%1$s`, it should have its counterpart in table `%2$s`~~',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contain a valid class~~',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Some user accounts have no profile at all~~',
	'DBTools:Analyze' => 'Analyze~~',
	'DBTools:Base' => 'Base~~',
	'DBTools:Class' => 'Class~~',
	'DBTools:Count' => 'Count~~',
	'DBTools:DatabaseInfo' => 'Database Information~~',
	'DBTools:DetailedErrorLimit' => 'List limited to %1$s errors~~',
	'DBTools:DetailedErrorTitle' => '%2$s error(s) in class %1$s: %3$s~~',
	'DBTools:Details' => 'Show Details~~',
	'DBTools:Disclaimer' => 'DISCLAIMER: BACKUP YOUR DATABASE BEFORE RUNNING THE FIXES~~',
	'DBTools:Error' => 'Error~~',
	'DBTools:ErrorsFound' => 'Errors Found~~',
	'DBTools:FetchCheck' => 'Fetch Check (long)~~',
	'DBTools:FixitSQLquery' => 'SQL query To Fix it (indication)~~',
	'DBTools:HideIds' => 'Error List~~',
	'DBTools:Inconsistencies' => 'Database inconsistencies~~',
	'DBTools:Indication' => 'Important: after fixing errors in the database you\'ll have to run the analysis again as new inconsistencies will be generated~~',
	'DBTools:IntegrityCheck' => 'Integrity check~~',
	'DBTools:LostAttachments' => 'Lost attachments~~',
	'DBTools:LostAttachments:Button:Analyze' => 'Analyze~~',
	'DBTools:LostAttachments:Button:Busy' => 'Please wait...~~',
	'DBTools:LostAttachments:Button:Restore' => 'Restore~~',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'This action cannot be undone, please confirm that you want to restore the selected files.~~',
	'DBTools:LostAttachments:Disclaimer' => 'Here you can search your database for lost or misplaced attachments. This is NOT a data recovery tool, it does not retrieve deleted data.~~',
	'DBTools:LostAttachments:History' => 'Attachment "%1$s" restored with DB tools~~',
	'DBTools:LostAttachments:Step:Analyze' => 'First, search for lost/misplaced attachments by analyzing the database.~~',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Analyze results:~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Current location~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Filename~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Move to...~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Great! Every thing seems to be at the right place.~~',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Some attachments (%1$d) seem to be misplaced. Take a look at the following list and check the ones you would like to move.~~',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Restore results:~~',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d attachments were restored.~~',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Stored as inline image~~',
	'DBTools:NoError' => 'The database is OK~~',
	'DBTools:SQLquery' => 'SQL query~~',
	'DBTools:SQLresult' => 'SQL result~~',
	'DBTools:SelectAnalysisType' => 'Select analysis type~~',
	'DBTools:ShowAll' => 'Show All Errors~~',
	'DBTools:ShowIds' => 'Detailed view~~',
	'DBTools:ShowReport' => 'Report~~',
	'DBTools:Size' => 'Size~~',
	'DBTools:Title' => 'Database integrity check~~',
	'Menu:DBToolsMenu' => 'Database integrity~~',
]);
