<?php

namespace Combodo\iTop\FormImplementation\Helper;

use MetaModel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Helper to construct assets paths.
 *
 */
class TwigHelper extends AbstractExtension
{
	/** @var string|mixed application root */
	private string $sAppRoot;

	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
		$this->sAppRoot = MetaModel::GetConfig()->Get('app_root_url');
	}

	/** @inheritdoc  */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('asset_js', [$this, 'asset_js']),
			new TwigFunction('asset_css', [$this, 'asset_css']),
			new TwigFunction('asset_image', [$this, 'asset_image']),
			new TwigFunction('asset_node', [$this, 'asset_node']),
		];
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function asset_js($name) : string
	{
		return $this->sAppRoot . 'js/' . $name;
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function asset_css($name) : string
	{
		return $this->sAppRoot . 'css/' . $name;
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function asset_image($name) : string
	{
		return $this->sAppRoot . 'images/' . $name;
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function asset_node($name) : string
	{
		return $this->sAppRoot . 'node_modules/' . $name;
	}
}