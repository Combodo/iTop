<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

/**
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.1.0
 * @package Combodo\iTop\Controller
 */
interface iController
{
	/**
	 * @var string|null Meant for overlaoding. Route namespace, what will prefix the "route" parameter to define in which namespoce the operation is to be executed. If left to `null`, the controller will be ignored.
	 */
	public const ROUTE_NAMESPACE = null;

	/**
	 * It works if your JavaScript library sets an X-Requested-With HTTP header.
	 * It is known to work with common JavaScript frameworks: {@link https://wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript}
	 *
	 * @see \Symfony\Component\HttpFoundation\Request::isXmlHttpRequest() Inspired by
	 *
	 * @return bool True if the current request is an XmlHttpRequest (eg. an AJAX request)
	 */
	public function IsHandlingXmlHttpRequest(): bool;
}
