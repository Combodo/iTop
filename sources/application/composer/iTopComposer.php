<?php
/**
 * Copyright (C) 2010-2020 Combodo SARL
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

class iTopComposer
{

	public function ListAllTestDir()
	{
		return array_merge(
			glob(APPROOT.'lib/*/*/[tT]est/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/[tT]est/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/[tT]est/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/[tT]est/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/*/[tT]est/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/*/*/[tT]est/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/*/*/*/[tT]est/', GLOB_ONLYDIR ),


			glob(APPROOT.'lib/*/*/[tT]ests/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/[tT]ests/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/[tT]ests/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/[tT]ests/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/*/[tT]ests/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/*/*/[tT]ests/', GLOB_ONLYDIR ),
			glob(APPROOT.'lib/*/*/*/*/*/*/*/*/[tT]ests/', GLOB_ONLYDIR )

		);
	}

	public function ListAllowedTestDir()
	{
		return array(
			APPROOT.'lib/twig/twig/src/Node/Expression/Test/',
			APPROOT.'lib/twig/twig/lib/Twig/Node/Expression/Test/',
		);
	}

	public function ListDeniedTestDir()
	{
		return array(
			APPROOT.'lib/nikic/php-parser/test/',
			APPROOT.'lib/symfony/framework-bundle/Test/',
			APPROOT.'lib/symfony/var-dumper/Test/',
			APPROOT.'lib/symfony/var-dumper/Tests/Test/',
			APPROOT.'lib/twig/twig/src/Test/',
			APPROOT.'lib/psr/log/Psr/Log/Test/',
			APPROOT.'lib/twig/twig/lib/Twig/Test/',
			APPROOT.'lib/symfony/framework-bundle/Tests/Fixtures/TestBundle/FooBundle/Controller/Test/',
			APPROOT.'lib/pear/console_getopt/tests/',
			APPROOT.'lib/pear/pear_exception/tests/',
			APPROOT.'lib/symfony/cache/Tests/',
			APPROOT.'lib/symfony/class-loader/Tests/',
			APPROOT.'lib/symfony/config/Tests/',
			APPROOT.'lib/symfony/console/Tests/',
			APPROOT.'lib/symfony/css-selector/Tests/',
			APPROOT.'lib/symfony/debug/Tests/',
			APPROOT.'lib/symfony/dependency-injection/Tests/',
			APPROOT.'lib/symfony/dotenv/Tests/',
			APPROOT.'lib/symfony/event-dispatcher/Tests/',
			APPROOT.'lib/symfony/filesystem/Tests/',
			APPROOT.'lib/symfony/finder/Tests/',
			APPROOT.'lib/symfony/framework-bundle/Tests/',
			APPROOT.'lib/symfony/http-foundation/Tests/',
			APPROOT.'lib/symfony/http-kernel/Tests/',
			APPROOT.'lib/symfony/routing/Tests/',
			APPROOT.'lib/symfony/stopwatch/Tests/',
			APPROOT.'lib/symfony/twig-bridge/Tests/',
			APPROOT.'lib/symfony/twig-bundle/Tests/',
			APPROOT.'lib/symfony/var-dumper/Tests/',
			APPROOT.'lib/symfony/web-profiler-bundle/Tests/',
			APPROOT.'lib/symfony/yaml/Tests/',
			APPROOT.'lib/symfony/debug/Resources/ext/tests/',
		);
	}

	public function ListDeniedButStillPresent()
	{
		$aDeniedTestDir = $this->ListDeniedTestDir();
		$aAllTestDir = $this->ListAllTestDir();
		return array_intersect($aDeniedTestDir, $aAllTestDir);
	}
}