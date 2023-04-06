<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class ormCaseLogService
{
	const CASE_LOG_SEPARATOR_REGEX_FIND = "\n?========== \w+-\d+-\d+ \d+:\d+:\d+ : .*\s\(\d+\) ============\n\n";
	const CASE_LOG_SEPARATOR_REGEX_EXTRACT = "\n?========== (?<date>\w+-\d+-\d+ \d+:\d+:\d+) : (?<user_name>.*)\s\((?<user_id>\d+)\) ============\n\n";

	public function __construct()
	{
	}

	/**
	 * @param $sLog
	 *
	 * @return array
	 */
	public function RebuildIndex(string $sLog, array $aIndex)
	{
		$aTexts = preg_split('/'.self::CASE_LOG_SEPARATOR_REGEX_FIND.'/', $sLog, 0, PREG_SPLIT_NO_EMPTY);
		preg_match_all('/'.self::CASE_LOG_SEPARATOR_REGEX_FIND.'/', $sLog, $aMatches);

		if (count($aTexts) != count($aMatches[0])) {
			return [];
		}

		$aRebuiltIndex = [];
		$iPrevDate = 0;
		for ($index = count($aTexts) - 1; $index >= 0; $index--) {
			$sSeparator = $aMatches[0][$index];
			preg_match('/'.self::CASE_LOG_SEPARATOR_REGEX_EXTRACT.'/', $sSeparator, $aSeparatorParts);

			try {
				$iDate = (int)AttributeDateTime::GetAsUnixSeconds($aSeparatorParts['date']);
				$iPrevDate = $iDate + 1;
			}
			catch (Exception $e) {
				$iDate = $iPrevDate;
			}

			$user_id = $aSeparatorParts['user_id'];
			$aRebuiltIndex[] = array(
				'user_name' => $aSeparatorParts['user_name'],
				'user_id' => $user_id ==='0' ? null : $user_id,
				'date' => $iDate,
				'text_length' => strlen($aTexts[$index]),
				'separator_length' => strlen($sSeparator),
				'format' => 'html',
			);
		}

		return $aRebuiltIndex;
	}
}
