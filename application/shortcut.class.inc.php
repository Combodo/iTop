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
 * Persistent class Shortcut and derived
 * Shortcuts of any kind
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class Shortcut extends DBObject implements iDisplay
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "gui,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_shortcut",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("targetclass"=>"User", "allowed_values"=>null, "sql"=>"user_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("context", array("allowed_values"=>null, "sql"=>"context", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'context')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	abstract public function RenderContent(WebPage $oPage, $aExtraParams = array());

	protected function OnInsert()
	{
		$this->Set('user_id', UserRights::GetUserId());
	}

	public function StartRenameDialog($oPage)
	{
		$oPage->add('<div id="shortcut_rename_dlg">');

		$oForm = new DesignerForm();
		$sDefault = $this->Get('name');
		$oField = new DesignerTextField('name', Dict::S('Class:Shortcut/Attribute:name'), $sDefault);
		$oField->SetMandatory(true);
		$oForm->AddField($oField);
		$oForm->Render($oPage);
		$oPage->add('</div>');
		
		$sDialogTitle = Dict::S('UI:ShortcutRenameDlg:Title');
		$sOkButtonLabel = Dict::S('UI:Button:Ok');
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');
		$iShortcut = $this->GetKey();
		
		$oPage->add_ready_script(
<<<EOF
function ShortcutRenameOK() 
{
	var oForm = $(this).find('form');
	var sFormId = oForm.attr('id');
	var oParams = null;
	var aErrors = ValidateForm(sFormId, false);
	if (aErrors.length == 0)
	{
		oParams = ReadFormParams(sFormId);
	}
	oParams.operation = 'shortcut_rename_go';
	oParams.id = $iShortcut;
	var me = $(this);
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
		me.dialog( "close" );
		me.remove();
		$('body').append(data);
	});
}

$('#shortcut_rename_dlg form').bind('submit', function() { return false; });

$('#shortcut_rename_dlg').dialog({
	width: 400,
	modal: true,
	title: '$sDialogTitle',
	buttons: [
	{ text: "$sOkButtonLabel", click: ShortcutRenameOK},
	{ text: "$sCancelButtonLabel", click: function() {
		$(this).dialog( "close" ); $(this).remove();
	} },
	],
	close: function() { $(this).remove(); }
});
EOF
		);
	}

	// Minimual implementation of iDisplay: to make the shortcut be listable
	//
	public static function MapContextParam($sContextParam)
	{
		return (($sContextParam == 'menu') ? null : $sContextParam);
	}

	public function GetHilightClass()
	{
		return HILIGHT_CLASS_NONE;
	}

	public static function GetUIPage()
	{
		return '';
	}

	function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
	}

	function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		return array();
	}
	// End of the minimal implementation of iDisplay
}

class ShortcutOQL extends Shortcut
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "gui,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_shortcut_oql",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeOQL("oql", array("allowed_values"=>null, "sql"=>"oql", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("auto_reload", array("allowed_values"=>new ValueSetEnum('none,custom'), "sql"=>"auto_reload", "default_value"=>"none", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("auto_reload_sec", array("allowed_values"=>null, "sql"=>"auto_reload_sec", "default_value"=>60, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'context', 'oql')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$oPage->set_title($this->Get('name'));

		switch($this->Get('auto_reload'))
		{
		case 'custom':
			$iRate = (int)$this->Get('auto_reload_sec');
			if ($iRate > 0)
			{
				// Must a string otherwise it can be evaluated to 'true' and defaults to "standard" refresh rate!
				$aExtraParams['auto_reload'] = (string)$iRate;
			}
			break;

		default:
		case 'none':
		}

		$bSearchPane = true;
		$bSearchOpen = false;
		try
		{
			OQLMenuNode::RenderOQLSearch($this->Get('oql'), $this->Get('name'), 'shortcut_'.$this->GetKey(), $bSearchPane, $bSearchOpen, $oPage, $aExtraParams);
		}
		catch (Exception $e)
		{
			throw new Exception("The OQL shortcut '".$this->Get('name')."' (id: ".$this->GetKey().") could not be displayed: ".$e->getMessage());
		}
			
	}

	public function CloneTableSettings($sTableSettings)
	{
		$aTableSettings = json_decode($sTableSettings, true);

		$oFilter = DBObjectSearch::FromOQL($this->Get('oql'));
		$oCustomSettings = new DataTableSettings($oFilter->GetSelectedClasses());
		$oCustomSettings->iDefaultPageSize = $aTableSettings['iPageSize'];
		$oCustomSettings->aColumns = $aTableSettings['oColumns'];
		$oCustomSettings->Save('shortcut_'.$this->GetKey());
	}

	public static function GetCreationForm($sOQL = null, $sTableSettings = null)
	{
		$oForm = new DesignerForm();

		// Find a unique default name
		// -> The class of the query + an index if necessary
		if ($sOQL == null)
		{
			$sDefault = '';
		}
		else
		{
			$oBMSearch = new DBObjectSearch('Shortcut');
			$oBMSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
			$oBMSet = new DBObjectSet($oBMSearch);
			$aNames = $oBMSet->GetColumnAsArray('name');
	 		$oSearch = DBObjectSearch::FromOQL($sOQL);
			$sDefault = utils::MakeUniqueName($oSearch->GetClass(), $aNames);
		}

		$oField = new DesignerTextField('name', Dict::S('Class:Shortcut/Attribute:name'), $sDefault);
		$oField->SetMandatory(true);
		$oForm->AddField($oField);

		/*
		$oField = new DesignerComboField('auto_reload', Dict::S('Class:ShortcutOQL/Attribute:auto_reload'), 'none');
		$oAttDef = MetaModel::GetAttributeDef(__class__, 'auto_reload');
		$oField->SetAllowedValues($oAttDef->GetAllowedValues());
		$oField->SetMandatory(true);
		$oForm->AddField($oField);
		*/
		$oField = new DesignerBooleanField('auto_reload', Dict::S('Class:ShortcutOQL/Attribute:auto_reload'), false);
		$oForm->AddField($oField);

		$oField = new DesignerIntegerField('auto_reload_sec', Dict::S('Class:ShortcutOQL/Attribute:auto_reload_sec'), MetaModel::GetConfig()->GetStandardReloadInterval());
		$oField->SetBoundaries(MetaModel::GetConfig()->Get('min_reload_interval'), null); // no upper limit
		$oField->SetMandatory(false);
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('oql', '', $sOQL);
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('table_settings', '', $sTableSettings);
		$oForm->AddField($oField);

		return $oForm;
	}

	public static function GetCreationDlgFromOQL($oPage, $sOQL, $sTableSettings)
	{
		$oPage->add('<div id="shortcut_creation_dlg">');

		$oForm = self::GetCreationForm($sOQL, $sTableSettings);

		$oForm->Render($oPage);
		$oPage->add('</div>');
		
		$sDialogTitle = Dict::S('UI:ShortcutListDlg:Title');
		$sOkButtonLabel = Dict::S('UI:Button:Ok');
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');
		
		$oAppContext = new ApplicationContext();
		$sContext = $oAppContext->GetForLink();

		$sRateTitle = addslashes(Dict::Format('Class:ShortcutOQL/Attribute:auto_reload_sec/tip', MetaModel::GetConfig()->Get('min_reload_interval')));

		$oPage->add_ready_script(
<<<EOF

// Note: the title gets deleted by the validation mechanism
$("#attr_auto_reload_sec").tooltip({items: 'input', content: '$sRateTitle'});
$("#attr_auto_reload_sec").prop('disabled', !$('#attr_auto_reload').is(':checked'));

$('#attr_auto_reload').change( function(ev) {
	$("#attr_auto_reload_sec").prop('disabled', !$(this).is(':checked'));
} );

function ShortcutCreationOK() 
{
	var oForm = $('#shortcut_creation_dlg form');
	var sFormId = oForm.attr('id');
	var oParams = null;
	var aErrors = ValidateForm(sFormId, false);
	if (aErrors.length == 0)
	{
		oParams = ReadFormParams(sFormId);
	}
	oParams.operation = 'shortcut_list_create';
	var me = $('#shortcut_creation_dlg');
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?$sContext', oParams, function(data) {
		me.dialog( "close" );
		me.remove();
		$('body').append(data);
	});
}

$('#shortcut_creation_dlg form').bind('submit', function() { ShortcutCreationOK(); return false; });

$('#shortcut_creation_dlg').dialog({
	width: 400,
	modal: true,
	title: '$sDialogTitle',
	buttons: [
	{ text: "$sOkButtonLabel", click: ShortcutCreationOK },
	{ text: "$sCancelButtonLabel", click: function() {
		$(this).dialog( "close" ); $(this).remove();
	} },
	],
	close: function() { $(this).remove(); }
});
EOF
		);
	}
}

?>
