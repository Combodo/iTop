<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Renders a graph of the class' lifecycle as a png (directly in the HTTP response)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/utils.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

/**
 * Escape a label (string) in a manner suitable for use with graphviz' DOT syntax
 * @param string $s The string to escape
 * @return string The escaped string
 */
function GraphvizEscape($s)
{
	$s = str_replace('"', '\\"', $s);
	return $s;
}

/**
 * Helper to generate a Graphviz code for displaying the life cycle of a class
 * @param string $sClass The class to display
 * @return string The Graph description in Graphviz/Dot syntax   
 */
function GraphvizLifecycle($sClass)
{
	$sDotFileContent = "";
	$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
	if (empty($sStateAttCode))
	{
		//$oPage->p("no lifecycle for this class");
	}
	else
	{
		$aStates = MetaModel::EnumStates($sClass);
		$aStimuli = MetaModel::EnumStimuli($sClass);
		$sDotFileContent .= "digraph finite_state_machine {
	graph [bgcolor = \"transparent\"];
	rankdir=LR;
	size=\"12,12\"
	node [ fontname=Verdana style=filled fillcolor=\"#ffffff\" ];
	edge [ fontname=Verdana ];
";
		$aStatesLinks = array();
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$aStatesLinks[$sStateCode] = array('in' => 0, 'out' => 0);
		}
		
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = MetaModel::GetStateLabel($sClass, $sStateCode);
			$sStateDescription = MetaModel::GetStateDescription($sClass, $sStateCode);
			foreach(MetaModel::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
			{
				$aStatesLinks[$sStateCode]['out']++;
				$aStatesLinks[$aTransitionDef['target_state']]['in']++;
				$sStimulusLabel = $aStimuli[$sStimulusCode]->GetLabel();
				$sTargetStateLabel = MetaModel::GetStateLabel($sClass, $aTransitionDef['target_state']);
				$sDotFileContent .= "\t$sStateCode -> {$aTransitionDef['target_state']} [ label=\"".GraphvizEscape($sStimulusLabel)."\"];\n";
			}
		}
		foreach($aStates as $sStateCode => $aStateDef)
		{
			if (($aStatesLinks[$sStateCode]['out'] > 0) || ($aStatesLinks[$sStateCode]['in'] > 0))
			{
				// Show only reachable states
				$sStateLabel = str_replace(' ', '\n', MetaModel::GetStateLabel($sClass, $sStateCode));
				if ( ($aStatesLinks[$sStateCode]['in'] == 0) || ($aStatesLinks[$sStateCode]['out'] == 0))
				{
					// End or Start state, make it look different
					$sDotFileContent .= "\t$sStateCode [ shape=doublecircle,label=\"".GraphvizEscape($sStateLabel)."\"];\n";
				}
				else
				{
					$sDotFileContent .= "\t$sStateCode [ shape=circle,label=\"".GraphvizEscape($sStateLabel)."\"];\n";
				}
			}
		}
		$sDotFileContent .= "}\n";
	}
	return $sDotFileContent;
}

$sClass = utils::ReadParam('class', '', false, 'class');
$oReflection = new ReflectionClass($sClass);
$sDeclarationFile = $oReflection->getFileName();
$sModuleDir = dirname($sDeclarationFile);

$sImageFilePath = $sModuleDir."/lifecycle/".$sClass.".png";
$sDotExecutable = MetaModel::GetConfig()->Get('graphviz_path');
if (file_exists($sDotExecutable))
{
	// create the file with Graphviz
	$sImageFilePath = APPROOT."data/lifecycle/".$sClass.".png";
	if (!is_dir(APPROOT."data"))
	{
		@mkdir(APPROOT."data");
	}
	if (!is_dir(APPROOT."data/lifecycle"))
	{
		@mkdir(APPROOT."data/lifecycle");
	}
	$sDotDescription = GraphvizLifecycle($sClass);
	$sDotFilePath = APPROOT."data/lifecycle/{$sClass}.dot";
	
	$rFile = @fopen($sDotFilePath, "w");
	@fwrite($rFile, $sDotDescription);
	@fclose($rFile);
	$aOutput = array();
	$CommandLine = "$sDotExecutable -v -Tpng < $sDotFilePath -o$sImageFilePath 2>&1";
	
	exec($CommandLine, $aOutput, $iRetCode);
	if ($iRetCode != 0)
	{
		header('Content-type: text/html');
		echo "<p><b>Error:</b></p>";
		echo "<p>The command: <pre>$CommandLine</pre> returned $iRetCode</p>";
		echo "<p>The output of the command is:<pre>\n".implode("\n", $aOutput)."</pre></p>";
		echo "<hr>";
		echo "<p>Content of the '".basename($sDotFilePath)."' file:<pre>\n$sDotDescription</pre>";
	}
	else
	{
		header('Content-type: image/png');
		echo file_get_contents($sImageFilePath);
	}
	@unlink($sDotFilePath);
}
else
{
	header('Content-type: image/png');
	echo file_get_contents($sImageFilePath);
}

?>
