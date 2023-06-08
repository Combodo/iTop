<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use ActionEmail;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
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
			'simple-to-oql_ignoring_bypass_notify' => [
				'EN US',
				['to' => "SELECT Person WHERE email='some.valid2@email.com'"],
				['to' => 'some.valid2@email.com'], // contact2 has 'notify' set to 'no' BUT by default when don't care
			],
			'simple-to-oql-not-bypassing-notify' => [
				'EN US',
				['to' => "SELECT Person WHERE email='some.valid2@email.com'", 'bypass_notify' => 'no'],
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
}