<?php

namespace Combodo\iTop\FormSDK\Service;

use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use Combodo\iTop\FormSDK\Service\FormFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Regex;

trait FormFactoryBuilderTrait
{

	/**
	 * Add text field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddTextField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		// test widget for regex constraint
		if(array_key_exists('constraints', $aOptions)){
			$oConstraint = $aOptions['constraints'];
			if($oConstraint instanceof Regex){
				$aWidgetOptions = [
					'pattern' => $oConstraint->pattern,
				];
				$aOptions = array_merge([
					'attr' => [
						'data-widget' => 'TextWidget',
						'data-pattern' => $oConstraint->pattern,
						'data-widget-options' => json_encode($aWidgetOptions)
					]
				], $aOptions);
			}
		}

		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::TEXT, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}

	/**
	 * Add area field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddAreaField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		$aOptions = array_merge([
			'attr' => [
				'data-widget' => 'AreaWidget',
				'data-widget-options' => json_encode([])
			]
		], $aOptions);

		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::AREA, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}

	/**
	 * Add date field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddDateField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::DATE, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}


	/**
	 * Add select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddSelectField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::SELECT, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}

	/**
	 * Add dynamic ajax select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param array $aAjaxOptions
	 * @param array $aAjaxData
	 * @param mixed $oData
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 */
	public function AddSelectAjaxField(string $sKey, array $aOptions, array $aAjaxOptions, array $aAjaxData = [], mixed $oData = null) : FormFactory
	{
		// merge ajax options
		$aAjaxOptions = array_merge([
			'url' => '',
			'query_parameter' => 'query',
			'value_field' => 'value',
			'label_field' => 'label',
			'search_field' => 'search',
			'preload' => false,
			'threshold' => -1,
			'configuration' => 'AJAX'
		], $aAjaxOptions);

		// merge options
		$aOptions = array_merge([
			'placeholder' => 'Select...',
			'attr' => [
				'data-widget' => 'SelectWidget',
				'data-ajax-query-type' => $aAjaxOptions['configuration'],
				'data-widget-options' => json_encode($aAjaxOptions)
			],
			//			'choice_loader' => new CallbackChoiceLoader(function() use ($aAjaxOptions, $aAjaxData): array {
			//				$curl_data = utils::DoPostRequest($aAjaxOptions['url'], []);
			//				$response_data = json_decode($curl_data);
			//				if(count($response_data->items) > $aAjaxOptions['threshold']) return [];
			//				$result = [];
			//				foreach ($response_data->items as $e) {
			//					$result[$e->breed] = $e->breed;
			//				}
			//				return $result;
			//			}),
		], $aOptions);

		return $this->AddSelectField($sKey, $aOptions, $oData);
	}


	/**
	 * Add dynamic OQL select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param string $sObjectClass
	 * @param string $sOql
	 * @param array $aFieldsToLoad
	 * @param string $sSearch
	 * @param int $iAjaxThershold
	 * @param mixed $oData
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 */
	public function AddSelectOqlField(string $sKey, array $aOptions, string $sObjectClass, string $sOql, array $aFieldsToLoad, string $sSearch, int $iAjaxThershold, mixed $oData = null) : FormFactory
	{
		$aAjaxData = [
			'class' => $sObjectClass,
			'oql' => $sOql,
			'fields' => '{'.implode($aFieldsToLoad).'}',
		];
		$sUrl = 'http://localhost' . $this->oRouter->generate('formSDK_object_search') . '?' . http_build_query($aAjaxData);
		$aAjaxOptions = [
			'url' => $sUrl,
			'query_parameter' => 'search',
			'value_field' => 'key',
			'label_field' => 'friendlyname',
			'search_field' => 'friendlyname',
			'threshold' => $iAjaxThershold,
			'configuration' => 'OQL'
		];
		return $this->AddSelectAjaxField($sKey, $aOptions, $aAjaxOptions, $aAjaxData, $oData);
	}

	/**
	 * Add switch field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddSwitchField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		$aOptions = array_merge([
			'label_attr' => ['class' => 'checkbox-switch'],
		], $aOptions);

		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::SWITCH, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}
}