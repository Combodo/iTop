<?php
// Copyright (C) 2010-2024 Combodo SAS
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

use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Service\Notification\NotificationsRepository;
use Combodo\iTop\Service\Notification\NotificationsService;
use Combodo\iTop\Service\Router\Router;

/**
 * Persistent classes (internal): user defined actions
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/core/asynctask.class.inc.php');
require_once(APPROOT.'/core/email.class.inc.php');
//Actions
require_once(APPROOT.'/core/action/Action.php');
require_once(APPROOT.'/core/action/ActionNotification.php');
require_once(APPROOT.'/core/action/ActionEmail.php');
