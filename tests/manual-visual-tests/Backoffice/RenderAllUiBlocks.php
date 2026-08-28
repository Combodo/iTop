<?php

/** @noinspection PhpUnhandledExceptionInspection */

/*
 * Copyright (C) 2013-2024 Combodo SAS
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
use Combodo\iTop\Application\UI\Base\Component\Badge\BadgeUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonSeparator;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonBar\ButtonBarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroup;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSet;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\SetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Toggler;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Pill\PillFactory;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\Extension\ExtensionDetailsUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumn;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use LoginWebPage;
use MetaModel;
use URLPopupMenuItem;

require_once '../../../approot.inc.php';
require_once APPROOT.'application/startup.inc.php';

class RenderAllUiBlocksPage extends iTopWebPage
{
	public function __construct()
	{
		parent::__construct('Blocks Components Library');
		$this->InitLayout();
		$this->InitAssets();
		$this->BuildPageContent();
	}

	private function InitLayout(): void
	{
		$oPageMainLayout = PageContentFactory::MakeStandardEmpty();
		$this->SetContentLayout($oPageMainLayout);
	}

	private function InitAssets(): void
	{
		$this->LinkStylesheetFromAppRoot('js/highlight/styles/github.min.css');
		$this->LinkScriptFromAppRoot('js/highlight/highlight.min.js');
		$this->add_ready_script(
			<<<'JS'
(function() {
	if (!window.hljs) {
		return;
	}

	const aCodeBlocks = document.querySelectorAll('.ibo-is-code pre, .ibo-is-code code');
	aCodeBlocks.forEach((oBlock) => {
		if (window.hljs.highlightElement) {
			window.hljs.highlightElement(oBlock);
			return;
		}
		if (window.hljs.highlightBlock) {
			window.hljs.highlightBlock(oBlock);
		}
	});

	const copyTextToClipboard = async (sText) => {
		if (navigator.clipboard && window.isSecureContext) {
			await navigator.clipboard.writeText(sText);
			return;
		}

		const oTextArea = document.createElement('textarea');
		oTextArea.value = sText;
		oTextArea.style.position = 'fixed';
		oTextArea.style.left = '-9999px';
		document.body.appendChild(oTextArea);
		oTextArea.focus();
		oTextArea.select();
		document.execCommand('copy');
		document.body.removeChild(oTextArea);
	};

	aCodeBlocks.forEach((oBlock) => {
		if (oBlock.dataset.copyButtonInjected === '1') {
			return;
		}

		oBlock.dataset.copyButtonInjected = '1';
		const oWrapper = document.createElement('div');
		oWrapper.className = 'ibo-code-copy-wrapper';
		oBlock.parentNode.insertBefore(oWrapper, oBlock);
		oWrapper.appendChild(oBlock);

		const oButton = document.createElement('button');
		oButton.type = 'button';
		oButton.className = 'ibo-code-copy-button ibo-button  ibo-block ibo-is-regular ';
		oButton.textContent = 'Copier';
		oButton.addEventListener('click', async () => {
			try {
				await copyTextToClipboard(oBlock.innerText || oBlock.textContent || '');
				oButton.textContent = 'Copiee';
			} catch (e) {
				oButton.textContent = 'Echec';
			}
			setTimeout(() => {
				oButton.textContent = 'Copier';
			}, 1200);
		});

		oWrapper.appendChild(oButton);
	});
})();
JS
		);

		$this->add_style(<<<CSS
body{
	color: var(--ibo-body-text-color);
}
hr {
	background-color: var(--ibo-color-grey-950);
}
.ibo-code-copy-wrapper {
	position: relative;
}
.ibo-code-copy-button {
	position: absolute;
	top: 0.4rem;
	right: 0.4rem;
	z-index: 1;
}
.ibo-code-copy-button:hover {
	background: var(--ibo-color-grey-200);
}
.ibo-render-all--two-col-row {
	margin-bottom: 1rem;
}
CSS);
	}

	private function AddCreationCodeSnippet(iUIContentBlock $oBlock, string $sCode, string $sLanguage = 'language-php'): void
	{
		$oBlock->AddSubBlock(
			UIContentBlockUIBlockFactory::MakeForPreformatted(trim($sCode))
				->AddCSSClass($sLanguage)
		);
	}

	private function AddElementWithSnippet(iUIContentBlock $oBlock, $oElement, string $sCode, string $sLanguage = 'language-php'): void
	{
		$oBlock->AddSubBlock($oElement);
		$this->AddCreationCodeSnippet($oBlock, $sCode, $sLanguage);
	}

	private function BuildPageContent(): void
	{
		// panel
		$oPanel = PanelUIBlockFactory::MakeForInformation($this->s_title, 'This page is a visual test for all the UI blocks available in iTop. It is not meant to be used in production.');
		$this->AddSubBlock($oPanel);

		// tabs host
		$oTabsHost = UIContentBlockUIBlockFactory::MakeStandard('render-all-tabs-host');
		$this->AddTabContainer('render-all-tabs', '', $oTabsHost);
		$this->SetCurrentTabContainer('render-all-tabs');
		$oPanel->AddSubBlock($oTabsHost);

		// titles
		$this->SetCurrentTab('tab-titles', 'Titles');
		$this->RenderTitleSizeSection();
		$this->RenderTitleAlternativeSection();

		// pills
		$this->SetCurrentTab('tab-pills', 'Pills');
		$this->RenderPillSection();

		// badges
		$this->SetCurrentTab('tab-badges', 'Badges');
		$this->RenderBadgesSection();

		// buttons
		$this->SetCurrentTab('tab-buttons', 'Buttons');
		$this->RenderButtonsSection();
		$this->RenderTogglerSection();
		$this->RenderButtonGroupsSection();
		$this->RenderButtonBarsSection();

		// panels
		$this->SetCurrentTab('tab-panels', 'Panels');
		$this->RenderPanelsSection();
		$this->RenderMultiColumns();
		$this->RenderCollapsibleSection();
		$this->RenderFieldsetSection();

		// alerts
		$this->SetCurrentTab('tab-alerts', 'Alerts');
		$this->RenderAlertsBasicSection();
		$this->RenderAlertsBrandingSection();
		$this->RenderAlertsBehaviourSection();

		// sets
		$this->SetCurrentTab('tab-sets', 'Sets');
		$this->RenderSetSection();

		// tables
		$this->SetCurrentTab('tab-tables', 'Tables');
		$this->RenderDatatableSection();

		// code
		$this->SetCurrentTab('tab-codes', 'CodeBlocks');
		$this->RenderCodeSection();

		// functional
		$this->SetCurrentTab('tab-functional', 'Functional');
		$this->RenderObjectDetailsSection();
		$this->RenderExtensionsSection();
	}

	private function RenderAlertsBasicSection(): void
	{
		$oFieldsSet = FieldSetUIBlockFactory::MakeStandard('Basic');

		$sContent = <<<HTML
<div>The content text is made of raw HTML, therefore it must be sanitized before being injected into the component.</div>
<div>Here we put an hyperlink (<a href="#">link</a>) and a smiley (😻), just to see if it renders correctly</div>
HTML;
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeNeutral(sTitle: 'Neutral alert', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeNeutral(sTitle: 'Neutral alert', sContent: $sContent);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeForInformation(sTitle: 'Alert for information', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeForInformation(sTitle: 'Alert for information', sContent: $sContent);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeForSuccess(sTitle: 'Alert for success', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeForSuccess(sTitle: 'Alert for success', sContent: $sContent);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeForWarning(sTitle: 'Alert for warning', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeForWarning(sTitle: 'Alert for warning', sContent: $sContent);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeForDanger(sTitle: 'Alert for danger', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeForDanger(sTitle: 'Alert for danger', sContent: $sContent);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeForFailure(sTitle: 'Alert for failure', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeForFailure(sTitle: 'Alert for failure', sContent: $sContent);
PHP
		);
		$this->AddUiBlock($oFieldsSet);
	}

	private function RenderAlertsBrandingSection(): void
	{
		$oFieldsSet = FieldSetUIBlockFactory::MakeStandard('Basic');

		$sContent = <<<HTML
<div>The content text is made of raw HTML, therefore it must be sanitized before being injected into the component.</div>
<div>Here we put an hyperlink (<a href="#">link</a>) and a smiley (😻), just to see if it renders correctly</div>
HTML;

		$oFieldsSet = FieldSetUIBlockFactory::MakeStandard('Branding colors');
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeWithBrandingPrimaryColor(sTitle: 'Alert with branding primary color', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeWithBrandingPrimaryColor(sTitle: 'Alert with branding primary color', sContent: $sContent);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeWithBrandingSecondaryColor(sTitle: 'Alert with branding secondary color', sContent: $sContent),
			<<<'PHP'
AlertUIBlockFactory::MakeWithBrandingSecondaryColor(sTitle: 'Alert with branding secondary color', sContent: $sContent);
PHP
		);
		$this->AddUiBlock($oFieldsSet);
	}

	private function RenderAlertsBehaviourSection(): void
	{
		$oFieldsSet = FieldSetUIBlockFactory::MakeStandard('Behaviors');
		$this->AddUiBlock($oFieldsSet);

		$sContent = <<<HTML
<div>The content text is made of raw HTML, therefore it must be sanitized before being injected into the component.</div>
<div>Here we put an hyperlink (<a href="#">link</a>) and a smiley (😻), just to see if it renders correctly</div>
HTML;

		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeNeutral(sTitle: 'Alert not closable, not collapsable', sContent: $sContent)->SetIsClosable(false)->SetIsCollapsible(false),
			<<<'PHP'
AlertUIBlockFactory::MakeNeutral(sTitle: 'Alert not closable, not collapsable', sContent: $sContent)
		->SetIsClosable(false)
		->SetIsCollapsible(false);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeNeutral(sTitle: 'Alert collapsible but nos closable', sContent: $sContent)->SetIsClosable(false),
			<<<'PHP'
AlertUIBlockFactory::MakeNeutral(sTitle: 'Alert collapsible but nos closable', sContent: $sContent)
		->SetIsClosable(false);
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldsSet,
			AlertUIBlockFactory::MakeNeutral(sTitle: 'Alert with collapsible state saving', sContent: $sContent)->EnableSaveCollapsibleState('RenderAllUiBlocks-alert'),
			<<<'PHP'
	AlertUIBlockFactory::MakeNeutral(sTitle: 'Alert with collapsible state saving', sContent: $sContent)
		->EnableSaveCollapsibleState('RenderAllUiBlocks-alert');
PHP
		);
	}

	private function RenderButtonsSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Basic');

		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeNeutral(sLabel: 'Neutral'),
			<<<'PHP'
ButtonUIBlockFactory::MakeNeutral(sLabel: 'Neutral');
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeForPrimaryAction(sLabel: 'Primary'),
			<<<'PHP'
ButtonUIBlockFactory::MakeForPrimaryAction(sLabel: 'Primary');
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeForSecondaryAction(sLabel: 'Secondary'),
			<<<'PHP'
ButtonUIBlockFactory::MakeForSecondaryAction(sLabel: 'Secondary');
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeForPositiveAction(sLabel: 'Validation'),
			<<<'PHP'
ButtonUIBlockFactory::MakeForPositiveAction(sLabel: 'Validation');
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeForDestructiveAction(sLabel: 'Destructive'),
			<<<'PHP'
ButtonUIBlockFactory::MakeForDestructiveAction(sLabel: 'Destructive');
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeAlternativeNeutral(sLabel: 'Alt. neutral'),
			<<<'PHP'
ButtonUIBlockFactory::MakeAlternativeNeutral(sLabel: 'Alt. neutral');
PHP
		);
		$this->AddUiBlock($oFieldSet);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Links');
		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeLinkNeutral(sURL: '#', sLabel: 'Link neutral'),
			<<<'PHP'
ButtonUIBlockFactory::MakeLinkNeutral(sURL: '#', sLabel: 'Link neutral');
PHP
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonUIBlockFactory::MakeIconLink(sIconClasses: 'fas fa-thumbs-up', sTooltipText: 'Icon link button', sURL:'#'),
			<<<'PHP'
ButtonUIBlockFactory::MakeIconLink(sIconClasses:'fas fa-thumbs-up', sTooltipText: 'Icon link button', sURL: '#');
PHP
		);
		$this->AddUiBlock($oFieldSet);
	}

	private function RenderButtonGroupsSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Popover Menu');

		$oPopoverMenu = new PopoverMenu();
		$oPopoverMenu->AddItems(sSectionId: 'Section', aItems: [
			PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(new URLPopupMenuItem('option1', 'Option 1', '#')),
			PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(new URLPopupMenuItem('option2', 'Option 2', '#')),
			PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(new URLPopupMenuItem('option3', 'Option 3', '#')),
		]);

		$this->AddElementWithSnippet(
			$oFieldSet,
			ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(
				ButtonUIBlockFactory::MakeNeutral('Button With Options'),
				$oPopoverMenu
			),
			<<<'PHP'
$oPopoverMenu = new PopoverMenu();
$oPopoverMenu->AddItems(sSectionId: 'Section 1', aItems: [
	PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(new URLPopupMenuItem('option1', 'Option 1', '#')),
	PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(new URLPopupMenuItem('option2', 'Option 2', '#')),
	PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(new URLPopupMenuItem('option3', 'Option 3', '#')),
]);
ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu(ButtonUIBlockFactory::MakeNeutral('Neutral with options'), new PopoverMenu());
PHP
		);
		$this->AddUiBlock($oFieldSet);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Button Group');

		$this->AddElementWithSnippet(
			$oFieldSet,
			new ButtonGroup(
				aButtons: [
					ButtonUIBlockFactory::MakeNeutral('Three'),
					ButtonUIBlockFactory::MakeNeutral('neutral'),
					ButtonUIBlockFactory::MakeNeutral('button'),
				]
			),
			<<<'PHP'
new ButtonGroup(aButtons: [
		ButtonUIBlockFactory::MakeNeutral('Three'),
		ButtonUIBlockFactory::MakeNeutral('neutral'),
		ButtonUIBlockFactory::MakeNeutral('button'),
	]
);
PHP
		);
		$this->AddUiBlock($oFieldSet);
	}

	private function RenderButtonBarsSection(): void
	{
		$this->add_style(<<<CSS
.btn-bar-710{
	max-width: 710px;
}
CSS);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Buttons Bar With Overflow (710px max)');

		$oBtn1 = ButtonUIBlockFactory::MakeNeutral('Action 1');
		$oBtn1->SetIconClass('fas fa-thumbs-up');

		$oBtn5 = ButtonUIBlockFactory::MakeNeutral('Action 5');
		$oBtn5->SetIconClass('fas fa-thumbs-down');

		$oBtn11 = ButtonUIBlockFactory::MakeNeutral('Action 11');
		$oBtn11->SetIconClass('fas fa-bomb');

		$oBtnJS = ButtonUIBlockFactory::MakeNeutral('Action JS');
		$oBtnJS->SetOnClickJsCode('alert("Hello World!");console.log(this);');
		$oBtnJS->SetIconClass('fas fa-bolt');

		$oButtonBar = ButtonBarUIBlockFactory::MakeStandard(
			aItems: [
				$oBtn1,
				ButtonUIBlockFactory::MakeNeutral('Action 2'),
				ButtonUIBlockFactory::MakeNeutral('Action 3'),
				ButtonUIBlockFactory::MakeNeutral('Action 4'),
				new ButtonSeparator(),
				$oBtn5,
				ButtonUIBlockFactory::MakeNeutral('Action 6'),
				ButtonUIBlockFactory::MakeNeutral('Action 7'),
				ButtonUIBlockFactory::MakeNeutral('Action 8'),
				ButtonUIBlockFactory::MakeNeutral('Action 9'),
				ButtonUIBlockFactory::MakeNeutral('Action 10'),
				new ButtonSeparator(),
				$oBtnJS,
				$oBtn11,
				ButtonUIBlockFactory::MakeNeutral('Action 12'),
				ButtonUIBlockFactory::MakeNeutral('Action 13'),
				ButtonUIBlockFactory::MakeLinkNeutral('https://www.combodo.com', 'Combodo.com'),
				ButtonUIBlockFactory::MakeNeutral('Action 15'),
				ButtonUIBlockFactory::MakeNeutral('Action 16'),
			],
			sMoreButtonTooltipText: 'See overflow actions',
			sId: 'button-bar-test1'
		);
		$oButtonBar->AddCSSClass('btn-bar-710');
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oButtonBar,
			<<<'PHP'
$oButtonBar = ButtonBarUIBlockFactory::MakeStandard(aItems: [
		ButtonUIBlockFactory::MakeNeutral('Action 1'),
		ButtonUIBlockFactory::MakeNeutral('Action 2'),
		ButtonUIBlockFactory::MakeNeutral('Action 3'),
		...
	],
	sMoreButtonTooltipText: 'tooltip text for overflow actions',
	sId: 'button-bar-id'
);
PHP
		);
		$this->AddUiBlock($oFieldSet);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Buttons Bar With Fixed elements (3 items)');

		$oButtonBar = ButtonBarUIBlockFactory::MakeWithCountOverflow(
			aItems: [
				ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-up', sTooltipText: 'Promote this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-down', sTooltipText: 'Demote this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-bomb', sTooltipText: 'Explode this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-wrench', sTooltipText: 'Fix this entry'),
			],
			iOverflowCount: 3,
			sMoreButtonTooltipText: 'See overflow actions',
			sId: 'button-bar-test4'
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oButtonBar,
			<<<'PHP'
$oButtonBar = ButtonBarUIBlockFactory::MakeWithCountOverflow(aItems: [
		ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-up', sTooltipText: 'Promote this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-down', sTooltipText: 'Demote this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-bomb', sTooltipText: 'Explode this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-wrench', sTooltipText: 'Fix this entry'),
	],
	iOverflowCount: 3,
	sMoreButtonTooltipText: 'See overflow actions',
	sId: 'button-bar-test4'
);
PHP
		);
		$this->AddUiBlock($oFieldSet);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Buttons Bar With Marker');
		$oButtonBar = ButtonBarUIBlockFactory::MakeWithAfterMarkerOverflow(
			sOverflowStartAfterButtonId: 'pivot-button',
			aItems: [
				ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-up', sTooltipText: 'Promote this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-down', sTooltipText: 'Demote this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-search', sTooltipText: 'Search this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-tag', sTooltipText: 'Tag this entry', sId: 'pivot-button'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-tasks', sTooltipText: 'Assign this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-bomb', sTooltipText: 'Explode this entry'),
				new ButtonSeparator(),
				ButtonUIBlockFactory::MakeIconAction('fas fa-trash-alt', sTooltipText: 'Delete this entry'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-swimming-pool', sTooltipText: 'Je ne peux pas j\'ai Piscine'),
				ButtonUIBlockFactory::MakeIconAction('fas fa-wrench', sTooltipText: 'Fix this entry'),
			],
			sMoreButtonTooltipText:  'tooltip text for overflow actions',
			sId:'button-bar-id'
		);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oButtonBar,
			<<<'PHP'
$oButtonBar = ButtonBarUIBlockFactory::MakeWithAfterMarkerOverflow(sOverflowStartAfterButtonId: 'pivot-button',
	aItems: [
		ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-up', sTooltipText: 'Promote this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-down', sTooltipText: 'Demote this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-search', sTooltipText: 'Search this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-tag', sTooltipText: 'Tag this entry', sId: 'pivot-button'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-tasks', sTooltipText: 'Assign this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-bomb', sTooltipText: 'Explode this entry'),
		new ButtonSeparator(),
		ButtonUIBlockFactory::MakeIconAction('fas fa-trash-alt', sTooltipText: 'Delete this entry'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-swimming-pool', sTooltipText: 'Je ne peux pas j\'ai Piscine'),
		ButtonUIBlockFactory::MakeIconAction('fas fa-wrench', sTooltipText: 'Fix this entry'),
	],
	sMoreButtonTooltipText:  'tooltip text for overflow actions',
	sId:'button-bar-id'
);
PHP
		);
		$this->AddUiBlock($oFieldSet);
	}

	private function RenderPanelsSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Basic');
		$this->AddUiBlock($oFieldSet);

		$aSubBlocks = [
			new Html('<div>Panel body, can contain anything from simple text to rich text, forms, images, <a href="#">links</a>, graphs or tables.</div>'),
			new Html('<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>'),
		];

		$oPanel = PanelUIBlockFactory::MakeNeutral(sTitle: 'Neutral panel');
		$oPanel->SetSubBlocks($aSubBlocks);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeNeutral(sTitle: 'Neutral Panel');
PHP
		);

		$oPanel = PanelUIBlockFactory::MakeForInformation('Panel for information');
		$oPanel->SetSubBlocks($aSubBlocks);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeForInformation(sTitle: 'Panel for information');
PHP
		);

		$oPanel = PanelUIBlockFactory::MakeForSuccess('Panel for success');
		$oPanel->SetSubBlocks($aSubBlocks);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeForSuccess(sTitle: 'Panel for success');
PHP
		);

		$oPanel = PanelUIBlockFactory::MakeForWarning('Panel for warning');
		$oPanel->SetSubBlocks($aSubBlocks);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeForWarning(sTitle: 'Panel for warning');
PHP
		);

		$oPanel = PanelUIBlockFactory::MakeForDanger('Panel for danger');
		$oPanel->SetSubBlocks($aSubBlocks);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeForDanger(sTitle: 'Panel for danger');
PHP
		);

		$oPanel = PanelUIBlockFactory::MakeForFailure('Panel for failure');
		$oPanel->SetSubBlocks($aSubBlocks);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeForFailure(sTitle: 'Panel for failure');
PHP
		);

		// title and subtitle
		$oPanel = PanelUIBlockFactory::MakeNeutral('Panel with title and subtitle')
			->SetSubBlocks($aSubBlocks)
			->SetSubTitle('Subtitle');
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeNeutral('Panel with title and subtitle')
	->SetSubBlocks($aSubBlocks)
	->SetSubTitle('Subtitle');
PHP
		);

		// title and icon
		$sClassIconUrl = MetaModel::GetClassIcon('Person', false);
		$oPanel = PanelUIBlockFactory::MakeNeutral('Panel with title and icon')
			->SetSubBlocks($aSubBlocks)
			->SetIcon($sClassIconUrl);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanel,
			<<<'PHP'
PanelUIBlockFactory::MakeNeutral('Panel with title and icon')
	->SetSubBlocks($aSubBlocks)
	->SetIcon($sClassIconUrl);
PHP
		);

		$oButtonBar = ButtonBarUIBlockFactory::MakeWithCountOverflow(
			aItems: [
			ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-up', sTooltipText: 'Promote this entry'),
			ButtonUIBlockFactory::MakeIconAction('fas fa-thumbs-down', sTooltipText: 'Demote this entry'),
			ButtonUIBlockFactory::MakeIconAction('fas fa-bomb', sTooltipText: 'Explode this entry'),
			ButtonUIBlockFactory::MakeIconAction('fas fa-wrench', sTooltipText: 'Fix this entry'),
		],
			iOverflowCount: 3,
			sMoreButtonTooltipText: 'See overflow actions',
			sId: 'button-bar-test5'
		);

		$oPanelButton = PanelUIBlockFactory::MakeNeutral(sTitle: 'Panel With Button Bar');
		$oPanelButton->AddToolbarBlock($oButtonBar);
		$oPanel->SetSubBlocks([HtmlFactory::MakeHtmlContent('<div>Panel body, can contain anything from simple text to rich text, forms, images, <a href="#">links</a>, graphs or tables.</div><div>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>')]);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oPanelButton,
			<<<'PHP'
$oButtonBar = ButtonBarUIBlockFactory::MakeWithCountOverflow(...);
$oPanelButton = PanelUIBlockFactory::MakeNeutral(sTitle: 'Panel With Button Bar');
$oPanelButton->AddToolbarBlock($oButtonBar);
PHP
		);
	}

	private function RenderObjectDetailsSection(): void
	{
		$this->add_style(<<<CSS
.ibo-object-details{
  margin-bottom: 0!important;
}
CSS);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Object Details');
		$oOrgObject = MetaModel::NewObject('Organization');
		$oOrgObject->Set('name', 'Object Container With Tabs');
		$oOrgObject->Set('status', 'active');
		$oObjectDetails = ObjectFactory::MakeDetails($oOrgObject);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oObjectDetails,
			<<<'PHP'
$oOrgObject = MetaModel::NewObject('Organization');
$oOrgObject->Set('name', 'Stub, no tab container. Just to see how the header is displayed');
$oOrgObject->Set('status', 'active');
$oObjectDetails = ObjectFactory::MakeDetails($oOrgObject);
PHP
		);
		$this->AddUiBlock($oFieldSet);
		$this->AddTabContainer(OBJECT_PROPERTIES_TAB, '', $oObjectDetails);
		$this->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$this->SetCurrentTab('First');
		$this->SetCurrentTab('Second');
		$this->SetCurrentTab('Third');
		$this->SetCurrentTabContainer('render-all-tabs');
		$this->SetCurrentTab('tab-functional', 'Functional');
	}

	private function RenderCollapsibleSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Collapsible Sections');
		$this->AddUiBlock($oFieldSet);

		// collapsible section
		$this->AddElementWithSnippet(
			$oFieldSet,
			new CollapsibleSection(sTitle: 'Section title', aSubBlocks: [
				new Html('This is the section content !'),
			]),
			<<<'PHP'
new CollapsibleSection(sTitle: 'Section title', aSubBlocks: [
	new Html('This is the section content !'),
]),
PHP
		);
	}

	private function RenderFieldsetSection(): void
	{
		$this->add_style(<<<CSS
fieldset{
	min-inline-size: 0;
}
CSS);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Fieldsets');
		$this->AddUiBlock($oFieldSet);

		// field set
		$oFieldset = new FieldSet(sLegend: 'Grouped fields');
		$oFieldset->AddSubBlock(FieldUIBlockFactory::MakeStandard('Field A'));
		$oFieldset->AddSubBlock(InputUIBlockFactory::MakeStandard('text', 'input1', 'Input 1'));
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oFieldset,
			<<<'PHP'
$oFieldset = FieldSetUIBlockFactory::MakeStandard(sLegend: 'Grouped fields');
$oFieldset->AddSubBlock(FieldUIBlockFactory::MakeStandard('Field A'));
$oFieldset->AddSubBlock(InputUIBlockFactory::MakeStandard('text', 'input1', 'Input 1'));
$oFieldSet->AddSubBlock($oFieldset);
PHP
		);
	}

	private function RenderCodeSection(): void
	{
		$this->add_style(<<<CSS
.ibo-is-code{
	padding: 12px 0 24px;
	background-color: transparent;
}
CSS);

		$sCode = <<<'PHP'
function mean(int $a, int $b)
{
	return ($a + $b)/2;
}
PHP;

		// basic
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Basic');
		$this->AddUiBlock($oFieldSet);
		$this->AddElementWithSnippet(
			$oFieldSet,
			UIContentBlockUIBlockFactory::MakeForCode(sCode:$sCode)->AddCSSClass('language-php'),
			<<<'PHP'
UIContentBlockUIBlockFactory::MakeForCode(sCode: 'function mean(int $a, int $b) {\n\treturn ($a + $b)/2\n}')->AddCSSClass('language-php'));
PHP
		);

		// code preformatted
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Preformatted');
		$this->AddUiBlock($oFieldSet);
		$this->AddElementWithSnippet(
			$oFieldSet,
			UIContentBlockUIBlockFactory::MakeForPreformatted($sCode)->AddCSSClass('language-php'),
			<<<'PHP'
UIContentBlockUIBlockFactory::MakeForPreformatted('function mean(int $a, int $b) {\n\treturn ($a + $b)/2\n}')->AddCSSClass('language-php'));
PHP
		);
	}

	private function RenderPillSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Basic');
		$this->AddUiBlock($oFieldSet);

		$sHtml = '<span class="ibo-dashlet-header-dynamic--count">8</span><span class="ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis">:state</span>';

		$this->AddElementWithSnippet(
			$oFieldSet,
			PillFactory::MakeForState(sClass: 'Person', sStateCode: 'active')->AddHtml(str_replace(':state', 'Active', $sHtml)),
			<<<'PHP'
PillFactory::MakeForState(sClass: 'Person', sStateCode: 'active')->AddHtml($sHtml);
PHP
		);

		$this->AddElementWithSnippet(
			$oFieldSet,
			PillFactory::MakeForState(sClass: 'Person', sStateCode: 'inactive')->AddHtml(str_replace(':state', 'Inactive', $sHtml)),
			<<<'PHP'
PillFactory::MakeForState(sClass: 'Person', sStateCode: 'inactive')->AddHtml($sHtml);
PHP
		);

		$this->AddElementWithSnippet(
			$oFieldSet,
			PillFactory::MakeForState(sClass: 'Person', sStateCode: 'new')->AddHtml(str_replace(':state', 'New', $sHtml)),
			<<<'PHP'
PillFactory::MakeForState(sClass: 'Person', sStateCode: 'new')->AddHtml($sHtml);
PHP
		);

		$this->AddElementWithSnippet(
			$oFieldSet,
			HtmlFactory::MakeHtmlContent('<span>🧑‍💻 HTML Content</span>'),
			<<<HTML
<span class="ibo-dashlet-header-dynamic--count">:count</span>
<span class="ibo-dashlet-header-dynamic--label">:state</span>
HTML
			,
			'language-html'
		);
	}

	private function RenderTitleSizeSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Sizes');
		$this->AddUiBlock($oFieldSet);

		// title
		$this->AddElementWithSnippet(
			$oFieldSet,
			TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 1'),
			<<<'PHP'
TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 1');
PHP
		);

		// title 2
		$this->AddElementWithSnippet(
			$oFieldSet,
			TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 2', iLevel: 2),
			<<<'PHP'
TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 2', iLevel: 2);
PHP
		);

		// title 3
		$this->AddElementWithSnippet(
			$oFieldSet,
			TitleUIBlockFactory::MakeNeutral(sTitle: 'Title ', iLevel: 3),
			<<<'PHP'
TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 3', iLevel: 3);
PHP
		);

		// title 4
		$this->AddElementWithSnippet(
			$oFieldSet,
			TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 4', iLevel: 4),
			<<<'PHP'
TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 4', iLevel: 4);
PHP
		);

		// title 5
		$this->AddElementWithSnippet(
			$oFieldSet,
			TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 5', iLevel: 5),
			<<<'PHP'
TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 5', iLevel: 5);
PHP
		);

		// title 6
		$this->AddElementWithSnippet(
			$oFieldSet,
			TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 6', iLevel: 6),
			<<<'PHP'
TitleUIBlockFactory::MakeNeutral(sTitle: 'Title 6', iLevel: 6);
PHP
		);

	}

	private function RenderTitleAlternativeSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Alternatives');
		$this->AddUiBlock($oFieldSet);

		// title image
		$this->AddElementWithSnippet(
			$oFieldSet,
			TitleUIBlockFactory::MakeForPageWithIcon(sTitle: 'Title with image', sIconUrl: MetaModel::GetClassIcon('Organization', false)),
			<<<'PHP'
TitleUIBlockFactory::MakeForPageWithIcon(sTitle: 'Title with image', sIconUrl: MetaModel::GetClassIcon('Organization', false));
PHP
		);

	}

	private function RenderDatatableSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Static');
		$this->AddUiBlock($oFieldSet);

		// data table
		$this->AddElementWithSnippet(
			$oFieldSet,
			DataTableUIBlockFactory::MakeForStaticData(
				sTitle: 'Static datatable',
				aColumns: [
					'a' => ['label' => 'a'],
					'b' => ['label' => 'b'],
				],
				aData: [
					['a' => 'A1', 'b' => 'B1'],
					['a' => 'A2', 'b' => 'B2'],
				]
			),
			<<<'PHP'
DataTableUIBlockFactory::MakeForStaticData(
	sTitle: 'Static datatable',
	aColumns: [
		'a' => ['label' => 'a'],
		'b' => ['label' => 'b'],
	],
	aData: [
		['a' => 'A1', 'b' => 'B1'],
		['a' => 'A2', 'b' => 'B2'],
	]
);
PHP
		);
	}

	private function RenderSetSection(): void
	{
		$this->add_style(<<<CSS
.demo_set{
	color:red;
}
.simple-option-renderer--container{
	display: flex;
    gap: 5px;
}
CSS);

		$aOptions = [
			[
				'label' => 'Chien',
				'value' => 'dog',
				'icon'  => 'fas fa-dog',
				'group' => 'Domestique',
			],
			[
				'label' => 'Chat',
				'value' => 'cat',
				'icon'  => 'fas fa-cat',
				'group' => 'Domestique',
			],
			[
				'label' => 'Cheval',
				'value' => 'horse',
				'icon'  => 'fas fa-horse',
				'group' => 'Domestique',
			],
			[
				'label' => 'Araignée',
				'value' => 'spider',
				'icon'  => 'fas fa-spider',
				'class' => 'demo_set',
				'group' => 'Sauvage',
			],
			[
				'label' => 'Otarie',
				'value' => 'otter',
				'icon'  => 'fas fa-otter',
				'group' => 'Sauvage',
			],
			[
				'label' => 'Poisson',
				'value' => 'fish',
				'icon'  => 'fas fa-fish',
				'group' => 'Domestique',
			],
			[
				'label' => 'Grenouille',
				'value' => 'frog',
				'icon'  => 'fas fa-frog',
				'group' => 'Sauvage',
			],
			[
				'label' => 'Hippopotame',
				'value' => 'hippo',
				'icon'  => 'fas fa-hippo',
				'group' => 'Sauvage',
			],
		];

		// simple set
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Basic');
		$this->AddUiBlock($oFieldSet);

		$this->AddElementWithSnippet(
			$oFieldSet,
			SetUIBlockFactory::MakeForSimple(sId: 'SetSimple', aOptions: $aOptions, sLabelFields: 'label', sValueField: 'value', aSearchFields: ['label'], sGroupField: null, sTooltipField: null, sName: 'SimpleSetBlock'),
			<<<'PHP'
$aOptions = [
	['label' => 'Chien', 'value' => 'dog'],
	['label' => 'Chat', 'value' => 'cat'],
	...
];
SetUIBlockFactory::MakeForSimple(sId: 'SetSimple', aOptions: $aOptions, sLabelFields: 'label', sValueField: 'value', aSearchFields: ['label'], sGroupField: null, sTooltipField: null, sName: 'SimpleSetBlock');
PHP
		);

		// add option button
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Add Option Button');
		$this->AddUiBlock($oFieldSet);

		$this->AddElementWithSnippet(
			$oFieldSet,
			SetUIBlockFactory::MakeForSimple(sId: 'SetWithAddOption', aOptions: $aOptions, sLabelFields: 'label', sValueField: 'value', aSearchFields: ['label'], sGroupField: null, sTooltipField: null, sName: 'SetWithAddOption')
				->SetHasAddOptionButton(true),
			<<<'PHP'
$aOptions = [
	['label' => 'Chien', 'value' => 'dog'],
	['label' => 'Chat', 'value' => 'cat'],
	...
];
SetUIBlockFactory::MakeForSimple('SetWithAddOption', $aOptions, 'label', 'value', ['label'], null, null, 'SetWithAddOption')
	->SetHasAddOptionButton(true);
PHP
		);

		// renderer
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Renderer');
		$this->AddUiBlock($oFieldSet);

		$oSimpleSetBlockRenderer = SetUIBlockFactory::MakeForSimple(sId: 'SetRenderer', aOptions: $aOptions, sLabelFields: 'label', sValueField: 'value', aSearchFields: ['label'], sGroupField: null, sTooltipField: null, sName: 'SimpleSetBlockWithRenderer');
		$oSimpleSetBlockRenderer->SetOptionsTemplate('base/components/input/set/simple_option_renderer.html.twig');
		$oSimpleSetBlockRenderer->SetItemsTemplate('base/components/input/set/simple_option_renderer.html.twig');

		$this->AddElementWithSnippet(
			$oFieldSet,
			$oSimpleSetBlockRenderer,
			<<<'PHP'
$aOptions = [
	['label' => 'Chien', 'value' => 'dog', 'icon' => 'fas fa-dog'],
	['label' => 'Chat', 'value' => 'cat', 'icon' => 'fas fa-cat'],
	...
];
	$oSet = SetUIBlockFactory::MakeForSimple(sId: 'SetRenderer', aOptions: $aOptions, sLabelFields: 'label', sValueField: 'value', aSearchFields: ['label'], sGroupField: null, sTooltipField: null, sName: 'SimpleSetBlockWithRenderer');
	$oSet->SetOptionsTemplate('base/components/input/set/simple_option_renderer.html.twig');
	$oSet->SetItemsTemplate('base/components/input/set/simple_option_renderer.html.twig');
PHP
		);

		// group
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Group');
		$this->AddUiBlock($oFieldSet);

		$this->AddElementWithSnippet(
			$oFieldSet,
			SetUIBlockFactory::MakeForSimple(sId: 'group', aOptions: $aOptions, sLabelFields: 'label', sValueField: 'value', aSearchFields: ['label'], sGroupField: 'group', sTooltipField: null, sName: 'SimpleSetBlockWithRenderer'),
			<<<'PHP'
$aOptions = [
	['label' => 'Chien', 'value' => 'dog', 'group' => 'Domestique'],
	['label' => 'Chat', 'value' => 'cat', 'group' => 'Domestique'],
	...
];
SetUIBlockFactory::MakeForSimple(sId: 'group', aOptions: $aOptions, sLabelFields: 'label', sValueField: 'value', aSearchFields: ['label'], sGroupField: 'group', sTooltipField: null, sName: 'SimpleSetBlockWithRenderer');
PHP
		);

		// oql
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('OQL Persons');
		$this->AddUiBlock($oFieldSet);

		$this->AddElementWithSnippet(
			$oFieldSet,
			SetUIBlockFactory::MakeForOQL(sId:  'SetOql', sObjectClass: 'Person', sOql: 'SELECT Person', sWizardHelperJsVarName: null, aFieldsToLoad: [], sGroupField: null, sName: 'OqlSet'),
			<<<'PHP'
SetUIBlockFactory::MakeForOQL(sId: 'SetOql', sObjectClass:'Person', sOql: 'SELECT Person', sWizardHelperJsVarName: null, aFieldsToLoad: [], sGroupField: null, sName: 'OqlSet');
PHP
		);

		// oql2
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('OQL Locations');
		$this->AddUiBlock($oFieldSet);

		$this->AddElementWithSnippet(
			$oFieldSet,
			SetUIBlockFactory::MakeForOQL(sId: 'SetOql2', sObjectClass: 'Location', sOql: 'SELECT Location', sWizardHelperJsVarName: null, aFieldsToLoad: [], sGroupField: null, sName: 'OqlSet2'),
			<<<'PHP'
SetUIBlockFactory::MakeForOQL(sId: 'SetOql2', sObjectClass: 'Location', sOql:'SELECT Location', sWizardHelperJsVarName: null, aFieldsToLoad: [], sGroupField: null, sName: 'OqlSet2');
PHP
		);
	}

	private function RenderTogglerSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Toggler');
		$this->AddUiBlock($oFieldSet);

		// toggler
		$oToggler = new Toggler(sId: 'SampleToggler', sTooltip: 'Sample toggler');
		$oToggler->SetName(sName: 'SampleToggler');
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oToggler,
			<<<'PHP'
		$oToggler = new Toggler(sId: 'SampleToggler', sTooltip: 'Sample toggler');
		$oToggler->SetName(sName: 'SampleToggler');
PHP
		);

	}

	private function RenderBadgesSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Basic');
		$this->AddUiBlock($oFieldSet);

		// badge neutral
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeNeutral(sLabel: 'Badge Neutral', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeNeutral(sLabel: 'badge neutral', sTooltip: 'Tooltip');
PHP
		);

		// badge cyan
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeCyan(sLabel: 'Badge Cyan', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeCyan(sLabel: 'badge Cyan', sTooltip: 'Tooltip');
PHP
		);

		// badge green
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeGreen(sLabel: 'Badge Green', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeGreen(sLabel: 'Badge Green', sTooltip: 'Tooltip');
PHP
		);

		// badge grey
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeGrey(sLabel: 'Badge Grey', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeGrey(sLabel: 'Badge Grey', sTooltip: 'Tooltip');
PHP
		);

		// badge orange
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeOrange(sLabel: 'Badge Orange', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeOrange(sLabel: 'Badge Orange', sTooltip: 'Tooltip');
PHP
		);

		// badge red
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeRed(sLabel: 'Badge Red', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeRed(sLabel: 'Badge Red', sTooltip: 'Tooltip');
PHP
		);

		// badge pink
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakePink(sLabel: 'Badge Pink', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakePink(sLabel: 'Badge Pink', sTooltip: 'Tooltip');
PHP
		);

		// badge purple
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakePurple(sLabel: 'Badge Purple', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakePurple(sLabel: 'Badge Purple', sTooltip: 'Tooltip');
PHP
		);

		// badge blue
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeBlue(sLabel: 'Badge Blue', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeBlue(sLabel: 'Badge Blue', sTooltip: 'Tooltip');
PHP
		);

		// badge yellow
		$this->AddElementWithSnippet(
			$oFieldSet,
			BadgeUIBlockFactory::MakeYellow(sLabel: 'Badge Yellow', sTooltip: 'Tooltip'),
			<<<'PHP'
BadgeUIBlockFactory::MakeYellow(sLabel: 'Badge Yellow', sTooltip: 'Tooltip');
PHP
		);
	}

	private function RenderMultiColumns(): void
	{
		$this->add_style(
			<<<CSS
.column-center{
	background-color: #f6f6f6;
}
CSS
		);

		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Multi Columns');
		$this->AddUiBlock($oFieldSet);

		$oMultiCol = new MultiColumn();
		$oColumnLeft = new Column('multi-column-left', ['column-left']);
		$oColumnLeft->AddSubBlock(new Html('<div>Left column</div>'));
		$oColumnCenter = new Column('multi-column-center', ['column-center']);
		$oColumnCenter->AddSubBlock(new Html('<div>Center column</div>'));
		$oColumnRight = new Column('multi-column-right', ['column-right']);
		$oColumnRight->AddSubBlock(new Html('<div>Right column</div>'));
		$oMultiCol->AddColumn($oColumnLeft);
		$oMultiCol->AddColumn($oColumnCenter);
		$oMultiCol->AddColumn($oColumnRight);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oMultiCol,
			<<<'PHP'
$oMultiCol = new MultiColumn();
$oColumnLeft = new Column('multi-column-left', ['column-left']);
$oColumnLeft->AddSubBlock(new Html('<div>Left column</div>'));
$oColumnCenter = new Column('multi-column-center', ['column-center']);
$oColumnCenter->AddSubBlock(new Html('<div>Center column</div>'));
$oColumnRight = new Column('multi-column-right', ['column-right']);
$oColumnRight->AddSubBlock(new Html('<div>Right column</div>'));
$oMultiCol->AddColumn($oColumnLeft);
$oMultiCol->AddColumn($oColumnCenter);
$oMultiCol->AddColumn($oColumnRight);
PHP
		);
	}

	private function RenderExtensionsSection(): void
	{
		$oFieldSet = FieldSetUIBlockFactory::MakeStandard('Extensions details layout');
		$this->AddUiBlock($oFieldSet);

		$oExtension = ExtensionDetailsUIBlockFactory::MakeInstalled('itop-sample', 'My extension v2', 'This is for test only', ['v1.1.1', 'Designer', '12/12/2012'], ['uninstallable' => false, 'missing' => true]);
		$this->AddElementWithSnippet(
			$oFieldSet,
			$oExtension,
			<<<'PHP'
ExtensionDetailsUIBlockFactory::MakeInstalled('itop-sample', 'My extension v2', 'This is for test only', ['v1.1.1', 'Designer', '12/12/2012'], ['uninstallable' => false, 'missing' => true]);
PHP
		);
	}
}

LoginWebPage::DoLogin();
$oPage = new RenderAllUiBlocksPage();
$oPage->SetCurrentTab();
$oPage->SetCurrentTabContainer();

$oPage->output();
