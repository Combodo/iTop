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

namespace Combodo\iTop\Application\UI\Links\Set;

use AttributeLinkedSet;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\Set;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\SetUIBlockFactory;
use Combodo\iTop\Service\Links\LinksBulkDataPostProcessor;
use Combodo\iTop\Service\Links\LinkSetDataTransformer;
use Combodo\iTop\Service\Links\LinkSetModel;
use Combodo\iTop\Service\Links\LinkSetRepository;
use DBObject;
use iDBObjectSetIterator;

/**
 * Class LinkSetUIBlockFactory
 *
 * @api
 *
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Set
 */
class LinkSetUIBlockFactory extends SetUIBlockFactory
{

	/**
	 * Make a link set block.
	 *
	 * @param string $sId Block identifier
	 * @param AttributeLinkedSet $oAttDef Link set attribute definition
	 * @param iDBObjectSetIterator $oDbObjectSet Link set value
	 * @param string $sWizardHelperJsVarName Wizard helper name
	 * @param DBObject|null $oHostDbObject Host DB object
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Set\Set
	 */
	public static function MakeForLinkSet(string $sId, AttributeLinkedSet $oAttDef, iDBObjectSetIterator $oDbObjectSet, string $sWizardHelperJsVarName, DBObject $oHostDbObject = null): Set
	{
		$sTargetClass = LinkSetModel::GetTargetClass($oAttDef);
		$sTargetField = LinkSetModel::GetTargetField($oAttDef);

		// Set UI block for OQL
		$oSetUIBlock = SetUIBlockFactory::MakeForOQL($sId, $sTargetClass, $oAttDef->GetValuesDef()->GetFilterExpression(), $sWizardHelperJsVarName);

		$oSetUIBlock->AddJsFileRelPath('js/links/linkset.js');

		// Add button behaviour
		if (LinkSetModel::IsRemoteCreationAllowed($oAttDef) && $oHostDbObject !== null) {
			$oSetUIBlock->SetHasAddOptionButton(true);
			$oSetUIBlock->SetAddOptionButtonJsOnClick("iTopLinkSet.CreateLinkedObject('{$sTargetClass}', oWidget{$oSetUIBlock->GetId()} );");
		}

		// Current value
		$aCurrentValues = LinkSetDataTransformer::Decode($oDbObjectSet, $sTargetClass, $sTargetField);
		// Some operations can have been done in case of reload after an error
		$aInitialValues = LinkSetDataTransformer::Decode($oDbObjectSet->GetOriginalSet(), $sTargetClass, $sTargetField);

		// Initial options data
		$aInitialOptions = [];
		LinkSetRepository::LinksDbSetToTargetObjectArray($oDbObjectSet, false, $aInitialOptions, $sTargetClass, $sTargetField);
		// Register also original values in case of reload after an error. In order to remember the operations, use the "bForce" flag
		LinkSetRepository::LinksDbSetToTargetObjectArray($oDbObjectSet->GetOriginalSet(), true, $aInitialOptions, $sTargetClass, $sTargetField);
		if ($aInitialOptions !== null) {
			$oSetUIBlock->GetDataProvider()->SetOptions(array_values($aInitialOptions));
			// Set value
			$oSetUIBlock->SetValue(json_encode($aCurrentValues));
			$oSetUIBlock->SetInitialValue(json_encode(array_merge($aInitialValues, $aCurrentValues)));
		} else {
			$oSetUIBlock->SetHasError(true);
		}

		return $oSetUIBlock;
	}

	/**
	 * Make a link set block for bulk modify.
	 *
	 * @param string $sId Block identifier
	 * @param AttributeLinkedSet $oAttDef Link set attribute definition
	 * @param iDBObjectSetIterator $oDbObjectSet Link set value
	 * @param string $sWizardHelperJsVarName Wizard helper name
	 * @param array $aBulkContext
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Set\Set
	 */
	public static function MakeForBulkLinkSet(string $sId, AttributeLinkedSet $oAttDef, iDBObjectSetIterator $oDbObjectSet, string $sWizardHelperJsVarName, array $aBulkContext): Set
	{
		$oSetUIBlock = self::MakeForLinkSet($sId, $oAttDef, $oDbObjectSet, $sWizardHelperJsVarName);

		// Bulk modify specific
		$oSetUIBlock->GetDataProvider()->SetGroupField('group');
		$oSetUIBlock->SetIsMultiValuesSynthesis(true);

		// Data post processing
		$aBinderSettings = [
			'bulk_oql'     => $aBulkContext['oql'],
			'link_class'   => LinkSetModel::GetLinkedClass($oAttDef),
			'target_field' => LinkSetModel::GetTargetField($oAttDef),
			'origin_field' => $oAttDef->GetExtKeyToMe(),
		];

		// Initial options
		$aOptions = $oSetUIBlock->GetDataProvider()->GetOptions();
		$aOptions = LinksBulkDataPostProcessor::Execute($aOptions, $aBinderSettings);
		$oSetUIBlock->GetDataProvider()->SetOptions($aOptions);

		// Data provider post processor
		/** @var \Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\AjaxDataProvider $oDataProvider */
		$oDataProvider = $oSetUIBlock->GetDataProvider();
		$oDataProvider->SetPostParam('data_post_processor', [
			'class_name' => addslashes(LinksBulkDataPostProcessor::class),
			'settings'   => $aBinderSettings,
		]);

		return $oSetUIBlock;
	}
}