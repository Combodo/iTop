<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Simple web page with no includes or fancy formatting, useful to generateXML documents
 * The page adds the content-type text/XML and the encoding into the headers
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once("../application/webpage.class.inc.php");

class CSVPage extends WebPage
{
    function __construct($s_title)
    {
        parent::__construct($s_title);
		$this->add_header("Content-type: text/html; charset=iso-8859-1");
		$this->add_header("Cache-control: no-cache");
    }	

    public function output()
    {
		$this->add_header("Content-Length: ".strlen(trim($this->s_content)));
        foreach($this->a_headers as $s_header)
        {
            header($s_header);
        }
        echo trim($this->s_content);
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

?>
