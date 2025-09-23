<?php

/**
 * @api
 * @package LoginExtensibilityAPI
 * @since 2.7.0
 */
interface iLogoutExtension extends iLoginExtension
{
    /**
     * Execute all actions to log out properly
     * @api
     */
    public function LogoutAction();
}