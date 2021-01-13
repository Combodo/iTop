<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


class UIBlockHelper
{
	public static function PushParentBlock($sBlockVarName)
	{
		return "\$context['UIBlockParent'][] = \${$sBlockVarName};\n";
	}

	public static function PopParentBlock()
	{
		return "array_pop(\$context['UIBlockParent']);\n";
	}

	public static function GetParentBlock()
	{
		return "end(\$context['UIBlockParent'])";
	}

	public static function AddToParentBlock($sBlockVarName)
	{
		return "end(\$context['UIBlockParent'])->AddSubBlock(\${$sBlockVarName});\n";
	}

	public static function GetBlockVarName($sPrefix)
	{
		return str_replace('.', '', uniqid($sPrefix.'_', true));
	}
}