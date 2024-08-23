<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\Log;


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DeprecatedCallsLog;

class DeprecatedCallsLogTest extends ItopTestCase
{
	/**
	 * @dataProvider StripCallStackProvider
	 */
	public function testStripCallStack($sInputStack, $sExpectedStack)
	{
		$this->assertEquals(
			$sExpectedStack,
			$this->InvokeNonPublicStaticMethod(DeprecatedCallsLog::class, 'StripCallStack', [$sInputStack]),
			'The top item of the call stack should be the first item meaningful to track deprecated calls, not the intermediate layers of the PHP engine'
		);
	}

	public function StripCallStackProvider()
	{
		return [
			/* A deprecated PHP method is invoked from scratch.php, at line 25 (note: the name of the method is not present in the callstack) */
			'Should preserve the handler when the notice is fired by PHP itself' => [
				'in' => [
							[
								'file' => 'whateverfolder/scratch.php',
								'line' => 25,
								'function' => 'DeprecatedNoticesErrorHandler',
								'class' => 'DeprecatedCallsLog',
								'type' => '::',
							],
				],
				'out' => [
							[
								'file' => 'whateverfolder/scratch.php',
								'line' => 25,
								'function' => 'DeprecatedNoticesErrorHandler',
								'class' => 'DeprecatedCallsLog',
								'type' => '::',
							],
				],
			],
			'Should skip the handler when the notice is fired by a call to trigger_error' => [
				'in' => [
					[
						'function' => 'DeprecatedNoticesErrorHandler',
						'class' => 'DeprecatedCallsLog',
						'type' => '::',
					],
					[
						'file' => 'whateverfolder/scratch.php',
						'line' => 25,
						'function' => 'trigger_error',
					],
				],
				'out' => [
					[
						'file' => 'whateverfolder/scratch.php',
						'line' => 25,
						'function' => 'trigger_error',
					],
				],
			],
			'Should skip two levels when the notice is fired by trigger_deprecation (Symfony helper)' => [
				'in' => [
					[
						'function' => 'DeprecatedNoticesErrorHandler',
						'class' => 'DeprecatedCallsLog',
						'type' => '::',
					],
					[
						'file' => 'symfony/deprecation.php',
						'line' => 12,
						'function' => 'trigger_error',
					],
					[
						'file' => 'symfony/service.php',
						'line' => 25,
						'function' => 'trigger_deprecation',
					],
				],
				'out' => [
					[
						'file' => 'symfony/service.php',
						'line' => 25,
						'function' => 'trigger_deprecation',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider SummarizeCallStackProvider
	 */
	public function testSummarizeCallStack($sInputStackStripped, $sExpectedSummary)
	{
		$this->assertEquals(
			$sExpectedSummary,
			$this->InvokeNonPublicStaticMethod(DeprecatedCallsLog::class, 'SummarizeCallStack', [$sInputStackStripped])
		);
	}

	public function SummarizeCallStackProvider()
	{
		// All tests are based on a call stack issued from a deprecated PHP function
		// Other cases are similar: what counts on the top level item is the file and line number of the deprecated call
		return [
			'From the main page (deprecated PHP function)' => [
				'in:stripped call stack' => [
					[
						'file' => 'whateverfolder/scratch.php',
						'line' => 25,
						'function' => 'DeprecatedNoticesErrorHandler',
						'class' => 'DeprecatedCallsLog',
						'type' => '::',
					],
				],
				'out' => 'whateverfolder/scratch.php#25',
			],
			'From an iTop method (deprecated PHP function)' => [
				'in:stripped call stack' => [
					[
						'file' => 'whateverfolder/someclass.php',
						'line' => 18,
						'function' => 'DeprecatedNoticesErrorHandler',
						'class' => 'DeprecatedCallsLog',
						'type' => '::',
					],
					[
						'file' => 'whateverfolder/index.php',
						'line' => 25,
						'function' => 'SomeMethod',
						'class' => 'SomeClass',
						'type' => '->',
					],
				],
				'out' => 'SomeClass->SomeMethod (whateverfolder/someclass.php#18), itself called from whateverfolder/index.php#25',
			],
			'From an iTop static method (deprecated PHP function)' => [
				'in:stripped call stack' => [
					[
						'file' => 'whateverfolder/someclass.php',
						'line' => 18,
						'function' => 'DeprecatedNoticesErrorHandler',
						'class' => 'DeprecatedCallsLog',
						'type' => '::',
					],
					[
						'file' => 'whateverfolder/index.php',
						'line' => 25,
						'function' => 'SomeMethod',
						'class' => 'SomeClass',
						'type' => '::',
					],
				],
				'out' => 'SomeClass::SomeMethod (whateverfolder/someclass.php#18), itself called from whateverfolder/index.php#25',
			],
			'From an iTop function (deprecated PHP function)' => [
				'in:stripped call stack' => [
					[
						'file' => 'whateverfolder/someclass.php',
						'line' => 18,
						'function' => 'DeprecatedNoticesErrorHandler',
						'class' => 'DeprecatedCallsLog',
						'type' => '::',
					],
					[
						'file' => 'whateverfolder/index.php',
						'line' => 25,
						'function' => 'SomeFunction',
					],
				],
				'out' => 'SomeFunction (whateverfolder/someclass.php#18), itself called from whateverfolder/index.php#25',
			],
			'From a code snippet (deprecated PHP function)' => [
				'in:stripped call stack' => [
					[
						'file' => 'itop-root/env-production/core/main.php',
						'line' => 1290,
						'function' => 'DeprecatedNoticesErrorHandler',
						'class' => 'DeprecatedCallsLog',
						'type' => '::',
					],
					[
						'file' => 'itop-root/core/metamodel.class.php',
						'line' => 6698,
						'function' => 'require_once',
					],
					[
						'file' => 'itop-root/env-production/autoload.php',
						'line' => 6,
						'function' => 'IncludeModule',
						'class' => 'MetaModel',
						'type' => '::',
					],
					[
						'file' => 'itop-root/core/metamodel.class.php',
						'line' => 6487,
						'function' => 'require_once',
					],
				],
				'out' => 'itop-root/env-production/core/main.php#1290'
			],
			'From a persistent object method (deprecated PHP function)' => [
				'in:stripped call stack' => [
						[
							'file' => 'itop-root/env-production/itop-tickets/model.itop-tickets.php',
							'line' => 165,
							'function' => 'DeprecatedNoticesErrorHandler',
							'class' => 'DeprecatedCallsLog',
							'type' => '::',
						],
						[
							'file' => 'itop-root/core/dbobject.class.php',
							'line' => 6575,
							'function' => 'OnBeforeWriteTicket',
							'class' => 'Ticket',
							'type' => '->',
						],
						[
							'file' => 'itop-root/application/cmdbabstract.class.inc.php',
							'line' => 5933,
							'function' => 'FireEvent',
							'class' => 'DBObject',
							'type' => '->',
						],
						[
							'file' => 'itop-root/core/dbobject.class.php',
							'line' => 3643,
							'function' => 'FireEventBeforeWrite',
							'class' => 'cmdbAbstractObject',
							'type' => '->',
						],
						[
							'file' => 'itop-root/application/cmdbabstract.class.inc.php',
							'line' => 4593,
							'function' => 'DBUpdate',
							'class' => 'DBObject',
							'type' => '->',
						],
						[
							'file' => 'itop-root/sources/Controller/Base/Layout/ObjectController.php',
							'line' => 649,
							'function' => 'DBUpdate',
							'class' => 'cmdbAbstractObject',
							'type' => '->',
						],
						[
							'file' => 'itop-root/pages/UI.php',
							'line' => 720,
							'function' => 'OperationApplyModify',
							'class' => 'Combodo\\iTop\\Controller\\Base\\Layout\\ObjectController',
							'type' => '->',
						],
				],
				'out' => 'Ticket->OnBeforeWriteTicket (itop-root/env-production/itop-tickets/model.itop-tickets.php#165), itself called from DBObject->FireEvent (itop-root/core/dbobject.class.php#6575)'
			],
		];
	}
}
