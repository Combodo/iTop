<?php

/**
 * Extend this class instead of iPageUIBlockExtension if you don't need to overload all methods
 *
 * @api
 * @package     UIBlockExtensibilityAPI
 * @since       3.0.0
 */
abstract class AbstractPageUIBlockExtension implements iPageUIBlockExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetBannerBlock()
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHeaderBlock()
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetFooterBlock()
	{
		return null;
	}
}
