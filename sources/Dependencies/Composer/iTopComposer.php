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

namespace  Combodo\iTop\Dependencies\Composer;

use Combodo\iTop\Dependencies\AbstractHook;

class iTopComposer extends AbstractHook
{
	/**
	 * @inheritDoc
	 */
	protected function GetDependenciesRootFolderAbsPath(): string
	{
		return $this->GetApprootPathWithSlashes() . "lib";
	}

	/**
	 * @inheritDoc
	 */
	public function ListAllowedQuestionnableFoldersAbsPaths(): array
	{
		$APPROOT_WITH_SLASHES = $this->GetDependenciesRootFolderAbsPath();
		return [
			$APPROOT_WITH_SLASHES . '/twig/twig/src/Node/Expression/Test',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function ListDeniedQuestionnableFolderAbsPaths(): array
	{
		$APPROOT_WITH_SLASHES = $this->GetDependenciesRootFolderAbsPath();
		return [
			$APPROOT_WITH_SLASHES . '/doctrine/lexer/tests',

			$APPROOT_WITH_SLASHES . '/goaop/framework/tests',

			$APPROOT_WITH_SLASHES . '/laminas/laminas-servicemanager/src/Test',

			$APPROOT_WITH_SLASHES . '/nikic/php-parser/test',

			$APPROOT_WITH_SLASHES . '/pear/archive_tar/tests',
			$APPROOT_WITH_SLASHES . '/pear/console_getopt/tests',
			$APPROOT_WITH_SLASHES . '/pear/pear_exception/tests',

			$APPROOT_WITH_SLASHES . '/psr/log/Psr/Log/Test',

			$APPROOT_WITH_SLASHES . '/symfony/cache/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/cache/Tests/DoctrineProviderTest.php',
			$APPROOT_WITH_SLASHES . '/symfony/class-loader/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/config/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/console/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/css-selector/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/debug/Resources/ext/tests',
			$APPROOT_WITH_SLASHES . '/symfony/debug/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/dependency-injection/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/dotenv/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/event-dispatcher/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/filesystem/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/finder/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/http-client-contracts/Test',
			$APPROOT_WITH_SLASHES . '/symfony/http-foundation/Test',
			$APPROOT_WITH_SLASHES . '/symfony/http-kernel/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/service-contracts/Test',
			$APPROOT_WITH_SLASHES . '/symfony/framework-bundle/Test',
			$APPROOT_WITH_SLASHES . '/symfony/mime/Test',
			$APPROOT_WITH_SLASHES . '/symfony/routing/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/stopwatch/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/translation-contracts/Test',
			$APPROOT_WITH_SLASHES . '/symfony/twig-bridge/Test',
			$APPROOT_WITH_SLASHES . '/symfony/twig-bundle/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/var-dumper/Test',
			$APPROOT_WITH_SLASHES . '/symfony/var-dumper/Tests/Test',
			$APPROOT_WITH_SLASHES . '/symfony/var-dumper/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/web-profiler-bundle/Tests',
			$APPROOT_WITH_SLASHES . '/symfony/yaml/Tests',

			$APPROOT_WITH_SLASHES . '/tecnickcom/tcpdf/examples',

			$APPROOT_WITH_SLASHES . '/thenetworg/oauth2-azure/tests',

			$APPROOT_WITH_SLASHES . '/twig/twig/src/Test',
			$APPROOT_WITH_SLASHES . '/twig/twig/lib/Twig/Test',
			$APPROOT_WITH_SLASHES . '/twig/twig/doc/tests',

			$APPROOT_WITH_SLASHES . '/laminas/laminas-servicemanager/src/Test',
		];
	}
}