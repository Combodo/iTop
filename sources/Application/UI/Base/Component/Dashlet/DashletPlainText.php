<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;


/**
 * Class DashletPlainText
 *
 * @internal
 * @author Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Application\UI\Base\Component\Dashlet
 */
class DashletPlainText extends DashletContainer
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-dashlet-plain-text';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/dashlet/dashlet-plain-text';

	/** @var string */
	protected $sText;

	/**
	 * DashletPlainText constructor.
	 *
	 * @param string $sText
	 */
	public function __construct(string $sText, string $sId = null)
	{
		parent::__construct($sId);

		$this->sText = $sText;
	}

	/**
	 * @return string
	 */
	public function GetText(): string
	{
		return $this->sText;
	}

	/**
	 * @param string $sText
	 *
	 * @return $this
	 */
	public function SetText(string $sText)
	{
		$this->sText = $sText;

		return $this;
	}
}