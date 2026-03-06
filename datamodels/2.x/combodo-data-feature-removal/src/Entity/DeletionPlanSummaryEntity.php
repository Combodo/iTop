<?php

namespace Combodo\iTop\DataFeatureRemoval\Entity;

class DeletionPlanSummaryEntity
{
	public string $sClass;

	/**
	 * @var int : DEL_MANUAL|DEL_AUTO|DEL_SILENT|DEL_MOVEUP|DEL_NONE
	 * @see \AttributeDefinition DEL_xxx
	 */
	public int $iMode=0;
	public ?string $sIssue=null;
	public int $iUpdateCount=0;
	public int $iDeleteCount=0;

	/**
	 * @param string $sClass
	 */
	public function __construct(string $sClass)
	{
		$this->sClass = $sClass;
	}
}
