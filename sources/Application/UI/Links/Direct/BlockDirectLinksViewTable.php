<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Direct;

use Combodo\iTop\Application\UI\Links\AbstractBlockLinksViewTable;
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
		return array(
			'target_attr' => $this->oAttDef->GetExtKeyToMe(),
			'object_id'   => $this->oDbObject->GetKey(),
			'menu'        => MetaModel::GetConfig()->Get('allow_menu_on_linkset'),
			'default'     => $this->GetDefault(),
			'table_id'    => $this->sObjectClass.'_'.$this->sAttCode,
			'row_actions' => $this->GetRowActions(),
		);
	}

	/** @inheritdoc * */
	public function GetRowActions(): array
	{
		$aRowActions = array();

		if (!$this->oAttDef->GetReadOnly()) {

			switch ($this->oAttDef->GetRelationType()) {

				case LINKSET_RELATIONTYPE_LINK:
					$aRowActions[] = array(
						'action'        => 'UI:Links:ActionRow:detach',
						'tooltip'       => 'UI:Links:ActionRow:detach+',
						'icon_classes'  => 'fas fa-minus',
						'js_row_action' => "LinkSetWorker.DetachLinkedObject('{$this->sTargetClass}', aRowData['{$this->sTargetClass}/_key_/raw'], '{$this->oAttDef->GetExtKeyToMe()}');",
						'confirmation'  => [
							'message'                    => 'UI:Links:ActionRow:detach:confirmation',
							'message_row_data'           => "{$this->sTargetClass}/hyperlink",
							'do_not_show_again_pref_key' => 'LinkSetWorker.DetachLinkedObject',
						],
					);
					break;

				case LINKSET_RELATIONTYPE_PROPERTY:
					$aRowActions[] = array(
						'action'        => 'UI:Links:ActionRow:delete',
						'tooltip'       => 'UI:Links:ActionRow:delete+',
						'icon_classes'  => 'fas fa-trash',
						'js_row_action' => "LinkSetWorker.DeleteLinkedObject('{$this->oAttDef->GetLinkedClass()}', aRowData['{$this->oAttDef->GetLinkedClass()}/_key_/raw']);",
						'confirmation'  => [
							'message'                    => 'UI:Links:ActionRow:delete:confirmation',
							'message_row_data'           => "{$this->sTargetClass}/hyperlink",
							'do_not_show_again_pref_key' => 'LinkSetWorker.DeleteLinkedObject',
						],
					);
					break;
			}
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