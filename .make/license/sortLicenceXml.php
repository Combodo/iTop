<?php
/**
 * script used to sort license file (usefull for autogeneration)
 * Example: 
 */
$iTopFolder = __DIR__ . "/../../" ;
$xmlFilePath = $iTopFolder . "setup/licenses/community-licenses.xml";
$dom = new DOMDocument();
$dom->load($xmlFilePath);
$xp = new DOMXPath($dom);

$licenseList = $xp->query('/licenses/license');
$licenses = iterator_to_array($licenseList);


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

usort($licenses, 'sort_by_product');

$newdom = new DOMDocument("1.0");
$newdom->formatOutput = true;
$root = $newdom->createElement("licenses");
$newdom->appendChild($root);
foreach ($licenses as $b) {
    $node = $newdom->importNode($b,true);
    $root->appendChild($newdom->importNode($b,true));
}

$newdom->save($xmlFilePath);