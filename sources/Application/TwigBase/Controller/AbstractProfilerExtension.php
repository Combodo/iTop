<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Controller;


abstract class AbstractProfilerExtension implements iProfilerExtension
{
	public function Init()
	{
	}

	public abstract function GetTemplatesPath(): null|string|array;

	public abstract function IsEnabled(): bool;

	public abstract function GetDebugTemplate(): string;

	public abstract function GetDebugParams(array $aParams): array;

	public function GetLinkedScripts(): ?array
	{
		return null;
	}

	public function GetLinkedStylesheets(): ?array
	{
		return null;
	}

	public function GetSaas(): null|array
	{
		return null;
	}
}