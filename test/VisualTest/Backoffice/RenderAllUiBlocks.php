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
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroup;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletBadge;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTable;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSet;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Pill\PillFactory;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockWithJSRefreshCallback;
use iTopWebPage;
use LoginWebPage;
use MetaModel;

require_once '../../../approot.inc.php';
require_once APPROOT.'application/startup.inc.php';

LoginWebPage::DoLogin(); // Dependency for collapsible element with state saved, to get user pref

$oPage = new iTopWebPage('Render all UI blocks');
$oPageContentLayout = PageContentFactory::MakeStandardEmpty();
$oPage->SetContentLayout($oPageContentLayout);

$oPage->add_style(<<<CSS
hr {
	background-color: var(--ibo-color-grey-950);
}
CSS
);

$oMainTitle = new Html('<h1>All UI blocks examples</h1>');
$oPage->AddUiBlock($oMainTitle);

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

/////////
// Alerts
/////////
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Alerts examples', 2, 'title-alert'));
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
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('ButtonsJS examples', 2, 'title-buttonsjs'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeNeutral('Neutral'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeNeutral('Neutral dis.', 'neutral')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPrimaryAction('Primary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPrimaryAction('Primary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForSecondaryAction('Secondary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForSecondaryAction('Secondary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPositiveAction('Validation'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForPositiveAction('Validation dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForDestructiveAction('Destructive'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForDestructiveAction('Destructive dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeAlternativeNeutral('Alt. neutral'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeAlternativeNeutral('Alt. neutral dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativePrimaryAction('Alt. primary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativePrimaryAction('Alt. primary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeSecondaryAction('Alt. secondary'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeSecondaryAction('Alt. secondary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeValidationAction('Alt. validation'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeValidationAction('Alt. validation dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeDestructiveAction('Alt. destructive'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeForAlternativeDestructiveAction('Alt. destructive dis.')->SetIsDisabled(true));

$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('ButtonsURL examples', 2, 'title-buttonsurl'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeLinkNeutral('#', 'Link neutral'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeIconLink('fas fa-thumbs-up', 'Icon link button', '#'));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeLinkNeutral('#', 'Link primary')->SetColor(Button::ENUM_COLOR_SCHEME_PRIMARY));
$oPageContentLayout->AddMainBlock(ButtonUIBlockFactory::MakeIconLink('fas fa-thumbs-up', 'Icon link button primary', '#')->SetColor(Button::ENUM_COLOR_SCHEME_PRIMARY));

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

//////////////
// ButtonGroup
//////////////

$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('ButtonGroups examples: button + menu', 2, 'title-button-groupsmenu'));

$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeNeutral('Neutral with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForPrimaryAction('Primary with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForSecondaryAction('Secondary with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForPositiveAction('Validation with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForDestructiveAction('Destructive with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeAlternativeNeutral('Alt. neutral with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForAlternativePrimaryAction('Alt. primary with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForAlternativeSecondaryAction('Alt. secondary with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForAlternativeValidationAction('Alt. validation with options'),
	new PopoverMenu()
));
$oPageContentLayout->AddMainBlock(ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
	ButtonUIBlockFactory::MakeForAlternativeDestructiveAction('Alt. destructive with options'),
	new PopoverMenu()
));

$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('ButtonGroups examples: button + button + button', 2, 'title-button-groupsh'));

$oPageContentLayout->AddMainBlock(new ButtonGroup(
	[
		ButtonUIBlockFactory::MakeNeutral('Three'),
		ButtonUIBlockFactory::MakeNeutral('neutral'),
		ButtonUIBlockFactory::MakeNeutral('button'),
	]
));
$oPageContentLayout->AddMainBlock(new ButtonGroup(
	[
		ButtonUIBlockFactory::MakeForPrimaryAction('Three'),
		ButtonUIBlockFactory::MakeForPrimaryAction('primary'),
		ButtonUIBlockFactory::MakeForPrimaryAction('button'),
	]
));
$oPageContentLayout->AddMainBlock(new ButtonGroup(
	[
		ButtonUIBlockFactory::MakeAlternativeNeutral('Three'),
		ButtonUIBlockFactory::MakeAlternativeNeutral('primary'),
		ButtonUIBlockFactory::MakeAlternativeNeutral('alt. button'),
	]
));

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

/////////
// Panels
/////////
///
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Panels examples', 2, 'title-panels'));

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
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('ObjectDetails examples', 2, 'title-object-details'));

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
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Collapsible Sections examples', 2, 'title-collapsible'));

$sSectionContent = 'This is the section content !';
$oCollapsibleSection = new CollapsibleSection('Section title', [new Html($sSectionContent)]);
$oPage->AddUiBlock($oCollapsibleSection);

$oCollapsibleSectionSaveState = new CollapsibleSection('Section save state', [new Html($sSectionContent)]);
$oCollapsibleSectionSaveState->EnableSaveCollapsibleState('RenderAllUiBlocks__section');
$oPage->AddUiBlock($oCollapsibleSectionSaveState);

/////////
// Fieldset
/////////

$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Fieldset/field examples', 2));


$oDashletFieldset1 = new FieldSet('Fieldset 1');
$oDashletField1 = FieldUIBlockFactory::MakeStandard('Field A');
$oDashletInput1 = InputUIBlockFactory::MakeStandard('text', 'input1', 'Input 1');
$oDashletField2 = FieldUIBlockFactory::MakeStandard('Field B');
$oDashletInput2 = InputUIBlockFactory::MakeStandard('text', 'input2', 'Input 2');
$oDashletField3 = FieldUIBlockFactory::MakeStandard('Field C');
$oDashletInput3 = InputUIBlockFactory::MakeStandard('text', 'input3', 'Input 3');
$oDashletFieldset2 = new FieldSet('Fieldset 2');
$oDashletField4 = FieldUIBlockFactory::MakeStandard('Field D');
$oDashletField5 = FieldUIBlockFactory::MakeStandard('Field E');
$oDashletField6 = FieldUIBlockFactory::MakeStandard('Field F');
$oPage->AddUiBlock($oDashletFieldset1);
$oPage->AddUiBlock($oDashletFieldset2);
$oDashletFieldset1->AddSubBlock($oDashletField1);
$oDashletFieldset1->AddSubBlock($oDashletInput1);
$oDashletFieldset1->AddSubBlock($oDashletField2);
$oDashletFieldset1->AddSubBlock($oDashletInput2);
$oDashletFieldset1->AddSubBlock($oDashletField3);
$oDashletFieldset1->AddSubBlock($oDashletInput3);
$oDashletFieldset2->AddSubBlock($oDashletField4);
$oDashletFieldset2->AddSubBlock($oDashletField5);
$oDashletFieldset2->AddSubBlock($oDashletField6);

/////////
// Pill
/////////

$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Pill examples', 2 ));

$oBlock = new UIContentBlockWithJSRefreshCallback(null, ["ibo-dashlet-header-dynamic--container"]);
$oPage->AddUiBlock($oBlock);
$oPill1 = PillFactory::MakeForState('Person', 'active')->AddHtml("<span class=\"ibo-dashlet-header-dynamic--count\">8</span><span class=\"ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis\">active</span>");
$oPill2 = PillFactory::MakeForState('Person', 'inactive')->AddHtml("<span class=\"ibo-dashlet-header-dynamic--count\">8</span><span class=\"ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis\">inactive</span>");
$oPill3 = PillFactory::MakeForState('Person', 'closed')->AddHtml("<span class=\"ibo-dashlet-header-dynamic--count\">8</span><span class=\"ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis\">closed</span>");
$oPill4 = PillFactory::MakeForState('Person', 'new')->AddHtml("<span class=\"ibo-dashlet-header-dynamic--count\">8</span><span class=\"ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis\">new</span>");
$oPill5 = PillFactory::MakeForState('Person', 'waiting')->AddHtml("<span class=\"ibo-dashlet-header-dynamic--count\">8</span><span class=\"ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis\">waiting</span>");
$oPill6 = PillFactory::MakeForState('Person', 'escalated')->AddHtml("<span class=\"ibo-dashlet-header-dynamic--count\">8</span><span class=\"ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis\">escalated</span>");
$oBlock->AddSubBlock($oPill1);
$oBlock->AddSubBlock($oPill2);
$oBlock->AddSubBlock($oPill3);
$oBlock->AddSubBlock($oPill4);
$oBlock->AddSubBlock($oPill5);
$oBlock->AddSubBlock($oPill6);

/////////
// Title
/////////

$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Title examples', 2 ));
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Title example 1', 1 ));
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Title example 2', 2 ));
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Title example 3', 3 ));
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Title example 4', 4 ));
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Title example 5', 5 ));

/////////
// DataTable
/////////
$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral('Datatable examples', 2 ));

$oPage->AddUiBlock(DataTableUIBlockFactory::MakeForStaticData('Static datatable',
	array(
		'a' => array('label' => 'a'),
		'b' => array('label' => 'b'),
		'c' => array('label' => 'c'),
		'd' => array('label' => 'd')
	),
	array(
		array(
	'a' => 'A1', 'b' => 'B1', 'c' => 'C1', 'd' => 'D1'
	), array(
	'a' => 'A2', 'b' => 'B2', 'c' => 'C2', 'd' => 'D2'
	), array(
    'a' => 'A3', 'b' => 'B3', 'c' => 'C3', 'd' => 'D3'
	), array(
	'a' => 'A4', 'b' => 'B4', 'c' => 'C4', 'd' => 'D4'
	),array(
		'@class' => 'ibo-is-red','a' => 'A5', 'b' => 'B5', 'c' => 'C5', 'd' => 'D5'
	),array(
		'@class' => 'ibo-is-danger','a' => 'A6', 'b' => 'B6', 'c' => 'C6', 'd' => 'D6'
	),array(
		'@class' => 'ibo-is-orange','a' => 'A7', 'b' => 'B7', 'c' => 'C7', 'd' => 'D7'
	),array(
		'@class' => 'ibo-is-warning','a' => 'A8', 'b' => 'B8', 'c' => 'C8', 'd' => 'D8'
	),array(
		'@class' => 'ibo-is-blue','a' => 'A9', 'b' => 'B9', 'c' => 'C9', 'd' => 'D9'
	),array(
		'@class' => 'ibo-is-info','a' => 'A10', 'b' => 'B10', 'c' => 'C10', 'd' => 'D10'
	),array(
		'@class' => 'ibo-is-green','a' => 'A11', 'b' => 'B11', 'c' => 'C11', 'd' => 'D11'
	),array(
		'@class' => 'ibo-is-success','a' => 'A12', 'b' => 'B12', 'c' => 'C12', 'd' => 'D12'
	),
)));

$oPage->output();
