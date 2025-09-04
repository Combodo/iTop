<?php

/**
 * Class ItopCounter
 *
 * A simple counter stored in the DB.
 * It is used to generate unique keys for classes that do not have an autoincrement key
 */
final class ItopCounter
{

    /**
     * Key based counter.
     * The counter is protected against concurrency script.
     *
     * @param $sCounterName
     * @param null|callable $oNewObjectValueProvider optional callable that must return an integer. Used when no key is found
     *
     * @return int the counter starting at
     *  * `0` when no $oNewObjectValueProvider is given (or null)
     *  * `$oNewObjectValueProvider() + 1` otherwise
     *
     * @throws \CoreException
     * @throws \MySQLException
     * @throws \Exception
     */
    public static function Inc($sCounterName, $oNewObjectValueProvider = null)
    {
        $sSelfClassName = self::class;
        $sMutexKeyName = "{$sSelfClassName}-{$sCounterName}";
        $oiTopMutex = new iTopMutex($sMutexKeyName);
        $oiTopMutex->Lock();

        $bIsInsideTransaction = CMDBSource::IsInsideTransaction();
        if ($bIsInsideTransaction) {
            // # Transaction isolation hack:
            // When inside a transaction, we need to open a new connection for the counter.
            // So it is visible immediately to the connections outside of the transaction.
            // Either way, the lock is not long enought, and there would be duplicate ref.
            //
            // SELECT ... FOR UPDATE would have also worked but with the cost of extra long lock (until the commit),
            // we did not wanted this! As opening a short connection is less prone to starving than a long running one.
            // Plus it would trigger way more deadlocks!
            $hDBLink = self::InitMySQLSession();
        } else {
            $hDBLink = CMDBSource::GetMysqli();
        }

        try {
            $oFilter = DBObjectSearch::FromOQL('SELECT KeyValueStore WHERE key_name=:key_name AND namespace=:namespace', array(
                'key_name' => $sCounterName,
                'namespace' => $sSelfClassName,
            ));
            $oAttDef = MetaModel::GetAttributeDef(KeyValueStore::class, 'value');
            $aAttToLoad = array(KeyValueStore::class => array('value' => $oAttDef));
            $sSql = $oFilter->MakeSelectQuery(array(), array(), $aAttToLoad);
            $hResult = mysqli_query($hDBLink, $sSql);
            $aCounter = mysqli_fetch_array($hResult, MYSQLI_NUM);
            mysqli_free_result($hResult);

            //Rebuild the filter, as the MakeSelectQuery polluted the orignal and it cannot be reused
            $oFilter = DBObjectSearch::FromOQL('SELECT KeyValueStore WHERE key_name=:key_name AND namespace=:namespace', array(
                'key_name' => $sCounterName,
                'namespace' => $sSelfClassName,
            ));

            if (is_null($aCounter)) {
                if (null != $oNewObjectValueProvider) {
                    $iComputedValue = $oNewObjectValueProvider();
                } else {
                    $iComputedValue = 0;
                }

                $iCurrentValue = $iComputedValue + 1;

                $aQueryParams = array(
                    'key_name' => $sCounterName,
                    'value' => "$iCurrentValue",
                    'namespace' => $sSelfClassName,
                );

                $sSql = $oFilter->MakeInsertQuery($aQueryParams);
            } else {
                $iCurrentValue = (int)$aCounter[1];
                $iCurrentValue++;
                $aQueryParams = array(
                    'value' => "$iCurrentValue",
                );

                $sSql = $oFilter->MakeUpdateQuery($aQueryParams);
            }

            $hResult = mysqli_query($hDBLink, $sSql);

        } catch (Exception $e) {
            IssueLog::Error($e->getMessage());
            throw $e;
        } finally {
            if ($bIsInsideTransaction) {
                mysqli_close($hDBLink);
            }
            $oiTopMutex->Unlock();
        }

        return $iCurrentValue;
    }

    /**
     * handle a counter for the root class of given $sLeafClass.
     * If no counter exist initialize it with the `max(id) + 1`
     *
     *
     *
     * @param $sLeafClass
     *
     * @return int
     * @throws \ArchivedObjectException
     * @throws \CoreCannotSaveObjectException
     * @throws \CoreException
     * @throws \CoreOqlMultipleResultsForbiddenException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
     * @throws \OQLException
     */
    public static function IncClass($sLeafClass)
    {
        $sRootClass = MetaModel::GetRootClass($sLeafClass);

        $oNewObjectCallback = function () use ($sRootClass) {
            $sRootTable = MetaModel::DBGetTable($sRootClass);
            $sIdField = MetaModel::DBGetKey($sRootClass);

            return CMDBSource::QueryToScalar("SELECT max(`$sIdField`) FROM `$sRootTable`");
        };

        return self::Inc($sRootClass, $oNewObjectCallback);
    }

    /**
     * @return \mysqli
     * @throws \ConfigException
     * @throws \CoreException
     * @throws \MySQLException
     */
    private static function InitMySQLSession()
    {
        $oConfig = utils::GetConfig();
        $sDBHost = $oConfig->Get('db_host');
        $sDBUser = $oConfig->Get('db_user');
        $sDBPwd = $oConfig->Get('db_pwd');
        $sDBName = $oConfig->Get('db_name');
        $bDBTlsEnabled = $oConfig->Get('db_tls.enabled');
        $sDBTlsCA = $oConfig->Get('db_tls.ca');

        $hDBLink = CMDBSource::GetMysqliInstance($sDBHost, $sDBUser, $sDBPwd, $sDBName, $bDBTlsEnabled, $sDBTlsCA, false);

        if (!$hDBLink) {
            throw new MySQLException('Could not connect to the DB server ' . mysqli_connect_error() . ' (mysql errno: ' . mysqli_connect_errno(), array('host' => $sDBHost, 'user' => $sDBUser));
        }

        return $hDBLink;
    }
}