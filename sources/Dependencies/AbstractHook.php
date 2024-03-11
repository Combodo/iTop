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
 * Class AbstractHook
 *
 * Extend this class to enable a dependency manager such as Composer or NPM to clean unnecessary files after an installation or update of a package.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Dependencies
 */
abstract class AbstractHook
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
	public const QUESTIONNABLE_FOLDER_REGEXP = '/^(tests?|examples?|htdocs?|demos?|external)$/i';

	/**
	 * @return string Absolute path to the root folder of the dependencies (composer, npm, ...)
	 */
	abstract protected function GetDependenciesRootFolderAbsPath(): string;

	/**
	 * @return array List of all subdirs of the dependencies folder that are {@see IsQuestionnableFolder}.
	 *              Warning : each path contains slashes (meaning on Windows you'll get eg `C:/Dev/wamp64/www/itop-27/lib/goaop/framework/tests`)
	 */
	public function ListAllQuestionnableFoldersAbsPaths(): array
	{
		$aAllTestDirs = array();
		$sPath = realpath($this->GetDependenciesRootFolderAbsPath());

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
			if (!$this->IsQuestionnableFolder($sDirName))
			{
				continue;
			}

			$sTestPathDir = $file->getRealpath();
			$sTestPathDir = str_replace('\\', '/', $sTestPathDir);
			$aAllTestDirs[] = $sTestPathDir;
		}

		return $aAllTestDirs;
	}

	/**
	 * @param string $sFolderName
	 *
	 * @return false|int as {@see \preg_match()}
	 * @uses static::QUESTIONNABLE_FOLDER_REGEXP
	 * @uses \preg_match()
	 */
	public static function IsQuestionnableFolder(string $sFolderName): false|int
	{
		return preg_match(static::QUESTIONNABLE_FOLDER_REGEXP, $sFolderName);
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
	 * @return array Array of absolute paths to allowed questionnable folders
	 */
	abstract public function ListAllowedQuestionnableFoldersAbsPaths(): array;

	/**
	 * @return array Array of absolute paths to denied questionnable folders
	 */
	abstract public function ListDeniedQuestionnableFolderAbsPaths(): array;

	/**
	 * @return array Array of absolute paths to questionnable denied test folders that need to be marked as allowed or denied
	 */
	public function ListDeniedButStillPresent(): array
	{
		$aDeniedTestDir = $this->ListDeniedQuestionnableFolderAbsPaths();
		$aAllTestDir = $this->ListAllQuestionnableFoldersAbsPaths();
		return array_intersect($aDeniedTestDir, $aAllTestDir);
	}
}