<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class CoreException extends Exception
{
	protected $m_sIssue;
	protected $m_sImpact;
	protected $m_aContextData;

	/**
	 * CoreException constructor.
	 *
	 * @param string $sIssue error message
	 * @param array|null $aContextData key/value array, value MUST implements _toString
	 * @param string $sImpact
	 * @param Exception|null $oPrevious
	 */
	public function __construct($sIssue, $aContextData = null, $sImpact = '', $oPrevious = null)
	{
		$this->m_sIssue = $sIssue;
		$this->m_sImpact = $sImpact;

		if (is_array($aContextData)) {
			$this->m_aContextData = $aContextData;
		} else {
			$this->m_aContextData = [];
		}

		$sMessage = $sIssue;
		if (!empty($sImpact)) {
			$sMessage .= "($sImpact)";
		}
		if (count($this->m_aContextData) > 0) {
			$sMessage .= ": ";
			$aContextItems = array();
			foreach ($this->m_aContextData as $sKey => $value) {
				if (is_array($value)) {
					$aPairs = array();
					foreach ($value as $key => $val) {
						if (is_array($val)) {
							$aPairs[] = $key.'=>('.implode(', ', $val).')';
						} else {
							$aPairs[] = $key.'=>'.$val;
						}
					}
					$sValue = '{'.implode('; ', $aPairs).'}';
				} else {
					$sValue = $value;
				}
				$aContextItems[] = "$sKey = $sValue";
			}
			$sMessage .= implode(', ', $aContextItems);
		}
		parent::__construct($sMessage, 0, $oPrevious);
	}

	/**
	 * @return string code and message for log purposes
	 */
	public function getInfoLog()
	{
		return 'error_code='.$this->getCode().', message="'.$this->getMessage().'"';
	}

	public function getHtmlDesc($sHighlightHtmlBegin = '<b>', $sHighlightHtmlEnd = '</b>')
	{
		return $this->getMessage();
	}

	/**
	 * getTraceAsString() cannot be overrided and it is limited as only current exception stack is returned.
	 * we need stack of all previous exceptions
	 *
	 * @uses __tostring() already does the work.
	 * @since 2.7.2/ 3.0.0
	 */
	public function getFullStackTraceAsString()
	{
		return "".$this;
	}

	public function getTraceAsHtml()
	{
		$aBackTrace = $this->getTrace();

		return MyHelpers::get_callstack_html(0, $this->getTrace());
		// return "<pre>\n".$this->getTraceAsString()."</pre>\n";
	}

	public function addInfo($sKey, $value)
	{
		$this->m_aContextData[$sKey] = $value;
	}

	public function getIssue()
	{
		return $this->m_sIssue;
	}

	public function getImpact()
	{
		return $this->m_sImpact;
	}

	public function getContextData()
	{
		return $this->m_aContextData;
	}
}