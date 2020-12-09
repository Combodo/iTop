<?php
/**
 * script used to sort license file (usefull for autogeneration)
 * Example: php 
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
	$licenses = iterator_to_array($licenseList);

	usort($licenses, 'sort_by_product');
	return $licenses;
}

$old_licenses = get_license_nodes($xmlFilePath);

//generate file with updated licenses
$generated_license_file_path = __DIR__."/provfile.xml";
exec("bash " . __DIR__ . "/gen-community-license.sh $iTopFolder > ". $generated_license_file_path);
$new_licenses = get_license_nodes($generated_license_file_path);
exec("rm -f ". $generated_license_file_path);

foreach ($old_licenses as $b) {
	$aProductNode = get_product_node($b);

	if (get_scope($aProductNode) !== "lib" && get_scope($aProductNode) !== "datamodels" )
	{
		$new_licenses[] = $b;
	}
}

usort($new_licenses, 'sort_by_product');

$new_dom = new DOMDocument("1.0");
$new_dom->formatOutput = true;
$root = $new_dom->createElement("licenses");
$new_dom->appendChild($root);

foreach ($new_licenses as $b) {
	$node = $new_dom->importNode($b,true);
	$root->appendChild($new_dom->importNode($b,true));
}

$new_dom->save($xmlFilePath);