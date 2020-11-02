<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Dashlet;


class DashletBadge extends DashletContainer
{
	public const BLOCK_CODE = 'ibo-dashlet-badge';
	public const HTML_TEMPLATE_REL_PATH = 'components/dashlet/dashletbadge';

	/** @var string */
	protected $sClassIconUrl;
	/** @var string */
	protected $sHyperlink;
	/** @var string */
	protected $iCount;
	/** @var string */
	protected $sClassLabel;

	/** @var string */
	protected $sCreateActionUrl;
	/** @var string */
	protected $sCreateActionLabel;

	/**
	 * DashletBadge constructor.
	 *
	 * @param string $sClassIconUrl
	 * @param string $sHyperlink
	 * @param string $iCount
	 * @param string $sClassLabel
	 * @param string $sCreateActionUrl
	 * @param string $sCreateActionLabel
	 */
	public function __construct(string $sClassIconUrl, string $sHyperlink, string $iCount, string $sClassLabel, string $sCreateActionUrl = '', string $sCreateActionLabel = '')
	{
		parent::__construct();

		$this->sClassIconUrl = $sClassIconUrl;
		$this->sHyperlink = $sHyperlink;
		$this->iCount = $iCount;
		$this->sClassLabel = $sClassLabel;
		$this->sCreateActionUrl = $sCreateActionUrl;
		$this->sCreateActionLabel = $sCreateActionLabel;
	}


	/**
	 * @return string
	 */
	public function GetCreateActionUrl(): string
	{
		return $this->sCreateActionUrl;
	}

	/**
	 * @param string $sCreateActionUrl
	 *
	 * @return DashletBadge
	 */
	public function SetCreateActionUrl(string $sCreateActionUrl): DashletBadge
	{
		$this->sCreateActionUrl = $sCreateActionUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetCreateActionLabel(): string
	{
		return $this->sCreateActionLabel;
	}

	/**
	 * @param string $sCreateActionLabel
	 *
	 * @return DashletBadge
	 */
	public function SetCreateActionLabel(string $sCreateActionLabel): DashletBadge
	{
		$this->sCreateActionLabel = $sCreateActionLabel;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetClassIconUrl(): string
	{
		return $this->sClassIconUrl;
	}

	/**
	 * @param string $sClassIconUrl
	 *
	 * @return DashletBadge
	 */
	public function SetClassIconUrl(string $sClassIconUrl): DashletBadge
	{
		$this->sClassIconUrl = $sClassIconUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetHyperlink(): string
	{
		return $this->sHyperlink;
	}

	/**
	 * @param string $sHyperlink
	 *
	 * @return DashletBadge
	 */
	public function SetHyperlink(string $sHyperlink): DashletBadge
	{
		$this->sHyperlink = $sHyperlink;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetCount(): string
	{
		return $this->iCount;
	}

	/**
	 * @param string $iCount
	 *
	 * @return DashletBadge
	 */
	public function SetCount(string $iCount): DashletBadge
	{
		$this->iCount = $iCount;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetClassLabel(): string
	{
		return $this->sClassLabel;
	}

	/**
	 * @param string $sClassLabel
	 *
	 * @return DashletBadge
	 */
	public function SetClassLabel(string $sClassLabel): DashletBadge
	{
		$this->sClassLabel = $sClassLabel;
		return $this;
	}


}