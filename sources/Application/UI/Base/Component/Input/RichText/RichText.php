<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Application\UI\Base\Component\Input\RichText;
use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Application\Helper\WebResourcesHelper;
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
		'js/highlight/highlight.min.js',
	];
	public const DEFAULT_CSS_FILES_REL_PATH = [
	];

	/**
	 * @var string|null
	 * @since 3.2.0
	 */
	protected $sName;
	/** @var string|null */
	protected $sValue;
	/** @var array Configuration parameters for the CKEditor instance used with Richtext block */
	protected $aConfig;

	/**
	 * RichText constructor.
	 *
	 * @param string|null $sId
	 * @param string|null $sName
	 */
	public function __construct(?string $sId = null, ?string $sName = null)
	{
		parent::__construct($sId);
		$this->sName = $sName;
		$this->sValue = null;
		$this->aConfig = CKEditorHelper::GetCkeditorConfiguration(true, $this->sValue);

		foreach (CKEditorHelper::GetJSFilesRelPathsForCKEditor() as $sJSFile) {
			$this->AddJsFileRelPath($sJSFile);
		}
	}

	/**
	 * @see static::$sName
	 * @return string|null
	 * @since 3.2.0
	 */
	public function GetName(): ?string
	{
		return $this->sName;
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
		if(is_array($this->aConfig)) {
			$this->aConfig['detectChanges'] = ['initialValue' => $sValue];
		}

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