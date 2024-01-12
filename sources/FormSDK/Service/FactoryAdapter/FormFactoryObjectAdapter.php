<?php
/*
 * Copyright (C) 2013-2023 Combodo SARL
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

namespace Combodo\iTop\FormSDK\Service\FactoryAdapter;

use AttributeDefinition;
use AttributeString;
use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use DBObject;
use Exception;
use ExceptionLog;
use MetaModel;

/**
 * Form manipulation for DBObject.
 *
 * @package FormSDK
 * @since 3.X.0
 */
final class FormFactoryObjectAdapter implements FormFactoryAdapterInterface
{
	/** @var array list of object attributes */
	private array $aAttributes = [];

	/**
	 * Constructor.
	 *
	 * @param \DBObject $oDBObject
	 * @param bool $bGroup
	 */
	public function __construct(
		private readonly DBObject $oDBObject,
		private readonly bool $bGroup = true
	)
	{

	}

	/**
	 * Add an object attribute.
	 *
	 * @param string $sAttributeCode
	 * @param array $aOptions
	 *
	 * @return $this
	 */
	public function AddAttribute(string $sAttributeCode, array $aOptions = []) : FormFactoryObjectAdapter
	{
		$this->aAttributes[$sAttributeCode] = $aOptions;
		return $this;
	}

	/**
	 * Get attribute data.
	 *
	 * @throws \CoreException
	 * @throws \ArchivedObjectException
	 */
	protected function GetAttributeData(string $sAttributeCode) : mixed
	{
		return $this->oDBObject->Get($sAttributeCode);
	}

	/**
	 * Get attribute form type options.
	 *
	 * @param \AttributeDefinition $oAttributeDefinition
	 *
	 * @return array
	 */
	private function GetAttributeOptions(AttributeDefinition $oAttributeDefinition) : array
	{
		$aOptions = [];

		$sLabel = $oAttributeDefinition->GetLabel();
		if(!$this->bGroup){
			$sLabel = $this->GetLabel() . ' ••• ' . $sLabel;
		}

		if($oAttributeDefinition instanceof AttributeString) {
			$aOptions['required'] = !$oAttributeDefinition->IsNullAllowed();
			$aOptions['label'] = $sLabel;
		}

		return $aOptions;
	}

	/**
	 * Get attribute description.
	 *
	 * @param string $sAttributeCode
	 *
	 * @return \Combodo\iTop\FormSDK\Field\FormFieldDescription|null
	 * @throws \Exception
	 */
	private function GetAttributeDescription(string $sAttributeCode) : ?FormFieldDescription
	{
		$oAttributeDefinition = MetaModel::GetAttributeDef(get_class($this->oDBObject), $sAttributeCode);

		if($oAttributeDefinition instanceof AttributeString) {
			return new FormFieldDescription(
				$this->GetAttributeName($sAttributeCode),
				FormFieldTypeEnumeration::TEXT,
				array_merge(
					$this->GetAttributeOptions($oAttributeDefinition),
					$this->aAttributes[$sAttributeCode])
			);
		}

		return null;
	}

	/**
	 * Return attribute name.
	 *
	 * @param string $sAttributeCode
	 *
	 * @return string
	 */
	private function GetAttributeName(string $sAttributeCode) : string
	{
		return $this->bGroup ? $sAttributeCode : $this->GetIdentifier() . '_' . $sAttributeCode;
	}

	/** @inheritdoc */
	public function GetFieldsData() : array
	{
		$aData = [];
		foreach ($this->aAttributes as $sAttributeCode => $oValue){
			try {
				$aData[$this->GetAttributeName($sAttributeCode)] = $this->GetAttributeData($sAttributeCode);
			}
			catch (Exception $e) {
				$aData[$this->GetAttributeName($sAttributeCode)] = null;
				ExceptionLog::LogException($e);
			}
		}

		if($this->bGroup){
			return [
				$this->GetIdentifier() => $aData
			];
		}
		else{
			return $aData;
		}
	}

	/** @inheritdoc
	 * @throws \Exception
	 */
	public function GetFieldsDescriptions() : array
	{
		$aFieldsDescriptions = [];

		foreach ($this->aAttributes as $sAttCode => $oValue){
			try {
				$aFieldsDescriptions[$this->GetAttributeName($sAttCode)] = $this->GetAttributeDescription($sAttCode);
			}
			catch (Exception $e) {
				ExceptionLog::LogException($e);
			}
		}

		if($this->bGroup){
			$oGroupDescriptions = new FormFieldDescription($this->GetIdentifier(), FormFieldTypeEnumeration::FIELDSET, [
				'fields' => $aFieldsDescriptions,
				'layout' => [
					':row_1' => [
						':column_1' => ['name'],
						':column_2' => ['mobile_phone'],
					],
				]
			]);
			return [$this->GetIdentifier() => $oGroupDescriptions];
		}
		else{
			return $aFieldsDescriptions;
		}
	}

	public function GetLabel(): string
	{
		return get_class($this->oDBObject) . ' ' . $this->oDBObject->GetKey();
	}

	/** @inheritdoc */
	public function GetIdentifier(): string
	{
		return get_class($this->oDBObject) . '_' . $this->oDBObject->GetKey();
	}

	/** @inheritdoc */
	public function UpdateFieldsData(array $aFormData) : bool
	{
		if($this->bGroup){
			$aFormData = $aFormData[$this->GetIdentifier()];
		}

		foreach ($this->aAttributes as $sAttCode => $aValue){
			$this->oDBObject->Set($sAttCode, $aFormData[$this->GetAttributeName($sAttCode)]);
		}

		$this->oDBObject->DBUpdate();

		return true;
	}
}