<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Indirect;

use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Links\AbstractBlockLinksTable;
use MetaModel;
use PHPUnit\Exception;

/**
 * Class BlockIndirectLinksTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Indirect
 */
class BlockIndirectLinksTable extends AbstractBlockLinksTable
{

	/** @inheritdoc */
	public function GetTargetClass(): string
	{
		try {
			$oLinkingAttDef = MetaModel::GetAttributeDef($this->oAttDef->GetLinkedClass(), $this->oAttDef->GetExtKeyToRemote());

			return $oLinkingAttDef->GetTargetClass();
		}
		catch (Exception $e) {
			return '?';
		}
	}

	/** @inheritdoc */
	public function GetExtraParam(): array
	{
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
	}

	/** @inheritdoc */
	public function GetRowActions(): array
	{
		return array(
			[
				'tooltip'       => 'remove link',
				'icon_classes'  => 'fas fa-minus',
				'js_row_action' => "LinkSetWorker.DeleteLinkedObject('{$this->oAttDef->GetLinkedClass()}', aData['Link/_key_/raw']);",
			],
		);
	}

	/**
	 * GetAttCodesToDisplay.
	 *
	 * @return string
	 * @throws \CoreException
	 */
	private function GetAttCodesToDisplay(): string
	{
		$oLinkingAttDef = MetaModel::GetAttributeDef($this->oAttDef->GetLinkedClass(), $this->oAttDef->GetExtKeyToRemote());
		$sLinkingAttCode = $oLinkingAttDef->GetCode();
		$sTargetClass = $oLinkingAttDef->GetTargetClass();

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
}