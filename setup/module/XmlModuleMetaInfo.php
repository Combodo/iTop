<?php

class XmlModuleMetaInfo {
	public string $sLastNodeId;
	public string $sNodeName;
	public string $sPath;
	public string $sDelta;

	public function __construct(string $sLastNodeId, string $sNodeName, string $sPath, string $sDelta)
	{
		$this->sLastNodeId = $sLastNodeId;
		$this->sNodeName = $sNodeName;
		$this->sPath = $sPath;
		$this->sDelta = $sDelta;
	}

	public function IsDefine() : bool {
		return
			 $this->sDelta === 'define_if_not_exists'
			|| $this->sDelta === 'define';
	}

	public function GetUID() : string
	{
		return $this->sNodeName . '_' . $this->sPath;
	}
}