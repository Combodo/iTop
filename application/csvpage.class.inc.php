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
 * Simple web page with no includes or fancy formatting, useful to generateXML documents
 * The page adds the content-type text/XML and the encoding into the headers
 *
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/webpage.class.inc.php");

class CSVPage extends WebPage
{
    function __construct($s_title)
    {
        parent::__construct($s_title);
		$this->add_header("Content-type: text/plain; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		//$this->add_header("Content-Transfer-Encoding: binary");
    }	

    public function output()
    {
			$this->add_header("Content-Length: ".strlen(trim($this->s_content)));

			// Get the unexpected output but do nothing with it
			$sTrash = $this->ob_get_clean_safe();

        foreach($this->a_headers as $s_header)
        {
            header($s_header);
        }
        echo trim($this->s_content);
        echo "\n";

        if (class_exists('DBSearch'))
        {
            DBSearch::RecordQueryTrace();
        }
        if (class_exists('ExecutionKPI'))
        {
            ExecutionKPI::ReportStats();
        }
    }

	public function small_p($sText)
	{
	}

	public function add($sText)
	{
		$this->s_content .= $sText;
	}	

	public function p($sText)
	{
		$this->s_content .= $sText."\n";
	}	

	public function add_comment($sText)
	{
		$this->s_content .= "#".$sText."\n";
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
		$this->s_content .= implode(';', $aCells)."\n";

		foreach($aData as $aRow)
		{
			$aCells = array();
			foreach($aConfig as $sName=>$aAttribs)
			{
				$sValue = $aRow["$sName"];
				$aCells[] = $sValue;
			}
			$this->s_content .= implode(';', $aCells)."\n";
		}
	}
}

