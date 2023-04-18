<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Helper;

use AttributeBlob;
use Dict;
use MetaModel;
use utils;

class LegacyFormHelper
{
	/**
	 * DisableAttributeBlobInputs.
	 *
	 * @see N°5863 to allow blob edition in modal context.
	 *
	 * @param string $sClassName Form object class name
	 * @param array $aExtraParams Array extra parameters (to fill)
	 *
	 * @return void
	 * @throws \CoreException
	 */
	static public function DisableAttributeBlobInputs(string $sClassName, array &$aExtraParams)
	{

		// Initialize extra params array
		if (!array_key_exists('fieldsFlags', $aExtraParams)) {
			$aExtraParams['fieldsFlags'] = [];
		}
		if (!array_key_exists('fieldsComments', $aExtraParams)) {
			$aExtraParams['fieldsComments'] = [];
		}

		// Iterate throw class attributes...
		foreach (MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef) {

			// Set attribute blobs in read only
			if ($oAttDef instanceof AttributeBlob) {
				$aExtraParams['fieldsFlags'][$sAttCode] = OPT_ATT_READONLY;
				$aExtraParams['fieldsComments'][$sAttCode] = '&nbsp;<img src="../images/transp-lock.png" style="vertical-align:middle" title="'.utils::EscapeHtml(Dict::S('UI:UploadNotSupportedInThisMode')).'"/>';
			}
		}
	}
}