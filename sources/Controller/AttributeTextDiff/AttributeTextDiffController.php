<?php

namespace Combodo\iTop\Controller\AttributeTextDiff;

use Combodo\iTop\Controller\AbstractController;
use NiceWebPage;

class AttributeTextDiffController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'attributetext_diff';

	public function OperationDisplayDiff(): NiceWebPage
	{
		$oPage = new NiceWebPage('AttributeText diff');

		$oPage->add('Hello world !');

		return $oPage;
	}
}