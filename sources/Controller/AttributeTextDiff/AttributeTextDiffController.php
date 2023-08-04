<?php

namespace Combodo\iTop\Controller\AttributeTextDiff;

use ArchivedObjectException;
use CMDBChangeOp;
use Combodo\iTop\Application\Helper\CMDBChangeHelper;
use Combodo\iTop\Controller\AbstractController;
use CoreException;
use InvalidParameterException;
use MetaModel;
use NiceWebPage;
use utils;

class AttributeTextDiffController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'attributetext_diff';

	/**
	 * @throws InvalidParameterException
	 */
	public function OperationDisplayDiff(): NiceWebPage
	{
		$oPage = new NiceWebPage('AttributeText diff');

		$sChangeOpId = utils::ReadParam('changeop', -1, false, utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);

		$oChangeOp = $this->GetChangeOp($sChangeOpId);
		if (\is_null($oChangeOp)) {
			throw new InvalidParameterException('Cannot load object from changeop param');
		}
		$sDataPrev = $oChangeOp->Get('prevdata');

		$sDataNew = CMDBChangeHelper::GetAttributeNewValueFromChangeOp($oChangeOp);

		$oPage->add('<h1>Diff</h1>');
		$oPage->add('<h2>Previous</h2>');
		$oPage->P($sDataPrev);
		$oPage->add('<h2>New</h2>');
		$oPage->P($sDataNew);

		return $oPage;
	}

	/**
	 * @throws InvalidParameterException If cannot load object from id
	 */
	private function GetChangeOp(string $sChangeOpId): ?CMDBChangeOp
	{
		try {
			/** @var CMDBChangeOp $oChangeOp */
			$oChangeOp = MetaModel::GetObject(CMDBChangeOp::class, $sChangeOpId, false);
		} catch (ArchivedObjectException|CoreException $e) {
			$oChangeOp = null;
		}
		if (\is_null($oChangeOp)) {
			return null;
		}

		return $oChangeOp;
	}
}