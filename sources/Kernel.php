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

namespace Combodo\iTop;

use Combodo\iTop\Application\Helper\Session;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Class Kernel
 *
 * @package Combodo\iTop
 * @since 3.2.0
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

	private function getConfigDir(): string
	{
		return $this->getProjectDir() . '/resources/symfony/config';
	}

	public function getCacheDir(): string
	{
		$sEnv =  Session::Get('itop_env', 'production');
		return $this->getProjectDir() . "/data/cache-$sEnv/symfony";
	}

	public function getLogDir(): string
	{
		return $this->getProjectDir() . '/log/symfony';
	}
}
