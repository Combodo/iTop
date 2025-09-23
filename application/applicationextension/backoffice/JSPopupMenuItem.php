<?php

/**
 * Class for adding an item into a popup menu that triggers some Javascript code
 *
 * Note: This works only in the backoffice, {@see \JSButtonItem} for the end-user portal
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class JSPopupMenuItem extends ApplicationPopupMenuItem
{
    /** @ignore */
    protected $sJsCode;
    /** @ignore */
    protected $sUrl;
    /** @ignore */
    protected $aIncludeJSFiles;

    /**
     * Class for adding an item that triggers some Javascript code
     *
     * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
     * @param string $sLabel The display label of the menu (must be localized)
     * @param string $sJSCode In case the menu consists in executing some havascript code inside the page, pass it here. If supplied $sURL
     *     ans $sTarget will be ignored
     * @param array $aIncludeJSFiles An array of file URLs to be included (once) to provide some JS libraries for the page.
     * @api
     */
    public function __construct($sUID, $sLabel, $sJSCode, $aIncludeJSFiles = array())
    {
        parent::__construct($sUID, $sLabel);
        $this->sJsCode = $sJSCode;
        $this->sUrl = '#';
        $this->aIncludeJSFiles = $aIncludeJSFiles;
    }

    /** @ignore */
    public function GetMenuItem()
    {
        // Note: the semicolumn is a must here!
        return array(
            'label' => $this->GetLabel(),
            'onclick' => $this->GetJsCode() . '; return false;',
            'url' => $this->GetUrl(),
            'css_classes' => $this->GetCssClasses(),
            'icon_class' => $this->sIconClass,
            'tooltip' => $this->sTooltip
        );
    }

    /** @ignore */
    public function GetLinkedScripts()
    {
        return $this->aIncludeJSFiles;
    }

    /** @ignore */
    public function GetJsCode()
    {
        return $this->sJsCode;
    }

    /** @ignore */
    public function GetUrl()
    {
        return $this->sUrl;
    }
}