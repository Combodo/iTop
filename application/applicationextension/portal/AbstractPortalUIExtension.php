<?php

/**
 * Extend this class instead of iPortalUIExtension if you don't need to overload all methods
 *
 * @api
 * @package     PortalExtensibilityAPI
 * @since       2.4.0
 */
abstract class AbstractPortalUIExtension implements iPortalUIExtension
{
    /**
     * @inheritDoc
     */
    public function GetCSSFiles(\Symfony\Component\DependencyInjection\Container $oContainer)
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function GetCSSInline(\Symfony\Component\DependencyInjection\Container $oContainer)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function GetJSFiles(\Symfony\Component\DependencyInjection\Container $oContainer)
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function GetJSInline(\Symfony\Component\DependencyInjection\Container $oContainer)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function GetBodyHTML(\Symfony\Component\DependencyInjection\Container $oContainer)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function GetMainContentHTML(\Symfony\Component\DependencyInjection\Container $oContainer)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function GetNavigationMenuHTML(\Symfony\Component\DependencyInjection\Container $oContainer)
    {
        return null;
    }
}