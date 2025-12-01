<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Register;

/**
 * Option register.
 *
 * @package Combodo\iTop\Forms\Register
 * @since 3.3.0
 */
class OptionsRegister
{
	/** @var Option[] options used for Symfony type creation */
	private array $aOptions = [];

	/**
	 * Set an option.
	 *
	 * @param string $sOptionName
	 * @param mixed $mDefaultValue
	 * @param bool $bTypeOption
	 *
	 * @return void
	 * @throws RegisterException
	 */
	public function SetOption(string $sOptionName, mixed $mDefaultValue = null, bool $bTypeOption = true): void
	{
		$this->VerifyOptionName($sOptionName);

		if (isset($this->aOptions[$sOptionName])) {
			$this->aOptions[$sOptionName]->oValue = $mDefaultValue;
		} else {
			$this->aOptions[$sOptionName] = new Option($sOptionName, $mDefaultValue, $bTypeOption);
		}
	}

	/**
	 * @param string $sOptionName
	 *
	 * @return void
	 * @throws RegisterException
	 */
	private function VerifyOptionName(string $sOptionName): void
	{
		if (!ctype_alnum(str_replace(['-', '_'], '', $sOptionName))) {
			throw new RegisterException("Option name '$sOptionName' is not valid. Only alphanumeric characters, hyphens and underscores are allowed.");
		}
	}

	/**
	 * Set an option array value.
	 *
	 * @param string $sOptionName
	 * @param string $sArrayKey
	 * @param mixed $mDefaultValue
	 *
	 * @return void
	 */
	public function SetOptionArrayValue(string $sOptionName, string $sArrayKey, mixed $mDefaultValue = null): void
	{
		// Initialization of the option as an array if not set
		if (!isset($this->aOptions[$sOptionName])) {
			$this->SetOption($sOptionName, []);
		}

		$this->aOptions[$sOptionName]->oValue[$sArrayKey] = $mDefaultValue;
	}

	/**
	 * Get all options.
	 *
	 * @return array
	 */
	public function GetOptions(): array
	{
		$aOptions = array_filter($this->aOptions, fn ($oElement) => $oElement->bIsTypeOption);
		return array_map(fn ($oElement) => $oElement->oValue, $aOptions);
	}

	/**
	 * Get a type option.
	 *
	 * @param string $sOption
	 *
	 * @return mixed
	 */
	public function GetOption(string $sOption): mixed
	{
		return $this->aOptions[$sOption]->oValue;
	}

	/**
	 * Check if an option exists.
	 *
	 * @param string $sOption
	 *
	 * @return bool
	 */
	public function HasOption(string $sOption): bool
	{
		return array_key_exists($sOption, $this->aOptions);
	}
}
