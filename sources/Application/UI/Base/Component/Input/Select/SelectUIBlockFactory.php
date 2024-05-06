<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\Select;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\Select;

/**
 * Class SelectUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class SelectUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UISelect';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Select::class;

	/**
	 * Create a default Select input
	 *
	 * @api
	 * @param string $sName {@see Select::$sName}
	 * @param string|null $sId {@see UIBlock::$sId}
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Select\Select
	 */
	public static function MakeForSelect(string $sName, ?string $sId = null)
	{
		$oInput = new Select($sId);
		$oInput->SetName($sName);

		return $oInput;
	}

	/**
	 * Create a Select input with a label
	 *
	 * If you need to have a real field with a label, you might use a {@link Field} component instead
	 *
	 * @api
	 * @param string $sName {@see Select::$sName}
	 * @param string $sLabel {@see Select::$sLabel}
	 * @param string|null $sId {@see UIBlock::$sId}
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Select\Select
	 */
	public static function MakeForSelectWithLabel(string $sName, string $sLabel, ?string $sId = null)
	{
		$oInput = new Select($sId);
		$oInput->SetName($sName);
		$oInput->SetLabel($sLabel);

		return $oInput;
	}

}