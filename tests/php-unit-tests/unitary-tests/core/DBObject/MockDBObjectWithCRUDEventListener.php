<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace DBObject;

use Combodo\iTop\Service\Events\EventData;
use MetaModel;

class MockDBObjectWithCRUDEventListener extends \DBObject
{
	const TEST_EVENT = 'test_event';
	public $oEventDataReceived = null;

	public static function Init()
	{
		$aParams = array
		(
			'category'            => 'bizmodel, searchable',
			'key_type'            => 'autoincrement',
			'name_attcode'        => '',
			'state_attcode'       => '',
			'reconc_keys'         => [],
			'db_table'            => 'priv_unit_tests_mock',
			'db_key_field'        => 'id',
			'db_finalclass_field' => '',
			'display_template'    => '',
			'indexes'             => [],
		);
		MetaModel::Init_Params($aParams);
	}

	protected function RegisterEventListeners()
	{
		$this->RegisterCRUDListener(self::TEST_EVENT, 'TestEventCallback', 0, 'unit-test');
	}

	public function TestEventCallback(EventData $oEventData)
	{
		$this->oEventDataReceived = $oEventData;
	}
}