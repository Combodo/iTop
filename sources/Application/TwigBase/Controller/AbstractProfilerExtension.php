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

	abstract public function GetTemplatesPath(): null|string|array;

	abstract public function IsEnabled(): bool;

	abstract public function GetDebugTemplate(): string;

	abstract public function GetDebugParams(array $aParams): array;

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
