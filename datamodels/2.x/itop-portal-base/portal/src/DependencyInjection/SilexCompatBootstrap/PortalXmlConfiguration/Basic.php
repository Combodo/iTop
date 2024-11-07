<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration;

use Combodo\iTop\Application\Branding;
use Combodo\iTop\DesignElement;
use Combodo\iTop\Portal\Helper\UIExtensionsHelper;
use DOMFormatException;
use Exception;
use Symfony\Component\DependencyInjection\Container;
use utils;

/**
 * Class Basic
 *
 * @package Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.7.0
 */
class Basic extends AbstractConfiguration
{
	/**
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @throws \Exception
	 */
	public function Process(Container $oContainer)
	{
		try
		{
			// Parsing file
			// - Default values
			$aPortalConf = $this->GetInitialPortalConf();
			// - Global portal properties
			$aPortalConf = $this->ParseGlobalProperties($aPortalConf);
			// - Rectifying portal logo url
			$aPortalConf = $this->AppendLogoUri($aPortalConf);
			// - Rectifying portal favicon url
			$aPortalConf = $this->AppendFavIconUri($aPortalConf);
		}
		catch (Exception $oException)
		{
			throw new Exception('Error while parsing portal configuration file : '.$oException->getMessage());
		}

		$oContainer->setParameter('combodo.portal.instance.conf', $aPortalConf);
	}

	/**
	 * Returns an array containing the initial portal configuration with all default values
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function GetInitialPortalConf()
	{
		$aPortalConf = array(
			'properties' => array(
				'id'              => $_ENV['PORTAL_ID'],
				'name'            => 'Page:DefaultTitle',
				'logo'            => Branding::GetPortalLogoAbsoluteUrl(),
				'favicon'         => Branding::GetPortalFavIconAbsoluteUrl(),
				'themes'          => array(
					'bootstrap' => 'itop-portal-base/portal/public/css/bootstrap-theme-combodo.scss',
					'portal' => 'itop-portal-base/portal/public/css/portal.scss',
					'others' => array(),
				),
				'templates' => array(
					'layout' => 'itop-portal-base/portal/templates/layout.html.twig',
					'home' => 'itop-portal-base/portal/templates/home/layout.html.twig',
				),
				'urlmaker_class' => null,
				'triggers_query' => null,
				'attachments' => array(
					'allow_delete' => true,
				),
				'allowed_portals' => array(
					'opening_mode' => null,
				),
			),
			'forms' => array(),
			'bricks' => array(),
			'bricks_total_width' => 0,
		);

		return $aPortalConf;
	}

	/**
	 * @param array $aPortalConf
	 *
	 * @return array
	 * @throws \DOMFormatException
	 */
	private function ParseGlobalProperties(array $aPortalConf)
	{
		/** @var \MFElement $oPropertyNode */
		foreach ($this->GetModuleDesign()->GetNodes('/module_design/properties/*') as $oPropertyNode)
		{
			switch ($oPropertyNode->nodeName)
			{
				case 'name':
				case 'urlmaker_class':
				case 'triggers_query':
				case 'logo':
				case 'favicon':
					$aPortalConf['properties'][$oPropertyNode->nodeName] = $oPropertyNode->GetText(
						$aPortalConf['properties'][$oPropertyNode->nodeName]
					);
					break;
				case 'themes':
				case 'templates':
					$aPortalConf = $this->ParseTemplateAndTheme($aPortalConf, $oPropertyNode);
					break;
				case 'attachments':
					$aPortalConf = $this->ParseAttachments($aPortalConf, $oPropertyNode);
					break;
				case 'allowed_portals':
					$aPortalConf = $this->ParseAllowedPortalsOptions($aPortalConf, $oPropertyNode);
					break;
			}
		}

		return $aPortalConf;
	}

	/**
	 * @param array                       $aPortalConf
	 * @param \Combodo\iTop\DesignElement $oPropertyNode
	 *
	 * @return array
	 * @throws \DOMFormatException
	 */
	private function ParseTemplateAndTheme(array $aPortalConf, DesignElement $oPropertyNode)
	{
		/** @var \MFElement $oSubNode */
		foreach ($oPropertyNode->GetNodes('template|theme') as $oSubNode)
		{
			if (!$oSubNode->hasAttribute('id') || $oSubNode->GetText(null) === null)
			{
				throw new DOMFormatException(
					'Tag '.$oSubNode->nodeName.' must have a "id" attribute as well as a value',
					null, null, $oSubNode
				);
			}

			$sNodeId = $oSubNode->getAttribute('id');
			switch ($oSubNode->nodeName)
			{
				case 'theme':
					switch ($sNodeId)
					{
						case 'bootstrap':
						case 'portal':
						case 'custom':
							$aPortalConf['properties']['themes'][$sNodeId] = $oSubNode->GetText(null);
							break;
						default:
							$aPortalConf['properties']['themes']['others'][] = $oSubNode->GetText(null);
							break;
					}
					break;
				case 'template':
					switch ($sNodeId)
					{
						case 'layout':
						case 'home':
							$aPortalConf['properties']['templates'][$sNodeId] = $oSubNode->GetText(null);
							break;
						default:
							throw new DOMFormatException(
								'Value "'.$sNodeId.'" is not handled for template[@id]',
								null, null, $oSubNode
							);
							break;
					}
					break;
			}
		}

		return $aPortalConf;
	}

	/**
	 * @param array                       $aPortalConf
	 * @param \Combodo\iTop\DesignElement $oPropertyNode
	 *
	 * @return array
	 */
	private function ParseAttachments(array $aPortalConf, DesignElement $oPropertyNode)
	{
		/** @var \MFElement $oSubNode */
		foreach ($oPropertyNode->GetNodes('*') as $oSubNode)
		{
			switch ($oSubNode->nodeName)
			{
				case 'allow_delete':
					$sValue = $oSubNode->GetText();
					// If the text is null, we keep the default value
					// Else we set it
					if ($sValue !== null)
					{
						$aPortalConf['properties']['attachments'][$oSubNode->nodeName] = ($sValue === 'true') ? true : false;
					}
					break;
			}
		}

		return $aPortalConf;
	}

	/**
	 * @param array                       $aPortalConf
	 * @param \Combodo\iTop\DesignElement $oPropertyNode
	 *
	 * @return array
	 */
	private function ParseAllowedPortalsOptions(array $aPortalConf, DesignElement $oPropertyNode)
	{
		/** @var \MFElement $oSubNode */
		foreach ($oPropertyNode->GetNodes('*') as $oSubNode)
		{
			switch ($oSubNode->nodeName)
			{
				case 'opening_mode':
					$sValue = $oSubNode->GetText();
					// If the text is null, we keep the default value
					// Else we set it
					if ($sValue !== null)
					{
						$aPortalConf['properties']['allowed_portals'][$oSubNode->nodeName] = ($sValue === 'self') ? 'self' : 'tab';
					}
					break;
			}
		}

		return $aPortalConf;
	}

	/**
	 * @param array $aPortalConf
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function AppendLogoUri(array $aPortalConf)
	{
		$sLogoUri = $aPortalConf['properties']['logo'];
		if (!preg_match('/^http/', $sLogoUri)) {
			// We prefix it with the server base url
			$sLogoUri = utils::GetAbsoluteUrlAppRoot().'env-'.utils::GetCurrentEnvironment().'/'.$sLogoUri;
		}
		$aPortalConf['properties']['logo'] = $sLogoUri;

		return $aPortalConf;
	}

	/**
	 * @param array $aPortalConf
	 *
	 * @return array
	 * @throws \Exception
	 * @since 3.2.0 N°3363
	 */
	private function AppendFaviconUri(array $aPortalConf)
	{
		$sFaviconUri = $aPortalConf['properties']['favicon'];
		if (!preg_match('/^http/', $sFaviconUri)) {
			// We prefix it with the server base url
			$sFaviconUri = utils::GetAbsoluteUrlAppRoot().'env-'.utils::GetCurrentEnvironment().'/'.$sFaviconUri;
		}
		$aPortalConf['properties']['favicon'] = $sFaviconUri;

		return $aPortalConf;
	}
}