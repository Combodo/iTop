<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Notifications;

use Action;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Controller\AbstractController;
use CoreException;
use CoreUnexpectedValue;
use Dict;
use MetaModel;
use utils;

/**
 * @internal
 * @since 3.2.0 N°5472 creation
 */
class ActionController extends AbstractController {
	public const ROUTE_NAMESPACE = 'notifications.action';

	/**
	 * @throws CoreException if cannot load the Action object
	 * @throws CoreUnexpectedValue if `actionid` parameter is invalid
	 * @since 3.2.0 N°5472 creation
	 */
	public function OperationLastExecutionsTab()
	{
		$sActionId = utils::ReadParam('action_id', null, false);
		$sCannotLoadActionErrorMessage = __METHOD__ . ': invalid action_id parameter';
		if (utils::IsNullOrEmptyString($sActionId)) {
			throw new CoreUnexpectedValue($sCannotLoadActionErrorMessage);
		}

		$oAction = MetaModel::GetObject(Action::class, $sActionId, false);
		if (is_null($oAction)) {
			throw new CoreException($sCannotLoadActionErrorMessage);
		}

		$oPage = new AjaxPage(Dict::S('Action:last_executions_tab'));
		$oAction->GetLastExecutionsTabContent($oPage);

		return $oPage;
	}
}