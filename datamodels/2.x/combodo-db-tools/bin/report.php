<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\DBTools\Service\DBAnalyzerUtils;

require_once ('../../../approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');

require_once('../db_analyzer.class.inc.php');
require_once('../src/Service/DBAnalyzerUtils.php');

$oDBAnalyzer = new DatabaseAnalyzer(0);
$aResults = $oDBAnalyzer->CheckIntegrity([]);

if (empty($aResults))
{
	echo "Database OK\n";
	exit(0);
}

$sReportFile = DBAnalyzerUtils::GenerateReport($aResults);

echo "Report generated: {$sReportFile}.log\n";
