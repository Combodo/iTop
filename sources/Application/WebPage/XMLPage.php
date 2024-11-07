<?php
// Copyright (C) 2010-2024 Combodo SAS
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

namespace Combodo\iTop\Application\WebPage;

use DBSearch;
use ExecutionKPI;

/**
 * Class XMLPage
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Simple web page with no includes or fancy formatting, useful to generateXML documents
 * The page adds the content-type text/XML and the encoding into the headers
 */
class XMLPage extends WebPage
{
	/**
	 * For big XML files, it's better NOT to store everything in memory and output the XML piece by piece
	 */
	var $m_bPassThrough;
	var $m_bHeaderSent;
	
	function __construct($s_title, $bPassThrough = false)
	{
		$oKpi = new ExecutionKPI();
		parent::__construct($s_title);
		$this->m_bPassThrough = $bPassThrough;
		$this->m_bHeaderSent = false;
		$this->add_header("Content-type: text/xml; charset=".self::PAGES_CHARSET);
		$this->no_cache();
		$this->add_http_headers();
		$this->add_header("Content-location: export.xml");
		$oKpi->ComputeStats(get_class($this).' creation', 'XMLPage');
	}

	/**
	 * Disabling sending the header so that resource won't be blocked by CORB. See parent method documentation.
	 * @return void
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 N°4368 method creation
	 */
	public function add_xcontent_type_options()
	{
		// Nothing to do !
	}
	public function output()
	{
		if (!$this->m_bPassThrough)
		{
			$oKpi = new ExecutionKPI();

			// Get the unexpected output but do nothing with it
			$sTrash = $this->ob_get_clean_safe();

			$sCharset = self::PAGES_CHARSET;
			$this->s_content = "<?xml version=\"1.0\" encoding=\"$sCharset\"?".">\n".trim($this->s_content);
			$this->add_header("Content-Length: ".strlen($this->s_content));
			foreach($this->a_headers as $s_header)
			{
				header($s_header);
			}
			$oKpi->ComputeAndReport(get_class($this).' output');
			echo $this->s_content;
			$oKpi->ComputeAndReport('Echoing ('.round(strlen($this->s_content) / 1024).' Kb)');
		}
		if (class_exists('DBSearch')) {
			DBSearch::RecordQueryTrace();
		}
		ExecutionKPI::ReportStats();
	}

	public function add($sText)
	{
		if (!$this->m_bPassThrough) {
			parent::add($sText);
		} else {
			if ($this->m_bHeaderSent) {
				echo $sText;
			}
			else
			{
				$s_captured_output = $this->ob_get_clean_safe();
				foreach($this->a_headers as $s_header)
				{
					header($s_header);
				}
				$sCharset = self::PAGES_CHARSET;
				echo "<?xml version=\"1.0\" encoding=\"$sCharset\"?".">\n";
				echo trim($s_captured_output);
				echo trim($this->s_content);
				echo $sText;
				$this->m_bHeaderSent = true;
			}
		}
	}

	public function small_p($sText)
	{
	}

	public function table($aConfig, $aData, $aParams = array())
	{
	}
}
