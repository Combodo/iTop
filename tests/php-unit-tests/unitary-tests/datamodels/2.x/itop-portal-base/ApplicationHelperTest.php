<?php

namespace Combodo\iTop\Test\UnitTest\Module\iTopPortalBase;

/**
 * Copyright (C) 2010-2024 Combodo SAS
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

use Combodo\iTop\Portal\Brick\AbstractBrick;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @covers \Combodo\iTop\Portal\Helper\RequestManipulatorHelper
 */
class ApplicationHelperTest extends ItopDataTestCase
{
	public const PASSWORD = "aBCDEFG@123456789";

	protected function LoadRequiredItopFiles(): void
	{
		parent::LoadRequiredItopFiles();
	}

	public static function LoadBrickSecurityProvider()
	{
		return [
			'can access admin profile' => [
				'associated_profile' => 'Administrator',
			],
			'cannot access admin profile' => [
				'associated_profile' => 'Portal user',
			],
		];
	}

	/**
	 * @dataProvider LoadBrickSecurityProvider
	 */
	public function testLoadBrickSecurity_GetAllowedProfilesOql(string $sAssociatedProfileName)
	{
		$oBrick = $this->createMock(AbstractBrick::class);
		$oBrick->expects($this->any())
			->method('GetAllowedProfilesOql')
			->willReturn("SELECT URP_Profiles WHERE name IN ('Administrator')");

		$oBrick->expects($this->exactly(1))
			->method('AddAllowedProfile')
			->willReturn("Administrator");

		$_SESSION = [];
		$oUser = $this->CreateContactlessUser("$sAssociatedProfileName-".uniqid(), self::$aURP_Profiles[$sAssociatedProfileName], self::PASSWORD);
		\UserRights::Login($oUser->Get('login'));

		$this->InvokeNonPublicStaticMethod(ApplicationHelper::class, 'LoadBrickSecurity', [$oBrick]);
	}

	/**
	 * @dataProvider LoadBrickSecurityProvider
	 */
	public function testLoadBrickSecurity_GetDeniedProfilesOql(string $sAssociatedProfileName)
	{
		$oBrick = $this->createMock(AbstractBrick::class);
		$oBrick->expects($this->any())
			->method('GetDeniedProfilesOql')
			->willReturn("SELECT URP_Profiles WHERE name IN ('Administrator')");

		$oBrick->expects($this->exactly(1))
			->method('AddDeniedProfile')
			->willReturn("Administrator");

		$_SESSION = [];
		$oUser = $this->CreateContactlessUser("$sAssociatedProfileName-".uniqid(), self::$aURP_Profiles[$sAssociatedProfileName], self::PASSWORD);
		\UserRights::Login($oUser->Get('login'));

		$this->InvokeNonPublicStaticMethod(ApplicationHelper::class, 'LoadBrickSecurity', [$oBrick]);
	}

}
