<?php

namespace Combodo\iTop\Application\UI\Base\Common\Metadata;

use iHtmlAttributesSource;

/**
 * WAI-ARIA attributes class holding, allows to be enriched with more attributes
 *
 * @api @since 3.3.0
 */
final class AriaAttributes implements iHtmlAttributesSource
{
	private ?string $sLabel = null;

	public function SetLabel(?string $sLabel): self
	{
		$this->sLabel = $sLabel;

		return $this;
	}

	public function GetLabel(): ?string
	{
		return $this->sLabel;
	}

	/**
	 * Set several attributes at once, by ARIA attribute name ("label", "description", ...)
	 *
	 * @param array<string, mixed> $aAttributes
	 * @since 3.3.0
	 */
	public function SetMultiple(array $aAttributes): self
	{
		foreach ($aAttributes as $sName => $sValue) {
			$sMethod = 'Set'.$sName;
			if ($sMethod !== 'SetMultiple' && method_exists($this, $sMethod)) {
				$this->$sMethod($sValue);
			}
		}

		return $this;
	}

	public function GetAttributes(): array
	{
		$aAttributes = [];
		if ($this->sLabel !== null) {
			$aAttributes['label'] = $this->sLabel;
		}

		return $aAttributes;
	}

	public function GetHtmlAttributes(): array
	{
		$aAttributes = [];
		if ($this->sLabel !== null) {
			$aAttributes['aria-label'] = $this->sLabel;
		}

		return $aAttributes;
	}
}
