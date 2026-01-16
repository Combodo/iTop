<?php

// Copyright (C) 2012-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Application\Dashlet\Core;

use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Dict;
use utils;

class DashletGroupByBars extends DashletGroupBy
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'bars';
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$oDashletContainer = new DashletContainer(null, ['dashlet-content']);

		$sTitle = $this->aProperties['title'];

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$HTMLsTitle = ($sTitle != '') ? '<h1 style="text-align:center">'.utils::HtmlEntities($sTitle).'</h1>' : '';
		$oDashletContainer->AddHtml("<div style=\"background-color:#fff;padding:0.25em;\">$HTMLsTitle<div id=\"$sBlockId\" style=\"background-color:#fff;\"></div></div>");

		$aDisplayValues = $this->MakeSimulatedData();

		$aNames = [];
		foreach ($aDisplayValues as $idx => $aValue) {
			$aNames[$idx] = $aValue['label'];
		}
		$sJSNames = json_encode($aNames);

		$sJson = json_encode($aDisplayValues);
		$oPage->add_ready_script(
			<<<EOF
window.setTimeout(function() {
	var chart = c3.generate({
    bindto: '#{$sBlockId}',
    data: {
   	  json: $sJson,
      keys: {
      	x: 'label',
      	value: ["value"]
	  },
	  selection: {
		enabled: true
	  },
      type: 'bar'
    },
    axis: {
        x: {
			tick: {
				culling: {max: 25}, // Maximum 24 labels on x axis (2 years).
				centered: true,
				rotate: 90,
				multiline: false
			},
            type: 'category'   // this needed to load string x value
        }
    },
	grid: {
		y: {
			show: true
		}
	},
    legend: {
      show: false,
    },
	tooltip: {
	  grouped: false,
	  format: {
		title: function() { return '' },
	    name: function (name, ratio, id, index) {
			var aNames = $sJSNames;
			return aNames[index];
		}
	  }
	}
});
}, 100);
EOF
		);

		return $oDashletContainer;
	}
}
