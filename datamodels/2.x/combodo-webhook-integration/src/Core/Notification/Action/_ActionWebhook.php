<?php

namespace Combodo\iTop\Core\Notification\Action;

use ActionNotification;
use ApplicationContext;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Core\WebResponse;
use Combodo\iTop\Service\CallbackService;
use Combodo\iTop\Service\WebRequestSender;
use DBObject;
use EventWebhook;
use Exception;
use IssueLog;
use MetaModel;
use ReflectionException;
use UserRights;
use utils;

abstract class _ActionWebhook extends ActionNotification
{
	/** @var array $aRequestErrors Errors occurring during the execution */
	protected $aRequestErrors;

	/**
	 * Default WebResponse handler for a WebRequest made by an _ActionWebhook
	 *
	 * @param \Combodo\iTop\Core\WebResponse $oResponse
	 * @param array{
	 *     oActionWebhook: array{class: string, id: int},
	 *     oTriggeringObject: \DBObject|array{class: string, id: int},
	 * } $aParams Parameters of the handler, must contain at least the triggering object and activated webhook action information
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws ReflectionException
	 * @throws \Exception
	 */
	public static function ExecuteResponseHandler(WebResponse $oResponse, array $aParams)
	{
		// Retrieve objects from params
		if (!array_key_exists('oTriggeringObject', $aParams) || !array_key_exists('oActionWebhook', $aParams)) {
			IssueLog::Error('Missing parameters in response handler. Expecting at least oTriggeringObject and oActionWebhook', 'console', [
				'oResponse' => $oResponse,
				'aParams' => $aParams,
			]);
			throw new Exception('Missing parameters in response handler. See error log for details.');
		}
		/** @var DBObject $oTriggeringObject */
		$oTriggeringObject = is_object($aParams['oTriggeringObject']) ?
			$aParams['oTriggeringObject'] :
			MetaModel::GetObject($aParams['oTriggeringObject']['class'], $aParams['oTriggeringObject']['id'], true, true);
		$oActionWebhook = MetaModel::GetObject($aParams['oActionWebhook']['class'], $aParams['oActionWebhook']['id'], true, true);
        $sResponseCallback = $oActionWebhook->Get('process_response_callback');
        if (!empty($sResponseCallback)) {
            $oCallBack = new CallbackService($sResponseCallback);
            $oCallBack->CheckCallbackSignature(get_class($oTriggeringObject), [WebResponse::class, \ActionWebhook::class]);
            $oCallBack->Invoke($oTriggeringObject, [$oResponse, $oActionWebhook]);
        }
	}

	/**
	 * @inheritDoc
	 * @since 1.4.0
	 */
	public static function GetAsynchronousGlobalSetting(): bool
	{
		$oConfig = utils::GetConfig();
		return $oConfig->GetModuleSetting('combodo-webhook-integration', 'prefer_asynchronous', (WebRequestSender::DEFAULT_SEND_MODE === WebRequestSender::ENUM_SEND_MODE_ASYNC));
	}

	/**
	 * @inheritDoc
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DoExecute($oTrigger, $aContextArgs)
	{
		// Create notification object to log status information if enabled
		if (MetaModel::IsLogEnabledNotification())
		{
			$oLog = new EventWebhook();
			if ($this->IsBeingTested())
			{
				$oLog->Set('message', 'TEST - Notification pending');
			}
			else
			{
				$oLog->Set('message', 'Notification pending');
			}
			$oLog->Set('userinfo', UserRights::GetUser());
			$oLog->Set('action_finalclass', $this->Get('finalclass'));
			$oLog->Set('trigger_id', $oTrigger->GetKey());
			$oLog->Set('action_id', $this->GetKey());
			$oLog->Set('object_id', $aContextArgs ['this->object()']->GetKey());
			$oLog->DBInsertNoReload();
		}
		else
		{
			$oLog = null;
		}

		try
		{
			// Execute Request
			$sRes = $this->_DoExecute($oTrigger, $aContextArgs, $oLog);

			// Logging Feedback
			if ($oLog)
			{
				$sPrefix = ($this->IsBeingTested()) ? 'TEST - ' : '';
				$oLog->Set('message', $sPrefix.$sRes);
			}

		}
		catch (Exception $oException)
		{
			if ($oLog)
			{
				$oLog->Set('message', 'Error: '.$oException->getMessage());
			}
		}
		if ($oLog)
		{
			$oLog->DBUpdate();
		}
	}

	/**
	 * Do the execution itself
	 *
	 * @param \Trigger           $oTrigger TriggerObject which called the action
	 * @param array              $aContextArgs
	 * @param \EventNotification $oLog     Reference to the Log Object for store information in EventNotification
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function _DoExecute($oTrigger, $aContextArgs, &$oLog)
	{
		$sPreviousUrlMaker = ApplicationContext::SetUrlMakerClass();
		try
		{
			$this->aRequestErrors = array();

			$sActionClass = get_called_class();
			/** @var \DBObject $oTriggeringObject */
			$oTriggeringObject = $aContextArgs['this->object()'];

			$oRequest = $this->PrepareWebRequest($aContextArgs, $oLog);

			// Set default response handler if not already defined (this also custom ActionWebhook classes to define their own)
			if ($oRequest->HasResponseHandler() === false) {
                $aHandlerParams = [
                    'oActionWebhook' => [
                        'class' => $sActionClass,
                        'id' => $this->GetKey(),
                    ],
                ];
                
                if ($this->IsAsynchronous()) {
                    $aHandlerParams['oTriggeringObject'] = [
                        'class' => get_class($oTriggeringObject),
                        'id' => $oTriggeringObject->GetKey(),
                    ];
                } else {
                    $aHandlerParams['oTriggeringObject'] = $oTriggeringObject;
                }
                
				$oRequest->SetResponseHandlerName($sActionClass.'::ExecuteResponseHandler')
				->SetResponseHandlerParams($aHandlerParams);
			}

			// Errors during preparation
			if (!empty($this->m_aWebrequestErrors))
			{
				return 'Errors: '.implode(', ', $this->m_aWebrequestErrors);
			}

			if ($this->IsBeingTested() && empty($oRequest->GetURL()))
			{
				return 'Not sent as there was no test webhook URL defined';
			}

			// Prepare "send mode"
			$sSendMode = $this->IsAsynchronous() ? WebRequestSender::ENUM_SEND_MODE_ASYNC : WebRequestSender::ENUM_SEND_MODE_SYNC;

			$oSender = WebRequestSender::GetInstance();
			$aResult = $oSender->Send($oRequest, $this->aRequestErrors, $oLog, $sSendMode);

			switch ($aResult['sender_status'])
			{
				case WebRequestSender::ENUM_SEND_STATE_OK:
					return 'Sent';

				case WebRequestSender::ENUM_SEND_STATE_PENDING:
					return 'Pending';

				case WebRequestSender::ENUM_SEND_STATE_ERROR;
					return 'Errors: '.implode(', ', $this->aRequestErrors);
			}
		}
		catch (Exception $oException)
		{
			ApplicationContext::SetUrlMakerClass($sPreviousUrlMaker);
			throw $oException;
		}
		ApplicationContext::SetUrlMakerClass($sPreviousUrlMaker);

		return 'Bug: Unknown behavior, check the event notification log.';
	}

	/**
	 * @param array $aContextArgs
	 * @param \EventNotification $oLog
	 *
	 * @return \Combodo\iTop\Core\WebRequest Prepare and return the WebRequest to be sent
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	abstract protected function PrepareWebRequest(array $aContextArgs, \EventNotification &$oLog);

	/**
	 * @param string $sJson
	 *
	 * @return string|array|boolean|null decoded value
	 * @throws WebhookInvalidJsonValueException if error on decode
	 *
	 * @uses json_decode
	 * @uses json_last_error
	 *
	 * @link https://www.json.org/json-en.html JSON string can contain an object (associative array),
	 *              but also "null", or boolean ("true" or "false") or any other string values (for example "foo")
	 * @link https://www.php.net/manual/en/function.json-last-error.php for JSON error codes
	 *
	 * @since 1.3.2 N°6647 more generic method to check JSON validity
	 */
	public static function JsonDecodeWithError($sJson)
	{
		if (false === is_string($sJson)) {
			throw new WebhookInvalidJsonValueException('Input JSON is not a string', [
				'value' => $sJson,
			]);
		}

		$decodedValue = json_decode($sJson, true);
		$decodeError = json_last_error();
		if ($decodeError !== JSON_ERROR_NONE) {
			throw new WebhookInvalidJsonValueException('Invalid JSON format', [
				'value' => $sJson,
				'json_decode.error' => $decodeError,
				'json_decode.errormessage' => json_last_error_msg(),
			]);
		}

		return $decodedValue;
	}
}
