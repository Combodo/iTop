<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOpSetAttributeHTML;
use CMDBChangeOpSetAttributeLongText;
use CMDBSource;
use Exception;

/**
 * Map a log to an attribute
 *
 * @package     iTopORM
 */
class AttributeLongText extends AttributeText
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "LONGTEXT".CMDBSource::GetSqlStringColumnDefinition();
	}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535 * 1024; // Limited... still 64 MB!
	}

	protected function GetChangeRecordClassName(): string
	{
		return ($this->GetFormat() === 'html')
			? CMDBChangeOpSetAttributeHTML::class
			: CMDBChangeOpSetAttributeLongText::class;
	}
}