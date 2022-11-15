<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links;

use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use MetaModel;
use PHPUnit\Exception;

/**
 * Class BlockLinksTable
 *
 * @package Combodo\iTop\Application\UI\Links
 */
class BlockLinksTable extends Panel
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-block-links-table';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/layout';

	/** @var \AttributeLinkedSet $oAttDef */
	private \AttributeLinkedSet $oAttDef;

	private string $sTargetClass;

	private string $sAttCode;


	private string $sObjectClass;

	private \DBObject $oDbObject;

	/**
	 * Constructor.
	 *
	 * @param \AttributeLinkedSet $oAttDef
	 * @param string $sAttCode
	 *
	 * @throws \Exception
	 */
	public function __construct(\WebPage $oPage, \AttributeLinkedSet $oAttDef, string $sAttCode, string $sObjectClass, \DBObject $oDbObject)
	{
		parent::__construct('', [], self::DEFAULT_COLOR_SCHEME, $sAttCode);

		// retrieve parameters
		$this->oAttDef = $oAttDef;
		$this->sAttCode = $sAttCode;
		$this->sObjectClass = $sObjectClass;
		$this->oDbObject = $oDbObject;

		// Initialization
		$this->Init();

		// UI Initialization
		$this->InitUI($oPage);
	}

	/**
	 * Initialization.
	 *
	 * @return void
	 */
	private function Init()
	{
		$this->sTargetClass = $this->GetTargetClass();
	}

	/**
	 * GetTargetClass.
	 *
	 * @return string
	 */
	private function GetTargetClass(): string
	{
		try {
			if ($this->oAttDef->IsIndirect()) {
				$oLinkingAttDef = MetaModel::GetAttributeDef($this->oAttDef->GetLinkedClass(), $this->oAttDef->GetExtKeyToRemote());

				return $oLinkingAttDef->GetTargetClass();
			} else {
				return $this->oAttDef->GetLinkedClass();
			}
		}
		catch (Exception $e) {
			return '?';
		}
	}


	/**
	 * Initialize UI.
	 *
	 * @return void
	 * @throws \CoreException
	 */
	private function InitUI(\WebPage $oPage)
	{
		// Panel
		$this->SetCSSClasses(["ibo-block-links-table"]);
		$this->SetTitle($this->sTargetClass);
		$this->SetSubTitle($this->oAttDef->GetDescription());
		$this->SetColorFromClass($this->oAttDef->GetLinkedClass());
		$this->SetIcon(MetaModel::GetClassIcon($this->sTargetClass, false));

		$this->InitTable($oPage);
	}

	/**
	 * @param \WebPage $oPage
	 * @param \DBObjectSet $oValue
	 * @param string $sFormPrefix
	 *
	 * @return void
	 */
	public function InitTable(\WebPage $oPage)
	{
		//
		$oOrmLinkSet = $this->oDbObject->Get($this->sAttCode);
		$oLinkSet = $oOrmLinkSet->ToDBObjectSet(\utils::ShowObsoleteData());
		$oBlock = new \DisplayBlock($oLinkSet->GetFilter(), 'list', false);

		$this->AddSubBlock($oBlock->GetRenderContent($oPage, $this->GetExtraParam(), 'rel_'.$this->sAttCode));

//		$this->AddSubBlock(DataTableUIBlockFactory::MakeForObject($oPage, 'rel_'.$this->sAttCode, $this->GetSet(), $this->GetExtraParam()));
	}

	private function GetDefault(): array
	{
		$aDefaults = array($this->oAttDef->GetExtKeyToMe() => $this->oDbObject->GetKey());
		$oAppContext = new \ApplicationContext();
		foreach ($oAppContext->GetNames() as $sKey) {
			if (MetaModel::IsValidAttCode($this->sObjectClass, $sKey)) {
				$aDefaults[$sKey] = $this->oDbObject->Get($sKey);
			}
		}

		return $aDefaults;
	}

	private function GetExtraParam(): array
	{
		if ($this->oAttDef->IsIndirect()) {
			return array(
				'link_attr'     => $this->oAttDef->GetExtKeyToMe(),
				'object_id'     => $this->oDbObject->GetKey(),
				'target_attr'   => $this->oAttDef->GetExtKeyToRemote(),
				'view_link'     => false,
				'menu'          => false,
				'display_limit' => true,
				'table_id'      => $this->sObjectClass.'_'.$this->sAttCode,
				'zlist'         => false,
				'extra_fields'  => $this->GetAttCodesToDisplay(),
				'row_actions'   => $this->GetRowActions(),
			);
		} else {
			return array(
				'target_attr' => $this->oAttDef->GetExtKeyToMe(),
				'object_id'   => $this->oDbObject->GetKey(),
				'menu'        => MetaModel::GetConfig()->Get('allow_menu_on_linkset'),
				'default'     => $this->GetDefault(),
				'table_id'    => $this->sObjectClass.'_'.$this->sAttCode,
				'row_actions' => $this->GetRowActions(),
			);
		}
	}

	private function GetAttCodesToDisplay()
	{
		$oLinkingAttDef = MetaModel::GetAttributeDef($this->oAttDef->GetLinkedClass(), $this->oAttDef->GetExtKeyToRemote());
		$sLinkingAttCode = $oLinkingAttDef->GetCode();
		$sTargetClass = $oLinkingAttDef->GetTargetClass();
		$sLinkedClass = $oLinkingAttDef->GetHostClass();

		// N°2334 fields to display for n:n relations
		$aLnkAttDefsToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectLinkClass($this->sObjectClass, $this->sAttCode);
		$aRemoteAttDefsToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectRemoteClass($sTargetClass);
		$aLnkAttCodesToDisplay = array_map(function ($oLnkAttDef) {
			return ormLinkSet::LINK_ALIAS.'.'.$oLnkAttDef->GetCode();
		},
			$aLnkAttDefsToDisplay
		);
		if (!in_array(\ormLinkSet::LINK_ALIAS.'.'.$sLinkingAttCode, $aLnkAttCodesToDisplay)) {
			// we need to display a link to the remote class instance !
			$aLnkAttCodesToDisplay[] = \ormLinkSet::LINK_ALIAS.'.'.$sLinkingAttCode;
		}
		$aRemoteAttCodesToDisplay = array_map(function ($oRemoteAttDef) {
			return \ormLinkSet::REMOTE_ALIAS.'.'.$oRemoteAttDef->GetCode();
		},
			$aRemoteAttDefsToDisplay
		);
		$aAttCodesToDisplay = array_merge($aLnkAttCodesToDisplay, $aRemoteAttCodesToDisplay);

		return implode(',', $aAttCodesToDisplay);
	}

	/**
	 * Return row actions.
	 *
	 * @return \string[][]
	 */
	private function GetRowActions(): array
	{
		if ($this->oAttDef->IsIndirect()) {

			return array(
				[
					'tooltip'       => 'remove link',
					'icon_classes'  => 'fas fa-minus',
					'js_row_action' => "RemoveLinkedSetElementAjax('{$this->oAttDef->GetLinkedClass()}', aData['Link/_key_/raw']);",
				],
			);
		} else {
			return array(
				[
					'tooltip'       => 'remove link',
					'icon_classes'  => 'fas fa-minus',
					'js_row_action' => "RemoveLinkedSetElementAjax('{$this->sTargetClass}', aData['{$this->sTargetClass}/_key_/raw'], '{$this->oAttDef->GetExtKeyToMe()}', oTrElement);",
				],
				[
					'tooltip'       => 'delete object',
					'icon_classes'  => 'fas fa-trash',
					'js_row_action' => "RemoveLinkedSetElementAjax('{$this->oAttDef->GetLinkedClass()}', aData['{$this->oAttDef->GetLinkedClass()}/_key_/raw']);",
				],
			);
		}
	}


	private function GetSet()
	{
		$aQueryParams = array();
		$bDoSearch = \utils::ReadParam('dosearch', false);
		$oAppContext = new \ApplicationContext();
		$oOrmLinkSet = $this->oDbObject->Get($this->sAttCode);
		$sClass = $oOrmLinkSet->GetFilter()->GetClass();
		$aFilterCodes = MetaModel::GetFiltersList($sClass);
		$aCallSpec = array($sClass, 'MapContextParam');
		if (is_callable($aCallSpec)) {
			foreach ($oAppContext->GetNames() as $sContextParam) {
				$sParamCode = call_user_func($aCallSpec, $sContextParam); //Map context parameter to the value/filter code depending on the class
				if (!is_null($sParamCode)) {
					$sParamValue = $oAppContext->GetCurrentValue($sContextParam, null);
					if (!is_null($sParamValue)) {
						$aExtraParams[$sParamCode] = $sParamValue;
					}
				}
			}
		}
		foreach ($aFilterCodes as $sFilterCode) {
			$externalFilterValue = \utils::ReadParam($sFilterCode, '', false, 'raw_data');
			$condition = null;
			$bParseSearchString = true;
			if (isset($aExtraParams[$sFilterCode])) {
				$bParseSearchString = false;
				$condition = $aExtraParams[$sFilterCode];
			}
			if ($bDoSearch && $externalFilterValue != "") {
				// Search takes precedence over context params...
				$bParseSearchString = true;
				unset($aExtraParams[$sFilterCode]);
				if (!is_array($externalFilterValue)) {
					$condition = trim($externalFilterValue);
				} else {
					if (count($externalFilterValue) == 1) {
						$condition = trim($externalFilterValue[0]);
					} else {
						$condition = $externalFilterValue;
					}
				}
			}

			if (!is_null($condition)) {
				$sOpCode = null; // default operator
				if (is_array($condition)) {
					// Multiple values, add them as AND X IN (v1, v2, v3...)
					$sOpCode = 'IN';
				}

				$this->AddCondition($sFilterCode, $condition, $sOpCode, $bParseSearchString);
			}
		}
		if ($bDoSearch) {
			// Keep the table_id identifying this table if we're performing a search
			$sTableId = \utils::ReadParam('_table_id_', null, false, 'raw_data');
			if ($sTableId != null) {
				$aExtraParams['table_id'] = $sTableId;
			}
		}

		$aOrderBy = array();
		if (isset($aExtraParams['order_by'])) {
			// Convert the string describing the order_by parameter into an array
			// The syntax is +attCode1,-attCode2
			// attCode1 => ascending, attCode2 => descending
			$aTemp = explode(',', $aExtraParams['order_by']);
			foreach ($aTemp as $sTemp) {
				$aMatches = array();
				if (preg_match('/^([+-])?(.+)$/', $sTemp, $aMatches)) {
					$bAscending = true;
					if ($aMatches[1] == '-') {
						$bAscending = false;
					}
					$aOrderBy[$aMatches[2]] = $bAscending;
				}
			}
		}

		$aExtraParams['query_params'] = $oOrmLinkSet->GetFilter()->GetInternalParams();

		return new \CMDBObjectSet($oOrmLinkSet->GetFilter(), $aOrderBy, $aQueryParams);
	}
}