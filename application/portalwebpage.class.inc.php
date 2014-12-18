<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * Class PortalWebPage
 *
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/nicewebpage.class.inc.php");
require_once(APPROOT."/application/applicationcontext.class.inc.php");
require_once(APPROOT."/application/user.preferences.class.inc.php");

define('BUTTON_CANCEL', 1);
define('BUTTON_BACK', 2);
define('BUTTON_NEXT', 4);
define('BUTTON_FINISH', 8);

define('PARAM_ARROW_SEP', '_x_');

class TransactionException extends Exception
{
}

/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 * of the Portal web page
 */
class PortalWebPage extends NiceWebPage
{
	/**
	 * Portal menu
	 */
	protected $m_sWelcomeMsg;
	protected $m_aMenuButtons;
	
    public function __construct($sTitle, $sAlternateStyleSheet = '')
    {
    	$this->m_sWelcomeMsg = '';
    	$this->m_aMenuButtons = array();
        parent::__construct($sTitle);
		$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->add_linked_stylesheet("../css/jquery.treeview.css");
		$this->add_linked_stylesheet("../css/jquery.autocomplete.css");
		$this->add_linked_stylesheet("../css/jquery.multiselect.css");
		$sAbsURLAppRoot = addslashes(utils::GetAbsoluteUrlAppRoot()); // Pass it to Javascript scripts
		$sAbsURLModulesRoot = addslashes(utils::GetAbsoluteUrlModulesRoot()); // Pass it to Javascript scripts
		$oAppContext = new ApplicationContext();
		$sAppContext = addslashes($oAppContext->GetForLink());
		$this->add_dict_entry('UI:FillAllMandatoryFields');
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
		$this->add_linked_script("../js/jquery.qtip-1.0.min.js");
		$this->add_linked_script('../js/jquery.multiselect.min.js');
		$this->add_linked_script("../js/ajaxfileupload.js");
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

	$(".datetime-pick").datepicker({
		showOn: 'button',
		buttonImage: '../images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd 00:00:00',
		constrainInput: false,
		changeMonth: true,
		changeYear: true
		});

	//$('.resizable').resizable(); // Make resizable everything that claims to be resizable !
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
	function CheckSelection(sMessage, sInputId)
	{
		var bResult;
		if (sInputId.length > 0)
		{
			bResult = ($('input[name='+sInputId+']:checked').length > 0);
		}
		else
		{
			// First select found...
			bResult = ($('input:checked').length > 0);
		}
		if (!bResult)
		{
			alert(sMessage);
		}
		return bResult;
	}

		
	function GetAbsoluteUrlAppRoot()
	{
		return '$sAbsURLAppRoot';
	}
	
	function GetAbsoluteUrlModulesRoot()
	{
		return '$sAbsURLModulesRoot';
	}

	function AddAppContext(sURL)
	{
		var sContext = '$sAppContext';
		if (sContext.length > 0)
		{
			if (sURL.indexOf('?') == -1)
			{
				return sURL+'?'+sContext;
			}				
			return sURL+'&'+sContext;
		}
		return sURL;
	}
	
	function GoBack(sFormId)
	{
		var form = $('#'+sFormId);
		var step_back = $('input[name=step_back]');

		form.unbind('submit'); // De-activate validation

		step_back.val(1);
		form.submit(); // Go
	}

	function GoHome()
	{
		var form = $('FORM');
		form.unbind('submit'); // De-activate validation
		window.location.href = '?operation=';
		return false;
	}

	function SetWizardNextStep(sStep)
	{
		var next_step = $('input[id=next_step]');
		next_step.val(sStep);
	}
EOF
);

		// For Wizard helper to process the ajax replies
		$this->add('<div id="ajax_content"></div>');

		// Customize the logo (unless a customer CSS has been defined)
		if ($sAlternateStyleSheet == '')
		{
			if (file_exists(MODULESROOT.'branding/portal-logo.png'))
			{
				$sDisplayIcon = utils::GetAbsoluteUrlModulesRoot().'branding/portal-logo.png';
				$this->add_style("div#portal #logo {background: url(\"$sDisplayIcon\") no-repeat scroll 0 0 transparent;}");
			}
		}

}

	public function SetCurrentTab($sTabLabel = '')
	{
	}

	/**
	 * Specify a welcome message (optional)
	 */
	public function SetWelcomeMessage($sMsg)
	{
		$this->m_sWelcomeMsg = $sMsg;
	}
		 	
	
	/**
	 * Add a button to the portal's main menu
	 */
	public function AddMenuButton($sId, $sLabel, $sHyperlink)
	{
		$this->m_aMenuButtons[] = array('id' => $sId, 'label' => $sLabel, 'hyperlink' => $sHyperlink);
	}

	var $m_bEnableDisconnectButton = true;
	public function EnableDisconnectButton($bEnable)
	{
		$this->m_bEnableDisconnectButton = $bEnable;
	}
	
	public function output()
	{
		$sApplicationBanner = '';
		if (!MetaModel::DBHasAccess(ACCESS_USER_WRITE))
		{
			$sReadOnly = Dict::S('UI:AccessRO-Users');
			$sAdminMessage = trim(MetaModel::GetConfig()->Get('access_message'));
			$sApplicationBanner .= '<div id="admin-banner">';
			$sApplicationBanner .= '<img src="../images/locked.png" style="vertical-align:middle;">';
			$sApplicationBanner .= '&nbsp;<b>'.$sReadOnly.'</b>';
			if (strlen($sAdminMessage) > 0)
			{
				$sApplicationBanner .= '&nbsp;: '.$sAdminMessage.'';
			}
			$sApplicationBanner .= '</div>';
		}

		$sMenu = '';
		if ($this->m_bEnableDisconnectButton)
		{
			$this->AddMenuButton('logoff', 'Portal:Disconnect', utils::GetAbsoluteUrlAppRoot().'pages/logoff.php'); // This menu is always present and is the last one
		}
		foreach($this->m_aMenuButtons as $aMenuItem)
		{
			$sMenu .= "<a class=\"button\" id=\"{$aMenuItem['id']}\" href=\"{$aMenuItem['hyperlink']}\"><span>".Dict::S($aMenuItem['label'])."</span></a>";
		}
		$this->s_content = '<div id="portal"><div id="welcome">'.$this->m_sWelcomeMsg.'</div><div id="banner"><div id="logo"></div><div id="menu">'.$sMenu.'</div></div>'.$sApplicationBanner.'<div id="content">'.$this->s_content.'</div></div>';
		parent::output();
	}

	/**
	 * Displays a list of objects, without any hyperlink (except for the object's details)
	 * @param DBObjectSet $oSet The set of objects to display
	 * @param Array $aZList The ZList (list of field codes) to use for the tabular display
	 * @param String $sEmptyListMessage Message displayed whenever the list is empty
	 * @return string The HTML text representing the list
	 */
	 public function DisplaySet($oSet, $aZList, $sEmptyListMessage = '')
	 {
		if ($oSet->Count() > 0)
		{
			$sClass = $oSet->GetClass();
			if (is_subclass_of($sClass, 'cmdbAbstractObject'))
			{
				// Home-made and very limited display of an object set

				$sUniqueId = $sClass.$this->GetUniqueId();
				$this->add("<div id=\"$sUniqueId\">\n"); // The id here MUST be the same as currentId, otherwise the pagination will be broken
				cmdbAbstractObject::DisplaySet($this, $oSet, array('currentId' => $sUniqueId, 'menu' => false, 'toolkit_menu' => false, 'zlist' => false, 'extra_fields' => implode(',', $aZList)));
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
					
					$aRow['key'] = '<a href="./index.php?operation=details&class='.get_class($oObj).'&id='.$oObj->GetKey().'">'.$oObj->GetName().'</a>';
					$sHilightClass = $oObj->GetHilightClass();
					if ($sHilightClass != '')
					{
						$aRow['@class'] = $sHilightClass;	
					}
					foreach($aZList as $sAttCode)
					{
						$aRow[$sAttCode] = $oObj->GetAsHTML($sAttCode);
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
	public function DisplayObjectDetails($oObj, $aAttList)
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
				$sDisplayValue = $oObj->GetAsHTML($sAttCode);
				$aDetails[] = array('label' => '<span title="'.MetaModel::GetDescription($sClass, $sAttCode).'">'.MetaModel::GetLabel($sClass, $sAttCode).'</span>', 'value' => $sDisplayValue);
			}
		}
		$this->details($aDetails);
	}
	
	/**
	 * DisplayObjectLinkset
	 * @param Object $oObj Any kind of object
	 * @param $sLinkSetAttCode The attribute code of the link set attribute to display
	 * @param $sRemoteAttCode The external key on the linked class, pointing to the remote objects
	 * @param $aZList The list of attribute of the remote object
	 * @param $sEmptyListMessage The message to display if the list is empty	  
	 * @return void
	 */
	public function DisplayObjectLinkset($oObj, $sLinkSetAttCode, $sRemoteAttCode, $aZList, $sEmptyListMessage = '', $oSearchRestriction = null)
	{
		if (empty($sEmptyListMessage))
		{
			$sEmptyListMessage = Dict::S('UI:Search:NoObjectFound');
		}
	
		$oLinkSet = $oObj->Get($sLinkSetAttCode);
		if ($oLinkSet->Count() > 0)
		{
			$sClass = $oLinkSet->GetClass();
			$oExtKeyToRemote = MetaModel::GetAttributeDef($sClass, $sRemoteAttCode);
			$sRemoteClass = $oExtKeyToRemote->GetTargetClass();			
	
			if (is_null($oSearchRestriction))
			{
				$oObjSearch = new DBObjectSearch($sRemoteClass);
			}
			else
			{
				$oObjSearch = $oSearchRestriction;
			}
			$oObjSearch->AddCondition_ReferencedBy($oLinkSet->GetFilter(), $sRemoteAttCode);

			$aExtraParams = array('menu' => false, 'toolkit_menu' => false, 'zlist' => false, 'extra_fields' => implode(',', $aZList));
			$oBlock = new DisplayBlock($oObjSearch, 'list', false);
			$oBlock->Display($this, 1, $aExtraParams);
		}
		elseif (strlen($sEmptyListMessage) > 0)
		{
			$this->add($sEmptyListMessage);
		}
	}

	protected function DisplaySearchField($sClass, $sAttSpec, $aExtraParams, $sPrefix, $sFieldName = null, $aFilterParams = array())
	{
		if (is_null($sFieldName))
		{
			$sFieldName = str_replace('->', PARAM_ARROW_SEP, $sAttSpec);
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
			$this->DisplaySearchField($sTargetClass, $sSubSpec, $aExtraParams, $sPrefix, $sFieldName, $aFilterParams);
		}
		else
		{
			// $sAttSpec is an attribute code
			//
			$this->add('<span style="white-space: nowrap;padding:5px;display:inline-block;">');
			$sFilterValue = '';
			$sFilterValue = utils::ReadParam($sPrefix.$sFieldName, '', false, 'raw_data');
			$sFilterOpCode = null; // Use the default 'loose' OpCode
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttSpec);
			if ($oAttDef->IsExternalKey())
			{
				$sTargetClass = $oAttDef->GetTargetClass();
				$sFilterDefName = 'PORTAL_TICKETS_SEARCH_FILTER_'.$sAttSpec;
				if (defined($sFilterDefName))
				{
					try
					{
						$oFitlerWithParams = DBObjectSearch::FromOQL(constant($sFilterDefName));
						$sFilterOQL = $oFitlerWithParams->ToOQL(true, $aFilterParams);
						$oAllowedValues = new DBObjectSet(DBObjectSearch::FromOQL($sFilterOQL), array(), $aFilterParams);
					}
					catch(OQLException $e)
					{
						throw new Exception("Incorrect filter '$sFilterDefName' for attribute '$sAttcode': ".$e->getMessage());
					}
				}
				else
				{
					$oAllowedValues = new DBObjectSet(new DBObjectSearch($sTargetClass));
				}
		
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
					$sSanitizedValue = htmlentities($sFilterValue, ENT_QUOTES, 'UTF-8');
					$this->add("<label>".MetaModel::GetFilterLabel($sClass, $sAttSpec).":</label>&nbsp;<input class=\"textSearch\" name=\"$sPrefix$sFieldName\" value=\"$sSanitizedValue\"/>\n");
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

			$sTip = $oAttDef->GetHelpOnSmartSearch();
			if (strlen($sTip) > 0)
			{
				$sTip = addslashes($sTip);
				$sTip = str_replace(array("\n", "\r"), " ", $sTip);
				// :input does represent in form visible input (INPUT, SELECT, TEXTAREA)
				$this->add_ready_script("$(':input[name={$sPrefix}$sFieldName]').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
			}
		}
	}
	
	/**
	 * Get The organization of the current user (i.e. the organization of its contact)
	 * @throws Exception
	 */
	function GetUserOrg()
	{
		$oOrg = null;
		$iContactId = UserRights::GetContactId();
		$oContact = MetaModel::GetObject('Contact', $iContactId, false); // false => Can fail
		if (is_object($oContact))
		{
			$oOrg = MetaModel::GetObject('Organization', $oContact->Get('org_id'), false); // false => can fail
		}
		else
		{
			throw new Exception(Dict::S('Portal:ErrorNoContactForThisUser'));
		}
		return $oOrg;
	}
	
	public function DisplaySearchForm($sClass, $aAttList, $aExtraParams, $sPrefix, $bClosed = true)
	{
		$oUserOrg = $this->GetUserOrg();
		$aFilterParams = array('org_id' => $oUserOrg->GetKey(), 'contact_id' => UserRights::GetContactId());
		$sCSSClass = ($bClosed) ? 'DrawerClosed' : '';
		$this->add("<div id=\"ds_$sPrefix\" class=\"SearchDrawer $sCSSClass\">\n");
		$this->add_ready_script(
<<<EOF
		$("#dh_$sPrefix").click( function() {
		$("#ds_$sPrefix").slideToggle('normal', function() { $("#ds_$sPrefix").parent().resize(); } );
		$("#dh_$sPrefix").toggleClass('open');
	});
EOF
		);
		$this->add("<form id=\"search_$sClass\" action=\"\" method=\"post\">\n"); // Don't use $_SERVER['SCRIPT_NAME'] since the form may be called asynchronously (from ajax.php)
	//	$this->add("<h2>".Dict::Format('UI:SearchFor_Class_Objects', 'xxxxxx')."</h2>\n");
		$this->add("<p>\n");
		foreach($aAttList as $sAttSpec)
		{
			//$oAppContext->Reset($sAttSpec); // Make sure the same parameter will not be passed twice
			$this->DisplaySearchField($sClass, $sAttSpec, $aExtraParams, $sPrefix, null, $aFilterParams);
		}
		$this->add("</p>\n");
		$this->add("<p align=\"right\"><input type=\"submit\" value=\"".Dict::S('UI:Button:Search')."\"></p>\n");
		foreach($aExtraParams as $sName => $sValue)
		{
			// Note: use DumpHiddenParams() to transmit arrays as hidden params
			if (is_scalar($sValue))
			{
				$this->add("<input type=\"hidden\" name=\"$sName\" value=\"$sValue\" />\n");
			}
		}
	//	$this->add($oAppContext->GetForForm());
		$this->add("</form>\n");
 		$this->add("</div>\n");
 		$this->add("<div class=\"HRDrawer\"></div>\n");
 		$this->add("<div id=\"dh_$sPrefix\" class=\"DrawerHandle\">".Dict::S('UI:SearchToggle')."</div>\n");
	}

	/**
	 * Read parameters from the page
	 * Parameters that were absent from the page's parameters are not set in the resulting hash array
	 * @input string $sMethod Either get or post
	 * @return Hash Array of name => value corresponding to the parameters that were passed to the page
	 */
	public function ReadAllParams($sParamList, $sPrefix = 'attr_')
	{
		$aParams = explode(',', $sParamList);
		$aValues = array();
		foreach($aParams as $sName)
		{
			$sName = trim($sName);
			$value = utils::ReadParam($sPrefix.$sName, null, false, 'raw_data');
			if (!is_null($value))
			{
				$aValues[$sName] = $value;
			}
		}
		return $aValues;
	}

	/**
	 * Outputs a list of parameters as hidden fields
	 * Example: attr_dummy[-123][id] = "blah"
	 * @param Hash $aParameters Array name => value for the parameters
	 * @param Array $aExclude The list of parameters that must not be handled this way (probably already in the visible part of the form)
	 * @return void
	 */
	protected function DumpHiddenParamsInternal($sName, $value)
	{
		if (is_array($value))
		{
			foreach($value as $sKey => $item)
			{
				$this->DumpHiddenParamsInternal($sName.'['.$sKey.']', $item);
			}
		}
		else
		{
			$this->Add("<input type=\"hidden\" name=\"$sName\" value=\"$value\">");
		}
	}

	/**
	 * Outputs a list of parameters as hidden field into the current page
	 * (must be called when inside a form)
	 * @param Hash $aParameters Array name => value for the parameters
	 * @param Array $aExclude The list of parameters that must not be handled this way (probably already in the visible part of the form)
	 * @return void
	 */
	public function DumpHiddenParams($aParameters, $aExclude = null, $sPrefix = 'attr_')
	{
		foreach($aParameters as $sAttCode => $value)
		{
			if (is_null($aExclude) || !in_array($sAttCode, $aExclude))
			{
				$this->DumpHiddenParamsInternal($sPrefix.$sAttCode, $value);
			}
		}
	}

	public function PostedParamsToFilter($sClass, $aAttList, $sPrefix)
	{
		$oFilter = new DBObjectSearch($sClass);
		$iCountParams = 0;
		foreach($aAttList as $sAttSpec)
		{
			$sFieldName = str_replace('->', PARAM_ARROW_SEP, $sAttSpec);
			$value = utils::ReadPostedParam($sPrefix.$sFieldName, null, 'raw_data');
			if (!is_null($value) && (is_array($value) ? count($value)>0 : strlen($value)>0))
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

	/**
	 * Updates the object form POSTED arguments, and writes it into the DB (applies a stimuli if requested)
	 * @param DBObject $oObj The object to update
	 * $param array $aAttList If set, this will limit the list of updated attributes	 
	 * @return void
	 */
	public function DoUpdateObjectFromPostedForm(DBObject $oObj, $aAttList = null)
	{
		$sTransactionId = utils::ReadPostedParam('transaction_id', '');
		if (!utils::IsTransactionValid($sTransactionId))
		{
			throw new TransactionException();
		}
	
		$sClass = get_class($oObj);

		$sStimulus = trim(utils::ReadPostedParam('apply_stimulus', ''));
		$sTargetState = '';
		if (!empty($sStimulus))
		{
			// Compute the target state

			$aTransitions = $oObj->EnumTransitions();
			if (!isset($aTransitions[$sStimulus]))
			{
				throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus, $oObj->GetName(), $oObj->GetStateLabel()));
			}
			$sTargetState = $aTransitions[$sStimulus]['target_state'];
		}
			
		$oObj->UpdateObjectFromPostedForm('' /* form prefix */, $aAttList, $sTargetState);

		// Optional: apply a stimulus
		//
		if (!empty($sStimulus))
		{
			if (!$oObj->ApplyStimulus($sStimulus))
			{
					throw new Exception("Cannot apply stimulus '$sStimulus' to {$oObj->GetName()}");
			}
		}
		
		if ($oObj->IsModified())
		{
			// Record the change
			//
			$oObj->DBUpdate();
			
			// Trigger ?
			//
			$aClasses = MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL);
			$sClassList = implode(", ", CMDBSource::Quote($aClasses));
			$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnPortalUpdate AS t WHERE t.target_class IN ($sClassList)"));
			while ($oTrigger = $oSet->Fetch())
			{
				$oTrigger->DoActivate($oObj->ToArgs('this'));
			}
	
			$this->p("<h1>".Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName())."</h1>\n");
		}
	}

	/**
	 * Find the object of the specified Class/ID.
	 * @param WebPage $oP The current page
	 * @return DBObject The found object, or throws an exception in case of failure
	 */
	public function FindObjectFromArgs($aAllowedClasses = null)
	{
		$sClass = utils::ReadParam('class', '', true, 'class');
		$iId = utils::ReadParam('id', 0, true, 'integer');
	
		if (empty($sClass))
		{
			throw new Exception("Missing argument 'class'");
		}
		if (!MetaModel::IsValidClass($sClass))
		{
			throw new Exception("Wrong value for argument 'class': $sClass");
		}
		if ($iId == 0)
		{
			throw new Exception("Missing argument 'id'");
		}

		if(!is_null($aAllowedClasses))
		{
			$bAllowed = false;
			foreach($aAllowedClasses as $sParentClass)
			{
				if (MetaModel::IsParentClass($sParentClass, $sClass))
				{
					$bAllowed = true;
				}
			}
			if (!$bAllowed)
			{
				throw new Exception("Class '$sClass not allowed in this implementation'");
			}
		}

		$oObj = MetaModel::GetObject($sClass, $iId, false);		
		if (!is_object($oObj))
		{
			throw new Exception("Could not find the object $sClass/$iId");
		}
		return $oObj;
	}

	var $m_sWizardId = null;

	public function WizardFormStart($sId = '', $sNextStep = null, $bAttachment = false, $sMethod = 'post')
	{
		$this->m_sWizardId = $sId;

		// multipart... needed for file upload
		$this->add("<form id=\"{$this->m_sWizardId}\" method=\"$sMethod\" enctype=\"multipart/form-data\" onsubmit=\"window.bInSubmit = true;\">\n");

		$aPreviousSteps = $this->GetWizardStepHistory();
		if (utils::ReadParam('step_back', 0) == 1)
		{
			// Back into the past history
			array_pop($aPreviousSteps);
		}
		else
		{
			// Moving forward
			array_push($aPreviousSteps, utils::ReadParam('next_step'));
		}

		$sStepHistory = implode(',', $aPreviousSteps);
		$this->add("<input type=\"hidden\" id=\"step_history\" name=\"step_history\" value=\"".htmlentities($sStepHistory, ENT_QUOTES, 'UTF-8')."\">");

		if (!is_null($sNextStep))
		{		
			$this->add("<input type=\"hidden\" id=\"next_step\" name=\"next_step\" value=\"$sNextStep\">");
		}
		$this->add("<input type=\"hidden\" id=\"step_back\" name=\"step_back\" value=\"0\">");

		$sTransactionId = utils::GetNewTransactionId();
		$this->SetTransactionId($sTransactionId);
		$this->add("<input type=\"hidden\" id=\"transaction_id\" name=\"transaction_id\" value=\"$sTransactionId\">\n");
		$this->add_ready_script("$(window).unload(function() { OnUnload('$sTransactionId') } );\n");
	}

	public function WizardFormButtons($iButtonFlags)
	{
		$aButtons = array();
		if ($iButtonFlags & BUTTON_CANCEL)
		{
			$aButtons[] = "<input id=\"btn_cancel\" type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"GoHome();\">";
		}
		if ($iButtonFlags & BUTTON_BACK)
		{
			if (utils::ReadParam('step_back', 1) != 1)
			{
				$aButtons[] = "<input id=\"btn_back\" type=\"submit\" value=\"".Dict::S('UI:Button:Back')."\"  onClick=\"GoBack('{$this->m_sWizardId}');\">";
			}
		}
		if ($iButtonFlags & BUTTON_NEXT)
		{
			$aButtons[] = "<input id=\"btn_next\" type=\"submit\" value=\"".Dict::S('UI:Button:Next')."\">";
		}
		if ($iButtonFlags & BUTTON_FINISH)
		{
			$aButtons[] = "<input id=\"btn_finish\" type=\"submit\" value=\"".Dict::S('UI:Button:Finish')."\">";
		}

		$this->add('<div id="buttons">');
		$this->add(implode('', $aButtons));
		$this->add('</div>');
	}

	public function WizardFormEnd()
	{
		$this->add("</form>\n");
	}

	public function GetWizardStep()
	{
		if (utils::ReadParam('step_back', 0) == 1)
		{
			// Take the value into the history - one level above
			$aPreviousSteps = $this->GetWizardStepHistory();
			array_pop($aPreviousSteps);
			return end($aPreviousSteps);
		}
		else
		{
			return utils::ReadParam('next_step');
		}
	}

	protected function GetWizardStepHistory()
	{
		$sRawHistory = trim(utils::ReadParam('step_history', '', false, 'raw_data'));
		if (strlen($sRawHistory) == 0)
		{
			return array();
		}
		else
		{
			return explode(',', $sRawHistory);
		}
	}

	public function WizardCheckSelectionOnSubmit($sMessageIfNoSelection, $sInputName = '')
	{
		$this->add_ready_script(
<<<EOF
	$('#{$this->m_sWizardId}').submit(function() {
		return CheckSelection('$sMessageIfNoSelection', '$sInputName');
	});
EOF
		);
	}
}

?>
