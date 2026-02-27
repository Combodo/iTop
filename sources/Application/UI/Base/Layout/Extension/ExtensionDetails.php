<?php

namespace Combodo\iTop\Application\UI\Base\Layout\Extension;

use Combodo\iTop\Application\UI\Base\Component\Badge\Badge;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Toggler;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Dict;
use JSButtonItem;

class ExtensionDetails extends UIContentBlock
{
	public const BLOCK_CODE = 'ibo-extension-details';

	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/extension/extension-details/layout';

	protected string $sCode;
	protected string $sLabel;
	protected string $sAbout;
	protected array $aMetaData;
	protected string $sDescription;
	protected Toggler $oToggler;
	protected UIContentBlock $oMoreActions;
	protected PopoverMenu $oPopoverMenu;
	protected array $aBadges;

	public function __construct(string $sCode, string $sLabel, string $sDescription = '', array $aMetaData = [], array $aBadges = [], string $sAbout = '', string $sId = null)
	{
		parent::__construct($sId);
		$this->sCode = $sCode;
		$this->sLabel = $sLabel;
		$this->sDescription = $sDescription;
		$this->aMetaData = $aMetaData;
		$this->aBadges = $aBadges;
		$this->sAbout = $sAbout;
		$this->InitializeToggler();
		$this->InitializePopoverMenu();
	}

	public function GetSubBlocks(): array
	{
		$aSubBlocks = [
			$this->oToggler->GetId() => $this->oToggler,
			$this->oMoreActions->GetId() => $this->oMoreActions,
		];
		foreach ($this->aBadges as $oBadge) {
			$aSubBlocks[$oBadge->GetId()] = $oBadge;
		}
		return $aSubBlocks;
	}

	/**
	 * @return string
	 */
	public function GetCode(): string
	{
		return $this->sCode;
	}

	/**
	 * @param string $sCode
	 *
	 * @return ExtensionDetails
	 */
	public function SetCode(string $sCode): static
	{
		$this->sCode = $sCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetLabel(): string
	{
		return $this->sLabel;
	}

	/**
	 * @param string $sLabel
	 *
	 * @return ExtensionDetails
	 */
	public function SetLabel(string $sLabel): static
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	/**
	 * @return array
	 */
	public function GetMetaData(): array
	{
		return $this->aMetaData;
	}

	/**
	 * @param array $aMetaData
	 *
	 * @return ExtensionDetails
	 */
	public function SetMetaData(array $aMetaData): static
	{
		$this->aMetaData = $aMetaData;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetDescription(): string
	{
		return $this->sDescription;
	}

	/**
	 * @param string $sDescription
	 *
	 * @return ExtensionDetails
	 */
	public function SetDescription(string $sDescription): static
	{
		$this->sDescription = $sDescription;
		return $this;
	}

	/**
	 * @return Toggler
	 */
	public function GetToggler(): Toggler
	{
		return $this->oToggler;
	}

	/**
	 * @param Toggler $oToggler
	 *
	 * @return ExtensionDetails
	 */
	public function SetToggler(Toggler $oToggler): static
	{
		$this->oToggler = $oToggler;
		return $this;
	}

	/**
	 * @return array
	 */
	public function GetBadges(): array
	{
		return $this->aBadges;
	}

	/**
	 * @param array $aBadges
	 *
	 * @return ExtensionDetails
	 */
	public function SetBadges(array $aBadges): static
	{
		$this->aBadges = $aBadges;
		return $this;
	}

	public function AddBadge(Badge $oBadge): static
	{
		$this->aBadges[] = $oBadge;
		return $this;
	}

	public function GetMoreActions(): UIContentBlock
	{
		return $this->oMoreActions;
	}

	protected function InitializeToggler()
	{
		$this->oToggler = new Toggler();
		$this->oToggler->SetName('ExtensionToggler');
		$this->oToggler->AddCSSClass('toggler-install');
	}

	protected function InitializePopoverMenu()
	{
		$sModalLabel = Dict::Format('UI:Layout:ExtensionsDetails:MenuAboutTitle', $this->sLabel);
		$sModalText = $this->sAbout;
		$oModifyButton = new JSButtonItem(
			'extension_details',
			Dict::S('UI:Layout:ExtensionsDetails:MenuAbout'),
			<<<JS
	CombodoModal.OpenModal({
		title: '$sModalLabel',
		content: '$sModalText',
	});
JS,
		);
		$this->oPopoverMenu = new PopoverMenu();
		$this->oPopoverMenu->AddItem('more-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oModifyButton));
		$oPopoverOpenButton = ButtonUIBlockFactory::MakeIconAction('fas fa-ellipsis-v', 'Show more actions');
		$this->oPopoverMenu->SetTogglerFromBlock($oPopoverOpenButton);
		$this->oMoreActions = new UIContentBlock();
		$this->oMoreActions->AddSubBlock($this->oPopoverMenu);
		$this->oMoreActions->AddSubBlock($oPopoverOpenButton);
	}

	public function AllowForceUninstall()
	{
		$oForceUninstallButton = new JSButtonItem(
			'force_uninstall',
			Dict::S('UI:Layout:ExtensionsDetails:MenuForce'),
			<<<JS
	this.closest('.ibo-extension-details').querySelector('input[type=checkbox]').disabled = false
JS,
		);
		$this->oPopoverMenu->AddItem('more-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oForceUninstallButton));
	}

}
