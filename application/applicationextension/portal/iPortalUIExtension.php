<?php

/**
 * Implement this interface to add content to any enhanced portal page
 *
 * @api
 * @package     PortalExtensibilityAPI
 *
 * @since 2.4.0 interface creation
 * @since 2.7.0 change method signatures due to Silex to Symfony migration
 */
interface iPortalUIExtension
{
    const ENUM_PORTAL_EXT_UI_BODY = 'Body';
    const ENUM_PORTAL_EXT_UI_NAVIGATION_MENU = 'NavigationMenu';
    const ENUM_PORTAL_EXT_UI_MAIN_CONTENT = 'MainContent';

    /**
     * Returns an array of CSS file urls
     *
     * @param \Symfony\Component\DependencyInjection\Container $oContainer
     *
     * @return array
     * @api
     */
    public function GetCSSFiles(\Symfony\Component\DependencyInjection\Container $oContainer);

    /**
     * Returns inline (raw) CSS
     *
     * @param \Symfony\Component\DependencyInjection\Container $oContainer
     *
     * @return string
     * @api
     */
    public function GetCSSInline(\Symfony\Component\DependencyInjection\Container $oContainer);

    /**
     * Returns an array of JS file urls
     *
     * @param \Symfony\Component\DependencyInjection\Container $oContainer
     *
     * @return array
     * @api
     */
    public function GetJSFiles(\Symfony\Component\DependencyInjection\Container $oContainer);

    /**
     * Returns raw JS code
     *
     * @param \Symfony\Component\DependencyInjection\Container $oContainer
     *
     * @return string
     * @api
     */
    public function GetJSInline(\Symfony\Component\DependencyInjection\Container $oContainer);

    /**
     * Returns raw HTML code to put at the end of the <body> tag
     *
     * @param \Symfony\Component\DependencyInjection\Container $oContainer
     *
     * @return string
     * @api
     */
    public function GetBodyHTML(\Symfony\Component\DependencyInjection\Container $oContainer);

    /**
     * Returns raw HTML code to put at the end of the #main-wrapper element
     *
     * @param \Symfony\Component\DependencyInjection\Container $oContainer
     *
     * @return string
     * @api
     */
    public function GetMainContentHTML(\Symfony\Component\DependencyInjection\Container $oContainer);

    /**
     * Returns raw HTML code to put at the end of the #topbar and #sidebar elements
     *
     * @param \Symfony\Component\DependencyInjection\Container $oContainer
     *
     * @return string
     * @api
     */
    public function GetNavigationMenuHTML(\Symfony\Component\DependencyInjection\Container $oContainer);
}