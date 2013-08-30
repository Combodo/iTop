<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Includes all the classes to have the application up and running
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/applicationcontext.class.inc.php');
require_once(APPROOT.'/application/cmdbabstract.class.inc.php');
require_once(APPROOT.'/application/displayblock.class.inc.php');
require_once(APPROOT.'/application/sqlblock.class.inc.php');
require_once(APPROOT.'/application/audit.category.class.inc.php');
require_once(APPROOT.'/application/audit.rule.class.inc.php');
require_once(APPROOT.'/application/query.class.inc.php');
require_once(APPROOT.'/setup/moduleinstallation.class.inc.php');
//require_once(APPROOT.'/application/menunode.class.inc.php');
require_once(APPROOT.'/application/utils.inc.php');

class ApplicationException extends CoreException
{
}
?>
