<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
require_once '../../approot.inc.php';

require_once(APPROOT.'/application\utils.inc.php');
$index = 0;
function testSanitize ($sValue, $sType, &$index ){
	$sDefaultVal = '!defaultVal!';
	$sValueEscapedJs = str_replace('"', '\"', $sValue);
	$sSanitizedValue = utils::Sanitize($sValue, $sDefaultVal, $sType);

	echo <<<HTML
<tr id="test{$index}">
	<td>{$sType}</td>
	<td>{$sValue}</td>
	<td class="sanitized_php">{$sSanitizedValue}</td>
	<td class="sanitized_js"></td>
	<td class="hasDiff"></td>
</tr>
<script>
var parentTr = $("tr#test{$index}"),
	sanitizedPhp = parentTr.find("td.sanitized_php").text(),
	sanitizedJs = CombodoSanitizer.Sanitize("{$sValueEscapedJs}","{$sDefaultVal}","{$sType}");

parentTr.find("td.sanitized_js").text(sanitizedJs);

if (sanitizedJs !== sanitizedPhp) {
	console.error("difference detected !", "{$sValueEscapedJs}", '{$sType}', sanitizedPhp, sanitizedJs);
	parentTr.find("td.hasDiff").text("KO");
}
</script>
HTML;

	$index++;
}

$aValues = array(
	"test",
	"t;e-s_t$",
	"123test",
	"\"('èé&=hcb test",
	"<div>Hello!</div>",
	"*-+7464+guigez cfuze",
	"",
	"()=°²€",
	"éèç",
);

$aTypes = array(
	'context_param',
	'element_identifier',
	'field_name',
	'integer',
	'parameter',
	'string',
	'transaction_id',
	'variable_name', // introduced in 3.0.0
);

?>
<!DOCTYPE>
<html>
<head>
<script type="text/javascript" src="../../js/jquery.min.js"></script>
<script type="text/javascript" src="../../js/utils.js"></script>
<style>
table, tr, td {
	padding: 3px 10px;
	border: 1px solid lightgrey;
	border-collapse: collapse;
}

td.hasDiff {
	color: red;
}

thead {
	font-weight: bold;
}
</style>
</head>
<body>
<table>
	<thead>
	<tr>
		<td>Type</td>
		<td>chaine initiale</td>
		<td>chaine sanitize by php</td>
		<td>chaine sanitize by js</td>
		<td> status test</td>
	</tr>
	</thead>
<?php

foreach ($aTypes as $sType) {
	foreach ($aValues as $sValue) {
		testSanitize($sValue, $sType, $index);
	}
}
?></table>
</body>
</html>
