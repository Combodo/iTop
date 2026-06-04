<?php

class SetupDBBackup extends DBBackup
{
	protected function LogInfo($sMsg)
	{
		SetupLog::Ok('Info - '.$sMsg);
	}

	protected function LogError($sMsg)
	{
		SetupLog::Ok('Error - '.$sMsg);
	}
}
