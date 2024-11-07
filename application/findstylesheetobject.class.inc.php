<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

/**
 * Class FindStylesheetObject: dedicated class to store computations made in method ThemeHandler::FindStylesheetFile.
 * @author Olivier DAIN <olivier.dain@combodo.com>
 * @since 3.0.0 N°3588
 */
class FindStylesheetObject{

	//file URIs
	private $aStylesheetFileURIs;

	//fill paths
	private $aStylesheetImportPaths;
	private $aAllStylesheetFilePaths;
	private $sLastStyleSheetPath;

	private $iLastModified;

	/**
	 * FindStylesheetObject constructor.
	 */
	public function __construct()
	{
		$this->aStylesheetFileURIs = [];
		$this->aStylesheetImportPaths = [];
		$this->aAllStylesheetFilePaths = [];
		$this->sLastStyleSheetPath = "";
		$this->iLastModified = 0;
	}

	public function GetLastStylesheetFile(): string
	{
		return $this->sLastStyleSheetPath;
	}

	public function GetImportPaths(): array
	{
		return $this->aStylesheetImportPaths;
	}

	/**
	 * @return array : main stylesheets URIs
	 */
	public function GetStylesheetFileURIs(): array
	{
		return $this->aStylesheetFileURIs;
	}

	public function GetLastModified() : int
	{
		return $this->iLastModified;
	}

	/**
	 * @return array : main stylesheets paths + included files paths
	 */
	public function GetAllStylesheetPaths(): array
	{
		return $this->aAllStylesheetFilePaths;
	}

	/**
	 * @return string : last found stylesheet URI
	 */
	public function GetLastStyleSheetPath(): string
	{
		return $this->sLastStyleSheetPath;
	}

	public function AddStylesheet(string $sStylesheetFileURI, string $sStylesheetFilePath): void
	{
		$this->aStylesheetFileURIs[] = $sStylesheetFileURI;
		$this->aAllStylesheetFilePaths[] = $sStylesheetFilePath;
		$this->sLastStyleSheetPath = $sStylesheetFilePath;
	}

	public function AlreadyFetched(string $sStylesheetFilePath) : bool {
		return in_array($sStylesheetFilePath, $this->aAllStylesheetFilePaths);
	}

	public function AddImport(string $sStylesheetFileURI, string $sStylesheetFilePath): void
	{
		$this->aStylesheetImportPaths[$sStylesheetFileURI] = $sStylesheetFilePath;
		$this->aAllStylesheetFilePaths[] = $sStylesheetFilePath;
	}

	public function UpdateLastModified(string $sStylesheetFile): void
	{
		$this->iLastModified = max($this->iLastModified, @filemtime($sStylesheetFile));
	}

	public function ResetLastStyleSheet(): void
	{
		$this->sLastStyleSheetPath = "";
	}
}