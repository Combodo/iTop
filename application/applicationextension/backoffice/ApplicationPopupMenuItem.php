<?php

/**
 * Base class for the various types of custom menus
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
abstract class ApplicationPopupMenuItem
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

	/** @ignore */
	public function GetLinkedScripts()
	{
		return [];
	}
}
