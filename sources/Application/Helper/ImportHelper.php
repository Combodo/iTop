<?php

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\UI\Base\Component\Input\Select\Select;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\SelectUIBlockFactory;
use CoreException;
use Dict;
use DictExceptionMissingString;
use MetaModel;
use UserRights;

/**
 * Class
 * ImportHelper
 *
 * @internal
 * @since 3.2.3
 * @package Combodo\iTop\Application\Helper
 */
class ImportHelper
{
	/**
	 * Get classes select UI block.
	 *
	 * @param string $sName
	 * @param $sDefaultValue
	 * @param int $iActionCode
	 * @param bool $bAdvanced
	 *
	 * @return Select
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 */
	public static function GetClassesSelectUIBlock(string $sName, $sDefaultValue, int $iActionCode, bool $bAdvanced = false): Select
	{
		$oSelectBlock = SelectUIBlockFactory::MakeForSelect($sName, 'select_'.$sName);
		$oOption = SelectOptionUIBlockFactory::MakeForSelectOption("", Dict::S('UI:CSVImport:ClassesSelectOne'), false);
		$oSelectBlock->AddSubBlock($oOption);
		$aValidClasses = [];
		$aClassCategories = ['bizmodel', 'addon/authentication'];
		if ($bAdvanced) {
			$aClassCategories[] = 'grant_by_profile';
		}
		if (UserRights::IsAdministrator()) {
			$aClassCategories[] = 'application';
		}
		foreach ($aClassCategories as $sClassCategory) {
			foreach (MetaModel::GetClasses($sClassCategory) as $sClassName) {
				if ((is_null($iActionCode) || UserRights::IsActionAllowed($sClassName, $iActionCode)) &&
					(!MetaModel::IsAbstract($sClassName))) {
					$sDisplayName = ($bAdvanced) ? MetaModel::GetName($sClassName)." ($sClassName)" : MetaModel::GetName($sClassName);
					$aValidClasses[$sDisplayName] = SelectOptionUIBlockFactory::MakeForSelectOption($sClassName, $sDisplayName, ($sClassName == $sDefaultValue));
				}
			}
		}
		ksort($aValidClasses);
		foreach ($aValidClasses as $sValue => $oBlock) {
			$oSelectBlock->AddSubBlock($oBlock);
		}

		return $oSelectBlock;
	}
}
