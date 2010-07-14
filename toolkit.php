<?php

echo "<h1>iTop</h1>";

$sStyle = "
	border: 1px dashed #CCC;
	background: #CCF;
	padding:1.5em;
";
echo "<div style=\"$sStyle\">\n";
echo "<h2>Shortcuts</h2>\n";
echo "<a href=\"./pages/UI.php\">Main page</a></br>\n";
echo "<a href=\"./pages/csvimport.php\">CSV import (shortcut)</a></br>\n";
echo "<a href=\"./pages/UniversalSearch.php\">Universal search (shortcut)</a></br>\n";
echo "<h2>Itop customization</h2>\n";
echo "Please contact the iTop support team</br>\n";
echo "<h2>Web services</h2>\n";
echo "<a href=\"./webservices/soapserver.php\">Available functions</a></br>\n";
echo "<a href=\"./webservices/itop.wsdl.php\">WSDL (dynamically generated)</a></br>\n";
echo "<a href=\"./webservices/check_sla_for_tickets.php\">Check SLA for tickets</a></br>\n";
echo "</div>\n";


echo "<h1>phpinfo()</h1>";


$sStyle = "
	border: 1px dashed #CCC;
	background: #FCC;
	padding:1.5em;
";
echo "<div style=\"$sStyle\">\n";
phpinfo();
echo "</div>\n";
?>
