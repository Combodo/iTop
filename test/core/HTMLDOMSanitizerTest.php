<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use HTMLDOMSanitizer;

class HTMLDOMSanitizerTest extends ItopTestCase
{

	/**
	 * @dataProvider DoSanitizeProvider
	 */
	public function testDoSanitize($sHTML)
	{
		$oSanitizer = new HTMLDOMSanitizer();
		$sRes = $oSanitizer->DoSanitize($sHTML);

		$this->debug($sRes);
		$this->assertEquals('<div>
<p><span>Test mit nur Unterschrift</span></p>
<p><span>Kein bild</span></p>
<p><span> </span></p>
<p><span> </span></p>
<p><b><span style=\'font-size:10.0pt;font-family:"Arial",sans-serif;color:black\'>Christel Dedman</span></b><span></span></p>
<p><b><span style=\'font-size:10.0pt;font-family:"Arial",sans-serif;color:black\'> </span></b><span></span></p>
<p><b><span style=\'font-size:10.0pt;font-family:"Arial",sans-serif;color:black\'>Financial Reporting Manager | G4S International Logistics (Germany) GmbH</span></b></p>
<p><b><span style=\'font-size:10.0pt;font-family:"Arial",sans-serif;color:black\'> </span></b></p>
<p><span> </span></p>
<p><span style=\'font-size:10.0pt;font-family:"Arial",sans-serif;color:black\'>Rathenaustrasse 53, 63263 Neu </span></p>
</div>', $sRes);
	}

	public function DoSanitizeProvider()
	{
		return array(
			array(<<< EOF
<html><head><meta http-equiv="Content-Type" content="text/html; charset=us-ascii"><meta name="Generator" content="Microsoft Word 15 (filtered medium)"><style><!--
/* Font Definitions */
@font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
/* Style Definitions */
p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;
	mso-fareast-language:EN-US;}
a:link, span.MsoHyperlink
	{mso-style-priority:99;
	color:#0563C1;
	text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
	{mso-style-priority:99;
	color:#954F72;
	text-decoration:underline;}
p.msonormal0, li.msonormal0, div.msonormal0
	{mso-style-name:msonormal;
	mso-margin-top-alt:auto;
	margin-right:0cm;
	mso-margin-bottom-alt:auto;
	margin-left:0cm;
	font-size:12.0pt;
	font-family:"Times New Roman",serif;}
span.EmailStyle18
	{mso-style-type:personal;
	font-family:"Calibri",sans-serif;
	color:windowtext;}
span.EmailStyle19
	{mso-style-type:personal;
	font-family:"Calibri",sans-serif;
	color:#1F497D;}
span.EmailStyle20
	{mso-style-type:personal;
	font-family:"Calibri",sans-serif;
	color:#1F497D;}
span.EmailStyle21
	{mso-style-type:personal-compose;
	font-family:"Calibri",sans-serif;
	color:windowtext;}
.MsoChpDefault
	{mso-style-type:export-only;
	font-size:10.0pt;}
@page WordSection1
	{size:612.0pt 792.0pt;
	margin:72.0pt 72.0pt 72.0pt 72.0pt;}
div.WordSection1
	{page:WordSection1;}
--></style></head><body lang="EN-GB" link="#0563C1" vlink="#954F72"><div class="WordSection1"><p class="MsoNormal"><span lang="DE">Test mit nur Unterschrift</span></p><p class="MsoNormal"><span lang="DE">Kein bild</span></p><p class="MsoNormal"><span lang="DE"> </span></p><p class="MsoNormal"><span lang="DE"> </span></p><p class="MsoNormal"><b><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">Christel Dedman</span></b><span lang="EN-US"></span></p><p class="MsoNormal"><b><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"> </span></b><span lang="EN-US"></span></p><p class="MsoNormal"><b><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">Financial Reporting Manager | G4S International Logistics (Germany) GmbH</span></b></p><p class="MsoNormal"><b><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"> </span></b></p><p class="MsoNormal"><span lang="EN-US"> </span></p><p class="MsoNormal"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">Rathenaustrasse 53, 63263 Neu – Isenburg, Office Tel: +49 (0) 6102 / 4393 623 | Fax: +49 (0) 6102 / 4393 619 | Mobile: +49 (0) 172 / 5687367</span><span lang="EN-US"></span></p><p class="MsoNormal" style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">Email:</span><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif">  </span><a href="mailto:christel.dedman@g4si.com"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif">christel.dedman@g4si.com</span></a><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif">  <b>|</b>  <span style="color:black">Web site</span>:  </span><a href="http://www.g4si.com/" target="_blank"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif">www.g4si.com</span></a><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif"> / </span><a href="http://www.g4s.com/"><span lang="EN-US" style="font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif">www.g4s.com</span></a><span lang="EN-US" style="font-size:12.0pt;font-family:&quot;Times New Roman&quot;,serif"></span></p><p class="MsoNormal" style="margin-bottom:12.0pt"><span lang="EN-US" style="font-size:9.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">Disclaimer: G4S International Logistics (G4Si) is a division of the G4S plc group of companies. This communication contains information which may be confidential, personal and/or privileged. It is for the exclusive use of the intended recipient(s).<br>If you are not the intended recipient(s), please note that any distribution, forwarding, copying or use of this communication or the information in it is strictly prohibited. Any personal views expressed in this e-mail are those of the individual sender and G4Si does not endorse or accept responsibility for them. Prior to taking any action based upon this e-mail message, you should seek appropriate confirmation of its authenticity.</span><span lang="EN-US" style="font-size:12.0pt;font-family:&quot;Times New Roman&quot;,serif;color:black"></span></p><p class="MsoNormal"> </p></div></body></html>

<br>
<div><font size="1"><font face="Verdana" color="green" style="line-height:21px;background-color:rgb(255,255,255)"><br></font></font></div><font size="1"><font face="Verdana" color="green" style="line-height:21px;background-color:rgb(255,255,255)">Please consider the environment before printing this email.</font><font face="Verdana" color="gray" style="line-height:21px;background-color:rgb(255,255,255)"><br>******************************<wbr>******************************<wbr>*********<br>This communication may contain information which is confidential, personal and/or privileged. It is for the exclusive use of the intended recipient(s).<br>If you are not the intended recipient(s), please note that any distribution, forwarding, copying or use of this communication or the information in it is strictly prohibited. If you have received it in error please contact the sender immediately by return e-mail. Please then delete the e-mail and any copies of it and do not use or disclose its contents to any person.<br>Any personal views expressed in this e-mail are those of the individual sender and the company does not endorse or accept responsibility for them. Prior to taking any action based upon this e-mail message, you should seek appropriate confirmation of its authenticity.<br>This message has been checked for viruses on behalf of the company.<br>******************************<wbr>******************************<wbr>*********</font><font face="Verdana" color="gray" style="line-height:21px;background-color:rgb(255,255,255)"><br><br></font></font>
EOF
			),
		);
	}


}
