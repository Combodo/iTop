<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\TurboUpdate;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class TurboUpdateUIBlockFactory
 *
 * @api
 * @since 3.3.0
 * @package UIBlockAPI
 */
class TurboStreamUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UITurboStream';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = TurboStream::class;

	/**
	 * @api
	 *
	 * @param string $sTarget   Id of the block to update
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboUpdate\TurboStream An HTML form in which you can add UIBlocks
	 */
	public static function MakeUpdate(string $sTarget, string $sId = null): TurboStream
	{
		return new TurboStream($sTarget, 'update', $sId);
	}

	/**
	 * @api
	 *
	 * @param string $sTarget   Id of the block to update
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboUpdate\TurboStream An HTML form in which you can add UIBlocks
	 */
	public static function MakeReplace(string $sTarget, string $sId = null): TurboStream
	{
		return new TurboStream($sTarget, 'replace', $sId);
	}

	/**
	 * @api
	 *
	 * @param string $sTarget   Id of the block to update
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboUpdate\TurboStream An HTML form in which you can add UIBlocks
	 */
	public static function MakePrepend(string $sTarget, string $sId = null): TurboStream
	{
		return new TurboStream($sTarget, 'prepend', $sId);
	}

	/**
	 * @api
	 *
	 * @param string $sTarget   Id of the block to update
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboUpdate\TurboStream An HTML form in which you can add UIBlocks
	 */
	public static function MakeAppend(string $sTarget, string $sId = null): TurboStream
	{
		return new TurboStream($sTarget, 'append', $sId);
	}
}
