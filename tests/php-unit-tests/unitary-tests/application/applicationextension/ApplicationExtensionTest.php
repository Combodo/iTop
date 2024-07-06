<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Application\UI\Base\iUIBlockFactory;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
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
	 * TODO: remove when the refactoring is done
	 */
	public function testInterfaceDiscovery(): void
	{
		$oInterfaceDiscoveryService = InterfaceDiscovery::GetInstance();

		$this->AssertArraysHaveSameItems(
			[
				0 => 'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory',
				1 => 'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory',
				2 => 'Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory',
				3 => 'Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSectionUIBlockFactory',
				4 => 'Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory',
				5 => 'Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadgeUIBlockFactory',
				6 => 'Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory',
				7 => 'Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory',
				8 => 'Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory',
				9 => 'Combodo\iTop\Application\UI\Base\Component\Input\FileSelect\FileSelectUIBlockFactory',
				10 => 'Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory',
				11 => 'Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory',
				12 => 'Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory',
				13 => 'Combodo\iTop\Application\UI\Base\Component\Input\Set\SetUIBlockFactory',
				14 => 'Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory',
				15 => 'Combodo\iTop\Application\UI\Base\Component\Spinner\SpinnerUIBlockFactory',
				16 => 'Combodo\iTop\Application\UI\Base\Component\Template\TemplateUIBlockFactory',
				17 => 'Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory',
				18 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator\ToolbarSeparatorUIBlockFactory',
				19 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer\ToolbarSpacerUIBlockFactory',
				20 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory',
				21 => 'Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory',
				22 => 'Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory',
				23 => 'Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory',
				24 => 'Combodo\iTop\Application\UI\Links\Set\LinkSetUIBlockFactory',
			],
			$oInterfaceDiscoveryService->FindItopClasses(iUIBlockFactory::class)
		);
	}

	/**
	 * Protection test for Refactoring
	 * TODO: This test should be removed when the refactoring is done
	 * @--data--Provider HardcodedImplementationsProvider
	 */
	public function testGetClassesForInterfaceReturnsExactlySomething()
	{
		foreach ($this->InterfaceToExpectedClasses() as $sInterface => $aExpectedClasses) {
			$aClasses = utils::GetClassesForInterface($sInterface, '', ['[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]', '[\\\\/]tests[\\\\/]']);
			$this->AssertArraysHaveSameItems($aExpectedClasses, $aClasses, "Found unexpected classes extending the '$sInterface' API");
		}
	}

	public function InterfaceToExpectedClasses(): array
	{
		return [
			\iFieldRendererMappingsExtension::class => [
				'ExampleFor_iFieldRendererMappingsExtension',
				'Combodo\iTop\Renderer\Bootstrap\BsFieldRendererMappings',
				'Combodo\iTop\Renderer\Console\ConsoleFieldRendererMappings',
			],
			\iNewsroomProvider::class => [
				'ExampleFor_iNewsroomProvider',
				'HubNewsroomProvider',
				'Combodo\iTop\Application\Newsroom\iTopNewsroomProvider',
			],
			\iBackupExtraFilesExtension::class => [
				'ExampleFor_iBackupExtraFilesExtension',
			],
			\Combodo\iTop\Application\UI\Base\iUIBlockFactory::class => [
				0 => 'Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory',
				2 => 'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory',
				3 => 'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory',
				4 => 'Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory',
				5 => 'Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSectionUIBlockFactory',
				6 => 'Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory',
				7 => 'Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadgeUIBlockFactory',
				8 => 'Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory',
				9 => 'Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory',
				10 => 'Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory',
				11 => 'Combodo\iTop\Application\UI\Base\Component\Input\FileSelect\FileSelectUIBlockFactory',
				12 => 'Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory',
				13 => 'Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory',
				14 => 'Combodo\iTop\Application\UI\Base\Component\Input\Set\SetUIBlockFactory',
				15 => 'Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory',
				16 => 'Combodo\iTop\Application\UI\Base\Component\Spinner\SpinnerUIBlockFactory',
				17 => 'Combodo\iTop\Application\UI\Base\Component\Template\TemplateUIBlockFactory',
				18 => 'Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory',
				19 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator\ToolbarSeparatorUIBlockFactory',
				20 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer\ToolbarSpacerUIBlockFactory',
				21 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory',
				22 => 'Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory',
				23 => 'Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory',
				24 => 'Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory',
				25 => 'Combodo\iTop\Application\UI\Links\Set\LinkSetUIBlockFactory',
			],
			\Combodo\iTop\Controller\iController::class => [
				0 => 'Combodo\iTop\CoreUpdate\Controller\UpdateController',
				1 => 'Combodo\iTop\CoreUpdate\Controller\AjaxController',
				2 => 'Combodo\iTop\Controller\Base\Layout\ActivityPanelController',
				3 => 'Combodo\iTop\Controller\Base\Layout\ObjectController',
				4 => 'Combodo\iTop\Controller\Links\LinkSetController',
				5 => 'Combodo\iTop\Controller\Newsroom\iTopNewsroomController',
				6 => 'Combodo\iTop\Controller\Notifications\ActionController',
				7 => 'Combodo\iTop\Controller\Notifications\NotificationsCenterController',
				8 => 'Combodo\iTop\Controller\OAuth\OAuthLandingController',
				9 => 'Combodo\iTop\Controller\PreferencesController',
				10 => 'Combodo\iTop\Controller\TemporaryObjects\TemporaryObjectController',
				11 => 'Combodo\iTop\Controller\WelcomePopupController',
				12 => 'Combodo\iTop\OAuthClient\Controller\AjaxOauthClientController',
				13 => 'Combodo\iTop\OAuthClient\Controller\OAuthClientController',
			],
			\Combodo\iTop\Application\UI\Hook\iKeyboardShortcut::class => [
				0 => 'Combodo\iTop\Application\UI\Base\Component\GlobalSearch\GlobalSearch',
				1 => 'Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreate',
				2 => 'Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu',
				3 => 'Combodo\iTop\Application\UI\Base\Layout\Object\ObjectDetails',
				4 => 'Combodo\iTop\Application\UI\Base\Layout\Object\ObjectSummary',
			],
			\Combodo\iTop\Service\Events\iEventServiceSetup::class => [
				0 => 'Combodo\iTop\Application\EventRegister\ApplicationEvents',
				1 => 'Combodo\iTop\Core\EventListener\AttributeBlobEventListener',
				2 => 'Combodo\iTop\Service\TemporaryObjects\TemporaryObjectsEvents',
				3 => 'Combodo\iTop\Attachments\Hook\EventListener',
			],
			\iWelcomePopupExtension::class => [
				'Combodo\iTop\Application\WelcomePopup\Provider\DefaultProvider'
			],
			\iProcess::class => [
				0 => 'BackupExec',
				1 => 'BulkExportResultGC',
				2 => 'CheckStopWatchThresholds',
				3 => 'Combodo\iTop\Service\Notification\Event\EventNotificationNewsroomGC',
				4 => 'Combodo\iTop\Service\TemporaryObjects\TemporaryObjectGC',
				5 => 'Combodo\iTop\SessionTracker\SessionGC',
				6 => 'ExecAsyncTask',
				7 => 'InlineImageGC',
				8 => 'LogFileRotationProcess',
				9 => 'ObsolescenceDateUpdater',
			]
		];
	}

	/**
	 * TODO: This test should be removed when the refactoring is done
	 * Protection of the use case UIBlockExtension::getTokenParsers(), which uses a filter on the class, and does not exclude any directories
	 */
	public function testVerySpecificCallToGetClassesForInterfaces()
	{
		$this->AssertArraysHaveSameItems(
			[
				0 => 'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory',
				1 => 'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory',
				2 => 'Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory',
				3 => 'Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSectionUIBlockFactory',
				4 => 'Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory',
				5 => 'Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadgeUIBlockFactory',
				6 => 'Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory',
				7 => 'Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory',
				8 => 'Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory',
				9 => 'Combodo\iTop\Application\UI\Base\Component\Input\FileSelect\FileSelectUIBlockFactory',
				10 => 'Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory',
				11 => 'Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory',
				12 => 'Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory',
				13 => 'Combodo\iTop\Application\UI\Base\Component\Input\Set\SetUIBlockFactory',
				14 => 'Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory',
				15 => 'Combodo\iTop\Application\UI\Base\Component\Spinner\SpinnerUIBlockFactory',
				16 => 'Combodo\iTop\Application\UI\Base\Component\Template\TemplateUIBlockFactory',
				17 => 'Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory',
				18 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator\ToolbarSeparatorUIBlockFactory',
				19 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer\ToolbarSpacerUIBlockFactory',
				20 => 'Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory',
				21 => 'Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory',
				22 => 'Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory',
				23 => 'Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory',
				24 => 'Combodo\iTop\Application\UI\Links\Set\LinkSetUIBlockFactory',
			],
			utils::GetClassesForInterface(iUIBlockFactory::class, 'UIBlockFactory')
		);
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
				$iExtendingClassesCount = count(InterfaceDiscovery::GetInstance()->FindItopClasses($sAPIFQCN));
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
				static::ENUM_API_CALL_METHOD_GETCLASSESFORINTERFACE,
			],
			\iBackupExtraFilesExtension::class => [
				\iBackupExtraFilesExtension::class,
				static::ENUM_API_CALL_METHOD_GETCLASSESFORINTERFACE,
			],
		];
	}
}
