<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\UI\Base\UIBlock;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');
require_once(APPROOT.'setup/extensionsmap.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

$oAppContext = new ApplicationContext();

$oPage = new iTopWebPage(Dict::S('iTopHub:InstalledExtensions'));
$oPage->SetBreadCrumbEntry('ui-hub-myextensions', Dict::S('Menu:iTopHub:MyExtensions'), Dict::S('Menu:iTopHub:MyExtensions+'), '', 'fas fa-puzzle-piece', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);


function GetExtensionInfoComponent(iTopExtension $oExtension): UIBlock
{
	$sExtensionDescription = Dict::Format('UI:About:Extension_Version', $oExtension->sVersion);
	if (!empty($oExtension->sLabel)) {
		$sExtensionDescription .= '<br><i>'.$oExtension->sDescription.'</i>';
	}

	return AlertUIBlockFactory::MakeForInformation($oExtension->sLabel, $sExtensionDescription)
		->SetIsClosable(false)
		->SetIsCollapsible(true)
		->SetOpenedByDefault(false);
}


try {
	$oExtensionsMap = new iTopExtensionsMap();
	$oExtensionsMap->LoadChoicesFromDatabase(MetaModel::GetConfig());

	$oPage->AddUiBlock(TitleUIBlockFactory::MakeForPage(Dict::S('iTopHub:InstalledExtensions')));




	/**------------------------------------------------------------------------------------------------------
	 * Remotely deployed ext
	 */
	$oFieldsetRemote = FieldSetUIBlockFactory::MakeStandard(Dict::S('iTopHub:ExtensionCategory:Remote'));
	$oPage->AddUiBlock($oFieldsetRemote);

	$aRemotelyDeployedExt = array_filter($oExtensionsMap->GetAllExtensions(), static function ($oExtension) {
		return ($oExtension->sSource === iTopExtension::SOURCE_REMOTE);
	});
	$iRemotelyDeployedExtCount = count($aRemotelyDeployedExt);

	if ($iRemotelyDeployedExtCount === 0) {
		$oFieldsetRemote->AddSubBlock(
			AlertUIBlockFactory::MakeForWarning(Dict::S('iTopHub:NoExtensionInThisCategory'), Dict::S('iTopHub:NoExtensionInThisCategory+'))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	} else {
		$oFieldsetRemote->AddHtml('<p>'.Dict::S('iTopHub:ExtensionCategory:Remote+').'</p>');
		foreach ($aRemotelyDeployedExt as $oExtension) {
			$oFieldsetRemote->AddSubBlock(GetExtensionInfoComponent($oExtension));
		}
	}

	/**------------------------------------------------------------------------------------------------------
	 * Hub button
	 */
	$oHubButtonContainer = UIContentBlockUIBlockFactory::MakeStandard()
		->AddCSSClass('hub-button');
	$oPage->AddSubBlock($oHubButtonContainer);
	$sUrl = utils::GetAbsoluteUrlModulePage('itop-hub-connector', 'launch.php', array('target' => 'browse_extensions'));
	$oHubButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('iTopHub:GetMoreExtensions'), 'install-extensions-button')
		->SetOnClickJsCode("window.location.href='$sUrl'")
		->SetIconClass('fa-fw fc fc-itophub-icon fc-1-5x');
	$oHubButtonContainer->AddSubBlock($oHubButton);


	/**------------------------------------------------------------------------------------------------------
	 * Manually deployed ext
	 * Only if there are some !
	 */
	$aManuallyDeployedExt = array_filter($oExtensionsMap->GetAllExtensions(), static function ($oExtension) {
		return ($oExtension->sSource === iTopExtension::SOURCE_MANUAL);
	});
	$iManuallyDeployedExtCount = count($aManuallyDeployedExt);

	if ($iManuallyDeployedExtCount > 0) {
		$oFieldsetManual = FieldSetUIBlockFactory::MakeStandard(Dict::S('iTopHub:ExtensionCategory:Manual'));
		$oPage->AddUiBlock($oFieldsetManual);
		$oFieldsetManual->AddHtml(Dict::Format('iTopHub:ExtensionCategory:Manual+', '<span title="'.(APPROOT.'extensions').'" id="extension-dir-path">"extensions"</span>'));
		foreach ($aManuallyDeployedExt as $oExtension) {
			$oFieldsetManual->AddSubBlock(GetExtensionInfoComponent($oExtension));
		}
	}

	$sExtensionsDirTooltip = json_encode(APPROOT.'extensions');
	$oPage->add_style(
		<<<CSS
.hub-button {
	width: 100%;
	margin: 2rem;
	text-align: center;
}

#extension-dir-path {
	display: inline-block;
	border-bottom: 1px #999 dashed;
	cursor: help;
}
CSS
	);
}
catch (Exception $e) {
	$oPage->p('<b>'.Dict::Format('UI:Error_Details', $e->getMessage()).'</b>');
}

$oPage->output();
