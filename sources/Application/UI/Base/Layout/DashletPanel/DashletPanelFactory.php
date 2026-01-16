<?php

namespace Combodo\iTop\Application\UI\Base\Layout\DashletPanel;

use Combodo\iTop\Application\Dashlet\Service\DashletService;

class DashletPanelFactory
{
	public static function MakeForDashboardEditor(string $sId = null): DashletPanel
	{
		$oDashletPanel = new DashletPanel($sId);

		$aAvailableDashlets = DashletService::GetInstance()->GetAvailableDashlets();

		foreach ($aAvailableDashlets as $sDashletClass => $aDashletInformation) {
			$oDashletEntry = new DashletEntry($sDashletClass, $aDashletInformation['label'], $aDashletInformation['description'], $aDashletInformation['icon']);

			if (isset($aDashletInformation['min_width'])) {
				$oDashletEntry->SetDashletMinWidth($aDashletInformation['min_width']);
			}
			if (isset($aDashletInformation['min_height'])) {
				$oDashletEntry->SetDashletMinHeight($aDashletInformation['min_height']);
			}
			if (isset($aDashletInformation['preferred_width'])) {
				$oDashletEntry->SetDashletPreferredWidth($aDashletInformation['preferred_width']);
			}
			if (isset($aDashletInformation['preferred_height'])) {
				$oDashletEntry->SetDashletPreferredHeight($aDashletInformation['preferred_height']);
			}

			$oDashletPanel->AddDashletEntry($oDashletEntry);
		}

		return $oDashletPanel;
	}
}
