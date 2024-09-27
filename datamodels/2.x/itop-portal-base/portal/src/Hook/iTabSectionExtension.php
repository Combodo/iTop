<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Hook\Display;

interface iTabSectionExtension
{
	/**
	 * Get the target reference of the page to display this tab
	 *
	 * @return string
	 */
	public function GetTarget(): string;

	/**
	 * Indicates if the extension is active or not
	 * @return bool
	 */
	public function IsActive(): bool;

	/**
	 * Get the absolute path to the directory containing the templates for this extension
	 * @return string  the absolute path to the directory containing the templates for this extension
	 */
	public function GetTemplatePath(): string;

	/**
	 * Tab code name where to add the section
	 *
	 * @return string tab code
	 */
	public function GetTabCode(): string;

	/**
	 * return the section template located in the template path directory
	 * the templates used are <name>.html.twig and <name>.ready.js.twig
	 *
	 * @return string template name
	 */
	public function GetTemplateName(): string;

	/**
	 * Section callable to return an array of parameters used in the twig templates
	 * in the form ['name' => value, ...]
	 * The template can reference the values with Section.<name>
	 *
	 * @return callable
	 */
	public function GetSectionCallback(): callable;

	/**
	 * Get the section rank in the tab
	 *
	 * @return float rank order
	 */
	public function GetSectionRank(): float;

}