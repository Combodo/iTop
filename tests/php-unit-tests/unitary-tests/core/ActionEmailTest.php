<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use ActionEmail;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;
use utils;
use Dict;

/**
 * @covers \ActionEmail
 */
class ActionEmailTest extends ItopDataTestCase
{
	/**
	 * @inheritDoc
	 */
	const CREATE_TEST_ORG = true;

	/** @var \ActionEmail|null Temp ActionEmail created for tests */
	protected static $oActionEmail = null;
	
	/** @var \ormDocument|null Temp ormDocument for tests */
	protected static $oDocument = null;

	/** @var \UserRequest|null Temp ormDocument for tests */
	protected static $oUserRequest = null;
	
	/** @var string[] Dict formatted message, because the Dict class is not available in providers */ 
	protected static $aWarningMessages;

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('application/Html2Text.php');

		static::$oActionEmail = MetaModel::NewObject('ActionEmail', [
			'name' => 'Test action',
			'status' => 'disabled',
			'from' => 'unit-test@openitop.org',
			'subject' => 'Test subject',
			'body' => 'Test body',
		]);

		$sHtml =
<<<HTML
<body>
	<table data-something-that-would-be-removed-by-the-sanitizer-through-ckeditor-but-that-will-stay-with-the-template="bar">
		<tr><td>Formatted eMail</td></tr>
		<tr><td>\$content\$</td></tr>
</body>
HTML
		;

		static::$oActionEmail->DBInsert();
		static::$oDocument = new \ormDocument($sHtml, 'text/html', 'sample.html');
		static::$oUserRequest =  MetaModel::NewObject('UserRequest', [
			'title' => 'Test UserRequest',
			'description' => '<p>Multi-line<br/>description</p>'
		]);

		static::$aWarningMessages = [
			'warning-missing-content' => Dict::Format('ActionEmail:content_placeholder_missing', '$content$', Dict::S('Class:ActionEmail/Attribute:body')) 
		];
	}

	/**
	 * @covers \ActionEmail::GenerateIdentifierForHeaders
	 * @dataProvider GenerateIdentifierForHeadersProvider
	 * @throws \Exception
	 */
	public function testGenerateIdentifierForHeaders(string $sHeaderName)
	{
		// Retrieve object
		$oObject = MetaModel::GetObject('Organization', $this->getTestOrgId(), true, true);
		$sObjClass = get_class($oObject);
		$sObjId = $oObject->GetKey();

		try {
			$sTestedIdentifier = $this->InvokeNonPublicMethod('\ActionEmail', 'GenerateIdentifierForHeaders', static::$oActionEmail, [$oObject, $sHeaderName]);
		} catch (Exception $oException) {
			$sTestedIdentifier = null;
		}

		$sAppName = utils::Sanitize(ITOP_APPLICATION_SHORT, '', utils::ENUM_SANITIZATION_FILTER_VARIABLE_NAME);
		$sEnvironmentHash = MetaModel::GetEnvironmentId();

		switch ($sHeaderName) {
			case ActionEmail::ENUM_HEADER_NAME_MESSAGE_ID:
				// Note: For this test we can't use the more readable sprintf test as the generated timestamp will never be the same as the one generated during the call of the tested method
				//   $sTimestamp = microtime(true /* get as float*/);
				//   $sExpectedIdentifier = sprintf('%s_%s_%d_%f@%s.openitop.org', $sAppName, $sObjClass, $sObjId, $sTimestamp, $sEnvironmentHash);
				$this->assertEquals(1, preg_match('/'.$sAppName.'_'.$sObjClass.'_'.$sObjId.'_[\d]+\.[\d]+@'.$sEnvironmentHash.'.openitop.org/', $sTestedIdentifier), "Identifier doesn't match regexp for header $sHeaderName, got $sTestedIdentifier");
				break;

			case ActionEmail::ENUM_HEADER_NAME_REFERENCES:
				$sExpectedIdentifier = '<'.sprintf('%s_%s_%d@%s.openitop.org', $sAppName, $sObjClass, $sObjId, $sEnvironmentHash).'>';
				$this->assertEquals($sExpectedIdentifier, $sTestedIdentifier);
				break;

			default:
				$sExpectedIdentifier = null;
				$this->assertEquals($sExpectedIdentifier, $sTestedIdentifier);
				break;
		}
	}

	public function GenerateIdentifierForHeadersProvider()
	{
		return [
			'Message-ID' => ['Message-ID'],
			'References' => ['References'],
			'IncorrectHeaderName' => ['IncorrectHeaderName'],
		];
	}

	/**
	 * @dataProvider prepareMessageContentProvider
	 */
	public function testPrepareMessageContent($sCurrentUserLanguage, $aActionFields, $aFieldsToCheck)
	{
		\Dict::SetUserLanguage($sCurrentUserLanguage);
		$aContext = ['this->object()' => static::$oUserRequest];
		$oActionEmail = MetaModel::NewObject('ActionEmail', [
			'name' => 'Test action',
			'status' => 'disabled',
			'from' => 'unit-test@openitop.org',
			'subject' => 'Test subject',
			'body' => 'Test body',
		]);
		foreach($aActionFields as $sCode => $value) {
			if ($sCode === 'html_template') {
				// special case since the data provider cannot create ormDocument objects
				$oActionEmail->Set($sCode, static::$oDocument);
			} else {
				$oActionEmail->Set($sCode, $value);
			}

		}
		$oActionEmail->DBInsert();
		
		$oOrg = $this->CreateOrganization('testPrepareMessageContent');
		
		$oContact1 = MetaModel::NewObject('Person', [
			'name' => 'Person 1',
			'first_name' => 'PrepareMessageContent',
			'org_id' => $oOrg->GetKey(),
			'email' => 'some.valid@email.com',
			'notify' => 'yes',
		]);
		$oContact1->DBInsert();
		
		
		$oContact2 = MetaModel::NewObject('Person', [
			'name' => 'Person 2',
			'first_name' => 'PrepareMessageContent',
			'org_id' => $oOrg->GetKey(),
			'email' => 'some.valid2@email.com',
			'notify' => 'no',
		]);
		$oContact2->DBInsert();
		
		$oLog = null;
		
		$aEmailContent = $this->InvokeNonPublicMethod('\ActionEmail', 'PrepareMessageContent', $oActionEmail, [$aContext, &$oLog]);
		// Normalize the content of the body to simplify the comparison, useful when status == test
		$aEmailContent['body'] = preg_replace('/title="[^"]+"/', 'title="****"', $aEmailContent['body']);
		$aEmailContent['body'] = preg_replace('/class="object-ref-link" href="[^"]+"/', 'class="object-ref-link" href="****"', $aEmailContent['body']);
		$aEmailContent['body'] = preg_replace('/References: <[^>]+>/', 'References: ****', $aEmailContent['body']);
		foreach($aFieldsToCheck as $sCode => $expectedValue) {
			$this->assertEquals($expectedValue, $aEmailContent[$sCode]);
		}
	}
	
	public function prepareMessageContentProvider()
	{
		return [
			'subject-no-placeholder' => [
				'EN US',
				['subject' => 'This is a test'],
				['subject' => 'This is a test'],
			],
			'subject-with-placeholder' => [
				'EN US',
				['subject' => 'Ticket "$this->title$" created'],
				['subject' => 'Ticket "Test UserRequest" created'],
			],
			'simple-to-oql' => [
				'EN US',
				['to' => "SELECT Person WHERE email='some.valid@email.com'"],
				['to' => 'some.valid@email.com'],
			],
			'simple-to-oql_ignoring_ignore_notify' => [
				'EN US',
				['to' => "SELECT Person WHERE email='some.valid2@email.com'"],
				['to' => 'some.valid2@email.com'], // contact2 has 'notify' set to 'no' BUT by default when don't care
			],
			'simple-to-oql-not-bypassing-notify' => [
				'EN US',
				['to' => "SELECT Person WHERE email='some.valid2@email.com'", 'ignore_notify' => 'no'],
				['to' => ''], // contact2 has 'notify' set to 'no'
			],
			'subject-with-localized-placeholder (default behavior)' => [
				'EN US',
				['subject' => 'Ticket in state "$this->label(status)$" created'],
				['subject' => 'Ticket in state "New" created'],
			],
			'subject-with-localized-placeholder (default behavior 2)' => [
				'FR FR',
				['subject' => 'Ticket in state "$this->label(status)$" created'],
				['subject' => 'Ticket in state "Nouveau" created'],
			],
			'subject-with-localized-placeholder (new behavior)' => [
				'FR FR',
				['subject' => 'Ticket in state "$this->label(status)$" created', 'language' => 'EN US'],
				['subject' => 'Ticket in state "New" created'],
			],
			'simple-body-with-placeholder' => [
				'EN US',
				['body' => '<p>Ticket "$this->title$" created.</p>'],
				['body' => '<p>Ticket "Test UserRequest" created.</p>'],
			],
			'simple-body-with-placeholder-TEST-mode' => [
				'EN US',
				['body' => '<p>Ticket "$this->title$" created.</p>', 'status' => 'test'],
				['body' => 
<<<HTML
<p>Ticket "Test UserRequest" created.</p><div style="border: dashed;">
<h1>Testing email notification <span class="object-ref "  title="****"><a class="object-ref-link" href="****">Test action</a></span></h1>
<p>The email should be sent with the following properties
<ul>
<li>TO: </li>
<li>CC: </li>
<li>BCC: </li>
<li>From: unit-test@openitop.org</li>
<li>Reply-To: </li>
<li>References: ****</li>
</ul>
</p>
</div>

HTML
					,
					'subject' => 'TEST[Test subject]',
				],
			],
			'more-complex-body-and-title-with-placeholder' => [
				'EN US',
				['subject' => 'Ticket "$this->title$" created'],
				['subject' => 'Ticket "Test UserRequest" created'],
				['body' => '<h1>Ticket "$this->title$" created.</h1><p>Description: $this->html(description)</p>'],
				['body' => '<h1>Ticket "Test UserRequest" created.</h1><p>Description: <p>Multi-line<br/>description</p></p>'],
			],
			'simple-body-with-placeholder_and_template' => [
				'EN US',
				['body' => '<p>Ticket "$this->title$" created.</p>', 'html_template' => true],
				['body' => 
<<<HTML
<body>
	<table data-something-that-would-be-removed-by-the-sanitizer-through-ckeditor-but-that-will-stay-with-the-template="bar">
		<tr><td>Formatted eMail</td></tr>
		<tr><td><p>Ticket "Test UserRequest" created.</p></td></tr>
</body>
HTML
				],
			],
		];
	}

	/**
	 * @dataProvider doCheckToWriteProvider
	 * @param string $sBody
	 * @param string $sHtmlTemplate
	 * @param string[] $aExpectedWarnings
	 */
	public function testDoCheckToWrite(string $sBody, ?string $sHtmlTemplate, $expectedWarnings)
	{
		$oActionEmail = new ActionEmail();
		// Set mandatory fields
		$oActionEmail->Set('name', 'test');
		$oActionEmail->Set('subject', 'Ga Bu Zo Meu');
		// Set the fields for testing
		$oActionEmail->Set('body', $sBody);
		if ($sHtmlTemplate !== null) {
			$oDoc = new \ormDocument($sHtmlTemplate, 'text/html', 'template.html');
			$oActionEmail->Set('html_template', $oDoc);
		}
		$oActionEmail->DoCheckToWrite();
		$aWarnings = $this->GetNonPublicProperty($oActionEmail, 'm_aCheckWarnings');
		if ($expectedWarnings === null) {
			$this->assertEquals($aWarnings, $expectedWarnings);
		} else {
			// The warning messages are localized, but the provider functions does not
			// have access to the Dict class, so let's replace the value given by the 
			// provider by a statically precomputed and localized message
			foreach($expectedWarnings as $index => $sMessageKey) {
				$expectedWarnings[$index] = static::$aWarningMessages[$sMessageKey];
			}
			$this->assertEquals($aWarnings, $expectedWarnings);
		}
	}

	public function doCheckToWriteProvider()
	{
		return [
			'no warnings' => [
				'<p>Some text here</p>',
				'<div>$content$</div>',
				null
			],			
			'$content$ missing' => [
				'<p>Some text here</p>',
				'<div>no placeholder</div>',
				[ 'warning-missing-content' ]
			],
		];	
	}
}