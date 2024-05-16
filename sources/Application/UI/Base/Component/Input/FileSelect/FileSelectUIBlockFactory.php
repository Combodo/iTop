<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\FileSelect;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class FileSelectUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class FileSelectUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIFileSelect';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = FileSelect::class;

	/**
	 * @api
	 * @param string $sName
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\FileSelect\FileSelect A styled file input selector
	 */
	public static function MakeStandard(string $sName, string $sId = null)
	{
		return new FileSelect($sName, $sId);
	}
}