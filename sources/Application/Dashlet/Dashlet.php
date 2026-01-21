<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Dashlet;

use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\DesignDocument;
use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\Serializer\XMLNormalizer;
use DesignerForm;
use DesignerHiddenField;
use Dict;
use DOMException;
use DOMNode;
use Exception;
use ModelReflection;
use OQLException;
use UnknownClassOqlException;
use utils;

/**
 * Base class for all 'dashlets' (i.e. widgets to be inserted into a dashboard)
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
abstract class Dashlet
{
	/** @var string */
	public const APP_USER_PREFERENCES_PREFIX = 'Dashlet';

	protected $oModelReflection;
	protected $sId;
	protected $bRedrawNeeded;
	protected $bFormRedrawNeeded;
	protected $aProperties; // array of {property => value}
	protected $aCSSClasses;
	protected $sDashletType;
	protected array $aDefinition;

	/**
	 * Dashlet constructor.
	 *
	 * @param \ModelReflection $oModelReflection
	 * @param string $sId
	 * @param string|null $sDashletType
	 */
	public function __construct(ModelReflection $oModelReflection, $sId)
	{
		$this->oModelReflection = $oModelReflection;
		$this->sId = $sId;
		$this->bRedrawNeeded = true; // By default: redraw each time a property changes
		$this->bFormRedrawNeeded = false; // By default: no need to redraw the form (independent fields)
		$this->aProperties = []; // By default: there is no property
		$this->aCSSClasses = ['ibo-dashlet'];
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

		if (gettype($sValue) == $sRefType) {
			// Do not change anything in that case!
			$ret = $sValue;
		} elseif ($sRefType == 'boolean') {
			$ret = ($sValue == 'true');
		} elseif ($sRefType == 'array') {
			$ret = explode(',', $sValue);
		} elseif (is_array($sValue)) {
			$ret = $sValue;
		} else {
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
		if ($sType == 'boolean') {
			$sRet = $value ? 'true' : 'false';
		} elseif ($sType == 'array') {
			$sRet = implode(',', $value);
		} else {
			$sRet = (string)$value;
		}

		return $sRet;
	}

	protected function OnUpdate()
	{
	}

	/**
	 */
	public function FromDOMNode(DesignElement $oDOMNode)
	{
		foreach ($this->aProperties as $sProperty => $value) {
			$oPropNode = $oDOMNode->getElementsByTagName($sProperty)->item(0);
			if ($oPropNode != null) {
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
		foreach ($this->aProperties as $sProperty => $value) {
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
		$oDomDoc = new DesignDocument('1.0', 'UTF-8');
		libxml_clear_errors();
		$oDomDoc->loadXml($sXml);
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0) {
			throw new DOMException("Malformed XML");
		}

		/** @var DesignElement $oDOMNode */
		$oDOMNode = $oDomDoc->firstChild;
		$this->FromDOMNode($oDOMNode);
	}

	/**
	 * @param array $aParams
	 */
	public function FromParams($aParams)
	{
		foreach ($this->aProperties as $sProperty => $value) {
			if (array_key_exists($sProperty, $aParams)) {
				$this->aProperties[$sProperty] = $aParams[$sProperty];
			}
		}
		$this->OnUpdate();
	}

	public function FromModelData(array $aModelData)
	{
		$this->aProperties = $aModelData;
		//		$this->aProperties = XMLNormalizer::GetInstance()->Normalize($aModelData, $this->sDashletType, 'Dashlet');
		$this->OnUpdate();
	}

	/**
	 * @return array Rel. path to the app. root of the JS files required by the dashlet
	 * @since 3.0.0
	 */
	public function GetJSFilesRelPaths(): array
	{
		return [];
	}

	/**
	 * @return array Rel. path to the app. root of the CSS files required by the dashlet
	 * @since 3.0.0
	 */
	public function GetCSSFilesRelPaths(): array
	{
		return [];
	}

	/**
	 * @param WebPage $oPage
	 * @param bool $bEditMode
	 * @param bool $bEnclosingDiv
	 * @param array $aExtraParams
	 */
	public function DoRender($oPage, $bEditMode = false, $bEnclosingDiv = true, $aExtraParams = []): UIBlock
	{
		$sId = $this->GetID();

		if ($bEnclosingDiv) {
			if ($bEditMode) {
				$oDashletContainer = new DashletContainer("dashlet_{$sId}");
			} else {
				$oDashletContainer = new DashletContainer();
			}
			$oDashletContainer->AddCSSClasses($this->aCSSClasses);
		} else {
			$oDashletContainer = new DashletContainer();
			$oDashletContainer->AddCSSClasses($this->aCSSClasses);
		}

		$oDashletContainer->AddMultipleJsFilesRelPaths($this->GetJSFilesRelPaths());
		$oDashletContainer->AddMultipleCssFilesRelPaths($this->GetCSSFilesRelPaths());

		try {
			if (get_class($this->oModelReflection) == 'ModelReflectionRuntime') {
				$oBlock = $this->Render($oPage, $bEditMode, $aExtraParams);
			} else {
				$oBlock = $this->RenderNoData($oPage, $bEditMode, $aExtraParams);
			}
			$oDashletContainer->AddSubBlock($oBlock);
		} catch (UnknownClassOqlException $e) {
			// Maybe the class is part of a non-installed module, fail silently
			// Except in Edit mode
			if ($bEditMode) {
				$oDashletContainer->AddCSSClass("dashlet-content");
				$oDashletContainer->AddHtml('<h2>'.$e->GetUserFriendlyDescription().'</h2>');
			}
		} catch (OqlException $e) {
			$oDashletContainer->AddCSSClass("dashlet-content");
			$oDashletContainer->AddHtml('<p>'.utils::HtmlEntities($e->GetUserFriendlyDescription()).'</p>');
		} catch (Exception $e) {
			$oDashletContainer->AddCSSClass("dashlet-content");
			$oDashletContainer->AddHtml('<p>'.$e->getMessage().'</p>');
		}

		if ($bEditMode) {
			$sClass = $this->sDashletType;
			$sType = $this->sDashletType;
			$oPage->add_ready_script(
				<<<EOF
$('#dashlet_$sId').dashlet({dashlet_id: '$sId', dashlet_class: '$sClass', 'dashlet_type': '$sType'});
EOF
			);
		}

		return $oDashletContainer;
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
	 * @param WebPage $oPage
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 *
	 * @return iUIBlock
	 */
	abstract public function Render($oPage, $bEditMode = false, $aExtraParams = []);

	/**
	 * Rendering without the real data
	 *
	 * @param WebPage $oPage
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 *
	 * @return iUIBlock
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		return $this->Render($oPage, $bEditMode, $aExtraParams);
	}

	/**
	 * @param \DesignerForm $oForm
	 *
	 * @return mixed
	 */
	public function GetPropertiesFields(\DesignerForm $oForm)
	{
		return null;
	}

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
	 * @return Dashlet
	 */
	public function Update($aValues, $aUpdatedFields)
	{
		foreach ($aUpdatedFields as $sProp) {
			if (array_key_exists($sProp, $this->aProperties)) {
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
	public static function GetInfo()
	{
		return [
			'label'       => '',
			'icon'        => '',
			'description' => '',
		];
	}

	/**
	 * @param array $aInfo
	 *
	 * @return \DesignerForm
	 */
	public function GetForm($aInfo = [])
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
	public static function IsVisible()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public static function CanCreateFromOQL()
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
		$aGroupBy = [];
		try {
			$oQuery = $this->oModelReflection->GetQuery($sOql);
			$sClass = $oQuery->GetClass();
			foreach ($this->oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
				// For external fields, find the real type of the target
				$sExtFieldAttCode = $sAttCode;
				$sTargetClass = $sClass;
				while (is_a($sAttType, 'AttributeExternalField', true)) {
					$sExtKeyAttCode = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'extkey_attcode');
					$sTargetAttCode = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'target_attcode');
					$sTargetClass = $this->oModelReflection->GetAttributeProperty($sTargetClass, $sExtKeyAttCode, 'targetclass');
					$aTargetAttCodes = $this->oModelReflection->ListAttributes($sTargetClass);
					$sAttType = $aTargetAttCodes[$sTargetAttCode];
					$sExtFieldAttCode = $sTargetAttCode;
				}

				$aForbidenAttType = [
					'AttributeLinkedSet',
					'AttributeFriendlyName',

					'iAttributeNoGroupBy', //we cannot only use iAttributeNoGroupBy since this method is also used by the designer who do not have access to the classes' PHP reflection API. So the known classes has to be listed altogether
					'AttributeOneWayPassword',
					'AttributeEncryptedString',
					'AttributePassword',
				];
				foreach ($aForbidenAttType as $sForbidenAttType) {
					if (is_a($sAttType, $sForbidenAttType, true)) {
						continue 2;
					}
				}

				$sLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
				if (!in_array($sLabel, $aGroupBy)) {
					$aGroupBy[$sAttCode] = $sLabel;

					if (is_a($sAttType, 'AttributeDateTime', true)) {
						$aGroupBy[$sAttCode.':hour'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Hour', $sLabel);
						$aGroupBy[$sAttCode.':month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Month', $sLabel);
						$aGroupBy[$sAttCode.':day_of_week'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek', $sLabel);
						$aGroupBy[$sAttCode.':day_of_month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth', $sLabel);
					}
				}
			}
			asort($aGroupBy);
		} catch (Exception $e) {
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

	public function GetModelData(): ?array
	{
		return $this->aProperties;
		//return XMLNormalizer::GetInstance()->Denormalize($this->aProperties, $this->sDashletType, 'Dashlet');
	}
}
