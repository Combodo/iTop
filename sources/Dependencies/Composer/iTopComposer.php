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

namespace  Combodo\iTop\Dependencies\Composer;

use Combodo\iTop\Dependencies\AbstractFolderAnalyzer;

class iTopComposer extends AbstractFolderAnalyzer
{
	/**
	 * @inheritDoc
	 */
	protected function GetDependenciesRootFolderRelPath(): string
	{
		return "lib/";
	}

	/**
	 * @inheritDoc
	 */
	public function ListAllowedFilesRelPaths(): array
	{
		return [
			'twig/twig/src/Node/Expression/Test',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function ListDeniedFilesRelPaths(): array
	{
		return [
			'doctrine/lexer/tests',

			'goaop/framework/tests',

			'laminas/laminas-servicemanager/src/Test',

			'nikic/php-parser/test',

			'pear/archive_tar/.github',
			'pear/archive_tar/tests',
			'pear/console_getopt/tests',
			'pear/pear_exception/tests',
			'pear/archive_tar/docs',
			'pear/archive_tar/scripts',
			'pear/archive_tar/sync-php4',

			'psr/log/Psr/Log/Test',

			'soundasleep/html2text/.github',
			'soundasleep/html2text/tests',

			'symfony/cache/Tests',
			'symfony/cache/Tests/DoctrineProviderTest.php',
			'symfony/class-loader/Tests',
			'symfony/config/Tests',
			'symfony/console/Tests',
			'symfony/css-selector/Tests',
			'symfony/debug/Resources/ext/tests',
			'symfony/debug/Tests',
			'symfony/dependency-injection/Tests',
			'symfony/dotenv/Tests',
			'symfony/event-dispatcher/Tests',
			'symfony/filesystem/Tests',
			'symfony/finder/Tests',
			'symfony/http-client-contracts/Test',
			'symfony/http-foundation/Test',
			'symfony/http-kernel/Tests',
			'symfony/service-contracts/Test',
			'symfony/framework-bundle/Test',
			'symfony/mime/Test',
			'symfony/routing/Tests',
			'symfony/stopwatch/Tests',
			'symfony/translation-contracts/Test',
			'symfony/twig-bridge/Test',
			'symfony/twig-bundle/Tests',
			'symfony/var-dumper/Test',
			'symfony/var-dumper/Tests/Test',
			'symfony/var-dumper/Tests',
			'symfony/web-profiler-bundle/Tests',
			'symfony/yaml/Tests',

			'tecnickcom/tcpdf/examples',

			'thenetworg/oauth2-azure/tests',

			'twig/twig/src/Test',
			'twig/twig/lib/Twig/Test',
			'twig/twig/doc/tests',

			'laminas/laminas-servicemanager/src/Test',
		];
	}
}