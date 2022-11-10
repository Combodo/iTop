<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest;
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 20/11/2017
 * Time: 11:21
 */

use CMDBSource;
use MySQLTransactionNotClosedException;
use PHPUnit\Framework\TestCase;
use SetupUtils;

define('DEBUG_UNIT_TEST', true);

class ItopTestCase extends TestCase
{
	const TEST_LOG_DIR = 'test';

	/** @noinspection UsingInclusionOnceReturnValueInspection avoid errors for approot includes */
	protected function setUp(): void
	{
		@include_once '../approot.inc.php';
		@include_once '../../approot.inc.php';
		@include_once '../../../approot.inc.php';
		@include_once '../../../../approot.inc.php';
		@include_once '../../../../../approot.inc.php';
		@include_once '../../../../../../approot.inc.php';
		@include_once '../../../../../../../approot.inc.php';
		@include_once '../../../../../../../../approot.inc.php';
		@include_once getcwd().'/approot.inc.php'; // this is when launching phpunit from within the IDE
	}

	/**
	 * @throws \MySQLTransactionNotClosedException see N°5538
	 * @since 2.7.8 3.0.3 3.1.0 N°5538
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		if (CMDBSource::IsInsideTransaction()) {
			// Nested transactions were opened but not finished !
			throw new MySQLTransactionNotClosedException('Some DB transactions were opened but not closed ! Fix the code by adding ROLLBACK or COMMIT statements !', []);
		}
	}

	protected function debug($sMsg)
	{
		if (DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
		        /** @noinspection ForgottenDebugOutputInspection */
		        print_r($sMsg);
	        }
        }
    }

	public function GetMicroTime()
	{
		list($uSec, $sec) = explode(" ", microtime());
		return ((float)$uSec + (float)$sec);
	}

	public function WriteToCsvHeader($sFilename, $aHeader)
	{
		$sResultFile = APPROOT.'log/'.$sFilename;
		if (is_file($sResultFile))
		{
			@unlink($sResultFile);
		}
		SetupUtils::builddir(dirname($sResultFile));
		file_put_contents($sResultFile, implode(';', $aHeader)."\n");
	}

	public function WriteToCsvData($sFilename, $aData)
	{
		$sResultFile = APPROOT.'log/'.$sFilename;
		$file = fopen($sResultFile, 'a');
		fputs($file, implode(';', $aData)."\n");
		fclose($file);
	}

	public function GetTestId()
	{
		$sId = str_replace('"', '', $this->getName());
		$sId = str_replace(' ', '_', $sId);

		return $sId;
	}

	/**
	 * @since 2.7.4 3.0.0
	 */
	public function InvokeNonPublicStaticMethod($sObjectClass, $sMethodName, $aArgs)
	{
		return $this->InvokeNonPublicMethod($sObjectClass, $sMethodName, null, $aArgs);
	}

	/**
	 * @param string $sObjectClass for example DBObject::class
	 * @param string $sMethodName
	 * @param object $oObject
	 * @param array $aArgs
	 *
	 * @return mixed method result
	 *
	 * @throws \ReflectionException
	 *
	 * @since 2.7.4 3.0.0
	 */
	public function InvokeNonPublicMethod($sObjectClass, $sMethodName, $oObject, $aArgs)
	{
		$class = new \ReflectionClass($sObjectClass);
		$method = $class->getMethod($sMethodName);
		$method->setAccessible(true);

		return $method->invokeArgs($oObject, $aArgs);
	}


	/**
	 * @param object $oObject
	 * @param string $sProperty
	 *
	 * @return mixed property
	 *
	 * @throws \ReflectionException
	 * @since 2.7.8 3.0.3 3.1.0
	 */
	public function GetNonPublicProperty(object $oObject, string $sProperty)
	{
		$class = new \ReflectionClass(get_class($oObject));
		$property = $class->getProperty($sProperty);
		$property->setAccessible(true);

		return $property->getValue($oObject);
	}

	/**
	 * @param object $oObject
	 * @param string $sProperty
	 * @param $value
	 *
	 * @throws \ReflectionException
	 * @since 2.7.8 3.0.3 3.1.0
	 */
	public function SetNonPublicProperty(object $oObject, string $sProperty, $value)
	{
		$class = new \ReflectionClass(get_class($oObject));
		$property = $class->getProperty($sProperty);
		$property->setAccessible(true);

		$property->setValue($oObject, $value);
	}

	public function RecurseRmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir.DIRECTORY_SEPARATOR.$object))
						$this->RecurseRmdir($dir.DIRECTORY_SEPARATOR.$object);
					else
						unlink($dir.DIRECTORY_SEPARATOR.$object);
				}
			}
			rmdir($dir);
		}
	}

	public function CreateTmpdir() {
		$sTmpDir=tempnam(sys_get_temp_dir(),'');
		if (file_exists($sTmpDir))
		{
			unlink($sTmpDir);
		}
		mkdir($sTmpDir);
		if (is_dir($sTmpDir))
		{
			return $sTmpDir;
		}

		return sys_get_temp_dir();
	}

	public function RecurseMkdir($sDir){
		if (strpos($sDir, DIRECTORY_SEPARATOR) === 0){
			$sPath = DIRECTORY_SEPARATOR;
		} else {
			$sPath = "";
		}

		foreach (explode(DIRECTORY_SEPARATOR, $sDir) as $sSubDir){
			if (($sSubDir === '..')) {
				break;
			}

			if (( trim($sSubDir) === '' ) || ( $sSubDir === '.' )) {
				continue;
			}

			$sPath .= $sSubDir . DIRECTORY_SEPARATOR;
			if (!is_dir($sPath)) {
				var_dump($sPath);
				@mkdir($sPath);
			}
		}

	}

	public function RecurseCopy($src,$dst) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					$this->RecurseCopy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
				}
				else {
					copy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
		closedir($dir);
	}
}