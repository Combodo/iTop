<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Test\UnitTest\Module\ItopAttachment;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class TestAttachment extends ItopDataTestCase
{
	private string $sAddAttachmentName;
	private string $sRemoveAttachmentName;
	const CREATE_TEST_ORG = true;

	public function setUp(): void
	{
		parent::setUp();
		//static::$DEBUG_UNIT_TEST = true;
	}

	public function testAddAttachment()
	{
		$this->sAddAttachmentName = '';
		$this->sRemoveAttachmentName = '';

		$_REQUEST['transaction_id'] = 'test_transaction';
		$_REQUEST['attachment_plugin'] = 'in_form';

		$oDocument = new \ormDocument('Test', 'text/plain', 'test.txt');

		$this->EventService_RegisterListener(EVENT_ADD_ATTACHMENT_TO_OBJECT, [$this, 'OnAddAttachment']);
		$this->EventService_RegisterListener(EVENT_REMOVE_ATTACHMENT_FROM_OBJECT, [$this, 'OnRemoveAttachment']);

		$oAttachment = MetaModel::NewObject('Attachment', [
			'item_class' => 'UserRequest',
			'temp_id' => 'test_transaction',
			'contents' => $oDocument,
		]);

		$oAttachment->DBInsert();
		$oTicket = $this->CreateTicket(1);

		$_REQUEST['removed_attachments'] = [$oAttachment->GetKey()];
		$this->InvokeNonPublicStaticMethod(\AttachmentPlugIn::class, 'UpdateAttachments', [$oTicket]);

		$this->assertEquals('test.txt', $this->sAddAttachmentName);
		$this->assertEquals('test.txt', $this->sRemoveAttachmentName);
	}

	public function OnAddAttachment(EventData $oData)
	{
		$this->debug('OnAddAttachment');
		$this->assertEquals('UserRequest', get_class($oData->Get('object')));
		$oAttachment = $oData->Get('attachment');
		/** @var \ormDocument $oDocument */
		$oDocument = $oAttachment->Get('contents');
		$this->sAddAttachmentName = $oDocument->GetFileName();
	}

	public function OnRemoveAttachment(EventData $oData)
	{
		$this->debug('OnRemoveAttachment');
		$this->assertEquals('UserRequest', get_class($oData->Get('object')));
		$oAttachment = $oData->Get('attachment');
		/** @var \ormDocument $oDocument */
		$oDocument = $oAttachment->Get('contents');
		$this->sRemoveAttachmentName = $oDocument->GetFileName();
	}
}
