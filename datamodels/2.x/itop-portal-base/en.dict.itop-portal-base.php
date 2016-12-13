<?php

// Copyright (C) 2010-2015 Combodo SARL
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

/**
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license	 http://opensource.org/licenses/AGPL-3.0
 */


// Portal
Dict::Add('EN US', 'English', 'English', array(
	'Page:DefaultTitle' => 'iTop User portal',
	'Page:PleaseWait' => 'Please wait...',
	'Page:Home' => 'Home',
	'Page:GoPortalHome' => 'Home page',
	'Page:GoPreviousPage' => 'Previous page',
	'Portal:Button:Submit' => 'Submit',
	'Portal:Button:Cancel' => 'Cancel',
	'Portal:Button:Close' => 'Close',
	'Portal:Button:Add' => 'Add',
	'Portal:Button:Remove' => 'Remove',
	'Portal:Button:Delete' => 'Delete',
	'Error:HTTP:404' => 'Page not found',
	'Error:HTTP:500' => 'Oops! An error has occured.',
	'Error:HTTP:GetHelp' => 'Please contact your iTop administrator if the problem keeps happening.',
	'Error:XHR:Fail' => 'Could not load data, please contact your iTop administrator',
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
));

// UserProfile brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:UserProfile:Name' => 'User profile',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'My profile',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Logoff',
	'Brick:Portal:UserProfile:Password:Title' => 'Password',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Choose password',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Confirm password',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'To change your password, please contact your iTop administrator',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Can\'t change password, please contact your iTop administrator',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Personal informations',
	'Brick:Portal:UserProfile:Photo:Title' => 'Photo',
));

// BrowseBrick brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Browse:Name' => 'Browse throught items',
	'Brick:Portal:Browse:Mode:List' => 'List',
	'Brick:Portal:Browse:Mode:Tree' => 'Tree',
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
));

// ObjectBrick brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Object:Name' => 'Object',
	'Brick:Portal:Object:Form:Create:Title' => 'New %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Updating %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Please, fill the following informations:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Saved',
	'Brick:Portal:Object:Search:Regular:Title' => 'Select %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Select %1$s (%2$s)',
));

// CreateBrick brick
Dict::Add('EN US', 'English', 'English', array(
	'Brick:Portal:Create:Name' => 'Quick creation',
));
?>
