<?php

class ormBrokenCaselogExtension implements iOrmCaseLogExtension {
	const CASE_LOG_SEPARATOR_REGEX_FIND = "\n?========== \w+-\d+-\d+ \d+:\d+:\d+ : .*\s\(\d+\) ============\n\n";
	const CASE_LOG_SEPARATOR_REGEX_EXTRACT = "\n?========== (?<date>\w+-\d+-\d+ \d+:\d+:\d+) : (?<user_name>.*)\s\((?<user_id>\d+)\) ============\n\n";

	public function Rebuild(&$sLog, &$aIndex): bool {
		try {
			if (! self::IsIndexIntegrityOk($aIndex, $sLog)){
				throw new \Exception();
			}
		} catch (Exception $oException) {
			\IssueLog::Error('Broken caselog', LogChannels::ORM_CASELOG, [
				'sLog' => $sLog,
				'$aIndex' => $aIndex,
				'exception_stacktrace' => $oException->getTraceAsString(),
			]);
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function IsIndexIntegrityOk($aIndex, $sLog, $bExtraChecks = false)
	{
		preg_match_all('/'.self::CASE_LOG_SEPARATOR_REGEX_FIND.'/', $sLog, $aMatches);
		if (count($aIndex) != count($aMatches[0])) {
			return false;
		}

		$iPos = 0;
		for ($i = count($aIndex) - 1; $i >= 0; $i--) {
			$iSeparatorLen = $aIndex[$i]['separator_length'];
			$sSeparator = substr($sLog, $iPos, $iSeparatorLen);
			if (!preg_match('/^'.self::CASE_LOG_SEPARATOR_REGEX_FIND.'$/', $sSeparator)) {
				return false;
			}

			$iPos += $iSeparatorLen;
			$iPos += $aIndex[$i]['text_length'];
		}

		if ($bExtraChecks) {
			$aNewIndex = static::RebuildIndex($sLog);
			if ($aIndex != $aNewIndex) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param $sLog
	 *
	 * @return array
	 */
	public static function RebuildIndex($sLog)
	{
		$aTexts = preg_split('/'.self::CASE_LOG_SEPARATOR_REGEX_FIND.'/', $sLog, 0, PREG_SPLIT_NO_EMPTY);
		preg_match_all('/'.self::CASE_LOG_SEPARATOR_REGEX_FIND.'/', $sLog, $aMatches);

		if (count($aTexts) != count($aMatches[0])) {
			return [];
		}

		$aIndex = [];
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

			$aIndex[] = array(
				'user_name' => $aSeparatorParts['user_name'],
				'user_id' => $aSeparatorParts['user_id'],
				'date' => $iDate,
				'text_length' => strlen($aTexts[$index]),
				'separator_length' => strlen($sSeparator),
				'format' => 'html',
			);
		}

		return $aIndex;
	}
}
