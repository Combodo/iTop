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
 * Class UIExtKeyWidget
 * UI wdiget for displaying and editing external keys when
 * A simple drop-down list is not enough...
 * 
 * The layout is the following
 * 
 * +-- #label_<id> (input)-------+  +-----------+
 * |                             |  | Browse... |
 * +-----------------------------+  +-----------+
 * 
 * And the popup dialog has the following layout:
 * 
 * +------------------- ac_dlg_<id> (div)-----------+
 * + +--- ds_<id> (div)---------------------------+ |
 * | | +------------- fs_<id> (form)------------+ | |
 * | | | +--------+---+                         | | |
 * | | | | Class  | V |                         | | |
 * | | | +--------+---+                         | | |
 * | | |                                        | | |
 * | | |    S e a r c h   F o r m               | | |
 * | | |                           +--------+   | | |
 * | | |                           | Search |   | | |
 * | | |                           +--------+   | | |
 * | | +----------------------------------------+ | |
 * | +--------------+-dh_<id>-+--------------------+ |
 * |                \ Search /                      |
 * |                 +------+                       |
 * | +--- fr_<id> (form)--------------------------+ |
 * | | +------------ dr_<id> (div)--------------+ | |
 * | | |                                        | | |
 * | | |      S e a r c h  R e s u l t s        | | |
 * | | |                                        | | |
 * | | +----------------------------------------+ | |
 * | |   +--------+    +-----+                    | |
 * | |   | Cancel |    | Add |                    | |
 * | |   +--------+    +-----+                    | |
 * | +--------------------------------------------+ |
 * +------------------------------------------------+
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/displayblock.class.inc.php');

class UIExtKeyWidget 
{
	protected static $iWidgetIndex = 0;
	protected $sAttCode;
	protected $sNameSuffix;
	protected $iId;
	protected $sTitle;

	protected $oAllowedValues = null;
	
	public function __construct($sAttCode, $sClass, $sTitle, $oAllowedValues, $value, $iInputId, $bMandatory, $sNameSuffix = '', $sFieldPrefix = '', $sFormPrefix = '')
	{
		self::$iWidgetIndex++;
		$this->sAttCode = $sAttCode;
		$this->sClass = $sClass;
		$this->oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$this->sNameSuffix = $sNameSuffix;
		$this->iId = $iInputId;
		$this->oAllowedValues = $oAllowedValues;
		$this->value = $value;
		$this->sFieldPrefix = $sFieldPrefix;
		$this->sTargetClass = $this->oAttDef->GetTargetClass();
		$this->sTitle = $sTitle;
		$this->sFormPrefix = $sFormPrefix;
		$this->bMandatory = $bMandatory;
	}
	
	/**
	 * Get the HTML fragment corresponding to the linkset editing widget
	 * @param WebPage $oP The web page used for all the output
	 * @param Hash $aArgs Extra context arguments
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function Display(WebPage $oPage, $aArgs = array(), $bSearchMode = false)
	{
		$oPage->add_linked_script('../js/extkeywidget.js');
		$oPage->add_linked_script('../js/forms-json-utils.js');
		
		$bCreate = (!$bSearchMode) && (!MetaModel::IsAbstract($this->sTargetClass)) && (UserRights::IsActionAllowed($this->sTargetClass, UR_ACTION_BULK_MODIFY) && $this->oAttDef->AllowTargetCreation());
		$sMessage = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$sAttrFieldPrefix = ($bSearchMode) ? '' : 'attr_';

		$sHTMLValue = "<span style=\"white-space:nowrap\">"; // no wrap
		if($bSearchMode)
		{
			$sWizHelper = 'null';
		} 
		else
		{
			$sWizHelper = 'oWizardHelper'.$this->sFormPrefix;
		}
		if (is_null($this->oAllowedValues))
		{
			throw new Exception('Implementation: null value for allowed values definition');
		}
		elseif ($this->oAllowedValues->Count() < $this->oAttDef->GetMaximumComboLength())
		{
			// Few choices, use a normal 'select'
			$sSelectMode = 'true';
			
			$sHelpText = $this->oAttDef->GetHelpOnEdition();

			// In case there are no valid values, the select will be empty, thus blocking the user from validating the form
			$sHTMLValue = "<select title=\"$sHelpText\" name=\"{$sAttrFieldPrefix}{$this->sFieldPrefix}{$this->sAttCode}{$this->sNameSuffix}\" id=\"$this->iId\">\n";
			if ($bSearchMode)
			{
				$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SearchValue:Any')."</option>\n";			
			}
			else
			{
				$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>\n";
			}
			$this->oAllowedValues->Rewind();
			while($oObj = $this->oAllowedValues->Fetch())
			{
				$key = $oObj->GetKey();
				$display_value = $oObj->Get('friendlyname');

				if (($this->oAllowedValues->Count() == 1) && ($this->bMandatory == 'true') )
				{
					// When there is only once choice, select it by default
					$sSelected = ' selected';
				}
				else
				{
					$sSelected = ($this->value == $key) ? ' selected' : '';
				}
				$sHTMLValue .= "<option value=\"$key\"$sSelected>$display_value</option>\n";
			}
			$sHTMLValue .= "</select>\n";
			$oPage->add_ready_script(
<<<EOF
		oACWidget_{$this->iId} = new ExtKeyWidget('{$this->iId}', '{$this->sClass}', '{$this->sAttCode}', '{$this->sNameSuffix}', $sSelectMode, $sWizHelper);
		oACWidget_{$this->iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
		$('#$this->iId').bind('update', function() { oACWidget_{$this->iId}.Update(); } );

EOF
);
		}
		else
		{
			// Too many choices, use an autocomplete
			$sSelectMode = 'false';
		
			if ($this->oAttDef->IsNull($this->value)) // Null values are displayed as ''
			{
				$sDisplayValue = '';
			}
			else
			{
				$sDisplayValue = $this->GetObjectName($this->value);
			}
			$sFormPrefix = $this->sFormPrefix;
			$iMinChars = $this->oAttDef->GetMinAutoCompleteChars();
			$iFieldSize = $this->oAttDef->GetMaxSize();
	
			// the input for the auto-complete
			$sHTMLValue = "<input count=\"".$this->oAllowedValues->Count()."\" type=\"text\" id=\"label_$this->iId\" size=\"30\" maxlength=\"$iFieldSize\" value=\"$sDisplayValue\"/>&nbsp;";
			$sHTMLValue .= "<a class=\"no-arrow\" href=\"javascript:oACWidget_{$this->iId}.Search();\"><img id=\"mini_search_{$this->iId}\" style=\"border:0;vertical-align:middle;\" src=\"../images/mini_search.gif\" /></a>&nbsp;";
	
			// another hidden input to store & pass the object's Id
			$sHTMLValue .= "<input type=\"hidden\" id=\"$this->iId\" name=\"{$sAttrFieldPrefix}{$this->sFieldPrefix}{$this->sAttCode}{$this->sNameSuffix}\" value=\"$this->value\" />\n";
	
			// Scripts to start the autocomplete and bind some events to it
			$oPage->add_ready_script(
<<<EOF
		oACWidget_{$this->iId} = new ExtKeyWidget('{$this->iId}', '{$this->sClass}', '{$this->sAttCode}', '{$this->sNameSuffix}', $sSelectMode, $sWizHelper);
		oACWidget_{$this->iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>$sMessage</p></div>";
		$('#label_$this->iId').autocomplete('./ajax.render.php', { scroll:true, minChars:{$iMinChars}, formatItem:formatItem, autoFill:false, matchContains:true, keyHolder:'#{$this->iId}', extraParams:{operation:'autocomplete', sclass:'{$this->sClass}',attCode:'{$this->sAttCode}'}});
		$('#label_$this->iId').blur(function() { $(this).search(); } );
		$('#label_$this->iId').keyup(function() { if ($(this).val() == '') { $('#$this->iId').val(''); } } ); // Useful for search forms: empty value in the "label", means no value, immediatly !
		$('#label_$this->iId').result( function(event, data, formatted) { OnAutoComplete('{$this->iId}', event, data, formatted); } );
		$('#$this->iId').bind('update', function() { oACWidget_{$this->iId}.Update(); } );
EOF
);
			//$oPage->add_at_the_end($this->GetSearchDialog($oPage)); // To prevent adding forms inside the main form
			$oPage->add_at_the_end('<div id="ac_dlg_'.$this->iId.'"></div>'); // The place where to download the search dialog is outside of the main form (to prevent nested forms)

		}
		if ($bCreate)
		{
			$sHTMLValue .= "<a class=\"no-arrow\" href=\"javascript:oACWidget_{$this->iId}.CreateObject();\"><img id=\"mini_add_{$this->iId}\" style=\"border:0;vertical-align:middle;\" src=\"../images/mini_add.gif\" /></a>&nbsp;";
			$oPage->add_at_the_end('<div id="ajax_'.$this->iId.'"></div>');
		}
		$sHTMLValue .= "<span id=\"v_{$this->iId}\"></span>";
		$sHTMLValue .= "</span>"; // end of no wrap
		return $sHTMLValue;
	}
	
	public function GetSearchDialog(WebPage $oPage)
	{
		$sHTML = '<div class="wizContainer" style="vertical-align:top;"><div id="dc_'.$this->iId.'">';

		$oFilter = new DBObjectSearch($this->sTargetClass);
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$sHTML .= $oBlock->GetDisplay($oPage, $this->iId, array('open' => true, 'currentId' => $this->iId));
		$sHTML .= "<form id=\"fr_{$this->iId}\" OnSubmit=\"return oACWidget_{$this->iId}.DoOk();\">\n";
		$sHTML .= "<div id=\"dr_{$this->iId}\" style=\"vertical-align:top;background: #fff;height:100%;overflow:auto;padding:0;border:0;\">\n";
		$sHTML .= "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n";
		$sHTML .= "</div>\n";
		$sHTML .= "<input type=\"button\" id=\"btn_cancel_{$this->iId}\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#ac_dlg_{$this->iId}').dialog('close');\">&nbsp;&nbsp;";
		$sHTML .= "<input type=\"button\" id=\"btn_ok_{$this->iId}\" value=\"".Dict::S('UI:Button:Ok')."\"  onClick=\"oACWidget_{$this->iId}.DoOk();\">";
		$sHTML .= "</form>\n";
		$sHTML .= '</div></div>';

		$sDialogTitle = addslashes($this->sTitle);
		$oPage->add_ready_script(
<<<EOF
		$('#ac_dlg_{$this->iId}').dialog({ width: $(window).width()*0.8, height: $(window).height()*0.8, autoOpen: false, modal: true, title: '$sDialogTitle', resizeStop: oACWidget_{$this->iId}.UpdateSizes, close: oACWidget_{$this->iId}.OnClose });
		$('#fs_{$this->iId}').bind('submit.uiAutocomplete', oACWidget_{$this->iId}.DoSearchObjects);
		$('#dc_{$this->iId}').resize(oACWidget_{$this->iId}.UpdateSizes);
EOF
);
		$oPage->add($sHTML);
	}

	/**
	 * Search for objects to be selected
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of m_sRemoteClass
	 * @param Array $aAlreadyLinkedIds List of IDs of objects of "remote" class already linked, to be filtered out of the search
	 */
	public function SearchObjectsToSelect(WebPage $oP, $sTargetClass = '')
	{
		if (is_null($this->oAllowedValues))
		{
			throw new Exception('Implementation: null value for allowed values definition');
		}
		$oBlock = new DisplayBlock($this->oAllowedValues->GetFilter(), 'list', false);
		$oBlock->Display($oP, $this->iId, array('menu' => false, 'selection_mode' => true, 'selection_type' => 'single', 'display_limit' => false)); // Don't display the 'Actions' menu on the results
	}
	
	/**
	 * Get the display name of the selected object, to fill back the autocomplete
	 */
	public function GetObjectName($iObjId)
	{
		$oObj = MetaModel::GetObject($this->sTargetClass, $iObjId);
		return $oObj->GetName();
	}
	
	/**
	 * Get the form to create a new object of the 'target' class
	 */
	public function GetObjectCreationForm(WebPage $oPage)
	{
		$sDialogTitle = addslashes($this->sTitle);
		$oPage->add('<div id="ac_create_'.$this->iId.'"><div class="wizContainer" style="vertical-align:top;"><div id="dcr_'.$this->iId.'">');
		$oPage->add("<h1>".MetaModel::GetClassIcon($this->sTargetClass)."&nbsp;".Dict::Format('UI:CreationTitle_Class', MetaModel::GetName($this->sTargetClass))."</h1>\n");
	 	cmdbAbstractObject::DisplayCreationForm($oPage, $this->sTargetClass, null, array(), array('formPrefix' => $this->iId, 'noRelations' => true));	
		$oPage->add('</div></div></div>');
//		$oPage->add_ready_script("\$('#ac_create_$this->iId').dialog({ width: $(window).width()*0.8, height: 'auto', autoOpen: false, modal: true, title: '$sDialogTitle'});\n");
		$oPage->add_ready_script("\$('#ac_create_$this->iId').dialog({ width: 'auto', height: 'auto', autoOpen: false, modal: true, title: '$sDialogTitle'});\n");
		$oPage->add_ready_script("$('#dcr_{$this->iId} form').removeAttr('onsubmit');");
		$oPage->add_ready_script("$('#dcr_{$this->iId} form').bind('submit.uilinksWizard', oACWidget_{$this->iId}.DoCreateObject);");
	}

	/**
	 * Get the form to create a new object of the 'target' class
	 */
	public function DoCreateObject($oPage)
	{
		$oObj = MetaModel::NewObject($this->sTargetClass);
		$aErrors = $oObj->UpdateObject($this->sFormPrefix.$this->iId);
		if (count($aErrors) == 0)
		{
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$sUserString = CMDBChange::GetCurrentUserName();
			$oMyChange->Set("userinfo", $sUserString);
			$iChangeId = $oMyChange->DBInsert();
			$oObj->DBInsertTracked($oMyChange);
			return array('name' => $oObj->GetName(), 'id' => $oObj->GetKey());
		}
		else
		{
			return array('name' => implode(' ', $aErrors), 'id' => 0);		
		}
	}
}
?>
