<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Form;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class FormUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @since 3.0.0
 * @api
 */
class FormUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIForm';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Form::class;

	/**
	 * @api
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Form\Form An HTML form in which you can add UIBlocks
	 */
	public static function MakeStandard(string $sId = null)
	{
		return new Form($sId);
	}
}