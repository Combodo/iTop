<?php

namespace Combodo\iTop\Test\UnitTest\Core;

class QueryBuilderExpressionsTest extends \Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase
{
	const CREATE_TEST_ORG = true;

	/**
	 * @inheritDoc
	 */
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__."/Delta/all-attributes.xml";
	}

	public function testICanWriteAndReadAnyTypeOfAttribute()
	{
		$oTagA = \MetaModel::NewObject(\TagSetFieldData::GetTagDataClassName('TestObject', 'tagset'), [
			'code'        => 'tagA',
			'label'       => 'Tag A',
			'description' => 'Tag known as "A"'
		]);
		$oTagA->DBInsert();

		$sTargetClass = 'TestObject';
		$aValues = [
			'name'           => [
				'value' => 'Tadam!',
				'type'  => 'php:integer',
			],
			'org_id'         => [
				'value' => $this->getTestOrgId(),
				'type'  => 'php:integer',
			],
			'parent_id'      => [
				'value' => 0,
				'type'  => 'php:integer',
			],
			'caselog'        => [
				'value' => '{"items":[{"message":"Hi folks!"}]}',
				'type'  => 'ormCaseLog',
			],
			'date'           => [
				'value' => '2023-10-04',
				'type'  => 'php:string',
			],
			'date_time'      => [
				'value' => '2023-10-04 17:11:54',
				'type'  => 'php:string',
			],
			'deadline'       => [
				'value' => '2023-10-04 17:11:54',
				'type'  => 'php:string',
			],
			'decimal'        => [
				'value' => '123456.78',
				'type'  => 'php:string',
			],
			'duration'       => [
				'value' => 3660, // One hour and one minute
				'type'  => 'php:integer',
			],
			'email'          => [
				'value' => 'john.foo@company.com',
				'type'  => 'php:string',
			],
			'encrypted'      => [
				'value' => 'secret...inDB!',
				'type'  => 'php:string',
			],
			'password'       => [
				'value' => 'abc123',
				'type'  => 'php:string',
			],
			'onewaypassword' => [
				'value' => 'az"e(t-yè',
				'type'  => 'php:string',
			],
			'enum'           => [
				'value' => 'yes',
				'type'  => 'php:string',
			],
			'enumset'        => [
				'value' => 'low|high',
				'type'  => 'php:string',
			],
			'file'           => [
				'value' => '{"data":"blahblah","mimetype":"text/plain","filename":"trash.txt", "downloads_count":0}',
				'type'  => 'ormDocument',
			],
			'html'           => [
				'value' => '<p><b>Hello</b>&nbsp;world!</p>',
				'type'  => 'php:string',
			],
			'image'          => [
				'value' => '{"data":"notanimage-sowhat?","mimetype":"image/png","filename":"trash.png", "downloads_count":0}',
				'type'  => 'ormDocument',
			],
			'integer'        => [
				'value' => 123,
				'type'  => 'php:integer',
			],
			'ip_address'     => [
				'value' => '192.158.1.38',
				'type'  => 'php:string',
			],
			'percentage'     => [
				'value' => 49.3,
				'type'  => 'php:double',
			],
			'phone'          => [
				'value' => '+33476123456',
				'type'  => 'php:string',
			],
			'status'         => [
				'value' => 'investigation',
				'type'  => 'php:string',
			],
			'stopwatch'      => [
				'value' => null,
				'type'  => 'ormStopWatch',
			],
			'tagset'         => [
				'value' => 'tagA',
				'type'  => 'php:string',
			],
			'text'           => [
				'value' => 'Hello world!',
				'type'  => 'php:string',
			],
			'long_text'      => [
				'value' => 'Hello world!',
				'type'  => 'php:string',
			],
			'url'            => [
				'value' => 'http://www.combodo.com/void',
				'type'  => 'php:string',
			],
			'oql'            => [
				'value' => 'SELECT Organization',
				'type'  => 'php:string',
			],
			'boolean'        => [
				'value' => true,
				'type'  => 'php:boolean',
			],
		];

		$aValuesToSet = [];
		foreach ($aValues as $sAttCode => $aValueData) {
			if (substr($aValueData['type'], 0, 4) == 'php:') {
				$aValuesToSet[$sAttCode] = $aValueData['value'];
			} else {
				$oAttDef = \MetaModel::GetAttributeDef($sTargetClass, $sAttCode);
				$oJSONObject = is_null($aValueData['value']) ? null : json_decode($aValueData['value'], false);
				$aValuesToSet[$sAttCode] = $oAttDef->FromJSONToValue($oJSONObject);
			}
		}


		// Test that I can write without any error (such as malformed SQL query, that would throw an exception)
		$oObject = \MetaModel::NewObject($sTargetClass, $aValuesToSet);
		$oObject->DBInsert();

		// Test that I can read without any error (such as malformed SQL query, that would throw an exception)
		$iTestObject = $oObject->GetKey();
		$oObjectFromDB = \MetaModel::GetObject($sTargetClass, $iTestObject);

		// Test that each value matches the original value
		foreach ($aValues as $sAttCode => $aValueData) {
			$oAttDef = \MetaModel::GetAttributeDef($sTargetClass, $sAttCode);
			static::assertTrue($oAttDef->Equals(
				$oObject->Get($sAttCode),
				$oObjectFromDB->Get($sAttCode)
			), "Value of attribute '$sAttCode' has been altered after DB write + read");
		}

		// Create an indirection
		$oSubObject = \MetaModel::NewObject('SubObject', [
			'name'          => 'subobject for '.$iTestObject,
			'testobject_id' => $iTestObject
		]);
		$oSubObject->DBInsert();

		// Test that it can be read from the DB
		$iSubObject = $oSubObject->GetKey();
		$oSubObjectFromDB = \MetaModel::GetObject('SubObject', $iSubObject);

		// Test that each external field value matches the original value
		foreach ($aValues as $sAttCode => $aValueData) {
			$sExtFieldAttCode = '_'.$sAttCode;
			$oAttDef = \MetaModel::GetAttributeDef($sTargetClass, $sAttCode);
			static::assertTrue($oAttDef->Equals($oObject->Get($sAttCode), $oSubObjectFromDB->Get($sExtFieldAttCode)), "Value of attribute '$sAttCode' not correctly read as an external key");
		}
	}
}