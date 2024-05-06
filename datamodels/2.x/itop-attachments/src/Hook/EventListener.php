<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Attachments\Hook;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Service\Events\iEventServiceSetup;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use IssueLog;
use LogChannels;
use MetaModel;
use ormDocument;
use TriggerOnAttachmentDownload;

/**
 * Class EventListener
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.1.0
 */
class EventListener implements iEventServiceSetup
{
	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
		EventService::RegisterListener(
			EVENT_DOWNLOAD_DOCUMENT,
			[$this, 'OnAttachmentDownloadActivateTriggers'],
			'Attachment'
		);
	}

	/**
	 * Callback when an Attachment is downloaded: Activate corresponding triggers
	 *
	 * @param \Combodo\iTop\Service\Events\EventData $oEventData
	 *
	 * @return void
	 */
	public function OnAttachmentDownloadActivateTriggers(EventData $oEventData): void
	{
		// Only consider "download as attachment". "inline" (preview in browser) or other should be ignored
		if ($oEventData->Get('content_disposition') !== ormDocument::ENUM_CONTENT_DISPOSITION_ATTACHMENT) {
			return;
		}

		/** @var \DBObject $oAttachment */
		$oAttachment = $oEventData->Get('object');
		$oHostObj = MetaModel::GetObject($oAttachment->Get('item_class'), $oAttachment->Get('item_id'), false /* false to avoid exception during trigger */, true);
		/** @var \ormDocument $oDocument */
		$oDocument = $oEventData->Get('document');

		$sTriggerClass = TriggerOnAttachmentDownload::class;
		$aTriggerContextArgs = [
			'this->object()' => $oHostObj,
			'attachment->object()' => $oAttachment,
			'attachment->mime_type' => $oDocument->GetMimeType(),
			'attachment->file_name' => $oDocument->GetFileName(),
			'attachment->downloads_count' => $oDocument->GetDownloadsCount(),
			'attachment->data' => $oDocument->GetData(),
			'attachment->data_as_base64' => base64_encode($oDocument->GetData()),
		];
		$aTriggerParams = array('class_list' => MetaModel::EnumParentClasses($oAttachment->Get('item_class'), ENUM_PARENT_CLASSES_ALL));
		$oTriggerSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT $sTriggerClass AS t WHERE t.target_class IN (:class_list)"), [], $aTriggerParams);

		/** @var \Trigger $oTrigger */
		while ($oTrigger = $oTriggerSet->Fetch()) {
			try {
				$oTrigger->DoActivate($aTriggerContextArgs);
			} catch (Exception $oException) {
				IssueLog::Error('Exception occurred during trigger activation in '.EventListener::class.'::'.__METHOD__, LogChannels::NOTIFICATIONS, [
					'trigger_class' => get_class($oTrigger),
					'trigger_id' => $oTrigger->GetKey(),
					'exception_message' => $oException->getMessage(),
					'exception_stacktrace' => $oException->getTraceAsString(),
				]);
			}
		}
	}
}