<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Base\Layout;

use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelHelper;
use Combodo\iTop\Controller\AbstractController;
use Combodo\iTop\Renderer\BlockRenderer;
use Dict;
use Exception;
use InlineImage;
use MetaModel;
use utils;

/**
 * Class ActivityPanelController
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Controller\Base\Layout
 */
class ActivityPanelController extends AbstractController
{
	/**
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function SaveState(): void
	{
		$sObjectClass = utils::ReadPostedParam('object_class', '', utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sObjectMode = utils::ReadPostedParam('object_mode');
		$bIsExpanded = utils::ReadPostedParam('is_expanded');
		$bIsClosed = utils::ReadPostedParam('is_closed');

		if (false === empty($bIsExpanded)) {
			ActivityPanelHelper::SaveExpandedStateForClass($sObjectClass, $sObjectMode, ('true' === $bIsExpanded));
		}

		if (false === empty($bIsClosed)) {
			ActivityPanelHelper::SaveClosedStateForClass($sObjectClass, $sObjectMode, ('true' === $bIsClosed));
		}
	}

	/**
	 * Add new entries to some of the object's (identified by posted parameters) case logs
	 *
	 * @return array The status of the update, a renewed transaction ID and the entries as HTML so they can be append to the front.
	 * [
	 *  'success' => true,
	 *  'entries' => [
	 *      '<ATT_CODE_1>' => [
	 *          'html_rendering' => '<HTML_RENDERING_TO_BE_APPEND_IN_FRONT_END>',
	 *      ],
	 *      '<ATT_CODE_2>' => [
	 *          'html_rendering' => '<HTML_RENDERING_TO_BE_APPEND_IN_FRONT_END>',
	 *      ],
	 *      ...
	 *  ],
	 *  'renewed_transaction_id' => '<RENEWED_TRANSACTION_ID>',
	 * ]
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \OQLException
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function AddCaseLogsEntries(): array
	{
		$sObjectClass = utils::ReadPostedParam('object_class', null, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sObjectId = utils::ReadPostedParam('object_id', 0);
		$sTransactionId = utils::ReadPostedParam('transaction_id', null, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);
		$aEntries = utils::ReadPostedParam('entries', [], utils::ENUM_SANITIZATION_FILTER_RAW_DATA);

		// Consistency checks
		// - Mandatory parameters
		if (empty($sObjectClass) || empty($sObjectId) || empty($sTransactionId) || empty($aEntries)) {
			throw new Exception('Missing mandatory parameters object_class / object_id / transaction_id / entries');
		}
		// - Transaction ID
		// Note: We keep the transaction ID for several reasons:
		// - We might send several messages, so renewing it would not make such a difference except making the follwoing line harder
		// - We need the transaction ID to passed in the JS snippet that allows images to be uploaded (see InlineImage::EnableCKEditorImageUpload()), renewing it would only make things more complicated
		// => For all those reasons, we let the GC clean the transactions IDs, just like when a transaction ID is not deleted when cancelling a regular object edition.
		if (!utils::IsTransactionValid($sTransactionId, false)) {
			throw new Exception(Dict::S('iTopUpdate:Error:InvalidToken'));
		}

		$aResults = [
			'success' => true,
			'entries' => [],
		];

		// Note: Will trigger an exception if object does not exists or not accessible to the user
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId);
		foreach ($aEntries as $sAttCode => $aData) {
			// Add entry to object
			$oObject->Set($sAttCode, $aData['value']);

			// Make entry rendering to send back to the front
			$aEntryAsArray = $oObject->Get($sAttCode)->GetAsArray()[0];
			$oEntryBlock = ActivityEntryFactory::MakeFromCaseLogEntryArray($sAttCode, $aEntryAsArray);
			$oEntryBlock->SetCaseLogRank((int)$aData['rank']);
			$sEntryAsHtml = BlockRenderer::RenderBlockTemplates($oEntryBlock);

			$aResults['entries'][$sAttCode] = [
				'html_rendering' => $sEntryAsHtml,
			];
		}
		// Finalize inline images
		InlineImage::FinalizeInlineImages($oObject);

		// Invoke extensions after the update of the object from the activity form
		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnFormSubmit($oObject);
		}

		$oObject->DBWrite();

		return $aResults;
	}

	/**
	 * Load next entries chunk
	 *
	 * @return array Entries already rendered and metadata for pagination
	 * [
	 *  'success' => true,
	 *  'entries' => [
	 *      ['html_rendering' => '<HTML_RENDERING>'],
	 *      ['html_rendering' => '<HTML_RENDERING>'],
	 *      ...
	 *  ],
	 *  'last_loaded_entries_ids' => [
	 *      'cmdbchangeop' => <LAST_ENTRY_ID>,
	 *  ],
	 * ]
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function LoadMoreEntries(): array
	{
		$sObjectClass = utils::ReadPostedParam('object_class', null, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sObjectId = utils::ReadPostedParam('object_id', 0);
		$aLastLoadedEntriesIds = utils::ReadPostedParam('last_loaded_entries_ids', [], utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$bLimitResultsLength = utils::ReadPostedParam('limit_results_length', 'true') === 'true';

		$aResults = [
			'success' => true,
			'entries' => [],
			'last_loaded_entries_ids' => [],
		];

		// CMDBChangeOp entries
		if (array_key_exists('cmdbchangeop', $aLastLoadedEntriesIds)) {
			$aChangesData = ActivityPanelHelper::GetCMDBChangeOpEditsEntriesForObject($sObjectClass, $sObjectId, $aLastLoadedEntriesIds['cmdbchangeop'], $bLimitResultsLength);

			if (true === $aChangesData['more_entries_to_load']) {
				$aResults['last_loaded_entries_ids']['cmdbchangeop'] = $aChangesData['last_loaded_entry_id'];
			}

			/** @var \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry $oEntryBlock */
			foreach ($aChangesData['entries'] as $oEntryBlock) {
				$sEntryAsHtml = BlockRenderer::RenderBlockTemplates($oEntryBlock);
				$aResults['entries'][] = [
					'html_rendering' => $sEntryAsHtml,
				];
			}
		}

		return $aResults;
	}
}
