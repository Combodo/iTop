<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\ButtonGroup;


use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class ButtonGroup
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\ButtonGroup
 * @since 3.0.0
 */
class ButtonGroup extends UIBlock
{
	// Overloaded constants
	/** @inheritDoc */
	public const BLOCK_CODE = 'ibo-button-group';
	/** @inheritDoc */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/button-group/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/button-group.js',
	];
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;

	/** @var \Combodo\iTop\Application\UI\Base\Component\Button\Button[] Buttons to be displayed as a group */
	protected $aButtons;
	/** @var \Combodo\iTop\Application\UI\Base\iUIBlock[] Extra blocks used in the group (eg. PopoverMenu toggled by one of the static::$aButtons) */
	protected $aExtraBlocks;

	/**
	 * Button constructor.
	 *
	 * @param array $aButtons
	 * @param string|null $sId
	 */
	public function __construct(array $aButtons = [], ?string $sId = null)
	{
		parent::__construct($sId);

		$this->SetButtons($aButtons);
		$this->aExtraBlocks = [];
	}

	/**
	 * Replace all existing buttons with $aButtons
	 *
	 * @param array $aButtons
	 *
	 * @return $this
	 * @uses static::$aButtons
	 */
	public function SetButtons(array $aButtons)
	{
		$this->aButtons = [];
		$this->AddButtons($aButtons);

		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button[]
	 * @use static::$aButtons
	 */
	public function GetButtons(): array
	{
		return $this->aButtons;
	}

	/**
	 * Add $oButton, replacing any button with the same ID
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\Button\Button $oButton
	 *
	 * @return $this
	 * @uses static::$aButtons
	 */
	public function AddButton(Button $oButton)
	{
		$this->aButtons[$oButton->GetId()] = $oButton;

		return $this;
	}

	/**
	 * Add all $aButtons, replacing any button with the same IDs
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\Button\Button[] $aButtons
	 *
	 * @return $this
	 */
	public function AddButtons(array $aButtons)
	{
		foreach ($aButtons as $oButton) {
			$this->AddButton($oButton);
		}

		return $this;
	}

	/**
	 * Remove the button with the $sId. if no button with that ID, proceed silently.
	 *
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function RemoveButton(string $sId)
	{
		if (array_key_exists($sId, $this->aButtons)) {
			unset($this->aButtons[$sId]);
		}

		return $this;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return $this
	 * @uses static::$aExtraBlocks
	 */
	public function AddExtraBlock(iUIBlock $oBlock)
	{
		$this->aExtraBlocks[$oBlock->GetId()] = $oBlock;

		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 * @uses static::$aExtraBlocks
	 */
	public function GetExtraBlocks(): array
	{
		return $this->aExtraBlocks;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		return array_merge($this->GetButtons(), $this->GetExtraBlocks());
	}
}