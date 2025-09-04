<?php

/**
 * A
 *
 * @package     iTopORM
 */
abstract class AsyncTask extends DBObject
{
    /**
     * @throws \CoreException
     * @throws \Exception
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "core/cmdb",
            "key_type" => "autoincrement",
            "name_attcode" => array('created'),
            "state_attcode" => "",
            "reconc_keys" => array(),
            "db_table" => "priv_async_task",
            "db_key_field" => "id",
            "db_finalclass_field" => "realclass",
        );
        MetaModel::Init_Params($aParams);

        // Null is allowed to ease the migration from iTop 2.0.2 and earlier, when the status did not exist, and because the default value is not taken into account in the SQL definition
        // The value is set from null to planned in the setup program
        MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values" => new ValueSetEnum('planned,running,idle,error'), "sql" => "status", "default_value" => "planned", "is_null_allowed" => true, "depends_on" => array())));

        MetaModel::Init_AddAttribute(new AttributeDateTime("created", array("allowed_values" => null, "sql" => "created", "default_value" => "NOW()", "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeDateTime("started", array("allowed_values" => null, "sql" => "started", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeDateTime("planned", array("allowed_values" => null, "sql" => "planned", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeExternalKey("event_id", array("targetclass" => "Event", "jointype" => "", "allowed_values" => null, "sql" => "event_id", "is_null_allowed" => true, "on_target_delete" => DEL_SILENT, "depends_on" => array())));

        MetaModel::Init_AddAttribute(new AttributeInteger("remaining_retries", array("allowed_values" => null, "sql" => "remaining_retries", "default_value" => 0, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("last_error_code", array("allowed_values" => null, "sql" => "last_error_code", "default_value" => 0, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("last_error", array("allowed_values" => null, "sql" => "last_error", "default_value" => '', "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeDateTime("last_attempt", array("allowed_values" => null, "sql" => "last_attempt", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));
    }

    /**
     * Every is fine
     */
    const OK = 0;
    /**
     * The task no longer exists
     */
    const DELETED = 1;
    /**
     * The task is already being executed
     */
    const ALREADY_RUNNING = 2;

    /**
     *    The current process requests the ownership on the task.
     *    In case the task can be accessed concurrently, this function can be overloaded to add a critical section.
     *    The function must not block the caller if another process is already owning the task
     *
     * @return integer A code among OK/DELETED/ALREADY_RUNNING.
     */
    public function MarkAsRunning()
    {
        try {
            if ($this->Get('status') == 'running') {
                return self::ALREADY_RUNNING;
            } else {
                $this->Set('status', 'running');
                $this->Set('started', time());
                $this->DBUpdate();
                return self::OK;
            }
        } catch (Exception $e) {
            // Corrupted task !! (for example: "Failed to reload object")
            IssueLog::Error('Failed to process async task #' . $this->GetKey() . ' - reason: ' . $e->getMessage() . ' - fatal error, deleting the task.');
            if ($this->Get('event_id') != 0) {
                $oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
                $oEventLog->Set('message', 'Failed, corrupted data: ' . $e->getMessage());
                $oEventLog->DBUpdate();
            }
            $this->DBDelete();
            return self::DELETED;
        }
    }

    public function GetRetryDelay($iErrorCode = null)
    {
        $iRetryDelay = 600;
        $aRetries = MetaModel::GetConfig()->Get('async_task_retries');
        if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries)) {
            $aConfig = $aRetries[get_class($this)];
            $iRetryDelay = $aConfig['retry_delay'] ?? $iRetryDelay;
        }
        return $iRetryDelay;
    }

    public function GetMaxRetries($iErrorCode = null)
    {
        $iMaxRetries = 0;
        $aRetries = MetaModel::GetConfig()->Get('async_task_retries');
        if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries)) {
            $aConfig = $aRetries[get_class($this)];
            $iMaxRetries = $aConfig['max_retries'] ?? $iMaxRetries;
        }
        return $iMaxRetries;
    }

    public function IsRetryDelayExponential()
    {
        $bExponential = false;
        $aRetries = MetaModel::GetConfig()->Get('async_task_retries');
        if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries)) {
            $aConfig = $aRetries[get_class($this)];
            $bExponential = (bool)($aConfig['exponential_delay'] ?? $bExponential);
        }
        return $bExponential;
    }

    public static function CheckRetryConfig(Config $oConfig, $sAsyncTaskClass)
    {
        $aMessages = [];
        $aRetries = $oConfig->Get('async_task_retries');
        if (is_array($aRetries) && array_key_exists($sAsyncTaskClass, $aRetries)) {
            $aValidKeys = array("retry_delay", "max_retries", "exponential_delay");
            $aConfig = $aRetries[$sAsyncTaskClass];
            if (!is_array($aConfig)) {
                $aMessages[] = Dict::Format('Class:AsyncTask:InvalidConfig_Class_Keys', $sAsyncTaskClass, implode(', ', $aValidKeys));
            } else {
                foreach ($aConfig as $key => $value) {
                    if (!in_array($key, $aValidKeys)) {
                        $aMessages[] = Dict::Format('Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys', $sAsyncTaskClass, $key, implode(', ', $aValidKeys));
                    }
                }
            }
        }
        return $aMessages;
    }

    /**
     * Compute the delay to wait for the "next retry", based on the given parameters
     * @param bool $bIsExponential
     * @param int $iRetryDelay
     * @param int $iMaxRetries
     * @param int $iRemainingRetries
     * @return int
     */
    public static function GetNextRetryDelay($bIsExponential, $iRetryDelay, $iMaxRetries, $iRemainingRetries)
    {
        if ($bIsExponential) {
            $iExponent = $iMaxRetries - $iRemainingRetries;
            if ($iExponent < 0) $iExponent = 0; // Safety net in case on configuration change in the middle of retries
            return $iRetryDelay * (2 ** $iExponent);
        } else {
            return $iRetryDelay;
        }
    }

    /**
     * Override to notify people that a task cannot be performed
     */
    protected function OnDefinitiveFailure()
    {
    }

    protected function OnInsert()
    {
        $this->Set('created', time());
    }

    /**
     * @return boolean True if the task record can be deleted
     */
    public function Process()
    {
        // By default: consider that the task is not completed
        $bRet = false;

        // Attempt to take the ownership
        $iStatus = $this->MarkAsRunning();
        if ($iStatus == self::OK) {
            try {
                $sStatus = $this->DoProcess();
                if ($this->Get('event_id') != 0) {
                    $oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
                    $oEventLog->Set('message', $sStatus);
                    $oEventLog->DBUpdate();
                }
                $bRet = true;
            } catch (Exception $e) {
                $this->HandleError($e->getMessage(), $e->getCode());
            }
        } else {
            // Already done or being handled by another process... skip...
            $bRet = false;
        }

        return $bRet;
    }

    /**
     * Overridable to extend the behavior in case of error (logging)
     */
    protected function HandleError($sErrorMessage, $iErrorCode)
    {
        if ($this->Get('last_attempt') == '') {
            // First attempt
            $this->Set('remaining_retries', $this->GetMaxRetries($iErrorCode));
        }

        $this->SetTrim('last_error', $sErrorMessage);
        $this->Set('last_error_code', $iErrorCode); // Note: can be ZERO !!!
        $this->Set('last_attempt', time());

        $iRemaining = $this->Get('remaining_retries');
        if ($iRemaining > 0) {
            $iRetryDelay = $this->GetRetryDelay($iErrorCode);
            $iNextRetryDelay = static::GetNextRetryDelay($this->IsRetryDelayExponential(), $iRetryDelay, $this->GetMaxRetries($iErrorCode), $iRemaining);
            IssueLog::Info('Failed to process async task #' . $this->GetKey() . ' - reason: ' . $sErrorMessage . ' - remaining retries: ' . $iRemaining . ' - next retry in ' . $iNextRetryDelay . 's');
            if ($this->Get('event_id') != 0) {
                $oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
                $oEventLog->Set('message', "$sErrorMessage\nFailed to process async task. Remaining retries: $iRemaining. Next retry in {$iNextRetryDelay}s");
                try {
                    $oEventLog->DBUpdate();
                } catch (Exception $e) {
                    $oEventLog->Set('message', "Failed to process async task. Remaining retries: $iRemaining. Next retry in {$iNextRetryDelay}s, more details in the log");
                    $oEventLog->DBUpdate();
                }
            }
            $this->Set('remaining_retries', $iRemaining - 1);
            $this->Set('status', 'planned');
            $this->Set('started', null);
            $this->Set('planned', time() + $iNextRetryDelay);
        } else {
            IssueLog::Error('Failed to process async task #' . $this->GetKey() . ' - reason: ' . $sErrorMessage);
            if ($this->Get('event_id') != 0) {
                $oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
                $oEventLog->Set('message', "$sErrorMessage\nFailed to process async task.");
                try {
                    $oEventLog->DBUpdate();
                } catch (Exception $e) {
                    $oEventLog->Set('message', 'Failed to process async task, more details in the log');
                    $oEventLog->DBUpdate();
                }
            }
            $this->Set('status', 'error');
            $this->Set('started', null);
            $this->Set('planned', null);
            $this->OnDefinitiveFailure();
        }
        $this->DBUpdate();
    }

    /**
     * Throws an exception (message and code)
     *
     * @return string
     */
    abstract public function DoProcess();

    /**
     * Describes the error codes that DoProcess can return by the mean of exceptions
     */
    static public function EnumErrorCodes()
    {
        return array();
    }
}