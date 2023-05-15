<?php

class LogBrokenOrmCaseLog implements iOrmCaseLogExtension {
	public function Rebuild($sCaseLogId, &$sLog, &$aIndex): bool {
		try {
			$oUser->CheckProfiles();
		} catch (Exception $oException) {
			\IssueLog::Error('Broken caselog', LogChannels::ORM_CASELOG, [
				'caselog_id' => $sCaseLogId,
				'exception_message' => $oException->getMessage(),
				'exception_stacktrace' => $oException->getTraceAsString(),
			]);
		}
	}
}
