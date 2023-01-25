<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links;

use ApplicationException;
use ArchivedObjectException;
use AttributeLinkedSet;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use CoreException;
use CoreWarning;
use DBObject;
use DictExceptionMissingString;
use DisplayBlock;
use Exception;
use MetaModel;
use MySQLException;
use Utils;
use WebPage;

/**
 * Class AbstractBlockLinksViewTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links
 */
abstract class AbstractBlockLinksViewTable extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-abstract-block-links-view-table';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/layout';
	public const DEFAULT_JS_FILES_REL_PATH    = [
		'js/links/link_set_worker.js',
		'js/wizardhelper.js',
	];

	/** @var DBObject $oDbObject db object witch link set belongs to */
	protected DBObject $oDbObject;

	/** @var string $sObjectClass db object class name */
	protected string $sObjectClass;

	/** @var string $sAttCode db object link set attribute code */
	protected string $sAttCode;

	/** @var AttributeLinkedSet $oAttDef attribute link set */
	protected AttributeLinkedSet $oAttDef;

	/** @var string $sTargetClass links target classname */
	protected string $sTargetClass;

	protected string $sTableId;

	/**
	 * Constructor.
	 *
	 * @param WebPage $oPage
	 * @param DBObject $oDbObject
	 * @param string $sObjectClass
	 * @param string $sAttCode
	 * @param AttributeLinkedSet $oAttDef
	 *
	 * @throws CoreException
	 * @throws Exception
	 */
	public function __construct(WebPage $oPage, DBObject $oDbObject, string $sObjectClass, string $sAttCode, AttributeLinkedSet $oAttDef)
	{
		parent::__construct('', ["ibo-block-links-table"]);

		// retrieve parameters
		$this->oAttDef = $oAttDef;
		$this->sAttCode = $sAttCode;
		$this->sObjectClass = $sObjectClass;
		$this->oDbObject = $oDbObject;
		$this->sTableId = 'rel_'.$this->sAttCode;
		$this->SetDataAttributes(['role' => 'ibo-block-links-table', 'link-attcode' => $sAttCode, 'link-class' => $this->oAttDef->GetLinkedClass()]);
		// Initialization
		$this->Init();

		// UI Initialization
		$this->InitUI($oPage);
	}

	/**
	 * Init.
	 *
	 * @return void
	 * @throws Exception
	 */
	private function Init()
	{
		$this->sTargetClass = $this->GetTargetClass();
	}

	/**
	 * Initialize UI.
	 *
	 * @return void
	 * @throws CoreException
	 */
	private function InitUI(WebPage $oPage)
	{
		// header
		$this->InitHeader();

		// Table
		$this->InitTable($oPage);
	}

	/**
	 * InitHeader.
	 *
	 * @return void
	 * @throws CoreException
	 * @throws \Exception
	 */
	private function InitHeader()
	{
		// Linkset description as an informative alert
		$sDescription = $this->oAttDef->GetDescription();
		if (utils::IsNotNullOrEmptyString($sDescription)) {
			$oAlert = AlertUIBlockFactory::MakeForInformation('', $sDescription);
			$this->AddSubBlock($oAlert);
		}
	}

	/**
	 * InitTable.
	 *
	 * @param WebPage $oPage
	 *
	 * @return void
	 * @throws ApplicationException
	 * @throws ArchivedObjectException
	 * @throws CoreException
	 * @throws CoreWarning
	 * @throws DictExceptionMissingString
	 * @throws MySQLException
	 */
	private function InitTable(WebPage $oPage)
	{
		// retrieve db object set
		$oOrmLinkSet = $this->oDbObject->Get($this->sAttCode);
		$oLinkSet = $oOrmLinkSet->ToDBObjectSet(utils::ShowObsoleteData());

		// add list block
		$oBlock = new \DisplayBlock($oLinkSet->GetFilter(), 'list', false);
		$this->AddSubBlock($oBlock->GetRenderContent($oPage, $this->GetExtraParam(), $this->sTableId));
	}

	/**
	 * GetTableId.
	 *
	 * @return string table identifier
	 */
	protected function GetTableId(): string
	{
		return $this->sObjectClass.'_'.$this->sAttCode;
	}

	/**
	 * GetDoNotShowAgainPreferenceKey.
	 *
	 * @return string do not show again preference key
	 */
	protected function GetDoNotShowAgainPreferenceKey(): string
	{
		return "{$this->GetTableId()}.remove_link.do_not_show_again";
	}

	/**
	 * GetExtraParam.
	 *
	 * Provide parameters for display block as list.
	 *
	 * @see DisplayBlock::RenderList
	 *
	 * @return array
	 * @throws ArchivedObjectException
	 * @throws CoreException
	 */
	abstract function GetExtraParam(): array;

	/**
	 * Return row actions.
	 *
	 * Register row actions for table.
	 *
	 * @see \Combodo\iTop\Application\UI\Base\Component\DataTable\tTableRowActions
	 *
	 * @return string[][]
	 */
	abstract function GetRowActions(): array;

	/**
	 * GetTargetClass.
	 *
	 * Return link set target class.
	 *
	 * @return string
	 * @throws Exception
	 */
	abstract function GetTargetClass(): string;


	/**
	 * @return string
	 */
	public function GetAttCode(): string
	{
		return $this->sAttCode;
	}

}