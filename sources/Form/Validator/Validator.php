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

namespace Combodo\iTop\Form\Validator;

use DeprecatedCallsLog;


/**
 * Description of Validator
 *
 * @deprecated 3.1.0 N°6414 use {@see \Combodo\iTop\Form\Validator\CustomRegexpValidator} instead
 */
class Validator extends CustomRegexpValidator
{
	public function __construct($sRegExp = null, $sErrorMessage = null)
	{
		// cannot use DeprecatedCallsLog::NotifyDeprecatedFile as it would trigger an exception on dev env
		// because all autoloader files are loaded during MetaModel::Startup (calling \Combodo\iTop\Service\Events\EventService::InitService calling InterfaceDiscovery::FindItopClasses)
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('3.1.0 N°6414 use '.CustomRegexpValidator::class.' instead');

		parent::__construct($sRegExp, $sErrorMessage);
	}
}
