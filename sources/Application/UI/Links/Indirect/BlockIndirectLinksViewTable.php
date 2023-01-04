<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Indirect;

use Combodo\iTop\Application\UI\Links\AbstractBlockLinksViewTable;
use MetaModel;
use PHPUnit\Exception;

/**
 * Class BlockIndirectLinksViewTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Indirect
 */
class BlockIndirectLinksViewTable extends AbstractBlockLinksViewTable
{
	public const BLOCK_CODE = 'ibo-block-indirect-links-view-table';

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
			'table_id'      => $this->GetTableId(),
			'zlist'         => false,
			'extra_fields'  => $this->GetAttCodesToDisplay(),
			'row_actions'   => $this->GetRowActions(),
		);
	}

	/** @inheritdoc */
	public function GetRowActions(): array
	{
		$aRowActions = array();

		if (!$this->oAttDef->GetReadOnly()) {

			$aRowActions[] = array(
				'label'         => 'UI:Links:ActionRow:Detach',
				'tooltip'       => 'UI:Links:ActionRow:Detach+',
				'icon_classes'  => 'fas fa-minus',
				'js_row_action' => "LinkSetWorker.DeleteLinkedObject('{$this->oAttDef->GetLinkedClass()}', aRowData['Link/_key_/raw']);",
				'confirmation'  => [
					'message'                    => 'UI:Links:ActionRow:Detach:Confirmation',
					'message_row_data'           => "Remote/hyperlink",
					'do_not_show_again_pref_key' => $this->GetDoNotShowAgainPreferenceKey(),
				],
			);

		}

		return $aRowActions;
	}

	/**
	 * @return string
	 * @throws \CoreException
	 */
	private function GetAttCodesToDisplay(): string
	{
		/** @var \AttributeLinkedSetIndirect $oAttributeLinkedSetIndirectDefinition */
		$oAttributeLinkedSetIndirectDefinition = MetaModel::GetAttributeDef($this->oAttDef->GetLinkedClass(), $this->oAttDef->GetExtKeyToRemote());
		$sAttributeLinkedSetIndirectAttCode = $oAttributeLinkedSetIndirectDefinition->GetCode();
		$sAttributeLinkedSetIndirectLinkedClass = $oAttributeLinkedSetIndirectDefinition->GetTargetClass();

		$aAttCodesToDisplay = MetaModel::GetAttributeLinkedSetIndirectDatatableAttCodesToDisplay($this->sObjectClass, $this->sAttCode, $sAttributeLinkedSetIndirectLinkedClass, $sAttributeLinkedSetIndirectAttCode);
		/** @noinspection PhpUnnecessaryLocalVariableInspection *//** @noinspection OneTimeUseVariablesInspection */
		$sAttCodesToDisplay = implode(',', $aAttCodesToDisplay);

		return $sAttCodesToDisplay;
	}
}