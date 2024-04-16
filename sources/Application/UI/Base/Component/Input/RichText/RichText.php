<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Application\UI\Base\Component\Input\RichText;
use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Dict;
use utils;

/**
 * Class RichText
 *
 * @package Combodo\iTop\Application\UI\Base\Component\RichText
 */
class RichText extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-richtext';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/richtext/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/input/richtext/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/ckeditor/build/ckeditor.js',
		'js/highlight/highlight.js',
	];
	public const DEFAULT_CSS_FILES_REL_PATH = [
		'js/highlight/styles/obsidian.css',
		'css/ckeditor/contents.css',
	];

	/** @var string|null */
	protected $sValue;
	/** @var array Configuration parameters for the CKEditor instance used with Richtext block */
	protected $aConfig;

	/**
	 * RichText constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->sValue = null;
		$this->aConfig = CKEditorHelper::GetCkeditorPref();

		// add CKEditor translations resource
		$sLanguage = strtolower(explode(' ', Dict::GetUserLanguage())[0]);
		$this->AddJsFileRelPath('js/ckeditor/build/translations/' . $sLanguage . '.js');
	}

	/**
	 * @see static::$sValue
	 * @return string|null
	 */
	public function GetValue(): ?string
	{
		return $this->sValue;
	}

	/**
	 * @param string|null $sValue
	 * @see static::$sValue
	 *
	 * @return $this
	 */
	public function SetValue(?string $sValue)
	{
		$this->sValue = $sValue;

		return $this;
	}

	/**
	 * @param array $aConfig
	 * @see static::$aConfig
	 *
	 * @return $this
	 */
	public function SetConfig(array $aConfig)
	{
		$this->aConfig = $aConfig;

		return $this;
	}

	/**
	 * @see static::$aConfig
	 * @return array
	 */
	public function GetConfig(): array
	{
		return $this->aConfig;
	}
}