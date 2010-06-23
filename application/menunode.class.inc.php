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
 * Persistent class menuNode
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../core/attributedef.class.inc.php');
require_once('../core/filterdef.class.inc.php');
require_once('../core/stimulus.class.inc.php');
require_once('../core/MyHelpers.class.inc.php');

require_once('../core/cmdbsource.class.inc.php');
require_once('../core/sqlquery.class.inc.php');

require_once('../core/dbobject.class.php');
require_once('../core/dbobjectsearch.class.php');
require_once('../core/dbobjectset.class.php');

require_once('../application/displayblock.class.inc.php');

/**
 * This class manages en entries in the menu tree on the left of the iTop pages
 */
class menuNode extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "gui",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_menunode",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
//		MetaModel::Init_AddAttribute(new AttributeExternalKey("change", array("allowed_values"=>null, "sql"=>"changeid", "targetclass"=>"CMDBChange", "jointype"=>"closed")));
//		MetaModel::Init_AddAttribute(new AttributeExternalField("date", array("allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"date")));
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("label", array("allowed_values"=>null, "sql"=>"label", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hyperlink", array("allowed_values"=>null, "sql"=>"hyperlink", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("icon_path", array("allowed_values"=>null, "sql"=>"icon_path", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("template", array("allowed_values"=>null, "sql"=>"template", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('application,user,administrator'), "sql"=>"type", "default_value"=>"application", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("rank", array("allowed_values"=>null, "sql"=>"rank", "default_value" => 999, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("allowed_values"=>null, "sql"=>"parent_id", "targetclass"=>"menuNode", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_name", array("allowed_values"=>null, "extkey_attcode"=>"parent_id", "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeInteger("user_id", array("allowed_values"=>null, "sql"=>"user_id", "default_value" => 0, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('parent_id', 'name', 'label', 'hyperlink', 'template', 'rank', 'type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('parent_id', 'label', 'rank', 'type')); // Attributes to be displayed for a list
	}
	
	public function IsVisible()
	{
		return true;
	}
	
	public function GetMenuName()
	{
		return Dict::S($this->Get('name'));
	}

	public function GetMenuIcon()
	{
		return $this->Get('icon_path');
	}

	public function GetMenuLabel()
	{
		return Dict::S($this->Get('label'));
	}
	
	public function GetMenuLink($aExtraParams)
	{
		$aExtraParams['menu'] = $this->GetKey(); // Make sure we overwrite the current menu id (if any)
		$aParams = array();
		foreach($aExtraParams as $sName => $sValue)
		{
			$aParams[] = urlencode($sName)."=".urlencode($sValue);
		}
		return $this->Get('hyperlink')."?".implode("&", $aParams);
	}

	public function GetChildNodesSet($sType = null)
	{
		$aParams = array();

		if ($sType == 'user')
		{
			$sSelectChilds = 'SELECT menuNode AS m WHERE m.parent_id = :parent AND type = :type AND m.user_id = :user';
			$aParams = array('parent' => $this->GetKey(), 'type' => $sType, 'user' => UserRights::GetUserId());
		}
		elseif ($sType != null)
		{
			$sSelectChilds = 'SELECT menuNode AS m WHERE m.parent_id = :parent AND type = :type';
			$aParams = array('parent' => $this->GetKey(), 'type' => $sType);
		}
		else
		{
			$sSelectChilds = 'SELECT menuNode AS m WHERE m.parent_id = :parent';
			$aParams = array('parent' => $this->GetKey());
		}
		$oSearchFilter = DBObjectSearch::FromOQL($sSelectChilds);
		$oSet = new CMDBObjectSet($oSearchFilter, array('rank' => true), $aParams);
		return $oSet;
	}

	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$sTemplate = $this->Get('template');
		$oTemplate = new DisplayTemplate($sTemplate);
		$oTemplate->Render($oPage, $aExtraParams);
		//$this->ProcessTemplate($sTemplate, $oPage, $aExtraParams);
	}
	
	public function DisplayMenu(iTopWebPage $oP, $sType, $aExtraParams, $bRootLevel = true, $iActiveNodeId = -1)
	{
		$bActive = false;
		if ($bRootLevel)
		{
			$oP->AddToMenu("<h3><a href=\"".$this->GetMenuLink($aExtraParams)."\" title=\"".$this->GetMenuLabel()."\">".$this->GetMenuName()."</a></h3>\n");
			$oP->AddToMenu("\n<div>\n");
			$oP->AddToMenu("<p><a href=\"".$this->GetMenuLink($aExtraParams)."\" title=\"".$this->GetMenuLabel()."\">".$this->GetMenuLabel()."</a></p>\n");
			$oSet = $this->GetChildNodesSet($sType);
			if ($oSet->Count() > 0)
			{
				$oP->AddToMenu("\n<ul>\n");
				while($oChildNode = $oSet->Fetch())
				{
					$bActive |= $oChildNode->DisplayMenu($oP, $sType, $aExtraParams, false /* ! RootLevel */, $iActiveNodeId);
				}
				$oP->AddToMenu("</ul>\n");
			}
			$oP->AddToMenu("\n</div>\n");
		}
		else
		{
			$oP->AddToMenu("<li><a href=\"".$this->GetMenuLink($aExtraParams)."\" title=\"".$this->GetMenuLabel()."\">".$this->GetMenuName()."</a>");
			$oSet = $this->GetChildNodesSet($sType);
			if ($oSet->Count() > 0)
			{
				$oP->AddToMenu("\n<ul>\n");
				while($oChildNode = $oSet->Fetch())
				{
					$bActive |= $oChildNode->DisplayMenu($oP, $sType, $aExtraParams, false /* ! RootLevel */, $iActiveNodeId);
				}
				$oP->AddToMenu("</ul>\n");
			}
			$oP->AddToMenu("</li>\n");
		}
		$bResult = ($iActiveNodeId == $this->GetKey()) | $bActive;
		return $bResult;
	}
	static public function DisplayCreationForm(WebPage $oP, $sClass, $sFilter, $aExtraParams = array())
	{
		$oFilter = DBObjectSearch::unserialize($sFilter);
		$oP->p('Create a new menu item for: '.$oFilter->__DescribeHTML());
		$oP->add('<form action="UniversalSearch.php" method="post">');
		$oP->add('<input type="hidden" name="operation" value="add_menu">');
		$oP->add('<input type="hidden" name="filter" value="'.$sFilter.'">');
		$oP->add('<input type="hidden" name="class" value="'.$sClass.'">');
		$oP->p('Menu Label: <input type="text" name="label" size="30">');
		$oP->p('Description: <input type="text" name="description" size="30">');
		$oP->add('<p>Insert after: <select name="previous_node_id">');
		$aNodes = self::GetMenuAsArray(null, 'user');
		foreach($aNodes as $aNodeDesc)
		{
			$oP->add('<option value="'.$aNodeDesc['id'].'">'.str_repeat('&nbsp;&nbsp;&nbsp;', $aNodeDesc['depth']).$aNodeDesc['label'].'</option>');
		}
		$oP->add('</select></p>');
		$oP->p('<input type="checkbox" name="child_item" value="1"> Create as a child menu item');
		$oP->p('<input type="submit" value=" Ok "> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="jqmClose" value="Cancel">');
		$oP->add('</form>');
	}
	
	static public function GetMenuAsArray($oRootNode = null, $sType = 'application', $iDepth = 0)
	{
		$aNodes = array();
		if (is_object($oRootNode))
		{
			$oChildSet = $oRootNode->GetChildNodesSet($sType);
			while($oNode = $oChildSet->Fetch())
			{
				$aNodes[] = array('depth' => $iDepth, 'id' => $oNode->GetKey(), 'label' => $oNode->GetName());
				$aNodes = array_merge($aNodes, self::GetMenuAsArray($oNode, $sType, $iDepth+1));
			}
		}
		else
		{
			$oSearchFilter = new DbObjectSearch("menuNode");
			$oSearchFilter->AddCondition('parent_id', 0, '=');
			if ($sType != null)
			{
				$oSearchFilter->AddCondition('type', $sType, '=');
				if ($sType == 'user')
				{
				    $oSearchFilter->AddCondition('user_id', UserRights::GetUserId(), '=');
				}
			}
			$oRootSet = new CMDBObjectSet($oSearchFilter, array('rank' => true));
			while($oNode = $oRootSet->Fetch())
			{
				$aNodes[] = array('depth' => $iDepth, 'id' => $oNode->GetKey(), 'label' => $oNode->GetName());
				$aNodes = array_merge($aNodes, self::GetMenuAsArray($oNode, $sType, $iDepth+1));
			}
		}
		return $aNodes;
	}
	/**
	 * Returns a set of all the nodes following the current node in the tree
	 * (i.e. nodes with the same parent but with a greater rank)
	 */
	public function GetNextNodesSet($sType = 'application')
	{
		$oSearchFilter = new DBObjectSearch("menuNode");
		$oSearchFilter->AddCondition('parent_id', $this->Get('parent_id'));
		$oSearchFilter->AddCondition('rank', $this->Get('rank'), '>');
		if ($sType != null)
		{
			$oSearchFilter->AddCondition('type', $sType, '=');
			if ($sType == 'user')
			{
			    $oSearchFilter->AddCondition('user_id', UserRights::GetUserId(), '=');
			}
		}
		$oSet = new DBObjectSet($oSearchFilter, array('rank'=> true)); // Order by rank (true means ascending)
		return $oSet;
	}
}
?>
