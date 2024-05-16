<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;


use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use utils;

class DashletBadge extends DashletContainer
{
	use tJSRefreshCallback;

	public const BLOCK_CODE = 'ibo-dashlet-badge';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/dashlet/dashlet-badge';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/dashlet/dashlet-badge';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/dashlet/dashlet-badge.js',
	];

	/** @var string */
	protected $sClassIconUrl;
	/** @var string */
	protected $sHyperlink;
	/** @var string */
	protected $iCount;
	/** @var string */
	protected $sClassLabel;
	/**
	 * @var string
	 * @since 3.1.1 3.2.0
	 */
	protected $sClassDescription;

	/** @var string */
	protected $sCreateActionUrl;
	/** @var string */
	protected $sCreateActionLabel;
	/** @var array */
	protected $aRefreshParams;

	/**
	 * DashletBadge constructor.
	 *
	 * @param string $sClassIconUrl
	 * @param string $sHyperlink
	 * @param string $iCount
	 * @param string $sClassLabel
	 * @param string|null $sCreateActionUrl
	 * @param string|null $sCreateActionLabel
	 * @param array $aRefreshParams
	 */
	public function __construct(
		string $sClassIconUrl, string $sHyperlink, string $iCount, string $sClassLabel, ?string $sCreateActionUrl = '',
		?string $sCreateActionLabel = '', array $aRefreshParams = []
	)
	{
		parent::__construct();

		$this->sClassIconUrl = $sClassIconUrl;
		$this->sHyperlink = $sHyperlink;
		$this->iCount = $iCount;
		$this->sClassLabel = $sClassLabel;
		$this->sCreateActionUrl = $sCreateActionUrl;
		$this->sCreateActionLabel = $sCreateActionLabel;
		$this->aRefreshParams = $aRefreshParams;
		$this->sClassDescription = '';
	}


	/**
	 * @return string
	 */
	public function GetCreateActionUrl(): ?string
	{
		return $this->sCreateActionUrl;
	}

	/**
	 * @param string|null $sCreateActionUrl
	 *
	 * @return DashletBadge
	 */
	public function SetCreateActionUrl(?string $sCreateActionUrl)
	{
		$this->sCreateActionUrl = $sCreateActionUrl;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetCreateActionLabel(): ?string
	{
		return $this->sCreateActionLabel;
	}

	/**
	 * @param string|null $sCreateActionLabel
	 *
	 * @return DashletBadge
	 */
	public function SetCreateActionLabel(?string $sCreateActionLabel)
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
	public function SetClassIconUrl(string $sClassIconUrl)
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
	public function SetHyperlink(string $sHyperlink)
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
	public function SetCount(string $iCount)
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
	public function SetClassLabel(string $sClassLabel)
	{
		$this->sClassLabel = $sClassLabel;

		return $this;
	}

	/**
	 * @return string
	 * @since 3.1.1 3.2.0
	 */
	public function GetClassDescription(): string
	{
		return $this->sClassDescription;
	}

	/**
	 * @param string $sClassDescription
	 *
	 * @return DashletBadge
	 * @since 3.1.1 3.2.0
	 */
	public function SetClassDescription(string $sClassDescription)
	{
		$this->sClassDescription = $sClassDescription;
		
		return $this;
	}

	/**
	 * @return bool
	 * @since 3.1.1
	 */
	public function HasClassDescription(): bool
	{
		return utils::IsNotNullOrEmptyString($this->sClassDescription);
	}

	public function GetJSRefresh(): string
	{
		return "$('#".$this->sId."').block();
				$.post('ajax.render.php?operation=refreshDashletCount&style=count',
				   ".json_encode($this->aRefreshParams).",
				   function(data){
					 $('#".$this->sId."').find('.ibo-dashlet-badge--action-list-count').html(data.count);
					 $('#".$this->sId."').unblock();
					});
					
				$('#".$this->sId."').unblock();";
	}

}