<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
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
	public const QUESTIONNABLE_FILES_REGEXP = '/^(tests?|examples?|htdocs?|demos?|website|external|libs?|src|.github|.idea)$/i';

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
	public function ListAllowedFilesRelPaths(): array
	{
		return [
			'ace-builds/textarea/src',      // Unknown usage
			'commander/lib',                // Unknown usage

			'jquery-contextmenu/src',       // Used sources
			'magnific-popup/libs',          // Unknown usage
			'toastify-js/src',              // Used sources
		];
	}

	/**
	 * @inheritDoc
	 */
	public function ListDeniedFilesRelPaths(): array
	{
		return [
			'@fontsource/raleway/files/raleway-cyrillic-100-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-100-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-100-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-100-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-200-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-200-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-200-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-200-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-300-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-300-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-300-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-300-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-400-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-400-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-400-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-400-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-500-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-500-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-500-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-500-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-600-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-600-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-600-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-600-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-700-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-700-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-700-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-700-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-800-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-800-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-800-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-800-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-900-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-900-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-900-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-900-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-100-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-100-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-100-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-100-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-200-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-200-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-200-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-200-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-300-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-300-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-300-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-300-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-400-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-400-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-400-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-400-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-500-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-500-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-500-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-500-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-600-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-600-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-600-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-600-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-700-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-700-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-700-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-700-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-800-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-800-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-800-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-800-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-900-italic.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-900-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-900-normal.woff',
			'@fontsource/raleway/files/raleway-cyrillic-ext-900-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-variable-wghtOnly-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-ext-variable-wghtOnly-normal.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-variable-wghtOnly-italic.woff2',
			'@fontsource/raleway/files/raleway-cyrillic-variable-wghtOnly-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-100-italic.woff',
			'@fontsource/raleway/files/raleway-latin-100-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-100-normal.woff',
			'@fontsource/raleway/files/raleway-latin-100-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-200-italic.woff',
			'@fontsource/raleway/files/raleway-latin-200-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-200-normal.woff',
			'@fontsource/raleway/files/raleway-latin-200-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-300-italic.woff',
			'@fontsource/raleway/files/raleway-latin-300-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-300-normal.woff',
			'@fontsource/raleway/files/raleway-latin-300-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-400-italic.woff',
			'@fontsource/raleway/files/raleway-latin-400-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-400-normal.woff',
			'@fontsource/raleway/files/raleway-latin-400-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-500-italic.woff',
			'@fontsource/raleway/files/raleway-latin-500-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-500-normal.woff',
			'@fontsource/raleway/files/raleway-latin-500-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-600-italic.woff',
			'@fontsource/raleway/files/raleway-latin-600-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-600-normal.woff',
			'@fontsource/raleway/files/raleway-latin-600-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-700-italic.woff',
			'@fontsource/raleway/files/raleway-latin-700-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-700-normal.woff',
			'@fontsource/raleway/files/raleway-latin-700-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-800-italic.woff',
			'@fontsource/raleway/files/raleway-latin-800-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-800-normal.woff',
			'@fontsource/raleway/files/raleway-latin-800-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-900-italic.woff',
			'@fontsource/raleway/files/raleway-latin-900-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-900-normal.woff',
			'@fontsource/raleway/files/raleway-latin-900-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-100-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-100-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-100-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-100-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-200-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-200-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-200-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-200-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-300-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-300-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-300-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-300-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-400-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-400-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-400-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-400-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-500-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-500-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-500-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-500-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-600-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-600-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-600-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-600-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-700-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-700-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-700-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-700-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-800-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-800-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-800-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-800-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-900-italic.woff',
			'@fontsource/raleway/files/raleway-latin-ext-900-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-900-normal.woff',
			'@fontsource/raleway/files/raleway-latin-ext-900-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-variable-wghtOnly-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-ext-variable-wghtOnly-normal.woff2',
			'@fontsource/raleway/files/raleway-latin-variable-wghtOnly-italic.woff2',
			'@fontsource/raleway/files/raleway-latin-variable-wghtOnly-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-100-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-100-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-100-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-100-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-200-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-200-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-200-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-200-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-300-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-300-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-300-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-300-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-400-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-400-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-400-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-400-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-500-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-500-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-500-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-500-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-600-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-600-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-600-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-600-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-700-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-700-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-700-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-700-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-800-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-800-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-800-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-800-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-900-italic.woff',
			'@fontsource/raleway/files/raleway-vietnamese-900-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-900-normal.woff',
			'@fontsource/raleway/files/raleway-vietnamese-900-normal.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-variable-wghtOnly-italic.woff2',
			'@fontsource/raleway/files/raleway-vietnamese-variable-wghtOnly-normal.woff2',

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
			'jquery-contextmenu/src',
			'jquery-migrate/.github',
			'jquery-migrate/.idea',
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