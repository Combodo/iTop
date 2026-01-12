<?php

namespace Combodo\iTop\Application\UI\Base\Layout\Extension;

use Combodo\iTop\Application\UI\Base\Component\Input\Toggler;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

class ExtensionDetails extends UIContentBlock {
	public const BLOCK_CODE = 'ibo-extension-details';

	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/extension/extension-details/layout';
	
	protected string $sCode;
	protected string $sLabel;
	protected array $aMetaData;
	protected string $sDescription;
	protected Toggler $oToggler;
	protected array $aBadges;
	
	public function __construct(string $sCode, string $sLabel, string $sDescription = '', array $aMetaData = [], string $sId = null) {
		parent::__construct($sId);
		$this->sCode = $sCode;
		$this->sLabel = $sLabel;
		$this->sDescription = $sDescription;
		$this->aMetaData = $aMetaData;
		$this->InitializeToggler();
	}

	/**
	 * @return string
	 */
	public function GetCode(): string {
		return $this->sCode;
	}

	/**
	 * @param string $sCode
	 */
	public function SetCode(string $sCode) {
		$this->sCode = $sCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetLabel(): string {
		return $this->sLabel;
	}

	/**
	 * @param string $sLabel
	 */
	public function SetLabel(string $sLabel) {
		$this->sLabel = $sLabel;
		return $this;
	}

	/**
	 * @return array
	 */
	public function GetMetaData(): array {
		return $this->aMetaData;
	}

	/**
	 * @param array $aMetaData
	 */
	public function SetMetaData(array $aMetaData) {
		$this->aMetaData = $aMetaData;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetDescription(): string {
		return $this->sDescription;
	}

	/**
	 * @param string $sDescription
	 */
	public function SetDescription(string $sDescription) {
		$this->sDescription = $sDescription;
		return $this;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Toggler
	 */
	public function GetToggler(): Toggler {
		return $this->oToggler;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Component\Input\Toggler $oToggler
	 */
	public function SetToggler(Toggler $oToggler) {
		$this->oToggler = $oToggler;
		return $this;
	}

	/**
	 * @return array
	 */
	public function GetBadges(): array {
		return $this->aBadges;
	}

	/**
	 * @param array $aBadges
	 */
	public function SetBadges(array $aBadges) {
		$this->aBadges = $aBadges;
		return $this;
	}
	
	protected function InitializeToggler(){
		$this->oToggler = new Toggler();
		$this->oToggler->SetName('ExtensionToggler');
	}

}