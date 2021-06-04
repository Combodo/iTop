<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Documentation\UI;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use utils;

chdir(__DIR__);

require_once '../../../approot.inc.php';
require_once(APPROOT.'application/startup.inc.php');

function log($sMsg)
{
	$sDate = date('Y-m-d H:i:s');
	echo "{$sDate} - {$sMsg}\n";
}

/**
 * Echo an RST array of aParams filtered by aColumns
 *
 * @param array $aParams
 * @param array $aColumns
 */
function DisplayParamsArray(array $aParams, array $aColumns)
{
	foreach ($aParams as $aParam) {
		foreach ($aColumns as $sColName => $iMax) {
			$iLen = strlen($aParam[$sColName]);
			if ($iLen > $iMax) {
				$aColumns[$sColName] = $iLen;
			}
		}
	}

	$sArrayLine = '+';
	foreach ($aColumns as $iMax) {
		$sArrayLine .= str_repeat('-', $iMax + 2).'+';
	}
	echo "$sArrayLine\n";
	foreach ($aParams as $aParam) {
		foreach ($aColumns as $sColName => $iMax) {
			echo '| '.str_pad($aParam[$sColName], $iMax).' ';
		}
		echo "|\n";
		echo "$sArrayLine\n";
	}
	echo "\n";
}

/**
 * Generate the syntax for the given aParams
 *
 * @param array $aParams
 *
 * @return string
 */
function DisplayParamsAsString(array $aParams): string
{
	$aParamStr = [];
	foreach ($aParams as $aParam) {
		$sParam = $aParam['name'].':';
		switch ($aParam['type']) {
			case 'string':
				$sParam .= "'value'";
				break;

			case 'array':
				$sParam .= "{name:value, name:value}";
				break;

			case 'bool':
				$sParam .= "true";
				break;

			default:
				$sParam .= "value";
				break;
		}
		$aParamStr[] = $sParam;
	}

	return implode(', ', $aParamStr);
}

/**
 * Echo the generated rst file for the given class
 *
 * @param string $sClass
 * @param string $sClassComment
 * @param string $sDir
 * @param string $sTag
 * @param bool $bHasSubBlocks
 * @param array $aDocTypes
 * @param array $aDocGeneralParams
 */
function output(string $sClass, string $sClassComment, string $sDir, string $sTag, bool $bHasSubBlocks, array $aDocTypes, array $aDocGeneralParams)
{
	if ($bHasSubBlocks) {
		$sSyntax = <<<EOF
    {% $sTag Type {Parameters} %}
        Content Goes Here
    {% End$sTag %}
EOF;
	} else {
		$sSyntax = <<<EOF
    {% $sTag Type {Parameters} %}
EOF;
	}

	echo ".. Copyright (C) 2010-2021 Combodo SARL\n";
	echo ".. http://opensource.org/licenses/AGPL-3.0\n";
	echo "\n";
	echo ".. _$sClass:\n";
	echo "\n";
	echo "$sClass\n";
	$sLine = str_repeat('=', strlen($sClass));
	echo "$sLine\n";
	echo "\n";
	echo "$sClassComment\n";
	echo "\n";
	echo "----\n";
	echo "\n";
	echo ".. include:: /manual/{$sDir}AdditionalDescription.rst\n";
	echo "\n";
	echo "----\n";
	echo "\n";
	echo "Twig Tag\n";
	echo "--------\n";
	echo "\n";
	echo ":Tag: **$sTag**\n";
	echo "\n";
	echo ":Syntax:\n";
	echo "\n";
	echo ".. code-block:: twig\n";
	echo "\n";
	echo "$sSyntax\n";
	echo "\n";
	echo ":Type:\n";
	echo "\n";
	$iMaxLength = 0;
	$iMaxComLength = 0;
	foreach ($aDocTypes as $sType => $aDoc) {
		$sComment = $aDoc['comment'];
		$sType = ":ref:`$sType <$sClass$sType>`";
		$iLength = strlen($sType);
		if ($iLength > $iMaxLength) {
			$iMaxLength = $iLength;
		}
		$iComLength = strlen($sComment);
		if ($iComLength > $iMaxComLength) {
			$iMaxComLength = $iComLength;
		}
	}
	$sArrayLine = '+'.str_repeat('-', $iMaxLength + 2).'+'.str_repeat('-', $iMaxComLength + 2).'+';
	echo "$sArrayLine\n";
	foreach ($aDocTypes as $sType => $aDoc) {
		$sComment = $aDoc['comment'];
		$sType = ":ref:`$sType <$sClass$sType>`";
		echo '| '.str_pad($sType, $iMaxLength).' | '.str_pad($sComment, $iMaxComLength)." |\n";
		echo "$sArrayLine\n";
	}
	echo "\n";

	// Parameters for each type
	foreach ($aDocTypes as $sType => $aDoc) {
		$aParams = $aDoc['params'];
		if (!empty($aParams)) {
			echo ".. _$sClass$sType:\n";
			echo "\n";
			echo "$sClass $sType\n";
			echo str_repeat("^", strlen("$sClass $sType"));
			echo "\n";
			echo "\n";
			echo ":syntax:\n";
			echo "\n";
			echo ".. code-block:: twig\n";
			echo "\n";
			$sParameters = DisplayParamsAsString($aParams);
			if ($bHasSubBlocks) {
				$sSyntax = <<<EOF
    {% $sTag $sType {{$sParameters}} %}
        Content Goes Here
    {% End$sTag %}
EOF;
			} else {
				$sSyntax = <<<EOF
    {% $sTag $sType {{$sParameters}} %}
EOF;
			}
			echo "$sSyntax\n";
			echo "\n";
			echo ":parameters:\n";
			echo "\n";
			$aColumns = [
				'name' => 0,
				'type' => 0,
				'status' => 0,
				'default' => 0,
				'comment' => 0,
			];
			DisplayParamsArray($aParams, $aColumns);
		}
	}

	if (!empty($aDocGeneralParams)) {
		echo "$sClass common parameters\n";
		echo str_repeat("^", strlen("$sClass common parameters"));
		echo "\n\n";
		$aColumns = [
			'name' => 0,
			'type' => 0,
			'comment' => 0,
		];
		usort($aDocGeneralParams, function ($a, $b) {
			return strcmp($a['name'], $b['name']);
		});
		DisplayParamsArray($aDocGeneralParams, $aColumns);
	}

	echo "----\n";
	echo "\n";
	echo ".. include:: /manual/{$sDir}Footer.rst\n";

}

/**
 * Get parameters info from a method
 *
 * @param \ReflectionMethod $oMethod
 *
 * @return array
 * @throws \ReflectionException
 */
function GetMethodParameters(ReflectionMethod $oMethod): array
{
	$aDocParams = [];
	$aParameters = $oMethod->getParameters();
	$sFullComment = $oMethod->getDocComment();
	foreach ($aParameters as $oParameter) {
		$sName = $oParameter->getName();
		$aDocParam['name'] = $sName;
		if ($oParameter->isOptional()) {
			$aDocParam['status'] = 'optional';
			$sDefault = $oParameter->getDefaultValue();
			$aDocParam['default'] = str_replace("\n", '', var_export($sDefault, true));
		} else {
			$aDocParam['status'] = 'mandatory';
			$aDocParam['default'] = '';
		}

		$oParamType = $oParameter->getType();
		if ($oParamType instanceof ReflectionNamedType) {
			$sType = $oParamType->getName();
			$iPos = strrpos($sType, "\\");
			if ($iPos !== false) {
				$sType = substr($sType, $iPos + 1);
			}
			$aDocParam['type'] = $sType;
		} else {
			$aDocParam['type'] = '';
		}

		// Get the comment for the param from method comment
		if ($sFullComment !== false) {
			if (preg_match("#^\s*\*\s*@param\s+\S*\s*.$sName(?<comment>.*)$#m", $sFullComment, $aMatches)) {
				$sComment = trim($aMatches['comment']);
				if (preg_match("#^{@see (?<class>\S+)::.(?<property>\S+)}#", $sComment, $aMatches)) {
					// Reference to a property of another class
					$sClassName = $aMatches['class'];
					$sProperty = $aMatches['property'];
					try {
						GetPropertyComment($sClassName, $sProperty, $sComment);
					}
					catch (ReflectionException $e) {
						// Try with the current name space
						$oLocalClass = new ReflectionClass($oMethod->class);
						$sNameSpace = $oLocalClass->getNamespaceName();
						$sClass = $sNameSpace.'\\'.$sClassName;
						try {
							GetPropertyComment($sClass, $sProperty, $sComment);
						}
						catch (ReflectionException $e) {
							// Search in the autoloader
							$sAutoloadFile = APPROOT.'lib/composer/autoload_classmap.php';
							$aTmpClassMap = include $sAutoloadFile;
							foreach (array_keys($aTmpClassMap) as $sClass) {
								if (utils::EndsWith($sClass, '\\'.$sClassName)) {
									try {
										GetPropertyComment($sClass, $sProperty, $sComment);
										break;
									}
									catch (ReflectionException $e) {
									}
								}
							}
						}
					}
				}
			} else {
				$sComment = '';
			}
		} else {
			$sComment = '';
		}
		$aDocParam['comment'] = $sComment;
		$aDocParams[] = $aDocParam;
	}

	return $aDocParams;
}

/**
 * Get the comment for a class property (variable)
 *
 * @param $sClass
 * @param $sProperty
 * @param $sComment
 *
 * @throws \ReflectionException when class or property does not exist
 */
function GetPropertyComment($sClass, $sProperty, &$sComment): void
{
	$oTargetClass = new ReflectionClass($sClass);
	$oProperty = $oTargetClass->getProperty($sProperty);
	$sComment = $oProperty->getDocComment();
	if (preg_match("#/\*\*\s*@var\s+\S+(\s+$sProperty)?\s+(?<comment>.*)\*/#", $sComment, $aMatches)) {
		$sComment = trim($aMatches['comment']);
	}
}

function GetMethodComment(ReflectionMethod $oMethod, string $sParamName)
{
	$sComment = $oMethod->getDocComment();
	if (strpos($sComment, $sParamName) !== false) {
		return $sComment;
	}

	//echo "- comment for $sParamName not found in ".$oMethod->class.":".$oMethod->name."\n";

	// Try to find the comment in the parent class
	$oReflectionClass = new ReflectionClass($oMethod->class);
	$oReflectionParentClass = $oReflectionClass->getParentClass();
	if ($oReflectionParentClass === false) {
		$aReflectionParentClasses = $oReflectionClass->getInterfaces();
		foreach ($aReflectionParentClasses as $oReflectionParentClass) {
			try {
				$oParentMethod = $oReflectionParentClass->getMethod($oMethod->name);
			}
			catch (ReflectionException $e) {
				continue;
			}
			$sComment = GetMethodComment($oParentMethod, $sParamName);
			if (!empty($sComment)) {
				return $sComment;
			}
		}

		return '';
	}
	try {
		$oParentMethod = $oReflectionParentClass->getMethod($oMethod->name);
	}
	catch (ReflectionException $e) {
		return '';
	}

	return GetMethodComment($oParentMethod, $sParamName);
}

/////////////////////////////
/// Main
///

if (!utils::IsModeCLI()) {
	\Combodo\iTop\FullTextSearch\log("Only CLI mode is allowed");

	return;
}

$sUIBlock = utils::ReadParam('uiblock', '', true);

$sSourceDir = '../source';

$sInterface = "Combodo\\iTop\\Application\\UI\\Base\\iUIBlockFactory";
$aFactoryClasses = utils::GetClassesForInterface($sInterface, $sUIBlock.'UIBlockFactory');


foreach ($aFactoryClasses as $sFactoryClass) {
	try {
		$sTag = call_user_func([$sFactoryClass, 'GetTwigTagName']);
		$sBlockClassName = call_user_func([$sFactoryClass, 'GetUIBlockClassName']);
		$bHasSubBlocks = is_subclass_of($sBlockClassName, "Combodo\\iTop\\Application\\UI\\Base\\Layout\\UIContentBlock") || $sBlockClassName == "Combodo\\iTop\\Application\\UI\\Base\\Layout\\UIContentBlock";

		$oReflectionClassFactory = new ReflectionClass($sFactoryClass);
		$oReflectionClassUIBlock = new ReflectionClass($sBlockClassName);
		$sClassName = $oReflectionClassUIBlock->getShortName();

		$aMethods = $oReflectionClassFactory->getMethods(ReflectionMethod::IS_PUBLIC);

		$aDocTypes = [];
		foreach ($aMethods as $oMethod) {
			$sMethodName = $oMethod->name;
			if (utils::StartsWith($sMethodName, 'Make')) {
				$oMethod = $oReflectionClassFactory->getMethod($sMethodName);
				$sFullComment = $oMethod->getDocComment();
				if ($sFullComment !== false) {
					// Remove the first line
					$sComment = $sFullComment;
					$sComment = substr($sComment, strpos($sComment, "\n") + 1);
					$sComment = trim(substr($sComment, strpos($sComment, "*") + 1));
					// Remove the last lines
					$sComment = substr($sComment, 0, strpos($sComment, "\n"));
				} else {
					$sComment = 'No comment';
				}
				$sType = substr($sMethodName, strlen('Make'));
				$aDocType['comment'] = $sComment;

				$aDocType['params'] = GetMethodParameters($oMethod);
				$aDocTypes[$sType] = $aDocType;
			}
		}

		// Setters and Adders
		$aMethods = $oReflectionClassUIBlock->getMethods(ReflectionMethod::IS_PUBLIC);
		$aDocGeneralParams = [];
		foreach ($aMethods as $oMethod) {
			if (!$oMethod->isStatic() && $oMethod->getNumberOfParameters() == 1) {
				$sName = '';
				if (utils::StartsWith($oMethod->getName(), 'Set')) {
					$sName = substr($oMethod->getName(), 3); // remove 'Set' to get the variable name
				}
				if (utils::StartsWith($oMethod->getName(), 'Add')) {
					$sName = $oMethod->getName();
				}
				if (!empty($sName)) {
					// Get the param name
					$aParams = GetMethodParameters($oMethod)[0];
					$aParams['name'] = $sName;
					$aDocGeneralParams[] = $aParams;
				}
			}
		}

		// Class comment
		$sFullClassComment = $oReflectionClassUIBlock->getDocComment();
		$aComments = preg_split("@\n@", $sFullClassComment);
		$aClassComments = [];
		// remove first line
		array_shift($aComments);
		while ($sComment = array_shift($aComments)) {
			if (utils::StartsWith($sComment, " * @")) {
				break;
			}
			$sComment = trim(preg_replace("@^ \*@", '', $sComment));
			if (strlen($sComment) > 0) {
				$aClassComments[] = $sComment;
			}
		}

		$sClassComment = implode("\n", $aClassComments);

		if (empty($sClassComment)) {
			$sClassComment = "Class $sClassName";
		}

		$sDir = str_replace("Combodo\\iTop\\Application\\UI\\Base\\", '', $sBlockClassName);
		$sDir = str_replace("\\", '/', $sDir);

		ob_start();
		output($sClassName, $sClassComment, $sDir, $sTag, $bHasSubBlocks, $aDocTypes, $aDocGeneralParams);
		$sContent = ob_get_contents();
		ob_end_clean();

		$sFilename = $sSourceDir.'/generated/'.$sDir.'.rst';
		@mkdir(dirname($sFilename), 0775, true);
		file_put_contents($sFilename, $sContent);

		// Check that manual files exists
		$sAdditionalDescription = $sSourceDir.'/manual/'.$sDir.'AdditionalDescription.rst';
		@mkdir(dirname($sAdditionalDescription), 0775, true);
		if (!is_file($sAdditionalDescription)) {
			file_put_contents($sAdditionalDescription, <<<EOF
.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0


Output
------

No output provided yet

.. example of image
	.. image:: /manual/$sDir.png


EOF
			);
		}
		$sFooter = $sSourceDir.'/manual/'.$sDir.'Footer.rst';
		@mkdir(dirname($sFooter), 0775, true);
		if (!is_file($sFooter)) {
			file_put_contents($sFooter, <<<EOF
.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0


Examples
--------

No examples provided yet

.. example of image
	.. image:: /manual/$sDir.png

EOF
			);
		}


		echo "Generated $sFilename\n";
	}
	catch (Exception $e) {

	}
}

// Rebuild doc
$sRootDir = dirname(__DIR__);
shell_exec("$sRootDir/make.bat html");



