<?php

/** @noinspection PhpUnhandledExceptionInspection */

/*
 * Copyright (C) 2013-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\VisualTest\Backoffice;

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use iTopWebPage;
use LoginWebPage;
use MetaModel;
use utils;

require_once '../../../approot.inc.php';
require_once APPROOT.'application/startup.inc.php';

LoginWebPage::DoLogin(); // Dependency for collapsible element with state saved, to get user pref

$oPage = new iTopWebPage('Render all UI blocks');
$oPageContentLayout = PageContentFactory::MakeStandardEmpty();
$oPage->SetContentLayout($oPageContentLayout);

$oPage->add_style(<<<CSS
h1, h2 {
	font-size: initial;
	font-weight: initial;
	margin: initial;
	padding: initial;
}
h1 {
	text-align: center;
}

hr {
	background-color: black;
}
CSS
);

$oMainTitle = new Html('<h1>All UI blocks examples</h1>');
$oPage->AddUiBlock($oMainTitle);

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

/////////
// Alerts
/////////
$oAlertsTitle = new Html('<h2 id="title-alerts">Alerts examples</h2>');
$oPage->AddUiBlock($oAlertsTitle);
$sContent = <<<HTML
<div>The content text is made of raw HTML, therefore it must be sanitized before being injected into the component.</div>
<div>Here we put an hyperlink (<a href="#">link</a>) and a smiley (😻), just to see if it renders correctly</div>
HTML;

$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeNeutral('Neutral alert', $sContent));
$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeForInformation('Alert for information', $sContent));
$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeForSuccess('Alert for success', $sContent));
$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeForWarning('Alert for warning', $sContent));
$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeForDanger('Alert for danger', $sContent));
$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeForFailure('Alert for failure', $sContent));
$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeWithBrandingPrimaryColor('Alert with branding primary color', $sContent));
$oPageContentLayout->AddMainBlock(AlertUIBlockFactory::MakeWithBrandingSecondaryColor('Alert with branding secondary color', $sContent));
$oAlertNonClosable = AlertUIBlockFactory::MakeNeutral('Alert not closable, not collapsable', $sContent)
	->SetIsClosable(false)
	->SetIsCollapsible(false);
$oPageContentLayout->AddMainBlock($oAlertNonClosable);
$oAlertCollapsibleNotClosable = AlertUIBlockFactory::MakeNeutral('Alert collapsible but nos closable', $sContent)
	->SetIsClosable(false);
$oPageContentLayout->AddMainBlock($oAlertCollapsibleNotClosable);
$oAlertSaveCollapsibleState = AlertUIBlockFactory::MakeNeutral('Alert with collapsible state saving', $sContent)
	->EnableSaveCollapsibleState('RenderAllUiBlocks-alert');
$oPageContentLayout->AddMainBlock($oAlertSaveCollapsibleState);

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

//////////
// Buttons
//////////
$oButtonsTitle = new Html('<h2 id="title-buttons">Buttons examples</h2>');
$oPage->AddUiBlock($oButtonsTitle);
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeNeutral('Neutral', 'neutral'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeNeutral('Neutral dis.', 'neutral')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPrimaryAction('Primary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPrimaryAction('Primary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForSecondaryAction('Secondary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForSecondaryAction('Secondary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPositiveAction('Validation'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPositiveAction('Validation dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForDestructiveAction('Destructive'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForDestructiveAction('Destructive dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeAlternativeNeutral('Alt. neutral', 'alt-neutral'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeAlternativeNeutral('Alt. neutral dis.', 'alt-neutral')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativePrimaryAction('Alt. primary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativePrimaryAction('Alt. primary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeSecondaryAction('Alt. secondary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeSecondaryAction('Alt. secondary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeValidationAction('Alt. validation'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeValidationAction('Alt. validation dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeDestructiveAction('Alt. destructive'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeDestructiveAction('Alt. destructive dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeLinkNeutral(utils::GetAbsoluteUrlAppRoot(), 'Link neutral'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeLinkNeutral(utils::GetAbsoluteUrlAppRoot(), 'Link neutral dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeIconLink('fas fa-thumbs-up', 'Icon link button'));

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

/////////
// Panels
/////////
$oPanelsTitle = new Html('<h2 id="title-panels">Panels examples</h2>');
$oPage->AddUiBlock($oPanelsTitle);

$aSubBlocks = [
	new Html('<div>Panel body, can contain anything from simple text to rich text, forms, images, <a href="#">links</a>, graphs or tables.</div>'),
	new Html('<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>'),
];
$sClassIconUrl = MetaModel::GetClassIcon('Organization', false);

$oPanel = PanelUIBlockFactory::MakeNeutral('Neutral panel');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeForInformation('Panel for information');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeForSuccess('Panel for success');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeForWarning('Panel for warning');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeForDanger('Panel for danger');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeForFailure('Panel for failure');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeWithBrandingPrimaryColor('Panel with branding primary color');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeWithBrandingSecondaryColor('Panel with branding secondary color');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeNeutral('Panel with title only');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeNeutral('')
	->SetSubBlocks($aSubBlocks)
	->SetSubTitle('Panel with subtitle only');
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeNeutral('Panel with title and subtitle')
	->SetSubBlocks($aSubBlocks)
	->SetSubTitle('Subtitle');
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeNeutral('Panel with title and icon')
	->SetSubBlocks($aSubBlocks)
	->SetIcon($sClassIconUrl);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeNeutral('')
	->SetSubBlocks($aSubBlocks)
	->SetSubTitle('Panel with subtitle and icon')
	->SetIcon($sClassIconUrl);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeNeutral('Panel with title, subtitle and icon')
	->SetSubBlocks($aSubBlocks)
	->SetSubTitle('Subtitle')
	->SetIcon($sClassIconUrl);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelUIBlockFactory::MakeNeutral('Panel with title, subtitle and icon as a medallion')
	->SetSubBlocks($aSubBlocks)
	->SetSubTitle('Subtitle')
	->SetIcon($sClassIconUrl, Panel::ENUM_ICON_COVER_METHOD_ZOOMOUT, true);
$oPageContentLayout->AddMainBlock($oPanel);

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

/////////
// ObjectDetails
/////////
$oObjecTDetailsTitle = new Html('<h2 id="title-object-details">ObjectDetails examples</h2>');
$oPage->AddUiBlock($oObjecTDetailsTitle);

$oOrgObject = MetaModel::NewObject('Organization');
$oOrgObject->Set('name', 'Stub, no tab container. Just to see how the header is displayed');
$oOrgObject->Set('status', 'active');

$oObjectDetails = ObjectFactory::MakeDetails($oOrgObject);
$oPageContentLayout->AddMainBlock($oObjectDetails);
$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB, '', $oObjectDetails);
$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
$oPage->SetCurrentTab('First');
$oPage->add('Extra tabs icon is normal as there is no JS widget instantiated here.');
$oPage->SetCurrentTab('Second');
$oPage->SetCurrentTab('Third');
$oPage->SetCurrentTab('Fourth');
$oPage->SetCurrentTab('Fifth');
$oPage->SetCurrentTab('Sixth');
$oPage->SetCurrentTab('Seventh');
$oPage->SetCurrentTabContainer();

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

/////////
// Collapsible Section
/////////
$oCollapsibleSectionTitle = new Html('<h2 id="title-panels">Collapsible Sections examples</h2>');
$oPage->AddUiBlock($oCollapsibleSectionTitle);

$sSectionContent = 'This is the section content !';
$oCollapsibleSection = new CollapsibleSection('Section title', [new Html($sSectionContent)]);
$oPage->AddUiBlock($oCollapsibleSection);

$oCollapsibleSectionSaveState = new CollapsibleSection('Section save state', [new Html($sSectionContent)]);
$oCollapsibleSectionSaveState->EnableSaveCollapsibleState('RenderAllUiBlocks__section');
$oPage->AddUiBlock($oCollapsibleSectionSaveState);

$oPage->output();
