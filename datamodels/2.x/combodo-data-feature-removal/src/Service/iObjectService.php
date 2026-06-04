<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

use DBObject;

interface iObjectService
{
	public function Update(DBObject $oToUpdate, string $sAttCode, $value): void;

	public function Delete(string $sClass, string $sId): void;

	public function SetIssue(string $sClass): void;

	public function GetSummary(): array;
}
