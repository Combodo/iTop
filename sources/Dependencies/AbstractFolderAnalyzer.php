<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace  Combodo\iTop\Dependencies;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class AbstractFolderAnalyzer
 *
 * Extend this class to enable a dependency manager such as Composer or NPM to clean unnecessary files after an installation or update of a package.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Dependencies
 *
 * @since 3.2.0 N°7331 class creation to have managers for both Composer and NPM (only Composer was existing before)
 */
abstract class AbstractFolderAnalyzer
{
	/**
	 * Questionnable folder is a folder name that seems like it doesn't need to be package as it only contain
	 * unnecessary files (e.g. lib test or example files)
	 *
	 * Parenthesis around alternation as it is eager (see linked ref), and we really want to use start / end of string
	 * `^test|examples$i` would match Tester for example
	 * `^(test|examples)$i` is not !
	 *
	 * @since 3.2.0 N°7175 update regexp to also remove `examples` folder
	 * @link https://www.regular-expressions.info/alternation.html RegExp alternation reference
	 */
	public const QUESTIONNABLE_FILES_REGEXP = '/^(tests?|examples?|htdocs?|demos?|.github|.idea)$/i';

	/**
	 * @param string $sFileName
	 *
	 * @return false|int as {@see \preg_match()}
	 * @uses static::QUESTIONNABLE_FILES_REGEXP
	 * @uses \preg_match()
	 */
	public static function IsQuestionnableFile(string $sFileName): false|int
	{
		return preg_match(static::QUESTIONNABLE_FILES_REGEXP, $sFileName);
	}

	/**
	 * @return string Relative path to the root folder of the dependencies (e.g. "lib" for composer, "node_modules" for npm, ...) from iTop app. root
	 */
	abstract protected function GetDependenciesRootFolderRelPath(): string;

	/**
	 * @return string Absolute path to the root folder of the dependencies
	 */
	public function GetDependenciesRootFolderAbsPath(): string
	{
		return $this->GetApprootPathWithSlashes() . $this->GetDependenciesRootFolderRelPath();
	}

	/**
	 * @return string APPROOT constant but with slashes instead of DIRECTORY_SEPARATOR.
	 *      This ease writing our paths, as we can use '/' for every platform.
	 */
	final protected function GetApprootPathWithSlashes(): string
	{
		return str_replace(DIRECTORY_SEPARATOR, '/', APPROOT);
	}

	/**
	 * @param bool $bCheckQuestionableFilesOnly If true, only questionnable folders {@see \Combodo\iTop\Dependencies\AbstractFolderAnalyzer::IsQuestionnableFile()} will be listed
	 *
	 * @return array List of all subdirs of the dependencies folder that are {@see IsQuestionnableFile}.
	 *              Warning : each path contains slashes (meaning on Windows you'll get eg `C:/Dev/wamp64/www/itop-27/lib/goaop/framework/tests`)
	 */
	public function ListAllFilesAbsPaths(bool $bCheckQuestionableFilesOnly = true): array
	{
		$aAllTestDirs = array();
		$sPath = realpath($this->GetDependenciesRootFolderAbsPath());

		$oDirectoryIterator = new RecursiveDirectoryIterator($sPath,  FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO|FilesystemIterator::SKIP_DOTS|FilesystemIterator::UNIX_PATHS);
		$iterator = new RecursiveIteratorIterator(
			$oDirectoryIterator,
			RecursiveIteratorIterator::CHILD_FIRST);

		/** @var DirectoryIterator $file */
		foreach($iterator as $file) {
			$sDirName = $file->getFilename();
			if ($bCheckQuestionableFilesOnly && !static::IsQuestionnableFile($sDirName)) {
				continue;
			}

			$sTestPathDir = $file->getRealpath();
			$sTestPathDir = str_replace('\\', '/', $sTestPathDir);
			$aAllTestDirs[] = $sTestPathDir;
		}

		return $aAllTestDirs;
	}

	/**
	 * @return array Array of absolute paths to allowed questionnable folders
	 */
	abstract public function ListAllowedFilesRelPaths(): array;

	/**
	 * @return array Array of absolute paths to allowed folders
	 */
	public function ListAllowedFilesAbsPaths(): array
	{
		return array_map(fn ($sRelPath): string => $this->GetDependenciesRootFolderAbsPath() . $sRelPath, $this->ListAllowedFilesRelPaths());
	}

	/**
	 * @return array Array of relative paths (from dependencies root folder {@see static::GetDependenciesRootFolderAbsPath()}) to denied folders
	 */
	abstract public function ListDeniedFilesRelPaths(): array;

	/**
	 * @return array Array of absolute paths to denied folders
	 */
	public function ListDeniedFilesAbsPaths(): array
	{
		return array_map(fn ($sRelPath): string => $this->GetDependenciesRootFolderAbsPath() . $sRelPath, $this->ListDeniedFilesRelPaths());
	}

	/**
	 * @return array Array of absolute paths to questionnable denied test folders that need to be marked as allowed or denied
	 */
	public function ListDeniedButStillPresentFilesAbsPaths(): array
	{
		$aDeniedTestDir = $this->ListDeniedFilesAbsPaths();
		$aAllTestDir = $this->ListAllFilesAbsPaths(false);
		return array_intersect($aDeniedTestDir, $aAllTestDir);
	}
}