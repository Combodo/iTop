<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links;

use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use MetaModel;

/**
 * Class AbstractBlockLinksTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links
 */
abstract class AbstractBlockLinksTable extends Panel
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-block-links-table';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/layout';

	/** @var \AttributeLinkedSet $oAttDef */
	protected \AttributeLinkedSet $oAttDef;

	/** @var string $sTargetClass */
	protected string $sTargetClass;

	/** @var string $sAttCode */
	protected string $sAttCode;

	/** @var string $sObjectClass */
	protected string $sObjectClass;

	/** @var \DBObject $oDbObject */
	protected \DBObject $oDbObject;

	/**
	 * Constructor.
	 *
	 * @param \WebPage $oPage
	 * @param \AttributeLinkedSet $oAttDef
	 * @param string $sAttCode
	 * @param string $sObjectClass
	 * @param \DBObject $oDbObject
	 *
	 * @throws \CoreException
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
	 * Init.
	 *
	 * @return void
	 * @throws \Exception
	 */
	private function Init()
	{
		$this->sTargetClass = $this->GetTargetClass();
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

		// Table
		$this->InitTable($oPage);
	}

	/**
	 * InitTable.
	 *
	 * @param \WebPage $oPage
	 *
	 * @return void
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 */
	public function InitTable(\WebPage $oPage)
	{
		//
		$oOrmLinkSet = $this->oDbObject->Get($this->sAttCode);
		$oLinkSet = $oOrmLinkSet->ToDBObjectSet(\utils::ShowObsoleteData());

		$oBlock = new \DisplayBlock($oLinkSet->GetFilter(), 'list', false);
		$this->AddSubBlock($oBlock->GetRenderContent($oPage, $this->GetExtraParam(), 'rel_'.$this->sAttCode));
	}

	/**
	 * GetExtraParam.
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	abstract function GetExtraParam(): array;

	/**
	 * Return row actions.
	 *
	 * @return \string[][]
	 */
	abstract function GetRowActions(): array;

	/**
	 * GetTargetClass.
	 *
	 * @return string
	 * @throws \Exception
	 */
	abstract function GetTargetClass(): string;
}