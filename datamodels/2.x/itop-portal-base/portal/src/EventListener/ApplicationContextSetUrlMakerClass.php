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

namespace Combodo\iTop\Portal\EventListener;

use ApplicationContext;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class ApplicationContextSetUrlMakerClass
 *
 * @package Combodo\iTop\Portal\EventListener
 * @since 2.7.0
 * @author Bruno Da Silva <bruno.dasilva@combodo.com>
 */
class ApplicationContextSetUrlMakerClass
{
    /** @var array $aCombodoPortalInstanceConf */
    private $aCombodoPortalInstanceConf;

    /**
     * @param array $aCombodoPortalInstanceConf
     */
    public function __construct($aCombodoPortalInstanceConf)
    {
        $this->aCombodoPortalInstanceConf = $aCombodoPortalInstanceConf;
    }

	/**
	 * @param RequestEvent $oRequestEvent
	 */
	public function onKernelRequest(RequestEvent $oRequestEvent)
	{
		if ($this->aCombodoPortalInstanceConf['properties']['urlmaker_class'] !== null) {
			ApplicationContext::SetUrlMakerClass($this->aCombodoPortalInstanceConf['properties']['urlmaker_class']);
		}
	}
}