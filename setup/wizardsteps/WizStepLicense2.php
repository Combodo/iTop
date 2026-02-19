<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/**
 * License acceptation screen (when upgrading)
 */
class WizStepLicense2 extends WizStepLicense
{
	public function GetPossibleSteps()
	{
		return [WizStepUpgradeMiscParams::class];
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		return new WizardState(WizStepUpgradeMiscParams::class);
	}
}
