<?php

/**
 * Data model classes
 *
 * @package     iTopORM
 */
class ValueSetEnumClasses extends ValueSetEnum
{
    protected $m_sCategories;

    public function __construct($sCategories = '', $sAdditionalValues = '')
    {
        $this->m_sCategories = $sCategories;
        parent::__construct($sAdditionalValues, true /* Classes are always sorted alphabetically */);
    }

    protected function LoadValues($aArgs)
    {
        // Call the parent to parse the additional values...
        parent::LoadValues($aArgs);

        // Translate the labels of the additional values
        foreach ($this->m_aValues as $sClass => $void) {
            if (MetaModel::IsValidClass($sClass)) {
                $this->m_aValues[$sClass] = MetaModel::GetName($sClass);
            } else {
                unset($this->m_aValues[$sClass]);
            }
        }

        // Then, add the classes from the category definition
        foreach (MetaModel::GetClasses($this->m_sCategories) as $sClass) {
            if (MetaModel::IsValidClass($sClass)) {
                $this->m_aValues[$sClass] = MetaModel::GetName($sClass);
            } else {
                unset($this->m_aValues[$sClass]);
            }
        }

        return true;
    }
}