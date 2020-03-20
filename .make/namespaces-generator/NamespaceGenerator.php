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

class NamespaceGenerator
{
	CONST CLASS_PATTERN= '/class\s+(?\'class\'[_[:alnum:]]+)(?:\s+(?:extends|implements)\s+[_\\\\[:alnum:]]+|#.*|\/\/.)*\s*({|$)/';

	/** @var string[] */
	private $aScannedDirs;

	private $sWriteToPath;

	private $aExpectedClassPerDirectory = [];

	public function __construct($aScannedDirs = null, $sWriteToPath = null)
	{
		$this->aScannedDirs = $aScannedDirs ?: array(
			'addons',
			'application',
			'core' => array(
				'exclude' => array('oql'),
				'namespaces' => array(
					'apc-.*' => '\\Combodo\\iTop\\Core\\Cache\\Apc',
					'attributedef.class.inc.php' => '\\Combodo\\iTop\\Core\\AttributeDefinition',
					'db.*' => '\\Combodo\\iTop\\Core\\Orm\\DbObject',
					'oql.*' => '\\Combodo\\iTop\\Core\\Orm\\Oql',
				),
			),
			'pages',
			'setup',
			'sources',
			'synchro',
			'webservices',
		);
		$this->sWriteToPath = $sWriteToPath ?: APPROOT;
	}

	public function run()
	{
		clearstatcache();
		$aScandir = scandir($this->sWriteToPath);
		if (is_readable($this->sWriteToPath) && ($count = count($aScandir)) > 2)
		{
			$count = $count -2;

			fwrite(
				STDERR ,
				"\e[0;31mFatal error\e[0m: target is not empty: \033[0;32m{$count}\e[0m subdirectory found into '{$this->sWriteToPath}':\n"
			);

			foreach ($aScandir as $i => $sDirectory)
			{
				if ($sDirectory == '.' || $sDirectory == '..')
				{
					continue;
				}

				fwrite(
					STDERR ,
					" - {$sDirectory}\n"
				);

				if ($i > 22)
				{
					$iRemaining = count($aScandir) - 22;
					fwrite(
						STDERR ,
						" - ... (20 first showed, $iRemaining remaining)\n"
					);
					break;
				}
			}

			exit(1);
		}


		$this->aExpectedClassPerDirectory = array();

		foreach ($this->aScannedDirs as $key => $val) {

			if (is_array($val))
			{
				$sScanDir = $key;
				$aOptions = $val;
			}
			else
			{
				$sScanDir = $val;
				$aOptions = array();
			}

			if (! isset($aOptions['namespaces']))
			{
				fwrite(
					STDERR ,
					"ScanDir: \033[0;31m'{$sScanDir}' is in debug mode\e[0m (since no namespaces was given)!\n"
				);
			}
			else
			{
				$i = count($aOptions['namespaces']);
				fwrite(
					STDERR ,
					"ScanDir: \033[0;32m{$i}\e[0m namespace pattern given for '{$sScanDir}'\n"
				);
			}

			$oFinder = new Symfony\Component\Finder\Finder();
			$oFinder->files()->name('*.php')->in(APPROOT.'/'.$sScanDir);
			if (isset($aOptions['exclude']))
			{
				$oFinder->exclude($aOptions['exclude']);
			}
			$this->iterateOnFiles($oFinder, $sScanDir, $aOptions);
		}

//echo json_encode($this->aExpectedClassPerDirectory, JSON_PRETTY_PRINT);


	}

	/**
	 * @param \Symfony\Component\Finder\Finder $oFinder
	 * @param $aMatches
	 * @param $sScanDir
	 *
	 */
	protected function iterateOnFiles(\Symfony\Component\Finder\Finder $oFinder, $sScanDir, $aOptions)
	{
		foreach ($oFinder as $oFile)
		{
			$sContents = $oFile->getContents();
			if (!preg_match_all(self::CLASS_PATTERN, $sContents, $aMatches, PREG_SET_ORDER))
			{
				continue;
			}

			foreach ($aMatches as $aMatch)
			{
				$sRelativePath = "{$sScanDir}/{$oFile->getRelativePathname()}";
				$this->aExpectedClassPerDirectory[$sRelativePath][] = $aMatch['class'];
			}

			$this->parseFile($sContents, $sRelativePath, $sScanDir,  $oFile, $aOptions);
		}
	}

	/**
	 * @param $sContents
	 *
	 * @return \PhpParser\Parser
	 */
	protected function parseFile($sContents, $sRelativePath, $sScanDir, \Symfony\Component\Finder\SplFileInfo $oFile, $aOptions)
	{
		$traverser     = new \PhpParser\NodeTraverser();
		$traverser->addVisitor(new NodeVisitorClassExtractor($this->sWriteToPath, $this->aExpectedClassPerDirectory[$sRelativePath], $sScanDir, $oFile, $aOptions));

		$oParser = (new \PhpParser\ParserFactory())->create(\PhpParser\ParserFactory::PREFER_PHP7);
		$stmts = $oParser->parse($sContents); // traverse
		$stmts = $traverser->traverse($stmts);

	}
}
