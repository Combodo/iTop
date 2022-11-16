<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Direct;

use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Links\AbstractBlockLinksTable;
use MetaModel;
use PHPUnit\Exception;

/**
 * Class BlockDirectLinksTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Direct
 */
class BlockDirectLinksTable extends AbstractBlockLinksTable
{

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
		return array(
			[
				'tooltip'       => 'remove link',
				'icon_classes'  => 'fas fa-minus',
				'js_row_action' => "LinkSetWorker.DetachLinkedObject('{$this->sTargetClass}', aData['{$this->sTargetClass}/_key_/raw'], '{$this->oAttDef->GetExtKeyToMe()}', oTrElement);",
			],
			[
				'tooltip'       => 'delete object',
				'icon_classes'  => 'fas fa-trash',
				'js_row_action' => "LinkSetWorker.DeleteLinkedObject('{$this->oAttDef->GetLinkedClass()}', aData['{$this->oAttDef->GetLinkedClass()}/_key_/raw']);",
			],
		);
	}

	/**
	 * GetDefault.
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
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