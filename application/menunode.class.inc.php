<?php
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
			"name" => "menuNode",
			"description" => "Main menu configuration elements",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_menunode",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
//		MetaModel::Init_AddAttribute(new AttributeExternalKey("change", array("label"=>"change", "description"=>"change", "allowed_values"=>null, "sql"=>"changeid", "targetclass"=>"CMDBChange", "jointype"=>"closed")));
//		MetaModel::Init_AddAttribute(new AttributeExternalField("date", array("label"=>"date", "description"=>"date and time of the change", "allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"date")));
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Menu Name", "description"=>"Short name for this menu", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("label", array("label"=>"Menu Description", "description"=>"Long description for this menu", "allowed_values"=>null, "sql"=>"label", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hyperlink", array("label"=>"Hyperlink", "description"=>"Hyperlink to the page", "allowed_values"=>null, "sql"=>"hyperlink", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("icon_path", array("label"=>"Menu Icon", "description"=>"Path to the icon o the menu", "allowed_values"=>null, "sql"=>"icon_path", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("template", array("label"=>"Template", "description"=>"HTML template for the view", "allowed_values"=>null, "sql"=>"template", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"Type", "description"=>"Type of menu", "allowed_values"=>new ValueSetEnum('application,user,administrator'), "sql"=>"type", "default_value"=>"application", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("rank", array("label"=>"Display rank", "description"=>"Sort order for displaying the menu", "allowed_values"=>null, "sql"=>"rank", "default_value" => 999, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("label"=>"Parent Menu Item", "description"=>"Parent Menu Item", "allowed_values"=>null, "sql"=>"parent_id", "targetclass"=>"menuNode", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_name", array("label"=>"Parent Menu Item", "description"=>"Parent Menu Item", "allowed_values"=>null, "extkey_attcode"=>"parent_id", "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeInteger("user_id", array("label"=>"Owner of the menu", "description"=>"User who owns this menu (for user defined menus)", "allowed_values"=>null, "sql"=>"user_id", "default_value" => 0, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("label");
		MetaModel::Init_AddFilterFromAttribute("parent_id");
		MetaModel::Init_AddFilterFromAttribute("rank");
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("user_id");
		
		MetaModel::Init_SetZListItems('details', array('parent_id', 'name', 'label', 'hyperlink', 'template', 'rank', 'type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('parent_id', 'name', 'label', 'rank', 'type')); // Attributes to be displayed for a list
	}
	
	public function IsVisible()
	{
		return true;
	}
	
	public function GetMenuName()
	{
		return $this->Get('name');
	}

	public function GetMenuIcon()
	{
		return $this->Get('icon_path');
	}

	public function GetMenuLabel()
	{
		return $this->Get('label');
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

	public function RenderContent(web_page $oPage, $aExtraParams = array())
	{
		$sTemplate = $this->Get('template');
		$this->ProcessTemplate($sTemplate, $oPage, $aExtraParams);
	}
	
	protected function ProcessTemplate($sTemplate, web_page $oPage, $aExtraParams = array())
	{
		$iStartPos = stripos($sTemplate, '<'.DisplayBlock::TAG_BLOCK.' ',0);
		$index = 0;
		while(($iStartPos = stripos($sTemplate, '<'.DisplayBlock::TAG_BLOCK.' ',0)) !== false)
		{
			$iEndPos = stripos($sTemplate, '</'.DisplayBlock::TAG_BLOCK.'>', $iStartPos); 
			
			$sBlockDefinition = substr($sTemplate, $iStartPos, $iEndPos - $iStartPos + strlen('</'.DisplayBlock::TAG_BLOCK.'>'));
			$oBlock = DisplayBlock::FromTemplate($sBlockDefinition);

			$oPage->add(substr($sTemplate, 0, $iStartPos));		
			if ($oBlock) // Protects agains invalid XML templates
			{
				$oBlock->Display($oPage, "block{$index}", $aExtraParams); // Values from $aExtraParams have precedence over $aParams
			}
			$index++;
			$sTemplate = substr($sTemplate, $iEndPos + strlen('</'.DisplayBlock::TAG_BLOCK.'>'));
		}
		// What remains is purely static (without any block inside), just output as it is
		$oPage->add($sTemplate);
	}
	
	public function DisplayMenu(iTopWebPage $oP, $sType, $aExtraParams)
	{
		$oP->AddToMenu("<li><a href=\"".$this->GetMenuLink($aExtraParams)."\" title=\"".$this->GetMenuLabel()."\">".$this->GetMenuName()."</a>");
		$oSet = $this->GetChildNodesSet($sType);
		if ($oSet->Count() > 0)
		{
			$oP->AddToMenu("\n<ul>\n");
			while($oChildNode = $oSet->Fetch())
			{
				$oChildNode->DisplayMenu($oP, $sType, $aExtraParams);
			}
			$oP->AddToMenu("</ul>\n");
		}
		$oP->AddToMenu("</li>\n");
	}
	static public function DisplayCreationForm(web_page $oP, $sClass, $sFilter, $aExtraParams = array())
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
