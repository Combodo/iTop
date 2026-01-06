<?php

namespace Combodo\iTop\Service\Dashboard;

use ReflectionClass;

class DashletService {
	public static function GetAvailableDashlets(): array {
		$aDashlets = [];

		foreach (get_declared_classes() as $sDashletClass) {
			// DashletUnknown is not among the selection as it is just a fallback for dashlets that can't be instantiated.
			if (is_subclass_of($sDashletClass, 'Dashlet') && !in_array($sDashletClass, ['DashletUnknown', 'DashletProxy'])) {
				$oReflection = new ReflectionClass($sDashletClass);
				if (!$oReflection->isAbstract()) {
					$aCallSpec = [$sDashletClass, 'IsVisible'];
					$bVisible = call_user_func($aCallSpec);
					if ($bVisible) {
						$aCallSpec = [$sDashletClass, 'GetInfo'];
						$aInfo = call_user_func($aCallSpec);
						$aDashlets[$sDashletClass] = $aInfo;
					}
				}
			}
		}

		return $aDashlets;
	}
}
