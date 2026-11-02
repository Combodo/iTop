<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\AttributeDefinition;

use Combodo\iTop\Core\AttributeDefinition\AttributeText;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * Tests for HTML text change snapshots.
 *
 * @since 3.3.0 N°1365 - Test HTML history snapshots
 */
class AttributeTextTest extends ItopDataTestCase
{
	/**
	 * @covers \Combodo\iTop\Core\AttributeDefinition\AttributeText::GetChangeRecordAdditionalData
	 * @since 3.3.0 N°1365 - Store previous and current HTML snapshots
	 */
	public function testGetChangeRecordAdditionalDataStoresHtmlSnapshots(): void
	{
		$oAttribute = MetaModel::GetAttributeDef('UserRequest', 'description');
		$this->assertInstanceOf(AttributeText::class, $oAttribute);
		$this->assertSame('html', $oAttribute->GetFormat());

		$oChangeOp = MetaModel::NewObject('CMDBChangeOpSetAttributeHTML');
		$this->InvokeNonPublicMethod(AttributeText::class, 'GetChangeRecordAdditionalData', $oAttribute, [
			$oChangeOp,
			MetaModel::NewObject('UserRequest'),
			'<p>Previous version</p>',
			'<p>New version</p>',
		]);

		$this->assertSame('<p>Previous version</p>', $oChangeOp->Get('prevdata'));
		$this->assertSame('<p>New version</p>', $oChangeOp->Get('newvalue'));
	}

	/**
	 * @covers \Combodo\iTop\Core\AttributeDefinition\AttributeText::GetChangeRecordAdditionalData
	 * @since 3.3.0 N°1365 - Store empty values as explicit HTML snapshots
	 */
	public function testGetChangeRecordAdditionalDataStoresNullHtmlAsEmptySnapshot(): void
	{
		$oAttribute = MetaModel::GetAttributeDef('UserRequest', 'description');
		$oChangeOp = MetaModel::NewObject('CMDBChangeOpSetAttributeHTML');

		$this->InvokeNonPublicMethod(AttributeText::class, 'GetChangeRecordAdditionalData', $oAttribute, [
			$oChangeOp,
			MetaModel::NewObject('UserRequest'),
			'<p>Previous version</p>',
			null,
		]);

		$this->assertSame('', $oChangeOp->Get('newvalue'));
	}
}
