<?php

use Combodo\iTop\Application\UI\Base\Common\Metadata\AriaAttributes;
use Combodo\iTop\Application\UI\Base\Common\Metadata\DataAttributes;
use Combodo\iTop\Application\UI\Base\Common\Metadata\Grouping;

/**
 * Base class for the various types of custom menus
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
abstract class ApplicationPopupMenuItem implements iGroupingProvider, iDataAttributesProvider, iAriaAttributesProvider
{
	/** @ignore */
	protected $sUID;
	/** @ignore */
	protected $sLabel;
	/** @ignore */
	protected $sTooltip;
	/** @ignore */
	protected $sIconClass;
	/** @ignore */
	protected $aCssClasses;
	/** @since 3.3.0 */
	protected ?AriaAttributes $oAriaAttributes = null;
	/** @since 3.3.0 */
	protected ?DataAttributes $oDataAttributes = null;
	/** @since 3.3.0 */
	protected ?Grouping $oGrouping = null;

	/**
	 * Constructor
	 *
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 * @api
	 */
	public function __construct($sUID, $sLabel)
	{
		$this->sUID = $sUID;
		$this->sLabel = $sLabel;
		$this->sTooltip = '';
		$this->sIconClass = '';
		$this->aCssClasses = [];
	}

	/**
	 * Get the UID
	 *
	 * @return string The unique identifier
	 * @ignore
	 */
	public function GetUID()
	{
		return $this->sUID;
	}

	/**
	 * Get the label
	 *
	 * @return string The label
	 * @ignore
	 */
	public function GetLabel()
	{
		return $this->sLabel;
	}

	/**
	 * @param string $sLabel
	 *
	 * @api
	 * @since 3.3.0
	 */
	public function SetLabel(string $sLabel): void
	{
		$this->sLabel = $sLabel;
	}

	/**
	 * Get the CSS classes
	 *
	 * @return array
	 * @ignore
	 */
	public function GetCssClasses()
	{
		return $this->aCssClasses;
	}

	/**
	 * @param $aCssClasses
	 * @api
	 */
	public function SetCssClasses($aCssClasses)
	{
		$this->aCssClasses = $aCssClasses;
	}

	/**
	 * Adds a CSS class to the CSS classes that will be put on the menu item
	 *
	 * @param $sCssClass
	 * @api
	 */
	public function AddCssClass($sCssClass)
	{
		$this->aCssClasses[] = $sCssClass;
	}

	/**
	 * @param $sTooltip
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function SetTooltip($sTooltip)
	{
		$this->sTooltip = $sTooltip;
	}

	/**
	 * @return string
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function GetTooltip()
	{
		return $this->sTooltip;
	}

	/**
	 * @param $sIconClass
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function SetIconClass($sIconClass)
	{
		$this->sIconClass = $sIconClass;
	}

	/**
	 * @return string
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function GetIconClass()
	{
		return $this->sIconClass;
	}

	/**
	 * Returns the components to create a popup menu item in HTML
	 *
	 * @return array A hash array: array('label' => , 'url' => , 'target' => , 'onclick' => )
	 * @ignore
	 */
	abstract public function GetMenuItem();

	/**
	 * Get additional data, child classes can use this without having to worry about this class interfaces
	 * @return array<string, mixed>
	 * @since 3.3.0
	 */
	protected function GetMenuItemAdditionalData(): array
	{
		$aAdditionalData = [];

		$aDataAttributes = $this->GetDataAttributes()->GetAttributes();
		if (!empty($aDataAttributes)) {
			$aAdditionalData['data_attributes'] = $aDataAttributes;
		}

		$aAriaAttributes = $this->GetAriaAttributes()->GetAttributes();
		if (!empty($aAriaAttributes)) {
			$aAdditionalData['aria_attributes'] = $aAriaAttributes;
		}

		$oGrouping = $this->GetGrouping();
		if ($oGrouping !== null) {
			$aAdditionalData['grouping_uid'] = $oGrouping->GetUID();
		}

		return $aAdditionalData;
	}

	/** @ignore */
	public function GetLinkedScripts()
	{
		return [];
	}

	/**
	 * @api
	 * @since 3.3.0
	 */
	public function GetAriaAttributes(): AriaAttributes
	{
		return $this->oAriaAttributes ??= new AriaAttributes();
	}

	/**
	 * @api
	 * @since 3.3.0
	 */
	public function GetDataAttributes(): DataAttributes
	{
		return $this->oDataAttributes ??= new DataAttributes();
	}

	/**
	 * @api
	 * @since 3.3.0
	 */
	public function GetGrouping(): ?Grouping
	{
		return $this->oGrouping;
	}

	/**
	 * @param Grouping|null $oGrouping
	 * @return static
	 * @api
	 * @since 3.3.0
	 */
	public function SetGrouping(?Grouping $oGrouping): static
	{
		$this->oGrouping = $oGrouping;

		return $this;
	}
}
