<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

require_once ('../approot.inc.php');
require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'application/itopwebpage.class.inc.php');
require_once(APPROOT.'application/startup.inc.php');
require_once(APPROOT.'application/loginwebpage.class.inc.php');


/////////////////////////////////////////////////////////////////////
// Main program
//
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed


$sSubmit = utils::ReadParam('submit', '', false, 'raw_data');
if ($sSubmit != 'Reset')
{
	$sOQL = utils::ReadParam('OQL_Request', '', false, 'raw_data');
}
else
{
	$sOQL = '';
}
$bError = false;
$oP = new iTopWebPage('Database inconsistencies');
$oP->set_base(utils::GetAbsoluteUrlAppRoot().'test/');
$oP->set_title('Grouping with functions');
$oP->add('<div style="padding: 15px;"><h2>Grouping with functions</h2>');
$oP->add('<div style="padding: 15px; background: #ddd;">');
try
{
	if (!empty($sOQL))
	{
		// Getting class attributes
		$oSearch = DBSearch::FromOQL($sOQL);
		$aSearches = $oSearch->GetSearches();
		if ($oSearch instanceof DBUnionSearch)
		{
			$sClass = $aSearches[0]->GetClassAlias();
			$sRealClass = $aSearches[0]->GetClass();
		}
		else
		{
			$sClass = $oSearch->GetClassAlias();
			$sRealClass = $oSearch->GetClass();
		}

		$sGroupBy1 = utils::ReadParam('groupby_1', '');
		$sGroupBy2 = utils::ReadParam('groupby_2', '');
		$sOrderBy1 = utils::ReadParam('orderby_1', '');
		$sOrderBy2 = utils::ReadParam('orderby_2', '');

		$sAttributesOptions1 = '';
		$sAttributesOptions2 = '';
		$sAttributesOptions3 = '';
		$sAttributesOptions4 = '';

		foreach(array('_itop_sum_', '_itop_avg_', '_itop_min_', '_itop_max_', '_itop_count_', 'group1', 'group2') as $sAttCode)
		{
			$sAttributesOptions3 .= '<option value="'.$sAttCode.'" '.($sOrderBy1 == $sAttCode ? 'selected' : '').'>'.$sAttCode.'</option>';
			$sAttributesOptions4 .= '<option value="'.$sAttCode.'" '.($sOrderBy2 == $sAttCode ? 'selected' : '').'>'.$sAttCode.'</option>';
		}

		foreach(MetaModel::ListAttributeDefs($sRealClass) as $sAttCode => $oAttDef)
		{
			// Skip this attribute if not defined in this table
			if ($oSearch instanceof DBUnionSearch)
			{
				foreach($aSearches as $oSubQuery)
				{
					$sSubClass = $oSubQuery->GetClass();
					if (!MetaModel::IsValidAttCode($sSubClass, $sAttCode))
					{
						continue 2;
					}
				}
			}
			$sAttributesOptions1 .= '<option value="'.$sAttCode.'" '.($sGroupBy1 == $sAttCode ? 'selected' : '').'>'.$sAttCode.'</option>';
			$sAttributesOptions2 .= '<option value="'.$sAttCode.'" '.($sGroupBy2 == $sAttCode ? 'selected' : '').'>'.$sAttCode.'</option>';
		}

		$iLimit = intval(utils::ReadParam('top', '0'));

		$sInvOrder1 = utils::ReadParam('desc1', '');
		$sCheck1 = ($sInvOrder1 == 'on' ? 'checked' : '');

		$sInvOrder2 = utils::ReadParam('desc2', '');
		$sCheck2 = ($sInvOrder2 == 'on' ? 'checked' : '');

		$sFuncField = utils::ReadParam('funcfield', '');

		$sFuncFieldOption = '';
		foreach(MetaModel::ListAttributeDefs($sRealClass) as $sAttCode => $oAttDef)
		{
			// Skip this attribute if not defined in this table
			if ($oSearch instanceof DBUnionSearch)
			{
				foreach($aSearches as $oSubQuery)
				{
					$sSubClass = $oSubQuery->GetClass();
					if (!MetaModel::IsValidAttCode($sSubClass, $sAttCode))
					{
						continue 2;
					}
				}
			}
			switch (get_class($oAttDef))
			{
				case 'Integer':
				case 'AttributeDecimal':
				case 'AttributeDuration':
				case 'AttributeSubItem':
				case 'AttributePercentage':
					$sFuncFieldOption .= '<option value="'.$sAttCode.'" '.($sFuncField == $sAttCode ? 'selected' : '').'>'.$sAttCode.'</option>';
					break;
			}
		}
	}
}
catch (Exception $e)
{
	$oP->p('<div class="header_message message_error">'.$e->getMessage().'</div>');
	$bError = true;
}
$oP->add("<div><form>");
$oP->add("<input type=\"submit\" name=\"submit\" value=\"Reset\">\n");
$oP->add("</form></div>");

$oP->add("<form>");

$oP->add(
	<<<EOF
	<div>
		<label>Search OQL:</label>
		<div>
			<textarea id='OQL_Request' name='OQL_Request' cols='60' rows='5'>$sOQL</textarea>
		</div>
	</div>
EOF
);

if (!empty($sOQL) && !$bError)
{
	$oP->add(
		<<<EOF
	<div>
		<label>Group by:</label>
		<div>
			<select id="groupby_1" name="groupby_1">
				$sAttributesOptions1
			</select>
			<select id="groupby_2" name="groupby_2">
				<option></option>
				$sAttributesOptions2
			</select>
		</div>
	</div>
	<div>
		<label>Order by:</label>
		<div>
			<select id="orderby_1" name="orderby_1">$sAttributesOptions3</select>
			<label>Inv order</label><input type="checkbox" name="desc1" $sCheck1/>
		</div>
		<div>
			<select id="orderby_2" name="orderby_2">
				<option></option>
				$sAttributesOptions4
			</select>
			<label>Inv order</label><input type="checkbox" name="desc2" $sCheck2/>
		</div>
	</div>
	<div>
		<label>Functions on:</label>
		<div>
			<select id="funcfield" name="funcfield">$sFuncFieldOption</select>
		</div>
	</div>
	<div>
		<label>Top:</label>
		<div><input type="text" id="top" name="top" value="$iLimit"/>
		</div>
	</div>

EOF
	);
}

$oP->add("<input type=\"submit\" name=\"submit\" value=\"Search\">\n");

$oP->add("</form>");

$sSQL = '';


if (empty($sOQL) || empty($sGroupBy1))
{
	$oP->output();
	return;
}
try
{
	$iLimitStart = 0;
	$aOrderBy = array();
	if (!empty($sOrderBy1))
	{
		$aOrderBy[$sOrderBy1] = ($sInvOrder1 != 'on');
	}
	if (!empty($sOrderBy2))
	{
		$aOrderBy[$sOrderBy2] = ($sInvOrder2 != 'on');
	}

	$aGroupBy = array();
	$oExpr1 = Expression::FromOQL($sClass.'.'.$sGroupBy1);
	$aGroupBy["group1"] = $oExpr1;

	if (!empty($sGroupBy2))
	{
		$oExpr2 = Expression::FromOQL($sClass.'.'.$sGroupBy2);
		$aGroupBy["group2"] = $oExpr2;
	}

	$aArgs = array();

	if (empty($sFuncField))
	{
		$aFunctions = array();
	}
	else
	{
		$oTimeExpr = Expression::FromOQL($sClass.'.'.$sFuncField);
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);
	}

	$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy, $iLimit, $iLimitStart);

	$aRes = CMDBSource::QueryToArray($sSQL);

	// Display results
	if (!empty($aRes))
	{
		$oP->add('<div>');
		$oP->add('<table class="listResults">');
		$aLine = $aRes[0];
		$aCols = array();
		$oP->add('<tr>');
		foreach(array_keys($aLine) as $item)
		{
			if (!is_numeric($item))
			{
				$aCols[] = $item;
				$oP->add("<th>$item</th>");
			}
		}
		$oP->add('</tr>');

		foreach($aRes as $aLine)
		{
			$oP->add('<tr>');
			foreach($aCols as $sCol)
			{
				$oP->add("<td>".$aLine[$sCol]."</td>");
			}
			$oP->add('</tr>');
		}

		$oP->add('</table>');
		$oP->add('</div>');
	}
	else
	{
		$oP->add("<p>No Result</p>\n");
	}
}
catch (Exception $e)
{
	$oP->p('<div class="header_message message_error">'.$e->getMessage().'</div>');
	$bError = true;
}

$oP->add("<div class=\"header_message message_info\">$sSQL</div>\n");

$oP->output();

return;

/*
echo "<pre>";
$aClassSelection = MetaModel::GetClasses();
foreach($aClassSelection as $sClass)
{
	if (!MetaModel::HasTable($sClass))
	{
		continue;
	}
	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
	{
		// Skip this attribute if not defined in this table
		if (!MetaModel::IsAttributeOrigin($sClass, $sAttCode))
		{
			continue;
		}
		switch (get_class($oAttDef))
		{
			case 'Integer':
			case 'AttributeDecimal':
			case 'AttributeDuration':
			case 'AttributeSubItem':
			case 'AttributePercentage':
			echo "$sClass:$sAttCode = ".get_class($oAttDef)."\n";
				break;
		}
	}
}
*/