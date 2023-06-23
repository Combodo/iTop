<?php

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;

use appUserPreferences;
use AttributeDashboard;
use AttributeFriendlyName;
use AttributeLinkedSet;
use cmdbAbstract;
use cmdbAbstractObject;
use Dict;
use Metamodel;

/**
 * Class DataTableSettings
 *
 * @author Anne-Catherine Cognet <anne-catherine.cognet@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\DataTable
 * @since 3.0.0
 */
class DataTableSettings
{
	/**
	 * @var array
	 */
    public $aClassAliases;
	/**
	 * @var null|string
	 */
    public $sTableId;
	/**
	 * @var int
	 */
    public $iDefaultPageSize;
	/**
	 * @var array
	 */
    public $aColumns;
	/**
	 * @var array
	 */
	public $aSortOrder;


    /**
     * DataTableSettings constructor.
     *
     * @param $aClassAliases
     * @param null $sTableId
     */
    public function __construct($aClassAliases, $sTableId = null)
    {
        $this->aClassAliases = $aClassAliases;
        $this->sTableId = $sTableId;
        $this->iDefaultPageSize = 10;
        $this->aColumns = array();
    }

	/**
	 * @return string
	 * @since 3.1.0
	 */
	public function __serialize() {
		return serialize([
			$this->aClassAliases,
			$this->sTableId,
			$this->iDefaultPageSize,
			$this->aColumns
			]);
	}

	/**
	 * @param $data
	 * @since 3.1.0
	 */
	public function __unserialize($data) {
		list(
			$this->aClassAliases,
			$this->sTableId,
			$this->iDefaultPageSize,
			$this->aColumns
			) = unserialize($data);
	}

    /**
     * @param $iDefaultPageSize
     * @param $aSortOrder
     * @param $aColumns
     */
    protected function Init($iDefaultPageSize, $aSortOrder, $aColumns)
    {
        $this->iDefaultPageSize = $iDefaultPageSize;
        $this->aColumns = $aColumns;
        $this->FixVisibleColumns();
    }

    /**
     * @return string
     */
    public function serialize()
    {
        // Save only the 'visible' columns
        $aColumns = array();
        foreach ($this->aClassAliases as $sAlias => $sClass) {
            $aColumns[$sAlias] = array();
            if (isset($this->aColumns[$sAlias])) {
	            foreach ($this->aColumns[$sAlias] as $sAttCode => $aData) {
		            unset($aData['label']); // Don't save the display name
		            unset($aData['alias']); // Don't save the alias (redundant)
		            unset($aData['code']); // Don't save the code (redundant)
		            if ($aData['checked']) {
			            $aColumns[$sAlias][$sAttCode] = $aData;
		            }
	            }
            }
        }
        return serialize(
            array(
                'iDefaultPageSize' => $this->iDefaultPageSize,
                'aColumns' => $aColumns,
            )
        );
    }

    /**
     * @param string $sData
     *
     * @throws \Exception
     */
    public function unserialize($sData)
    {
        $aData = unserialize($sData);
        $this->iDefaultPageSize = $aData['iDefaultPageSize'];
        $this->aColumns = $aData['aColumns'];
        foreach ($this->aClassAliases as $sAlias => $sClass) {
            foreach ($this->aColumns[$sAlias] as $sAttCode => $aData) {
                $aFieldData = false;
                if ($sAttCode == '_key_') {
                    $aFieldData = $this->GetFieldData($sAlias, $sAttCode, null, true /* bChecked */, $aData['sort']);
                } else if (MetaModel::isValidAttCode($sClass, $sAttCode)) {
                    $oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
                    $aFieldData = $this->GetFieldData($sAlias, $sAttCode, $oAttDef, true /* bChecked */, $aData['sort']);
                }

                if ($aFieldData) {
                    $this->aColumns[$sAlias][$sAttCode] = $aFieldData;
                } else {
                    unset($this->aColumns[$sAlias][$sAttCode]);
                }
            }
        }
        $this->FixVisibleColumns();
    }

    /**
     * @param $aClassAliases
     * @param $bViewLink
     * @param $aDefaultLists
     *
     * @return DataTableSettings
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     */
    public static function GetDataModelSettings($aClassAliases, $bViewLink, $aDefaultLists)
    {
        $oSettings = new DataTableSettings($aClassAliases);
        // Retrieve the class specific settings for each class/alias based on the 'list' ZList
        //TODO let the caller pass some other default settings (another Zlist, extre fields...)
        $aColumns = [];
	    $aSortOrder = [];
        foreach ($aClassAliases as $sAlias => $sClass) {
            if ($aDefaultLists == null) {
                $aList = cmdbAbstractObject::FlattenZList(MetaModel::GetZListItems($sClass, 'list'));
            } else {
                $aList = $aDefaultLists[$sAlias];
            }

            $aSortOrder = MetaModel::GetOrderByDefault($sClass);
            if ($bViewLink) {
                $sSort = 'none';
                if (array_key_exists('friendlyname', $aSortOrder)) {
                    $sSort = $aSortOrder['friendlyname'] ? 'asc' : 'desc';
                }
                $sNormalizedFName = MetaModel::NormalizeFieldSpec($sClass, 'friendlyname');
                if (array_key_exists($sNormalizedFName, $aSortOrder)) {
                    $sSort = $aSortOrder[$sNormalizedFName] ? 'asc' : 'desc';
                }
	            $aColumns[$sAlias]['_key_'] = $oSettings->GetFieldData($sAlias, '_key_', null, true /* bChecked */, $sSort);
            }

	        foreach ($aList as $sAttCode) {
                $sSort = 'none';
                if (array_key_exists($sAttCode, $aSortOrder)) {
                    $sSort = $aSortOrder[$sAttCode] ? 'asc' : 'desc';
                }
                $oAttDef = Metamodel::GetAttributeDef($sClass, $sAttCode);
                $aFieldData = $oSettings->GetFieldData($sAlias, $sAttCode, $oAttDef, true /* bChecked */, $sSort);
                if ($aFieldData) $aColumns[$sAlias][$sAttCode] = $aFieldData;
            }
        }
        $iDefaultPageSize = appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());
        $oSettings->Init($iDefaultPageSize, $aSortOrder, $aColumns);
        return $oSettings;
    }

    /**
     * @throws \CoreException
     */
    protected function FixVisibleColumns()
    {
        foreach ($this->aClassAliases as $sAlias => $sClass) {
            if (!isset($this->aColumns[$sAlias])) {
                continue;
            }
            foreach ($this->aColumns[$sAlias] as $sAttCode => $aData) {
                // Remove non-existent columns
                // TODO: check if the existing ones are still valid (in case their type changed)
                if (($sAttCode != '_key_') && (!MetaModel::IsValidAttCode($sClass, $sAttCode))) {
                    unset($this->aColumns[$sAlias][$sAttCode]);
                }
            }
            $aList = MetaModel::ListAttributeDefs($sClass);

            // Add the other (non visible ones), sorted in alphabetical order
            $aTempData = array();
            foreach ($aList as $sAttCode => $oAttDef) {
                if ((!array_key_exists($sAttCode, $this->aColumns[$sAlias])) && (!($oAttDef instanceof AttributeLinkedSet || $oAttDef instanceof AttributeDashboard))) {
                    $aFieldData = $this->GetFieldData($sAlias, $sAttCode, $oAttDef, false /* bChecked */, 'none');
                    if ($aFieldData) $aTempData[$aFieldData['label']] = $aFieldData;
                }
            }
            ksort($aTempData);
            foreach ($aTempData as $sLabel => $aFieldData) {
                $this->aColumns[$sAlias][$aFieldData['code']] = $aFieldData;
            }
        }
    }

    /**
     * @param $aClassAliases
     * @param null $sTableId
     * @param bool $bOnlyOnTable
     *
     * @return DataTableSettings|null
     * @throws \Exception
     */
    static public function GetTableSettings($aClassAliases, $sTableId = null, $bOnlyOnTable = false)
    {
	    $pref = null;
	    $oSettings = new DataTableSettings($aClassAliases, $sTableId);

	    if ($sTableId != null) {
		    // An identified table, let's fetch its own settings (if any)
		    $pref = appUserPreferences::GetPref($oSettings->GetPrefsKey($sTableId), null);
	    }

	    if ($pref == null) {
		    if (!$bOnlyOnTable) {
			    // Try the global preferred values for this class / set of classes
			    $pref = appUserPreferences::GetPref($oSettings->GetPrefsKey(null), null);
		    }
		    if ($pref == null) {
			    // no such settings, use the default values provided by the data model
			    return null;
		    }
	    }
	    $oSettings->unserialize($pref);

	    return $oSettings;
    }

    /**
     * @return array
     */
    public function GetSortOrder()
    {
        $aSortOrder = array();
        foreach ($this->aColumns as $sAlias => $aColumns) {
            foreach ($aColumns as $aColumn) {
                if ($aColumn['sort'] != 'none') {
                    $sCode = ($aColumn['code'] == '_key_') ? 'friendlyname' : $aColumn['code'];
                    $aSortOrder[$sCode] = ($aColumn['sort'] == 'asc'); // true for ascending, false for descending
                }
            }
            break; // TODO: For now the Set object supports only sorting on the first class of the set
        }
        return $aSortOrder;
    }

    /**
     * @param null $sTargetTableId
     *
     * @return bool
     */
    public function Save($sTargetTableId = null)
    {
        $sSaveId = is_null($sTargetTableId) ? $this->sTableId : $sTargetTableId;
        if ($sSaveId == null) return false; // Cannot save, the table is not identified, use SaveAsDefault instead

        $sSettings = $this->serialize();
        appUserPreferences::SetPref($this->GetPrefsKey($sSaveId), $sSettings);
        return true;
    }

    /**
     * @return bool
     */
    public function SaveAsDefault()
    {
        $sSettings = $this->serialize();
        appUserPreferences::SetPref($this->GetPrefsKey(null), $sSettings);
        return true;
    }


    /**
     * Clear the preferences for this particular table
     * @param $bResetAll boolean If true,the settings for all tables of the same class(es)/alias(es) are reset
     */
    public function ResetToDefault($bResetAll)
    {
        if (($this->sTableId == null) && (!$bResetAll)) return false; // Cannot reset, the table is not identified, use force $bResetAll instead
        if ($bResetAll) {
            // Turn the key into a suitable PCRE pattern
            $sKey = $this->GetPrefsKey(null);
            $sPattern = str_replace(array('|'), array('\\|'), $sKey); // escape the | character
            $sPattern = '#^' . str_replace(array('*'), array('.*'), $sPattern) . '$#'; // Don't use slash as the delimiter since it's used in our key to delimit aliases
            appUserPreferences::UnsetPref($sPattern, true);
        } else {
            appUserPreferences::UnsetPref($this->GetPrefsKey($this->sTableId), false);
        }
        return true;
    }

    /**
     * @param null $sTableId
     *
     * @return string
     */
    protected function GetPrefsKey($sTableId = null)
    {
        return static::GetAppUserPreferenceKey($this->aClassAliases, $sTableId);
    }

    public static function GetAppUserPreferenceKey($aClassAliases, $sTableId)
    {
        if ($sTableId === null) {
            $sTableId = '*';
        }

        $aKeys = array();
        foreach ($aClassAliases as $sAlias => $sClass) {
            $aKeys[] = $sAlias . '-' . $sClass;
        }
        return implode('/', $aKeys) . '|' . $sTableId;
    }

    /**
     * @param $sAlias
     * @param $sAttCode
     * @param $oAttDef
     * @param $bChecked
     * @param $sSort
     *
     * @return array|bool
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     */
    protected function GetFieldData($sAlias, $sAttCode, $oAttDef, $bChecked, $sSort)
    {
        $ret = false;
        if ($sAttCode == '_key_') {
            $sLabel = Dict::Format('UI:ExtKey_AsLink', MetaModel::GetName($this->aClassAliases[$sAlias]));
            $ret = array(
                'label' => $sLabel,
                'checked' => $bChecked,
                'disabled' => true,
                'alias' => $sAlias,
                'code' => $sAttCode,
                'sort' => $sSort,
            );
        } else if (!$oAttDef->IsLinkSet()) {
            $sLabel = $oAttDef->GetLabel();
            if ($oAttDef->IsExternalKey()) {
                $sLabel = Dict::Format('UI:ExtKey_AsLink', $oAttDef->GetLabel());
            } else if ($oAttDef->IsExternalField()) {
                if ($oAttDef->IsFriendlyName()) {
                    $sLabel = Dict::Format('UI:ExtKey_AsFriendlyName', $oAttDef->GetLabel());
                } else {
                    $oExtAttDef = $oAttDef->GetExtAttDef();
                    $sLabel = Dict::Format('UI:ExtField_AsRemoteField', $oAttDef->GetLabel(), $oExtAttDef->GetLabel());
                }
            } elseif ($oAttDef instanceof AttributeFriendlyName) {
                $sLabel = Dict::Format('UI:ExtKey_AsFriendlyName', $oAttDef->GetLabel());
            }
            $ret = array(
                'label' => $sLabel,
                'checked' => $bChecked,
                'disabled' => false,
                'alias' => $sAlias,
                'code' => $sAttCode,
                'sort' => $sSort,
            );
        }
        return $ret;
    }
}