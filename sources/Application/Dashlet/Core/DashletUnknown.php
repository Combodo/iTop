<?php

// Copyright (C) 2012-2024 Combodo SAS
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

namespace Combodo\iTop\Application\Dashlet\Core;

use Combodo\iTop\Application\Dashlet\Dashlet;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use DesignerForm;
use DesignerXMLField;
use Dict;
use DOMDocument;
use DOMElement;
use DOMFormatException;
use utils;

/**
 * Class DashletUnknown
 *
 * Used as a fallback in iTop for unknown dashlet classes.
 *
 * @since 2.5.0
 */
class DashletUnknown extends Dashlet
{
	protected static $aClassList = null;

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
		foreach ($oDOMNode->childNodes as $oDOMChildNode) {
			if ($oDOMChildNode instanceof DOMElement) {
				$sProperty = $oDOMChildNode->tagName;

				// For all properties but "rank" as it is handle by the dashboard.
				if ($sProperty !== 'rank') {
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
		if (count($aErrors) > 0) {
			throw new DOMFormatException('Dashlet definition not correctly formatted!');
		}
		foreach ($oDoc->documentElement->childNodes as $oDOMChildNode) {
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
		if (array_key_exists('xml', $aParams)) {
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
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sIconUrl = utils::HtmlEntities(utils::GetAbsoluteUrlAppRoot().'images/dashlet-unknown.png');
		$sExplainText = ($bEditMode) ? Dict::Format('UI:DashletUnknown:RenderText:Edit', $this->GetDashletType()) : Dict::S('UI:DashletUnknown:RenderText:View');

		$oDashletContainer = new DashletContainer(null, ['dashlet-content']);

		$oDashletContainer->AddHtml('<div class="dashlet-ukn-image"><img src="'.$sIconUrl.'" /></div><div class="dashlet-ukn-text">'.$sExplainText.'</div>');

		return $oDashletContainer;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sIconUrl = utils::HtmlEntities(utils::GetAbsoluteUrlAppRoot().'images/dashlet-unknown.png');
		$sExplainText = Dict::Format('UI:DashletUnknown:RenderNoDataText:Edit', $this->GetDashletType());

		$oDashletContainer = new DashletContainer(null, ['dashlet-content']);

		$oDashletContainer->AddHtml('<div class="dashlet-ukn-image"><img src="'.$sIconUrl.'" /></div><div class="dashlet-ukn-text">'.$sExplainText.'</div>');

		return $oDashletContainer;
	}

	/**
	 * @inheritdoc
	 */
	public function GetForm($aInfo = [])
	{
		if (isset($aInfo['configuration']) && empty($this->sOriginalDashletXML)) {
			$this->sOriginalDashletXML = $aInfo['configuration'];
		}

		return parent::GetForm($aInfo);
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerXMLField('xml', Dict::S('UI:DashletUnknown:Prop-XMLConfiguration'), $this->sOriginalDashletXML);
		$oForm->AddField($oField);
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		$bHasSubProperties = false;
		foreach ($oDOMNode->childNodes as $oDOMChildNode) {
			if ($oDOMChildNode->nodeType === XML_ELEMENT_NODE) {
				$bHasSubProperties = true;
				break;
			}
		}

		if ($bHasSubProperties) {
			$sTmp = $oDOMNode->ownerDocument->saveXML($oDOMNode, LIBXML_NOENT);
			$sTmp = trim(preg_replace("/(<".$oDOMNode->tagName."[^>]*>|<\/".$oDOMNode->tagName.">)/", "", $sTmp));

			return $sTmp;
		} else {
			return parent::PropertyFromDOMNode($oDOMNode, $sProperty);
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		// Save subnodes
		if (preg_match('/<(.*)>/', $value)) {
			/** @var \DOMDocumentFragment $oDOMFragment */
			$oDOMFragment = $oDOMNode->ownerDocument->createDocumentFragment();
			$oDOMFragment->appendXML($value);
			$oDOMNode->appendChild($oDOMFragment);
		} else {
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
}
