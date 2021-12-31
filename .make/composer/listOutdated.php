<?php
/**
 * Copyright (C) 2010-2021 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

$iTopFolder = __DIR__ . "/../../" ;

require_once ("$iTopFolder/approot.inc.php");
$sApproot = APPROOT;
$aTrace = array();

$aParamsConfig = array(
	'composer-path' => array(
		'default' => 'composer.phar',
	)
);
$aParamsConfigNotFound = array_flip(array_keys($aParamsConfig));
$aGivenArgs = $argv;
unset($aGivenArgs[0]);

$aParams = array();

foreach ($aParamsConfig as $sParam => $aConfig)
{
	$bParamsFound = false;
	foreach ($aGivenArgs as $sGivenArg)
	{
		if (preg_match("/--$sParam(?:=(?<value>.*))?$/", $sGivenArg, $aMatches))
		{
			$aParams[$sParam] =
				isset($aMatches['value'])
					? $aMatches['value']
					: true
			;
			$bParamsFound = true;
			unset($aGivenArgs[$sGivenArg]);

		}
	}

	if ($bParamsFound)
	{
		unset($aParamsConfigNotFound[$sParam]);
	}
}

foreach ($aParamsConfigNotFound as $sParamsConfigNotFound => $void)
{
	if (isset($aParamsConfig[$sParamsConfigNotFound]['default']))
	{
		$aParams[$sParamsConfigNotFound] = $aParamsConfig[$sParamsConfigNotFound]['default'];
		$aTrace[] =  "\e[1;30mUsing default value '{$aParams[$sParamsConfigNotFound]}' for '$sParamsConfigNotFound'\e[0m\n";
		continue;
	}

	die("Missing '$sParamsConfigNotFound'");
}

echo "This command aims at helping you find upgradable dependencies\n";
echo "\e[0;33mBeware of the version colored in orange, they probably introduce BC breaks!\e[0m\n";

$sCommand = "{$aParams['composer-path']} show -loD --working-dir=$sApproot --ansi";
$execCode = exec($sCommand, $output);
$sOutput = implode("\n", $output)."\n";

if (!$execCode)
{
	echo  "\e[41mFailed to execute '$sCommand'\e[0m\n";
	echo "Trace: \n".implode("\n", $aTrace);
}
else
{
	$iCountDepdendenciesFound = count($output);

	$iCountBc =  substr_count($sOutput, '[33m');

	echo sprintf("Found \033[44m%d\033[0m upgradable dependencies, including \e[41m%s  BC break\e[0m 😱 :\n\n", $iCountDepdendenciesFound, $iCountBc);
}


echo $sOutput;

