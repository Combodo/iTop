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
	/** @var string DEFAULT_PAGE_TEMPLATE_PATH */
	const DEFAULT_PAGE_TEMPLATE_PATH = 'itop-portal-base/portal/templates/bricks/object/layout.html.twig';
	/** @var string DEFAULT_MODAL_TEMPLATE_PATH */
	const DEFAULT_MODAL_TEMPLATE_PATH = 'itop-portal-base/portal/templates/bricks/object/modal.html.twig';
	/** @var string DEFAULT_MODE_LOADER_TEMPLATE_PATH */
	const DEFAULT_MODE_LOADER_TEMPLATE_PATH = 'itop-portal-base/portal/templates/modal/mode_loader.html.twig';

	protected static $DEFAULT_TEMPLATES_PATH = [
		'page'  => self::DEFAULT_PAGE_TEMPLATE_PATH,
		'modal' => self::DEFAULT_MODAL_TEMPLATE_PATH,
		'mode_loader' => self::DEFAULT_MODE_LOADER_TEMPLATE_PATH,
	];

	/**
	 * @param $aCombodoPortalInstanceConf
	 *
	 * @return void
	 */
	public static function InitializeSelf($aCombodoPortalInstanceConf): void
	{
		static::LoadClassDefinitionFromPortalProperties($aCombodoPortalInstanceConf['properties']);
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