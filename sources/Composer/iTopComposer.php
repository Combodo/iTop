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

namespace  Combodo\iTop\Composer;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class iTopComposer
{

	public function ListAllTestDir()
	{
		$aAllTestDirs = array();
		$sPath = realpath(APPROOT.'lib');

		$oDirectoryIterator = new RecursiveDirectoryIterator($sPath,  FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO|FilesystemIterator::SKIP_DOTS|FilesystemIterator::UNIX_PATHS);
		$iterator = new RecursiveIteratorIterator(
			$oDirectoryIterator,
			RecursiveIteratorIterator::CHILD_FIRST);

		/** @var DirectoryIterator $file */
		foreach($iterator as $file) {
			if(!$file->isDir()) {
				continue;
			}
			$sDirName = $file->getFilename();
			if (!$this->IsTestDir($sDirName))
			{
				continue;
			}

			$aAllTestDirs[] = $file->getRealpath();
		}

		return $aAllTestDirs;
	}
	
	private function IsTestDir($sDirName)
	{
		return preg_match('/^[tT]ests?$/', $sDirName);
	}

	/**
	 * @return string APPROOT constant but with slashes instead of DIRECTORY_SEPARATOR.
	 *      This ease writing our paths, as we can use '/' for every platforms.
	 */
	private function GetApprootWithSlashes()
	{
		return str_replace(DIRECTORY_SEPARATOR, '/', APPROOT);
	}

	public function ListAllowedTestDir()
	{
		$APPROOT_WITH_SLASHES = $this->GetApprootWithSlashes();
		return array(
			$APPROOT_WITH_SLASHES.'lib/twig/twig/src/Node/Expression/Test',
			$APPROOT_WITH_SLASHES.'lib/twig/twig/lib/Twig/Node/Expression/Test',
		);
	}

	public function ListDeniedTestDir()
	{
		$APPROOT_WITH_SLASHES = $this->GetApprootWithSlashes();
		return array(
			$APPROOT_WITH_SLASHES.'lib/nikic/php-parser/test',
			$APPROOT_WITH_SLASHES.'lib/symfony/framework-bundle/Test',
			$APPROOT_WITH_SLASHES.'lib/symfony/var-dumper/Test',
			$APPROOT_WITH_SLASHES.'lib/symfony/var-dumper/Tests/Test',
			$APPROOT_WITH_SLASHES.'lib/twig/twig/src/Test',
			$APPROOT_WITH_SLASHES.'lib/psr/log/Psr/Log/Test',
			$APPROOT_WITH_SLASHES.'lib/twig/twig/lib/Twig/Test',
			$APPROOT_WITH_SLASHES.'lib/symfony/framework-bundle/Tests/Fixtures/TestBundle/FooBundle/Controller/Test',
			$APPROOT_WITH_SLASHES.'lib/pear/console_getopt/tests',
			$APPROOT_WITH_SLASHES.'lib/pear/pear_exception/tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/cache/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/class-loader/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/config/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/console/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/css-selector/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/debug/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/dependency-injection/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/dotenv/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/event-dispatcher/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/filesystem/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/finder/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/framework-bundle/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/http-foundation/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/http-kernel/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/routing/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/stopwatch/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/twig-bridge/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/twig-bundle/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/var-dumper/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/web-profiler-bundle/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/yaml/Tests',
			$APPROOT_WITH_SLASHES.'lib/symfony/debug/Resources/ext/tests',
			$APPROOT_WITH_SLASHES.'lib/goaop/framework/tests',
			$APPROOT_WITH_SLASHES.'lib/twig/twig/doc/tests',
		);
	}

	public function ListDeniedButStillPresent()
	{
		$aDeniedTestDir = $this->ListDeniedTestDir();
		$aAllTestDir = $this->ListAllTestDir();
		return array_intersect($aDeniedTestDir, $aAllTestDir);
	}
}