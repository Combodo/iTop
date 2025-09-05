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

require_once(APPROOT.'application/newsroomprovider.class.inc.php');

/**
 * Management of application plugins
 *
 * Definition of interfaces that can be implemented to customize iTop.
 * You may implement such interfaces in a module file (e.g. main.mymodule.php)
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @since       2.7.0
 */
require_once(APPROOT.'application/applicationextension/backoffice/iApplicationUIExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeDictEntriesExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeDictEntriesPrefixesExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeEarlyScriptExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeInitScriptExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeLinkedScriptsExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeLinkedStylesheetsExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeReadyScriptExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeSassExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeScriptExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iBackofficeStyleExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iFieldRendererMappingsExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iPageUIBlockExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iPopupMenuExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iPreferencesExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/iWelcomePopupExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/AbstractApplicationUIExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/AbstractPageUIBlockExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/AbstractPreferencesExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/AbstractWelcomePopupExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/ApplicationPopupMenuItem.php');
require_once(APPROOT.'application/applicationextension/backoffice/JSButtonItem.php');
require_once(APPROOT.'application/applicationextension/backoffice/JSPopupMenuItem.php');
require_once(APPROOT.'application/applicationextension/backoffice/SeparatorPopupMenuItem.php');
require_once(APPROOT.'application/applicationextension/backoffice/URLButtonItem.php');
require_once(APPROOT.'application/applicationextension/backoffice/URLPopupMenuItem.php');

//deprecated class and interface
require_once(APPROOT.'application/applicationextension/backoffice/iApplicationObjectExtension.php');
require_once(APPROOT.'application/applicationextension/backoffice/AbstractApplicationObjectExtension.php');



require_once(APPROOT.'application/applicationextension/iBackupExtraFilesExtension.php');
require_once(APPROOT.'application/applicationextension/iKPILoggerExtension.php');
require_once(APPROOT.'application/applicationextension/iModuleExtension.php');

require_once(APPROOT.'application/applicationextension/login/iLoginExtension.php');
require_once(APPROOT.'application/applicationextension/login/iLoginFSMExtension.php');
require_once(APPROOT.'application/applicationextension/login/iLoginUIExtension.php');
require_once(APPROOT.'application/applicationextension/login/iLogoutExtension.php');
require_once(APPROOT.'application/applicationextension/login/AbstractLoginFSMExtension.php');

require_once(APPROOT.'application/applicationextension/portal/iPortalUIExtension.php');
require_once(APPROOT.'application/applicationextension/portal/AbstractPortalUIExtension.php');

require_once(APPROOT.'application/applicationextension/rest/iRestInputSanitizer.php');
require_once(APPROOT.'application/applicationextension/rest/iRestServiceProvider.php');
require_once(APPROOT.'application/applicationextension/rest/RestResult.php');
require_once(APPROOT.'application/applicationextension/rest/RestUtils.php');




