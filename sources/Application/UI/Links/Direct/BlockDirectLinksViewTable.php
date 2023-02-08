<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Direct;

use Combodo\iTop\Application\UI\Links\AbstractBlockLinksViewTable;
use Combodo\iTop\Service\Links\LinkSetModel;
use MetaModel;

/**
 * Class BlockDirectLinksViewTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Direct
 */
class BlockDirectLinksViewTable extends AbstractBlockLinksViewTable
{
	public const BLOCK_CODE                          = 'ibo-block-direct-links-view-table';
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;

	/** @inheritdoc */
	public function GetTargetClass(): string
	{
		return $this->oAttDef->GetLinkedClass();
	}

	/** @inheritdoc * */
	public function GetExtraParam(): array
	{
		$aExtraParams = array(
			'target_attr' => $this->oAttDef->GetExtKeyToMe(),
			'object_id'   => $this->oDbObject->GetKey(),
			'menu'        => MetaModel::GetConfig()->Get('allow_menu_on_linkset'),
			'default'     => $this->GetDefault(),
			'table_id'    => $this->GetTableId(),
			'row_actions' => $this->GetRowActions(),
			'currentId'   => $this->GetTableId(),
			'panel_title' => $this->oAttDef->GetLabel(),
			'panel_icon'  => MetaModel::GetClassIcon($this->GetTargetClass(), false),
		);

		// Description
		if ($this->oAttDef->HasDescription()) {
			$aExtraParams['panel_title_tooltip'] = $this->oAttDef->GetDescription();
		}

		// Add creation in modal if the linkset is not readonly
		if ($this->oAttDef->GetEditMode() != LINKSET_EDITMODE_NONE) {
			$aExtraParams['creation_in_modal_is_allowed'] = true;
			$aExtraParams['creation_in_modal_js_handler'] = "{$this->GetWidgetName()}.links_view_table('CreateLinkedObject');";
		}

		return $aExtraParams;
	}

	/** @inheritdoc * */
	public function GetRowActions(): array
	{
		$aRowActions = array();

		// until a full link set refactoring (continue using edit_mode property)
		switch ($this->oAttDef->GetEditMode()) {
			case LINKSET_EDITMODE_NONE: // The linkset is read-only
				break;

			case LINKSET_EDITMODE_ADDONLY: // The only possible action is to open (in a new window) the form to create a new object
				$aRowActions[] = array(
					'label'         => 'UI:Links:ActionRow:Modify',
					'tooltip'       => 'UI:Links:ActionRow:Modify+',
					'icon_classes'  => 'fas fa-pen',
					'js_row_action' => "{$this->GetWidgetName()}.links_view_table('ModifyLinkedObject', aRowData['{$this->oAttDef->GetLinkedClass()}/_key_/raw']);",
				);
				break;

			case LINKSET_EDITMODE_INPLACE: // The whole linkset can be edited 'in-place'
			case LINKSET_EDITMODE_ACTIONS: // Show the usual 'Actions' popup menu
				$aRowActions[] = array(
					'label'         => 'UI:Links:ActionRow:Delete',
					'tooltip'       => 'UI:Links:ActionRow:Delete+',
					'icon_classes'  => 'fas fa-trash',
					'js_row_action' => "{$this->GetWidgetName()}.links_view_table('DeleteLinkedObject', aRowData['{$this->oAttDef->GetLinkedClass()}/_key_/raw'], oTrElement);",
					'confirmation'  => [
						'message'                    => 'UI:Links:ActionRow:Delete:Confirmation',
						'message_row_data'           => "{$this->sTargetClass}/hyperlink",
						'do_not_show_again_pref_key' => $this->GetDoNotShowAgainPreferenceKey(),
					],
				);
				$aRowActions[] = array(
					'label'         => 'UI:Links:ActionRow:Modify',
					'tooltip'       => 'UI:Links:ActionRow:Modify+',
					'icon_classes'  => 'fas fa-pen',
					'js_row_action' => "{$this->GetWidgetName()}.links_view_table('ModifyLinkedObject', aRowData['{$this->oAttDef->GetLinkedClass()}/_key_/raw']);",
				);
				break;

			case LINKSET_EDITMODE_ADDREMOVE: // The whole linkset can be edited 'in-place'
				$aRowActions[] = array(
					'label'         => 'UI:Links:ActionRow:Detach',
					'tooltip'       => 'UI:Links:ActionRow:Detach+',
					'icon_classes'  => 'fas fa-minus',
					'js_row_action' => "{$this->GetWidgetName()}.links_view_table('DetachLinkedObject', aRowData['{$this->sTargetClass}/_key_/raw'], oTrElement);",
					'confirmation'  => [
						'message'                    => 'UI:Links:ActionRow:Detach:Confirmation',
						'message_row_data'           => "{$this->sTargetClass}/hyperlink",
						'do_not_show_again_pref_key' => $this->GetDoNotShowAgainPreferenceKey(),
					],
				);
				$aRowActions[] = array(
					'label'         => 'UI:Links:ActionRow:Modify',
					'tooltip'       => 'UI:Links:ActionRow:Modify+',
					'icon_classes'  => 'fas fa-pen',
					'js_row_action' => "{$this->GetWidgetName()}.links_view_table('ModifyLinkedObject', aRowData['{$this->oAttDef->GetLinkedClass()}/_key_/raw']);",
				);
				break;

			default:
				break;
		}

		return $aRowActions;
	}

	/**
	 * GetDefault.
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Exception
	 */
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
}