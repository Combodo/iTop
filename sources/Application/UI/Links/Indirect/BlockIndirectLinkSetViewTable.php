<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Indirect;

use Combodo\iTop\Application\UI\Links\AbstractBlockLinkSetViewTable;
use MetaModel;
use PHPUnit\Exception;

/**
 * Class BlockIndirectLinkSetViewTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Indirect
 */
class BlockIndirectLinkSetViewTable extends AbstractBlockLinkSetViewTable
{
	public const BLOCK_CODE                          = 'ibo-block-indirect-linkset-view-table';
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;

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
		$aExtraParams = array(
			'link_attr'     => $this->oAttDef->GetExtKeyToMe(),
			'object_id'     => $this->oDbObject->GetKey(),
			'target_attr'   => $this->oAttDef->GetExtKeyToRemote(),
			'view_link'     => false,
			'menu'          => MetaModel::GetConfig()->Get('allow_menu_on_linkset'),
			'display_limit' => true,
			'table_id'      => $this->GetTableId(),
			'zlist'         => false,
			'extra_fields'  => $this->GetAttCodesToDisplay(),
			'row_actions'   => $this->GetRowActions(),
			'currentId'     => $this->GetTableId(),
			'panel_title'   => $this->oAttDef->GetLabel(),
			'panel_icon'    => MetaModel::GetClassIcon($this->GetTargetClass(), false),
		);

		// Description
		if ($this->oAttDef->HasDescription()) {
			$aExtraParams['panel_title_tooltip'] = $this->oAttDef->GetDescription();
		}

		// Add creation in modal if creation allowed
		if ( $this->bIsAllowCreate) {
			$aExtraParams['creation_in_modal'] = true;
			$aExtraParams['creation_in_modal_tooltip'] = $this->GetDictionaryEntry(static::DICT_ADD_BUTTON_TOOLTIP);
			$aExtraParams['creation_in_modal_form_title'] = $this->GetDictionaryEntry(static::DICT_ADD_MODAL_TITLE);
			$aExtraParams['creation_in_modal_js_handler'] = "{$this->GetWidgetName()}.links_view_table('CreateLinkedObject');";
		} else {
			$aExtraParams['creation_disallowed'] = true;
		}

		return $aExtraParams;
	}

	/** @inheritdoc */
	public function GetRowActions(): array
	{
		$aRowActions = array();

		if ($this->bIsAllowModify) {
			$aRowActions[] = array(
				'label'         => 'UI:Links:ModifyLink:Button',
				'name'          => 'ModifyButton',
				'tooltip'       => $this->GetDictionaryEntry(static::DICT_MODIFY_LINK_BUTTON_TOOLTIP),
				'icon_classes'  => 'fas fa-pen',
				'js_row_action' => "{$this->GetWidgetName()}.links_view_table('ModifyLinkedObject', aRowData['Link/_key_/raw'], oTrElement, aRowData['Remote/friendlyname']);",
				'metadata'      => [
					'modal-title' => $this->GetDictionaryEntry(static::DICT_MODIFY_LINK_MODAL_TITLE),
				],
			);
		}

		if ($this->bIsAllowDelete) {
			$aRowActions[] = array(
				'label'         => 'UI:Links:Remove:Button',
				'name'          => 'RemoveButton',
				'tooltip'       => $this->GetDictionaryEntry(static::DICT_REMOVE_BUTTON_TOOLTIP),
				'icon_classes'  => 'fas fa-minus',
				'js_row_action' => "{$this->GetWidgetName()}.links_view_table('DeleteLinkedObject', aRowData['Link/_key_/raw'], oTrElement);",
				'confirmation'  => [
					'title'                      => $this->GetDictionaryEntry(static::DICT_REMOVE_MODAL_TITLE),
					'message'                    => $this->GetDictionaryEntry(static::DICT_REMOVE_MODAL_MESSAGE),
					'row_data'                   => "Remote/hyperlink",
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