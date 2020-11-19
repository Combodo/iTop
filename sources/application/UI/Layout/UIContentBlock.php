<?php
/*
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Layout;


use Combodo\iTop\Application\UI\Component\Html\Html;
use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class UIContentBlock
 *
 * @package Combodo\iTop\Application\UI\Layout
 * @author  Eric Espie <eric.espie@combodo.com>
 * @author  Anne-Catherine Cognet <anne-catherine.cognet@combodo.com>
 * @internal
 * @since   3.0.0
 */
class UIContentBlock extends UIBlock implements iUIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-content-block';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/content-block/layout';

	/** @var array */
	protected $aCSSClasses;
	/** @var array Array <KEY> => <VALUE> which will be output as HTML data-xxx attributes (eg. data-<KEY>="<VALUE>")  */
	protected $aDataAttributes;
	/** @var array */
	protected $aSubBlocks;
	/** @var array */
	protected $aDeferredBlocks;

	/**
	 * UIContentBlock constructor.
	 *
	 * @param string|null $sName
	 * @param string $sContainerClass
	 */
	public function __construct(string $sName = null, string $sContainerClass = '')
	{
		parent::__construct($sName);

		$this->aSubBlocks = [];
		$this->aDeferredBlocks = [];
		$this->aDataAttributes = [];
		$this->SetCSSClasses($sContainerClass);
	}

	/**
	 * @inheritDoc
	 */
	public function AddHtml(string $sHtml)
	{
		$oBlock = new Html($sHtml);
		$this->AddSubBlock($oBlock);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddSubBlock(iUIBlock $oSubBlock)
	{
		$this->aSubBlocks[$oSubBlock->GetId()] = $oSubBlock;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function RemoveSubBlock(string $sId)
	{
		if ($this->HasSubBlock($sId)) {
			unset($this->aSubBlocks[$sId]);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function HasSubBlock(string $sId): bool
	{
		return array_key_exists($sId, $this->aSubBlocks);
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlock(string $sId): ?iUIBlock
	{
		return isset($this->aSubBlocks[$sId]) ? $this->aSubBlocks[$sId] : null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		return $this->aSubBlocks;
	}

	/**
	 * @inheritDoc
	 */
	public function SetSubBlocks(array $aSubBlocks)
	{
		foreach ($aSubBlocks as $oSubBlock) {
			$this->AddSubBlock($oSubBlock);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetCSSClasses(): string
	{
		return implode(' ', $this->aCSSClasses);
	}

	/**
	 * @param string $sCSSClasses
	 *
	 * @return UIContentBlock
	 */
	public function SetCSSClasses(string $sCSSClasses)
	{
		$this->aCSSClasses = [];
		$this->AddCSSClasses($sCSSClasses);

		return $this;
	}

	/**
	 * @param string $sCSSClasses
	 *
	 * @return $this
	 */
	public function AddCSSClasses(string $sCSSClasses)
	{
		foreach (explode(' ', $sCSSClasses) as $sCSSClass) {
			if (!empty($sCSSClass)) {
				$this->aCSSClasses[$sCSSClass] = $sCSSClass;
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function GetDataAttributes(): array
	{
		return $this->aDataAttributes;
	}

	/**
	 * @param array $aDataAttributes
	 *
	 * @return $this
	 */
	public function SetDataAttributes(array $aDataAttributes)
	{
		$this->aDataAttributes = $aDataAttributes;
		return $this;
	}


	/**
	 * @param string $sName
	 * @param string $sValue
	 *
	 * @return $this
	 */
	public function AddDataAttribute(string $sName, string $sValue)
	{
		$this->aDataAttributes[$sName] = $sValue;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddDeferredBlock(iUIBlock $oDeferredBlock)
	{
		$this->aDeferredBlocks[$oDeferredBlock->GetId()] = $oDeferredBlock;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function RemoveDeferredBlock(string $sId)
	{
		if ($this->HasDeferredBlock($sId)) {
			unset($this->aDeferredBlocks[$sId]);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function HasDeferredBlock(string $sId): bool
	{
		return array_key_exists($sId, $this->aDeferredBlocks);
	}

	/**
	 * @inheritDoc
	 */
	public function GetDeferredBlock(string $sId): ?iUIBlock
	{
		return isset($this->aDeferredBlocks[$sId]) ? $this->aDeferredBlocks[$sId] : null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetDeferredBlocks(): array
	{
		return $this->aDeferredBlocks;
	}

	/**
	 * @inheritDoc
	 */
	public function SetDeferredBlocks(array $aDeferredBlocks)
	{
		foreach ($aDeferredBlocks as $oDeferredBlock) {
			$this->AddDeferredBlock($oDeferredBlock);
		}

		return $this;
	}

}
