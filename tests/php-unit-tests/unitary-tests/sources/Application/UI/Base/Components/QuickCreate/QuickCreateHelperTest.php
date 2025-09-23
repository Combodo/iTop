<?php

namespace Application\UI\Base\Components\QuickCreate;

use appUserPreferences;
use Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreateHelper;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class QuickCreateHelperTest extends ItopDataTestCase
{
	public function testPopularClassesShouldBeLeftUnchangedWhenNotInRecent()
	{
		// Given popular classes = ['FunctionalCI', 'UserRequest'], as defined in datamodel
		$this->GivenRecentClasses(['Person']);

		$aPopularClasses = QuickCreateHelper::GetPopularClasses();
		$this->AssertPopularClassesMatches(['FunctionalCI', 'UserRequest'], $aPopularClasses, "");
	}

	public function testPopularClassesShouldBeLeftUnchangedWhenNoRecent()
	{
		// Given popular classes = ['FunctionalCI', 'UserRequest'], as defined in datamodel
		$this->GivenRecentClasses([]);

		$aPopularClasses = QuickCreateHelper::GetPopularClasses();
		$this->AssertPopularClassesMatches(['FunctionalCI', 'UserRequest'], $aPopularClasses, "");
	}

	public function testClassInRecentShouldNotBeInPopular()
	{
		// Given popular classes = ['FunctionalCI', 'UserRequest'], as defined in datamodel
		$this->GivenRecentClasses(['UserRequest']);

		$aPopularClasses = QuickCreateHelper::GetPopularClasses();
		$this->AssertPopularClassesMatches(['FunctionalCI'], $aPopularClasses, "");
	}
	private function GivenRecentClasses(array $aGivenClasses)
	{
		$aRecentClasses = [];
		// User preferences will be reset during the rollback
		foreach($aGivenClasses as $sClass) {
			$aRecentClasses[] =  array(
				'class' => $sClass,
			);
		}

		appUserPreferences::SetPref(QuickCreateHelper::USER_PREF_CODE, $aRecentClasses);
	}

	private function AssertPopularClassesMatches(array $aExpectedClasses, array $aPopularClasses, string $sMessage = '')
	{
		$aFoundClasses = [];
		foreach($aPopularClasses as $aClassData) {
			$aFoundClasses[] = $aClassData['class'];
		}
		sort($aFoundClasses);
		sort($aExpectedClasses);
		$this->assertEquals($aExpectedClasses, $aFoundClasses, $sMessage);
	}
}