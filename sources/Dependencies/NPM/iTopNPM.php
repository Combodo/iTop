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
	/** @inheritDoc */
	public const QUESTIONNABLE_FOLDER_REGEXP = '/^(tests?|examples?|htdocs?|demos?|.github|website|external|libs?|src)$/i';

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
			'ace-builds/textarea/src',      // Unknown usage
			'cliui/build/lib',              // Unknown usage

			'jquery/external',              // jQuery Sizzle used by jQuery

			'jquery-contextmenu/src',       // Used sources
			'magnific-popup/libs',          // Unknown usage
			'toastify-js/src',              // Used sources

			'y18n/build/lib',               // Unknown usage
			'yargs/build/lib',              // Unknown usage
			'yargs/lib',                    // Unknown usage
			'yargs-parser/build/lib',       // Unknown usage
		];
	}

	/**
	 * @inheritDoc
	 */
	public function ListDeniedFoldersRelPaths(): array
	{
		return [
			'@popperjs/core/dist/cjs',
			'@popperjs/core/dist/esm',
			'@popperjs/core/lib',

			// ACE Editor see https://www.npmjs.com/package/ace-builds for dir contents
			'ace-builds/.github',
			'ace-builds/demo',
			'ace-builds/src',
			'ace-builds/src-min-noconflict',
			'ace-builds/src-noconflict',

			'c3/htdocs',
			'c3/src',
			'clipboard/.github',
			'clipboard/demo',
			'clipboard/src',
			'clipboard/test',
			'd3/src',
			'delegate/demo',
			'delegate/src',
			'delegate/test',
			'good-listener/demo',
			'good-listener/src',
			'good-listener/test',
			'jquery/src',
			'jquery-migrate/src',
			'jquery-migrate/test',

			// `jquery-ui` package is just there for vulnerability scans, so we don't want to version its files (only `jquery-ui-dist` is used within the code base)
			'jquery-ui/.github',
			'jquery-ui/build',
			'jquery-ui/dist',
			'jquery-ui/external',
			'jquery-ui/themes',
			'jquery-ui/ui',

			'jquery-ui-dist/external',
			'magnific-popup/libs/jquery',
			'magnific-popup/src',
			'magnific-popup/website',
			'moment/src',
			'mousetrap/plugins/record/tests',
			'mousetrap/tests',
			'select/demo',
			'select/src',
			'select/test',
			'selectize-plugin-a11y/examples',
			'tiny-emitter/test',
			'toastify-js/example',
		];
	}
}