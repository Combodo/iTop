<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

/**
 * Class AbstractController
 *
 * Abstract controller to centralize common features of business controllers which are still to be defined.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Controller
 * @since 3.1.0
 */
class AbstractController
{
	/**
	 * It works if your JavaScript library sets an X-Requested-With HTTP header.
	 * It is known to work with common JavaScript frameworks: {@link https://wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript}
	 *
	 * @see \Symfony\Component\HttpFoundation\Request::isXmlHttpRequest() Inspired by
	 *
	 * @return bool True if the current request is an XmlHttpRequest (eg. an AJAX request)
	 */
	public function IsHandlingXmlHttpRequest(): bool
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
	}
}