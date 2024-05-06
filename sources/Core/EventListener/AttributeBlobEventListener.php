<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\EventListener;

use Combodo\iTop\Service\Events\Description\EventDescription;
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
use TriggerOnAttributeBlobDownload;

/**
 * Class AttributeBlobEventListener
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Core\EventListener
 * @since 3.1.0
 */
class AttributeBlobEventListener implements iEventServiceSetup
{
	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
		EventService::RegisterListener(
			EVENT_DOWNLOAD_DOCUMENT,
			[$this, 'OnAttributeBlobDownloadActivateTrigger']
		);
	}

	/**
	 * Callback when an ormDocument is downloaded: Activate corresponding triggers
	 *
	 * @param \Combodo\iTop\Service\Events\EventData $oEventData
	 *
	 * @return void
	 */
	public function OnAttributeBlobDownloadActivateTrigger(EventData $oEventData): void
	{
		// Only consider "download as attachment". "inline" (preview in browser) or other should be ignored
		if ($oEventData->Get('content_disposition') !== ormDocument::ENUM_CONTENT_DISPOSITION_ATTACHMENT) {
			return;
		}

		/** @var \DBObject $oObject */
		$oObject = $oEventData->Get('object');
		$sAttCode = $oEventData->Get('att_code');
		/** @var \ormDocument $oDocument */
		$oDocument = $oEventData->Get('document');

		$sTriggerClass = TriggerOnAttributeBlobDownload::class;
		$aTriggerContextArgs = array(
			'this->object()' => $oObject,
			'attribute_code' => $sAttCode,
			'file->mime_type' => $oDocument->GetMimeType(),
			'file->file_name' => $oDocument->GetFileName(),
			'file->downloads_count' => $oDocument->GetDownloadsCount(),
			'file->data' => $oDocument->GetData(),
			'file->data_as_base64' => base64_encode($oDocument->GetData()),
		);
		$aTriggerParams = array('class_list' => MetaModel::EnumParentClasses(get_class($oObject), ENUM_PARENT_CLASSES_ALL));
		$oTriggerSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT $sTriggerClass AS t WHERE t.target_class IN (:class_list)"), array(), $aTriggerParams);

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