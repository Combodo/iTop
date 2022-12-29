<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Spinner;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class SpinnerUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockExtensibilityAPI
 * @api
 * @since 3.0.0
 */
class SpinnerUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UISpinner';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Spinner::class;

	/**
	 * @api
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Spinner\Spinner
	 */
	public static function MakeStandard(?string $sId = null)
	{
		return new Spinner($sId);
	}
}