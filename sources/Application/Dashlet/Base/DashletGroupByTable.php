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

namespace Combodo\iTop\Application\Dashlet\Base;

use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Dict;

class DashletGroupByTable extends DashletGroupBy
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'table';
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$oDashletContainer = new DashletContainer();

		$aDisplayValues = $this->MakeSimulatedData();
		$iTotal = 0;
		foreach ($aDisplayValues as $iRow => $aDisplayData) {
			$iTotal += $aDisplayData['value'];
		}

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$sHtml = '';
		$sHtml .= '<div id="'.$sBlockId.'" class="display_block">';
		$sHtml .= '<div class="dashlet-content">';
		$sHtml .= '<p>'.Dict::Format('UI:Pagination:HeaderNoSelection', $iTotal).'</p>';
		$sHtml .= '<table class="listResults">';
		$sHtml .= '<thead>';
		$sHtml .= '<tr>';
		$sHtml .= '<th class="header" title="">'.$this->sGroupByLabel.'</th>';
		$sHtml .= '<th class="header" title="'.Dict::S('UI:GroupBy:Count+').'">'.Dict::S('UI:GroupBy:Count').'</th>';
		$sHtml .= '</tr>';
		$sHtml .= '</thead>';
		$sHtml .= '<tbody>';
		foreach ($aDisplayValues as $aDisplayData) {
			$sHtml .= '<tr class="even">';
			$sHtml .= '<td class=""><span title="Active">'.$aDisplayData['label'].'</span></td>';
			$sHtml .= '<td class=""><a>'.$aDisplayData['value'].'</a></td>';
			$sHtml .= '</tr>';
		}
		$sHtml .= '</tbody>';
		$sHtml .= '</table>';
		$sHtml .= '</div>';

		$sHtml .= '</div>';

		$oDashletContainer->AddHtml($sHtml);

		return $oDashletContainer;
	}
}
