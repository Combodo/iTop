<?php

use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;

class UI
{

    /**
     * Operation select_for_modify_all
     *
     * @param iTopWebPage $oP
     *
     * @throws \ApplicationException
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \OQLException
     */
    public static function OperationSelectForModifyAll(iTopWebPage $oP, $sTitleTab = 'UI:ModifyAllPageTitle', $sTitleCode = 'UI:Modify_ObjectsOf_Class', $sNextOperation = 'form_for_modify_all'): void
    {
        $oP->DisableBreadCrumb();
		IssueLog::Error('OperationSelectForModifyAll'.$sTitleCode);
        $oP->set_title(Dict::S($sTitleTab));
        $sFilter = utils::ReadParam('filter', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
        if (empty($sFilter)) {
            throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
        }
        $oFilter = DBObjectSearch::unserialize($sFilter); //TODO : check that the filter is valid
        // Add user filter
        $oFilter->UpdateContextFromUser();
        $oChecker = new ActionChecker($oFilter, UR_ACTION_BULK_MODIFY);
        $sClass = $oFilter->GetClass();
        $sClassName = MetaModel::GetName($sClass);

        $aDisplayParams = [
            'icon' => MetaModel::GetClassIcon($sClass, false),
            'title' => Dict::Format('UI:Modify_ObjectsOf_Class', $sClassName),
        ];
        IssueLog::Error('OperationSelectForModifyAll');
        self::DisplayMultipleSelectionForm($oP, $oFilter, $sNextOperation, $oChecker, [], $aDisplayParams);
    }

    /**
     * Operation form_for_modify_all
     *
     * @param iTopWebPage $oP
     * @param \ApplicationContext $oAppContext
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
     * @throws \OQLException
     */
    public static function OperationFormForModifyAll(iTopWebPage $oP, ApplicationContext $oAppContext): void
    {
        $oP->DisableBreadCrumb();
        $sFilter = utils::ReadParam('filter', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
        $sClass = utils::ReadParam('class', '', false, utils::ENUM_SANITIZATION_FILTER_CLASS);
        $oFullSetFilter = DBObjectSearch::unserialize($sFilter);
        // Add user filter
        $oFullSetFilter->UpdateContextFromUser();
        $aSelectedObj = utils::ReadMultipleSelection($oFullSetFilter);
        $sCancelUrl =  utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&filter=' . urlencode($sFilter) . '&' . $oAppContext->GetForLink();
        $aContext = array('filter' => utils::EscapeHtml($sFilter));
        cmdbAbstractObject::DisplayBulkModifyForm($oP, $sClass, $aSelectedObj, 'preview_or_modify_all', $sCancelUrl, array(), $aContext);
    }

    /**
     * Operation preview_or_modify_all
     *
     * @param iTopWebPage $oP
     * @param \ApplicationContext $oAppContext
     *
     * @throws \ApplicationException
     * @throws \ArchivedObjectException
     * @throws \CoreCannotSaveObjectException
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     * @throws \OQLException
     */
    public static function OperationPreviewOrModifyAll(iTopWebPage $oP, ApplicationContext $oAppContext): void
    {
        $oP->DisableBreadCrumb();
        $sFilter = utils::ReadParam('filter', '', false, 'raw_data');
        $oFilter = DBObjectSearch::unserialize($sFilter); // TO DO : check that the filter is valid
        // Add user filter
        $oFilter->UpdateContextFromUser();

        $sClass = utils::ReadParam('class', '', false, 'class');
        $bPreview = utils::ReadParam('preview_mode', '');
        $sSelectedObj = utils::ReadParam('selectObj', '', false, 'raw_data');
        if (empty($sClass) || empty($sSelectedObj)) // TO DO: check that the class name is valid !
        {
            throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'selectObj'));
        }
        $aSelectedObj = explode(',', $sSelectedObj);
        $sCancelUrl =  utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&filter=' . urlencode($sFilter) . '&' . $oAppContext->GetForLink();
        $aContext = array(
            'filter' => utils::EscapeHtml($sFilter),
            'selectObj' => $sSelectedObj,
        );
        cmdbAbstractObject::DoBulkModify($oP, $sClass, $aSelectedObj, 'preview_or_modify_all', $bPreview, $sCancelUrl, $aContext);
    }/**
     * Displays a form (checkboxes) to select the objects for which to apply a given action
     * Only the objects for which the action is valid can be checked. By default all valid objects are checked
     *
     * @param WebPage $oP WebPage The page for output
     * @param \DBSearch $oFilter DBSearch The filter that defines the list of objects
     * @param string $sNextOperation string The next operation (code) to be executed when the form is submitted
     * @param ActionChecker $oChecker ActionChecker The helper class/instance used to check for which object the action is valid
     * @param array $aExtraFormParams
     * @param array $aDisplayParams
     *
     * @throws \ApplicationException
     * @throws \ArchivedObjectException
     * @throws \CoreException
     *@since 3.0.0 $aDisplayParams parameter
     *
     */
	public static function DisplayMultipleSelectionForm(WebPage $oP, DBSearch $oFilter, string $sNextOperation, ActionChecker $oChecker, array $aExtraFormParams = [], array $aDisplayParams = [])
{
	$oAppContext = new ApplicationContext();
	$iBulkActionAllowed = $oChecker->IsAllowed();
	$aExtraParams = array('selection_type' => 'multiple', 'selection_mode' => true, 'display_limit' => false, 'menu' => false);
	if ($iBulkActionAllowed == UR_ALLOWED_DEPENDS) {
		$aExtraParams['selection_enabled'] = $oChecker->GetAllowedIDs();
	} else {
		if (UR_ALLOWED_NO) {
			throw new ApplicationException(Dict::Format('UI:ActionNotAllowed'));
		}
	}

	$oForm = new Form();
	$oForm->SetAction( utils::GetAbsoluteUrlAppRoot().'pages/UI.php');
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', $sNextOperation));
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('class', $oFilter->GetClass()));
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('filter', utils::HtmlEntities($oFilter->Serialize())));
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', utils::GetNewTransactionId()));
	foreach ($aExtraFormParams as $sName => $sValue) {
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($sName, $sValue));
	}
	IssueLog::Error('DisplayMultipleSelectionForm');
	$oForm->AddSubBlock($oAppContext->GetForFormBlock());
	$oDisplayBlock = new DisplayBlock($oFilter, 'list', false);
	//by default all the elements are selected
	$aExtraParams['selectionMode'] = 'negative';
	if (array_key_exists('icon', $aDisplayParams) || array_key_exists('title', $aDisplayParams)) {
		$aExtraParams['surround_with_panel'] = true;
		if (array_key_exists('icon', $aDisplayParams)) {
			$aExtraParams['panel_icon'] = $aDisplayParams['icon'];
		}
		if (array_key_exists('title', $aDisplayParams)) {
			$aExtraParams['panel_title'] = $aDisplayParams['title'];
		}
	}
	$oForm->AddSubBlock($oDisplayBlock->GetDisplay($oP, 1, $aExtraParams));
	$oToolbarButtons = ToolbarUIBlockFactory::MakeStandard(null);
	$oToolbarButtons->AddCSSClass('ibo-toolbar--button');
	$oForm->AddSubBlock($oToolbarButtons);
	$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Cancel'), 'cancel')->SetOnClickJsCode('window.history.back()'));
	$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Next'), 'next', Dict::S('UI:Button:Next'), true));

	$oP->AddUiBlock($oForm);
}
}