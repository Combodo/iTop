<?php
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

use Combodo\iTop\Application\UI\Component\Alert\AlertFactory;
use Combodo\iTop\Application\UI\Component\Button\ButtonFactory;
use Combodo\iTop\Application\UI\Component\Html\Html;
use Combodo\iTop\Application\UI\Layout\PageContent\PageContentFactory;
use iTopWebPage;

require_once '../../../approot.inc.php';
require_once APPROOT.'application/startup.inc.php';

$oPage = new iTopWebPage('Render all UI blocks');
$oPageContentLayout = PageContentFactory::MakeStandardEmpty();
$oPage->SetContentLayout($oPageContentLayout);

// Alerts
$sContent = <<<HTML
<div>The content text is made of raw HTML, therefore it must be sanitized before being injected into the component.</div>
<div>Here we put an hyperlink (<a href="#">link</a>) and a smiley (😻), just to see if it renders correctly</div>
HTML;

$oPageContentLayout->AddMainBlock(AlertFactory::MakeNeutral('Neutral alert', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForSuccess('Alert for success', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForWarning('Alert for warning', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForDanger('Alert for danger', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeForFailure('Alert for failure', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeWithBrandingPrimaryColor('Alert with branding primary color', $sContent));
$oPageContentLayout->AddMainBlock(AlertFactory::MakeWithBrandingSecondaryColor('Alert with branding secondary color', $sContent));

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));

// Buttons
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeNeutral('Neutral', 'neutral'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForPrimaryAction('Primary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForSecondaryAction('Secondary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForValidationAction('Validation'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForDestructiveAction('Destructive'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeAlternativeNeutral('Alt. neutral', 'alt-neutral'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativePrimaryAction('Alt. primary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeSecondaryAction('Alt. secondary'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeValidationAction('Alt. validation'));
$oPageContentLayout->AddMainBlock(ButtonFactory::MakeForAlternativeDestructiveAction('Alt. destructive'));

$oPageContentLayout->AddMainBlock(new Html('<hr/>'));


$oPage->output();
