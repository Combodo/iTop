<?php
/**
 * script used to sort license file (useful for autogeneration)
 *
 * Requirements :
 *  * bash (on Windows, use Git Bash)
 *  * composer (if you use the phar version, mind to create a `Composer` alias !)
 *  * JQ command
 *    to install on Windows :
 *       `curl -L -o /usr/bin/jq.exe https://github.com/stedolan/jq/releases/latest/download/jq-win64.exe`
 *    this is a Windows port : https://stedolan.github.io/jq/
 *
 * Licenses sources :
 *  * `composer licenses --format json` (see https://getcomposer.org/doc/03-cli.md#licenses)
 *  * keep every existing nodes with `/licenses/license[11]/product/@scope` not in ['lib', 'datamodels']
 *    ⚠ If licenses were added manually, they might be removed by this tool ! Be very careful to check for the result before pushing !
 *
 * To launch, check requirements and run `php updateLicenses.php`
 * The target license file path is in `$xmlFilePath`
 */

$iTopFolder = __DIR__ . "/../../" ;
$xmlFilePath = $iTopFolder . "setup/licenses/community-licenses.xml";

function get_scope($product_node)
{
	$scope = $product_node->getAttribute("scope");

	if ($scope === "")
	{   //put iTop first
		return "aaaaaaaaa";
	}
	return $scope;
}

function get_product_node($license_node)
{
	foreach ($license_node->childNodes as $child)
	{
		if (is_a($child, 'DomElement') && $child->tagName === "product")
		{
			return $child;
		}
	}
	return null;
}

function sort_by_product($a, $b)
{
	$aProductNode = get_product_node($a);
	$bProductNode = get_product_node($b);

	$res = strcmp(get_scope($aProductNode), get_scope($bProductNode));
	if ($res !== 0)
	{
		return $res;
	}
	//sort on node product name
    return strcmp($aProductNode->nodeValue, $bProductNode->nodeValue);
}

function get_license_nodes($file_path)
{
	$dom = new DOMDocument();
	$dom->load($file_path);
	$xp = new DOMXPath($dom);

	$licenseList = $xp->query('/licenses/license');
	$licenses    = iterator_to_array($licenseList);

	usort($licenses, 'sort_by_product');

	return $licenses;
}

/** @noinspection SuspiciousAssignmentsInspection */
function fix_product_name(DOMNode &$oProductNode)
{
	$sProductNameOrig = $oProductNode->nodeValue;

	// sample : `C:\Dev\wamp64\www\itop-27\.make\license/../..//lib/symfony/polyfill-ctype`
	$sProductNameFixed = remove_dir_from_string($sProductNameOrig, 'lib/');

	// sample : `C:\Dev\wamp64\www\itop-27\.make\license/../..//datamodels/2.x/authent-cas/vendor/apereo/phpcas`
	$sProductNameFixed = remove_dir_from_string($sProductNameFixed, 'vendor/');

	$oProductNode->nodeValue = $sProductNameFixed;
}

function remove_dir_from_string($sString, $sNeedle)
{
	if (strpos($sString, $sNeedle) === false) {
		return $sString;
	}

	$sStringTmp   = strstr($sString, $sNeedle);
	$sStringFixed = str_replace($sNeedle, '', $sStringTmp);

	// DEBUG trace O:)
//	echo "$sNeedle = $sString => $sStringFixed\n";

	return $sStringFixed;
}

$old_licenses = get_license_nodes($xmlFilePath);

//generate file with updated licenses
$generated_license_file_path = __DIR__."/provfile.xml";
echo "- Generating licences...";
exec("bash ".__DIR__."/gen-community-license.sh $iTopFolder > ".$generated_license_file_path);
echo "OK!\n";

echo "- Get licenses nodes...";
$new_licenses = get_license_nodes($generated_license_file_path);
unlink($generated_license_file_path);

foreach ($old_licenses as $b) {
	$aProductNode = get_product_node($b);

	if (get_scope($aProductNode) !== "lib" && get_scope($aProductNode) !== "datamodels") {
		$new_licenses[] = $b;
	}
}

usort($new_licenses, 'sort_by_product');
echo "OK!\n";

echo "- Overwritting Combodo license file...";
$new_dom = new DOMDocument("1.0");
$new_dom->formatOutput = true;
$root = $new_dom->createElement("licenses");
$new_dom->appendChild($root);

foreach ($new_licenses as $b) {
	$node = $new_dom->importNode($b, true);

	// N°3870 fix when running script in Windows
	// fix should be in gen-community-license.sh but it is easier to do it here !
	if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
		$oProductNodeOrig = get_product_node($node);
		fix_product_name($oProductNodeOrig);
	}

	$root->appendChild($node);
}

$new_dom->save($xmlFilePath);
echo "OK!\n";