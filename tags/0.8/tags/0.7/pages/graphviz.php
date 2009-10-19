<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');

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
	rankdir=LR;
	size=\"12,12\"
	node [ fontname=Verdana ];
	edge [ fontname=Verdana ];
";
		$aStatesLinks = array();
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$aStatesLinks[$sStateCode] = array('in' => 0, 'out' => 0);
		}
		
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = $aStates[$sStateCode]['label'];
			$sStateDescription = $aStates[$sStateCode]['description'];
			foreach(MetaModel::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
			{
				$aStatesLinks[$sStateCode]['out']++;
				$aStatesLinks[$aTransitionDef['target_state']]['in']++;
				$sStimulusLabel = $aStimuli[$sStimulusCode]->Get('label');
				$sTargetStateLabel = $aStates[$aTransitionDef['target_state']]['label'];
				$sDotFileContent .= "\t$sStateCode -> {$aTransitionDef['target_state']} [ label=\"$sStimulusLabel\"];\n";
			}
		}
		foreach($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = str_replace(' ', '\n', $aStates[$sStateCode]['label']);
			if ( ($aStatesLinks[$sStateCode]['in'] == 0) || ($aStatesLinks[$sStateCode]['out'] == 0))
			{
				$sDotFileContent .= "\t$sStateCode [ shape=doublecircle,label=\"$sStateLabel\"];\n";
			}
			else
			{
				$sDotFileContent .= "\t$sStateCode [ shape=circle,label=\"$sStateLabel\"];\n";
			}
		}
		$sDotFileContent .= "}\n";
	}
	return $sDotFileContent;
}

$sClass = utils::ReadParam('class', 'bizIncidentTicket');
$sDir = dirname(__FILE__);
$sImageFilePath = realpath($sDir."/../images/lifecycle/$sClass.png");
if (!file_exists($sImageFilePath))
{
	// create the file with Graphviz
	$sDotDescription = GraphvizLifecycle($sClass);
	$sDotFilePath = $sDir."/tmp-lifecycle.dot";
	$rFile = fopen($sDotFilePath, "w");
	fwrite($rFile, $sDotDescription);
	fclose($rFile);
	exec("/iTop/Graphviz/bin/dot.exe -Tpng < $sDotFilePath > $sImageFilePath");
}

header('Content-type: image/png');
echo file_get_contents($sImageFilePath);
?>
