<?php

/**
 * Login page extensibility
 *
 * @api
 * @package UIExtensibilityAPI
 * @since 3.x.x
 */
interface iTokenLoginUIExtension
{
	/**
	 * @return array
	 * @api
	 */
	public function GetTokenInfo(): array;

	/**
	 * @return array
	 * @api
	 */
	public function GetUserLogin(array $aTokenInfo): string;
}
