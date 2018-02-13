<?php
class HubRunTimeEnvironment extends RunTimeEnvironment
{	
	/**
	 * Constructor
	 * @param string $sEnvironment
	 * @param string $bAutoCommit
	 */
	public function __construct($sEnvironment = 'production', $bAutoCommit = true)
	{
		parent::__construct($sEnvironment, $bAutoCommit);
		
		if ($sEnvironment != $this->sTargetEnv)
		{
		    if (is_dir(APPROOT.'/env-'.$this->sTargetEnv))
		    {
		        SetupUtils::rrmdir(APPROOT.'/env-'.$this->sTargetEnv);
		    }
		    if (is_dir(APPROOT.'/data/'.$this->sTargetEnv.'-modules'))
		    {
		        SetupUtils::rrmdir(APPROOT.'/data/'.$this->sTargetEnv.'-modules');
		    }
			SetupUtils::copydir(APPROOT.'/data/'.$sEnvironment.'-modules', APPROOT.'/data/'.$this->sTargetEnv.'-modules');
		}
	}
	
	/**
	 * Update the includes for the target environment
	 * @param Config $oConfig
	 */
	public function UpdateIncludes(Config $oConfig)
	{
		$oConfig->UpdateIncludes('env-'.$this->sTargetEnv); // TargetEnv != FinalEnv
	}
	
	/**
	 * Move an extension (path to folder of this extension) to the target environment
	 * @param string $sExtensionDirectory The folder of the extension
	 * @throws Exception
	 */
	public function MoveExtension($sExtensionDirectory)
	{
		if (!is_dir(APPROOT.'/data/'.$this->sTargetEnv.'-modules'))
		{
			if (!mkdir(APPROOT.'/data/'.$this->sTargetEnv.'-modules')) throw new Exception("ERROR: failed to create directory:'".(APPROOT.'/data/'.$this->sTargetEnv.'-modules')."'");
		}
		$sDestinationPath = APPROOT.'/data/'.$this->sTargetEnv.'-modules/';
		
		// Make sure that the destination directory of the extension does not already exist
		if (is_dir($sDestinationPath.basename($sExtensionDirectory)))
		{
		    // Cleanup before moving...
		    SetupUtils::rrmdir($sDestinationPath.basename($sExtensionDirectory));
		}
		if (!rename($sExtensionDirectory, $sDestinationPath.basename($sExtensionDirectory))) throw new Exception("ERROR: failed move directory:'$sExtensionDirectory' to '".$sDestinationPath.basename($sExtensionDirectory)."'");
	}
	
	/**
	 * Move the selected extensions located in the given directory in data/<target-env>-modules
	 * @param string $sDownloadedExtensionsDir The directory to scan
	 * @param string[] $aSelectedExtensionDirs The list of folders to move
	 * @throws Exception
	 */
	public function MoveSelectedExtensions($sDownloadedExtensionsDir, $aSelectedExtensionDirs)
	{
		foreach(glob($sDownloadedExtensionsDir.'*', GLOB_ONLYDIR) as $sExtensionDir)
		{
			if (in_array(basename($sExtensionDir), $aSelectedExtensionDirs))
			{
				$this->MoveExtension($sExtensionDir);
			}
		}
	}
}
