<?php

namespace Combodo\iTop\Application\UI\Base\Common\Metadata;

use iHtmlAttributesSource;

/**
 * data-xxx attributes class holding, allows to be enriched with more attributes
 *
 * @api @since 3.3.0
 */
final class DataAttributes implements iHtmlAttributesSource
{
	private const NAME_PATTERN = '/^[a-z][a-z0-9\-_.]*$/';

	/** @var array<string, string> */
	private array $aValues = [];

	public function Set(string $sName, string $sValue): self
	{
		$this->aValues[static::NormalizeName($sName)] = $sValue;

		return $this;
	}

	/** @param array<string, string> $aValues */
	public function SetMultiple(array $aValues): self
	{
		foreach ($aValues as $sName => $sValue) {
			$this->Set($sName, $sValue);
		}

		return $this;
	}

	public function Get(string $sName): ?string
	{
		return $this->aValues[static::NormalizeName($sName)] ?? null;
	}

	public function Has(string $sName): bool
	{
		return array_key_exists(static::NormalizeName($sName), $this->aValues);
	}

	public function Remove(string $sName): self
	{
		unset($this->aValues[static::NormalizeName($sName)]);

		return $this;
	}

	/** @return array<string, string> Names without the "data-" prefix */
	public function GetAttributes(): array
	{
		return $this->aValues;
	}

	public function GetHtmlAttributes(): array
	{
		$aAttributes = [];
		foreach ($this->aValues as $sName => $sValue) {
			$aAttributes['data-'.$sName] = $sValue;
		}

		return $aAttributes;
	}

	/** @throws \InvalidArgumentException */
	private static function NormalizeName(string $sName): string
	{
		$sNormalized = strtolower(trim($sName));
		if (str_starts_with($sNormalized, 'data-')) {
			$sNormalized = substr($sNormalized, 5);
		}
		if (preg_match(static::NAME_PATTERN, $sNormalized) !== 1) {
			throw new \InvalidArgumentException(sprintf('Invalid data attribute name "%s"', $sName));
		}

		return $sNormalized;
	}
}
