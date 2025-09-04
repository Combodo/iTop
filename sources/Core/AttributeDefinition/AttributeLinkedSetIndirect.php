<?php

/**
 * Set of objects linked to an object (n-n), and being part of its definition
 *
 * @package     iTopORM
 */
class AttributeLinkedSetIndirect extends AttributeLinkedSet
{
    /**
     * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
     *
     * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
     * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
     *
     * @param string $sCode
     * @param array $aParams
     *
     * @throws \Exception
     * @noinspection SenselessProxyMethodInspection
     */
    public function __construct($sCode, $aParams)
    {
        parent::__construct($sCode, $aParams);
    }

    public static function ListExpectedParams()
    {
        return array_merge(parent::ListExpectedParams(), array("ext_key_to_remote"));
    }

    public function IsIndirect()
    {
        return true;
    }

    public function GetExtKeyToRemote()
    {
        return $this->Get('ext_key_to_remote');
    }

    public function GetEditClass()
    {
        return "LinkedSet";
    }

    public function DuplicatesAllowed()
    {
        return $this->GetOptional("duplicates", false);
    } // The same object may be linked several times... or not...

    public function GetTrackingLevel()
    {
        return $this->GetOptional('tracking_level',
            MetaModel::GetConfig()->Get('tracking_level_linked_set_indirect_default'));
    }

    /**
     * Find the corresponding "link" attribute on the target class, if any
     *
     * @return null | AttributeDefinition
     * @throws \CoreException
     */
    public function GetMirrorLinkAttribute()
    {
        $oRet = null;
        /** @var \AttributeExternalKey $oExtKeyToRemote */
        $oExtKeyToRemote = MetaModel::GetAttributeDef($this->GetLinkedClass(), $this->GetExtKeyToRemote());
        $sRemoteClass = $oExtKeyToRemote->GetTargetClass();
        foreach (MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef) {
            if (!$oRemoteAttDef instanceof AttributeLinkedSetIndirect) {
                continue;
            }
            if ($oRemoteAttDef->GetLinkedClass() != $this->GetLinkedClass()) {
                continue;
            }
            if ($oRemoteAttDef->GetExtKeyToMe() != $this->GetExtKeyToRemote()) {
                continue;
            }
            if ($oRemoteAttDef->GetExtKeyToRemote() != $this->GetExtKeyToMe()) {
                continue;
            }
            $oRet = $oRemoteAttDef;
            break;
        }

        return $oRet;
    }

    /** @inheritDoc */
    public static function IsBulkModifyCompatible(): bool
    {
        return true;
    }

}