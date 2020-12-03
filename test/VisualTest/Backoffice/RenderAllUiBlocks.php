<?php

/** @noinspection PhpUnhandledExceptionInspection */

/*
 * Copyright (C) 2013-2020 Combodo SARL
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

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use iTopWebPage;
use utils;

require_once '../../../approot.inc.php';
require_once APPROOT.'application/startup.inc.php';

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

$oMainTitle = new Html('<h1>Buttons examples</h1>');
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

$oPageContentLayout->AddMainBlock(AlertFactory::MakeNeutral('Neutral alert', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForInformation('Alert for information', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForSuccess('Alert for success', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForWarning('Alert for warning', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForDanger('Alert for danger', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForFailure('Alert for failure', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeWithBrandingPrimaryColor('Alert with branding primary color', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeWithBrandingSecondaryColor('Alert with branding secondary color', $sContent));

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

//////////
// Buttons
//////////
$oButtonsTitle = new Html('<h2 id="title-buttons">Buttons examples</h2>');
$oPage->AddUiBlock($oButtonsTitle);
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeNeutral('Neutral', 'neutral'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeNeutral('Neutral dis.', 'neutral')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForPrimaryAction('Primary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForPrimaryAction('Primary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForSecondaryAction('Secondary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForSecondaryAction('Secondary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForPositiveAction('Validation'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForPositiveAction('Validation dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForDestructiveAction('Destructive'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForDestructiveAction('Destructive dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeAlternativeNeutral('Alt. neutral', 'alt-neutral'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeAlternativeNeutral('Alt. neutral dis.', 'alt-neutral')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativePrimaryAction('Alt. primary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativePrimaryAction('Alt. primary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeSecondaryAction('Alt. secondary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeSecondaryAction('Alt. secondary dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeValidationAction('Alt. validation'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeValidationAction('Alt. validation dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeDestructiveAction('Alt. destructive'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeDestructiveAction('Alt. destructive dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeLinkNeutral(utils::GetAbsoluteUrlAppRoot(), 'Link neutral'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeLinkNeutral(utils::GetAbsoluteUrlAppRoot(), 'Link neutral dis.')->SetIsDisabled(true));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeIconLink('fas fa-thumbs-up', 'This is the tooltip content'));

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

/////////
// Panels
/////////
$oPanelsTitle = new Html('<h2 id="title-panels">Panels examples</h2>');
$oPage->AddUiBlock($oPanelsTitle);
$aSubBlocks = [
	new Html('<div>Panel body, can contain anything from simple text to rich text, forms, images, <a href="#">links</a>, graphs or tables.</div>'),
	new Html('<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>'),
];

$oPanel = PanelFactory::MakeNeutral('Neutral panel');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelFactory::MakeForInformation('Panel for information');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelFactory::MakeForSuccess('Panel for success');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelFactory::MakeForWarning('Panel for warning');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelFactory::MakeForDanger('Panel for danger');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelFactory::MakeForFailure('Panel for failure');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelFactory::MakeWithBrandingPrimaryColor('Panel with branding primary color');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPanel = PanelFactory::MakeWithBrandingSecondaryColor('Panel with branding secondary color');
$oPanel->SetSubBlocks($aSubBlocks);
$oPageContentLayout->AddMainBlock($oPanel);

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));


$oPage->output();
