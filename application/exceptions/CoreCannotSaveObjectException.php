<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Class CoreCannotSaveObjectException
 *
 * Specialized exception to raise if {@link DBObject::CheckToWrite()} fails, which allow easy data retrieval
 *
 * @see \DBObject::DBInsertNoReload()
 * @see \DBObject::DBUpdate()
 *
 * @since 2.6.0 N°659 uniqueness constraint
 */
class CoreCannotSaveObjectException extends CoreException
{
	/** @var string[] */
	private $aIssues;
	/** @var int */
	private $iObjectId;
	/** @var string */
	private $sObjectClass;

	/**
	 * CoreCannotSaveObjectException constructor.
	 *
	 * @param array $aContextData containing at least those keys : issues, id, class
	 */
	public function __construct($aContextData, $oPrevious = null)
	{
		$this->aIssues = $aContextData['issues'];
		$this->iObjectId = $aContextData['id'];
		$this->sObjectClass = $aContextData['class'];

		$sIssues = implode(', ', $this->aIssues);
		parent::__construct($sIssues, $aContextData, '', $oPrevious);
	}

	/**
	 * @return string
	 */
	public function getHtmlMessage()
	{
		$sTitle = Dict::S('UI:Error:SaveFailed');
		$sContent = "<span><strong>".utils::HtmlEntities($sTitle)."</strong></span>";

		if (count($this->aIssues) == 1) {
			$sIssue = reset($this->aIssues);
			$sContent .= "&nbsp;<span>".utils::HtmlEntities($sIssue)."</span>";
		} else {
			$sContent .= '<ul>';
			foreach ($this->aIssues as $sError) {
				$sContent .= "<li>".utils::HtmlEntities($sError)."</li>";
			}
			$sContent .= '</ul>';
		}

		return $sContent;
	}

	public function getTextMessage()
	{
		$sTitle = Dict::S('UI:Error:SaveFailed');
		$sContent = utils::HtmlEntities($sTitle);

		if (count($this->aIssues) == 1) {
			$sIssue = reset($this->aIssues);
			$sContent .= utils::HtmlEntities($sIssue);
		} else {
			foreach ($this->aIssues as $sError) {
				$sContent .= " ".utils::HtmlEntities($sError).", ";
			}
		}

		return $sContent;
	}


	public function getIssues()
	{
		return $this->aIssues;
	}

	public function getObjectId()
	{
		return $this->iObjectId;
	}

	public function getObjectClass()
	{
		return $this->sObjectClass;
	}
}
