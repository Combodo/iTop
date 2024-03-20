<?php

namespace Combodo\iTop\Test\UnitTest\Dependencies\Fake;

use Combodo\iTop\Dependencies\AbstractFolderAnalyzer;

class FakeFolderAnalyzer extends AbstractFolderAnalyzer
{

	protected function GetDependenciesRootFolderRelPath(): string
	{
		return 'tests/php-unit-tests/unitary-tests/sources/Dependencies/Fake/FakeLibs/';
	}

	public function ListAllowedFoldersRelPaths(): array
	{
		return [
			'Lib1/src/tests',

			'Lib2/external',
		];
	}

	public function ListDeniedFoldersRelPaths(): array
	{
		return [
			'Lib1/denied_dir',
			'Lib1/test',

			'Lib2/examples',

			'NonExistingLibFolder/test',
		];
	}

	public function ExpectedDeniedButStillPresentFoldersAbsPaths(): array
	{
		$aRelPaths = [
			'Lib1/denied_dir',
			'Lib1/test',
			'Lib2/examples',
		];

		return $this->TransformRelToAbsPaths($aRelPaths);
	}

	public function ExpectedAllFoldersAbsPathsIsQuestionnable(): array
	{
		$aRelPaths = [
			'Lib1/src/tests',
			'Lib1/test',
			'Lib2/examples',
			'Lib2/external',
		];

		return $this->TransformRelToAbsPaths($aRelPaths);
	}
}