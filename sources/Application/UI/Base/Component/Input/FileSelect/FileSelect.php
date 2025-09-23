<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\FileSelect;


use Combodo\iTop\Application\UI\Base\UIBlock;
use Dict;

class FileSelect extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-input-file-select';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/file-select/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/input/file-select/layout';

	/** @var string */
	private $sName;
	/** @var string */
	private $sFileName;
	/** @var string */
	private $sButtonText;
	/** @var bool */
	private $bShowFilename;

	public function __construct(string $sName, string $sId = null)
	{
		parent::__construct($sId);
		$this->sName = $sName;
		$this->sFileName = Dict::S('UI:InputFile:NoFileSelected');
		$this->sButtonText = Dict::S('UI:InputFile:SelectFile');
		$this->bShowFilename = true;
	}

	/**
	 * @return string
	 */
	public function GetFileName(): string
	{
		return $this->sFileName;
	}

	/**
	 * @param mixed $sFileName
	 *
	 * @return $this
	 */
	public function SetFileName($sFileName)
	{
		$this->sFileName = $sFileName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetButtonText(): string
	{
		return $this->sButtonText;
	}

	/**
	 * @param string $sButtonText
	 *
	 * @return $this
	 */
	public function SetButtonText(string $sButtonText)
	{
		$this->sButtonText = $sButtonText;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * @param bool $bShowFilename
	 *
	 * @return $this
	 */
	public function SetShowFilename(bool $bShowFilename)
	{
		$this->bShowFilename = $bShowFilename;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function GetShowFilename(): bool
	{
		return $this->bShowFilename;
	}
	
}