<?php
// Copyright (C) 2010-2015 Combodo SARL
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


/**
 * CLI page 
 * The page adds the content-type text/XML and the encoding into the headers
 *
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/webpage.class.inc.php");

class CLIPage implements Page
{
    function __construct($s_title)
    {
    }	

    public function output()
    {
        if (class_exists('DBSearch'))
        {
            DBSearch::RecordQueryTrace();
        }
        if (class_exists('ExecutionKPI'))
        {
            ExecutionKPI::ReportStats();
        }
    }

	public function add($sText)
	{
		echo $sText;
	}	

	public function p($sText)
	{
		echo $sText."\n";
	}	

	public function pre($sText)
	{
		echo $sText."\n";
	}	

	public function add_comment($sText)
	{
		echo "#".$sText."\n";
	}	

	public function table($aConfig, $aData, $aParams = array())
	{
		$aCells = array();
		foreach($aConfig as $sName=>$aDef)
		{
			if (strlen($aDef['description']) > 0)
			{
				$aCells[] = $aDef['label'].' ('.$aDef['description'].')';
			}
			else
			{
				$aCells[] = $aDef['label'];
			}
		}
		echo implode(';', $aCells)."\n";

		foreach($aData as $aRow)
		{
			$aCells = array();
			foreach($aConfig as $sName=>$aAttribs)
			{
				$sValue = $aRow["$sName"];
				$aCells[] = $sValue;
			}
			echo implode(';', $aCells)."\n";
		}
	}
}

?>
