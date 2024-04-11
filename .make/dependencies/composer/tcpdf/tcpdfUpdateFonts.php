<?php
/**
 * This script will copy custom fonts in the TCPDF lib fonts directory.
 * If you need to add other files :
 * - add the corresponding files in this script directory
 * - modify this script to copy also your files
 *
 * @since 2.7.0 N°1947 add DroidSansFallback font (see also PR #49 in the links below)
 * @since 2.7.0 N°2435 TCPPDF lib forked and added in composer.json (at that time the lib was announced as deprecated and rewritten in tecnickcom/tc-lib-pdf)
 * @since 3.2.0 N°7175 switch back to TCPDF original lib (which is finally still maintained, tecnickcom/tc-lib-pdf us still under dev), script creation to keep custom DroidSansFallback font
 *
 * @link https://github.com/Combodo/iTop/pull/49 add DroidSansFallback font
 * @link https://github.com/tecnickcom/TCPDF?tab=readme-ov-file#note TCPDF is in support only mode
 */

$sItopRootFolder = realpath(__DIR__ . "/../../../../");
$sCurrentScriptFileName = basename(__FILE__);


require_once ("$sItopRootFolder/lib/autoload.php");


$sTcPdfRootFolder = $sItopRootFolder.'/lib/tecnickcom/tcpdf';
if (false === file_exists($sTcPdfRootFolder)) {
	echo $sCurrentScriptFileName.": No TCPDF lib detected, exiting !\n";
	return;
}
$sTcPdfFontsFolder = $sTcPdfRootFolder.'/Fonts/';


/**
 * 1) Cleaning up the fonts directory to keep only the ones we want in iTop
 */
echo $sCurrentScriptFileName.": ---1) Cleaning up the fonts files\n";
$aTcpdfDefaultFontsToKeepInItop = file(__DIR__.'/tcpdfDefaultFontsToKeepInItop.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$aTcpdfFontsDirContent = scandir($sTcPdfFontsFolder);
foreach ($aTcpdfFontsDirContent as $sTcpdfFontResourceName) {
	if ($sTcpdfFontResourceName === '.') {
		continue;
	}
	if ($sTcpdfFontResourceName === '..') {
		continue;
	}

	if (!in_array($sTcpdfFontResourceName, $aTcpdfDefaultFontsToKeepInItop, true)) {
		echo $sCurrentScriptFileName.": Removing $sTcpdfFontResourceName !\n";

		$sTcpdfFontResourceFullPath = $sTcPdfFontsFolder.$sTcpdfFontResourceName;
		if (is_file($sTcpdfFontResourceFullPath)) {
			unlink($sTcpdfFontResourceFullPath);
		} elseif (is_dir($sTcpdfFontResourceFullPath)) {
			rrmdir($sTcpdfFontResourceFullPath);
		}
	}
}



/**
 * 2) Then adding the DroidSansFallback font (useful for CJK data for example)
 */
echo $sCurrentScriptFileName.": ---2) Copying font files to TCPDF ($sTcPdfFontsFolder)...\n";
$aFontFilesToCopy = glob(__DIR__.'\droidsansfallback.*');
foreach ($aFontFilesToCopy as $sFontFileToCopy) {
	$sFontFileName = basename($sFontFileToCopy);
	echo $sCurrentScriptFileName.': copying '.$sFontFileName."\n";
	copy($sFontFileToCopy, $sTcPdfFontsFolder.$sFontFileName);
}
echo $sCurrentScriptFileName.": Done !\n";



/*-----------------------------------------------------------------------------------------------*/



/**
 * Recursively delete a directory and its content
 *
 * @param $sDirToRemovePath
 *
 * @return void
 */
function rrmdir($sDirToRemovePath):void
{
	if (is_dir($sDirToRemovePath)) {
		$objects = scandir($sDirToRemovePath);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($sDirToRemovePath."/".$object) == "dir") {
					rrmdir($sDirToRemovePath."/".$object);
				} else {
					unlink($sDirToRemovePath."/".$object);
				}
			}
		}
		reset($objects);
		rmdir($sDirToRemovePath);
	}
}
