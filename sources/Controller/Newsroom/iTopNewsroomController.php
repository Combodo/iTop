<?php

use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Service\Router\Router;

class iTopNewsroomController extends Controller
{
	public const ROUTE_NAMESPACE = 'itopnewsroom';

	/**
	 * @return \iTopWebPage
	 * @throws \ApplicationException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function OperationViewAll()
	{
		$oPage = new iTopWebPage('cc');
		$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE read = "no"');
		$oSearch->AddCondition('contact_id', UserRights::GetContactId(), '=');
		$oBlock = new DisplayBlock($oSearch, 'search', false /* Asynchronous */, []);
		$oBlock->Display($oPage, 0);
		$oPage->add("<div class='sf_results_area ibo-add-margin-top-250' data-target='search_results'>");
		return $oPage;
	}

	/**
	 * @return \AjaxPage
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function OperationFetchUnreadMessages()
	{
		$sCallback = utils::ReadParam('callback', '');
		$oPage = new AjaxPage('');

		$aMessages = [];
		$iContactId = UserRights::GetContactId();
		
		if (\utils::IsNotNullOrEmptyString($iContactId)) {
			$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE contact_id = :contact_id AND read = "no"');
			$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

			while($oMessage = $oSet->Fetch())
			{
				$sTitle = $oMessage->Get('title');
				$Message = $oMessage->Get('message');
				$sText = <<<HTML
**$sTitle**


$Message
HTML;

				$sIcon = $oMessage->Get('icon') !== null ? 
					$oMessage->Get('icon')->GetDisplayURL('EventiTopNotification', $oMessage->GetKey(), 'icon') : 
					Branding::GetCompactMainLogoAbsoluteUrl();
				$aMessages[] = array(
					'id' => $oMessage->GetKey(),
					'text' => $sText,
					'url' => Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE . '.view_event', ['event_id' => $oMessage->GetKey()]),
					'start_date' => $oMessage->Get('date'),
					'priority' => $oMessage->Get('priority'),
					'image' => $sIcon,
				);
			}

		}
		$sOutput = $sCallback . '(' . json_encode($aMessages) . ')';
		echo $sOutput;
		$oPage->SetContentType('application/jsonp');
		$oPage->SetAddJSDict(false);
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
		

		if (\utils::IsNotNullOrEmptyString($iContactId)) {
			$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE contact_id = :contact_id AND read = "no"');
			$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

			while($oMessage = $oSet->Fetch())
			{
				$oMessage->Set('read', 'yes');
				$oMessage->Set('read_date', time());
				$oMessage->DBWrite();
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
	 */
	public function OperationViewEvent(){
		$sEventId = utils::ReadParam('event_id', 0);
		if($sEventId > 0) {
			$oEvent = MetaModel::GetObject('EventiTopNotification', $sEventId);
			if($oEvent !== null && $oEvent->Get('contact_id') === UserRights::GetContactId()){
				$oEvent->Set('read', 'yes');
				$oEvent->Set('read_date', time());
				$oEvent->DBWrite();
				$sUrl = $oEvent->Get('url');
				header("Location: $sUrl");
			}
		}
	}
}