<?php
namespace Combodo\iTop\Portal\Brick;

/**
 * Description of ObjectBrick
 *
 * @package Combodo\iTop\Portal\Brick
 * @since  3.2.1
 * @author Stephen Abello <stephen.abello@combodo.com>
 */
abstract class ObjectBrick extends  AbstractBrick
{

	protected static $DEFAULT_TEMPLATES_PATH = [
		'page'  => 'itop-portal-base/portal/templates/bricks/object/layout.html.twig',
		'modal' => 'itop-portal-base/portal/templates/bricks/object/modal.html.twig',
		'mode_loader' => 'itop-portal-base/portal/templates/modal/mode_loader.html.twig',
	];

	/**
	 * @param $aCombodoPortalInstanceConf
	 *
	 * @return void
	 */
	public static function InitializeSelf($aCombodoPortalInstanceConf): void
	{
		static::LoadFromPortalProperties($aCombodoPortalInstanceConf['properties']);
	}

	/**
	 * @return string
	 */
	public static function GetPageDefaultTemplatePath(): string
	{
		return static::$DEFAULT_TEMPLATES_PATH['page'];
	}

	/**
	 * @return string
	 */
	public static function GetModalDefaultTemplatePath(): string
	{
		return static::$DEFAULT_TEMPLATES_PATH['modal'];
	}

	/**
	 * @return string
	 */
	public static function GetModeLoaderDefaultTemplatePath(): string
	{
		return static::$DEFAULT_TEMPLATES_PATH['mode_loader'];
	}
}