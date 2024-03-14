<?php
/**
 * Copyright (C) 2010-2023 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

namespace  Combodo\iTop\Dependencies\NPM;

use Combodo\iTop\Dependencies\AbstractFolderAnalyzer;

class iTopNPM extends AbstractFolderAnalyzer
{
	/**
	 * @inheritDoc
	 */
	protected function GetDependenciesRootFolderRelPath(): string
	{
		return "node_modules/";
	}

	/**
	 * @inheritDoc
	 */
	public function ListAllowedFoldersRelPaths(): array
	{
		return [
			// jQuery Sizzle used by jQuery
			'jquery/external',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function ListDeniedFoldersRelPaths(): array
	{
		return [
			// ACE Editor see https://www.npmjs.com/package/ace-builds for dir contents
			'ace-builds/demo',
			'ace-builds/src',
			'ace-builds/src-min-noconflict',
			'ace-builds/src-noconflict',

			'c3/htdocs',
			'clipboard/demo',
			'clipboard/test',
			'delegate/demo',
			'delegate/test',
			'good-listener/demo',
			'good-listener/test',
			'jquery-migrate/test',

			// `jquery-ui` package is just there for vulnerability scans, so we don't want to version its files (only `jquery-ui-dist` is used within the code base)
			'jquery-ui/.github',
			'jquery-ui/build',
			'jquery-ui/dist',
			'jquery-ui/external',
			'jquery-ui/themes',
			'jquery-ui/ui',

			'jquery-ui-dist/external',
			'mousetrap/plugins/record/tests',
			'mousetrap/tests',
			'select/demo',
			'select/test',
			'selectize-plugin-a11y/examples',
			'tiny-emitter/test',
			'toastify-js/example',
		];
	}
}