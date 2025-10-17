<?php

namespace Combodo\iTop\Forms\Dependency;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

class DependencyDescription
{
	private array $aPostSetData = [];

	private array $aPostSubmitData = [];

	private bool $isAdded = false;

	public function __construct(public readonly array $aDependencies, public readonly string|FormBuilderInterface $child, public readonly ?string $type = null, public readonly array $options = [])
	{
	}

	public function IsAdded(): bool
	{
		return $this->isAdded;
	}

	public function SetAdded(bool $bAdded): void
	{
		$this->isAdded = $bAdded;
	}

	public function IsDataReady(string $sEventType): bool
	{
		$aData = ($sEventType === FormEvents::POST_SET_DATA) ? $this->aPostSetData : $this->aPostSubmitData;

		foreach (array_keys($this->aDependencies) as $sInput){
			if(!array_key_exists($sInput, $aData) || $aData[$sInput] == null){
				return false;
			}
		}

		return true;
	}

	public function IsPostSetDataReady(): bool
	{
		foreach ($this->aDependencies as $sData => $sValue) {
			if (!array_key_exists($sData, $this->aPostSetData)) {
				return false;
			}
		}
		return true;
	}

	public function IsPostSubmitDataReady(): bool
	{
		foreach ($this->aDependencies as $sData => $sValue) {
			if (!array_key_exists($sData, $this->aPostSubmitData)) {
				return false;
			}
		}
		return true;
	}

	public function SetData(string $sEventType, string $sData, mixed $oValue): void
	{
		if($oValue === null) return;

		if($sEventType === FormEvents::POST_SET_DATA){
			$this->aPostSetData[$sData] = $oValue;
		}
		else{
			$this->aPostSubmitData[$sData] = $oValue;
		}
	}

	public function GetData(string $sEventType): array
	{
		if($sEventType === FormEvents::POST_SET_DATA){
			return $this->aPostSetData;
		}
		else{
			return $this->aPostSubmitData;
		}
	}

	public function SetPostSetData(string $sInput, mixed $oData): void
	{
		$this->aPostSetData[$sInput] = $oData;
	}

	public function SetPostSubmitData(string $sInput, mixed $oData): void
	{
		$this->aPostSubmitData[$sInput] = $oData;
	}

	public function IsReady(string $sEventType): bool
	{
		$aData = ($sEventType === FormEvents::POST_SET_DATA) ? $this->aPostSetData : $this->aPostSubmitData;

		foreach (array_keys($this->aDependencies) as $sInput){
			if(!array_key_exists($sInput, $aData)){
				return false;
			}
		}

		return true;
	}
}