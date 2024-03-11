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

use Combodo\iTop\Dependencies\AbstractHook;

class iTopNPM extends AbstractHook
{
	/**
	 * @inheritDoc
	 */
	protected function GetDependenciesRootFolderAbsPath(): string
	{
		return $this->GetApprootPathWithSlashes() . "node_modules";
	}

	/**
	 * @inheritDoc
	 */
	public function ListAllowedQuestionnableFoldersAbsPaths(): array
	{
		$APPROOT_WITH_SLASHES = $this->GetDependenciesRootFolderAbsPath();
		return [
			// jQuery Sizzle used by jQuery
			$APPROOT_WITH_SLASHES . '/jquery/external',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function ListDeniedQuestionnableFolderAbsPaths(): array
	{
		$APPROOT_WITH_SLASHES = $this->GetDependenciesRootFolderAbsPath();
		return [
			$APPROOT_WITH_SLASHES . '/ace-builds/demo',
			$APPROOT_WITH_SLASHES . '/ace-builds/src',
			$APPROOT_WITH_SLASHES . '/ace-builds/src-min-noconflict',
			$APPROOT_WITH_SLASHES . '/ace-builds/src-noconflict',

			$APPROOT_WITH_SLASHES . '/c3/htdocs',
			$APPROOT_WITH_SLASHES . '/clipboard/demo',
			$APPROOT_WITH_SLASHES . '/clipboard/test',
			$APPROOT_WITH_SLASHES . '/delegate/demo',
			$APPROOT_WITH_SLASHES . '/delegate/test',
			$APPROOT_WITH_SLASHES . '/good-listener/demo',
			$APPROOT_WITH_SLASHES . '/good-listener/test',
			$APPROOT_WITH_SLASHES . '/jquery-migrate/test',

			// `jquery-ui` package is just there for vulnerability scans, so we don't want to version its files (only `jquery-ui-dist` is used within the code base)
			$APPROOT_WITH_SLASHES . '/jquery-ui/.github',
			$APPROOT_WITH_SLASHES . '/jquery-ui/build',
			$APPROOT_WITH_SLASHES . '/jquery-ui/dist',
			$APPROOT_WITH_SLASHES . '/jquery-ui/external',
			$APPROOT_WITH_SLASHES . '/jquery-ui/themes',
			$APPROOT_WITH_SLASHES . '/jquery-ui/ui',

			$APPROOT_WITH_SLASHES . '/jquery-ui-dist/external',
			$APPROOT_WITH_SLASHES . '/mousetrap/plugins/record/tests',
			$APPROOT_WITH_SLASHES . '/mousetrap/tests',
			$APPROOT_WITH_SLASHES . '/select/demo',
			$APPROOT_WITH_SLASHES . '/select/test',
			$APPROOT_WITH_SLASHES . '/selectize-plugin-a11y/examples',
			$APPROOT_WITH_SLASHES . '/tiny-emitter/test',
		];
	}
}