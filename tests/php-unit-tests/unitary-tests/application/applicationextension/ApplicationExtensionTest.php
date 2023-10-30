<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use MetaModel;
use utils;

/**
 * @covers \iBackofficeLinkedScriptsExtension
 */
class ApplicationExtensionTest extends ItopCustomDatamodelTestCase
{
	protected const ENUM_API_CALL_METHOD_ENUMPLUGINS = 'MetaModel::EnumPlugins';
	protected const ENUM_API_CALL_METHOD_GETCLASSESFORINTERFACE = 'utils::GetClassesForInterface';

	/**
	 * @inheritDoc
	 */
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__ . '/Delta/application-extension-usages-in-snippets.xml';
	}

	/**
	 * This test ensures that APIs are discovered / registered / called.
	 *
	 * It was introduced after {@since N°6436} when some APIs registration in \MetaModel::InitClasses() were lost during a merge {@link https://github.com/Combodo/iTop/commit/6432678de9f635990e22a6512e5b30713c22204a#diff-c94890a26989b5a5ce638f82e8cc7d4c7aa24e6fbb9c2ca89850e8fa4e0e9adaL3004} preventing them from being called when requested. This was hard to detect as it needed an extension to use the lost API to witness that it was no longer called.
	 *
	 * For each new API, a new test case should be added here to ensure that we don't lose it later.
	 * To do so:
	 * - Add the API to the provider
	 * - Add a class extending / implementing the API in ./Delta/application-extension-usages-in-snippets.xml
	 *
	 * @return void
	 */
	public function testExtensionAPIRegisteredAndCalled()
	{
		foreach ($this->ExtensionAPIRegisteredAndCalledProvider() as list($sAPIFQCN, $sCallMethod)) {
			if ($sCallMethod === static::ENUM_API_CALL_METHOD_ENUMPLUGINS) {
				$iExtendingClassesCount = count(MetaModel::EnumPlugins($sAPIFQCN));
			} else {
				$iExtendingClassesCount = count(utils::GetClassesForInterface($sAPIFQCN, '', ['[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]', '[\\\\/]tests[\\\\/]']));
			}
			$this->assertGreaterThan(0, $iExtendingClassesCount, "Found no class extending the $sAPIFQCN API");
		}
	}

	public function ExtensionAPIRegisteredAndCalledProvider(): array
	{
		// APIs not concerned by this test:
		// * \iRestServiceProvider as it is discovered by iterating over declared classes directly
		// * \iLoginUIExtension as it is not iterated directly, only its derived interfaces

		return [
			\iLoginFSMExtension::class => [
				\iLoginFSMExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iLogoutExtension::class => [
				\iLogoutExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iLoginUIExtension::class => [
				\iLoginUIExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iPreferencesExtension::class => [
				\iPreferencesExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iApplicationUIExtension::class => [
				\iApplicationUIExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iApplicationObjectExtension::class => [
				\iApplicationObjectExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iPopupMenuExtension::class => [
				\iPopupMenuExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iPageUIExtension::class => [
				\iPageUIExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iPageUIBlockExtension::class => [
				\iPageUIBlockExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeLinkedScriptsExtension::class => [
				\iBackofficeLinkedScriptsExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeEarlyScriptExtension::class => [
				\iBackofficeEarlyScriptExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeScriptExtension::class => [
				\iBackofficeScriptExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeInitScriptExtension::class => [
				\iBackofficeInitScriptExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeReadyScriptExtension::class => [
				\iBackofficeReadyScriptExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeLinkedStylesheetsExtension::class => [
				\iBackofficeLinkedStylesheetsExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeStyleExtension::class => [
				\iBackofficeStyleExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeDictEntriesExtension::class => [
				\iBackofficeDictEntriesExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iBackofficeDictEntriesPrefixesExtension::class => [
				\iBackofficeDictEntriesPrefixesExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iPortalUIExtension::class => [
				\iPortalUIExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iQueryModifier::class => [
				\iQueryModifier::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iOnClassInitialization::class => [
				\iOnClassInitialization::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iFieldRendererMappingsExtension::class => [
				\iFieldRendererMappingsExtension::class,
				static::ENUM_API_CALL_METHOD_GETCLASSESFORINTERFACE,
			],
			\iModuleExtension::class => [
				\iModuleExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iKPILoggerExtension::class => [
				\iKPILoggerExtension::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\ModuleHandlerApiInterface::class => [
				\ModuleHandlerApiInterface::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
			\iNewsroomProvider::class => [
				\iNewsroomProvider::class,
				static::ENUM_API_CALL_METHOD_ENUMPLUGINS,
			],
		];
	}
}
