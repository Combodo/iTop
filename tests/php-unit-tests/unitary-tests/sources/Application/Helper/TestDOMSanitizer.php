<?php

namespace Combodo\iTop\Test\UnitTest\Application\Helper;


use DOMSanitizer;

class TestDOMSanitizer extends DOMSanitizer
{
	protected static array $aTagsWhiteList = [];
	private static $aStylesWhiteList = [];
	private static $aAttrsWhiteList = [];

	protected static array $aTagsBlackList = [];
	private static $aAttrsBlackList = [];

	public function GetTagsWhiteList()
	{
		return static::$aTagsWhiteList;
	}

	public function GetTagsBlackList()
	{
		return static::$aTagsBlackList;
	}

	public function GetAttrsWhiteList()
	{
		return static::$aAttrsWhiteList;
	}

	public function GetAttrsBlackList()
	{
		return static::$aAttrsBlackList;
	}

	public function GetStylesWhiteList()
	{
		return static::$aStylesWhiteList;
	}
	
	public function SetTagsWhiteList(array $aTagsWhiteList)
	{
		static::$aTagsWhiteList = $aTagsWhiteList;
	}
	
	public function SetAttrsWhiteList(array $aAttrsWhiteList)
	{
		static::$aAttrsWhiteList = $aAttrsWhiteList;
	}
	
	public function SetStylesWhiteList(array $aStylesWhiteList)
	{
		static::$aStylesWhiteList = $aStylesWhiteList;
	}
	
	public function SetTagsBlackList(array $aTagsBlackList)
	{
		static::$aTagsBlackList = $aTagsBlackList;
	}
	
	public function SetAttrsBlackList(array $aAttrsBlackList)
	{
		static::$aAttrsBlackList = $aAttrsBlackList;
	}

	public function LoadDoc($sHTML)
	{
		// TODO: Implement LoadDoc() method.
	}

	public function PrintDoc()
	{
		// TODO: Implement PrintDoc() method.
	}
}