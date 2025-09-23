<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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
 */

namespace Combodo\iTop\Application\UI\Base\Component\Html;


/**
 * @package Combodo\iTop\Application\UI\Base\Component\Html
 * @since 3.0.0
 */
class HtmlFactory
{
	/**
	 * Make an HTML block without any extra markup.
	 * The only purpose of this method is to enable devs to use only factories without instantiating base class directly.
	 *
	 * @param string $sContent
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Html\Html
	 */
	public static function MakeRaw(string $sContent): Html
	{
		return new Html($sContent);
	}

	/**
	 * Make an HTML paragraph with $sContent inside
	 *
	 * @param string $sContent
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Html\Html
	 */
	public static function MakeParagraph(string $sContent): Html
	{
		return new Html('<p>'.$sContent.'</p>');
	}

	/**
	 * Create a container for contents having multiple HTML tags, for which we want to preserve style and don't apply minireset
	 *
	 * @param string $sContent
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Html\Html
	 */
	public static function MakeHtmlContent(string $sContent): Html
	{
		return new Html('<div class="ibo-is-html-content">'.$sContent.'</div>');
	}
}