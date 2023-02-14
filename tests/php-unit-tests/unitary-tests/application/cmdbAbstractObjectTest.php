<?php

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class cmdbAbstractObjectTest extends ItopDataTestCase {
	public function testCheckLinkModifications() {
		$aLinkModificationsStack = $this->GetNonPublicStaticProperty(cmdbAbstractObject::class, 'aLinkModificationsStack');
		$this->assertSame([], $aLinkModificationsStack);

		// lnkPersonToTeam:1 is sample data with : team_id=39 ; person_id=9 ; role_id=3
		$oLinkPersonToTeam1 = MetaModel::GetObject(lnkPersonToTeam::class, 1);
		$oLinkPersonToTeam1->Set('role_id', 1);
		$oLinkPersonToTeam1->DBWrite();
		$aLinkModificationsStack = $this->GetNonPublicStaticProperty(cmdbAbstractObject::class, 'aLinkModificationsStack');
		self::assertCount(3, $aLinkModificationsStack);
		$aExpectedLinkStack = [
			'Team'        => ['39' => 1],
			'Person'      => ['9' => 1],
			'ContactType' => ['1' => 1],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);

		$oLinkPersonToTeam1->Set('role_id', 2);
		$oLinkPersonToTeam1->DBWrite();
		$aLinkModificationsStack = $this->GetNonPublicStaticProperty(cmdbAbstractObject::class, 'aLinkModificationsStack');
		self::assertCount(3, $aLinkModificationsStack);
		$aExpectedLinkStack = [
			'Team'        => ['39' => 2],
			'Person'      => ['9' => 2],
			'ContactType' => [
				'1' => 1,
				'2' => 1,
			],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);
	}
}