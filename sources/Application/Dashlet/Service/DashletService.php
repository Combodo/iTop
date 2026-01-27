<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Dashlet\Service;

use Combodo\iTop\Application\Dashlet\DashletException;
use Combodo\iTop\DesignDocument;
use Combodo\iTop\DesignElement;
use Dict;
use utils;

class DashletService
{
	private array $aDashlets = [];

	public function __construct()
	{
	}

	/**
	 * @param string $sCategory
	 *
	 * @return array
	 * @throws \Combodo\iTop\Application\Dashlet\DashletException
	 * @throws \DOMFormatException
	 */
	public function GetAvailableDashlets(string $sCategory = ''): array
	{
		$this->InitDashletDefinitions();
		$aFilteredDashlets = [];

		switch ($sCategory) {
			case 'can_be_created':
				foreach ($this->aDashlets as $sType => $aDashlet) {
					if ($aDashlet['can_be_created']) {
						$aFilteredDashlets[$sType] = $aDashlet;
					}
				}
				break;

			case 'can_create_by_oql':
				foreach ($this->aDashlets as $sType => $aDashlet) {
					if ($aDashlet['can_create_by_oql']) {
						$aFilteredDashlets[$sType] = $aDashlet;
					}
				}
				break;

			default:
				$aFilteredDashlets = $this->aDashlets;
				break;
		}

		return $aFilteredDashlets;
	}

	/**
	 * @param string $sXMLContent
	 *
	 * @return \Combodo\iTop\DesignElement
	 * @throws \Combodo\iTop\Application\Dashlet\DashletException
	 */
	private function GetDomNode(string $sXMLContent): DesignElement
	{
		$oDoc = new DesignDocument();
		libxml_clear_errors();
		$oDoc->loadXML($sXMLContent);
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0) {
			throw new DashletException('Dashlets definition not correctly formatted!');
		}

		/** @var \Combodo\iTop\DesignElement $oRoot */
		$oRoot = $oDoc->firstChild;

		return $oRoot;
	}

	/**
	 * @return string
	 * @throws \Combodo\iTop\Application\Dashlet\DashletException
	 */
	private function GetXMLContent(): string
	{
		$sPath = utils::GetAbsoluteModulePath('core')."dashlets.xml";
		if (!file_exists($sPath)) {
			throw new DashletException("Dashlets definition not present");
		}

		return file_get_contents($sPath);
	}

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \Combodo\iTop\Application\Dashlet\DashletException
	 * @throws \DOMFormatException
	 */
	public function IsDashletAvailable(string $sClass): bool
	{
		$this->InitDashletDefinitions();

		return array_key_exists($sClass, $this->aDashlets);
	}

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \Combodo\iTop\Application\Dashlet\DashletException
	 * @throws \DOMFormatException
	 */
	public function GetDashletDefinition(string $sClass): array
	{
		if ($this->IsDashletAvailable($sClass)) {
			return $this->aDashlets[$sClass];
		}

		throw new DashletException('Dashlets definition '.json_encode($sClass).' not present');
	}

	/**
	 * @return void
	 * @throws \Combodo\iTop\Application\Dashlet\DashletException
	 * @throws \DOMFormatException
	 */
	private function InitDashletDefinitions(): void
	{
		if (count($this->aDashlets) === 0) {
			$this->aDashlets = [];

			$oDashletsNode = $this->GetDomNode($this->GetXMLContent());
			/** @var DesignElement $oDashletNode */
			foreach ($oDashletsNode->getElementsByTagName('dashlet') as $oDashletNode) {
				$sType = $oDashletNode->getAttribute('id');
				$aInfo = [
					'label'             => Dict::S($oDashletNode->GetChildText('label')),
					'icon'              => $oDashletNode->GetChildText('icon'),
					'description'       => Dict::S($oDashletNode->GetChildText('description')),
					'min_width'         => intval($oDashletNode->GetChildText('min_width', '2')),
					'min_height'        => intval($oDashletNode->GetChildText('min_height', '1')),
					'preferred_width'   => intval($oDashletNode->GetChildText('preferred_width', '2')),
					'preferred_height'  => intval($oDashletNode->GetChildText('preferred_height', '1')),
					'can_create_by_oql' => utils::IsTrue($oDashletNode->GetChildText('can_create_by_oql', 'false')),
					'can_be_created'    => utils::IsTrue($oDashletNode->GetChildText('can_be_created', 'true')),
				];
				$this->aDashlets[$sType] = $aInfo;
			}

			uasort($this->aDashlets, function ($a, $b) {
				return strcmp($a['label'], $b['label']);
			});
		}
	}
}
