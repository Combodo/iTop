<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class ErrorPage extends NiceWebPage
{
	public function __construct($sTitle)
	{
		parent::__construct($sTitle);
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../setup/setup.js");
		$this->add_saas("css/setup.scss");
	}

	public function info($sText)
	{
		$this->add("<p class=\"info\">$sText</p>\n");
		$this->log_info($sText);
	}

	public function ok($sText)
	{
		$this->add("<div class=\"message message-valid\"><span class=\"message-title\">Success:</span>$sText</div>");
		$this->log_ok($sText);
	}

	public function warning($sText)
	{
		$this->add("<div class=\"message message-warning\"><span class=\"message-title\">Warning:</span>$sText</div>");
		$this->log_warning($sText);
	}

	public function error($sText)
	{
		$this->add("<div class=\"message message-error\">$sText</div>");
		$this->log_error($sText);
	}

	public function output()
	{
		$sLogo = utils::GetAbsoluteUrlAppRoot().'/images/itop-logo.png';
		$sTimeStamp = utils::GetCacheBusterTimestamp();
		$sTitle = utils::HtmlEntities($this->s_title);
		$this->s_content = <<<HTML
<div id="header" class="error_page">
	<h1><a href="http://www.combodo.com/itop" target="_blank"><img title="iTop by Combodo" alt=" " src="{$sLogo}?t={$sTimeStamp}"></a>&nbsp;{$sTitle}</h1>
</div>
<div id="setup" class="error_page">
	{$this->s_content}
</div>
HTML;
		return parent::output();
	}

	public static function log_error($sText)
	{
		IssueLog::Error($sText);
	}

	public static function log_warning($sText)
	{
		IssueLog::Warning($sText);
	}

	public static function log_info($sText)
	{
		IssueLog::Info($sText);
	}

	public static function log_ok($sText)
	{
		IssueLog::Ok($sText);
	}

	public static function log($sText)
	{
		IssueLog::Ok($sText);
	}
}
