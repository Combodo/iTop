<?php
/** @noinspection PhpUnused */
/** @noinspection PhpIllegalPsrClassPathInspection */
/**
 * Copyright (C) 2010-2021 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

/**
 * Mock class used to count number of calls for the ProcessImage static method
 *
 * @used-by \Combodo\iTop\Test\UnitTest\Core\Sanitizer\HTMLDOMSanitizerTest::testDoSanitizeCallInlineImageProcessImageTag()
 */
class InlineImageMock
{
	private static $iCallCounter = 0;

	public static function ProcessImageTag(DOMNode $oNode)
	{
		self::$iCallCounter++;
	}

	public static function ResetCallCounter()
	{
		self::$iCallCounter = 0;
	}

	public static function GetCallCounter()
	{
		return self::$iCallCounter;
	}
}