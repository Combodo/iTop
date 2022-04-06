<?php
/**
 * Copyright (c) 2010-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */


/**
 * Allow the setup page to load and perform its checks (including the check about the required extensions)
 */
if (!class_exists('DOMDocument'))
{
	/**
	 * Class DOMDocument
	 */
	class DOMDocument {
		function __construct(){throw new Exception('The dom extension is not enabled');}
	}
}


/**
 * Allow the setup page to load and perform its checks (including the check about the required extensions)
 */
if (!class_exists('DOMElement'))
{
	/**
	 * Class DOMElement
	 */
	class DOMElement {
		function __construct(){throw new Exception('The dom extension is not enabled');}
	}
}
