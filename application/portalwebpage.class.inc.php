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
 * Class PortalWebPage
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT."/application/nicewebpage.class.inc.php");
require_once(APPROOT."/application/applicationcontext.class.inc.php");
require_once(APPROOT."/application/user.preferences.class.inc.php");

/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 * of the Portal web page
 */
class PortalWebPage extends NiceWebPage
{
	/**
	 * Portal menu
	 */
	protected $m_aMenuButtons;
	
    public function __construct($sTitle, $sAlternateStyleSheet = '')
    {
    	$this->m_aMenuButtons = array();
        parent::__construct($sTitle);
		$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->add_linked_stylesheet("../css/jquery.treeview.css");
		$this->add_linked_stylesheet("../css/jquery.autocomplete.css");
		if ($sAlternateStyleSheet != '')
		{
			$this->add_linked_stylesheet("../portal/$sAlternateStyleSheet/portal.css");
		}
		else
		{
			$this->add_linked_stylesheet("../portal/portal.css");
		}
		$this->add_linked_script('../js/jquery.layout.min.js');
		$this->add_linked_script('../js/jquery.ba-bbq.min.js');
		$this->add_linked_script("../js/jquery.tablehover.js");
		$this->add_linked_script("../js/jquery.treeview.js");
		$this->add_linked_script("../js/jquery.autocomplete.js");
		$this->add_linked_script("../js/jquery.positionBy.js");
		$this->add_linked_script("../js/jquery.popupmenu.js");
		$this->add_linked_script("../js/date.js");
		$this->add_linked_script("../js/jquery.tablesorter.min.js");
		$this->add_linked_script("../js/jquery.tablesorter.pager.js");
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../js/utils.js");
		$this->add_linked_script("../js/forms-json-utils.js");
		$this->add_linked_script("../js/swfobject.js");
		$this->add_ready_script(
<<<EOF
try
{
	//add new widget called TruncatedList to properly display truncated lists when they are sorted
	$.tablesorter.addWidget({ 
	    // give the widget a id 
	    id: "truncatedList", 
	    // format is called when the on init and when a sorting has finished 
	    format: function(table)
	    { 
			// Check if there is a "truncated" line
			this.truncatedList = false;  
			if ($("tr td.truncated",table).length > 0)
			{
				this.truncatedList = true;
			}
			if (this.truncatedList)
			{
				$("tr td",table).removeClass('truncated');
				$("tr:last td",table).addClass('truncated');
			}
	    } 
	});
		
	
	$.tablesorter.addWidget({ 
	    // give the widget a id 
	    id: "myZebra", 
	    // format is called when the on init and when a sorting has finished 
	    format: function(table)
	    {
	    	// Replace the 'red even' lines by 'red_even' since most browser do not support 2 classes selector in CSS, etc..
			$("tbody tr:even",table).addClass('even');
			$("tbody tr.red:even",table).removeClass('red').removeClass('even').addClass('red_even');
			$("tbody tr.orange:even",table).removeClass('orange').removeClass('even').addClass('orange_even');
			$("tbody tr.green:even",table).removeClass('green').removeClass('even').addClass('green_even');
	    } 
	});
		
	$(".date-pick").datepicker({
			showOn: 'button',
			buttonImage: '../images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
	$('.resizable').resizable(); // Make resizable everything that claims to be resizable !
	$('.caselog_header').click( function () { $(this).toggleClass('open').next('.caselog_entry').toggle(); });
}
catch(err)
{
	// Do something with the error !
	alert(err);
}
EOF
);

	$this->add_script(
<<<EOF
	function CheckSelection(sMessage)
	{
		var bResult = ($('input:checked').length > 0);
		if (!bResult)
		{
			alert(sMessage);
		}
		return bResult;
	}

	function GoBack()
	{
		var form = $('#request_form');
		var step = $('input[name=step]');

		form.unbind('submit'); // De-activate validation
		step.val(step.val() -2); // To go Back one step: next step is x, current step is x-1, previous step is x-2
		form.submit(); // Go
	}
EOF
);
		
	}
	
	/**
	 * Add a button to the portal's main menu
	 */
	public function AddMenuButton($sId, $sLabel, $sHyperlink)
	{
		$this->m_aMenuButtons[] = array('id' => $sId, 'label' => $sLabel, 'hyperlink' => $sHyperlink);
	}
	
	public function output()
	{
		$sMenu = '';
		$this->AddMenuButton('logoff', 'Portal:Disconnect', '../pages/logoff.php'); // This menu is always present and is the last one
		foreach($this->m_aMenuButtons as $aMenuItem)
		{
			$sMenu .= "<a class=\"button\" id=\"{$aMenuItem['id']}\" href=\"{$aMenuItem['hyperlink']}\"><span>".Dict::S($aMenuItem['label'])."</span></a>";
		}
		$this->s_content = '<div id="portal"><div id="banner"><div id="logo"></div>'.$sMenu.'</div><div id="content">'.$this->s_content.'</div></div>';
		parent::output();
	}

	/**
	 * Displays a list of objects, without any hyperlink (except for the object's details)
	 * @param DBObjectSet $oSet The set of objects to display
	 * @param Array $aZList The ZList (list of field codes) to use for the tabular display
	 * @param String $sEmptyListMessage Message displayed whenever the list is empty
	 * @return string The HTML text representing the list
	 */
	 function DisplaySet($oSet, $aZList, $sEmptyListMessage = '')
	 {
		if ($oSet->Count() > 0)
		{
			$sClass = $oSet->GetClass();
			if (is_subclass_of($sClass, 'cmdbAbstractObject'))
			{
				// Home-made and very limited display of an object set

				//
				//$oSet->Seek(0);// juste pour que le warning soit moins crado
				//$oSet->Fetch();// juste pour que le warning soit moins crado
				//

				$this->add("<div id=\"listOf$sClass\">\n");
				cmdbAbstractObject::DisplaySet($this, $oSet, array('currentId' => "listOf$sClass", 'menu' => false, 'zlist' => false, 'extra_fields' => implode(',', $aZList)));
				$this->add("</div>\n");
			}
			else
			{
				// Home-made and very limited display of an object set
				$aAttribs = array();
				$aValues = array();
				$aAttribs['key'] = array('label' => MetaModel::GetName($sClass), 'description' => '');
				foreach($aZList as $sAttCode)
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$aAttribs[$sAttCode] = array('label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
				}
				while($oObj = $oSet->Fetch())
				{
					$aRow = array();
					
					$aRow['key'] = '<a href="./index.php?operation=details&class='.get_class($oObj).'&id='.$oObj->GetKey().'">'.$oObj->Get('friendlyname').'</a>';
					$sHilightClass = $oObj->GetHilightClass();
					if ($sHilightClass != '')
					{
						$aRow['@class'] = $sHilightClass;	
					}
					foreach($aZList as $sAttCode)
					{
						$aRow[$sAttCode] = GetFieldAsHtml($oObj, $sAttCode);
					}
					$aValues[$oObj->GetKey()] = $aRow;
				}
				$this->table($aAttribs, $aValues);
			}
		}
		elseif (strlen($sEmptyListMessage) > 0)
		{
			$this->add($sEmptyListMessage);
		}
	}
	/**
	 * Display the attributes of an object (no title, no form)
	 * @param Object $oObj Any kind of object
	 * @param aAttList The list of attributes to display
	 * @return void
	 */
	function DisplayObjectDetails($oObj, $aAttList)
	{
		$sClass = get_class($oObj);
		$aDetails = array();
		foreach($aAttList as $sAttCode)
		{
			$iFlags = $oObj->GetAttributeFlags($sAttCode);
			$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
			if ( (!$oAttDef->IsLinkSet()) && (($iFlags & OPT_ATT_HIDDEN) == 0) )
			{
				// Don't display linked set and non-visible attributes (in this state)
				$sDisplayValue = GetFieldAsHtml($oObj, $sAttCode);
				$aDetails[] = array('label' => '<span title="'.MetaModel::GetDescription($sClass, $sAttCode).'">'.MetaModel::GetLabel($sClass, $sAttCode).'</span>', 'value' => $sDisplayValue);
			}
		}
		if (false) // Attachements !!!!!
		{
			$sAttachements = '<table>';
			while($oDoc = $oDocSet->Fetch())
			{
				$sAttachements .= '<tr><td>'.$oDoc->GetAsHtml('contents').'</td></tr>';
			}
			$sAttachements .= '</table>';
			$aDetails[] = array('label' => Dict::S('Portal:Attachments'), 'value' => $sAttachements);
		}
		$this->details($aDetails);
	}
	
	/**
	 * xxxx
	 * @param Object $oObj Any kind of object
	 * @param $sLinkSetAttCode The attribute code of the link set attribute to display
	 * @param $sRemoteAttCode The external key on the linked class, pointing to the remote objects
	 * @param $aZList The list of attribute of the remote object 
	 * @return void
	 */
	function DisplayObjectLinkset($oObj, $sLinkSetAttCode, $sRemoteAttCode, $aZList, $sEmptyListMessage = '')
	{
		if (empty($sEmptyListMessage))
		{
			$sEmptyListMessage = Dict::S('UI:Search:NoObjectFound');
		}
	
		$oSet = $oObj->Get($sLinkSetAttCode);
	
		if ($oSet->Count() > 0)
		{
			$sClass = $oSet->GetClass();
			$oExtKeyToRemote = MetaModel::GetAttributeDef($sClass, $sRemoteAttCode);
			$sRemoteClass = $oExtKeyToRemote->GetTargetClass();			
	
			$aAttribs = array();
			$aValues = array();
			$aAttribs['key'] = array('label' => MetaModel::GetName($sRemoteClass), 'description' => '');
			foreach($aZList as $sAttCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($sRemoteClass, $sAttCode);
				$aAttribs[$sAttCode] = array('label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
			}
			while($oLink = $oSet->Fetch())
			{
				$aRow = array();
	
				$oObj = MetaModel::GetObject($sRemoteClass, $oLink->Get($sRemoteAttCode));
	
				$aRow['key'] = '<a href="./index.php?operation=details&class='.get_class($oObj).'&id='.$oObj->GetKey().'">'.$oObj->Get('friendlyname').'</a>';
				$sHilightClass = $oObj->GetHilightClass();
				if ($sHilightClass != '')
				{
					$aRow['@class'] = $sHilightClass;	
				}
				foreach($aZList as $sAttCode)
				{
					$aRow[$sAttCode] = GetFieldAsHtml($oObj, $sAttCode);
				}
				$aValues[$oObj->GetKey()] = $aRow;
			}
			$this->Table($aAttribs, $aValues);
		}
		elseif (strlen($sEmptyListMessage) > 0)
		{
			$this->add($sEmptyListMessage);
		}
	}


	protected function DisplaySearchField($sClass, $sAttSpec, $aExtraParams, $sPrefix, $sFieldName = null)
	{
		if (is_null($sFieldName))
		{
			$sFieldName = str_replace('->', '_x_', $sAttSpec);
		}

		$iPos = strpos($sAttSpec, '->');
		if ($iPos !== false)
		{
			$sAttCode = substr($sAttSpec, 0, $iPos);
			$sSubSpec = substr($sAttSpec, $iPos + 2);

			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				throw new Exception("Invalid attribute code '$sClass/$sAttCode' in search specification '$sAttSpec'");
			}

			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef->IsLinkSet())
			{
				$sTargetClass = $oAttDef->GetLinkedClass();
			}
			elseif ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
			{
				$sTargetClass = $oAttDef->GetTargetClass(EXTKEY_ABSOLUTE);
			}
			else
			{
				throw new Exception("Attribute specification '$sAttSpec', '$sAttCode' should be either a link set or an external key");
			}
			$this->DisplaySearchField($sTargetClass, $sSubSpec, $aExtraParams, $sPrefix, $sFieldName);
		}
		else
		{
			// $sAttSpec is an attribute code
			//
			$this->add('<span style="white-space: nowrap;padding:5px;display:inline-block;">');
			$sFilterValue = '';
			$sFilterValue = utils::ReadParam($sPrefix.$sFieldName, '');
			$sFilterOpCode = null; // Use the default 'loose' OpCode
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttSpec);
			if ($oAttDef->IsExternalKey())
			{
				$sTargetClass = $oAttDef->GetTargetClass();
				$oAllowedValues = new DBObjectSet(new DBObjectSearch($sTargetClass));
		
				$iFieldSize = $oAttDef->GetMaxSize();
				$iMaxComboLength = $oAttDef->GetMaximumComboLength();
				$this->add("<label>".MetaModel::GetFilterLabel($sClass, $sAttSpec).":</label>&nbsp;");
				//$oWidget = UIExtKeyWidget::DIsplayFromAttCode($sAttSpec, $sClass, $oAttDef->GetLabel(), $oAllowedValues, $sFilterValue, $sPrefix.$sFieldName, false, '', $sPrefix, '');
				//$this->add($oWidget->Display($this, $aExtraParams, true /* bSearchMode */));
				$aExtKeyParams = $aExtraParams;
				$aExtKeyParams['iFieldSize'] = $oAttDef->GetMaxSize();
				$aExtKeyParams['iMinChars'] = $oAttDef->GetMinAutoCompleteChars();
				//	                      DisplayFromAttCode($this, $sAttCode, $sClass, $sTitle,              $oAllowedValues, $value,        $iInputId,            $bMandatory, $sFieldName = '', $sFormPrefix = '', $aArgs, $bSearchMode = false)
				$sHtml = UIExtKeyWidget::DisplayFromAttCode($this, $sAttSpec, $sClass, $oAttDef->GetLabel(), $oAllowedValues, $sFilterValue, $sPrefix.$sFieldName, false, $sPrefix.$sFieldName, $sPrefix, $aExtKeyParams, true);
				$this->add($sHtml);
			}
			else
			{
				$aAllowedValues = MetaModel::GetAllowedValues_flt($sClass, $sAttSpec, $aExtraParams);
				if (is_null($aAllowedValues))
				{
					// Any value is possible, display an input box
					$this->add("<label>".MetaModel::GetFilterLabel($sClass, $sAttSpec).":</label>&nbsp;<input class=\"textSearch\" name=\"$sPrefix$sFieldName\" value=\"$sFilterValue\"/>\n");
				}
				else
				{
					//Enum field or external key, display a combo
					$sValue = "<select name=\"$sPrefix$sFieldName\">\n";
					$sValue .= "<option value=\"\">".Dict::S('UI:SearchValue:Any')."</option>\n";
					foreach($aAllowedValues as $key => $value)
					{
						if ($sFilterValue == $key)
						{
							$sSelected = ' selected';
						}
						else
						{
							$sSelected = '';
						}
						$sValue .= "<option value=\"$key\"$sSelected>$value</option>\n";
					}
					$sValue .= "</select>\n";
					$this->add("<label>".MetaModel::GetFilterLabel($sClass, $sAttSpec).":</label>&nbsp;$sValue\n");
				}
			}				
			unset($aExtraParams[$sFieldName]);
			$this->add('</span> ');
		}
	}
	
	
	public function DisplaySearchForm($sClass, $aAttList, $aExtraParams, $sPrefix)
	{
		$this->add("<form id=\"search_$sClass\" action=\"\" method=\"post\">\n"); // Don't use $_SERVER['SCRIPT_NAME'] since the form may be called asynchronously (from ajax.php)
	//	$this->add("<h2>".Dict::Format('UI:SearchFor_Class_Objects', 'xxxxxx')."</h2>\n");
		$this->add("<p>\n");
		foreach($aAttList as $sAttSpec)
		{
			//$oAppContext->Reset($sAttSpec); // Make sure the same parameter will not be passed twice
			$this->DisplaySearchField($sClass, $sAttSpec, $aExtraParams, $sPrefix);
		}
		$this->add("</p>\n");
		$this->add("<p align=\"right\"><input type=\"submit\" value=\"".Dict::S('UI:Button:Search')."\"></p>\n");
		foreach($aExtraParams as $sName => $sValue)
		{
			$this->add("<input type=\"hidden\" name=\"$sName\" value=\"$sValue\" />\n");
		}
	//	$this->add($oAppContext->GetForForm());
		$this->add("</form>\n");
	}


	public function PostedParamsToFilter($sClass, $aAttList, $sPrefix)
	{
		$oFilter = new DBObjectSearch($sClass);
		$iCountParams = 0;
		foreach($aAttList as $sAttSpec)
		{
			$sFieldName = str_replace('->', '_x_', $sAttSpec);
			$value = utils::ReadPostedParam($sPrefix.$sFieldName, null);
			if (!is_null($value) && strlen($value) > 0)
			{
				$oFilter->AddConditionAdvanced($sAttSpec, $value);
				$iCountParams++;
			}
		}
		if ($iCountParams == 0)
		{
			return null;
		}
		else
		{
			return $oFilter;
		}
	}
}
?>
