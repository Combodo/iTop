<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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
 */

// Portal
Dict::Add('EN US', 'English', 'English', array(
	'Page:DefaultTitle' => '%1$s User portal',
	'Page:PleaseWait' => 'Please wait...',
	'Page:Home' => 'Home',
	'Page:GoPortalHome' => 'Home page',
	'Page:GoPreviousPage' => 'Previous page',
	'Page:ReloadPage' => 'Reload page',
	'Portal:Button:Submit' => 'Submit',
	'Portal:Button:Apply' => 'Update',
	'Portal:Button:Cancel' => 'Cancel',
	'Portal:Button:Close' => 'Close',
	'Portal:Button:Add' => 'Add',
	'Portal:Button:Remove' => 'Remove',
	'Portal:Button:Delete' => 'Delete',
	'Portal:EnvironmentBanner:Title' => 'You are currently in <strong>%1$s</strong> mode',
	'Portal:EnvironmentBanner:GoToProduction' => 'Go back to PRODUCTION mode',
	'Error:HTTP:400' => 'Bad request',
	'Error:HTTP:401' => 'Authentication',
	'Error:HTTP:404' => 'Page not found',
	'Error:HTTP:500' => 'Oops! An error has occured.',
	'Error:HTTP:GetHelp' => 'Please contact your %1$s administrator if the problem keeps happening.',
	'Error:XHR:Fail' => 'Could not load data, please contact your %1$s administrator',
	'Portal:ErrorUserLoggedOut' => 'You are logged out and need to log in again in order to continue.',
	'Portal:Datatables:Language:Processing' => 'Please wait...',
	'Portal:Datatables:Language:Search' => 'Filter:',
	'Portal:Datatables:Language:LengthMenu' => 'Display _MENU_ items per page',
	'Portal:Datatables:Language:ZeroRecords' => 'No result',
	'Portal:Datatables:Language:Info' => 'Page _PAGE_ of _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'No information',
	'Portal:Datatables:Language:InfoFiltered' => 'filtered out of _MAX_ items',
	'Portal:Datatables:Language:EmptyTable' => 'No data available in this table',
	'Portal:Datatables:Language:DisplayLength:All' => 'All',
	'Portal:Datatables:Language:Paginate:First' => 'First',
	'Portal:Datatables:Language:Paginate:Previous' => 'Previous',
	'Portal:Datatables:Language:Paginate:Next' => 'Next',
	'Portal:Datatables:Language:Paginate:Last' => 'Last',
	'Portal:Datatables:Language:Sort:Ascending' => 'enable for an ascending sort',
	'Portal:Datatables:Language:Sort:Descending' => 'enable for a descending sort',
	'Portal:Autocomplete:NoResult' => 'No data',
	'Portal:Attachments:DropZone:Message' => 'Drop your files to add them as attachments',
	'Portal:File:None' => 'No file',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Open</a> / <a href="%4$s" class="file_download_link">Download</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'en-us', //work with moment.js locales
));

// Object form
Dict::Add('EN US', 'English', 'English', array(
	'Portal:Form:Caselog:Entry:Close:Tooltip' => 'Close this entry',
	'Portal:Form:Close:Warning' => 'Do you want to leave this form ? Data entered may be lost',
	'Portal:Error:ObjectCannotBeCreated' => 'Error: object cannot be created. Check associated objects and attachments before submitting again this form.',
	'Portal:Error:ObjectCannotBeUpdated' => 'Error: object cannot be updated. Check associated objects and attachments before submitting again this form.',
));

// UserProfile brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:UserProfile:Name' => 'User profile',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'My profile',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Logoff',
	'Brick:Portal:UserProfile:Password:Title' => 'Password',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Choose password',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Confirm password',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'To change your password, please contact your %1$s administrator',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Can\'t change password, please contact your %1$s administrator',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Personal information',
	'Brick:Portal:UserProfile:Photo:Title' => 'Photo',
));

// AggregatePageBrick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Dashboard',
));

// BrowseBrick brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Browse:Name' => 'Browse throught items',
	'Brick:Portal:Browse:Mode:List' => 'List',
	'Brick:Portal:Browse:Mode:Tree' => 'Tree',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mosaic',
	'Brick:Portal:Browse:Action:Drilldown' => 'Drilldown',
	'Brick:Portal:Browse:Action:View' => 'Details',
	'Brick:Portal:Browse:Action:Edit' => 'Edit',
	'Brick:Portal:Browse:Action:Create' => 'Create',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'New %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Expand all',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Collapse all',
	'Brick:Portal:Browse:Filter:NoData' => 'No item',
));

// ManageBrick brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Manage:Name' => 'Manage items',
	'Brick:Portal:Manage:Table:NoData' => 'No item.',
	'Brick:Portal:Manage:Table:ItemActions' => 'Actions',
	'Brick:Portal:Manage:DisplayMode:list' => 'List',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Pie Chart',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Bar Chart',
	'Brick:Portal:Manage:Others' => 'Others',
	'Brick:Portal:Manage:All' => 'All',
	'Brick:Portal:Manage:Group' => 'Group',
	'Brick:Portal:Manage:fct:count' => 'Total',
	'Brick:Portal:Manage:fct:sum' => 'Sum',
	'Brick:Portal:Manage:fct:avg' => 'Average',
	'Brick:Portal:Manage:fct:min' => 'Min',
	'Brick:Portal:Manage:fct:max' => 'Max',
));

// ObjectBrick brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Object:Name' => 'Object',
	'Brick:Portal:Object:Form:Create:Title' => 'New %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Updating %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s: %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Please, complete the following information:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Saved',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s saved',
	'Brick:Portal:Object:Search:Regular:Title' => 'Select %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Select %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Copy object link',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Copied'
));

// CreateBrick brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Create:Name' => 'Quick creation',
	'Brick:Portal:Create:ChooseType' => 'Please, choose a type',
));

// Filter brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Filter:Name' => 'Prefilter a brick',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'eg. connect wifi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Search',
));
