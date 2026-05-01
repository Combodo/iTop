<?php

use Combodo\iTop\Core\Email\EMailSymfony;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Component\Mime\Part\Multipart\RelatedPart;
use Symfony\Component\Mime\Part\TextPart;

class EmailSymfonyTest extends ItopTestCase
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
		$sActualBody = $this->InvokeNonPublicStaticMethod(EMailSymfony::class, 'InlineCssIntoBodyContent', [$sInputBody, $sInputCss]);

		$this->assertSame($sExpectedBody, $sActualBody);
	}

	/**
	 * Returns the parts of the AlternativePart produced by SetBody() for an HTML email.
	 *
	 * Handles both the simple case (AlternativePart at root) and the inline-images case
	 * where the root is a RelatedPart whose first child is the AlternativePart.
	 *
	 * @return AbstractPart[]
	 */
	private function GetAlternativePartsFromHtmlEmail(EMailSymfony $oEmail): array
	{
		$oSymfonyMessage = $this->GetNonPublicProperty($oEmail, 'm_oMessage');
		$oBody = $oSymfonyMessage->getBody();

		// With inline images the root is a RelatedPart; the AlternativePart is its first child.
		if ($oBody instanceof RelatedPart) {
			$oBody = $oBody->getParts()[0];
		}

		$this->assertInstanceOf(AlternativePart::class, $oBody, 'Body should be a multipart/alternative for HTML emails');

		return $oBody->getParts();
	}

	/**
	 * RFC 2046 §5.1.4: parts in multipart/alternative must be ordered from least to most preferred.
	 * Email clients display the last part they support, so text/plain must come first and text/html last.
	 *
	 * @see https://www.rfc-editor.org/rfc/rfc2046.html#section-5.1.4
	 * @since N°9574
	 */
	public function testSetBodyAlternativePartOrderForHtmlEmailIsPlainThenHtml(): void
	{
		$oEmail = new EMailSymfony();
		$oEmail->SetBody('<p>Hello there!</p>', 'text/html');

		[$oFirstPart, $oSecondPart] = $this->GetAlternativePartsFromHtmlEmail($oEmail);

		$this->assertSame('plain', $oFirstPart->getMediaSubtype(), 'First part must be text/plain (least preferred per RFC 2046)');
		$this->assertSame('html', $oSecondPart->getMediaSubtype(), 'Last part must be text/html (most preferred per RFC 2046)');
	}

	/**
	 * @dataProvider provideSetBodyPlainTextDoesNotContainCss
	 *
	 * @since N°9574
	 */
	public function testSetBodyPlainTextDoesNotContainCss(string $sHtml, ?string $sCustomStyles): void
	{
		$oEmail = new EMailSymfony();
		$oEmail->SetBody($sHtml, 'text/html', $sCustomStyles);

		// We locate the plain text part by subtype to be order-agnostic and isolate this assertion from the order bug.
		$aParts = $this->GetAlternativePartsFromHtmlEmail($oEmail);
		$oPlainPart = null;
		foreach ($aParts as $oPart) {
			if ($oPart instanceof TextPart && $oPart->getMediaSubtype() === 'plain') {
				$oPlainPart = $oPart;
				break;
			}
		}
		$this->assertNotNull($oPlainPart, 'No text/plain part found in the message');

		$sPlainText = $oPlainPart->getBody();

		$this->assertStringNotContainsString('<style>', $sPlainText, 'Style tag must not appear in plain text');
		$this->assertStringNotContainsString('color:', $sPlainText, 'CSS color rule must not appear in plain text');
		$this->assertStringNotContainsString('font-size:', $sPlainText, 'CSS font-size rule must not appear in plain text');
		$this->assertStringNotContainsString('@media', $sPlainText, 'CSS @media rule must not appear in plain text');
		$this->assertStringContainsString('Hello there!', $sPlainText, 'Actual content must be preserved in plain text');
	}

	/**
	 * The HTML part must contain the body content and the CSS inlined by Emogrifier.
	 * This guards against regressions where the wrong body (e.g. the plain-text version)
	 * would end up in the HTML part.
	 *
	 * @since N°9574
	 */
	public function testSetBodyHtmlPartContainsBodyAndInlinedCss(): void
	{
		$oEmail = new EMailSymfony();
		$oEmail->SetBody('<html><body><p>Hello there!</p></body></html>', 'text/html', 'p { color: red; }');

		$aParts = $this->GetAlternativePartsFromHtmlEmail($oEmail);

		$oHtmlPart = null;
		foreach ($aParts as $oPart) {
			if ($oPart instanceof TextPart && $oPart->getMediaSubtype() === 'html') {
				$oHtmlPart = $oPart;
				break;
			}
		}
		$this->assertNotNull($oHtmlPart, 'No text/html part found in the message');

		$sHtmlContent = $oHtmlPart->getBody();
		$this->assertStringContainsString('Hello there!', $sHtmlContent, 'HTML part must preserve the original text content');
		$this->assertStringContainsString('color: red', $sHtmlContent, 'HTML part must contain the CSS inlined by Emogrifier');
	}

	/**
	 * With inline images, SetBody() wraps the AlternativePart in a RelatedPart.
	 * The AlternativePart must still be correctly ordered (plain first, HTML last)
	 * and the plain-text part must not contain CSS.
	 *
	 * @see https://www.rfc-editor.org/rfc/rfc2046.html#section-5.1.4
	 * @since N°9574
	 */
	public function testSetBodyWithInlineImagesHasCorrectPartStructure(): void
	{
		// Anonymous subclass so we can inject a fake inline image part without a real inline image in DB
		$oEmail = new class () extends EMailSymfony {
			protected function EmbedInlineImages(string &$sBody): array
			{
				return [new DataPart('fake-image-data', 'image.png', 'image/png')];
			}
		};
		$oEmail->SetBody('<html><head><style>p { color: red; }</style></head><body><p>Hello there!</p></body></html>', 'text/html');

		$oSymfonyMessage = $this->GetNonPublicProperty($oEmail, 'm_oMessage');
		$oBody = $oSymfonyMessage->getBody();

		// Root must be a RelatedPart when inline images are present
		$this->assertInstanceOf(RelatedPart::class, $oBody, 'Root part must be multipart/related when inline images are present');

		// The AlternativePart must be the first child of the RelatedPart
		$aRelatedParts = $oBody->getParts();
		$this->assertInstanceOf(AlternativePart::class, $aRelatedParts[0], 'First child of RelatedPart must be the AlternativePart');

		// Order and CSS checks are delegated to the shared helper, which now handles RelatedPart
		[$oFirstPart, $oSecondPart] = $this->GetAlternativePartsFromHtmlEmail($oEmail);
		$this->assertSame('plain', $oFirstPart->getMediaSubtype(), 'First part must be text/plain (least preferred per RFC 2046)');
		$this->assertSame('html', $oSecondPart->getMediaSubtype(), 'Last part must be text/html (most preferred per RFC 2046)');
	}

	public function provideSetBodyPlainTextDoesNotContainCss(): array
	{
		$sCustomStyles = 'p { color: blue; font-size: 14px; }';

		return [
			'<style> tag in HTML, no custom styles' => [
				'<html><head><style>body { color: red; font-size: 12px; } @media print { p { color: black; } }</style></head><body><p>Hello there!</p></body></html>',
				null,
			],
			'<style> tag in HTML with custom styles' => [
				'<html><head><style>body { color: red; font-size: 12px; } @media print { p { color: black; } }</style></head><body><p>Hello there!</p></body></html>',
				$sCustomStyles,
			],
			'custom styles only, no <style> tag' => [
				'<html><body><p>Hello there!</p></body></html>',
				$sCustomStyles,
			],
		];
	}
}
