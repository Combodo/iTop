<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license	http://opensource.org/licenses/AGPL-3.0
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */

// Database inconsistencies
Dict::Add('EN US', 'English', 'English', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Database integrity',
	'DBTools:Class' => 'Class',
	'DBTools:Title' => 'Database integrity check',
	'DBTools:ErrorsFound' => 'Errors Found',
	'DBTools:Indication' => 'Important: after fixing errors in the database you\'ll have to run the analysis again as new inconsistencies will be generated',
	'DBTools:Disclaimer' => 'DISCLAIMER: BACKUP YOUR DATABASE BEFORE RUNNING THE FIXES',
	'DBTools:Error' => 'Error',
	'DBTools:Count' => 'Count',
	'DBTools:SQLquery' => 'SQL query',
	'DBTools:FixitSQLquery' => 'SQL query To Fix it (indication)',
	'DBTools:SQLresult' => 'SQL result',
	'DBTools:NoError' => 'The database is OK',
	'DBTools:HideIds' => 'Error List',
	'DBTools:ShowIds' => 'Detailed view',
	'DBTools:ShowReport' => 'Report',
	'DBTools:IntegrityCheck' => 'Integrity check',
	'DBTools:FetchCheck' => 'Fetch Check (long)',
	'DBTools:SelectAnalysisType' => 'Select analysis type',

	'DBTools:Analyze' => 'Analyze',
	'DBTools:Details' => 'Show Details',
	'DBTools:ShowAll' => 'Show All Errors',

	'DBTools:Inconsistencies' => 'Database inconsistencies',
	'DBTools:DetailedErrorTitle' => '%2$s error(s) in class %1$s: %3$s',
	'DBTools:DetailedErrorLimit' => 'List limited to %1$s errors',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Orphan record in `%1$s`, it should have its counterpart in table `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Invalid external key %1$s (column: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Missing external key %1$s (column: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Invalid value for %1$s (column: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Some user accounts have no profile at all',
	'DBAnalyzer-Integrity-HKInvalid' => 'Broken hierarchical key `%1$s`',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value as `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contains a valid class',
));

// Database Info
Dict::Add('EN US', 'English', 'English', array(
	'DBTools:DatabaseInfo' => 'Database Information',
	'DBTools:Base' => 'Base',
	'DBTools:Size' => 'Size',
));

// Lost attachments
Dict::Add('EN US', 'English', 'English', array(
	'DBTools:LostAttachments' => 'Lost attachments',
	'DBTools:LostAttachments:Disclaimer' => 'Here you can search your database for lost or misplaced attachments. This is NOT a data recovery tool, is does not retrieve deleted data.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analyze',
	'DBTools:LostAttachments:Button:Restore' => 'Restore',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'This action cannot be undone, please confirm that you want to restore the selected files.',
	'DBTools:LostAttachments:Button:Busy' => 'Please wait...',

	'DBTools:LostAttachments:Step:Analyze' => 'First, search for lost/misplaced attachments by analyzing the database.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Analyze results:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Great! Every thing seems to be at the right place.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Some attachments (%1$d) seem to be misplaced. Take a look at the following list and check the ones you would like to move.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Filename',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Current location',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Move to...',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Restore results:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d attachments were restored.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Stored as inline image',
	'DBTools:LostAttachments:History' => 'Attachment "%1$s" restored with DB tools'
));
