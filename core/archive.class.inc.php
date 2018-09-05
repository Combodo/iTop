<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Utility to import/export the DB from/to a ZIP file
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * iTopArchive a class to manipulate (read/write) iTop archives with their catalog
 * Each iTop archive is a zip file that contains (at the root of the archive)
 * a file called catalog.xml holding the description of the archive
 */
class iTopArchive
{
	const read = 0;
	const create = ZipArchive::CREATE;
	
	protected $m_sZipPath;
	protected $m_oZip;
	protected $m_sVersion;
	protected $m_sTitle;
	protected $m_sDescription;
	protected $m_aPackages;
	protected $m_aErrorMessages;

	/**
	 * Construct an iTopArchive object
	 * @param $sArchivePath string The full path the archive file
	 * @param $iMode integrer Either iTopArchive::read for reading an existing archive or iTopArchive::create for creating a new one. Updating is not supported (yet)
	 */
	public function __construct($sArchivePath, $iMode = iTopArchive::read)
	{
		$this->m_sZipPath = $sArchivePath;
		$this->m_oZip = new ZipArchive();
		$this->m_oZip->open($this->m_sZipPath, $iMode);
		$this->m_aErrorMessages = array();
		$this->m_sVersion = '1.0';
		$this->m_sTitle = '';
		$this->m_sDescription = '';
		$this->m_aPackages = array();
	}

	public function SetTitle($sTitle)
	{
		$this->m_sTitle = $sTitle;
	}
	
	public function SetDescription($sDescription)
	{
		$this->m_sDescription = $sDescription;
	}
	
	public function GetTitle()
	{
		return $this->m_sTitle;
	}
	
	public function GetDescription()
	{
		return $this->m_sDescription;
	}
	
	public function GetPackages()
	{
		return $this->m_aPackages;
	}
	
	public function __destruct()
	{
		$this->m_oZip->close();
	}
	
	/**
	 * Get the error message explaining the latest error encountered
	 * @return array All the error messages encountered during the validation
	 */
	public function GetErrors()
	{
		return $this->m_aErrorMessages;
	}
	
	/**
	 * Read the catalog from the archive (zip) file
	 * @param sPath string Path the the zip file
	 * @return boolean True in case of success, false otherwise
	 */
	public function ReadCatalog()
	{
		if ($this->IsValid())
		{
			$sXmlCatalog = $this->m_oZip->getFromName('catalog.xml');
			$oParser = xml_parser_create();
			xml_parse_into_struct($oParser, $sXmlCatalog, $aValues, $aIndexes);
			xml_parser_free($oParser);
			
			$iIndex = $aIndexes['ARCHIVE'][0];
			$this->m_sVersion = $aValues[$iIndex]['attributes']['VERSION'];
			$iIndex = $aIndexes['TITLE'][0];
			$this->m_sTitle = $aValues[$iIndex]['value'];
			$iIndex = $aIndexes['DESCRIPTION'][0];
			if (array_key_exists('value', $aValues[$iIndex]))
			{
				// #@# implement a get_array_value(array, key, default) ?
				$this->m_sDescription = $aValues[$iIndex]['value'];
			}
			
			foreach($aIndexes['PACKAGE'] as $iIndex)
			{
				$this->m_aPackages[$aValues[$iIndex]['attributes']['HREF']] = array( 'type' => $aValues[$iIndex]['attributes']['TYPE'], 'title'=> $aValues[$iIndex]['attributes']['TITLE'], 'description' => $aValues[$iIndex]['value']);
			}
			
			//echo "Archive path: {$this->m_sZipPath}<br/>\n";
			//echo "Archive format version: {$this->m_sVersion}<br/>\n";
			//echo "Title: {$this->m_sTitle}<br/>\n";
			//echo "Description: {$this->m_sDescription}<br/>\n";
			//foreach($this->m_aPackages as $aFile)
			//{
			//	echo "{$aFile['title']} ({$aFile['type']}): {$aFile['description']}<br/>\n";
			//}
		}
		return true;
	}
	
	public function WriteCatalog()
	{
		$sXml  = "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n"; // split the XML closing tag that disturbs PSPad's syntax coloring
		$sXml .= "<archive version=\"1.0\">\n";
		$sXml .= "<title>{$this->m_sTitle}</title>\n";
		$sXml .= "<description>{$this->m_sDescription}</description>\n";
		foreach($this->m_aPackages as $sFileName => $aFile)
		{
			$sXml .= "<package title=\"{$aFile['title']}\" type=\"{$aFile['type']}\" href=\"$sFileName\">{$aFile['description']}</package>\n";
		}
		$sXml .= "</archive>";
		$this->m_oZip->addFromString('catalog.xml', $sXml);
	}
	
   /**
	* Add a package to the archive
	* @param string $sExternalFilePath The path to the file to be added to the archive as a package (directories are not yet implemented)
	* @param string $sFilePath The name of the file inside the archive
	* @param string $sTitle A short title for this package
	* @param string $sType Type of the package. SQL scripts must be of type 'text/sql'
	* @param string $sDescription A longer description of the purpose of this package
	* @return none
	*/
	public function AddPackage($sExternalFilePath, $sFilePath, $sTitle, $sType, $sDescription)
	{
		$this->m_aPackages[$sFilePath] = array('title' => $sTitle, 'type' => $sType, 'description' => $sDescription);
		$this->m_oZip->addFile($sExternalFilePath, $sFilePath);
	}
	 
   /**
	* Reads the contents of the given file from the archive
	* @param string $sFileName The path to the file inside the archive
	* @return string The content of the file read from the archive
	*/
	public function GetFileContents($sFileName)
	{
		return $this->m_oZip->getFromName($sFileName);
	}
	 
   /**
	* Extracts the contents of the given file from the archive
	* @param string $sFileName The path to the file inside the archive
	* @param string $sDestinationFileName The path of the file to write
	* @return none
	*/
	 public function ExtractToFile($sFileName, $sDestinationFileName)
	 {
	 	$iBufferSize = 64 * 1024; // Read 64K at a time
		$oZipStream = $this->m_oZip->getStream($sFileName);
		$oDestinationStream = fopen($sDestinationFileName, 'wb');
		while (!feof($oZipStream)) {
			$sContents = fread($oZipStream, $iBufferSize);
			fwrite($oDestinationStream, $sContents);
		}
		fclose($oZipStream);
		fclose($oDestinationStream);
	 }
	 
	 /**
	  * Apply a SQL script taken from the archive. The package must be listed in the catalog and of type text/sql
	  * @param string $sFileName The path to the SQL package inside the archive
	  * @return boolean false in case of error, true otherwise
	  */
	 public function ImportSql($sFileName, $sDatabase = 'itop')
	 {
		if ( ($this->m_oZip->locateName($sFileName) == false) || (!isset($this->m_aPackages[$sFileName])) || ($this->m_aPackages[$sFileName]['type'] != 'text/sql'))
		{
			// invalid type or not listed in the catalog
			return false;
		}
		$sTempName = tempnam("../tmp/", "sql");
		//echo "Extracting to: '$sTempName'<br/>\n";
		$this->ExtractToFile($sFileName, $sTempName);
		// Note: the command line below works on Windows with the right path to mysql !!!
		$sCommandLine = 'type "'.$sTempName.'" | "/iTop/MySQL Server 5.0/bin/mysql.exe" -u root '.$sDatabase;
		//echo "Executing: '$sCommandLine'<br/>\n";
		exec($sCommandLine, $aOutput, $iRet);
		//echo "Return code: $iRet<br/>\n";
		//echo "Output:<br/><pre>\n";
		//print_r($aOutput);
		//echo "</pre><br/>\n";
		unlink($sTempName);
		return ($iRet == 0);
	 }
	 
	 /**
	  * Dumps some part of the specified MySQL database into the archive as a text/sql package
	  * @param $sTitle string A short title for this SQL script
	  * @param $sDescription string A longer description of the purpose of this SQL script
	  * @param $sFileName string The name of the package inside the archive
	  * @param $sDatabase string name of the database
	  * @param $aTables array array or table names. If empty, all tables are dumped
	  * @param $bStructureOnly boolean Whether or not to dump the data or just the schema
	  * @return boolean False in case of error, true otherwise
	  */
	 public function AddDatabaseDump($sTitle, $sDescription, $sFileName, $sDatabase = 'itop', $aTables = array(), $bStructureOnly = true)
	 {
		$sTempName = tempnam("../tmp/", "sql");
		$sNoData = $bStructureOnly ? "--no-data" : "";
		$sCommandLine = "\"/iTop/MySQL Server 5.0/bin/mysqldump.exe\" --user=root --opt $sNoData --result-file=$sTempName $sDatabase ".implode(" ", $aTables);
		//echo "Executing command: '$sCommandLine'<br/>\n";
		exec($sCommandLine, $aOutput, $iRet);
		//echo "Return code: $iRet<br/>\n";
		//echo "Output:<br/><pre>\n";
		//print_r($aOutput);
		//echo "</pre><br/>\n";
		if ($iRet == 0)
		{
			$this->AddPackage($sTempName, $sFileName, $sTitle, 'text/sql', $sDescription);
		}
		//unlink($sTempName);
		return ($iRet == 0);
	 }

	/**
	 * Check the consistency of the archive
	 * @return boolean True if the archive file is consistent
	 */
	 public function IsValid()
	 {
	 	// TO DO: use a DTD to validate the XML instead of this hand-made validation
	 	$bResult = true;
		$aMandatoryTags = array('ARCHIVE' => array('VERSION'),
								'TITLE' => array(),
								'DESCRIPTION' => array(),
								'PACKAGE'  => array('TYPE', 'HREF', 'TITLE'));
		
		$sXmlCatalog = $this->m_oZip->getFromName('catalog.xml');
		$oParser = xml_parser_create();
		xml_parse_into_struct($oParser, $sXmlCatalog, $aValues, $aIndexes);
		xml_parser_free($oParser);
		
		foreach($aMandatoryTags as $sTag => $aAttributes)
		{
			// Check that all the required tags are present
			if (!isset($aIndexes[$sTag]))
			{
				$this->m_aErrorMessages[] = "The XML catalog does not contain the mandatory tag $sTag.";
				$bResult = false; 
			}
			else
			{
				foreach($aIndexes[$sTag] as $iIndex)
				{
					switch($aValues[$iIndex]['type'])
					{
						case 'complete':
						case 'open':
							// Check that all the required attributes are present
							foreach($aAttributes as $sAttribute)
							{
								if (!isset($aValues[$iIndex]['attributes'][$sAttribute]))
								{
									$this->m_aErrorMessages[] = "The tag $sTag ($iIndex) does not contain the required attribute $sAttribute.";
								}
							}
						break;
						
						default:
							// ignore other type of tags: close or cdata
					}
				}
			}
		}
		return $bResult;
	 }
}
/*
// Unit test - reading an archive
$sArchivePath = '../tmp/archive.zip';
$oArchive = new iTopArchive($sArchivePath, iTopArchive::read);
$oArchive->ReadCatalog();
$oArchive->ImportSql('full_backup.sql');

// Writing an archive --

$sArchivePath = '../tmp/archive2.zip';
$oArchive = new iTopArchive($sArchivePath, iTopArchive::create);
$oArchive->SetTitle('First Archive !');
$oArchive->SetDescription('This is just a test. Does not contain a lot of useful data.');
$oArchive->AddPackage('../tmp/schema.sql', 'test.sql', 'this is just a test', 'text/sql', 'My first attempt at creating an archive from PHP...');
$oArchive->WriteCatalog();


$sArchivePath = '../tmp/archive2.zip';
$oArchive = new iTopArchive($sArchivePath, iTopArchive::create);
$oArchive->SetTitle('First Archive !');
$oArchive->SetDescription('This is just a test. Does not contain a lot of useful data.');
$oArchive->AddDatabaseDump('Test', 'This is my first automatic dump', 'schema.sql', 'itop', array('objects'));
$oArchive->WriteCatalog();
*/
?>
