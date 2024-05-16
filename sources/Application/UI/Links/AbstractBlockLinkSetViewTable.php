<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links;

use ApplicationException;
use ArchivedObjectException;
use AttributeLinkedSet;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use CoreException;
use CoreWarning;
use DBObject;
use Dict;
use DictExceptionMissingString;
use DisplayBlock;
use Exception;
use MetaModel;
use MySQLException;
use UserRights;
use Utils;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Class AbstractBlockLinkSetViewTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links
 */
abstract class AbstractBlockLinkSetViewTable extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                            = 'ibo-abstract-block-linkset-view-table';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'application/links/layout';
	public const DEFAULT_JS_FILES_REL_PATH             = [
		'js/links/links_view_table_widget.js',
		'js/links/linkset-worker.js',
		'js/object/object-worker.js',
		'js/wizardhelper.js',
	];

	// Dictionnary entries
	public const DICT_ADD_BUTTON_TOOLTIP           = 'UI:Links:Add:Button+';
	public const DICT_ADD_MODAL_TITLE              = 'UI:Links:Add:Modal:Title';
	public const DICT_CREATE_BUTTON_TOOLTIP        = 'UI:Links:Create:Button+';
	public const DICT_CREATE_MODAL_TITLE           = 'UI:Links:Create:Modal:Title';
	public const DICT_MODIFY_LINK_BUTTON_TOOLTIP   = 'UI:Links:ModifyLink:Button+';
	public const DICT_MODIFY_LINK_MODAL_TITLE      = 'UI:Links:ModifyLink:Modal:Title';
	public const DICT_MODIFY_OBJECT_BUTTON_TOOLTIP = 'UI:Links:ModifyObject:Button+';
	public const DICT_MODIFY_OBJECT_MODAL_TITLE    = 'UI:Links:ModifyObject:Modal:Title';
	public const DICT_REMOVE_BUTTON_TOOLTIP        = 'UI:Links:Remove:Button+';
	public const DICT_REMOVE_MODAL_TITLE           = 'UI:Links:Remove:Modal:Title';
	public const DICT_REMOVE_MODAL_MESSAGE         = 'UI:Links:Remove:Modal:Message';
	public const DICT_DELETE_BUTTON_TOOLTIP        = 'UI:Links:Delete:Button+';
	public const DICT_DELETE_MODAL_TITLE           = 'UI:Links:Delete:Modal:Title';
	public const DICT_DELETE_MODAL_MESSAGE         = 'UI:Links:Delete:Modal:Message';

	/** @var DBObject $oDbObject db object witch link set belongs to */
	protected DBObject $oDbObject;

	/** @var string $sObjectClass db object class name */
	protected string $sObjectClass;

	/** @var string $sAttCode db object link set attribute code */
	protected string $sAttCode;

	/** @var AttributeLinkedSet $oAttDef attribute link set */
	protected AttributeLinkedSet $oAttDef;

	/** @var bool $bIsAttEditable Is attribute editable */
	protected bool $bIsAttEditable;

	/** @var string $sTargetClass links target classname */
	protected string $sTargetClass;

	protected string $sTableId;

	// User rights
	protected bool $bIsAllowCreate;
	protected bool $bIsAllowModify;
	protected bool $bIsAllowDelete;

	/**
	 * Constructor.
	 *
	 * @param WebPage $oPage
	 * @param DBObject $oDbObject
	 * @param string $sObjectClass
	 * @param string $sAttCode
	 * @param AttributeLinkedSet $oAttDef
	 * @param bool $bIsReadOnly
	 *
	 * @throws \CoreException
	 */
	public function __construct(WebPage $oPage, DBObject $oDbObject, string $sObjectClass, string $sAttCode, AttributeLinkedSet $oAttDef, bool $bIsReadOnly = false)
	{
		parent::__construct("links_view_table_$sAttCode", ["ibo-block-links-table"]);

		// retrieve parameters
		$this->oAttDef = $oAttDef;
		$this->sAttCode = $sAttCode;
		$this->sObjectClass = $sObjectClass;
		$this->oDbObject = $oDbObject;
		$this->sTableId = 'rel_'.$this->sAttCode;
		$this->bIsAttEditable = !$bIsReadOnly;
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
		
		// User rights
		$this->bIsAllowCreate = $this->bIsAttEditable && UserRights::IsActionAllowed($this->oAttDef->GetLinkedClass(), UR_ACTION_CREATE) == UR_ALLOWED_YES;
		$this->bIsAllowModify = $this->bIsAttEditable && UserRights::IsActionAllowed($this->oAttDef->GetLinkedClass(), UR_ACTION_MODIFY) == UR_ALLOWED_YES;
		$this->bIsAllowDelete = $this->bIsAttEditable && UserRights::IsActionAllowed($this->oAttDef->GetLinkedClass(), UR_ACTION_DELETE) == UR_ALLOWED_YES;
	}


	/**
	 * @param string $sKey
	 * @param \DBObject|null $oDBObject
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function GetDictionaryEntry(string $sKey, DBObject $oDBObject = null)
	{
		return $this->oAttDef->SearchSpecificLabel($sKey, '', true,
			MetaModel::GetName($this->sObjectClass),
			$this->oDbObject->Get('friendlyname'),
			$this->oAttDef->GetLabel(),
			MetaModel::GetName($this->sTargetClass),
			$oDBObject !== null ? $oDBObject->Get('friendlyname') : '{item}');
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
	 * InitHeader by adding UIBlocks to the current self
	 *
	 * @return void
	 * @throws CoreException
	 * @throws \Exception
	 */
	private function InitHeader()
	{

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
		$oBlock = new DisplayBlock($oLinkSet->GetFilter(), DisplayBlock::ENUM_STYLE_LIST_IN_OBJECT, false);
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
	 * GetAttCode.
	 *
	 * @return string
	 */
	public function GetAttCode(): string
	{
		return $this->sAttCode;
	}

	/**
	 * GetLinkedClass.
	 *
	 * @return mixed
	 */
	public function GetLinkedClass()
	{
		return $this->oAttDef->GetLinkedClass();
	}

	/**
	 * GetExternalKeyToMe.
	 *
	 * @return mixed
	 */
	public function GetExternalKeyToMe()
	{
		return $this->oAttDef->GetExtKeyToMe();
	}

	/**
	 * GetWidgetName.
	 *
	 * @return string
	 */
	public function GetWidgetName(): string
	{
		return "oWidget{$this->GetId()}";
	}
}