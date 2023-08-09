<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest\Integration;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Dict;
use const APPROOT;

/**
 * As {@see DictionariesConsistencyTest}, we are testing dict files, but the ones that are compiled, so we cannot be in the beforeSetup group !
 */
class CompiledDictionariesConsistencyTest extends ItopTestCase
{
	/**
	 * make sure N°5305 dictionary changes (CSV import ergonomy) are still here and UI remains unbroken for any lang
	 *
	 * One of the things checked is the number of parameters in the dict value. This is for now crashing the app (N°5491)
	 * and we have multiple inconsistencies in our existing dict files... So it is complicated to have a generic test for all files !
	 * At least we are protecting those new entries...
	 */
	public function testImportCsvMessageStillOk()
	{
		$aFailedLabels = [];
		$aLabelsToTest = [
			'UI:CSVReport-Value-SetIssue' => [],
			'UI:CSVReport-Value-ChangeIssue' => ['arg1'],
			'UI:CSVReport-Value-NoMatch' => ['arg1'],
			'UI:CSVReport-Value-NoMatch-PossibleValues' => ['arg1', 'arg2'],
			'UI:CSVReport-Value-NoMatch-NoObject' => ['arg1'],
			'UI:CSVReport-Value-NoMatch-NoObject-ForCurrentUser' => ['arg1'],
			'UI:CSVReport-Value-NoMatch-SomeObjectNotVisibleForCurrentUser' => ['arg1'],
		];

		$sCompiledLanguagesFilePath = APPROOT . 'env-' . \utils::GetCurrentEnvironment() . '/dictionaries/languages.php';
		$this->assertFileExists($sCompiledLanguagesFilePath, 'We must have an existing compiled language.php file in the current env !');
		require_once($sCompiledLanguagesFilePath);
		$this->assertNotEmpty(Dict::GetLanguages(), 'the languages.php file exists but didn\'t load any language');

		foreach (glob(APPROOT . 'env-' . \utils::GetCurrentEnvironment() . '/dictionaries/*.dict.php') as $sDictFile) {
			if (preg_match('/.*\\/(.*).dict.php/', $sDictFile, $aMatches)) {
				$sLangCode = $aMatches[1];
				$sLanguageCode = strtoupper(str_replace('-', ' ', $sLangCode));
				Dict::SetUserLanguage($sLanguageCode);

				foreach ($aLabelsToTest as $sLabelKey => $aLabelArgs) {
					echo "Testing $sDictFile, label $sLabelKey with " . \var_export($aLabelArgs, true) . "\n";
					try {
						$sLabelValue = Dict::Format($sLabelKey, ...$aLabelArgs);
						//$this->debug($sLabelValue);
					} catch (\ValueError $e) {
						$aFailedLabels[] = $sLabelKey;

						$this->debug([
							'exception' => $e->getMessage(),
							'trace' => $e->getTraceAsString(),
							'label_name' => $sLabelKey,
							'label_args' => $aLabelArgs,
						]);
					}
				}
				$this->assertEquals([], $aFailedLabels, "$sDictFile : test fail for lang $sLangCode and labels (" . implode(", ", $aFailedLabels) . ')');
			}
		}
	}
}
