<?php

/**
 * Login page extensibility
 *
 * @api
 * @package UIExtensibilityAPI
 * @since 2.7.0
 */
interface iLoginUIExtension extends iLoginExtension
{
	/**
	 * @return LoginTwigContext
	 * @api
	 */
	public function GetTwigContext();
}
