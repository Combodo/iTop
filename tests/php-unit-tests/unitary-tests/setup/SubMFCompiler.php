<?php

/**
 * Class SubMFCompiler: used to call a protected method for testing purpose
 */
class SubMFCompiler extends MFCompiler {
	public function CompileThemes($oBrandingNode, $sTempTargetDir) {
		return parent::CompileThemes($oBrandingNode, $sTempTargetDir);
	}

	public function GetCompilationTimeStamp(){
		return $this->sCompilationTimeStamp;
	}
}