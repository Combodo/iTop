<?php

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class EmailLaminasTest extends ItopTestCase
{
	public function testInlineCssIntoBodyContent(): void
	{
		$sInputBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
<p>Hello Claude Monet</p>
 
<p>&nbsp;</p>
 
<p>The ticket R-000041 had been created</p>
 
<p>&nbsp;</p>
 
<p>Public_log:</p>
 
<p></p>
<table style="width: 100%; table-layout: fixed;"><tr><td>
<div class="caselog_header">
<span class="caselog_header_date">2020-05-06 17:53:23</span> - <span class="caselog_header_user">Marguerite Duras</span>:</div>
<div class="caselog_entry_html" style="">
<p>This is a test</p>
 
<p>in the public log</p>
</div>
</td></tr></table><p>&nbsp;</p>
 
<p>Impacted CI:</p>
 
<p></p>
<ul><li>Apache VM1</li>
<li>Open ERP</li>
<li>ERP</li>
<li>Sales web site</li>
<li>Sugar CRM</li>
<li>CRM</li>
<li>itop</li>
</ul><p>&nbsp;</p>
 
<p>You can communicate and followup on <span class="object-ref " title="User Request::38"><a class="object-ref-link" href="http://192.168.56.104/itop-demo/pages/exec.php/object/edit/UserRequest/38?exec_module=itop-portal-base&amp;exec_page=index.php&amp;exec_env=production&amp;portal_id=itop-portal">R-000041</a></span></p>
 
<p>&nbsp;</p>
 
<p>Regards</p>
 
<p><strong>The IT Team</strong></p>

</body>
</html>
HTML;

		$sInputCss = <<<CSS
.caselog_header {
        padding: 3px;
        border-top: 1px solid #fff;
        background-color: #ddd;
        padding-left: 16px;
        width: 100%;
}
.caselog_header_date {
}
.caselog_header_user {
}

body {
	background-color: red;
}
CSS;

		$sExpectedBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body style="background-color: red;" bgcolor="red">
<p>Hello Claude Monet</p>
 
<p> </p>
 
<p>The ticket R-000041 had been created</p>
 
<p> </p>
 
<p>Public_log:</p>
 
<p></p>
<table style="width: 100%; table-layout: fixed;" width="100%"><tr><td>
<div class="caselog_header" style="padding: 3px; border-top: 1px solid #fff; background-color: #ddd; padding-left: 16px; width: 100%;" bgcolor="#ddd" width="100%">
<span class="caselog_header_date">2020-05-06 17:53:23</span> - <span class="caselog_header_user">Marguerite Duras</span>:</div>
<div class="caselog_entry_html" style="">
<p>This is a test</p>
 
<p>in the public log</p>
</div>
</td></tr></table><p> </p>
 
<p>Impacted CI:</p>
 
<p></p>
<ul><li>Apache VM1</li>
<li>Open ERP</li>
<li>ERP</li>
<li>Sales web site</li>
<li>Sugar CRM</li>
<li>CRM</li>
<li>itop</li>
</ul><p> </p>
 
<p>You can communicate and followup on <span class="object-ref " title="User Request::38"><a class="object-ref-link" href="http://192.168.56.104/itop-demo/pages/exec.php/object/edit/UserRequest/38?exec_module=itop-portal-base&amp;exec_page=index.php&amp;exec_env=production&amp;portal_id=itop-portal">R-000041</a></span></p>
 
<p> </p>
 
<p>Regards</p>
 
<p><strong>The IT Team</strong></p>

</body>
</html>
HTML;

		$sExpectedBody .= "\n"; // Emogriffer is always adding latest line feed, adding it in expected content !
		$sActualBody = $this->InvokeNonPublicStaticMethod(EMailLaminas::class, 'InlineCssIntoBodyContent', [$sInputBody, $sInputCss]);

		$this->assertSame($sExpectedBody, $sActualBody);
	}
}