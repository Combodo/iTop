<?php

/**
 * Class for adding a separator (horizontal line, not selectable) the output
 * will automatically reduce several consecutive separators to just one
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class SeparatorPopupMenuItem extends ApplicationPopupMenuItem
{
	public static $idx = 0;

	/**
	 * Constructor
	 * @api
	 */
	public function __construct()
	{
		parent::__construct('_separator_'.(self::$idx++), '');
	}

	/** @ignore */
	public function GetMenuItem()
	{
		return ['label' => '<hr class="menu-separator">', 'url' => '', 'css_classes' => $this->aCssClasses];
	}
}
