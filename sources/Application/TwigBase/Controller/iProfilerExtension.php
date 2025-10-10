<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Controller;

interface iProfilerExtension
{
	public static function GetInstance(): iProfilerExtension;
	public function Init();
	public function IsEnabled(): bool;
	public function GetTemplatesPath(): null|string|array;
	public function GetDebugTemplate(): string;
	public function GetDebugParams(array $aParams): array;
	public function GetLinkedScripts(): null|array;
	public function GetLinkedStylesheets(): null|array;
	public function GetSaas(): null|array;
}