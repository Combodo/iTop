<?php
// Copyright (C) 2012-2018 Combodo SARL
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

require_once(APPROOT.'application/forms.class.inc.php');

/**
 * Base class for all 'dashlets' (i.e. widgets to be inserted into a dashboard)
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
abstract class Dashlet
{
	protected $oModelReflection;
	protected $sId;
	protected $bRedrawNeeded;
	protected $bFormRedrawNeeded;
	protected $aProperties; // array of {property => value}
	protected $aCSSClasses;
	protected $sDashletType;

	/**
	 * Dashlet constructor.
	 *
	 * @param \ModelReflection $oModelReflection
	 * @param string $sId
	 */
	public function __construct(ModelReflection $oModelReflection, $sId)
	{
		$this->oModelReflection = $oModelReflection;
		$this->sId = $sId;
		$this->bRedrawNeeded = true; // By default: redraw each time a property changes
		$this->bFormRedrawNeeded = false; // By default: no need to redraw the form (independent fields)
		$this->aProperties = array(); // By default: there is no property
		$this->aCSSClasses = array('dashlet');
		$this->sDashletType = get_class($this);
	}

	/**
	 * Assuming that a property has the type of its default value, set in the constructor
	 *
	 * @param string $sProperty
	 * @param string $sValue
	 *
	 * @return mixed
	 */
	public function Str2Prop($sProperty, $sValue)
	{
		$refValue = $this->aProperties[$sProperty];
		$sRefType = gettype($refValue);
		if (gettype($sValue) == $sRefType)
		{
			// Do not change anything in that case!
			$ret = $sValue;
		}
		elseif ($sRefType == 'boolean')
		{
			$ret = ($sValue == 'true');
		}
		elseif ($sRefType == 'array')
		{
			$ret = explode(',', $sValue);
		}
		else
		{
			$ret = $sValue;
			settype($ret, $sRefType);
		}
		return $ret;
	}

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function Prop2Str($value)
	{
		$sType = gettype($value);
		if ($sType == 'boolean')
		{
			$sRet = $value ? 'true' : 'false';
		}
		elseif ($sType == 'array')
		{
			$sRet = implode(',', $value);
		}
		else
		{
			$sRet = (string) $value;
		}
		return $sRet;
	}

	protected function OnUpdate()
	{
	}

	/**
	 * @param \DOMElement $oDOMNode
	 */
	public function FromDOMNode($oDOMNode)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			$oPropNode = $oDOMNode->getElementsByTagName($sProperty)->item(0);
			if ($oPropNode != null)
			{
				$this->aProperties[$sProperty] = $this->PropertyFromDOMNode($oPropNode, $sProperty);
			}
		}
		$this->OnUpdate();
	}

	/**
	 * @param \DOMElement $oDOMNode
	 */
	public function ToDOMNode($oDOMNode)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			$oPropNode = $oDOMNode->ownerDocument->createElement($sProperty);
			$oDOMNode->appendChild($oPropNode);
			$this->PropertyToDOMNode($oPropNode, $sProperty, $value);
		}
	}

	/**
	 * @param \DOMElement $oDOMNode
	 * @param string $sProperty
	 *
	 * @return mixed
	 */
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		$res = $this->Str2Prop($sProperty, $oDOMNode->textContent);
		return $res;
	}

	/**
	 * @param \DOMElement $oDOMNode
	 * @param string $sProperty
	 * @param mixed $value
	 */
	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		$sXmlValue = $this->Prop2Str($value);
		$oTextNode = $oDOMNode->ownerDocument->createTextNode($sXmlValue);
		$oDOMNode->appendChild($oTextNode);
	}

	/**
	 * @param string $sXml
	 *
	 * @throws \DOMException
	 */
	public function FromXml($sXml)
	{
		$oDomDoc = new DOMDocument('1.0', 'UTF-8');
		libxml_clear_errors();
		$oDomDoc->loadXml($sXml);
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0)
		{
			throw new DOMException("Malformed XML");
		}

		$this->FromDOMNode($oDomDoc->firstChild);
	}

	/**
	 * @param array $aParams
	 */
	public function FromParams($aParams)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			if (array_key_exists($sProperty, $aParams))
			{
				$this->aProperties[$sProperty] = $aParams[$sProperty];
			}
		}
		$this->OnUpdate();
	}

	/**
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 * @param bool $bEnclosingDiv
	 * @param array $aExtraParams
	 */
	public function DoRender($oPage, $bEditMode = false, $bEnclosingDiv = true, $aExtraParams = array())
	{
		$sCSSClasses = implode(' ', $this->aCSSClasses);
		$sId = $this->GetID();
		if ($bEnclosingDiv)
		{
			if ($bEditMode)
			{
				$oPage->add('<div class="'.$sCSSClasses.'" id="dashlet_'.$sId.'">');
			}
			else
			{
				$oPage->add('<div class="'.$sCSSClasses.'">');
			}
		}
		else
		{
			foreach ($this->aCSSClasses as $sCSSClass)
			{
				$oPage->add_ready_script("$('#dashlet_".$sId."').addClass('$sCSSClass');");
			}
		}

		try
		{
			if (get_class($this->oModelReflection) == 'ModelReflectionRuntime')
			{
				$this->Render($oPage, $bEditMode, $aExtraParams);
			}
			else
			{
				$this->RenderNoData($oPage, $bEditMode, $aExtraParams);
			}
		}
		catch(UnknownClassOqlException $e)
		{
			// Maybe the class is part of a non-installed module, fail silently
			// Except in Edit mode
			if ($bEditMode)
			{
				$oPage->add('<div class="dashlet-content">');
				$oPage->add('<h2>'.$e->GetUserFriendlyDescription().'</h2>');
				$oPage->add('</div>');
			}
		}
		catch(OqlException $e)
		{
			$oPage->add('<div class="dashlet-content">');
			$oPage->p($e->GetUserFriendlyDescription());
			$oPage->add('</div>');
		}
		catch(Exception $e)
		{
			$oPage->add('<div class="dashlet-content">');
			$oPage->p($e->getMessage());
			$oPage->add('</div>');
		}

		if ($bEnclosingDiv)
		{
			$oPage->add('</div>');
		}

		if ($bEditMode)
		{
			$sClass = get_class($this);
			$sType = $this->sDashletType;
			$oPage->add_ready_script(
<<<EOF
$('#dashlet_$sId').dashlet({dashlet_id: '$sId', dashlet_class: '$sClass', 'dashlet_type': '$sType'});
EOF
			);
		}
	}

	/**
	 * @param string $sId
	 */
	public function SetID($sId)
	{
		$this->sId = $sId;
	}

	/**
	 * @return string
	 */
	public function GetID()
	{
		return $this->sId;
	}

	/**
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 *
	 * @return mixed
	 */
	abstract public function Render($oPage, $bEditMode = false, $aExtraParams = array());

	/**
	 * Rendering without the real data
	 *
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$this->Render($oPage, $bEditMode, $aExtraParams);
	}

	/**
	 * @param \DesignerForm $oForm
	 *
	 * @return mixed
	 */
	abstract public function GetPropertiesFields(\DesignerForm $oForm);

	/**
	 * @param \DOMNode $oContainerNode
	 */
	public function ToXml(DOMNode $oContainerNode)
	{

	}

	/**
	 * @param array $aValues
	 * @param array $aUpdatedFields
	 *
	 * @return \Dashlet
	 */
	public function Update($aValues, $aUpdatedFields)
	{
		foreach($aUpdatedFields as $sProp)
		{
			if (array_key_exists($sProp, $this->aProperties))
			{
				$this->aProperties[$sProp] = $this->Str2Prop($sProp, $aValues[$sProp]);
			}
		}
		$this->OnUpdate();
		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsRedrawNeeded()
	{
		return $this->bRedrawNeeded;
	}

	/**
	 * @return bool
	 */
	public function IsFormRedrawNeeded()
	{
		return $this->bFormRedrawNeeded;
	}

	/**
	 * @return array
	 */
	static public function GetInfo()
	{
		return array(
			'label' => '',
			'icon' => '',
			'description' => '',
		);
	}

	/**
	 * @param array $aInfo
	 *
	 * @return \DesignerForm
	 */
	public function GetForm($aInfo = array())
	{
		$oForm = new DesignerForm();
		$sPrefix = "dashlet_".$this->GetID();
		$oForm->SetPrefix($sPrefix);
		$oForm->SetHierarchyPath($sPrefix);
		$oForm->SetParamsContainer('params');

		$this->GetPropertiesFields($oForm);

		$oDashletClassField = new DesignerHiddenField('dashlet_class', '', get_class($this));
		$oForm->AddField($oDashletClassField);

		$oDashletTypeField = new DesignerHiddenField('dashlet_type', '', $this->sDashletType);
		$oForm->AddField($oDashletTypeField);

		$oDashletIdField = new DesignerHiddenField('dashlet_id', '', $this->GetID());
		$oForm->AddField($oDashletIdField);

		return $oForm;
	}

	/**
	 * @return bool
	 */
	static public function IsVisible()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	static public function CanCreateFromOQL()
	{
		return false;
	}

	/**
	 * @param \DesignerForm $oForm
	 * @param string|null $sOQL
	 */
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL = null)
	{
		// Default: do nothing since it's not supported
	}

	/**
	 * @param string $sOql
	 *
	 * @return array
	 */
	protected function GetGroupByOptions($sOql)
	{
		$aGroupBy = array();
		try
		{
			$oQuery = $this->oModelReflection->GetQuery($sOql);
			$sClass = $oQuery->GetClass();
			foreach($this->oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType)
			{
				// For external fields, find the real type of the target
				$sTargetClass = $sClass;
				while (is_a($sAttType, 'AttributeExternalField', true))
				{
					$sExtKeyAttCode = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sAttCode, 'extkey_attcode');
					$sTargetAttCode = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sAttCode, 'target_attcode');
					$sTargetClass = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sExtKeyAttCode, 'targetclass');
					$aTargetAttCodes = $this->oModelReflection->ListAttributes($sTargetClass);
					$sAttType = $aTargetAttCodes[$sTargetAttCode];
				}
				if (is_a($sAttType, 'AttributeLinkedSet', true))
				{
					continue;
				}
				if (is_a($sAttType, 'AttributeFriendlyName', true))
				{
					continue;
				}
				if (is_a($sAttType, 'AttributeOneWayPassword', true))
				{
					continue;
				}

				$sLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
				if (!in_array($sLabel, $aGroupBy))
				{
					$aGroupBy[$sAttCode] = $sLabel;

					if (is_a($sAttType, 'AttributeDateTime', true))
					{
						$aGroupBy[$sAttCode.':hour'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Hour', $sLabel);
						$aGroupBy[$sAttCode.':month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Month', $sLabel);
						$aGroupBy[$sAttCode.':day_of_week'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek', $sLabel);
						$aGroupBy[$sAttCode.':day_of_month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth', $sLabel);
					}
				}
			}
			asort($aGroupBy);
		}
		catch (Exception $e)
		{
			// Fallback in case of OQL problem
		}
		return $aGroupBy;
	}

	/**
	 * @return string
	 */
	public function GetDashletType()
	{
		return $this->sDashletType;
	}

	/**
	 * @param string $sDashletType
	 */
	public function SetDashletType($sDashletType)
	{
		$this->sDashletType = $sDashletType;
	}
}

/**
 * Class DashletUnknown
 *
 * Used as a fallback in iTop for unknown dashlet classes.
 *
 * @since 2.5
 */
class DashletUnknown extends Dashlet
{
	static protected $aClassList = null;

	protected $sOriginalDashletXML;

	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->sOriginalDashletXML = '';
		$this->aCSSClasses[] = 'dashlet-unknown';
	}

	/**
	 * @inheritdoc
	 */
	public function FromDOMNode($oDOMNode)
	{
		// Parent won't do anything as there is no property declared
		parent::FromDOMNode($oDOMNode);

		// Build properties from XML
        $this->sOriginalDashletXML = "";
		foreach($oDOMNode->childNodes as $oDOMChildNode)
		{
			if($oDOMChildNode instanceof DOMElement)
			{
				$sProperty = $oDOMChildNode->tagName;

				// For all properties but "rank" as it is handle by the dashboard.
				if($sProperty !== 'rank')
				{
					// We need to initialize the property before setting it, otherwise it will guessed as NULL and not used.
					$this->aProperties[$sProperty] = '';
					$this->aProperties[$sProperty] = $this->PropertyFromDOMNode($oDOMChildNode, $sProperty);

					// And build the original XML
                    $this->sOriginalDashletXML .= $oDOMChildNode->ownerDocument->saveXML($oDOMChildNode)."\n";
				}
			}
		}

		$this->OnUpdate();
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 * @throws \DOMFormatException
	 */
	public function ToDOMNode($oDOMNode)
	{
		$oDoc = new DOMDocument();
		libxml_clear_errors();
		$oDoc->loadXML('<root>'.$this->sOriginalDashletXML.'</root>');
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0)
		{
			throw new DOMFormatException('Dashlet definition not correctly formatted!');
		}
		foreach($oDoc->documentElement->childNodes as $oDOMChildNode)
		{
			$oPropNode = $oDOMNode->ownerDocument->importNode($oDOMChildNode, true);
			$oDOMNode->appendChild($oPropNode);
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \DOMException
	 */
	public function FromParams($aParams)
    {
        // For unknown dashlet, parameters are not parsed but passed as a raw xml
        if(array_key_exists('xml', $aParams))
        {
            // A namespace must be present for the "xsi:type" attribute, otherwise a warning will be thrown.
            $sXML = '<dashlet id="'.$aParams['dashlet_id'].'" xsi:type="'.$aParams['dashlet_type'].'" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'.$aParams['xml'].'</dashlet>';
            $this->FromXml($sXML);
        }
        $this->OnUpdate();
    }

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$aInfos = static::GetInfo();

		$sIconUrl = utils::GetAbsoluteUrlAppRoot().$aInfos['icon'];
		$sExplainText = ($bEditMode) ? Dict::Format('UI:DashletUnknown:RenderText:Edit', $this->GetDashletType()) : Dict::S('UI:DashletUnknown:RenderText:View');

		$oPage->add('<div class="dashlet-content">');

		$oPage->add('<div class="dashlet-ukn-image"><img src="'.utils::HtmlEntities($sIconUrl).'" /></div>');
		$oPage->add('<div class="dashlet-ukn-text">'.$sExplainText.'</div>');

		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$aInfos = static::GetInfo();

		$sIconUrl = utils::GetAbsoluteUrlAppRoot().$aInfos['icon'];
		$sExplainText = Dict::Format('UI:DashletUnknown:RenderNoDataText:Edit', $this->GetDashletType());

		$oPage->add('<div class="dashlet-content">');

		$oPage->add('<div class="dashlet-ukn-image"><img src="'.utils::HtmlEntities($sIconUrl).'" /></div>');
		$oPage->add('<div class="dashlet-ukn-text">'.$sExplainText.'</div>');

		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function GetForm($aInfo = array())
	{
		if (isset($aInfo['configuration']) && empty($this->sOriginalDashletXML))
		{
			$this->sOriginalDashletXML = $aInfo['configuration'];
		}
		return parent::GetForm($aInfo);
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerLongTextField('xml', Dict::S('UI:DashletUnknown:Prop-XMLConfiguration'), $this->sOriginalDashletXML);
		$oForm->AddField($oField);
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		$bHasSubProperties = false;
		foreach($oDOMNode->childNodes as $oDOMChildNode)
		{
			if($oDOMChildNode->nodeType === XML_ELEMENT_NODE)
			{
				$bHasSubProperties = true;
				break;
			}
		}

		if($bHasSubProperties)
		{
			$sTmp = $oDOMNode->ownerDocument->saveXML($oDOMNode, LIBXML_NOENT);
			$sTmp = trim(preg_replace("/(<".$oDOMNode->tagName."[^>]*>|<\/".$oDOMNode->tagName.">)/", "", $sTmp));
			return $sTmp;
		}
		else
		{
			return parent::PropertyFromDOMNode($oDOMNode, $sProperty);
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		// Save subnodes
		if(preg_match('/<(.*)>/', $value))
		{
			/** @var \DOMDocumentFragment $oDOMFragment */
			$oDOMFragment = $oDOMNode->ownerDocument->createDocumentFragment();
			$oDOMFragment->appendXML($value);
			$oDOMNode->appendChild($oDOMFragment);
		}
		else
		{
			parent::PropertyToDOMNode($oDOMNode, $sProperty, $value);
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \DOMException
	 */
	public function Update($aValues, $aUpdatedFields)
    {
        $this->FromParams($aValues);
        // OnUpdate() already done in FromParams()
        return $this;
    }

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletUnknown:Label'),
			'icon' => 'images/dashlet-unknown.png',
			'description' => Dict::S('UI:DashletUnknown:Description'),
		);
	}
}

class DashletProxy extends DashletUnknown
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);

		// Remove DashletUnknown class
        if( ($key = array_search('dashlet-unknown', $this->aCSSClasses)) !== false )
        {
            unset($this->aCSSClasses[$key]);
        }

		$this->aCSSClasses[] = 'dashlet-proxy';
	}

	/**
	 * @inheritdoc
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		// This should never be called.
		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div>This dashlet is not supposed to be rendered as it is just a proxy for third-party widgets.</div>');
		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$aInfos = static::GetInfo();

		$sIconUrl = utils::GetAbsoluteUrlAppRoot().$aInfos['icon'];
		$sExplainText = Dict::Format('UI:DashletProxy:RenderNoDataText:Edit', $this->GetDashletType());

		$oPage->add('<div class="dashlet-content">');

		$oPage->add('<div class="dashlet-pxy-image"><img src="'.utils::HtmlEntities($sIconUrl).'" /></div>');
		$oPage->add('<div class="dashlet-pxy-text">'.$sExplainText.'</div>');

		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletProxy:Label'),
			'icon' => 'images/dashlet-proxy.png',
			'description' => Dict::S('UI:DashletProxy:Description'),
		);
	}
}

class DashletEmptyCell extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
	}

	/**
	 * @inheritdoc
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('&nbsp;');
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => 'Empty Cell',
			'icon' => 'images/dashlet-text.png',
			'description' => 'Empty Cell Dashlet Placeholder',
		);
	}

	/**
	 * @inheritdoc
	 */
	static public function IsVisible()
	{
		return false;
	}
}

class DashletPlainText extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['text'] = Dict::S('UI:DashletPlainText:Prop-Text:Default');
	}

	/**
	 * @inheritdoc
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sText = htmlentities($this->aProperties['text'], ENT_QUOTES, 'UTF-8');
		$sText = str_replace(array("\r\n", "\n", "\r"), "<br/>", $sText);

		$sId = 'plaintext_'.($bEditMode? 'edit_' : '').$this->sId;
		$oPage->add('<div id="'.$sId.'" class="dashlet-content">'.$sText.'</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerLongTextField('text', Dict::S('UI:DashletPlainText:Prop-Text'), $this->aProperties['text']);
		$oField->SetMandatory();
		$oForm->AddField($oField);
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletPlainText:Label'),
			'icon' => 'images/dashlet-text.png',
			'description' => Dict::S('UI:DashletPlainText:Description'),
		);
	}
}

class DashletObjectList extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = '';
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['menu'] = false;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \OQLException
	 * @throws \CoreException
	 * @throws \ArchivedObjectException
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$sShowMenu = $this->aProperties['menu'] ? '1' : '0';

		$oPage->add('<div class="dashlet-content">');
		$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
		if ($sHtmlTitle != '')
		{
			$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
		}
		if (isset($aExtraParams['query_params']))
		{
			$aQueryParams = $aExtraParams['query_params'];
		}
		elseif (isset($aExtraParams['this->class']) && isset($aExtraParams['this->id']))
		{
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aQueryParams = $oObj->ToArgsForQuery();
		}
		else
		{
			$aQueryParams = array();
		}
		$oFilter = DBObjectSearch::FromOQL($sQuery, $aQueryParams);
		$oBlock = new DisplayBlock($oFilter, 'list');
		$aParams = array(
			'menu' => $sShowMenu,
			'table_id' => 'Dashlet'.$this->sId,
		);
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occurring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, array_merge($aExtraParams, $aParams));
		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$bShowMenu = $this->aProperties['menu'];

		$oPage->add('<div class="dashlet-content">');
		$sHtmlTitle = htmlentities($this->oModelReflection->DictString($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
		if ($sHtmlTitle != '')
		{
			$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
		}
		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();
		$oPage->add('<div id="block_fake_'.$this->sId.'" class="display_block">');
		$oPage->p(Dict::S('UI:NoObjectToDisplay'));
		if ($bShowMenu)
		{
			$oPage->p('<a>'.Dict::Format('UI:ClickToCreateNew', $this->oModelReflection->GetName($sClass)).'</a>');
		}
		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletObjectList:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletObjectList:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', Dict::S('UI:DashletObjectList:Prop-Menu'), $this->aProperties['menu']);
		$oForm->AddField($oField);
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletObjectList:Label'),
			'icon' => 'images/dashlet-list.png',
			'description' => Dict::S('UI:DashletObjectList:Description'),
		);
	}

	/**
	 * @inheritdoc
	 */
	static public function CanCreateFromOQL()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL = null)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletObjectList:Prop-Title'), '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', Dict::S('UI:DashletObjectList:Prop-Query'), $sOQL);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', Dict::S('UI:DashletObjectList:Prop-Menu'), $this->aProperties['menu']);
		$oForm->AddField($oField);
	}
}

abstract class DashletGroupBy extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = '';
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'status';
		$this->aProperties['style'] = 'table';
		$this->aProperties['aggregation_function'] = 'count';
		$this->aProperties['aggregation_attribute'] = '';
		$this->aProperties['limit'] = '';
		$this->aProperties['order_by'] = '';
		$this->aProperties['order_direction'] = '';
	}

	protected $sGroupByLabel = null;
	protected $sGroupByExpr = null;
	protected $sGroupByAttCode = null;
	protected $sFunction = null;
	protected $sAggregationFunction = null;
	protected $sAggregationAttribute = null;
	protected $sLimit = null;
	protected $sOrderBy = null;
	protected $sOrderDirection = null;
	protected $sClass = null;

	/**
	 * Compute Grouping
	 *
	 * @inheritdoc
	 */
	public function OnUpdate()
	{
		$this->sGroupByExpr = null;
		$this->sGroupByLabel = null;
		$this->sGroupByAttCode = null;
		$this->sFunction = null;
		$this->sClass = null;

		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$this->sAggregationFunction = $this->aProperties['aggregation_function'];
		$this->sAggregationAttribute = $this->aProperties['aggregation_attribute'];

		$this->sLimit = $this->aProperties['limit'];
		$this->sOrderBy = $this->aProperties['order_by'];
		if (empty($this->sOrderBy))
		{
			if ($this->aProperties['style'] == 'pie')
			{
				$this->sOrderBy = 'function';
			}
			else
			{
				$this->sOrderBy = 'attribute';
			}
		}

		// First perform the query - if the OQL is not ok, it will generate an exception : no need to go further
		try
		{
			$oQuery = $this->oModelReflection->GetQuery($sQuery);
			$this->sClass = $oQuery->GetClass();
			$sClassAlias = $oQuery->GetClassAlias();
		}
		catch(Exception $e)
		{
			// Invalid query, let the user edit the dashlet/dashboard anyhow
			$this->sClass = null;
			$sClassAlias = '';
		}

		// Check groupby... it can be wrong at this stage
		if (preg_match('/^(.*):(.*)$/', $sGroupBy, $aMatches))
		{
			$this->sGroupByAttCode = $aMatches[1];
			$this->sFunction = $aMatches[2];
		}
		else
		{
			$this->sGroupByAttCode = $sGroupBy;
			$this->sFunction = null;
		}

		if (empty($this->aProperties['order_direction']))
		{
			$aAttributeTypes = $this->oModelReflection->ListAttributes($this->sClass);
			if (isset($aAttributeTypes[$this->sGroupByAttCode]))
			{
				$sAttributeType = $aAttributeTypes[$this->sGroupByAttCode];
				if (is_subclass_of($sAttributeType, 'AttributeDateTime') || $sAttributeType == 'AttributeDateTime')
				{
					$this->sOrderDirection = 'asc';
				}
				else
				{
					$this->sOrderDirection = 'desc';
				}
			}
		}
		else
		{
			$this->sOrderDirection = $this->aProperties['order_direction'];
		}

		if ((!is_null($this->sClass)) && $this->oModelReflection->IsValidAttCode($this->sClass, $this->sGroupByAttCode))
		{
			$sAttLabel = $this->oModelReflection->GetLabel($this->sClass, $this->sGroupByAttCode);
			if (!is_null($this->sFunction))
			{
				switch($this->sFunction)
				{
					case 'hour':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Hour', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%H')"; // 0 -> 23
						break;

					case 'month':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Month', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%Y-%m')"; // yyyy-mm
						break;

					case 'day_of_week':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfWeek', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%w')";
						break;

					case 'day_of_month':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfMonth', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%Y-%m-%d')"; // mm-dd
						break;

					default:
						$this->sGroupByLabel = 'Unknown group by function '.$this->sFunction;
						$this->sGroupByExpr = $sClassAlias.'.'.$this->sGroupByAttCode;
				}
			}
			else
			{
				$this->sGroupByExpr = $sClassAlias.'.'.$this->sGroupByAttCode;
				$this->sGroupByLabel = $sAttLabel;
			}
		}
		else
		{
			$this->sGroupByAttCode = null;
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \CoreException
	 * @throws \ArchivedObjectException
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$sStyle = $this->aProperties['style'];

		// First perform the query - if the OQL is not ok, it will generate an exception : no need to go further
		if (isset($aExtraParams['query_params']))
		{
			$aQueryParams = $aExtraParams['query_params'];
		}
		elseif (isset($aExtraParams['this->class']) && isset($aExtraParams['this->id']))
		{
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aQueryParams = $oObj->ToArgsForQuery();
		}
		else
		{
			$aQueryParams = array();
		}
		$oFilter = DBObjectSearch::FromOQL($sQuery, $aQueryParams);
		$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());

		$sClass = $oFilter->GetClass();
		if (!$this->oModelReflection->IsValidAttCode($sClass, $this->sGroupByAttCode))
		{
			$oPage->add('<p>'.Dict::S('UI:DashletGroupBy:MissingGroupBy').'</p>');
		}
		else
		{
			switch($sStyle)
			{
				case 'bars':
					$sType = 'chart';
					$aParams = array(
						'chart_type' => 'bars',
						'chart_title' => $sTitle,
						'group_by' => $this->sGroupByExpr,
						'group_by_label' => $this->sGroupByLabel,
						'aggregation_function' => $this->sAggregationFunction,
						'aggregation_attribute' => $this->sAggregationAttribute,
						'limit' => $this->sLimit,
						'order_direction' => $this->sOrderDirection,
						'order_by' => $this->sOrderBy,
					);
					$sHtmlTitle = ''; // done in the itop block
					break;

				case 'pie':
					$sType = 'chart';
					$aParams = array(
						'chart_type' => 'pie',
						'chart_title' => $sTitle,
						'group_by' => $this->sGroupByExpr,
						'group_by_label' => $this->sGroupByLabel,
						'aggregation_function' => $this->sAggregationFunction,
						'aggregation_attribute' => $this->sAggregationAttribute,
						'limit' => $this->sLimit,
						'order_direction' => $this->sOrderDirection,
						'order_by' => $this->sOrderBy,
					);
					$sHtmlTitle = ''; // done in the itop block
					break;

				case 'table':
				default:
					$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
					$sType = 'count';
					$aParams = array(
						'group_by' => $this->sGroupByExpr,
						'group_by_label' => $this->sGroupByLabel,
						'aggregation_function' => $this->sAggregationFunction,
						'aggregation_attribute' => $this->sAggregationAttribute,
						'limit' => $this->sLimit,
						'order_direction' => $this->sOrderDirection,
						'order_by' => $this->sOrderBy,
					);
					break;
			}

			$oPage->add('<div style="text-align:center" class="dashlet-content">');
			if ($sHtmlTitle != '')
			{
				$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
			}
			$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
			$oBlock = new DisplayBlock($oFilter, $sType);
			$oBlock->Display($oPage, $sBlockId, array_merge($aExtraParams, $aParams));
			if($bEditMode)
			{
				$oPage->add('<div class="dashlet-blocker"></div>');
			}
			$oPage->add('</div>');
		}
	}

	/**
	 * @return array
	 */
	protected function MakeSimulatedData()
	{
		$sQuery = $this->aProperties['query'];

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

		$aDisplayValues = array();
		if ($this->oModelReflection->IsValidAttCode($sClass, $this->sGroupByAttCode))
		{
			$aAttributeTypes = $this->oModelReflection->ListAttributes($sClass);
			$sAttributeType = $aAttributeTypes[$this->sGroupByAttCode];
			if (is_subclass_of($sAttributeType, 'AttributeDateTime') || $sAttributeType == 'AttributeDateTime')
			{
				// Note: an alternative to this somewhat hardcoded way of doing things would be to implement...
				//$oExpr = Expression::FromOQL($this->sGroupByExpr);
				//$aTranslationData = array($oQuery->GetClassAlias() => array($this->sGroupByAttCode => new ScalarExpression(date('Y-m-d H:i:s', $iTime))));
				//$sRawValue = CMDBSource::QueryToScalar('SELECT '.$oExpr->Translate($aTranslationData)->Render());
				//$sValueLabel = $oExpr->MakeValueLabel(oFilter, $sRawValue, $sRawValue);
				// Anyhow, this requires :
				// - an update to the prototype of MakeValueLabel() so that it takes ModelReflection parameters
				// - propose clever date/times samples

				$aValues = array();
				switch($this->sFunction)
				{
					case 'hour':
						$aValues = array(8, 9, 15, 18);
						break;

					case 'month':
						$aValues = array('2013 '.Dict::S('Month-11'), '2013 '.Dict::S('Month-12'), '2014 '.Dict::S('Month-01'), '2014 '.Dict::S('Month-02'), '2014 '.Dict::S('Month-03'));
						break;

					case 'day_of_week':
						$aValues = array(Dict::S('DayOfWeek-Monday'), Dict::S('DayOfWeek-Wednesday'), Dict::S('DayOfWeek-Thursday'), Dict::S('DayOfWeek-Friday'));
						break;

					case 'day_of_month':
						$aValues = array(Dict::S('Month-03'). ' 30', Dict::S('Month-03'). ' 31', Dict::S('Month-04'). ' 01', Dict::S('Month-04'). ' 02', Dict::S('Month-04'). ' 03');
						break;
				}
				foreach ($aValues as $sValue)
				{
					$aDisplayValues[] = array('label' => $sValue, 'value' => (int)rand(1, 15));
				}
			}
			elseif (is_subclass_of($sAttributeType, 'AttributeEnum') || $sAttributeType == 'AttributeEnum')
			{
				$aAllowed = $this->oModelReflection->GetAllowedValues_att($sClass, $this->sGroupByAttCode);
				if ($aAllowed) // null for non enums
				{
					foreach ($aAllowed as $sValue => $sValueLabel)
					{
						$iCount = (int) rand(2, 100);
						$aDisplayValues[] = array(
							'label' => $sValueLabel,
							'value' => $iCount
						);
					}
				}
			}
			else
			{
				$aDisplayValues[] = array('label' => 'a', 'value' => 123);
				$aDisplayValues[] = array('label' => 'b', 'value' => 321);
				$aDisplayValues[] = array('label' => 'c', 'value' => 456);
			}
		}
		return $aDisplayValues;
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('<div class="dashlet-content">');
		$oPage->add('error!');
		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletGroupBy:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletGroupBy:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		try
		{
			// Group by field: build the list of possible values (attribute codes + ...)
			$aGroupBy = $this->GetGroupByOptions($this->aProperties['query']);

			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aGroupBy);
		}
		catch(Exception $e)
		{
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetReadOnly();
			$aGroupBy = array();
		}
		$oForm->AddField($oField);

		$aStyles = array(
			'pie' => Dict::S('UI:DashletGroupByPie:Label'),
			'bars' => Dict::S('UI:DashletGroupByBars:Label'),
			'table' => Dict::S('UI:DashletGroupByTable:Label'),
		);

		$oField = new DesignerComboField('style', Dict::S('UI:DashletGroupBy:Prop-Style'), $this->aProperties['style']);
		$oField->SetMandatory();
		$oField->SetAllowedValues($aStyles);
		$oForm->AddField($oField);

		$aFunctionAttributes = $this->GetNumericAttributes($this->aProperties['query']);
		$aFunctions = $this->GetAllowedFunctions($aFunctionAttributes);
		$oSelectorField = new DesignerFormSelectorField('aggregation_function', Dict::S('UI:DashletGroupBy:Prop-Function'), $this->aProperties['aggregation_function']);
		$oForm->AddField($oSelectorField);
		$oSelectorField->SetMandatory();
		// Count sub-menu
		$oSubForm = new DesignerForm();
		$oSelectorField->AddSubForm($oSubForm, Dict::S('UI:GroupBy:count'), 'count');
		foreach($aFunctions as $sFct => $sLabel)
		{
			$oSubForm = new DesignerForm();
			$oField = new DesignerComboField('aggregation_attribute', Dict::S('UI:DashletGroupBy:Prop-FunctionAttribute'), $this->aProperties['aggregation_attribute']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aFunctionAttributes);
			$oSubForm->AddField($oField);
			$oSelectorField->AddSubForm($oSubForm, $sLabel, $sFct);
		}

		$aOrderField = array();

		if (isset($this->aProperties['group_by']) && isset($aGroupBy[$this->aProperties['group_by']]))
		{
			$aOrderField['attribute'] = $aGroupBy[$this->aProperties['group_by']];
		}

		if ($this->aProperties['aggregation_function'] == 'count')
		{
			$aOrderField['function'] = Dict::S('UI:GroupBy:count');
		}
		else
		{
			$aOrderField['function'] = $aFunctions[$this->aProperties['aggregation_function']];
		}
		$oSelectorField = new DesignerFormSelectorField('order_by', Dict::S('UI:DashletGroupBy:Prop-OrderField'), $this->aProperties['order_by']);
		$oForm->AddField($oSelectorField);
		$oSelectorField->SetMandatory();
		foreach($aOrderField as $sField => $sLabel)
		{
			$oSubForm = new DesignerForm();
			if ($sField == 'function')
			{
				$oField = new DesignerIntegerField('limit', Dict::S('UI:DashletGroupBy:Prop-Limit'), $this->aProperties['limit']);
				$oSubForm->AddField($oField);
			}
			$oSelectorField->AddSubForm($oSubForm, $sLabel, $sField);
		}

		$aOrderDirections = array(
			'asc' => Dict::S('UI:DashletGroupBy:Order:asc'),
			'desc' => Dict::S('UI:DashletGroupBy:Order:desc'),
			);
		$sOrderDirection = empty($this->aProperties['order_direction']) ? $this->sOrderDirection : $this->aProperties['order_direction'];
		$oField = new DesignerComboField('order_direction', Dict::S('UI:DashletGroupBy:Prop-OrderDirection'), $sOrderDirection);
		$oField->SetMandatory();
		$oField->SetAllowedValues($aOrderDirections);
		$oForm->AddField($oField);

	}

	/**
	 * @return array
	 */
	protected function GetOrderBy()
	{
		if (is_null($this->sClass))
		{
			return array();
		}
		return array(
			$this->aProperties['group_by'] => $this->oModelReflection->GetLabel($this->sClass, $this->aProperties['group_by']),
			'_itop_'.$this->aProperties['aggregation_function'].'_' => Dict::S('UI:GroupBy:'.$this->aProperties['aggregation_function']));
	}

	/**
	 * @param array $aFunctionAttributes
	 *
	 * @return array
	 */
	protected function GetAllowedFunctions($aFunctionAttributes)
	{
		$aFunctions = array();

		if (!empty($aFunctionAttributes) || is_null($this->sClass))
		{
			$aFunctions['sum'] = Dict::S('UI:GroupBy:sum');
			$aFunctions['avg'] = Dict::S('UI:GroupBy:avg');
			$aFunctions['min'] = Dict::S('UI:GroupBy:min');
			$aFunctions['max'] = Dict::S('UI:GroupBy:max');
		}

		return $aFunctions;
	}

	/**
	 * @param string $sOql
	 *
	 * @return array
	 */
	protected function GetNumericAttributes($sOql)
	{
		$aFunctionAttributes = array();
		try
		{
			$oQuery = $this->oModelReflection->GetQuery($sOql);
			$sClass = $oQuery->GetClass();
			if (is_null($sClass))
			{
				return $aFunctionAttributes;
			}
			foreach($this->oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType)
			{
				switch ($sAttType)
				{
					case 'AttributeDecimal':
					case 'AttributeDuration':
					case 'AttributeInteger':
					case 'AttributePercentage':
					case 'AttributeSubItem': // TODO: Known limitation: no unit displayed (values in sec)
						$sLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
						$aFunctionAttributes[$sAttCode] = $sLabel;
						break;
				}
			}
		}
		catch (Exception $e)
		{
			// In case the OQL is bad
		}

		return $aFunctionAttributes;
	}

	/**
	 * @inheritdoc
	 */
	public function Update($aValues, $aUpdatedFields)
	{
		if (in_array('query', $aUpdatedFields))
		{
			try
			{
				$sCurrQuery = $aValues['query'];
				$oCurrSearch = $this->oModelReflection->GetQuery($sCurrQuery);
				$sCurrClass = $oCurrSearch->GetClass();

				$sPrevQuery = $this->aProperties['query'];
				$oPrevSearch = $this->oModelReflection->GetQuery($sPrevQuery);
				$sPrevClass = $oPrevSearch->GetClass();

				if ($sCurrClass != $sPrevClass)
				{
					$this->bFormRedrawNeeded = true;
					// wrong but not necessary - unset($aUpdatedFields['group_by']);
					$this->aProperties['group_by'] = '';
				}
			}
			catch(Exception $e)
			{
				$this->bFormRedrawNeeded = true;
			}
		}
		$oDashlet = parent::Update($aValues, $aUpdatedFields);

		if (in_array('style', $aUpdatedFields))
		{
			switch($aValues['style'])
			{
				// Style changed, mutate to the specified type of chart
				case 'pie':
					$oDashlet = new DashletGroupByPie($this->oModelReflection, $this->sId);
					break;

				case 'bars':
					$oDashlet = new DashletGroupByBars($this->oModelReflection, $this->sId);
					break;

				case 'table':
					$oDashlet = new DashletGroupByTable($this->oModelReflection, $this->sId);
					break;
			}
			$oDashlet->FromParams($aValues);
			$oDashlet->bRedrawNeeded = true;
			$oDashlet->bFormRedrawNeeded = true;
		}
		if (in_array('aggregation_attribute', $aUpdatedFields) || in_array('order_direction', $aUpdatedFields) || in_array('order_by', $aUpdatedFields) || in_array('limit', $aUpdatedFields))
		{
			$oDashlet->bRedrawNeeded = true;
		}
		if (in_array('group_by', $aUpdatedFields) || in_array('aggregation_function', $aUpdatedFields))
		{
			$oDashlet->bRedrawNeeded = true;
			$oDashlet->bFormRedrawNeeded = true;
		}
		return $oDashlet;
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		// Note: no need to translate, should never be visible to the end-user!
		return array(
			'label' => 'Objects grouped by...',
			'icon' => 'images/dashlet-object-grouped.png',
			'description' => 'Grouped objects dashlet (abstract)',
		);
	}

	/**
	 * @inheritdoc
	 */
	static public function CanCreateFromOQL()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL = null)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletGroupBy:Prop-Title'), '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', Dict::S('UI:DashletGroupBy:Prop-Query'), $sOQL);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		if (!is_null($sOQL))
		{
			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), null);
			$aGroupBy = $this->GetGroupByOptions($sOQL);
			$oField->SetAllowedValues($aGroupBy);
		}
		else
		{
			// Creating a form for reading parameters!
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), null);
		}
		$oField->SetMandatory();

		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('style', '', $this->aProperties['style']);
		$oField->SetMandatory();
		$oForm->AddField($oField);
	}
}

class DashletGroupByPie extends DashletGroupBy
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'pie';
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletGroupByPie:Label'),
			'icon' => 'images/dashlet-pie-chart.png',
			'description' => Dict::S('UI:DashletGroupByPie:Description'),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$HTMLsTitle = ($sTitle != '') ? '<h1 style="text-align:center">'.htmlentities($sTitle, ENT_QUOTES, 'UTF-8').'</h1>' : '';
		$oPage->add("<div style=\"background-color:#fff;padding:0.25em;\">$HTMLsTitle<div id=\"$sBlockId\" style=\"background-color:#fff;\"></div></div>");

		$aDisplayValues = $this->MakeSimulatedData();

		$aColumns = array();
		$aNames = array();
		foreach($aDisplayValues as $idx => $aValue)
		{
			$aColumns[] = array('series_'.$idx, (int)$aValue['value']);
			$aNames['series_'.$idx] = $aValue['label'];
		}
		$sJSColumns = json_encode($aColumns);
		$sJSNames = json_encode($aNames);
		$oPage->add_ready_script(
<<<EOF
window.setTimeout(function() {
var chart = c3.generate({
    bindto: '#{$sBlockId}',
    data: {
    	columns: $sJSColumns,
      	type: 'pie',
		names: $sJSNames,
    },
    legend: {
      show: true,
	  position: 'right',
    },
	tooltip: {
	  format: {
	    value: function (value, ratio, id) { return value; }
	  }
	}
});}, 100);
EOF
		);
	}
}


class DashletGroupByBars extends DashletGroupBy
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'bars';
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletGroupByBars:Label'),
			'icon' => 'images/dashlet-bar-chart.png',
			'description' => Dict::S('UI:DashletGroupByBars:Description'),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$HTMLsTitle = ($sTitle != '') ? '<h1 style="text-align:center">'.htmlentities($sTitle, ENT_QUOTES, 'UTF-8').'</h1>' : '';
		$oPage->add("<div style=\"background-color:#fff;padding:0.25em;\">$HTMLsTitle<div id=\"$sBlockId\" style=\"background-color:#fff;\"></div></div>");

		$aDisplayValues = $this->MakeSimulatedData();

		$aNames = array();
		foreach($aDisplayValues as $idx => $aValue)
		{
			$aNames[$idx] = $aValue['label'];
		}
		$sJSNames = json_encode($aNames);

		$sJson = json_encode($aDisplayValues);
		$oPage->add_ready_script(
<<<EOF
window.setTimeout(function() {
	var chart = c3.generate({
    bindto: '#{$sBlockId}',
    data: {
   	  json: $sJson,
      keys: {
      	x: 'label',
      	value: ["value"]
	  },
	  selection: {
		enabled: true
	  },
      type: 'bar'
    },
    axis: {
        x: {
			tick: {
				culling: {max: 25}, // Maximum 24 labels on x axis (2 years).
				centered: true,
				rotate: 90,
				multiline: false
			},
            type: 'category'   // this needed to load string x value
        }
    },
	grid: {
		y: {
			show: true
		}
	},
    legend: {
      show: false,
    },
	tooltip: {
	  grouped: false,
	  format: {
		title: function() { return '' },
	    name: function (name, ratio, id, index) {
			var aNames = $sJSNames;
			return aNames[index];
		}
	  }
	}
});
}, 100);
EOF
		);
	}
}

class DashletGroupByTable extends DashletGroupBy
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'table';
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletGroupByTable:Label'),
			'description' => Dict::S('UI:DashletGroupByTable:Description'),
			'icon' => 'images/dashlet-groupby-table.png',
		);
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{

		$aDisplayValues = $this->MakeSimulatedData();
		$iTotal = 0;
		foreach($aDisplayValues as $iRow => $aDisplayData)
		{
			$iTotal += $aDisplayData['value'];
		}

		$oPage->add('<div class="dashlet-content">');

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$oPage->add('<div id="'.$sBlockId.'" class="display_block">');
		$oPage->add('<p>'.Dict::Format('UI:Pagination:HeaderNoSelection', $iTotal).'</p>');
		$oPage->add('<table class="listResults">');
		$oPage->add('<thead>');
		$oPage->add('<tr>');
		$oPage->add('<th class="header" title="">'.$this->sGroupByLabel.'</th>');
		$oPage->add('<th class="header" title="'.Dict::S('UI:GroupBy:Count+').'">'.Dict::S('UI:GroupBy:Count').'</th>');
		$oPage->add('</tr>');
		$oPage->add('</thead>');
		$oPage->add('<tbody>');
		foreach($aDisplayValues as $aDisplayData)
		{
			$oPage->add('<tr class="even">');
			$oPage->add('<td class=""><span title="Active">'.$aDisplayData['label'].'</span></td>');
			$oPage->add('<td class=""><a>'.$aDisplayData['value'].'</a></td>');
			$oPage->add('</tr>');
		}
		$oPage->add('</tbody>');
		$oPage->add('</table>');
		$oPage->add('</div>');

		$oPage->add('</div>');
	}
}


class DashletHeaderStatic extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderStatic:Prop-Title:Default');
		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$this->aProperties['icon'] = $oIconSelect->GetDefaultValue('Contact');
	}

	/**
	 * @inheritdoc
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = $oIconSelect->MakeFileUrl($sIcon);

		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div class="main_header">');

		$oPage->add('<img src="'.utils::HtmlEntities($sIconPath).'">');
		$oPage->add('<h1>'.$this->oModelReflection->DictString($sTitle).'</h1>');

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderStatic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = $this->oModelReflection->GetIconSelectionField('icon', Dict::S('UI:DashletHeaderStatic:Prop-Icon'), $this->aProperties['icon']);
		$oForm->AddField($oField);
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			return $oIconField->ValueFromDOMNode($oDOMNode);
		}
		else
		{
			return parent::PropertyFromDOMNode($oDOMNode, $sProperty);
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			$oIconField->ValueToDOMNode($oDOMNode, $value);
		}
		else
		{
			parent::PropertyToDOMNode($oDOMNode, $sProperty, $value);
		}
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletHeaderStatic:Label'),
			'icon' => 'images/dashlet-header.png',
			'description' => Dict::S('UI:DashletHeaderStatic:Description'),
		);
	}
}


class DashletHeaderDynamic extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderDynamic:Prop-Title:Default');
		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$this->aProperties['icon'] = $oIconSelect->GetDefaultValue('Contact');
		$this->aProperties['subtitle'] = Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle:Default');
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'status';
		$this->aProperties['values'] = array('active', 'inactive');
	}

	/**
	 * @return array
	 */
	protected function GetValues()
	{
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$aValues = $this->aProperties['values'];

		if (empty($aValues))
		{
			$aValues = array();
		}

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

		if ($this->oModelReflection->IsValidAttCode($sClass, $sGroupBy))
		{
			if (count($aValues) == 0)
			{
				$aAllowed = $this->oModelReflection->GetAllowedValues_att($sClass, $sGroupBy);
				if (is_array($aAllowed))
				{
					$aValues = array_keys($aAllowed);
				}
			}
		}
		return $aValues;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \CoreException
	 * @throws \ArchivedObjectException
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];
		$sSubtitle = $this->aProperties['subtitle'];
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = $oIconSelect->MakeFileUrl($sIcon);

		$aValues = $this->GetValues();
		if (count($aValues) > 0)
		{
			// Stats grouped by <group_by>
			$sCSV = implode(',', $aValues);
			$aParams = array(
				'title[block]' => $sTitle,
				'label[block]' => $sSubtitle,
				'status[block]' => $sGroupBy,
				'status_codes[block]' => $sCSV,
				'context_filter' => 1,
			);
		}
		else
		{
			// Simple stats
			$aParams = array(
				'title[block]' => $sTitle,
				'label[block]' => $sSubtitle,
				'context_filter' => 1,
			);
		}

		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div class="main_header">');

		$oPage->add('<img src="'.utils::HtmlEntities($sIconPath).'">');

		if (isset($aExtraParams['query_params']))
		{
			$aQueryParams = $aExtraParams['query_params'];
		}
		elseif (isset($aExtraParams['this->class']))
		{
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aQueryParams = $oObj->ToArgsForQuery();
		}
		else
		{
			$aQueryParams = array();
		}
		$oFilter = DBObjectSearch::FromOQL($sQuery, $aQueryParams);
		$oBlock = new DisplayBlock($oFilter, 'summary');
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, array_merge($aExtraParams, $aParams));

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];
		$sSubtitle = $this->aProperties['subtitle'];
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = $oIconSelect->MakeFileUrl($sIcon);

		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div class="main_header">');

		$oPage->add('<img src="'.utils::HtmlEntities($sIconPath).'">');

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$iTotal = 0;
		$aValues = $this->GetValues();

		$oPage->add('<div class="display_block" id="'.$sBlockId.'">');
		$oPage->add('<div class="summary-details">');
		$oPage->add('<table><tbody>');
		$oPage->add('<tr>');
		foreach ($aValues as $sValue)
		{
			$sValueLabel = $this->oModelReflection->GetValueLabel($sClass, $sGroupBy, $sValue);
			$oPage->add('	<th>'.$sValueLabel.'</th>');
		}
		$oPage->add('</tr>');
		$oPage->add('<tr>');
		foreach ($aValues as $sValue)
		{
			$iCount = (int) rand(2, 100);
			$iTotal += $iCount;
			$oPage->add('	<td>'.$iCount.'</td>');
		}
		$oPage->add('</tr>');
		$oPage->add('</tbody></table>');
		$oPage->add('</div>');

		$sTitle = $this->oModelReflection->DictString($sTitle);
		$sSubtitle = $this->oModelReflection->DictFormat($sSubtitle, $iTotal);

		$oPage->add('<h1>'.$sTitle.'</h1>');
		$oPage->add('<a class="summary">'.$sSubtitle.'</a>');
		$oPage->add('</div>');

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderDynamic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = $this->oModelReflection->GetIconSelectionField('icon', Dict::S('UI:DashletHeaderDynamic:Prop-Icon'), $this->aProperties['icon']);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('subtitle', Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle'), $this->aProperties['subtitle']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletHeaderDynamic:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		try
		{
			// Group by field: build the list of possible values (attribute codes + ...)
			$oQuery = $this->oModelReflection->GetQuery($this->aProperties['query']);
			$sClass = $oQuery->GetClass();
			$aGroupBy = $this->GetGroupByOptions($this->aProperties['query']);
			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletHeaderDynamic:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aGroupBy);
		}
		catch(Exception $e)
		{
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletHeaderDynamic:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetReadOnly();
		}
		$oForm->AddField($oField);

		$oField = new DesignerComboField('values', Dict::S('UI:DashletHeaderDynamic:Prop-Values'), $this->aProperties['values']);
		$oField->MultipleSelection(true);
		if (isset($sClass) && $this->oModelReflection->IsValidAttCode($sClass, $this->aProperties['group_by']))
		{
			$aValues = $this->oModelReflection->GetAllowedValues_att($sClass, $this->aProperties['group_by']);
			$oField->SetAllowedValues($aValues);
		}
		else
		{
			$oField->SetReadOnly();
		}
		$oForm->AddField($oField);
	}

	/**
	 * @inheritdoc
	 */
	public function Update($aValues, $aUpdatedFields)
	{
		if (in_array('query', $aUpdatedFields))
		{
			try
			{
				$sCurrQuery = $aValues['query'];
				$oCurrSearch = $this->oModelReflection->GetQuery($sCurrQuery);
				$sCurrClass = $oCurrSearch->GetClass();

				$sPrevQuery = $this->aProperties['query'];
				$oPrevSearch = $this->oModelReflection->GetQuery($sPrevQuery);
				$sPrevClass = $oPrevSearch->GetClass();

				if ($sCurrClass != $sPrevClass)
				{
					$this->bFormRedrawNeeded = true;
					// wrong but not necessary - unset($aUpdatedFields['group_by']);
					$this->aProperties['group_by'] = '';
					$this->aProperties['values'] = array();
				}
			}
			catch(Exception $e)
			{
				$this->bFormRedrawNeeded = true;
			}
		}
		if (in_array('group_by', $aUpdatedFields))
		{
			$this->bFormRedrawNeeded = true;
			$this->aProperties['values'] = array();
		}
		return parent::Update($aValues, $aUpdatedFields);
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			return $oIconField->ValueFromDOMNode($oDOMNode);
		}
		else
		{
			return parent::PropertyFromDOMNode($oDOMNode, $sProperty);
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			$oIconField->ValueToDOMNode($oDOMNode, $value);
		}
		else
		{
			parent::PropertyToDOMNode($oDOMNode, $sProperty, $value);
		}
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletHeaderDynamic:Label'),
			'icon' => 'images/dashlet-header-stats.png',
			'description' => Dict::S('UI:DashletHeaderDynamic:Description'),
		);
	}
}


class DashletBadge extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['class'] = 'Contact';
		$this->aCSSClasses[] = 'dashlet-inline';
		$this->aCSSClasses[] = 'dashlet-badge';
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sClass = $this->aProperties['class'];

		$oPage->add('<div class="dashlet-content">');

		$oFilter = new DBObjectSearch($sClass);
		$oBlock = new DisplayBlock($oFilter, 'actions');
		$aExtraParams['context_filter'] = 1;
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occurring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, $aExtraParams);

		$oPage->add('</div>');
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sClass = $this->aProperties['class'];

		$sIconUrl = $this->oModelReflection->GetClassIcon($sClass, false);
		$sClassLabel = $this->oModelReflection->GetName($sClass);

		$oPage->add('<div class="dashlet-content">');

		$oPage->add('<div id="block_fake_'.$this->sId.'" class="display_block">');
		$oPage->add('<p>');
		$oPage->add('   <a class="actions"><img src="'.utils::HtmlEntities($sIconUrl).'" style="vertical-align:middle;float;left;margin-right:10px;border:0;">'.$sClassLabel.': 947</a>');
		$oPage->add('</p>');
		$oPage->add('<p>');
		$oPage->add('   <a>'.Dict::Format('UI:ClickToCreateNew', $sClassLabel).'</a>');
		$oPage->add('   <br/>');
		$oPage->add('   <a>'.Dict::Format('UI:SearchFor_Class', $sClassLabel).'</a>');
		$oPage->add('</p>');
		$oPage->add('</div>');

		$oPage->add('</div>');
	}

	static protected $aClassList = null;

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		if (is_null(self::$aClassList))
		{
			// Cache the ordered list of classes (ordered on the label)
			// (has a significant impact when editing a page with lots of badges)
			//
			$aClasses = array();
			foreach($this->oModelReflection->GetClasses('bizmodel', true /*exclude links*/) as $sClass)
			{
				$aClasses[$sClass] = $this->oModelReflection->GetName($sClass);
			}
			asort($aClasses);

			self::$aClassList = array();
			foreach($aClasses as $sClass => $sLabel)
			{
				$sIconUrl = $this->oModelReflection->GetClassIcon($sClass, false);
				if ($sIconUrl == '')
				{
					// The icon does not exist, let's use a transparent one of the same size.
					$sIconUrl = utils::GetAbsoluteUrlAppRoot().'images/transparent_32_32.png';
				}
				self::$aClassList[] = array('value' => $sClass, 'label' => $sLabel, 'icon' => $sIconUrl);
			}
		}

		$oField = new DesignerIconSelectionField('class', Dict::S('UI:DashletBadge:Prop-Class'), $this->aProperties['class']);
		$oField->SetAllowedValues(self::$aClassList);

		$oForm->AddField($oField);
	}

	/**
	 * @inheritdoc
	 */
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletBadge:Label'),
			'icon' => 'images/dashlet-badge.png',
			'description' => Dict::S('UI:DashletBadge:Description'),
		);
	}
}
