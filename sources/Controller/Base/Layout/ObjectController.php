<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Base\Layout;

use AjaxPage;
use ApplicationException;
use cmdbAbstractObject;
use CMDBObjectSet;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Controller\AbstractController;
use Dict;
use iTopWebPage;
use MetaModel;
use SecurityException;
use utils;
use UserRights;
use WebPage;

/**
 * Class ObjectController
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.1.0
 * @package Combodo\iTop\Controller\Base\Layout
 */
class ObjectController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'object';

	/**
	 * @return \iTopWebPage|\AjaxPage Object edit form in its webpage
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 */
	public function OperationModify()
	{
		$bPrintable = utils::ReadParam('printable', '0') === '1';
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sId = utils::ReadParam('id', '');

		// Check parameters
		if (utils::IsNullOrEmptyString($sClass) || utils::IsNullOrEmptyString($sId))
		{
			throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
		}

		$oObj = MetaModel::GetObject($sClass, $sId, false);
		// Check user permissions
		// - Is allowed to view it?
		if (is_null($oObj)) {
			throw new ApplicationException(Dict::S('UI:ObjectDoesNotExist'));
		}

		// - Is allowed to edit it?
		$oSet = CMDBObjectSet::FromObject($oObj);
		if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO) {
			throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $sId));
		}

		// Prepare web page (should more likely be some kind of response object like for Symfony)
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage = new AjaxPage('');
		} else {
			$oPage = new iTopWebPage('', $bPrintable);
			$oPage->DisableBreadCrumb();
			$oPage->SetContentLayout(PageContentFactory::MakeForObjectDetails($oObj, cmdbAbstractObject::ENUM_DISPLAY_MODE_EDIT));
		}
		// - JS files
		foreach (static::EnumRequiredForModificationJsFilesRelPaths() as $sJsFileRelPath) {
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().$sJsFileRelPath);
		}

		// Note: Code duplicated to the case 'apply_modify' in UI.php when a data integrity issue has been found
		$oObj->DisplayModifyForm($oPage, array('wizard_container' => 1)); // wizard_container: Display the title above the form

		return $oPage;
	}

	/**
	 * @return string[] Rel. paths (to iTop root folder) of required JS files for object modification (create, edit, stimulus, ...)
	 */
	public static function EnumRequiredForModificationJsFilesRelPaths(): array
	{
		return [
			'js/json.js',
			'js/forms-json-utils.js',
			'js/wizardhelper.js',
			'js/wizard.utils.js',
			'js/linkswidget.js',
			'js/linksdirectwidget.js',
			'js/extkeywidget.js',
			'js/jquery.blockUI.js',
		];
	}
}