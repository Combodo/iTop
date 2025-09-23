<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;


/**
 * Class DashletHeaderStatic
 *
 * @internal
 * @author Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Application\UI\Base\Component\Dashlet
 */
class DashletHeaderStatic extends DashletContainer
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-dashlet-header-static';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/dashlet/dashlet-header-static';

	/** @var string */
	protected $sTitle;
	/** @var string */
	protected $sIconUrl;

	/**
	 * DashletHeaderStatic constructor.
	 *
	 * @param string $sTitle
	 * @param string $sIconUrl
	 * @param string|null $sId
	 */
	public function __construct(string $sTitle, string $sIconUrl, string $sId = null)
	{
		parent::__construct($sId);

		$this->sTitle = $sTitle;
		$this->sIconUrl = $sIconUrl;
	}


	/**
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle
	 *
	 * @return $this
	 */
	public function SetTitle(string $sTitle)
	{
		$this->sTitle = $sTitle;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetIconUrl(): string
	{
		return $this->sIconUrl;
	}

	/**
	 * @param string $sIconUrl
	 *
	 * @return $this
	 */
	public function SetIconUrl(string $sIconUrl)
	{
		$this->sIconUrl = $sIconUrl;

		return $this;
	}
}