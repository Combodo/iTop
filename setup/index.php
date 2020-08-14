<?php

require_once '../approot.inc.php';
require_once APPROOT.'setup/setuputilslight.class.php';


/** @var \CheckResult[] $aResult */
$aResult = array();
SetupUtilsLight::CheckPhpVersion($aResult);

$aResultError = array_filter(
	$aResult,
	function ($item) {
		return ($item->iSeverity === CheckResult::ERROR);
	}
);

if (empty($aResultError))
{
	/**
	 * there are multiple means to redirect to the setup wizard
	 * We don't want to use header('Location: ...') as this will change browser's url
	 * Include allows us to execute the wizard script without throwing compilation errors
	 *
	 * @see https://www.php.net/manual/fr/function.include.php
	 */
	@include 'setup.start.php';
	die(0);
}
else
{
	$aErrorMessages = array_map(
		function ($item) {
			return $item->sLabel;
		},
		$aResultError
	);

	$sPageTitle = 'iTop : cannot install !';
	$sErrorMessages = implode('<br>', $aErrorMessages);
	$sPageContent = <<<HTML
<div style="color:red;">$sErrorMessages</div>
HTML;
	$iCacheTimeStamp = time();
	$sPageContent = WebPageLight::EmbedSetupPageContent('../', $sPageContent, $sPageTitle, $iCacheTimeStamp);
}
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title><?= $sPageTitle ?></title>
<link rel="stylesheet" type="text/css" href="../css/setup.css?t=<?= $iCacheTimeStamp ?>"/>
</head>
<body>
<?= $sPageContent ?>
</body>
</html>
