<?php

/**
 * Map a text column (size < 255) to an attribute that is encrypted in the database
 * The encryption is based on a key set per iTop instance. Thus if you export your
 * database (in SQL) to someone else without providing the key at the same time
 * the encrypted fields will remain encrypted
 *
 * @package     iTopORM
 */
class AttributeEncryptedString extends AttributeString implements iAttributeNoGroupBy
{
    const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

    protected function GetSQLCol($bFullSpec = false)
    {
        return "TINYBLOB";
    }

    public function GetMaxSize()
    {
        return 255;
    }

    public function MakeRealValue($proposedValue, $oHostObj)
    {
        if (is_null($proposedValue)) {
            return null;
        }

        return (string)$proposedValue;
    }

    /**
     * Decrypt the value when reading from the database
     *
     * @param array $aCols
     * @param string $sPrefix
     *
     * @return string
     * @throws \Exception
     */
    public function FromSQLToValue($aCols, $sPrefix = '')
    {
        $oSimpleCrypt = new SimpleCrypt(MetaModel::GetConfig()->GetEncryptionLibrary());
        $sValue = $oSimpleCrypt->Decrypt(MetaModel::GetConfig()->GetEncryptionKey(), $aCols[$sPrefix]);

        return $sValue;
    }

    /**
     * Encrypt the value before storing it in the database
     *
     * @param $value
     *
     * @return array
     * @throws \Exception
     */
    public function GetSQLValues($value)
    {
        $oSimpleCrypt = new SimpleCrypt(MetaModel::GetConfig()->GetEncryptionLibrary());
        $encryptedValue = $oSimpleCrypt->Encrypt(MetaModel::GetConfig()->GetEncryptionKey(), $value);

        $aValues = array();
        $aValues[$this->Get("sql")] = $encryptedValue;

        return $aValues;
    }

    protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
    {
        if (is_null($original)) {
            $original = '';
        }
        $oMyChangeOp->Set("prevstring", $original);
    }

    protected function GetChangeRecordClassName(): string
    {
        return CMDBChangeOpSetAttributeEncrypted::class;
    }


}