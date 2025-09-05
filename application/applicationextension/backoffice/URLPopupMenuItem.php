<?php

/**
 * Class for adding an item into a popup menu that browses to the given URL
 *
 * Note: This works only in the backoffice, {@see \URLButtonItem} for the end-user portal
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class URLPopupMenuItem extends ApplicationPopupMenuItem
{
    /** @ignore */
    protected $sUrl;
    /** @ignore */
    protected $sTarget;

    /**
     * Constructor
     *
     * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
     * @param string $sLabel The display label of the menu (must be localized)
     * @param string $sUrl If the menu is an hyperlink, provide the absolute hyperlink here
     * @param string $sTarget In case the menu is an hyperlink and a specific target is needed (_blank for example), pass it here
     * @api
     */
    public function __construct($sUID, $sLabel, $sUrl, $sTarget = '_top')
    {
        parent::__construct($sUID, $sLabel);
        $this->sUrl = $sUrl;
        $this->sTarget = $sTarget;
    }

    /** @ignore */
    public function GetMenuItem()
    {
        return array('label' => $this->GetLabel(),
            'url' => $this->GetUrl(),
            'target' => $this->GetTarget(),
            'css_classes' => $this->aCssClasses,
            'icon_class' => $this->sIconClass,
            'tooltip' => $this->sTooltip
        );
    }

    /** @ignore */
    public function GetUrl()
    {
        return $this->sUrl;
    }

    /** @ignore */
    public function GetTarget()
    {
        return $this->sTarget;
    }
}