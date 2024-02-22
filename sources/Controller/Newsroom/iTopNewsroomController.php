<?php

namespace Combodo\iTop\Controller\Newsroom;

use ArchivedObjectException;
use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\JsonPPage;
use Combodo\iTop\Service\Router\Router;
use CoreException;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use DisplayBlock;
use MetaModel;
use UserRights;
use utils;


/**
 *  Class iTopNewsroomController
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Controller\Newsroom
 * @since 3.2.0
 */
class iTopNewsroomController extends Controller
{
	public const ROUTE_NAMESPACE = 'itopnewsroom';

	/**
	 * @return iTopWebPage
	 * @throws \ApplicationException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function OperationViewAll()
	{
		$oPage = new iTopWebPage(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Title'));
		$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE read = "no"');
		$oSearch->AddCondition('contact_id', UserRights::GetContactId(), '=');
		$oBlock = new DisplayBlock($oSearch, 'search', false /* Asynchronous */, []);
		$oBlock->Display($oPage, 0);
		$oPage->add("<div class='sf_results_area ibo-add-margin-top-250' data-target='search_results'>");

		return $oPage;
	}

	/**
	 * @return JsonPPage
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function OperationFetchUnreadMessages()
	{
		$sCallback = utils::ReadParam('callback', '');
		$oPage = new JsonPPage($sCallback);

		$aMessages = [];
		$iContactId = UserRights::GetContactId();

		if (utils::IsNotNullOrEmptyString($iContactId)) {
			$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE contact_id = :contact_id AND read = "no"');
			$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

			while ($oMessage = $oSet->Fetch()) {
				$sTitle = $oMessage->Get('title');
				$sMessage = $oMessage->Get('message');
				$sText = <<<HTML
**$sTitle**


$sMessage
HTML;

				$sIcon = $oMessage->Get('icon') !== null ?
					$oMessage->Get('icon')->GetDisplayURL('EventiTopNotification', $oMessage->GetKey(), 'icon') :
					Branding::GetCompactMainLogoAbsoluteUrl();
				$aMessages[] = array(
					'id'         => $oMessage->GetKey(),
					'text'       => $sText,
					'url'        => Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.view_event', ['event_id' => $oMessage->GetKey()]),
					'target'     => '_self',
					'start_date' => $oMessage->Get('date'),
					'priority'   => $oMessage->Get('priority'),
					'image'      => $sIcon,
				);
			}

		}
		$oPage->SetData($aMessages);
		$oPage->SetOutputDataOnly(true);

		return $oPage;
	}

	/**
	 * @return int
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function OperationMarkAllAsReadMessages()
	{
		$iCount = 0;
		$iContactId = UserRights::GetContactId();


		if (utils::IsNotNullOrEmptyString($iContactId)) {
			$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE contact_id = :contact_id AND read = "no"');
			$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

			while ($oEvent = $oSet->Fetch()) {
				$oEvent->Set('read', 'yes');
				$oEvent->SetCurrentDate('read_date');
				$oEvent->DBWrite();
				$iCount++;
			}
		}

		return $iCount;
	}

	/**
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function OperationViewEvent()
	{
		$sEventId = utils::ReadParam('event_id', 0);
		if ($sEventId > 0) {
			try {
				$oEvent = MetaModel::GetObject('EventiTopNotification', $sEventId);
				if ($oEvent !== null && $oEvent->Get('contact_id') === UserRights::GetContactId()) {
					$oEvent->Set('read', 'yes');
					$oEvent->SetCurrentDate('read_date');
					$oEvent->DBWrite();
					$sUrl = $oEvent->Get('url');
					header("Location: $sUrl");
				}
			}
			catch (ArchivedObjectException|CoreException $e) {
				$this->DisplayPageNotFound();
			}
		}
	}
}